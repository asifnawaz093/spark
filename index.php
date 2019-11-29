<?php
require_once('includes/config/hosts.php');
require_once('includes/config/constants.php');
require_once('includes/config/pconfig.php');
require_once('includes/config/paths.php');
require_once('includes/functions/customize.php');
require_once('application/models/front.php');
require_once('application/models/icontroller.php');
require_once('application/models/view.php');
FC::loadClass("Session");
FC::loadClass("Settings");
FC::loadClass("Tools");
require_once('includes/functions/headers.php');
require_once('application/controllers/index.php');
//Initialize the FrontController
$front = FC::getInstance();
FC::loadClass("ObjectHtml");
$front->route();
$front->loadTemplate();

?>
