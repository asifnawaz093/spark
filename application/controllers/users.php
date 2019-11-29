<?php
class users implements IController {
    public function main() {
		$view = new View();
		$fc = FC::getInstance();
		$db = FC::getClassInstance("Db");
		$session = FC::getClassInstance("Session");
		$session->session();
		global $globals;
        $word = strtolower(Tools::getValue("type", "drivers"));
		$filter = FC::getClassInstance("Filter");
		$filter->num        = 100;
		$page               = (isset($_GET['p']) && !empty($_GET['p'])) ? $_GET['p'] : 1;
		$filter->from       = ($page - 1) * $filter->num;
		$view->from	    = $filter->from + 1;
		$filter->seachLabel = "ID, First Name, Last Name, Email";
		$filter->seperate_pagination = true;
		$filter->filters    = array(
			"search"    => array("`id`","`first_name`","last_name", "`ad_email`"),
			"sort"      => array(
				"id:desc"    	=> "Latest First",
				"id:asc"     	=> "Oldest First",
				"first_name:asc" 	=> "Name (A-Z)",
				"last_name:desc" => "Name (Z-A)",
				),
			"filters"   => array("Select Status"=>array("status"=>array("0"=>"Pending Email Verification", "1"=>"Pending Approval","2"=>"Active","3"=>"Suspended"))),
			"pagination"	=> array("pageLink"=>SITEURL."users/")
			);
		$filter->select     = "SELECT *, CONCAT(first_name, ' ', last_name) as name FROM user_pro";
        $filter->where      = "acc_typ = ". $globals['acc_typ'][$word];
		$filter->params	    	= array("type"=>"$word");
		$filter->order 			= "order by id desc";
		$view->filter       	= $filter->createFilter();
		$view->pagination   	= $filter->pagination;
		$query              	= $filter->getQuery();
		$rows 					= $db->getRows($query);
		$builder					= FC::getClass("Builder");
		$builder->table				= "user_pro";
		if(Tools::isSubmit("multi_delete")){
			$multi_ids = Tools::getArray("multi");
			if(count($multi_ids) > 0){
				$builder->multiDelete($multi_ids);
			}else{
				$fc->error = "Error. Please select ".$word."s you want to delete.";
				Tools::redirect();
			}
		}
		$builder->link 				= SITEURL."users/?type=$word";
		$builder->log_title			= ucfirst($word).": ";
		$builder->links->view		= SITEURL."users/?action=profile";
		$builder->links->edit		= SITEURL . "users/?action=add&type=$word";
        $builder->actions 			= ["view","edit","delete"];
		$builder->auto 				= ["delete"];
        $builder->columns 			= array("name"=>"Name", "ad_email"=>"Email", "phone"=>"Phone", "city"=>"City", "date_added"=>"Date Registered");
		$view->table 				= $builder->getTable($rows);
		if(!$rows){$fc->error = "No $word found."; }
		$view->word = $word;
        $view->page_title           = ucfirst($word). "s";
		$result 					= $view->render('../views/users/list.php');
		$fc->setBody($result);
	}
     public function add() {
		$view = new View();
		$fc = FC::getInstance();
		$db = FC::getClassInstance("Db");
		$session = FC::getClassInstance("Session");
		$session->session();
        global $globals;
		$builder = FC::getClass("Builder");
        $word = strtolower(Tools::getValue("type","client"));
		$view->profile=false;
		if(Tools::isSubmit("edit") && Tools::getValue("id")){
			$id_user = Tools::getValue("id");
			$view->profile = $db->getRow("SELECT * FROM user_pro WHERE id = '$id_user'");
            $user_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN vmeta_user b ON a.id = b.id_meta WHERE b.id_user = '$id_user'","name","value");
			if($user_meta) $view->profile = array_merge($view->profile, $user_meta);
		}
		$custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='$word'");
		$companies = $db->getNameValue("SELECT id, name FROM companies order by name ASC");
		if(Tools::isSubmit("submit")){
			$data=[];
			if($_FILES["image"]['size']>0){
				$Image = FC::getClass("EasyImage", $_FILES["image"]);
				$img = $Image->processImgUpload();
				$image_url = $img['imgUrl'];
			}else{
				$image_url = $view->profile['profile_picture'];
			}
            $data['acc_typ'] = $globals['acc_typ'][$word];
			$data['first_name'] = Tools::getValue("first_name");
			$data['last_name'] = Tools::getValue("last_name");
			$data['ad_email'] = Tools::getValue("ad_email");
			$data['ad_pwd'] = hashing(Tools::getValue("ad_pwd"));
			$data['phone'] = Tools::getValue("phone");
			$data['address'] = Tools::getValue("address");
			$data['city'] = Tools::getValue("city");
			$data['state'] = Tools::getValue("state");
			$data['company'] = Tools::getValue("company");
            $data['comission'] = Tools::getValue("comission");
			$data['profile_picture'] = $image_url;
			if(Tools::isSubmit("edit")){
				if($db->update(array("user_pro"=>$data), "WHERE id = '$id_user'",false)){
					$fc->success = "Profile update successful";
                    FC::getClass("Log")->add("Profile ID: $id_user updated");
				}else{
					$fc->error = "Some error occured.";
				}
			}else{
				if($id_user = $db->insert(array("user_pro"=>$data))){
					$fc->success = "New $word created successfully"; FC::getClass("Log")->add("New $word account created. Profile ID: $id_insert");
				}else{
					$fc->error = "Some error occured.";
				}
			}
		
			FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id_user,"vmeta_user","id_user");
			Tools::redirect();
		}
		$builder->form_attribute = "enctype='multipart/form-data'";
		$builder->form_fields = array(
				["name"=>"first_name", "label"=>"First Name"],["name"=>"last_name", "label"=>"Last Name"],
                ["name"=>"ad_email", "label"=>"Email Address"],["name"=>"ad_pwd", "label"=>"Password", "type"=>"password"],
                ["name"=>"phone", "label"=>"Phone Number"],
                ["name"=>"address", "label"=>"Address"],["name"=>"city", "label"=>"City"],["name"=>"state", "label"=>"State"],
                ["name"=>"comission", "label"=>"Comission Rate"],
                ["name"=>"company", "type"=>"select", "label"=>"Insurance Company", "options"=>$companies, "selected"=>"0"],
				["name"=>"image", "label"=>"Profile Picture", "type"=>"file"],
			);
		$builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
		$view->form = $builder->addForm(false, $view->profile);
        $view->page_title = "Add New $word";
		$view->word = $word;
		$result = $view->render('../views/users/add.php');
		$fc->setBody($result);
	}
	public function profile() {
		$view = new View();
		$fc = FC::getInstance();
		$db = FC::getClassInstance("Db");
		$session = FC::getClassInstance("Session");
		$session->session();
		global $globals;
		$id = Tools::getValue("id");
		if($id){
			$view->profile = $db->getRow("SELECT * FROM user_pro WHERE id = '$id'");
			if(!$view->profile){$fc->error = "No information available. ";}
			else{
				$view->profile['meta'] = $db->getRows("SELECT a.name ,b.value from custom_meta a LEFT JOIN vmeta_user b ON a.id = b.id_meta WHERE b.id_user = '$id'");
				$view->profile['word'] = $globals['acc_typ_word'][$view->profile['acc_typ']];
				$view->profile['user_status'] = $globals['userStatus'][$view->profile['status']];
			}
		}else{
			$fc->error= "invalid parameter";
		}
		$result = $view->render('../views/users/profile.php');
		$fc->setBody($result);
	}
}