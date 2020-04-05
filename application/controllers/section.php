<?php
class section implements IController {
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
            "search"    => array("section"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."section/")
        );
        $filter->select     = "SELECT * FROM section";
        $filter->order 			= "order by id desc";
        $filter->filters['filters']['Select LAW'] = array("id_law" => $law);
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "section";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select section you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."section/?action=main";
        $builder->log_title			= "section: ";
        $builder->links->view		= SITEURL."section/?action=detail";
        $builder->links->edit		= SITEURL . "section/?action=add";
        $builder->actions 			= ["edit","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array( "section"=>"Section", "id_law"=>["label"=>"LAW", "function"=>"getlawname"],);
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No case found. <a href='".SITEURL."section/?action=add' class='btn btn-primary'>Add New case</a>"; }
        $result 					= $view->render('../views/section/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->section =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->section = $db->getRow("SELECT * FROM section WHERE id = '$id'");
            $section_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN section_meta b ON a.id = b.id_meta WHERE b.id_section = '$id'","name","value");
            if($section_meta) $view->section = array_merge($view->section, $section_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='section'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['section'] = Tools::getValue("section");
            $data['id_law']=Tools::getValue('id_law');
            if(Tools::isSubmit("edit")){
                if($db->update(array("section"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "section information updated successfully";
                    FC::getClass("Log")->add("section ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("section"=>$data))){
                    $fc->success = "New section created successfully"; FC::getClass("Log")->add("New section information created. section ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"section_meta","id_section");
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
            ["name"=>"section", "label"=>"Section"],
            );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->section);
        $view->page_title = "Add New section";
        $result = $view->render('../views/section/add.php');
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
            $view->section=$q = $db->getRow("SELECT * FROM section WHERE id = '$id'");
           // $view->law=getlawname($view->section['id_law']);
            $view->law=$db->getRow("SELECT `law` FROM `law` WHERE `id`='$q[id_law]'");
            if(!$view->section){$fc->error = "No information available. ";}
            else{
                $view->section['meta'] = FC::getClass("Settings")->getCustomMeta("section_meta","id_section",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/section/detail.php');
        $fc->setBody($result);
    }
}
function getlawname($law_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `law` FROM `law` WHERE `id`='$law_id'");
    return $name;
}