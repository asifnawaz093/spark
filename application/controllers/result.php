<?php
class result implements IController {
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
        $nature = $db->getNameValue("SELECT `id`,`nature` as 'name' FROM `nature`");
        $filter->seperate_pagination = true;
        $filter->filters    = array(
            "search"    => array("result"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."result/")
        );
        $filter->select     = "SELECT * FROM result";
        $filter->order 			= "order by id desc";
        $filter->filters['filters']['Select LAW'] = array("id_law" => $law);
        $filter->filters['filters']['Select Nature'] = array("id_nature" => $nature);
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "result";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select result you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."result/?action=main";
        $builder->log_title			= "result: ";
        $builder->links->view		= SITEURL."result/?action=detail";
        $builder->links->edit		= SITEURL . "result/?action=add";
        $builder->actions 			= ["edit","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array( "id_law"=>["label"=>"LAW", "function"=>"getlawname"],"id_nature"=>["label"=>"Nature", "function"=>"getnaturename"],"result"=>"Result");
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No record found. <a href='".SITEURL."result/?action=add' class='btn btn-primary'>Add New case</a>"; }
        $result 					= $view->render('../views/result/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->result =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->result = $db->getRow("SELECT * FROM result WHERE id = '$id'");
            $result_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN result_meta b ON a.id = b.id_meta WHERE b.id_result = '$id'","name","value");
            if($result_meta) $view->result = array_merge($view->result, $result_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='result'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['result'] = Tools::getValue("result");
            $data['id_law']=Tools::getValue('id_law');
                 $data['id_nature']=Tools::getValue('id_nature');

            if(Tools::isSubmit("edit")){
                if($db->update(array("result"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "result information updated successfully";
                    FC::getClass("Log")->add("result ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("result"=>$data))){
                    $fc->success = "New result created successfully"; FC::getClass("Log")->add("New result information created. result ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"result_meta","id_result");
            // Tools::redirect();
        }

        $getlaw=$db->getRows("SELECT `id`, `law` FROM `law`");
        $lawdrop=[];
        $lawdrop[0]="Select One";
        foreach($getlaw as $law){
            $lawdrop[$law['id']]=$law['law'];
        }

        $builder->form_attribute = "enctype='multipart/form-data'";
        $builder->form_fields = array(
            //["name"=>"id_law", "label"=>"LAW", "type"=>"select","options"=>$lawdrop, "attributes" => "onchange='getsection()' required"],
            ["name" => "id_law", "id" => "id_law", "label" => "Section", "type" => "select", "options" => $lawdrop, "class='form-control nodidsplay'", "attributes"=>"onchange='getnaturelist()' required"],
            ["name"=>"id_nature", "label"=>"Nature","type" => "select", "options" => [],"attributes" => "multipel=true", "class='form-control nodidsplay'", "attributes" =>"required"],
            ["name"=>"result", "label"=>"Result",],
        );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->result);
        $view->page_title = "Add New result";
        $result = $view->render('../views/result/add.php');
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
            $view->result = $q=$db->getRow("SELECT * FROM result WHERE id = '$id'");
            $view->law=$db->getRow("SELECT `law` FROM `law` WHERE `id`='$q[id_law]'");
            $view->nature=$db->getRow("SELECT `nature` FROM `nature` WHERE `id`='$q[id_nature]'");
            if(!$view->result){$fc->error = "No information available. ";}
            else{
                $view->result['meta'] = FC::getClass("Settings")->getCustomMeta("result_meta","id_result",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/result/detail.php');
        $fc->setBody($result);
    }
    public function getsection()
    {
        $db = FC::getClass("Db");
        $value = Tools::getValue("value");

        if($value) {
            $data = $db->getNameValue("SELECT `id`,  `section` as 'name' FROM `section` WHERE `id_law`= '$value'","id","name");

            if ($data) {
                echo "<option value=''>Select one</option>";
                foreach ($data as $id => $name) {
                    echo "<option value='{$id}'>$name</option>";
                }
            }
        }
    }
    public function getnaturelist()
    {
        $db = FC::getClass("Db");
        $value = Tools::getValue("value");
        if($value) {
            $data = $db->getNameValue("SELECT `id`,  `nature` as 'name' FROM `nature` WHERE `id_law`= '$value'","id","name");

            if ($data) {
                echo "<option value=''>Select one</option>";
                foreach ($data as $id => $name) {
                    echo "<option value='{$id}'>$name</option>";
                }
            }
        }
    }
}
function getlawname($law_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `law` FROM `law` WHERE `id`='$law_id'");
    return $name;
}

function getnaturename($nature_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `nature` FROM `nature` WHERE `id`='$nature_id'");
    return $name;
}

