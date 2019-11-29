<?php

class smsSubs extends form{
    public $values;
    
    
    public function sms_form(){ form::table(0,3,3,'','style="margin-top:20px;"');
    form::row('Mobile Number','mNum','Please enter your valid mobile number in formate 0xxx-xxxxxxx',$this->values);
    form::formButtons('Activate','activate','activeteMobile()','button',0); echo "</table>";
    }
    
    public function sms_veri_form(){ form::table(0,3,3,'','style="margin-top:20px;"');
    form::row('Verification Code','mVer','Please enter your valid verification code in formate xxxxx');
    form::formButtons('Verify','verify','verifyMobile()','button',0);
    form::tr(form::td("<a href='#' onclick=getIt('smsSubsContent','getPages/getStuff.php?resendVeri')>Resend Verification Code</a>"));
    echo "</table>";
    }
    
    public function sms_notify_status($id){ return user_pro_info($id,'mSubs'); }
    
    public function subscribers($field){ if($products = find_join_products('m_notify','user_pro','user_id','id',"`$field`='1'",'','`m_notify`.'.$field.',`user_pro`.`mNum`')){
        $i=0;foreach($products as $product){ $mNums[$i] = $product['mNum']; $i++;} }
        return $mNums;
        }
};

class notifications extends mail{
   public $message; public $to; public $mcol; public $ecol;
 public function is_mail_notif($col,$id){ $product = find_product3('e_notify','user_id',$id,$col); if($product=='1') return true; else return false;}
 public function is_sms_notif($col,$id){ $product = find_product3('m_notify','user_id',$id,$col); if($product=='1') return true; else return false;}
 
 
 public function send_sms(){ 
 require_once "../sms/sms.php"; 
 $apikey = "54da49e6f591c2cf1388"; $sms = new sendsmsdotpk($apikey);
 //if ($sms->isValid()) echo "Your key IS VALID"; else echo "KEY: " . $apikey . " IS NOT VALID";
 if ($sms->sendsms($this->to, $this->message, 0)) echo ""; else echo "error ouccured!";
}
public function sending_mail(){ echo $this->to;}
public function send_sms_notif($id){ $col=$this->mcol; if(notifications::is_sms_notif($col,$id)){notifications::send_sms();} }
public function send_mail_notif($id){ $col=$this->ecol; if(notifications::is_mail_notif($col,$id)){ notifications::sendMail(); } }
};


class multiNotifs extends notifications{

 public function smsNotifss($where=''){ 
 $q=mysql_query("select `user_id` from `ad_std` ".$where); if(mysql_num_rows($q)>0){
  while($row=mysql_fetch_array($q)){
  $id=$row['user_id'];
  $mNum=find_product3('user_pro','id',$id,'mNum');
  $this->to=$mNum; 
  notifications::send_sms_notif($id); } }}
  
   public function mailNotifss($where=''){ 
 $q=mysql_query("select `user_id` from `ad_std` ".$where); if(mysql_num_rows($q)>0){
  $emails=""; while($row=mysql_fetch_array($q)){
  $id=$row['user_id'];
  $email=find_product3('user_pro','id',$id,'ad_email');
  $emails.=$email.","; }
  $this->to=$emails;
  notifications::send_mail_notif($id); }}
  
};
?>