<?php
class SettingsCore {
    public $db;
    public function __construct(){
        $this->db = FC::getClassInstance("Db");
		$this->setting = array();
    }
    public function getSetting($name, $single = true){
        if($single){
			if(!isset($_SESSION['settings'])){
				$_SESSION['settings'] = $this->getSettings();
			}
			return (isset($_SESSION['settings'][$name])?$_SESSION['settings'][$name]:"");
        }
        else
            return $this->db->getRow("SELECT * FROM `settings` WHERE `sett_name` = '$name'");
    }
	public function getSettings(){
		$settings = $this->db->getRows("SELECT * FROM `settings`");
		if($settings){ 
			foreach($settings as $setting){
				$this->setting[$setting['sett_name']] = $setting['sett_value'];
			}
		}
		return $this->setting;
    }

    public function addSetting($name, $value){
		if(isset($_SESSION['settings'])){ unset($_SESSION['settings']); }
        return $this->db->insert( array(
                                "settings"  => array(
                                    "sett_name"      => $name,
                                    "sett_value"     => $value
                                )
                                ) );
    }
    public function updateSetting($name, $value){
		if(isset($_SESSION['settings'])){ unset($_SESSION['settings']); }
        $update = $this->db->updateData( array("settings"  => array("sett_value"     => $value)), "WHERE `sett_name` = '$name'" );
		if($update){
			if(!$this->db->getValue("SELECT id FROM settings WHERE sett_name = '$name'")){
				$this->addSetting($name, $value);
			}
		}
    }
    public function deleteSetting($name){
		if(isset($_SESSION['settings'])){ unset($_SESSION['settings']); }
        return $this->db->execute("DELETE FROM `settings` WHERE `sett_name` = '$name'");
    }
	public function getCustomFormFields(&$custom_meta, &$info=false)
    {
		$form_fields = [];
		if($custom_meta){
			foreach($custom_meta as $cm){
				$field = [
                    "name"=>"cm_{$cm['id']}",
                    "label"=>"{$cm['label']}",
                    "type"=>$cm['ftype'],
                    "is_required"=>$cm['is_required'],
                    "placeholder"=>$cm['placeholder'],
                    "validation_error"=>$cm['validation_error'],
                    "default_value"=>$cm['default_value'],
                    "display"=>$cm['display'],
                    "ordering"=>$cm['ordering'],
                ];
				// LETS CHECK IF THE VALUE IS PRESENT?
                if($info)
                {
                    if(isset($info['cm_'.$cm['id']]))
                    {
                        $field['value'] = $info['cm_'.$cm['id']];
                    }
                }

				$cm['finfo'] = nl2br($cm['finfo']);
				$finfo = explode("<br />",$cm['finfo']);
				$foptions = [];
				if($finfo){
					foreach($finfo as $fopt){
						$fopt = trim($fopt);
						$foptions[$fopt] = $fopt;
					}
				}
				if(in_array($cm['ftype'],["select","checkbox","radio"])){ $field['options'] = $foptions;$field['selected'] = explode(";",$cm['default_value']); }
				$form_fields[] = $field;
			}
		}
		return $form_fields;
	}
	public function saveCustomFormFields(&$custom_meta,$id, $table, $column){
		$db = FC::getClass("Db");
		if($custom_meta){
			foreach($custom_meta as $cm){
				if($cm['ftype'] == "checkbox"){
					$custom_field = implode(",",$_POST["cm_".$cm['id']]);
				}elseif($cm['ftype'] == 'file'){
                    if($_FILES["cm_".$cm['id']]['size']>0){
                        $file = FC::classInstance("Upload", $_FILES["cm_".$cm['id']]);
                        $filePath = $file->upload();
                        $custom_field = $filePath;
                    }
                }
				else{
					$custom_field = Tools::getValue("cm_".$cm['id']);
				}
				if($custom_field){
					if($db->getValue("SELECT value FROM $table WHERE id_meta = '".$cm['id']."' AND $column = '$id'")){
						$db->update(array($table=>["value"=>$custom_field]), "WHERE id_meta = '".$cm['id']."' AND $column = '$id'");
					}else{
						$db->insert(array($table=>["id_meta"=>$cm['id'], $column=>$id, "value"=>$custom_field]));
					}
				}
			}
		}
	}
	public function getCustomMeta($table, $column, $value){
		$ret = [];
		$rows = $this->db->getRows("SELECT a.name,a.ftype ,b.value from custom_meta a LEFT JOIN $table b ON a.id = b.id_meta WHERE b.$column = '$value'");
		if($rows){
			foreach($rows as $row){
				if($row['ftype'] == 'file'){
					$value = "<a href='".SITEURL . $row['value']."'>View File</a>";
				}else{
					$value = $row['value'];
				}
				$ret[] = ['name'=>$row['name'],'value'=>$value];
			}
		}
		return $ret;
	}
	public function getStudentCFs($id_student){
		return $this->db->getNameValue("SELECT name,value FROM `custom_fields` WHERE id_student = '$id_student'", "name", "value");
	}
	public function getStudentCF($name, $id_student, $single = true){
        if($single){
			return $this->db->getValue("SELECT value FROM `custom_fields` WHERE `name` = '$name' AND id_student = '$id_student'");
		}
		return $this->db->getRow("SELECT * FROM `custom_fields` WHERE `name` = '$name' AND id_student = '$id_student'");
    }
	public function addStudentCF($name, $value, $id_student){
		if($this->getStudentCF($name, $id_student, 1)){
			$update = $this->db->updateData( array("custom_fields"  => array("value"     => $value)), "WHERE `name` = '$name' AND id_student = '$id_student'" );
		}else{
        return $this->db->insert( array(
                                "custom_fields"  => array(
                                    "name"      => $name,
                                    "value"     => $value,
									"id_student"	=> $id_student
                                )
                                ) );
		}
    }
	public function deleteStudentCF($name, $id_student){
        return $this->db->execute("DELETE FROM `custom_fields` WHERE `sett_name` = '$name' AND id_student = '$id_student'");
    }
    public function getSessions($keyValue=false){
        if(!$keyValue) return $this->db->getRows("SELECT * FROM sessions");
		else{
			$sessions = $this->db->getRows("SELECT id, name FROM sessions");
			$ret = array();
			if($sessions){
				foreach($sessions as $sess){
					$ret[$sess['id']] = $sess['name'];
				}
				return $ret;
			}else{
				return false;
			}
		}
    }
	public function updateWorkingDay($term, $week, $day, $id_session=false){
		if(!$term){ $term = $this->getSetting("term", true); }
		if(!$id_session) { $id_session = $this->getActiveSession()['id']; }
		$date = date('Y-m-d');
		$db = $this->db;
		if($db->getValue("SELECT id FROM working_days WHERE `date` = '".$date."'")){
			$db->execute("UPDATE working_days SET `id_session` = '".$id_session."', `term` = '".$term."',
							 `week` = '".$week."', `day`='".$day."',`date` = '".$date."' WHERE date = '".$date."'");
		}else{
			$db->execute("INSERT INTO working_days(`id_session`, `term`, `week`, `day`, `date`) VALUES('".$id_session."', '".$term."',
							 '".$week."','".$day."','".$date."')");
		}
	}
	public function getSessionName($id_session){
        return $this->db->getValue("SELECT name FROM sessions WHERE id = $id_session");
    }
    public function getActiveSession(){
        return $this->db->getRow("SELECT b.id, b.name FROM settings a LEFT JOIN sessions b on a.sett_value = b.id WHERE a.sett_name = 'session'");
    }
	public function getSession($id_session =false){
        if(!$id_session) return $this->db->getRow("SELECT b.id, b.name FROM settings a LEFT JOIN sessions b on a.sett_value = b.id WHERE a.sett_name = 'session'");
		else{
			return $this->db->getRow("SELECT id, name FROM sessions WHERE id = $id_session");
		}
    }
    public function showSessionSelectBox(){
        $sessions = $this->getSessions();
        $active_session = $this->getActiveSession();
        ?>
        <div class="col-md-5 col-md-offset-1">
			<div class="row">
				<div class="col-md-4"><label for="gender">Session</label></div>
				<div class="col-md-8">
					<select name="id_session" id="id_session" class="form-control">
                        <option value="">Select Session</option>
                        <?php foreach($sessions as $sess){
                            echo "<option ".($active_session['id'] == $sess['id']?"selected":"")." value='".$sess['id']."'>".$sess['name']."</option>";
                        } ?>
                    </select>
					<div class="error" id="error_id_session">Please select valid value</div>
				</div>
			</div>
		</div>
    <?php }

    public function getMenu()
    {
//        if(isset($_SESSION['menu']))
//        {
//            return $_SESSION['menu'];
//        }
//        else
//        {
        $menuArray = [];
        $menuDb = $this->db->getRows("SELECT * FROM menu WHERE status = 1 AND acc_typ = '".Session::user('acc_typ')."'");
        if($menuDb)
        {
            foreach ($menuDb as $menu)
            {
                $menuArray[$menu['id_parent']][$menu['id']] = array("title" => $menu['name'], "url" => SITEURL.$menu['url'], "icon" => $menu['icon'], "visible" => $menu['status']);
            }
        }
            //$_SESSION['menu'] = $menu;
        //}
        return $menuArray;
    }
}
