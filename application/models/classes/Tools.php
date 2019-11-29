<?php
class Tools {
    public static function sanitizeInput($data){
        $db = FC::getClassInstance("Db");
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data);
        $data = htmlspecialchars($data);
       // $data = preg_replace("/\\r\\n/","",$data);
        $data = $db->link->real_escape_string($data);
        return $data;
    }
    public static function isSubmit($name){
        return isset($_REQUEST[$name]);
    }
    public static function safePrint($data){
        //return htmlspecialchars_decode(html_entity_decode($data));
        $data = htmlspecialchars_decode(html_entity_decode($data));
        $data = preg_replace("/rnrn/","<br>",$data);
        //$data = preg_replace("/\\n/","<br>",$data);
        return $data;
    }
     public static function printDate($date=false){
        $time = ($date ? strtotime($date) : time());
        return "<span class='print_date' data-toggle='tooltip' title='".date("F d, Y", $time)."'>".date("d-m-Y",$time)."</span>";
    }
    public static function dbDate($date=false){
        $time = ($date ? strtotime($date) : time());
        return date("Y-m-d", $time);
    }
    public static function getValue($name, $default=false){
        if(self::isSubmit($name)){
            return ($_REQUEST[$name] == "") ? $default : self::sanitizeInput($_REQUEST[$name]);
        }
        else{
            return $default;
        }
    }
    public static function sanitizeArray($array){
        if($array){
            $array = array_map("Tools::sanitizeInput", $array);
        }
        return $array;
    }
    public static function getArray($name, $default=array()){
        if(self::isSubmit($name)){
            return (count($_REQUEST[$name])>0) ? $_REQUEST[$name] : $default;
        } else {
            return array();
        }
    }
    public static function price($price, $currency = false, $dec = 0){
        if(!$currency){ $currency = Session::user("currency"); }
        if(!$currency) $currency = "Rs";
        return $currency ." ". number_format($price, $dec);
    }
    public static function redirect($page=false){
        if(!$page){ $page = FC::getClass("Url")->currentUrl(); }
        if(!preg_match("/http/",$page)){ $page = SITEURL . $page; }
        $fc = FC::getInstance();
        $session = FC::getClass("Session");
        if($fc->success) $session->set("success", $fc->success);
        if($fc->error) $session->set("error", $fc->error);
        if($fc->msg) $session->set("msg", $fc->msg);
        if($fc->info) $session->set("info", $fc->info);
        if($fc->warning) $session->set("warning", $fc->warning);
        if($fc->redirect) $session->set("redirect", $fc->redirect);
        header("Location:".$page);
        exit();
    }
    
    public static function isAssociative($array){
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }
    
    public static function val2Array($array){
        $anyVal = false;
        if($array){
            foreach($array as $row){
                if(is_array($row)){
                    foreach($row as $col){
                        if(!empty($col)){
                            $anyVal = true;
                        }
                    }
                } else {
                    if(!empty($row)){
                        $anyVal = true;
                    }
                }
            }
        }
        return $anyVal;
    }
    
  
    
}
?>