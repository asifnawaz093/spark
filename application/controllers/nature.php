<?php
class nature implements IController {
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
            "search"    => array("nature"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."nature/")
        );
        $filter->select     = "SELECT * FROM nature";
        $filter->order 			= "order by id desc";
        $filter->filters['filters']['Select LAW'] = array("id_law" => $law);
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "nature";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select nature you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."nature/?action=main";
        $builder->log_title			= "nature: ";
        $builder->links->view		= SITEURL."nature/?action=detail";
        $builder->links->edit		= SITEURL . "nature/?action=add";
        $builder->actions 			= ["edit","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array("nature"=>"nature", "id_law"=>["label"=>"LAW", "function"=>"getlawname"],);
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No record found. <a href='".SITEURL."nature/?action=add' class='btn btn-primary'>Add New Nature</a>"; }
        $result 					= $view->render('../views/nature/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->nature =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->nature = $db->getRow("SELECT * FROM nature WHERE id = '$id'");
            $nature_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN nature_meta b ON a.id = b.id_meta WHERE b.id_nature = '$id'","name","value");
            if($nature_meta) $view->nature = array_merge($view->nature, $nature_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='nature'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['nature'] = Tools::getValue("nature");
            $data['id_law']=Tools::getValue('id_law');

            if(Tools::isSubmit("edit")){
                if($db->update(array("nature"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "nature information updated successfully";
                    FC::getClass("Log")->add("nature ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("nature"=>$data))){
                    $fc->success = "New nature created successfully"; FC::getClass("Log")->add("New nature information created. nature ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"nature_meta","id_nature");
            // Tools::redirect();
        }
        $getlaw=$db->getRows("SELECT `id`, `law` FROM `law`");
        $lawdrop=[];
        foreach($getlaw as $law){
            $lawdrop[$law['id']]=$law['law'];
        }
        $getlaw=$db->getRows("SELECT `id`, `law` FROM `law`");
        $lawdrop=[];
        foreach($getlaw as $law){
            $lawdrop[$law['id']]=$law['law'];
        }
        $builder->form_attribute = "enctype='multipart/form-data'";
        $builder->form_fields = array(
            ["name"=>"id_law", "label"=>"LAW", "type"=>"select","options"=>$lawdrop,],
            ["name"=>"nature", "label"=>"Nature"],
        );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->nature);
        $view->page_title = "Add New nature";
        $result = $view->render('../views/nature/add.php');
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
            $view->nature = $q=$db->getRow("SELECT * FROM nature WHERE id = '$id'");
            $view->law=$db->getRow("SELECT `law` FROM `law` WHERE `id`='$q[id_law]'");

            if(!$view->nature){$fc->error = "No information available. ";}
            else{
                $view->nature['meta'] = FC::getClass("Settings")->getCustomMeta("nature_meta","id_nature",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/nature/detail.php');
        $fc->setBody($result);
    }
}
function getlawname($law_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `law` FROM `law` WHERE `id`='$law_id'");
    return $name;
}
