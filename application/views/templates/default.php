<?php
$session = FC::getClassInstance("Session");
$fc = FC::getInstance();
if(!isset($_GET['ajax'])){
    if(!isset($_GET['bodyoff']))
    $fc->loadHeader(); else exit();
    	echo "<div id='panelOptions' class='dslc-clearfix'>";
		 if($session->isAdmin()){ 
		 	//include  'includes/leftmenu.php';
		 }
}

echo $fc->getBody();

if(!isset($_GET['ajax'])){
    echo "</div>";
    echo $fc->loadFooter();
}

?>