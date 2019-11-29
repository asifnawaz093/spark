<?php
class ErrorsCore {
    public $db;
    public function __construct(){
        $this->db = FC::getClass("Db");
    }
    public function markRead($id_user, $code = false){
        $extra = "";
        if($code){ $extra = " AND `message` = '$code'"; }
        return $this->db->execute("UPDATE `errors` SET `is_read` = '1' WHERE `id_user` = '$id_user' $extra");
    }
    public function decode($code){
        
    }
    public function logSystemError($error){
        $this->db->insert(array("system_errors"=>array("error"=>$error, "ip"=>$_SERVER['REMOTE_ADDR'],  "date"=>date('Y-m-d H:i:s',time() ))));
    }
    public function log($error,$id_user, $data=""){
        if($data){
            if(isset($data['wp-settings-1'])) unset($data['wp-settings-1']);
            if(isset($data['card_number'])) unset($data['card_number']);
            $data = Tools::sanitizeInput(json_encode($data), true);
        }
        $this->db->insert(array("errors"=>array("error"=>$error, "id_user"=>$id_user, "date"=>date('Y-m-d H:i:s',time()), "ip"=>$_SERVER['REMOTE_ADDR'], "form_data"=>$data ) ));
    }
    
}
