<?php
class UsersCore{
    public function getUserInfoById($id,$column){ return find_product3("user_pro","id",$id,$column); }
    
    public function getReviewers(){ return find_products_by_where("user_pro","acc_typ","1"); }
    
    public function getUserByEmailId($email,$column='*'){ return find_product("user_pro","ad_email",$email,$column); }
    
    public function getUserId($email){ return find_product3("user_pro","ad_email",$email,'id'); }
    
    public function getUserNameById($id){
        return find_product3("user_pro","id",$id,"first_name");
    }
    
    public function getName($id_user){
        $name = FC::getClassInstance("Db")->getRow("SELECT `first_name`, `last_name` FROM `user_pro` WHERE `id` = '$id_user' LIMIT 1");
        return $name['first_name'] . " " . $name['last_name'];
    }
    public function getUsers($col="*"){
        return FC::getClassInstance("Db")->getRows("SELECT $col FROM `user_pro`");
    }
    public function getUser($id, $col="*"){
        return FC::getClassInstance("Db")->getRow("SELECT $col FROM `user_pro` WHERE `id` = '$id'");
    }
    public function getOverview($id_user){
        $name = FC::getClassInstance("Db")->getRow("SELECT `overview` FROM `user_pro` WHERE `id` = '$id_user' LIMIT 1");
        return $name['overview'] ;
    }
    
    public function rounteImage($path){
	if ( file_exists( $path ) ) {
	    return SITEURL. $path;
	}
	else {
	    return SITEURL."user-images/DSC05241.jpg";
	}
    }
    
    public function getImage($id_user){
        $image = FC::getClassInstance("Db")->getValue("SELECT `profile_picture` FROM `user_pro` WHERE `id` = '$id_user'");
		if($image==''){
			return SITEURL."user-images/DSC05241.jpg";
		}
		else {
		    return $this->rounteImage("user-images/".$image);
		}
    }
    
    public function getThumb($id_user, $size = ""){
	$image = $this->getImage($id_user);
	$exp = explode(".", $image);
	$extension = end($exp);
	$basename = basename($image, ".$extension");
	$thumbname = "thumb_" . $basename . $size . ".$extension";
	return $this->rounteImage("user-images/".$thumbname);
    }
    
	public function getThumbImage($id_user){
        if($id_user == Session::my("id")){ $image = Session::my('profile_picture'); }
		if(!isset($image))
            $image = FC::getClassInstance("Db")->getValue("SELECT `profile_picture` FROM `user_pro` WHERE `id` = '$id_user'");
        
		if($image==''){
            return SITEURL."images/default.jpg";
        } else {
			return $path = SITEURL.PHOTODIR.$image;
			if ( getimagesize($path) !== false ) {
				return $path;
			} else {
				return SITEURL."images/default.jpg";
			}
        }
    }
    
    public function getInterGeneres(){
        return FC::getClassInstance("Db")->getRows("SELECT * FROM `int_genarea`");
    }
    
    public function getBusinessCredit($id_user){
	return FC::getClassInstance("Db")->getRows("SELECT * FROM `business_data` where `id` = '$id_user'");
    }
    
    public function getEmail($id_user){
        return FC::getClassInstance("Db")->getValue("SELECT `ad_email` FROM `user_pro` WHERE `id` = '$id_user'");
    }
    
    public function isIdExist($id_user){
        return FC::getClassInstance("Db")->getValue("SELECT COUNT(`id`) FROM `user_pro` WHERE `id` = '$id_user'");
    }
    
    public function getCommission($id_user){
        $comm = FC::getClassInstance("Db")->getValue("SELECT `commission` FROM `user_pro` WHERE `id` = '$id_user' LIMIT 1");
        return $comm;
    }
    
    public function getClientType($id_user=false){
        if(!$id_user)
            $id_user = FC::getClassInstance("Session")->myId();
        $type = FC::getClassInstance("Db")->getValue("SELECT `user_type` FROM `user_pro` WHERE `id` = '$id_user'");
        if( $type==0 ){
            return "normal";
        }
        else {
            return "permanent";
        }
    }
    
    public function isUserOnline($id_user){
		$last_activity = FC::getClassInstance("Db")->getValue("SELECT `last_active` FROM `user_pro` WHERE `id` = '".$id_user."'");
		$last = strtotime($last_activity) + (ACTIVE_TIME_LIMIT / 1000);
		$date = date("Y-m-d H:i:s", time());
		$time = strtotime($date);
		if($last > $time){
			return true;
		}
		else{
			return false;
		}
   }
   
   public function getOnlineInterpreters($limit = 10){
        $time = time() - (ACTIVE_TIME_LIMIT / 1000) ;
        $date = date("Y-m-d H:i:s", $time);
        return FC::getClassInstance("Db")->getRows("SELECT `id` FROM `user_pro` WHERE `last_active` > '".$date."' AND `acc_typ` = 1 LIMIT $limit");
   }
   
   
   public function updateOnlineStatus($id_user){
    $date = date("Y-m-d H:i:s", time());
    return FC::getClassInstance("Db")->execute("UPDATE `user_pro` SET `last_active` = '$date' WHERE `id` = '".$id_user."'");
   }
   
   public function updateOnCallStatus($id_user, $status = 1){
    return FC::getClassInstance("Db")->execute("UPDATE `user_pro` SET `is_on_call` = '$status' WHERE `id` = '".$id_user."'");
   }
   
   public function getUserIdByFriendlyName($friendly){
    return FC::getClassInstance("Db")->getValue("SELECT `id` FROM `user_pro` WHERE `friendly_name` = '$friendly'");
   }
   
   public function createFriendlyName($email){
	$email = explode("@", $email);
	$friendly = FC::getClassInstance("ObjectPhp")->removeSpecialChars($email[0]);
	$i = 0;
	do{
	    if($i != 0){ $friendly .= $i;}
	    $i++;
	}
	while($this->getUserIdByFriendlyName($friendly));
	return $friendly;
   }
   
    public function getFriendly($id){
	return FC::getClassInstance("Db")->getValue("SELECT `friendly_name` FROM `user_pro` WHERE `id` = '$id'");
    }
    
    public function insertFriendlyName($friendly, $id){
	return FC::getClassInstance("Db")->execute("UPDATE `user_pro` set `friendly_name` = '$friendly' WHERE `id` = '$id'");
    }
}

?>