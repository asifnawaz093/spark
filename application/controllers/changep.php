<?php
class changep implements IController{
   public function main() {
      $view = new View();
      $fc = FC::getInstance();
      $db = FC::getClassInstance("Db");
		$session = FC::getClassInstance("Session");
		$session->session();
      $my_id = $session->myId();
      $email= Session::get("ad_user");
      if(isset($_POST['add_update'])){
        $old = hashing(Tools::getValue('old'));
        $new = Tools::getValue('new');
        $cnew = Tools::getValue('cnew');
        $hash = hashing($new);;
        if(strlen($new)> 0){
            if($new==$cnew){
               if (row_count2('user_pro','id',$my_id,'ad_pwd',$old)>0) {
                 if( $db->execute("UPDATE `user_pro` SET `ad_pwd` = '$hash' WHERE `id` = '$my_id' LIMIT 1") ) {

                   $fc->success = "Password updated";
                 }
                 else{
                   $fc->error = "Error updating password.";
                 }
               }
               else {
                  $fc->error = "You profile password is incorrect.";
               }
             }
             else {
               $fc->error = "Password does not match .";              
             }
				 
        }else{
				$fc->error = "Enter new password.";
		  }
         Tools::redirect();
      }   

      if(!file_exists('../views/changep.php')){
          $fc->_controllerFile = "404page";
      }
      $result = $view->render('../views/changep.php');
      $fc->setBody($result);		
	}
   
}