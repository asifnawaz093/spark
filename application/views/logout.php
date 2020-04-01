<?php
 session_start();
 unset($_SESSION['ad_user']);
 if(isset($_COOKIE['ad_user'])){
  setcookie("ad_user", "", time()-60*60*24*14);
 }
 if(isset($_COOKIE['my_page'])){
  setcookie("my_page", "", time()-60*60*24*14);
 }
 if(isset($_SESSION['ad_ad'])){ unset($_SESSION['ad_ad']); }
   if(isset($_SESSION['ad_st'])){ unset($_SESSION['ad_st']); }
 if(isset($_SESSION['ad_tch'])){ unset($_SESSION['ad_tch']); }
 session_destroy();
 header("Location: ".SITEURL);
?>