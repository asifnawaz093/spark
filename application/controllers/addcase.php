<?php
class addcase implements IController {
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
        $filter->seachLabel = "ID, Name";
        $law = $db->getNameValue("SELECT `id`,`law` as 'name' FROM `law`");
        $title = $db->getNameValue("SELECT `id`,`title` as 'name' FROM `title`");
        $section = $db->getNameValue("SELECT `id`,`section` as 'name' FROM `section`");
        $nature = $db->getNameValue("SELECT `id`,`nature` as 'name' FROM `nature`");
        $result = $db->getNameValue("SELECT `id`,`result` as 'name' FROM `result`");
        $filter->seperate_pagination = true;
        $filter->filters    = array(
            "search"    => array("`id`","name"),
            "sort"      => array(
                "id:desc"    	=> "Latest First",
                "id:asc"     	=> "Oldest First",
                "name:asc" 	=> "Name (A-Z)",
                "name:desc" => "Name (Z-A)",
            ),
            "pagination"	=> array("pageLink"=>SITEURL."addcase/")
        );
        $filter->select     = "SELECT * FROM addcase";
        $filter->order 			= "order by id desc";
        $filter->filters['filters']['Select LAW'] = array("id_law" => $law);
        $filter->filters['filters']['Select Title'] = array("id_title" => $title);
        $filter->filters['filters']['Select Section'] = array("id_section" => $section);
        $filter->filters['filters']['Select Nature'] = array("id_nature" => $nature);
        $filter->filters['filters']['Select Result'] = array("id_law" => $result);
        $view->filter       	= $filter->createFilter();
        $view->pagination   	= $filter->pagination;
        $query              	= $filter->getQuery();
        $rows 					= $db->getRows($query);
        $builder					= FC::getClass("Builder");
        $builder->table				= "addcase";
        if(Tools::isSubmit("multi_delete")){
            $multi_ids = Tools::getArray("multi");
            if(count($multi_ids) > 0){
                $builder->multiDelete($multi_ids);
            }else{
                $fc->error = "Error. Please select addcase you want to delete.";
                Tools::redirect();
            }
        }
        $builder->link 				= SITEURL."addcase/?action=main";
        $builder->log_title			= "addcase: ";
        $builder->links->view		= SITEURL."addcase/?action=detail";
        $builder->links->edit		= SITEURL . "addcase/?action=add";
        $builder->actions 			= ["view","delete"];
        $builder->auto 				= ["delete"];
        $builder->columns 			=  array( "id_law"=>["label"=>"LAW", "function"=>"getlawname"],"id_title"=>["label"=>"Title", "function"=>"gettitlename"],"id_section"=>["label"=>"Section", "function"=>"getsectionname"],"id_nature"=>["label"=>"Nature", "function"=>"getnaturename"],"id_result"=>["label"=>"Result", "function"=>"getresultname"], "date_added"=>["label"=>"Date", "function"=>"dateformat"]);
        $view->table 				= $builder->getTable($rows);
        if(!$rows){$fc->error = "No record found. <a href='".SITEURL."addcase/?action=add' class='btn btn-primary'>Add New case</a>"; }
        $result 					= $view->render('../views/addcase/list.php');
        $fc->setBody($result);
    }
    public function add() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        $builder = FC::getClass("Builder");
        $view->addcase =false;
        if(Tools::isSubmit("edit") && Tools::getValue("id")){
            $id = Tools::getValue("id");
            $view->addcase = $db->getRow("SELECT * FROM addcase WHERE id = '$id'");
            $addcase_meta = $db->getNameValue("SELECT CONCAT('cm_',a.id) name ,b.value from custom_meta a LEFT JOIN addcase_meta b ON a.id = b.id_meta WHERE b.id_addcase = '$id'","name","value");
            if($addcase_meta) $view->addcase = array_merge($view->addcase, $addcase_meta);
        }
        $custom_meta = $db->getRows("SELECT * FROM custom_meta WHERE form='addcase'");
        if(Tools::isSubmit("submit") ){
            $data=[];
            $data['details'] = Tools::getValue("details");
            $data['id_law']=Tools::getValue('id_law');
            $data['id_title']=Tools::getValue('id_title');
            $data['id_section']=Tools::getValue('id_section');
            $data['id_nature']=Tools::getValue('id_nature');
            $data['id_result']=Tools::getValue('id_result');
            if(Tools::isSubmit("edit")){
                if($db->update(array("addcase"=>$data), "WHERE id = '$id'",false)){
                    $fc->success = "addcase information updated successfully";
                    FC::getClass("Log")->add("addcase ID: $id updated");
                }else{
                    $fc->error = "Some error occured.";
                }
            }else{
                if($id = $db->insert(array("addcase"=>$data))){
                    $fc->success = "New addcase created successfully"; FC::getClass("Log")->add("New addcase information created. addcase ID: $id");
                }else{
                    $fc->error = "Some error occured.";
                }
            }
            FC::getClass("Settings")->saveCustomFormFields($custom_meta,$id,"addcase_meta","id_addcase");
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
            ["name"=>"id_law", "label"=>"LAW", "type"=>"select","options"=>$lawdrop, "attributes" => "onchange='getdata()' required"],
            ["name" => "id_title", "id" => "id_title", "label" => "Title", "type" => "select", "options" => [], "class='form-control nodidsplay'", "attributes"=>"required"],
            ["name" => "id_section", "id" => "id_section", "label" => "Section", "type" => "select", "options" => [], "class='form-control nodidsplay'", "attributes"=>"required"],
            ["name"=>"id_nature", "label"=>"Nature","type" => "select", "options" => [],"attributes" => "multipel=true", "class='form-control nodidsplay'", "attributes" =>"onchange='getresultlist()' required"],
            ["name" => "id_result", "id" => "id_result", "label" => "Result", "type" => "select", "options" => [], "class='form-control nodidsplay'", "attributes"=>"required"],
            ["name"=>"details", "label"=>"Details", "type"=>"textarea","attributes"=>"style='width:90%; height:200px;'", ],
        );
        $builder->form_fields = array_merge($builder->form_fields, FC::getClass("Settings")->getCustomFormFields($custom_meta));
        $view->form = $builder->addForm(false, $view->addcase);
        $view->page_title = "Add New addcase";
        $result = $view->render('../views/addcase/add.php');
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
            $view->addcase =$q= $db->getRow("SELECT * FROM addcase WHERE id = '$id'");
            $view->law=$db->getRow("SELECT `law` FROM `law` WHERE `id`='$q[id_law]'");
            $view->title=$db->getRow("SELECT `title` FROM `title` WHERE `id`='$q[id_title]'");
            $view->section=$db->getRow("SELECT `section` FROM `section` WHERE `id`='$q[id_section]'");
            $view->nature=$db->getRow("SELECT `nature` FROM `nature` WHERE `id`='$q[id_nature]'");
            $view->result=$db->getRow("SELECT `result` FROM `result` WHERE `id`='$q[id_result]'");
            if(!$view->addcase){$fc->error = "No information available. ";}
            else{
                $view->addcase['meta'] = FC::getClass("Settings")->getCustomMeta("addcase_meta","id_addcase",$id);
            }
        }else{
            $fc->error= "invalid parameter";
        }
        $result = $view->render('../views/addcase/detail.php');
        $fc->setBody($result);
    }
    public function getresultlist()
    {
        $db = FC::getClass("Db");
        $id_law = Tools::getValue("id_law");
        $id_nature = Tools::getValue("id_nature");
        if($id_law && $id_nature) {
            $data = $db->getNameValue("SELECT `id`,  `result` as 'name' FROM `result` WHERE `id_law`= '$id_law' AND `id_nature`='$id_nature' ","id","name");

            if ($data) {
                echo "<option value=''>Select one</option>";
                foreach ($data as $id => $name) {
                    echo "<option value='{$id}'>$name</option>";
                }
            }else{
                echo "<option>No Data</option>";
            }
        }
    }
    public function getdata()
    {
        $db = FC::getClass("Db");
        $value = Tools::getValue("value");
        $param=Tools::getValue('param');

        if($value) {
            if($param==1){
                $data = $db->getNameValue("SELECT `id`,  `title` as 'name' FROM `title` WHERE `id_law`= '$value'","id","name");
            }
            else if($param==2){
                    $data = $db->getNameValue("SELECT `id`,  `section` as 'name' FROM `section` WHERE `id_law`= '$value'","id","name");
                }else if($param==3){
                $data = $db->getNameValue("SELECT `id`,  `nature` as 'name' FROM `nature` WHERE `id_law`= '$value'","id","name");
            }

            if ($data) {
                echo "<option value=''>Select one</option>";
                foreach ($data as $id => $name) {
                    echo "<option value='{$id}'>$name</option>";
                }
            }else{
                echo "<option>No Data</option>";
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
function gettitlename($title_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `title` FROM `title` WHERE `id`='$title_id'");
    return $name;
}
function getsectionname($section_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `section` FROM `section` WHERE `id`='$section_id'");
    return $name;
}
function getnaturename($nature_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `nature` FROM `nature` WHERE `id`='$nature_id'");
    return $name;
}
function getresultname($result_id)
{
    $db = FC::getClassInstance("Db");
    $name=$db->getValue("SELECT `result` FROM `result` WHERE `id`='$result_id'");
    return $name;
}
function dateformat($date){
    $newdate=date("j-M-y", strtotime($date));
    return $newdate;
}

