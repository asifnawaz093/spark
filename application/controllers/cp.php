<?php
class cp implements IController{
    public function main() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        Tools::redirect("dashboard");
        $page = "cpanel";
        $session->session(["ad_ad","manager","executive"]);
        $loid =  $session->myId();
        $acctyp = $session->getAccTyp();
        $user = FC::getClassInstance("Users");
        $fc->css_files = array(SITEURL . "styles/icons.css");
        $view->id_user = $id_client = $session->myId();
        $view->name = FC::getClassInstance("Users")->getName($id_client);
        $view->last_login = $db->getRow("SELECT * FROM `inlogs` WHERE `id_user`='$id_client' ORDER BY `id` DESC LIMIT 1,1");
        $view->profile = $db->getRow("SELECT * from user_pro WHERE id = $id_client");
        $view->name = $view->profile['first_name'] . " " . $view->profile['last_name'];
        if($acctyp=="manager"){ $page = "mcp"; }
        if($acctyp=="executive"){ $page = "ecp"; }
        $result = $view->render('../views/'.$page.'.php');
        $fc->setBody($result);
      
    }
    
}
?>