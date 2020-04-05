<?php
class law implements IController {
    public function main()
    {
        /////
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClass("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        global $globals;
        $filter = FC::getClassInstance("Filter");
        $filter->num        = 100;
        $page               = (isset($_GET['p']) && !empty($_GET['p'])) ? $_GET['p'] : 1;
        $filter->from       = ($page - 1) * $filter->num;
        $view->from	    = $filter->from + 1;
        $filter->seachLabel = "Name";
        $filter->seperate_pagination = true;
        $filter->filters    = array(
            "search"    => array("law"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."law/")
        );
        $filter->select     = "SELECT * FROM law";
        $filter->order 			= "order by id desc";
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "law";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select law you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."law/?action=main";
        $builder->log_title			= "law: ";
        $builder->links->view		= SITEURL."law/?action=detail";
        $builder->links->edit		= SITEURL . "law/?action=add";
        $builder->actions 			= ["view","edit","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array("law"=>"LAW",);
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No case found. <a href='".SITEURL."law/?action=add' class='btn btn-primary'>Add New case</a>"; }
        $result 					= $view->render('../views/law/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->law =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->law = $db->getRow("SELECT * FROM law WHERE id = '$id'");
            $law_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN law_meta b ON a.id = b.id_meta WHERE b.id_law = '$id'","name","value");
            if($law_meta) $view->law = array_merge($view->law, $law_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='law'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['law'] = Tools::getValue("law");

            if(Tools::isSubmit("edit")){
                if($db->update(array("law"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "law information updated successfully";
                    FC::getClass("Log")->add("law ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("law"=>$data))){
                    $fc->success = "New law created successfully"; FC::getClass("Log")->add("New law information created. law ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"law_meta","id_law");
            // Tools::redirect();
        }
        $builder->form_attribute = "enctype='multipart/form-data'";
        $builder->form_fields = array(
           ["name"=>"law", "label"=>"LAW"],
//            ["name"=>"status","label"=>"Account Status", "type"=>"select","options"=>["0"=>"Pending","1"=>"InActive","2"=>"Active"]]
        );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->law);
        $view->page_title = "Add New law";
        $result = $view->render('../views/law/add.php');
        $fc->setBody($result);
    }
    public function detail() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $id = Tools::getValue("id");
        if($id){
            $view->law = $db->getRow("SELECT * FROM law WHERE id = '$id'");
            if(!$view->law){$fc->error = "No information available. ";}
            else{
                $view->law['meta'] = FC::getClass("Settings")->getCustomMeta("law_meta","id_law",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/law/detail.php');
        $fc->setBody($result);
    }
}