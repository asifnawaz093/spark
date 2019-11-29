<?php
class Mail{
 public $to;
 public $subject;
 public $message;
 public $from = "info@asitsol.net";
 public $header;
 public $near=0;
 public $unsbscription = false;
public function mailDesignHeader(){
 return '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "https://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head></head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0; padding: 0;">
        <div id="wrapper" style="width: 80%; margin: 0 auto;">
        <div id="header">
            <div id="head-top">
		<div id="head-logo" style="width: 110px; float: left;">
			
		</div>
		<div id="site-title" style="font-size: 1.6em; text-decoration: none;">
			<a href="'.SITEURL.'" rel="home" style="color: #036; text-decoration: none;font-weight:bold; color:#000; font-size: 1.6em;">asitsol.net</a>
		</div>
		</div>
            
        </div>
        <div id="main" style="clear: both; overflow: hidden; background: #fff; padding: 20px; border: 1px solid #a9a9a9;">
        <div id="main-cont">
        <div id="contents" style="margin-top: 0px; min-height: 200px;">';

}

public function mailDesignFooter(){
 return '</div></div></div>
        <div id="footer" style="background: #252525;border-top: 3px solid #801104;padding: 10px;min-height: 40px;">
	<a style="color:#E1E1E1;text-decoration:none; font-weight:bold;" href="'.SITEURL.'">AsitSol</a>
	</div>
        </div>
    </body>
</html>';
}



 public function mail($to, $subject, $message){
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
		return $this->sendMail();
	}

 public function sendMail(){
   $this->header = "From: " . $this->from . "\r\n";
   $this->header .= "Content-type: text/html\r\n";
	if(SENDEMAILS)
	{
	 $message = $this->mailDesignHeader();
	 $message .= $this->message;
	 $message .= $this->mailDesignFooter();
	 return mail($this->to, $this->subject, $message, $this->header);
	}
        else return true; 
 }
 public function CCToAdmin(){
   $this->header = "From: " . $this->from . "\r\n";
   $this->header .= "Content-type: text/html\r\n";
   $this->to = ADMINEMAILID;
     if(SENDEMAILS){
	 return mail($this->to, $this->subject, $this->message, $this->header);
     }
     else return true;
	 
 }

public function printTemplate(){
 return $this->mailDesignHeader(). $this->message. $this->mailDesignFooter();
}

public function unsubscription(){
 if($this->unsbscription){
  return '<span style="font-size:8pt; color: #f8f8f8;padding-left:30px;text-align:center;">If you no longer wish to receive these emails. Please click
   <a style="color:#f8f8f8; font-weight:bold; text-decoration:underline;" href="'.SITEURL.'emailsubs/?email='.$this->to.'">Unsubscribe</a></span>';
 }
}

 
}
