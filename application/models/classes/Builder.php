<?php
class Builder{
    public $db;
    public $fc;
    public $table;
    public $unique_column = "id";
    public $columns;
    public $columns_mapping;
    public $numbering = true;
    public $where_condition =false;
    public $table_classes = false;
    public $table_id = false;
    public $actions;
    public $actions_label = "Actions";
    public $rows;
    public $form_fields = array();
    public $form_buttons = false;
    public $form_col1_class = "col-md-4";
    public $form_col2_class = "col-md-8";
    public $form_row_extra_class = false;
    public $form_action;
    public $form_attribute;
    public $log_delete = false;
    public $log_edit = false;
    public $log_add = false;
    public $log_title;
    public $link;
    public $auto = array();
    public $form_id=false;
    public $links;
    public $checkbox =false;
    public $checkbox_column =false;
    public $multiactions = [];
    public $dataFunctionsOnSave=[];
    public function __construct(){
        $this->db = FC::getClass("Db");
        $this->fc = FC::getInstance();
        $this->links = new ArrayObject();
    }
    public function getTable(&$rows=false){
        if(!$this->checkbox_column){ $this->checkbox_column = $this->unique_column; }
        $table = false;
        if(in_array("delete", $this->auto)){ if(Tools::isSubmit("delete") && Tools::getValue($this->unique_column)){ $this->delete(Tools::getValue($this->unique_column)); } }
        if($this->unique_column) $column_names[] = $this->unique_column;
        $db = $this->db;
        $column_functions = array();
        if($this->columns){
            foreach($this->columns as $column_name => $column_label){
                $column_names[] = $column_name;
                if(is_array($column_label)){
                    $table_labels[] = $column_label['label'];
                    if(isset($column_label['function'])){ $column_functions[$column_name] = $column_label['function']; }
                }else{
                    $table_labels[] = $column_label;
                }
            }
            if(!$rows) $rows = $this->db->getRows("SELECT ".implode(", ", $column_names)." FROM `".$this->table."` ". $this->where_condition);
            if($rows){
                $table = "<table class='".($this->table_classes ?:"table table-striped table-hover")."' ".($this->table_id? " id='".$this->table_id."'":"").">";
                $table .= "<thead><tr>";
                if($this->checkbox){ $table .= "<th><input type='checkbox' class='checkall' data-target='.qchecks'></th>";}
                if($this->numbering){ $table .= "<th>#</th>";}
                $table .= "<th>" . implode('</th><th>', $table_labels) . "</th>";
                if($this->actions){ $table .= "<th>".$this->actions_label."</th>";}
                $table.="</tr></thead><tbody>";
                $i=1;
                foreach($rows as $row){
                    $table.="<tr>";
                    if($this->checkbox){ $table .= "<td><input type='checkbox' name='multi[]' value='".$row[$this->checkbox_column]."' class='qchecks'></td>";}
                    if($this->numbering){ $table .= "<td>$i</td>";}
                    foreach($this->columns as $col_name => $col_label){
                        if(isset($row[$col_name]) || $col_name){
                            $col_data = isset($this->columns_mapping[$col_name]) ? $this->columns_mapping[$col_name][$row[$col_name]] : $row[$col_name];
                            $col_data = isset($column_functions[$col_name]) ? call_user_func($column_functions[$col_name],$col_data) : $col_data;
                            $table .= "<td>".$col_data."</td>";
                        }
                    }
                    if($this->actions){
                        $table.="<td class='colactions'>";
                        foreach($this->actions as $link_action){
                            if($link_action =="view"){
                                $table .= "<a href='".(isset($this->links->view) && $this->links->view ? $this->links->view : $this->link)."&".$this->unique_column."={$row[$this->unique_column]}'><i class='glyphicon glyphicon-eye-open' data-toggle='tooltip' title='View'></i></a> ";
                            }
                            elseif($link_action =="edit"){
                                $table .= "<a href='".(isset($this->links->edit) && $this->links->edit ? $this->links->edit : $this->link)."&edit&".$this->unique_column."={$row[$this->unique_column]}".($this->form_id ? "&{$this->form_id}=1":"")."'><i class='glyphicon glyphicon-edit' data-toggle='tooltip' title='Edit'></i></a> ";
                            }elseif($link_action =="delete"){
                                $table .= "<a href='".(isset($this->links->delete) && $this->links->delete ? $this->links->edit : $this->link)."&delete&".$this->unique_column."={$row[$this->unique_column]}' onclick=\"return confirm('Are you sure?')\"><i class='glyphicon glyphicon-remove' data-toggle='tooltip' title='Delete'></i></a> ";
                            }else{
                                $link_action = str_replace("{unique_column}", $row[$this->unique_column], $link_action);
                                $table .= $link_action;
                            }
                        }
                        $table.="</td>";
                    }
                    $table .= "</tr>";
                    $i++;
                }
                $table .= "</tbody></table>";
            }
        }
        return $table;
    }
    //$data = array($unique_id, $data);
    public function addForm($unique_id=false, $data=false){
        if(in_array("edit", $this->auto)){ $unique_id = ($this->form_id ? (Tools::isSubmit($this->form_id) ? Tools::getValue($this->unique_column) : false) : Tools::getValue($this->unique_column)); }
        if(in_array("add", $this->auto)){ if(Tools::isSubmit("submit")){ $this->saveForm(Tools::getValue($this->unique_column)); } }
        if(!$data && $unique_id){
            $data = $this->db->getRow("SELECT * FROM `".$this->table."` WHERE `".$this->unique_column."` = '$unique_id'");
        }
        if($this->form_fields){
            $form = "<form method='post' action='".$this->form_action."' ".$this->form_attribute.">";
            foreach($this->form_fields as $form_fields){
                $inp_field =  (isset($form_fields['field'])?$form_fields['field']:false);
                if(!$inp_field){
                    if(isset($form_fields['type']) && $form_fields['type'] == "select"){
                        $inp_field = "<select name='{$form_fields['name']}' class='".(isset($form_fields['class'])?$form_fields['class']:"form-control")."'
                            id='".(isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."' ".(isset($form_fields['attributes'])?$form_fields['attributes']:"").">";
                        $selected = (($data && isset($data[$form_fields['name']]) ? $data[$form_fields['name']]:"")?:(isset($form_fields['selected'])?$form_fields['selected']:""));
                        foreach($form_fields['options'] as $index => $key){
                            $inp_field .= "<option value='$index'".($selected==$index ? "selected":"").">$key</option>";
                        }
                        $inp_field .= "</select>";
                    }elseif(isset($form_fields['type']) && $form_fields['type'] == "checkbox"){
                        $i=0;
                        foreach($form_fields['options'] as $index => $key){
                            $i++;
                            $id = (isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."_".$i;
                            $selected = (($data && isset($data[$form_fields['name']]) ? $data[$form_fields['name']]:"")?:(isset($form_fields['selected'])?$form_fields['selected']:""));
                            $inp_field .= "<input type='checkbox' value='$index' name='{$form_fields['name']}[]' ".($selected==$index ? "checked":"")." class='".(isset($form_fields['class'])?$form_fields['class']:"")."'
                            id='$id' ".(isset($form_fields['attributes'])?$form_fields['attributes']:"")."> 
                            <label for='$id'>$key</label> ";
                        }
                    }elseif(isset($form_fields['type']) && $form_fields['type'] == "radio"){
                        $i=0;
                        foreach($form_fields['options'] as $index => $key){
                            $i++;
                            $id = (isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."_".$i;
                            $selected = (($data && isset($data[$form_fields['name']]) ? $data[$form_fields['name']]:"")?:(isset($form_fields['selected'])?$form_fields['selected']:""));
                            $inp_field .= "<input type='radio' value='$index' name='{$form_fields['name']}' ".($selected==$index ? "checked":"")." class='".(isset($form_fields['class'])?$form_fields['class']:"")."'
                            id='$id' ".(isset($form_fields['attributes'])?$form_fields['attributes']:"")."> 
                            <label for='$id'>$key</label> ";
                        }
                    }elseif(isset($form_fields['type']) && $form_fields['type'] == "textarea"){
                        $inp_field = "<textarea 
                            name='{$form_fields['name']}' class='".(isset($form_fields['class'])?$form_fields['class']:"form-control")."'
                            id='".(isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."' 
                            ".(isset($form_fields['attributes'])?$form_fields['attributes']:"").">".Tools::safePrint((($data && isset($data[$form_fields['name']]) ? $data[$form_fields['name']]:"")?:(isset($form_fields['value'])?$form_fields['value']:"")))."</textarea>";
                    }
                    else{
                        $inp_field = "<input type='".(isset($form_fields['type'])?$form_fields['type']:"text")."'
                            name='{$form_fields['name']}' class='".(isset($form_fields['class'])?$form_fields['class']:"form-control")."'
                            id='".(isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."' value='".(($data && isset($data[$form_fields['name']]) ? $data[$form_fields['name']]:"")?:(isset($form_fields['value'])?$form_fields['value']:""))."'
                            ".(isset($form_fields['attributes'])?$form_fields['attributes']:"").">";
                    }
                }
                if(isset($form_fields['type']) && $form_fields['type'] == "hidden"){
                    $form .= $inp_field; continue;
                }
                $form .= "<div class='".((isset($form_fields['row_class']) && $form_fields['row_class']) ? $form_fields['row_class'] : "row form-group")."'>
                    <div ".($this->form_col1_class ? "class='".$this->form_col1_class."'" : "").">
                        <label for='".(isset($form_fields['id']) ? $form_fields['id']:$form_fields['name'])."'>{$form_fields['label']}</label>
                    </div>
                    <div ".($this->form_col2_class ? "class='".$this->form_col2_class."'" : "").">".
                       $inp_field;
                       if(isset($form_fields['hint']) && $form_fields['hint']){
                            $form .= "<div class='text-info'>".$form_fields['hint']."</div>";
                       }
                    $form .= "</div>
                </div>";
            }
            $form .= "<div class='row form-group'><div ".($this->form_col1_class ? "class='".$this->form_col1_class."'" : "")."></div>
                <div ".($this->form_col2_class ? "class='".$this->form_col2_class."'" : "").">" .
                ($this->form_buttons ? : "<input type='submit' name='submit' value='Submit' class='btn btn-primary'> <a href='".$this->link."' class='btn btn-default'>Reset</a>") .
                "</div></div>";
            $form .= "</form>";
        }
        return $form;
    }
    public function delete($id){
        if($row = $this->db->getRow("SELECT * FROM `".$this->table."` WHERE `".$this->unique_column."` = '".$id."'")){
            if($this->db->execute("DELETE FROM `".$this->table."` WHERE `".$this->unique_column."` = '".$id."' LIMIT 1")){
               $this->fc->success = "Delete Successful";
               FC::getClass("Log")->add($this->log_title . " Record Deleted: ", preg_replace("/\"/","",json_encode($row)) );
               Tools::redirect($this->link);
            }else{
               $this->fc->error = "Delete Fail";
            }
        }
    }
    public function multiDelete($ids){
        if($rows = $this->db->getRows("SELECT * FROM `".$this->table."` WHERE `".$this->unique_column."` IN ( '".implode("','",$ids)."' )")){
            if($this->db->execute("DELETE FROM `".$this->table."` WHERE `".$this->unique_column."` IN ( '".implode("','",$ids)."' )")){
               $this->fc->success = "Delete Successful";
               FC::getClass("Log")->add($this->log_title . " Records Deleted: ", preg_replace("/\"/","",json_encode($rows)) );
               Tools::redirect($this->link);
            }else{
               $this->fc->error = "Delete Fail";
            }
        }
    }
    public function autoDelete($unique_column){
        if(Tools::isSubmit("delete")){
            $this->unique_column = $unique_column;
            $this->delete(Tools::getValue($unique_column));
        }
    }
    public function saveForm($update=false){
        $data = array();
        foreach($this->form_fields as $form_fields){
            if(isset($_POST[$form_fields['name']]) || isset($_FILES[$form_fields['name']])){
                $data_name = $form_fields['name'];
                if($form_fields['type'] == 'file'){
                    if($_FILES[$form_fields['name']]['size']>0){
                        $Image = FC::getClass("EasyImage", $_FILES[$form_fields['name']]);
                        $img = $Image->processImgUpload();
                        $data_value = $img['imgUrl'];
                    }
                }else{
                    $data_value =  Tools::getValue($form_fields['name']);
                }
                if(isset($this->dataFunctionsOnSave[$data_name])){
                    $data_value = Tools::sanitizeInput(call_user_func($this->dataFunctionsOnSave[$data_name],$_POST[$form_fields['name']]));
                }
                $data[$data_name] =$data_value;
            }
        }
        if(count($data) > 0){
            if($update){
                if($this->db->update(array($this->table=>$data), "WHERE `".$this->unique_column."` = '".$update."'",false)){
                    $this->fc->success = "Update Successful";
                    FC::getClass("Log")->add($this->log_title . ". Updated.");
                    Tools::redirect($this->link);
                }else{
                    $this->fc->error = "Error.";
                }
            }else{
                if($this->db->insert(array($this->table=>$data))){
                    $this->fc->success = "Data Save Successful";
                    FC::getClass("Log")->add($this->log_title . ". New data saved.");
                    Tools::redirect($this->link);
                }else{
                    $this->fc->error = "Error.";
                }
            }
        }
    }
}
 //$builder->table = "user_processors";
 //       $builder->primary_key = "id_processor";
//$builder->columns = array("name"=>"Payment Method", "email"=>"Account ID");
//$builder->actions = array('<a href="pagelink/?id={unique_column}"');
//        $view->table = $builder->getTable();
//$builder->form_fields = array("First Name"=>"<input type=''>");
?>