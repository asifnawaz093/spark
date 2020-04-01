<?php
$debug=false;
if(isset($_GET['debug'])){ define("DEBUG",true); }else{ define("DEBUG",$debug); }
if(isset($_GET['profiling'])){ define("SQL_PROFILING",false); }else{ define("SQL_PROFILING",false); }
if(DEBUG){
    ini_set("display_errors", 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else {
    ini_set("display_errors", "Off");
    ini_set('display_startup_errors', "Off");
    error_reporting(0);
}
define("SITEACTIVE", true); 
define('HOSTNAME','localhost');
define('DBNAME','spark');
define('DBUSER','root');
define('DBPASS','');
define("OCSALT","hTu94Djag");
define('DBPREFIX', '');
define("PROTOCOL", "http");
define("HTTP", PROTOCOL);
define("TLD", "");
define("SITEHOST", "localhost/spark");
define("SITEROOT",PROTOCOL."://".SITEHOST.".".TLD."/");
define('SITEURL', PROTOCOL."://".SITEHOST."/");
define('URLSIMPLE',SITEHOST.'.'.TLD);
define('SITEDIR', "../../" . dirname(__FILE__) . "/");
define("CACHE", false);
define("COMBINE", false);
define("SENDEMAILS",true);
?>