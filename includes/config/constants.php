<?php
define('SITE_PAGE_TILE','Tamin | Insurance App');
define('SITE_TITLE','TAMIN');
define('SITE_SALOGON','Insurance App');
$index = count(explode("/",SITEURL))-4;
define('INDEX',$index);
$rand = rand(000000,999999) . time();
$reqEmailVerification = false;
$mailSendingAddress = "Tamin Support<admin@tamin.com>";
$adminEmailId = "admin@tamin.com";
define("FB_APP_ID", "");
define("EMAIL_ENGINE", "gmail");
define('MAIL_SENDING_ADDRESS', 'admin@tamin.com'); //test@gmail.com
define('MAIL_PASSWORD', '2018Strong'); //gmail app password.
define('MAIL_FROM_EMAIL', 'admin@tamin.com');
define('MAIL_REPLY_EMAIL', 'admin@tamin.com');
define('MAIL_FROM_NAME', 'Tamin Support');
define('MAIL_REPLY_NAME', 'Tamin Support');
define('IMGPREFIX',$rand."__");
define('FILE_PREFIX',time()."_");
define("IMGTHUMBSIZE",100);        //thumb size will be 160X160;
define("IMGTHUMBPREFIX","thumb_");
define("IMGSIZE",1600);          //Image Maximum height or width
define("IMGQUALITY",90);
define('REQEMAILVERIFICATION',$reqEmailVerification);
define("MAILSENDINGADDRESS",$mailSendingAddress);
define("ADMINEMAILID",$adminEmailId);
define("URLSHORTNER_KEY", "");
define("T_ACCOUNTSID","AC2dc8b5c2eba01648092eedeb4faa9c37");
define("T_AUTHTOKEN","4fff2d256529c1eca79b31db6b372145");
define("T_NUMBER","447481340200");
define('IMGDIR','images/');
define('PHOTODIR','uploads/images/');
define('VIDEODIR','uploads/videos/');
define('PHOTOURL',SITEURL . PHOTODIR);
define('VIDEOURL',SITEURL . VIDEODIR);
define("ADMINDIR","/");
define("MIN_WITHDRAW","2500");
define("MIN_DEPOSIT","100"); //100DIR //bank transfer
define("MIN_DEPOSIT_CC","100"); //20DIR
define("CC_DEPOSIT_FEE","6"); //% in percent
define("MAX_WITHDRAW",false);
define("WITHDRAWAL_FEE",100);
define("REMITTANCE_MIN_TRANSFER","50");
define("CURRENCY_MARGIN",0.5);
define("ENC_KEY", "orij3-i8y2");
define("SIGNUP_BONUS_INDIVIDUAL","10");
define("CURRENCY", "Rs");
define("CURR", "Rs");
define("GST","0");
define("ADMIN_ID", "-1");
define("REFUND_FEE", "5"); //percentage
define("FRAUD_FEE","100");
define("ALL_TXN","0.3");
define("CURRENCY_BIAS","2.5");

$includes_dir = "includes/";
$classes_dir = "application/models/classes/";
$config_dir = $includes_dir . "/config/";
$functions_dir = $includes_dir . "/functions/";
$img_dir = "images/";
$views_dir = "application/views/";
$models_dir = "application/models/";

define("FILE_UPLOAD_DIR","files/");

$abs_models_dir = SITEURL . $models_dir;
define('ABS_VIEWS_DIR', SITEURL . $views_dir);


$img_delete="<img src='".$img_dir . "delete.png' title='Delete' width='20px' height='20px'>";
$img_edit="<img src='".$img_dir . "pen.png' title='Edit' width='20px' height='20px'>";
$img_users="<img src='".$img_dir . "users.png' title='Users' width='20px' height='20px'>";
define("DbPrefix","dp_");
$globals['TransactionType']=array(0=>'Payment',1=>'Deposit',2=>'Payout',3=>'Escrow',4=>'Signup',5=>'Commission',6=>'Refund',7=>'Membership',8=>'Cash back',9=>"Bank Transfer",10=>"Holdback amount");
$globals['TransactionStatus']=array(0=>'Pending',1=>'Completed',2=>'Cancelled',3=>'Refunded',5=>'Pending',6=>'Chargeback',10=>'Failed',7=>"Pending Refund");
$globals['PaymentType']=array(0=>'product',1=>'subscription',2=>'donation',3=>'payment');
$globals['userStatus']=array(0=>"Pending Email Verification",1=>"Not Verified",2=>"Verified",3=>"Suspended");
$globals['country_code'] = array("singapore"=>"sg", "indonesia"=>"id", "malaysia"=>"my", "denmark"=>"dk","united kingdom"=>"uk");
$globals['acc_typ'] = array("client"=>1,"broker"=>2, "staff"=>3);
$globals['acc_typ_word'] = array("1"=>"client","2"=>"broker", "3"=>"staff");
?>