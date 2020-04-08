<?php $session = FC::getClass("Session");
?>
<?php if(!$session->isLoggedIn()){ ?>
<li class="menu-item about-us"><a href="<?php echo SITEURL; ?>">Home</a></li>
<?php } else{
    if($session->isAdmin()||$session->isManager()||$session->isExecutive()){ ?>
    <li class="menu-item dashboard"><a href="<?php echo SITEURL; ?>#">Dashboard</a></li>
    <?php }else{
    ?><li class="menu-item dashboard"><a href="<?php echo SITEURL; ?>#">Dashboard</a></li><?php }
}
?>
<li class="menu-item about"><a href="<?php echo SITEURL; ?>aboutus" target="_blank">About Us</a></li>
     <?php if(!$session->isLoggedIn()){ ?>

     <li class="menu-item login">
	     <a href="<?php echo SITEURL; ?>login" class="item_link  with_icon">
		     <i class="glyphicon glyphicon-log-in"></i> 
		     <span class="link_content">
			     <span class="link_text textbold">
				Login
			     </span>
		     </span>
	     </a>
     </li>
     <?php } else {
      $user = FC::getClassInstance("Users");
      $id = $session->myId();
     ?>
     <li class="menu-item logout">
	     <a href="<?php echo SITEURL; ?>logout" class="item_link  with_icon">
		     <i class="glyphicon glyphicon-log-out"></i> 
		     <span class="link_content">
			     <span class="link_text">
				     Logout
			     </span>
		     </span>
	     </a>
     </li>

     
     <?php } ?>
     