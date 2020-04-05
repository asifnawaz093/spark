<?php
class title implements IController {
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
        $law = $db->getNameValue("SELECT `id`,`law` as 'name' FROM `law`");
        $filter->seperate_pagination = true;
        $filter->filters    = array(
            "search"    => array("title"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."title/")
        );
        $filter->select     = "SELECT * FROM title";
        $filter->order 			= "order by id desc";
        $filter->filters['filters']['Select LAW'] = array("id_law" => $law);
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "title";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select title you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."title/?action=main";
        $builder->log_title			= "title: ";
        $builder->links->view		= SITEURL."title/?action=detail";
        $builder->links->edit		= SITEURL . "title/?action=add";
        $builder->actions 			= ["edit","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array("title"=>"title", "id_law"=>["label"=>"LAW", "function"=>"getlawname"],);
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No case found. <a href='".SITEURL."title/?action=add' class='btn btn-primary'>Add New case</a>"; }
        $result 					= $view->render('../views/title/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->title =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->title = $db->getRow("SELECT * FROM title WHERE id = '$id'");
            $title_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN title_meta b ON a.id = b.id_meta WHERE b.id_title = '$id'","name","value");
            if($title_meta) $view->title = array_merge($view->title, $title_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='title'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['title'] = Tools::getValue("title");
            $data['id_law']=Tools::getValue('id_law');
            if(Tools::isSubmit("edit")){
                if($db->update(array("title"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "title information updated successfully";
                    FC::getClass("Log")->add("title ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("title"=>$data))){
                    $fc->success = "New title created successfully"; FC::getClass("Log")->add("New title information created. title ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"title_meta","id_title");
            // Tools::redirect();
        }
        $getlaw=$db->getRows("SELECT `id`, `law` FROM `law`");
        $lawdrop=[];
        foreach($getlaw as $law){
            $lawdrop[$law['id']]=$law['law'];
        }
        $builder->form_attribute = "enctype='multipart/form-data'";
        $builder->form_fields = array(
            ["name"=>"id_law", "label"=>"LAW", "type"=>"select","options"=>$lawdrop],
            ["name"=>"title", "label"=>"Title"],
            );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->title);
        $view->page_title = "Add New Title";
        $result = $view->render('../views/title/add.php');
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
            $view->title = $db->getRow("SELECT * FROM title WHERE id = '$id'");
            $view->law=getlawname($view->title['id']);
            if(!$view->title){$fc->error = "No information available. ";}
            else{
                $view->title['meta'] = FC::getClass("Settings")->getCustomMeta("title_meta","id_title",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/title/detail.php');
        $fc->setBody($result);
    }
}
function getlawname($law_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `law` FROM `law` WHERE `id`='$law_id'");
    return $name;
}