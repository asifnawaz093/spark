<?php
$session = FC::getClassInstance("Session");
$Meta = FC::getClassInstance("Meta");
$fc = FC::getInstance();
if($session->isLoggedIn()){
 FC::getClassInstance("TimeZones")->setMyTimeZone();
}
$Meta->page = $fc->getController(); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fc->meta['title']; ?></title>
<meta name="DESCRIPTION" content="<?php echo $fc->meta['description']; ?>" />
<meta name="KEYWORDS" content="<?php echo $fc->meta['keywords']; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta property="og:title" content="<?php echo $fc->meta['title']; ?>" />
<meta property="og:locale" content="en_GB" />
<meta property="og:type" content="<?php echo 'website'; ?>" />
<meta property="og:url" content="<?php echo SITEURL; ?>" />
<meta property="og:image" content="<?php echo SITEURL; ?>images/logo.png" />
<meta property="og:description" content="<?php echo $fc->meta['description']; ?>" />
<meta property="fb:app_id" content="<?php echo FB_APP_ID; ?>" />
<link rel="canonical" href="<?php echo isset($fc->meta['canonical']) ? $fc->meta['canonical'] : ''; ?>" />
<?php if(isset( $fc->meta['properties']) ){
 foreach($fc->meta['properties'] as $property){
  echo "<meta property='".$property['property']."' content='".$property['content']."' />";
 }
 } ?>
 
<script type="text/javascript">
var SITEURL = "<?php echo SITEURL; ?>";
</script>
<link rel="icon"  type="image/png" href="<?php echo SITEURL.'images/favicon.jpg'; ?>" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,700,800,300,400italic' rel='stylesheet' type='text/css'>
<?php
if(isset($fc->vars['input_date'])){
	$fc->js_files[] = "//cdn.jsdelivr.net/momentjs/latest/moment.min.js";
	$fc->js_files[] = "//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js";
	$fc->css_files[] = "//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css";
}
if(isset($fc->vars['datatable'])){
	echo "<script type='text/javascript' src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>";
	echo "<link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'>";
}
if(isset($fc->vars['tinymce'])){
	echo "<script type='text/javascript' src='".SITEURL . "tinymce/js/tinymce/tinymce.min.js'></script>";
}
	$css_files = ["styles/styles.css","styles/contents.css","styles/oclibs.css","styles/glyphicons.css","styles/icons.css"];
 $sync_js = ['scripts/jquery-1.10.1.min.js','scripts/bootstrap.min.js','scripts/scripts.js'];
 $implode_sjs = implode(",", $sync_js);
 $async_js = ['scripts/simply-toast.min.js','scripts/newscripts.js','scripts/jqueryFunctions.js','scripts/getPages.js','scripts/jquery.scrollTo.js'];
  if(!empty($fc->min_js)) $async_js = array_merge($async_js, $this->min_js);
  if(!empty($fc->min_css)) $css_files = array_merge($css_files, $this->min_css);
  
 $implode = implode(",", $css_files);
 $implode_js = implode(",", $async_js);
 if(COMBINE){
  echo '<link rel="stylesheet" rev="stylesheet" href="'.SITEURL.'min/index.php?f='.$implode.'">';
  echo '<script src="'.SITEURL.'min/index.php?f='.$implode_sjs.'" type="text/javascript"></script>';
  echo '<script async="async" src="'.SITEURL.'min/index.php?f='.$implode_js.'" type="text/javascript"></script>';
 }
 else{
  foreach($css_files as $css){ echo '<link rel="stylesheet" rev="stylesheet" href="'.SITEURL . $css.'">';}
  foreach($sync_js as $js){ echo '<script src="'.SITEURL . $js . '" type="text/javascript"></script>'; }
  foreach($async_js as $js){ echo '<script async="async" src="'.SITEURL . $js . '" type="text/javascript"></script>'; }
 }

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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-86968410-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body class="container_<?php echo $fc->page; ?>">
 <header class="site-header" role="banner">
  <div class="topbar">
    <div class="menu_inner container clearfix">
     <div class="logo pull-left">
      <a href="<?php echo SITEURL; ?>"><img width="200px" alt="<?php echo SITE_TITLE; ?>" src="<?php echo SITEURL; ?>images/logo.png"></a>
     </div><!-- /class="nav_logo" -->
     <div class="pull-right">
      <span class="pull-right visible-xs visible-sm visible-md textwhite" id="mobile_trigger"><i class="glyphicon glyphicon-list"></i></span>
      <ul class="mega_main_menu_ul hidden-sm hidden-md hidden-xs"><?php FC::getInstance()->loadTemplate("topmenu"); ?></ul>
     </div>
    </div><ul id="mobile_menu_top" class="mega_main_menu_ul nodisplay"><?php FC::getInstance()->loadTemplate("topmenu"); ?></ul>
  </div><!-- /class="topbar" -->
  
 </header>
 