<?php
class TimeZones {
    protected $db;
    public function __construct(){
        $this->db = FC::getClassInstance("Db");
        //$this->db->execute("ALTER TABLE `user_pro` ADD `timezone` VARCHAR(400) NOT NULL;");
    }
    public function getTimeZones(){
        return $this->db->getRows("SELECT * FROM `timezones`");
    }
    
    public function setTimeZone($timeZone){
        date_default_timezone_set($timeZone);
        return true;
    }
    public function getTimeZone($id, $onlyTimeZone=false){
        if($onlyTimeZone)
            return $this->db->getValue("SELECT `timezone` FROM `timezones` WHERE `id` = '$id'");
        else
            return $this->db->getRow("SELECT * FROM `timezones` WHERE `id` = '$id'");
    }
    
    public function getUserTimeZone($id_user = false){
        if(!$id_user)
            $id_user = FC::getClassInstance("Session")->myId();
        $tz_id = $this->db->getValue("SELECT `timezone` FROM `user_pro` WHERE `id` = '$id_user'");
        if(!$tz_id || $tz_id == ""){
            return $this->getSystemTimeZone();
        }
        $timeZone = $this->getTimeZone($tz_id);
        return $timeZone['timezone'];
    }
    
    public function setMyTimeZone(){
        $timeZone = $this->getUserTimeZone();
        return date_default_timezone_set($timeZone);
    }
    
    public function getMyTimeZone(){
        return $this->getUserTimeZone();
    }
    
    public function getDefaultTimeZone(){
        return date_default_timezone_get();
    }
    
    public function getSystemTimeZone(){
        $id_timezone = FC::getClassInstance("Settings")->getSetting("timezone", true);
        if(!$id_timezone)
            return $this->getDefaultTimeZone();
        else
            return $this->getTimeZone($id_timezone,true);
    }
    
    public function convertTimeSlotToLocal($time, $date, $sourceTimeZone, $retun = 'time'){
        $strtime1 = strtotime($date ." " . $time);
        $date = new DateTime($date ." " . $time, new DateTimeZone($sourceTimeZone));
        $date->setTimeZone( new DateTimeZone($this->getMyTimeZone()) );
        if($retun=='time'){
            return $date->format("h:ia");
        }
        else{
            return $date->format("Y-m-d");
        }
    }
    
}
