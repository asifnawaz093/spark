<?php
class Log {
    public $db;
    public $table;
    public $id_user;
    public function __construct(){
        $this->db = FC::getClassInstance("Db");
        $this->table = "user_logs";
        $this->id_user = FC::getClassInstance("Session")->myUserId();
    }
    
    public function addData($data){
        $data['id_user'] = $this->id_user;
        $data['time']	= date('Y-m-d H:i:s');
        $data['ip_user'] = $this->getClientIP();
        return $this->db->insert(array($this->table => $data));
    }
    public function add($title, $detail = ""){
        return $this->addData(array("title"=>$title, "details"=>$detail));
    }
    public function addHistory($id_user, $history, $type, $user_type){
        return $this->db->insert(array("history" => [
                "id_user"       => $id_user,
                "text"          => $history,
                "h_type"        => $type,
                "user_type"     => $user_type
             ]));
    }
    public function updateData($data, $where){
        return $this->db->update(array($this->table => $data), $where );
    }
    
    public function getSingle($id){
        return $this->db->getRow("SELECT * FROM `".$this->table."` WHERE `id`='".$id."'");
    }
    
    public function getAllData(){
        return $this->db->getRows("SELECT * FROM `".$this->table."`");
    }
    
    public function getClientIP() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    public function getIDWhere($where){
        $id = isset(explode("'", $where)[1]) ? explode("'", $where)[1] : false;
        return (is_numeric($id)) ? $id : false;
    }

    public function getName($column, $table, $id){
        return $this->db->getValue("SELECT `".$column."` FROM `".$table."` WHERE `id`='".$id."'");
    }

}
