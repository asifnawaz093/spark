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

    public static function menuArray()
    {

        $menu = array();
        $menu['addcase']['root'] = array("title" => "Add Case Report", "url" => SITEURL . "addcase/?action=add", "icon" => "dashboard", "visible" => true);
        $menu['viewcasereport']['root'] = array("title" => "View Case Report", "url" => SITEURL . "addcase/", "icon" => "user", "visible" => true);
        $menu['setting']['root'] = array("title" => "Settings", "url" => "#", "icon" => "user", "visible" => true);
        $menu['setting']['addlaw'] = array("title" => "Add LAW", "url" => SITEURL . "law/?action=add", "icon" => "user", "visible" => true);
        $menu['setting']['law'] = array("title" => "View LAW", "url" => SITEURL . "law/", "icon" => "user", "visible" => true);
        $menu['setting']['addtitle'] = array("title" => "Add Title", "url" => SITEURL . "title/?action=add", "icon" => "user", "visible" => true);
        $menu['setting']['title'] = array("title" => "View Title", "url" => SITEURL . "title/", "icon" => "user", "visible" => true);
        $menu['setting']['addsection'] = array("title" => "Add Section", "url" => SITEURL . "section/?action=add", "icon" => "user", "visible" => true);
        $menu['setting']['section'] = array("title" => "View Section", "url" => SITEURL . "section/", "icon" => "user", "visible" => true);
        $menu['setting']['addnature'] = array("title" => "Add Nature", "url" => SITEURL . "nature/?action=add", "icon" => "user", "visible" => true);
        $menu['setting']['nature'] = array("title" => "View Nature", "url" => SITEURL . "nature/", "icon" => "user", "visible" => true);
        $menu['setting']['addresult'] = array("title" => "Add Result", "url" => SITEURL . "result/?action=add", "icon" => "user", "visible" => true);
        $menu['setting']['result'] = array("title" => "View Result", "url" => SITEURL . "result/", "icon" => "user", "visible" => true);
        $menu['changep']['root'] = array("title" => "Change Password", "url" =>  SITEURL . "changep/", "icon" => "gear", "visible" => true);
        // $menu['logout']['root'] = array("title" => "logout", "url" => SITEURL . "logout/", "icon" => "dashboard", "visible" => true);
        return $menu;
    }
}
?>