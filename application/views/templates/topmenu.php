<?php $session = FC::getClass("Session");
?>
<?php if(!$session->isLoggedIn()){ ?>
<li class="menu-item about-us"><a href="<?php echo SITEURL; ?>">Home</a></li>
<?php } else{
    if($session->isAdmin()||$session->isManager()||$session->isExecutive()){ ?>
    <li class="menu-item dashboard"><a href="<?php echo SITEURL; ?>cp">Dashboard</a></li>
    <?php }else{
    ?><li class="menu-item dashboard"><a href="<?php echo SITEURL; ?>dashboard">Dashboard</a></li><?php }
}
?>
<li class="menu-item about"><a href="<?php echo SITEURL; ?>about-us" target="_blank">About Us</a></li>
<li class="menu-item contact"><a href="mailto:support@tamin.com">Contact Us</a></li> 
     <?php if(!$session->isLoggedIn()){ ?>
     <li class="menu-item login">
	     <a href="<?php echo SITEURL; ?>signup/" class="item_link  with_icon">
		     <i class="glyphicon glyphicon-user"></i> 
		     <span class="link_content">
			     <span class="link_text textbold">
				Register
			     </span>
		     </span>
	     </a>
     </li>
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
     <li class="profile-item">
        <div class="userimage">
         <a href="<?php echo SITEURL; ?>profile"><img alt="" class="profilimg" src="<?php echo $user->getThumbImage($id); ?>" />
         <span class="head-greetings">Hi <?php echo Session::my("first_name"); ?>!</span></a>
        </div>
     </li>
     
     <?php } ?>
     