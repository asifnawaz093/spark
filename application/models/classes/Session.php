<?php
FC::loadClass("Login");
class Session extends Login{
    public $thisSess = "ad_user";
    public function __construct(){
        if( !isset( $_SESSION ) ){
           session_name("logged");
           session_start();
       }
   }
   public static function get($session){
       return (isset($_SESSION[$session])) ? $_SESSION[$session] : false;
   }
   public static function set($session, $value = true){
       $_SESSION[$session] = $value;
   }
   public static function user($session){
       return (isset($_SESSION['user'][$session])) ? $_SESSION['user'][$session] : false;
   }
   public static function emp($session){
        return (isset($_SESSION['user']['emp'][$session])) ? $_SESSION['user']['emp'][$session] : false;
   }
   public function remove($session){
       unset($_SESSION[$session]);
   }
   public function getOnce($session){
       $sess = (isset($_SESSION[$session])) ? $_SESSION[$session] : false;
       if($sess){ unset($_SESSION[$session]); }
       return $sess;
   }
   public function getMy($ret){ return find_product3("user_pro","ad_email",$_SESSION['ad_user'],$ret); }
   public function isCookieExist($ad_user='ad_user'){
    return false;
    if(isset($_COOKIE[$ad_user])){
        $_SESSION[$ad_user] = $_COOKIE[$ad_user];
        return true;
    }
    else return false;
}
public function isSessionExist($ad_user='ad_user'){
    return (isset($_SESSION[$ad_user]) ? true : false);
}
public function isLoggedIn($ad_user='ad_user'){
    if(!$this->isSessionExist($ad_user)){
        if($this->isCookieExist($ad_user)){
            $this->ad_email = $ad_user;
            $this->createLoginInstance();
            return true;
        }
        else{ return false; }
    }
    else{ return true;}
}
public function sessionDiv(){
    if(!$this->isSessionExist()){ $this->loginDiv(); return true;}
}
public function askForLogin(){
    $fc = FC::getInstance();
        $fc->info = "Sorry for the delay, but we need to authenticate you. Please login";
        $fc->redirect = FC::getClass("Url")->currentUrl();
        Tools::redirect("login");
}
public function session($ad_user="ad_user", $redirectTo = false){
    $logged = 0;
    if(is_array($ad_user)){
        foreach($ad_user as $user){
            if($this->isLoggedIn($user))
                $logged= 1;
        }
    }
    elseif($this->isLoggedIn($ad_user)){
        $logged=1;
    }
    if(!$logged){
        if($this->isLoggedIn("ad_user")){
            $fc->error = "You don't have access to this page. This page is limited to specific roles only.";
            Tools::redirect("noaccess");
        }
        $this->askForLogin();
    }else{
        if($this->isAuthorized())
            return true;
        else{
            $this->askForLogin();
        }
    }
}
public function isAuthorized(){
    $id_user = $this->myId();
    $authorized = 0;
    $fc = FC::getInstance();
    if($this->getAccTyp() == "ad_ad"){
        $authorized = 1;
    }else{
        $prv = FC::getClass("Privillage");
        $url =  SITEURL . $fc->getController();
        $action = $fc->getAction();
        if($action && $action != "main"){
            $url .= "/?action=".$action;
            $restriceted_params = $prv->restrictedParams();
            foreach($restriceted_params as $rp){
                if(isset($_REQUEST[$rp])){
                    $url .= "&".$rp;
                }
            }
            
        }
        $allowedUrls = $prv->allowedUrls();
        if(isset($_GET['debug_url'])){ echo "Role: ".Session::user("acc_typ")."<br><pre>";print_r($allowedUrls);exit();}
        if($allowedUrls){
            if(in_array($url, $allowedUrls)){
                $authorized = 1;
            }
        }else{
            $authorized = 0;
        }
    }
    if(!$authorized){
        $fc->error = $url . "<br><br><a class='btn btn-info' href='".FC::getClass("Url")->currentUrl()."'>Retry</a>";
        //$custom_url = FC::getClass("Db")->getValue("SELECT custom_urls FROM `user_privillages` WHERE `role` = '".Session::user('acc_typ')."'");
        //$custom_url .= ",$url";
        //FC::getClass("Db")->execute("UPDATE user_privillages SET `custom_urls` = '$custom_url' WHERE `role` = '".Session::user('acc_typ')."'");
        Tools::redirect(SITEURL."noaccess");
    }
    else{ return true; }
}
         public function session_getpages(){
            if(!Session::isSessionExist()){ echo "You are not logged in. Please <a href='#' onclick=showPopup('login')>click here</a> to login";exit(); }
        }
         public static function my($col){
            if(isset($_SESSION['user'])){
                return $_SESSION['user'][$col];
            }
            return false;
        }
        public function isUserAdmin(){ if(isset($_SESSION['ad_user'])){$this->ad_user=$_SESSION['ad_user'];}
        return ($this->getAccTyp()=="ad_ad") ? true : false; }
        public function isAdmin(){ return (isset($_SESSION['ad_ad'])) ? true : false; }
        public function isTeacher(){ return (isset($_SESSION['teacher'])) ? true : false; }
        public function isStaff(){ return (isset($_SESSION['staff'])) ? true : false; }
        public function isViewer(){ return (isset($_SESSION['viewer'])) ? true : false; }
        public function isMerchant(){ return (isset($_SESSION['merchant'])) ? true : true; }
        public function myInfo(){ return find_product("user_pro","ad_email",$_SESSION['ad_user']); }
        public function myThisInfo($ret){ return find_product3("user_pro","ad_email",$_SESSION['ad_user'],$ret); }
        public function myId(){ return isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false; }
        public function myUserId(){ return isset($_SESSION['id_this_user']) ? $_SESSION['id_this_user'] : false; }
        public function myEmail(){ return $this->myThisInfo('ad_email'); }
        public function accountStatus(){ $myInfo = $this->myInfo(); return $myInfo['is_active'];  }
        public function isEmailVerified(){ return $this->accountStatus()>0 ? true : false; }
        public function isAccountSuspended(){ return $this->accountStatus()==1 ? true : false; }
        public function isAccountActive(){ return $this->accountStatus()==2 ? true : false; }
    }
    ?>