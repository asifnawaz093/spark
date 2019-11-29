<?php
FC::loadClass("FormTable");
class Login extends FormTable{
  public $width = "450px";
  public $title = "Login";
  public $label = "Please fill the form to login";
  public $ad_email;
  public $ad_pwd;
  public $rememberme = false;
   
  public function createLoginInstance(){
    $_SESSION['ad_user'] = $this->ad_email;
    $ad_pwd = hashing($this->ad_pwd);
    $user = FC::getClassInstance("Db")->getRow("SELECT * FROM `user_pro` WHERE `ad_email`='$this->ad_email'");
    $this->updateUserSession($user["id"]);
    if($this->rememberme){ setcookie("ad_user",$this->ad_email,time()+60*60*24*30);
      setcookie($this->getAccTyp(),$this->ad_email,time()+60*60*24*30);
    }
    $_SESSION[$this->getAccTyp()] = $this->ad_email;
  }
  public function updateUserSession($id_user = false){
    if(!$id_user) $id_user = $_SESSION['id_user'];
    $_SESSION['id_user'] = $id_user;
    $_SESSION['user'] = FC::getClass("Users")->getUser($id_user, "`id`,first_name,last_name,ad_email,profile_picture,acc_typ,status,CONCAT(first_name,' ',last_name) as name");
  }
  public function checkStatus(){
    $ad_pwd = hashing($this->ad_pwd);
    return FC::getClassInstance("Db")->getValue("SELECT `status` FROM `user_pro` WHERE `ad_email`='$this->ad_email' AND `ad_pwd`='$ad_pwd'");
  }
  
  public function addLoginTime(){
    $db = FC::getClassInstance("Db");
    $ad_pwd = hashing($this->ad_pwd);
    $id = $_SESSION['id_user'];
    $time = date("Y-m-d H:i:s");
    $db->insert( array( "inlogs" => array(
              "id_user"   =>  $id,
              "ip"   => FC::getClass("Log")->getClientIP(),
              "time"      =>  $time
              )));
  }
  
  public function authenticate(){
    //$login = new Login();
    $ad_pwd = hashing($this->ad_pwd);
    return (row_count2("user_pro","ad_email",$this->ad_email,"ad_pwd",$ad_pwd)>0) ? true : false;
  }
  
  public function isUserExist($ad_email){ return ( FC::getClassInstance("Db")->getValue("SELECT COUNT(`id`) FROM `user_pro` WHERE `ad_email` = '$ad_email'") > 0 ) ? true : false; }
  
  public function getAccTyp(){
    if(!isset($_SESSION['ad_user'])){ return false;}
    $acc_typ=find_product3("user_pro","ad_email",$_SESSION['ad_user'],"acc_typ");
    switch($acc_typ){
      case 0: $return = "ad_ad"; break;
      case 1: $return = "teacher"; break;
      case 2: $return = "staff"; break;
      case 3: $return = "viewer"; break;
      case 4: $return = "teacher-viewer"; break;
      case 38: $return = "parent"; break;
    }
    return $return;
  }
  
  public function loginForm(){
    echo "<div id='signin'><div id='loginError' style='color:red'></div>";
    
    //echo table(0,5,5,'loginTable');
    $this->tr("<td><label>Email Id</label></td><td><input type='text' name='ad_email' id='ad_email' class='form-control' /></td>");
    //$this->row("Email Id","ad_email");
    $this->type="password";
    $this->tr("<td><label>Password</label></td><td><input type='password' name='ad_pwd' id='ad_pwd' class='form-control' /></td>");
    //$this->row("Password","ad_pwd");
   // $this->tr("<td></td><td class=\"chk_tab\"><input class=\"login_chk\" type='checkbox' id='rememberme' name='rememberme' value='1' /> Keep me Logged In
     //         </td>");
    
    echo "</div>";
    }

  public function loginDiv(){ 
    echo '<div align="center"><fieldset style="width:400px"><legend><h2>Login</h2></legend>';
    $this->loginForm();
    echo '</fieldset></div>';
    }
    
  public function loginPopUp(){
    FC::loadClass("ObjectLayout");
    ObjectLayout::popUpDivTop("login",$this->width,$this->title,$this->label);
    $this->loginForm();
    ObjectLayout::popUpDivBottom();
  }

}

?>