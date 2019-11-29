<?php
class members implements IController {
public function main() {
$view = new View();
FC::getClassInstance("Session")->session(["ad_ad","manager","executive"]);
$db = FC::getClassInstance("Db");
$fc = FC::getInstance();



if( isset( $_GET['delete'] ) ){
    $id_user = $_GET['id_user'];
    $db->execute("UPDATE `user_pro` set `status` = '4' WHERE `id`='$id_user' LIMIT 1");
    $fc->success = "User deleted successfully.";
    Tools::redirect();
}
if(isset($_GET['login'])){
    $id_user = $_GET['id'];
    $email = FC::getClassInstance("Users")->getEmail($id_user);
    unset($_SESSION);
    session_destroy();
   // FC::getClassInstance("Session");
   session_start();
    $login = FC::getClassInstance("Login");
    $login->ad_email = $email;
    $login->rememberme = false;
    $login->createLoginInstance();
    $_SESSION['ad_as_user'] = true;
    header("location:".SITEURL . "dashboard");
}
if( isset( $_GET['suspend'] ) ){
    $id_user = $_GET['id_user'];
    $db->execute("UPDATE `user_pro` SET `status`='3' WHERE `id`='$id_user' LIMIT 1");
    $fc->success = "User suspended successfully.";
}
if( isset( $_GET['active'] ) ){
    $id_user = $_GET['id_user'];
    $db->execute("UPDATE `user_pro` SET `status`='2' WHERE `id`='$id_user' LIMIT 1");
    $fc->success = "User activated successfully.";
    $id = $id_user;
    $email = FC::getClassInstance("Users")->getEmail($id);
    $mails = FC::getClassInstance("Mail");
    $mails->to = $email;
    $mails->subject = "Welcome to ".SITE_TITLE.".";
    $mails->message= "<a href='<?php echo SITEURL; ?>' >".SITE_TITLE."</a>
    <h4>Hi ".FC::getClassInstance("Users")->getName($id_user).",</h4>
    Your account has been activated.<br>
    Please <a href='".SITEURL."login'>login</a> to start transacting with us.<br>
Thank you for choosing BttPay.com.

    <br><br>
    Sincerely,
    <br>
    <a href='".SITEURL."'>".SITE_TITLE."</a><br>.
    ";
    if($mails->sendMail()){
            //echo "Your message has been mailed to ".$mails->to;
    }
    
    Tools::redirect();
}
if( isset ( $_POST['active_all'] ) ){
    if( !empty( $_POST['multi_user'] ) ){
        $count = 0;
        foreach( $_POST['multi_user'] as $id_user ) {
            $db->execute("UPDATE `user_pro` SET `status`='2' WHERE `id`='$id_user' LIMIT 1");
            $count++;
        }
        $fc->success = $count." Users activated successfully.";
    }
    else {
        $fc->error = "No one was selected.";
    }
}
if( isset( $_POST['sus_all'] ) ){
    if( !empty( $_POST['multi_user'] ) ){
        $count = 0;
        foreach( $_POST['multi_user'] as $id_user ) {
            $db->execute("UPDATE `user_pro` SET `status`='3' WHERE `id`='$id_user' LIMIT 1");
            $count++;
        }
        $fc->success = $count." Users suspended successfully.";
    }
    else {
        $fc->error = "No one was selected.";
    }
}
if( isset( $_POST['delete_all'] ) ){
    if( !empty( $_POST['multi_user'] ) ){
        $count = 0;
        foreach( $_POST['multi_user'] as $id_user ) {
            $db->execute("UPDATE `user_pro` set `status` = '4' WHERE `id`='$id_user' LIMIT 1");
            $count++;
        }
        $fc->success = $count." Users deleted successfully.";
    }
    else {
        $fc->error = "No one was selected.";
    }
    Tools::redirect();
}
FC::loadClass("Filter");
$filter = new Filter();
$filter->num        = 50;
$page               = (isset($_GET['p']) && !empty($_GET['p'])) ? $_GET['p'] : 1;
$filter->from       = ($page - 1) * $filter->num;
$filter->seachLabel = "ID, first name, last name or email ID";
$filter->filters    = array(
                            "search"    => array("`id`","`first_name`", "`last_name`", "`ad_email`","`ad_user`"),
                            "sort"      => array(
                                                 "id:desc"    => "Latest First",
                                                 "id:asc"     => "Oldest First",
                                                 "first_name:asc" => "Name (A-Z)",
                                                 "fist_name:desc"  => "Name (A-Z)",
                                                 "ad_funds:desc"  => "Earning (Desc)",
                                                 "ad_funds:asc"  => "Earning (Asc)"
                                            ),
                            "filters"   => array("Select Status"=>array("status"=>array("0"=>"Pending Email Verification", "1"=>"Pending Approval","2"=>"Active","3"=>"Suspended","4"=>"Deleted"))),
                            "pagination"	=> array("pageLink"=>SITEURL."members/" )
                            );
$filter->select     = "SELECT * FROM `user_pro`";
$filter->where      = "`acc_typ` IN (2,3)";
$filter->order      = "ORDER BY `status` ASC";
$filter->action = "";
$view->filter       = $filter->createFilter();
$query              = $filter->getQuery();
$view->users = FC::getClassInstance("Db")->getRows($query);
$view->total = $view->total_users = $db->getValue("SELECT COUNT(*) FROM `user_pro` WHERE `acc_typ` IN (2,3) AND `status` != '4'");
$view->active = $view->merchants = $db->getValue("SELECT COUNT(*) FROM `user_pro` WHERE `acc_typ` IN (2,3) AND `status`='2'");
$view->suspend = $view->suspended_users = $db->getValue("SELECT COUNT(*) FROM `user_pro` WHERE `acc_typ` IN (2,3) AND `status`='3'");
$view->pending = $view->pending_users = $db->getValue("SELECT COUNT(*) FROM `user_pro` WHERE `acc_typ` IN (2,3) AND (`status`='1')");
$view->pending_email = $view->pending_email_users = $db->getValue("SELECT COUNT(*) FROM `user_pro` WHERE `acc_typ` IN (2,3) AND (`status`='0')");

$result = $view->render('../views/members.php');
$fc = FC::getInstance();
$fc->setBody($result);
}

	
}
?>