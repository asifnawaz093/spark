<?php

###############################################################################



# PROGRAM     : EPAY ENTERPRISE                                               #



# VERSION     : 4.1                                                           #



# AUTHOR      : DMITRY PEREUDA                                                #







# COMPANY     : ALSTRASOFT	                                              #



# COPYRIGHTS  : (C)2009 ALSTRASOFT. ALL RIGHTS RESERVED                       #



#         COPYRIGHTS BY (C)2009 ALSTRASOFT. ALL RIGHTS RESERVDED  	      #



# LICENSE KEY : C3FA-76A1-83A4-C2B4-AE1F-1D5A-14ED-1DCA                       #



###############################################################################



#    THIS FILE IS PART OF EPAY SCRIPT - THE NEW UNIVERSAL PAYMENT GATEWAY     #



#               	     DEVELOPED BY ALSTRASOFT                          #



###############################################################################



#    ALL SOURCE CODE, IMAGES, PROGRAMS, FILES INCLUDED IN THIS DISTRIBUTION   #



#         COPYRIGHTS BY (C)2009 ALSTRASOFT. ALL RIGHTS RESERVDED  	      #



###############################################################################



#       ANY REDISTRIBUTION WITHOUT PERMISSION OF ALSTRASOFT AND IS            #



#                            STRICTLY FORBIDDEN                               #



###############################################################################



#         COPYRIGHTS BY (C)2009 ALSTRASOFT. ALL RIGHTS RESERVDED  	      #



###############################################################################











#         COPYRIGHTS BY (C)2009 ALSTRASOFT. ALL RIGHTS RESERVDED  	      #









###############################################################################



error_reporting(E_ERROR | E_WARNING | E_PARSE);



if(!ini_get('safe_mode'))set_time_limit(3600);



set_magic_quotes_runtime(0);



ignore_user_abort(true);



###############################################################################



$data['PostSent']=false;



$data['ScriptLoaded']=true;



if (!$_COOKIE["ln"]){



  $data['lang_ch']=$data['DefaultLanguage'];



  setcookie("ln", $data['lang_ch']);	



}



$data['lang_ch'] = $_COOKIE["ln"];



###############################################################################



$data['Path']=dirname(__FILE__);


$data['Prot']='https';

//if($_SERVER["HTTPS"]=='on')$data['Prot']='https';else $data['Prot']='http';



$data['Templates']="{$data['Path']}/templates";



$data['BannersPath']="{$data['Path']}/images/banners";



$data['SinBtnsPath']="{$data['Path']}/images/buttons/single";



$data['DonBtnsPath']="{$data['Path']}/images/buttons/donations";



$data['SubBtnsPath']="{$data['Path']}/images/buttons/subscriptions";



$data['ShopBtnsPath']="{$data['Path']}/images/buttons/shopcart";







if($data['Folder'])$data['Folder']="/{$data['Folder']}";



$data['Addr']="{$_SERVER['REMOTE_ADDR']}";



$data['Host']="{$data['Prot']}://{$_SERVER['HTTP_HOST']}{$data['Folder']}";



$data['Images']="{$data['Host']}/images";



$data['Banners']="{$data['Images']}/banners";



$data['SinBtns']="{$data['Images']}/buttons/single";



$data['DonBtns']="{$data['Images']}/buttons/donations";



$data['SubBtns']="{$data['Images']}/buttons/subscriptions";



$data['ShopBtns']="{$data['Images']}/buttons/shopcart";







$data['Admins']=SITEURL . "admins";



$data['Members']=SITEURL;







$data['Home']=SITEURL;





$data['DbPrefix']=DbPrefix;



###############################################################################





##############################################################################



function get_post(){



	global $_POST;



	$result=array();



	foreach($_POST as $key=>$value)$result[$key]=$value;



	reset($_POST);



	return $result;



}

 

            

####################################################################################################

 function create_graph($transaction_type,$date1,$date2)

		{

         	global $data;

       $sql= "SELECT `id`,`tdate`,`amount`,`type` FROM `{$data['DbPrefix']}transactions`"." WHERE `tdate` BETWEEN '{$date2}'  AND '{$date1}'" ."AND `type`=$transaction_type";     

        

            $depositeData=db_rows($sql);

         //  echo "<pre>";    print_r($depositeData);   echo "</pre>"; 

           return $depositeData;

}







##############################################################################









function Block_User()

	{       

		global $data;

		$ipdata=db_rows("SELECT `id`,`last_ip`,`username` FROM `{$data['DbPrefix']}members`");        

		return $ipdata;

	}



function set_ip_block_member($uid, $ip_block_member)

	{	

		global $data;

		$sql="UPDATE `{$data['DbPrefix']}members`"." SET `ip_block_member`={$ip_block_member}"." WHERE `id`={$uid}";

		db_query($sql);

	}

    

    function is_member_block($username)

{

		$user_id=get_member_id($username);

		$user_info=get_member_info($user_id);

		$block_ip=$user_info['ip_block_member'];

      	return $block_ip;

    

    }

###############################################################################

function getLocationInfoByIp($ipAddress)

	{

		$ip = $ipAddress;

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

		$ip_data->geoplugin_countryName;

		return $ip_data->geoplugin_countryName;

	}



function is_member_login_same_country($username,$password)

	{

		$user_id=get_member_id($username,$password);

		$user_info=get_member_info($user_id);

		$register_ip=$user_info['last_ip'];

 		$register_country = getLocationInfoByIp($register_ip);

return $register_country;

}



##############################################################################



function protect($buffer){



	global $data, $_SERVER, $_SESSION;



	if($data['ProtectHtml']&&$_SESSION['login'])return encrypt_pages($buffer);



	else return $buffer;



}







function prepare($buffer){



	return protect($buffer);



}







function show($template){



	global $data, $post;



	//echo $template;



	if(file_exists($template))include($template);



	else echo("Template \"{$template}\" not found!");



}







function display($path=''){

	global $data;

	ob_start();

	if($_SESSION['uid'])

		{	$id=$_SESSION['uid'];

			$sql="SELECT `ip_block_member` FROM `{$data['DbPrefix']}members`"." WHERE `id`={$id}";

			$ip_block=db_rows($sql); 

			$ip_block_member=$ip_block[0]['ip_block_member'];

			

			if ($ip_block_member==0)

				{ 

					unset($_SESSION['login']);

					unset($_SESSION['uid']);

					header("Location:{$data['Host']}/members/block.php");

					exit;

				}

		}

	

	ob_start("prepare");



	if($path){



		$path="/{$path}";



	}

    

	if (!$data['lang_ch']){



		$data['lang_ch']=$data['DefaultLanguage'];

        

	}

	

	if ($path == "" ){$path="/langs/{$data['lang_ch']}".$path;}

	if ($path == "/members" ){$path="/langs/{$data['lang_ch']}".$path;}







	
        show("{$data['Templates']}{$path}/template.header.htm");


	show("{$data['Templates']}{$path}/template.{$data['PageFile']}.htm");



	show("{$data['Templates']}{$path}/template.footer.htm");



	ob_end_flush();



}







function showpage($template){



	global $data;



	ob_start("prepare");



	show("{$data['Templates']}/{$template}");



	ob_end_flush();



}







function showmenu($mode, $path=''){



	global $data;



	$data['mode']=$mode;



	if($path)$path="/{$path}";



	if (!$data['lang_ch']){



		$data['lang_ch']=$data['DefaultLanguage'];



	}



	if ($path != "/admins"){$path="/langs/{$data['lang_ch']}".$path;}



	show("{$data['Templates']}{$path}/template.menu.htm");

	

}



function showbanner(){



	global $data;



	show("{$data['Templates']}/template.banners.htm");



}



###############################################################################



$data['cid']=null;







function show_menu_langs(){



	global $data;

	



	$langs_dir_obj = dir($data['Templates']."/langs/");

	while($entry = $langs_dir_obj->read()){

     

     if ($entry != "." && $entry != ".." && $entry != "default") {

       

       if($_COOKIE["ln"]==$entry || (!$_COOKIE["ln"] && $data['DefaultLanguage']==$entry)){$select="selected";}

       else{$select="";}



       echo "<option value='".$entry."' ".$select.">".$entry."</option>";



     }



	}



}



function show_default_select_lang(){



	global $data;

	



	$langs_dir_obj = dir($data['Templates']."/langs/");

	while($entry = $langs_dir_obj->read()){

     

     if ($entry != "." && $entry != ".." && $entry != "default") {

       

       if($data['DefaultLanguage']==$entry){$select="selected";}else{$select="";}



       echo "<option value='".$entry."' ".$select.">".$entry."</option>";



     }



	}



}


function db_query($statement,$print=false){
    return FC::getClass("Db")->execute($statement);
}
function newid(){
    return FC::getClass("Db")->insertId();
}
function db_count($result){
    return (int)@mysql_num_rows($result);
}
function db_row($statement,$print=false) {
    return FC::getClass("Db")->getRow($statement);
}
function db_rows($statement,$print=false) {
    return FC::getClass("Db")->getRows($statement);
}



###############################################################################



function verify_email($email){



	return !(bool)ereg("^.+@.+\\..+$", $email);



}







function verify_username($username){



	return !(bool)ereg("^[a-zA-Z0-9]+$", $username);



}







function gencode(){



	global $data;



	list($usec, $sec)=explode(' ', microtime());



	$rand=(float)$sec+((float)$usec*100000);



	srand($rand);



	if($data['TuringNumbers']){



		return (string)rand(pow(10, $data['TuringSize']-1), pow(10, $data['TuringSize'])-1);



	}else{



		return strtoupper(substr(md5(rand()), rand(1, 26), $data['TuringSize']));



	}



}







function around($amount){



	return sprintf("%6.2f", $amount);



}







function encode($number, $size){



	$result='';



	$length=strlen($number);



	for($i=0;$i<$length-$size;$i++)$result.='X';



	return $result.substr($number, $length-$size, $length);



}







function is_changed($number){



	return (bool)ereg("^[0-9]+$", $number);



}







function is_number($text){



	if(!is_changed($text))return true;



	return (bool)is_changed($text);



}







function showselect($values, $current=null){



	$result='';

	$exclute = array ('template.faq.htm','template.banners.htm') ;

	foreach($values as $key=>$value){



		$result.=



			"<option value=\"{$key}\"".



			($current!=null?($current==$key?' selected':''):'').



			">{$value}</option>"



		;



	}



	return $result;



}







function read_csv( $filename, $break) {



	if ( $file=fopen($filename,"r") ) {



		while ($content[]=fgetcsv($file,1024,$break));



		fclose($file);



		array_pop($content);



		return $content;



	}



}



###############################################################################



function prndate($date){



	global $data;



	if($date=='0000-00-00 00:00:00')return '---';



	else return date($data['DateFormat'], strtotime($date));



}







function prnintg($number){



	return number_format($number, 0, '', ',');



}







function prnsum($sum){



	return (float)str_replace(",", "", $sum);



}







function prnsumm($summ){



	global $data;



	$summ=str_replace(",", ".", $summ);



	return number_format(($summ>0?$summ:-$summ), $data['CurrSize'], '.', ',');



}



function prnsumm_two($summ){



	global $data;



	$summ=str_replace(",", "", $summ);



	$summn = $summ>0?$summ:-$summ;

	

	return $summn;



}







function prnpays($summ, $splus=true){



	global $data;



	if($summ<0)$color='red';else $color='green';



	return



		"<font color={$color}>".



		($summ>=0?($splus?'+':''):'-').$data['Currency'].prnsumm($summ).



		'</font>'



	;



}





function prnpays_fee($summ, $splus=true){



	global $data;



	if($summ!=0)

	{

	$color='red';

	return



		"<font color={$color}>".



		($summ>=0?($splus?'+':''):'-').$data['Currency'].prnsumm($summ).



		'</font>'



	;

	}

	else

	{

	 $color='maroon';

	return



		"<font color={$color}>---</font>";

		}



}







function prnfees($summ){



	return $summ!=0?prnpays($summ):'<font color=maroon>---</font>';



}







function prntext($text){



    $search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript



                 '@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags



                 '@([\r\n])[\s]+@',                // Strip out white space



                 '@&(quot|#34);@i',                // Replace HTML entities



                 '@&(amp|#38);@i',



                 '@&(lt|#60);@i',



                 '@&(gt|#62);@i',



                 '@&(nbsp|#160);@i',



                 '@&(iexcl|#161);@i',



                 '@&(cent|#162);@i',



                 '@&(pound|#163);@i',



                 '@&(copy|#169);@i',



                 '@&#(\d+);@e');                    // evaluate as php







$replace = array ('',



                 '',



                 '\1',



                 '"',



                 '&',



                 '<',



                 '>',



                 ' ',



                 chr(161),



                 chr(162),



                 chr(163),



                 chr(169),



                 'chr(\1)');







return preg_replace($search, $replace, $text);



}



function balance($summ){



	return prnpays($summ, false);



}







function prnuser($uid){



	if($uid>0)return get_member_username($uid);



	else return 'system';



}







function get_files_list($path){



	$result=array();



	if(@file_exists($path)){



		$handle=@opendir($path);



		while(($file=@readdir($handle))!==false){



			if($file!='.'&&$file!='..'){



				$x=strtolower(substr($file, -4));



				if($x&&$x=='.jpg'||$x=='.gif'||$x=='.png')$result[]="{$file}";



			}



		}



	}



	return $result;



}





function user_infor($uid){



        global $data;



        $result=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}members`".



                " WHERE `id`={$uid} LIMIT 1");



       


        return $result[0];



}












function get_html_templates(){



	global $data;



	$result=array('0'=>'--');



	if(@file_exists($data['Templates']."/langs/default")){



		$handle=@opendir($data['Templates']."/langs/default");



		while(($file=@readdir($handle))!==false){

			if($file!='.'&&$file!='..'){



				$x=strtolower(substr($file, -4));



				if($x&&$x=='.htm'){$result[$file]="{$file}";}else{

					$handle_mem=@opendir($data['Templates']."/langs/default/".$file);

					while(($file_mem=@readdir($handle_mem))!==false){

					  if($file_mem!='.'&&$file_mem!='..'){

					  	$x_mem=strtolower(substr($file_mem, -4));

					  	if($x_mem&&$x_mem=='.htm')$result[$file."/".$file_mem]="{$file}/{$file_mem}";

					  }

					}

				}



			}



		}



	}



	return $result;



}





function function1($siteUrl,$formPath,$resultUrl,$name,$price_string,$btc,$description,$type,$style,$price_currency_iso,$custom) {

							

							$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0';

							$sessPath   = ini_get('session.save_path'); 

							$ckfile = tempnam ($sessPath, "CURLCOOKIE");							

							

							$ch = curl_init();

							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

							curl_setopt($ch, CURLOPT_URL, $siteUrl.$formPath);

							

							curl_setopt($ch, CURLOPT_POST, true);

							curl_setopt($ch, CURLOPT_POSTFIELDS,'button[name]='.$name.'&button[price_string]='.$price_string.'&button[btc]='.$btc.'&button[description]='.$description.'&button[type]='.$type.'&button[style]='.$style.'&button[price_currency_iso]='.$price_currency_iso.'&button[custom]='.$custom);

							

							curl_setopt($ch, CURLOPT_REFERER, $siteUrl.$resultUrl);

							curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);

							curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);

							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

							curl_setopt($ch, CURLOPT_HEADER, true);

							

							curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

							

							

							

							

							$output = curl_exec($ch);

							

							

							

							curl_close($ch);

							

							

							$ch = curl_init();

							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

							curl_setopt($ch, CURLOPT_URL, $siteUrl.$resultUrl);

						    curl_setopt($ch, CURLOPT_POST, 1);

							curl_setopt($ch, CURLOPT_POSTFIELDS,'button[name]='.$name.'&button[price_string]='.$price_string.'&button[btc]='.$btc.'&button[description]='.$description.'&button[type]='.$type.'&button[style]='.$style.'&button[price_currency_iso]='.$price_currency_iso.'&button[custom]='.$custom);

							

							



							curl_setopt($ch, CURLOPT_REFERER, $siteUrl.$resultUrl);

							

							curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);

							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

							curl_setopt($ch, CURLOPT_HEADER, true);

							

							curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

							$output = curl_exec($ch);

						

						 $id=substr($output,710,32);

							return $id;

							unset($ch);

							unlink($ckfile);

}



###############################################################################



function send_email($key, $post){



	global $data;

	

 

 

	$template=db_rows(



		"SELECT `name`,`value` FROM `{$data['DbPrefix']}emails`".



		" WHERE `key`='{$key}'"



	);



	$text=$template[0]['value'];



	$subject=$template[0]['name'];







	if($post['username']){



		$text=str_replace("[username]", $post['username'], $text);



		$text=str_replace("[usersite]", "{$data['Host']}/?rid={$post['username']}", $text);



	}	

	if($post['username_rec']){



		$text=str_replace("[username_rec]", $post['username_rec'], $text);



		$text=str_replace("[usersite]", "{$data['Host']}/?rid={$post['username']}", $text);



	}



	if($post['password'])$text=str_replace("[password]", $post['password'], $text);



	if($post['fullname'])$text=str_replace("[fullname]", $post['fullname'], $text);



	if($post['emailadr'])$text=str_replace("[emailadr]", $post['emailadr'], $text);



	if($post['buyer'])$text=str_replace("[buyeradr]", $post['buyer'], $text);



	if($post['buyer_rec'])$text=str_replace("[buyeradr_rec]", $post['buyer_rec'], $text);



	if($post['product'])$text=str_replace("[product]", $post['product'], $text);
        if($post['member'])$text=str_replace("[member]", $post['member'], $text);


	if($post['ccode'])$text=str_replace("[confcode]", $post['ccode'], $text);



	if($post['chash'])$text=str_replace("[confhash]", $post['chash'], $text);



	if($post['comments'])$text=str_replace("[comments]", $post['comments'], $text);



	else $text=str_replace("[comments]", '---', $text);



	if($post['uid'])$text=str_replace("[uid]", $post['uid'], $text);







	$text=str_replace("[emailpage]", "{$data['Host']}/members/verifemail.php", $text);



	$text=str_replace("[email]", $post['email'], $text);



	$text=str_replace("[sitename]", $data['SiteName'], $text);



	$text=str_replace("[hostname]", $data['Host'], $text);



	$text=str_replace("[singpage]", "{$data['Members']}/signup.php", $text);
        $text=str_replace("[register]", "{$data['Members']}/register.php", $text);


	$text=str_replace("[confpage]", "{$data['Members']}/confirm.php", $text);



	$text=str_replace("[lognpage]", "{$data['Members']}/login.php", $text);



	$text=str_replace("[subusername]", $post['username'], $text);

    $text=str_replace("[subpassword]", $post['password'], $text);

	$text=str_replace("[subadminlognpage]", "{$data['Admins']}/login.php", $text);



	$text=str_replace("[fees_tr]", $data['Currency'].($post['fees_tr']), $text);

	$text=str_replace("[product_amount]", $data['Currency'].($post['product_amount']), $text);

	$text=str_replace("[amount]", $data['Currency'].($post['amount']), $text);



	$header="From: {$data['AdminEmail']}\nReturn-Path: {$data['AdminEmail']}\n";



	return mail($post['email'], stripslashes($subject), stripslashes($text), $header);



}





function send_mass_email($subject, $message, $active=-1){



	global $data;

	

	require_once "Mail.php";

 

	$host 	  = "mail.bttpay.com";

	$username = "noreply@bttpay.com";

	$password = "noreply";

	 

	$smtp = Mail::factory('smtp',

	  array ('host' => $host,

		'auth' => true,

		'username' => $username,

		'password' => $password));





	$members=db_rows(



		"SELECT `username`,`email`,`fname`,`lname`".



		" FROM `{$data['DbPrefix']}members`".



		($active<0?'':" WHERE `active`={$active}")



	);

	

	 

	



	foreach($members as $value){

	   $headers = array ('From' => 'Info <info@bttpay.com>',

						  'To' => $value['email'],

						  'Subject' => $subject);

	    $smtp->send($value['email'], $headers, $message);

	}



}



###############################################################################



function use_curl($href, $post=null){



	$handle=curl_init();



	curl_setopt($handle, CURLOPT_URL, $href);



	if($post){



		if($post){



			curl_setopt($handle, CURLOPT_POST, 1);



			curl_setopt($handle, CURLOPT_POSTFIELDS, $post);



		}



		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);



		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);



		curl_setopt($handle, CURLOPT_TIMEOUT, 90);



	}



	$result=curl_exec($handle);



	curl_close($handle);



	return $result;



}







function autorize($uid, $post){



	global $data;



	$query=array();



   array_push($query, 'x_ADC_Delim_Data=TRUE');



   array_push($query, 'x_ADC_URL=FALSE');



   array_push($query, 'x_Address='.urlencode($post['address']));



   array_push($query, 'x_Amount='.urlencode($post['total']));



   array_push($query, 'x_Card_Code='.urlencode($post['ccvv']));



   array_push($query, 'x_Card_Num='.urlencode($post['cnumber']));



   array_push($query, 'x_City='.urlencode($post['city']));



   array_push($query, 'x_Company='.urlencode($post['company']));



   array_push($query, 'x_Country='.urlencode($post['country']));



   array_push($query, 'x_Cust_ID='.urlencode(get_member_username($uid)));



   array_push($query, 'x_Customer_IP='.urlencode($_SERVER['REMOTE_ADDR']));



   array_push($query, 'x_Customer_Organization_Type='.urlencode((strlen($post['company'])>0)?'B':'I'));



   array_push($query, 'x_Description='.urlencode('Deposit to my account from Authorize.Net'));



   array_push($query, 'x_Email='.urlencode($post['email']));



   array_push($query, 'x_Exp_Date='.urlencode("{$post['cmonth']}/{$post['cyear']}"));



   array_push($query, 'x_First_Name='.urlencode($post['fname']));



   array_push($query, 'x_Last_Name='.urlencode($post['lname']));



   array_push($query, 'x_Method=CC');



   array_push($query, "x_Login={$data['DepositMethod']['autorize']['user']}");



   array_push($query, "x_Password={$data['DepositMethod']['autorize']['pswd']}");



   array_push($query, 'x_Phone='.urlencode($post['phone']));



   array_push($query, 'x_Recurring_Billing=FALSE');



   array_push($query, 'x_State='.urlencode($post['state']));



   array_push($query, 'x_Tax_Exempt=TRUE');



   array_push($query, 'x_Trans_ID=1');



   array_push($query, 'x_Type=AUTH_CAPTURE');



   array_push($query, 'x_Version=3.1');



   array_push($query, 'x_Zip='.urlencode($post['zip']));



	$query=implode('&', $query);







	$cid=curl_init('https://test.authorize.net/gateway/transact.dll');



	curl_setopt($cid, CURLOPT_POST, 1);



	curl_setopt($cid, CURLOPT_POSTFIELDS, $query);



	curl_setopt($cid, CURLOPT_SSL_VERIFYPEER, 0);



	curl_setopt($cid, CURLOPT_RETURNTRANSFER, 1);



	curl_setopt($cid, CURLOPT_TIMEOUT, 90);



	$result=curl_exec($cid);



	curl_close($cid);







	$rarray=array();



	$rarray=explode(',', $result);



	$result='Credit card transaction was denied.';



	switch($rarray[0]){



		case 1: $result='--DONE--';



		case 2: $result='Credit card transaction was denied.';



		case 3: $result="An error occurred while trying to process your information.<br><br>{$rarray[3]}";



	}



	return $result;



}



###############################################################################



function is_user_available($username){



	global $data;



	$confirms=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}confirms`".



		" WHERE(`newuser`='{$username}') LIMIT 1"



	);



	$members=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}members`".



		" WHERE(`username`='{$username}') LIMIT 1"



	);



	return (bool)(!$confirms&&!$members);



}







function is_mail_available($email){



        global $data;



        $confirms=db_rows(



                "SELECT `id` FROM `{$data['DbPrefix']}confirms`".



                " WHERE(`newmail`='{$email}') LIMIT 1"



        );



        $members=db_rows(



                "SELECT `id` FROM `{$data['DbPrefix']}members`".



                " WHERE(`email`='{$email}') LIMIT 1"



        );



        $emails=db_rows(



                "SELECT `owner` FROM `{$data['DbPrefix']}member_emails`".



                " WHERE(`email`='{$email}') LIMIT 1"



        );



       return (bool)(!$confirms&&!$members&&!$emails);



}



function encrypt_res($sData, $sKey='pctAsia'){ 
    $sResult = ''; 
    for($i=0;$i<32;$i++){ 
        $sChar    = substr($sData, $i, 1); 
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1); 
        $sChar    = chr(ord($sChar) + ord($sKeyChar)); 
        $sResult .= $sChar; 
    } 
    return encode_base64($sResult); 
}
function decrypt_res($sData, $sKey='pctAsia'){ 
    $sResult = ''; 
    $sData   = decode_base64($sData); 
    for($i=0;$i<32;$i++){ 
        $sChar    = substr($sData, $i, 1); 
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1); 
        $sChar    = chr(ord($sChar) - ord($sKeyChar)); 
        $sResult .= $sChar; 
    } 
    return $sResult; 
} 

function decode_base64($sData){ 
    $sBase64 = strtr($sData, '-_', '+/'); 
    return base64_decode($sBase64); 
} 
function encode_base64($sData){ 
    $sBase64 = base64_encode($sData); 
    return strtr($sBase64, '+/', '-_'); 
}



function create_confirmation(



   $newuser, $newpass, $newques, $newansw, $newmail,



   $newfname, $newlname, $newcompany, $newregnum, $newdrvnum, $newaddress,



   $newcity, $newcountry, $newstate, $newzip, $newphone, $newfax,



   $sponsor=0



){



	global $data;



	$result=gencode();



	$sponsor=($sponsor?$sponsor:0);




	db_query(
    


		"INSERT INTO `{$data['DbPrefix']}confirms`(".



		"`newuser`,`newpass`,`newquestion`,`newanswer`,`newmail`,".



		($data['UseExtRegForm']?



		"`newfname`,`newlname`,`newcompany`,`newregnum`,`newdrvnum`,`newaddress`,".



		"`newcity`,`newcountry`,`newstate`,`newzip`,`newphone`,`newfax`,":''



      ).



      "`sponsor`,`confirm`".



		")VALUES(".



		"'{$newuser}','{$newpass}','{$newques}','{$newansw}','{$newmail}',".



		($data['UseExtRegForm']?



		"'{$newfname}','{$newlname}','{$newcompany}','{$newregnum}','{$newdrvnum}',".



      "'{$newaddress}','{$newcity}','{$newcountry}','{$newstate}','{$newzip}',".



      "'{$newphone}','{$newfax}',":''



      ).



      "'{$sponsor}','{$result}'".



		")"

    
    
	);



	//$post['ccode']=$result;
	$post['ccode']=encrypt_res($result);


	$post['email']=$newmail;



	//$post['chash']=strtoupper(md5($post['ccode'].'|'.$post['email']));
    $post['chash']=encrypt_res($result);



	send_email('CONFIRM-TO-MEMBER', $post);



}



function  create_confirmation_email_reg( $newmail,$newresult ){



	global $data;

	

	$post['ccode']=$newresult;

	

	$post['email']=$newmail;

	

	$post['chash']=strtoupper(md5($post['ccode'].'|'.$post['email']));

	

	send_email('CONFIRM-TO-MEMBER', $post);



}


function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
	

		if (empty($password))
		{
			return FALSE;
		}

		$salt = substr(md5(uniqid(rand(), true)), 0, 10);
		
		return $salt.substr(sha1($salt . $password), 0);
		 
	}




function select_confirmation($ccode, $email, $chash=''){



	global $data;



	if(isset($chash)&&!empty($chash)){

		
	$query="WHERE(`confirm` = '".trim(decrypt_res($chash))."')";
		//$query="WHERE MD5(CONCAT(`confirm`,'|',`newmail`))='{$chash}'";



	}else{

	$query="WHERE(`confirm` = '".trim(decrypt_res($ccode))."' AND `newmail`='{$email}')";
		//$query="WHERE(`confirm`='{$ccode}' AND `newmail`='{$email}')";



	}



	$confirm=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}confirms` {$query} LIMIT 1"



	);


	return $confirm[0]['id'];



}


function select_confirmation_new($ccode){

	global $data;
    $query="WHERE(`confirm` = '".trim(decrypt_res($ccode))."')";

	$confirm=db_rows(

		"SELECT `id` FROM `{$data['DbPrefix']}confirms` {$query} LIMIT 1"

	);

	return $confirm[0]['id'];



}




function select_email_confirmation($ccode, $email, $chash=''){



	global $data;



	if(isset($chash)&&!empty($chash)){



		$query="WHERE MD5(CONCAT(`confirm`,'|',`email`))='{$chash}'";



	}else{



		$query="WHERE(`confirm`='{$ccode}' AND `email`='{$email}')";



	}



	$confirm=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}member_emails` {$query} LIMIT 1"



	);



	return $confirm[0]['id'];



}






function update_confirmation($cid){

	global $data;

	$last_ip=$_SERVER['REMOTE_ADDR'];

    //echo $last_ip; 

	db_query(



		"DELETE FROM `{$data['DbPrefix']}confirms`".



		" WHERE(TO_DAYS(NOW())-TO_DAYS(`cdate`)>=2)"



	);



	$confirm=db_rows("SELECT". "`id`,`newuser`,`newpass`,`newquestion`,`newanswer`,`newmail`,". ($data['UseExtRegForm']? "`newfname`,`newlname`,`newcompany`,`newregnum`,`newdrvnum`,`newaddress`,". "`newcity`,`newcountry`,`newstate`,`newzip`,`newphone`,`newfax`,":"" ). "`sponsor`". " FROM `{$data['DbPrefix']}confirms` WHERE(`id`='{$cid}')");



	$confirm=$confirm[0];



	foreach($confirm as $key=>$value){



		$confirm[$key] = @addslashes($value);



	}



	db_query(



		"INSERT INTO `{$data['DbPrefix']}members`(".



		"`sponsor`,`username`,`password`,`email`,`question`,`answer`, `last_ip` ,".



		($data['UseExtRegForm']?



		"`fname`,`lname`,`company`,`regnum`,`drvnum`,`address`,".



		"`city`,`country`,`state`,`zip`,`phone`,`fax`,":''



      ).



      "`ip_block_member`,`active`,`empty`,`cdate`".



		")VALUES(".



		"{$confirm['sponsor']},'{$confirm['newuser']}','{$confirm['newpass']}','{$confirm['newmail']}',".



		"'{$confirm['newquestion']}','{$confirm['newanswer']}', '$last_ip' ,".



		($data['UseExtRegForm']?



		"'{$confirm['newfname']}','{$confirm['newlname']}','{$confirm['newcompany']}',".



      "'{$confirm['newregnum']}','{$confirm['newdrvnum']}','{$confirm['newaddress']}',".



      "'{$confirm['newcity']}','{$confirm['newcountry']}','{$confirm['newstate']}',".



      "'{$confirm['newzip']}','{$confirm['newphone']}','{$confirm['newfax']}',":''



      ).



      "1,1,".($data['UseExtRegForm']?'0':'1').",'".date('Y-m-d H:i:s')."')"



	);

	

	$code=gencode();



	$receiver=newid();



	db_query("INSERT INTO `{$data['DbPrefix']}member_emails` 



	(`owner`,`email`,`active`,`primary`) VALUES



	('{$receiver}','{$confirm['newmail']}',1,1)



	");

	

	

	

$confirm_pos=db_rows("select * from dp_members where `email`='{$confirm['newmail']}'");





$pass = hash_password($confirm_pos[0]['password']);





db_query("INSERT INTO users (`id`,`ip_address`,`username`,`password`,`salt`,`email`,`activation_code`,`forgotten_password_code`,`forgotten_password_time`,`remember_code`,`created_on`,`last_login`,`active`,`first_name`,`last_name`,`company`,`phone`) VALUES('{$confirm_pos[0]['id']}','{$confirm_pos[0]['last_ip']}','{$confirm_pos[0]['username']}','{$pass}','','{$confirm_pos[0]['email']}','','','','','','','1','{$confirm_pos[0]['fname']}','{$confirm_pos[0]['lname']}','{$confirm_pos[0]['company']}','{$confirm_pos[0]['phone']}')");


db_query("INSERT INTO users_groups (`user_id`,`group_id`) VALUES('{$confirm_pos[0]['id']}','2')");
	


db_query(



		"DELETE FROM `{$data['DbPrefix']}confirms`".



		" WHERE(`id`={$confirm['id']})"



	);

    if($data['SignupBonus']>0) transaction(-1,$receiver,$data['SignupBonus'],0,4,1,'Signup Fees');
    $post['username']=$confirm['newuser'];
    $post['password']=$confirm['newpass'];



	$post['email']=$confirm['newmail'];



	send_email('SIGNUP-TO-MEMBER', $post);



	if($data['ReferralPays']){



		$post['email']=get_member_email($confirm['sponsor']);



		send_email('DOWNLINE-CHANGE', $post);



	}



	//$tmpays=get_unreg_member_pay($receiver,'RECEIVER');
        //if($tmpays[0]) update_unreg_member_pays($receiver);	

return $receiver;

}








function update_email_confirmation($eid){



        global $data;



        db_query(



                "UPDATE `{$data['DbPrefix']}member_emails`".



                " SET `confirm`='', `status`=2".



                " WHERE `id`={$eid}"



        );



}







function get_members_count($active=0){



	global $data;



	$result=db_rows(



		"SELECT COUNT(`id`) AS `count`".



		" FROM `{$data['DbPrefix']}members`".



		" WHERE `active`={$active}".



		" LIMIT 1"



	);



	return $result[0]['count']; 



}







function get_members_list($active=0, $start=0, $count=0, $online=false){



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$members=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}members`".



		" WHERE `active`={$active}".($online?' AND (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(`adate`)<1800)':'').



		" ORDER BY `username` ASC{$limit}"



	);







	$result=array();



	foreach($members as $key=>$value){



		$result[$key]=$value;



		$trans=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}transactions`".



			" WHERE `sender`={$result[$key]['id']}".



			" OR `receiver`={$result[$key]['id']} LIMIT 1"



		);



		$result[$key]['transactions']=$trans[0]['count'];



		$result[$key]['candelete']=$trans[0]['count']<2;



		$result[$key]['email']=get_member_email($result[$key]['id'],true,true);



		if($result[$key]['sponsor']){



			$result[$key]['sname']=



				get_member_username($result[$key]['sponsor']).'<br>('.



				get_member_email($result[$key]['sponsor'],true,true).')'



			;



		}else $result[$key]['sname']='N/A';



	}



	return $result;



}







function get_members_count_where_pred($where_pred){



	global $data;



	$result=db_rows(



		"SELECT COUNT(`id`) AS `count`".



		" FROM `{$data['DbPrefix']}members`".



		" WHERE $where_pred ".



		" LIMIT 1"



	);



	return $result[0]['count']; 



}







function get_members_list_where_pred($start=0, $count=0, $where_pred){



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$members=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}members`".



		" WHERE $where_pred ".



		" ORDER BY `username` ASC{$limit}"



	);



	$result=array();



	foreach($members as $key=>$value){



		$result[$key]=$value;



		$trans=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}transactions` ".



			" WHERE `sender`={$result[$key]['id']}".



			" OR `receiver`={$result[$key]['id']} LIMIT 1"



		);



		$result[$key]['transactions']=$trans[0]['count'];



		$result[$key]['candelete']=$trans[0]['count']==0;



		if($result[$key]['sponsor']){



			$result[$key]['sname']=



				get_member_username($result[$key]['sponsor']).'<br>('.



				get_member_email($result[$key]['sponsor']).')'



			;



		}else $result[$key]['sname']='N/A';



	}



	return $result;



}







function get_member_id($username, $password='', $where=''){



	global $data;



	$result=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}members`".



		" WHERE (`username`='{$username}' OR `email`='{$username}')".



		($password?" AND `password`='{$password}'":'').



		($where?" AND $where":'')." LIMIT 1"



	);



        if(!$result){



           $result=db_rows(



                "SELECT `owner` as `id` FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `email`='{$username}' LIMIT 1"



           );



           if($result&&($password||$where)){



              $result=db_rows(



                 "SELECT `id` FROM `{$data['DbPrefix']}members`".



                 " WHERE `id`={$result[0]['id']}".



                 ($password?" AND `password`='{$password}'":'').



                 ($where?" AND $where":'')." LIMIT 1"



              );



           }



        }



        return $result[0]['id'];



}







/*function get_member_email($uid){



	global $data;



	$result=db_rows(



		"SELECT `email` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return $result[0]['email'];



}



*/



function get_member_email($uid, $primary=false, $confirmed=true){



	global $data;



	$result=db_rows(



		"SELECT `email` FROM `{$data['DbPrefix']}member_emails`".



		" WHERE `owner`={$uid}".



		($primary?" AND `primary`='{$primary}'":'').



		($confirmed?" AND `active`='{$confirmed}'":'').



		" ORDER BY `primary` DESC"



		



	);



	return $result[0]['email'];



}







function count_member_emails($uid, $primary=false, $confirmed=true) {



	global $data;



	$result=db_rows(



		"SELECT COUNT(`email`) AS `count`".



		" FROM `{$data['DbPrefix']}member_emails`".



		" WHERE `owner`={$uid}".



		($primary?" AND `primary`='{$primary}'":'').



		($confirmed?" AND `active`='{$confirmed}'":'').



		" LIMIT 1"



	);



	return $result[0]['count'];



}







function get_email_details($uid, $primary=false, $confirmed=true){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}member_emails`".



		" WHERE `owner`={$uid}".



		($primary?" AND `primary`='{$primary}'":'').



		($confirmed?" AND `active`='{$confirmed}'":'')



	);



	return $result;



}







function prnmemberemails($uid) {



	global $data;



	$str_add="";



	$result=db_rows(



		"SELECT `email` FROM `{$data['DbPrefix']}member_emails`".



		" WHERE `owner`={$uid} AND `active`='1'".



		" ORDER BY `primary` DESC"



		



	);



	foreach($result as $key=>$value) {



		$str_add .= "<a href=mailto:{$result[$key]['email']}> {$result[$key]['email']}</a>"."<br>";



	}



	return $str_add;



}







/* Users emails functions */







function add_email($uid,$email){



	global $data;



	$max_email=$data['maxemails'];



	$nb_emails=count_member_emails($uid,false,false);



	if($nb_emails >= $max_email) return TOO_MANY_EMAILS;



	elseif(verify_email($email)) return INVALID_EMAIL_ADDRESS;



	elseif(email_exists($email)) return EMAIL_EXISTS;



	else {



		$verifcode=gencode($email);



		$result=db_query(



			"INSERT INTO `{$data['DbPrefix']}member_emails`".



			"(`owner`,`email`,`active`,`primary`,`verifcode`) VALUES ".



			"($uid,'{$email}',0,0,'{$verifcode}')"



		);



		if (!$result) return DB_ERROR;



		$info=get_member_info($uid);



		$post['email']=$email;



		$post['fullname']=get_member_name($uid);



		$post['ccode']=$verifcode;



		$post['uid']=$uid;



		$post['emailpage'];



		send_email('CONFIRM-NEW-EMAIL',$post);



		return SUCCESS;



	}



}







function activate_email($uid, $verifcode){



	global $data;



	$confirm=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}member_emails` WHERE `owner`='$uid' AND `verifcode`='$verifcode' AND `active`=0");



	if (!isset($confirm[0])) return CONFIRMATION_NOT_FOUND;



	db_query("UPDATE `{$data['DbPrefix']}member_emails` SET `active`=1 WHERE `owner`={$uid} AND `verifcode`='{$verifcode}'");







	$info=get_member_info($uid);



	$post['email']=$confirm[0]['email'];



	$post['fullname']=get_member_name($uid);



	send_email('NEW-EMAIL-ACTIVATED',$post);



	return SUCCESS;



}







function make_email_prim($uid, $email){



	global $data;



	if (verify_email($email)) return INVALID_EMAIL_ADDRESS;



	$emails=get_email_details($uid,false,false);



	$oldprim=get_member_email($uid,true);



	foreach ($emails as $addr)



		if($addr['email']==$email && $addr['primary']) return ALREADY_PRIMARY;



		elseif($addr['email']==$email && !$addr['active']) return EMAIL_NOT_ACTIVE;



		elseif($addr['email']==$email){



			/* un-prim old, make prim new*/



			db_query("UPDATE {$data['DbPrefix']}member_emails SET `primary`=1 WHERE `owner`='{$uid}' AND `email`='{$email}'");



			db_query("UPDATE {$data['DbPrefix']}member_emails SET `primary`=0 WHERE `owner`='{$uid}' AND `email`='{$oldprim}'");



			db_query("UPDATE {$data['DbPrefix']}members SET `email`='{$email}' WHERE `id`='{$uid}'");



			return SUCCESS;



		}



	return EMAIL_NOT_FOUND;



}







function get_email_detail($email, $type=ALL){



	global $data;



	if ($type==CONFIRMED) $result=db_rows(



		"SELECT * FROM {$data['DbPrefix']}member_emails WHERE `email`='$email' AND `active`=1");



	else $result=db_rows(



		"SELECT * FROM {$data['DbPrefix']}member_emails WHERE `email`='$email'");



	return $result[0];



}







function delete_member_email($uid, $email){



	global $data;



	if(verify_email($email)) return INVALID_EMAIL_ADDRESS;



	$todel=get_email_detail($email);



	if(!$todel) return EMAIL_NOT_FOUND;



	elseif($todel['primary']) return CANNOT_DELETE_PRIMARY;







	db_query("DELETE FROM {$data['DbPrefix']}member_emails WHERE owner='{$uid}' AND `email`='{$email}'");



	return SUCCESS;



}







function email_exists ($email){



	global $data;



	$result=db_rows("SELECT owner FROM {$data['DbPrefix']}members_emails WHERE email='{$email}'");



	return (bool)$result['0'];	



}







function get_user_id($unoremail){



	global $data;



	if(verify_email($unoremail)){



	// here we know its the username



		$result=db_rows(



			"SELECT `id` FROM `{$data['DbPrefix']}members`".



			" WHERE (`username`='{$unoremail}') AND `active`=1 LIMIT 1");



		return $result[0]['id'];



	} else {



	//... here the email address



		$result=db_rows(



			"SELECT `owner` FROM `{$data['DbPrefix']}member_emails` e, ".



			"`{$data['DbPrefix']}members` m".



			" WHERE (e.`email`='{$unoremail}' AND m.`active`=1)".



			" LIMIT 1");



		return $result[0]['owner'];		



	}



}











/* ------------ */







function get_sponsor_id($uid){



	global $data;



	$result=db_rows(



		"SELECT `sponsor` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return $result[0]['sponsor'];



}




function get_mem_by_email($uid){



	global $data;



	$result=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}members`".



		" WHERE `email`='{$uid}' LIMIT 1"



	);



	return $result[0]['id'];



}




function get_sponsors($uid){



	global $data;



	$members=db_rows(



		"SELECT `id`,`username`,`email`".



		" FROM `{$data['DbPrefix']}members`".



		($uid?" WHERE `id`<>{$uid} AND `sponsor`<>{$uid}":'')



	);



	$result=array('--');



	foreach($members as $value)$result[$value['id']]="{$value['username']} ({$value['email']})";



	return $result;



}







function get_member_username($uid){



	global $data;



	if($uid<0)return 'system';



	$result=db_rows(



		"SELECT `username` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1");



	return $result[0]['username'];



}



function save_remote_ipadd($ip,$attempts,$today){

        db_query(

        

                "INSERT INTO db_login_attempts (IpAddress, attempts, date_last_use) VALUES('$ip','$attempts', '$today')"

        

            );

}



function save_remote_ipaddress($ip){ 

        $result=db_rows(

        

                "SELECT date_last_use FROM db_login_attempts WHERE IpAddress='$ip'"

        

            );

   			

            $tommorow = date("d/m/y");

            if($result[0]['date_last_use']==$tommorow)

            	return false;

            else

            	return true;

}



function get_member_name($uid){



	global $data;



	if($uid<0)return 'system';



	$result=db_rows(



		"SELECT `fname`,`lname` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1");



	return $result[0]['fname']." ".strtoupper($result[0]['lname']);



}



function get_dispute_info($dispute_id){



        global $data;



        $result=db_rows(



                "SELECT * FROM `db_dispute`".



                " WHERE `dispute_id`={$dispute_id} LIMIT 1");

               

               

             

               $t_id=$result[0]['transaction_id'];

               

               $uid = $result[0]['user_id'];

              

              

               $users=db_rows(



                     "SELECT `username` FROM `{$data['DbPrefix']}members`".



                     " WHERE `id`={$uid}");   

                     

                $result[0]['username'] = $users[0]['username'];

              

              

                  

                  $transdata=db_rows(



                   "SELECT * FROM `{$data['DbPrefix']}transactions`".



                  " WHERE `transaction_id`={$t_id}");  

                  

                  

                    $result[0]['trans_date'] = $transdata[0]['tdate'];

                  

              

                  

                 

                  

                   

  

       return $result[0];



}



function get_member_info($uid){



        global $data;



        $result=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}members`".



                " WHERE `id`={$uid} LIMIT 1");



        $result[0]['emails']=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `owner`={$uid} AND `email`<>'{$result[0]['email']}'");



        return $result[0];



}







function get_member_status($uid){



	global $data;



	$result=db_rows(



		"SELECT `status` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return $result[0]['status'];



}







function get_ip_history($uid, $order=''){



	global $data;



	$result=db_rows(



		"SELECT `date`,`address` FROM `{$data['DbPrefix']}visits`".



		" WHERE `member`={$uid} ".($order?"ORDER BY `{$order}`":'ORDER BY `date` DESC')



	);



	return $result;



}







function is_member_found($username, $password){



	return (bool)get_member_id($username, $password);



}







function is_member_active($username){



	return (bool)get_member_id($username, '', '`active`=1');



}







function set_member_status($uid, $active){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members`".



		" SET `active`=".(int)$active.



		" WHERE `id`={$uid}"



	);



}







function set_member_status_ex($uid, $status){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members`".



		" SET `status`={$status}".



		" WHERE `id`={$uid}"



	);



}







function get_member_status_ex($uid){



	global $data;



	$record=db_rows(



		"SELECT `status` FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return $record[0]['status'];



}







function set_member_inactive($username){



	global $data;



	set_member_status(get_member_id($username), false);



}







function delete_member($uid){



	global $data;



	db_query(



		"DELETE FROM `{$data['DbPrefix']}members` WHERE `id`={$uid}"



	);



}



function block_member($uid){



	global $data;



	db_query(



		"DELETE FROM `db_login_attempts` WHERE `id`={$uid}"



	);



}

function get_ipaddress_list(){



	global $data;



	$members=db_rows(



		"SELECT * FROM `db_login_attempts`"



	);



	return $members;



		

}

function get_roles_list(){



	global $data;



	$roles=db_rows(



		"SELECT * FROM `dp_access_roles`"



	);



	return $roles;



		

}



function get_subadmin_list(){



	global $data;



	$subadmin=db_rows(



		"SELECT * FROM `dp_subadmin`"



	);



	return $subadmin;



		

}



function get_edit_roles_list($id){



	global $data;



	$accessroles=db_rows(



		"SELECT * FROM `dp_access_roles` where id=$id"



	);



	return $accessroles;



		

}



function get_edit_subadmin_list($id){



	global $data;



	$subadmin=db_rows(



		"SELECT * FROM `dp_subadmin` where id=$id"



	);



	return $subadmin;



		

}



function select_balance($uid){



	global $data;



	if($uid<0){



		$isql=



			"SELECT SUM(`fees`) AS `summ`".



			" FROM `{$data['DbPrefix']}transactions`".



			" WHERE (`status`=1 OR `status`=6 OR `status`=5) LIMIT 1"



		;



	}else{



		$isql=



			"SELECT SUM(`amount`-`fees`) AS `summ`".



			" FROM `{$data['DbPrefix']}transactions`".



			" WHERE `receiver`={$uid} AND (`status`=1 OR `status`=6) LIMIT 1"



		;



	}



	$outgoing=db_rows(



		"SELECT SUM(`amount`) AS `summ`".



		" FROM `{$data['DbPrefix']}transactions`".



		" WHERE `sender`={$uid} AND (`status`=1 OR `status`=6) LIMIT 1"



	);

	

	$pending_out__withdrw=db_rows(



		"SELECT SUM(`amount`) AS `summ`".



		" FROM `{$data['DbPrefix']}transactions`".



		" WHERE `sender`={$uid} AND (`status`=0 AND `type`=2) LIMIT 1"



	);



	//$pending_out_unreg=db_rows(
	//
	//
	//
	//	"SELECT SUM(`amount`) AS `summ`".
	//
	//
	//
	//	" FROM `{$data['DbPrefix']}temp_pays`".
	//
	//
	//
	//	" WHERE `sender`={$uid} AND (`status`=0) LIMIT 1"
	//
	//
	//
	//);



	$incoming=db_rows($isql);



	$outgoing=(double)$outgoing[0]['summ'];



	//$pending_out_unreg=(double)$pending_out_unreg[0]['summ'];
        $pending_out_unreg = 0; //@custom oc
	

	$pending_out__withdrws=(double)$pending_out__withdrw[0]['summ'];



	$outgoing=$outgoing+$pending_out_unreg+$pending_out__withdrws;



	$incoming=(double)$incoming[0]['summ'];



	return $incoming-$outgoing;



}







function set_last_access($username){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members`".



		" SET `ldate`='".date("Y-m-d H:i:s")."',".



		"`last_ip`='{$_SERVER['REMOTE_ADDR']}'".



		" WHERE `id`=".get_member_id($username)



	);



}







function set_last_access_date($uid, $reset=false){



	global $data;



	if(!$reset)$curr=date("Y-m-d H:i:s");else $curr=0;



	db_query(



		"UPDATE `{$data['DbPrefix']}members`".



		" SET `adate`='{$curr}'".



		" WHERE `id`={$uid}"



	);



}







function save_remote_ip($uid, $address){



	global $data;



	db_query(



		"INSERT `{$data['DbPrefix']}visits`(`member`,`date`,`address`".



		")VALUES({$uid},'".date('Y-m-d H:i:s')."','{$address}')"



	);



}







function is_valid_mail($email){



        global $data;



        $result=db_rows(



                "SELECT `id` FROM `{$data['DbPrefix']}members`".



                " WHERE `email`='{$email}' LIMIT 1"



        );



        $emails=db_rows(



                "SELECT `id` FROM `{$data['DbPrefix']}member_emails`".



                " WHERE(`email`='{$email}') LIMIT 1"



        );



        return (bool)(!$result&&!$emails);



}







function get_member_by_email($email){



        global $data;



        $result=db_rows(



                "SELECT `password`,`question`,`answer` FROM `{$data['DbPrefix']}members`".



                " WHERE `email`='{$email}'"



        );



        if(!$result){



           $emails=db_rows(



                "SELECT `owner` FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `email`='{$email}' LIMIT 1"



           );



           if($emails){



              $result=db_rows(



                 "SELECT `password`,`question`,`answer` FROM `{$data['DbPrefix']}members`".



                 " WHERE `id`={$emails[0]['owner']}"



              );



           }



        }



        return $result[0];



}



function is_info_empty($uid){



	global $data;



	$result=db_rows(



		"SELECT `empty`".



		" FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return (bool)$result[0]['empty'];



}







function select_info($uid, $post){



	global $data;



	$result=$post;



	$member=get_member_info($uid);



	if(!$member){



		$_SESSION['uid']=0;



		$_SESSION['login']=false;



		header("Location:{$data['Host']}/index.php");



		echo('ACCESS DENIED.');



		exit;



	}





	foreach($member as $key=>$value)if(!isset($post[$key]))$result[$key]=$value;



	if(!$result['active']){



		$_SESSION['uid']=0;



		$_SESSION['login']=false;



		header("Location:{$data['Host']}/index.php");



		echo('ACCESS DENIED.');



		exit;



	}



	return $result;



}







function insert_profile_info($post){



	global $data;

//print_r($post);exit;

	if(!$post['sponsor'])$post['sponsor']=0;



	db_query(



		"INSERT INTO `{$data['DbPrefix']}members`(".



		"`sponsor`,`username`,`password`,`email`,`active`,`empty`,".



		"`fname`,`lname`,`company`,`regnum`,`drvnum`,".



		"`address`,`city`,`country`,`state`,`zip`,`phone`,`fax`,`access_type`".



		")VALUES(".



		"{$post['sponsor']},'{$post['username']}','{$post['password']}',".



		"'{$post['email']}',0,0,'{$post['fname']}','{$post['lname']}',".



		"'{$post['company']}','{$post['regnum']}','{$post['drvnum']}',".



		"'{$post['address']}','{$post['city']}','{$post['country']}',".



		"'{$post['state']}','{$post['zip']}','{$post['phone']}',".



		"'{$post['fax']}','{$post['roles']}'".



		")"



	);



	$newid=newid();









	db_query("INSERT INTO `{$data['DbPrefix']}member_emails` 



	(`owner`,`email`,`active`,`primary`) VALUES



	('{$newid}','{$post['email']}',1,1)



	");











	return $newid;



}



function insert_subadmin_info($post){



	global $data;







	db_query(



		"INSERT INTO `{$data['DbPrefix']}subadmin`(".



		"`username`,`password`,`email`,".



		"`fname`,`lname`,".



		"`address`,`city`,`country`,`state`,`zip`,`phone`,`fax`,`access_id`".



		")VALUES(".



		"'{$post['username']}','{$post['password']}',".



		"'{$post['email']}','{$post['fname']}','{$post['lname']}',".



		"'{$post['address']}','{$post['city']}','{$post['country']}',".



		"'{$post['state']}','{$post['zip']}','{$post['phone']}',".



		"'{$post['fax']}','{$post['roles']}'".



		")"



	);

    $post['email']=$post['email'];

send_email('SIGNUP-TO-SUB-ADMIN', $post);

	



}



function get_access_admin_role($admin_access_id)

{

global $data;



 $subad_access_level=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}access_roles`".



                " WHERE `id`={$admin_access_id}"



        );

        return $subad_access_level;



}

function insert_roles_info($post){



	global $data;



	if(!$post['sponsor'])$post['sponsor']=0;



	db_query(



		"INSERT INTO `{$data['DbPrefix']}access_roles`(".



		"`rolesname`,`configuration`,`faq`,`dispute`,`statistics`,`active`,".



		"`suspended`,`closed`,`online`,`search`,`addnew`,".



		"`block`,`summary`,`transactions`,`deposits`,`masspayments`,`massmailing`,`management`,".

        

        "`withdrawals`,`escrows`,`signups`,`commissions`,`refunds`,`investment`,`singlepayment`,`shopsearch`".



		")VALUES(".



		"'{$post['rolesname']}','{$post['configuration']}','{$post['faq']}',".



		"'{$post['dispute']}','{$post['statistics']}','{$post['active']}',".



		"'{$post['suspended']}','{$post['closed']}','{$post['online']}',".



		"'{$post['search']}','{$post['addnew']}','{$post['block']}',".



		"'{$post['summary']}','{$post['transactions']}','{$post['deposits']}',".



		"'{$post['masspayments']}','{$post['massmailing']}','{$post['management']}',".

        

        "'{$post['withdrawals']}','{$post['escrows']}','{$post['signups']}','{$post['commissions']}','{$post['refunds']}',".

        

        "'{$post['investment']}','{$post['singlepayment']}','{$post['shopsearch']}'".



		")"



	);





}



function update_roles_info($post, $rid){



	global $data;





	db_query(



		"UPDATE `{$data['DbPrefix']}access_roles` SET ".



		"`rolesname`='{$post['rolesname']}',".



		"`configuration`='{$post['configuration']}',`faq`='{$post['faq']}',`dispute`='{$post['dispute']}',".



		"`statistics`='{$post['statistics']}',`active`='{$post['active']}',".



		"`suspended`='{$post['suspended']}',`closed`='{$post['closed']}',".



		"`online`='{$post['online']}',`search`='{$post['search']}',".



		"`addnew`='{$post['addnew']}',`block`='{$post['block']}',".



		"`summary`='{$post['summary']}',`transactions`='{$post['transactions']}',".

        

        "`deposits`='{$post['deposits']}',`masspayments`='{$post['masspayments']}',".



		"`massmailing`='{$post['massmailing']}',`management`='{$post['management']}',".



		"`withdrawals`='{$post['withdrawals']}',".

        

        "`singlepayment`='{$post['singlepayment']}',`escrows`='{$post['escrows']}',".



		"`signups`='{$post['signups']}',`commissions`='{$post['commissions']}',".



		"`refunds`='{$post['refunds']}',`investment`='{$post['investment']}',".



		"`shopsearch`='{$post['shopsearch']}'".



		" WHERE `id`='{$rid}'"



	);



	



}



function update_subadmin_info($post, $sid){



	global $data;





	db_query(



		"UPDATE `{$data['DbPrefix']}subadmin` SET ".



		"`username`='{$post['username']}',".



		"`password`='{$post['password']}',`fname`='{$post['fname']}',".



		"`lname`='{$post['lname']}',`access_id`='{$post['roles']}',".



		"`address`='{$post['address']}',`city`='{$post['city']}',".



		"`country`='{$post['country']}',`state`='{$post['state']}',".



		"`zip`='{$post['zip']}',`phone`='{$post['phone']}',".



		"`fax`='{$post['fax']}',`description`='{$post['description']}'".

       



		" WHERE `id`='{$sid}'"



	);



	



}

function update_epn_info($post, $sid){



	global $data;





	db_query(



		"UPDATE `{$data['DbPrefix']}members` SET ".



		"`epn_key`='{$post['epn_key']}',".



		"`epn_id`='{$post['epn_id']}'".

       

		" WHERE `id`='{$sid}'"



	);



	



}





function update_profile_info($post, $uid, $notify=true){



	global $data;



	if(!$post['sponsor'])$post['sponsor']=0;



	db_query(



		"UPDATE `{$data['DbPrefix']}members` SET ".



		"`sponsor`={$post['sponsor']},".



		"`empty`=0,`fname`='{$post['fname']}',`lname`='{$post['lname']}',".



		"`company`='{$post['company']}',`regnum`='{$post['regnum']}',".



		"`drvnum`='{$post['drvnum']}',`address`='{$post['address']}',".



"`city`='{$post['city']}',`country`='{$post['country']}',".



		"`city`='{$post['city']}',`country`='{$post['country']}',".



		"`state`='{$post['state']}',`zip`='{$post['zip']}',".



		"`phone`='{$post['phone']}',`fax`='{$post['fax']}',".
                "`website`='{$post['website']}',".


		"`description`='{$post['description']}'".



		" WHERE `id`={$uid}"



	);

	

	

	if($notify){



		$post['email']=get_member_email($uid);



		send_email('UPDATE-MEMBER-PROFILE', $post);



	}



}







function update_private_info($post, $uid){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members` SET ".



		"`username`='{$post['username']}',`password`='{$post['password']}',".



		"`email`='{$post['email']}' WHERE `id`={$uid}"



	);



}







function update_member_password($uid, $password, $notify=true){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members` SET ".



		"`password`='{$password}'".



		" WHERE `id`={$uid}"



	);



	if($notify){



		$post['email']=get_member_email($uid);



		send_email('UPDATE-MEMBER-PROFILE', $post);



	}



}







function update_member_question($uid, $question, $answer, $notify=true){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}members` SET ".



		"`question`='{$question}',`answer`='{$answer}'".



		" WHERE `id`={$uid}"



	);



	if($notify){



		$post['email']=get_member_email($uid);



		send_email('UPDATE-MEMBER-PROFILE', $post);



	}



}







function insert_email_info($email, $uid, $notify=true){



        global $data;



        db_query(



                "INSERT INTO `{$data['DbPrefix']}member_emails`(".



                "`owner`,`email`,`status`".



                ")VALUES(".



                "{$uid},'{$email}',0)"



        );



        if($notify)send_email_request(newid());



        return newid();



}







function delete_email_info($gid){



        global $data;



        db_query(



                "DELETE FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `id`={$gid}"



        );



}







function send_email_request($gid){



        global $data;



        $emails=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `id`={$gid} LIMIT 1"



        );



        if($emails[0]){



                $post['ccode']=gencode();



                db_query(



                         "UPDATE `{$data['DbPrefix']}member_emails`".



                         " SET `confirm`='{$post['ccode']}', `status`=1".



                         " WHERE `id`={$gid}"



                );



                $post['email']=$emails[0]['email'];



                send_email('CONFIRM-EMAIL', $post);



        }



}







function set_default_email($gid){



        global $data;



        $emails=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}member_emails`".



                " WHERE `id`={$gid} LIMIT 1"



        );



        if($emails[0]){



                db_query(



                         "INSERT INTO `{$data['DbPrefix']}member_emails`(".



                         "`owner`,`email`,`status`".



                         ")VALUES(".



                         "{$emails[0]['owner']},'".get_member_email($emails[0]['owner'])."',2)"



                );



                db_query(



                         "UPDATE `{$data['DbPrefix']}members`".



                         " SET `email`='{$emails[0]['email']}'".



                         " WHERE `id`={$emails[0]['owner']}"



                );



                db_query(



                         "DELETE FROM `{$data['DbPrefix']}member_emails`".



                         " WHERE `id`={$emails[0]['id']}"



                );



        }



}







function insert_card_info($post, $uid, $notify=true){



        global $data;



        db_query(



                "INSERT INTO `{$data['DbPrefix']}cards`(".



                "`owner`,`ctype`,`cname`,`cnumber`,`ccvv`,`cmonth`,`cyear`,".



                "`status`,`default`".



                ")VALUES(".



                "{$uid},'{$post['ctype']}','{$post['cname']}',".



                "'{$post['cnumber']}','{$post['ccvv']}',".



                "{$post['cmonth']},{$post['cyear']},".



                "0,0)"



        );



        if($notify){



                $post['email']=get_member_email($uid);



                send_email('UPDATE-CARD-INFORMATION', $post);



        }



        return newid();



}







function update_card_info($post, $gid, $uid, $notify=true){



        global $data;



        $cnumber=(is_changed($post['cnumber']))?"`cnumber`='{$post['cnumber']}',":'';



        $ccvv=(is_changed($post['ccvv']))?"`ccvv`='{$post['ccvv']}',":'';



        db_query(



                "UPDATE `{$data['DbPrefix']}cards` SET ".



                "`ctype`='{$post['ctype']}',`cname`='{$post['cname']}',".



                "{$cnumber}{$ccvv}".



                "`cmonth`={$post['cmonth']},`cyear`={$post['cyear']}".



                " WHERE `id`={$gid}"



        );



        if($notify){



                $post['email']=get_member_email($uid);



                send_email('UPDATE-CARD-INFORMATION', $post);



        }



}







function delete_card($gid){



        global $data;



        db_query(



                "DELETE FROM `{$data['DbPrefix']}cards`".



                " WHERE `id`={$gid}"



        );



}







function select_cards($uid, $hiden=true, $id=0, $single=false){



        global $data;



        $cards=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}cards`".



                " WHERE `owner`={$uid}".



                ($id?" AND `id`={$id}":'').($single?" LIMIT 1":'')



        );



        $result=array();



        foreach($cards as $key=>$value){



                foreach($value as $name=>$v){



                   $result[$key][$name]=$v;



                   if($hiden){



                     if($name=='cnumber') $result[$key][$name]=encode($v, 4);



                     elseif($name=='ccvv') $result[$key][$name]=encode($v, 1);



                   }



                }



        }



        return $result;



}







function insert_bank_info($post, $uid, $notify=true){



        global $data;



        db_query(



                "INSERT INTO `{$data['DbPrefix']}banks`(".



                "`owner`,`bname`,`baddress`,`bcity`,`bzip`,`bcountry`,`bstate`,".



                "`bphone`,`bnameacc`,`baccount`,`btype`,`brtgnum`,`bswift`,".



                "`status`,`default`".



                ")VALUES(".



                "{$uid},'{$post['bname']}','{$post['baddress']}','{$post['bcity']}',".



                "'{$post['bzip']}','{$post['bcountry']}','{$post['bstate']}',".



                "'{$post['bphone']}','{$post['bnameacc']}','{$post['baccount']}',".



                "'{$post['btype']}','{$post['brtgnum']}','{$post['bswift']}',".



                "0,0)"



        );



        if($notify){



                $post['email']=get_member_email($uid);



                send_email('UPDATE-BANK-INFORMATION', $post);



        }



        return newid();



}







function update_bank_info($post, $gid, $uid, $notify=true){



        global $data;



        db_query(



                "UPDATE `{$data['DbPrefix']}banks` SET ".



                "`bname`='{$post['bname']}',`baddress`='{$post['baddress']}',".



                "`bcity`='{$post['bcity']}',`bzip`='{$post['bzip']}',".



                "`bcountry`='{$post['bcountry']}',`bstate`='{$post['bstate']}',".



                "`bphone`='{$post['bphone']}',`bnameacc`='{$post['bnameacc']}',".



                "`baccount`='{$post['baccount']}',`btype`='{$post['btype']}',".



                "`brtgnum`='{$post['brtgnum']}',`bswift`='{$post['bswift']}'".



                " WHERE `id`={$gid}"



        );



        if($notify){



                $post['email']=get_member_email($uid);



                send_email('UPDATE-BANK-INFORMATION', $post);



        }



}







function delete_bank($gid){



        global $data;



        db_query(



                "DELETE FROM `{$data['DbPrefix']}banks`".



                " WHERE `id`={$gid}"



        );



}



function delete_access_roles($id){



        global $data;



        db_query(



                "DELETE FROM `{$data['DbPrefix']}access_roles`".



                " WHERE `id`={$id}"



        );



}





function delete_subadmin_roles($id){



        global $data;



        db_query(



                "DELETE FROM `{$data['DbPrefix']}subadmin`".



                " WHERE `id`={$id}"



        );



}





function select_banks($uid, $id=0, $single=false){



        global $data;



        $banks=db_rows(



                "SELECT * FROM `{$data['DbPrefix']}banks`".



                " WHERE `owner`={$uid}".



                ($id?" AND `id`={$id}":'').($single?" LIMIT 1":'')



        );



        $result=array();



        foreach($banks as $key=>$value){



                foreach($value as $name=>$v)$result[$key][$name]=$v;



        }



        return $result;



}







function set_trtype($uid, $dir){



	switch($dir){



		case 'both':



			return "(`sender`={$uid} OR `receiver`={$uid})";



		case 'incoming':



			return "`receiver`={$uid}";



		case 'outgoing':



			return "`sender`={$uid}";



	}



	return '';



}







function get_trans_count($where=''){



	global $data;



	$result=db_rows(



		"SELECT COUNT(`id`) AS `count`".



		" FROM `{$data['DbPrefix']}transactions`{$where} LIMIT 1"



	);



	return $result[0]['count'];



}







function get_transactions_count($uid, $dir='both', $extra='1'){



	$result=get_trans_count(



		' WHERE '.($uid>0?set_trtype($uid, $dir).



		($extra?" AND {$extra}":''):($extra?" {$extra}":''))



	);



	return $result;



}







function get_transactions_summ($where){



	global $data;



	$rows=db_rows(



		'SELECT SUM(`amount`) AS `summ`, SUM(`fees`) AS `fees`'.



		" FROM `{$data['DbPrefix']}transactions`".



		($where?" WHERE {$where}":'').' ORDER BY `tdate` LIMIT 1'



	);



	$result['summ']=$rows[0]['summ'];



	$result['fees']=$rows[0]['fees'];



	return $result;



}







function get_transactions_summary($dateA, $dateB){



	global $data;



	foreach($data['TransactionType'] as $key=>$value){



		$rows=get_transactions_summ(



			"`type`={$key} AND status != 10 AND".



			" UNIX_TIMESTAMP(`tdate`)>={$dateA} AND".



			" UNIX_TIMESTAMP(`tdate`)<{$dateB}"



		);



		$result[$value]['Summ']=prnpays($rows['summ']?$rows['summ']:0, false);



		$result[$value]['Fees']=prnpays($rows['fees']?$rows['fees']:0, false);



	}



	return $result;



}







function get_transactions_year(){



	global $data;



	$years=db_rows(



		"SELECT MIN(YEAR(`tdate`)) AS `min`, MAX(YEAR(`tdate`)) AS `max`".



		" FROM `{$data['DbPrefix']}transactions` LIMIT 1"



	);



	$result['min']=$years[0]['min'];



	$result['max']=$years[0]['max'];



	return $result;



}







function get_transactions_period(){



        global $data;



        $period=db_rows(



                "SELECT MIN(`tdate`) AS `min`, MAX(`tdate`) AS `max`".



                " FROM `{$data['DbPrefix']}transactions` LIMIT 1"



        );



        $result['min']=getdate(strtotime($period[0]['min']));



        $result['max']=getdate(strtotime($period[0]['max']));



        return $result;



}







function can_refund($id, $uid){



	global $data;



	$balance=select_balance($uid);



	$result=db_rows(



		"SELECT `id` FROM `{$data['DbPrefix']}transactions`".



		" WHERE `id`={$id} AND `receiver`={$uid}".



		" AND `type`=0 AND (`status`=0 OR `status`=1)".



		" AND `amount` - `fees` <= {$balance}".



		" AND TO_DAYS(NOW())-TO_DAYS(`tdate`)<{$data['RefundPeriod']}"



	);



	return $result[0];



}







function get_status_color($status){



	$result='000000';



	switch($status){



	case 0:



		$result='blue';



		break;



	case 1:



		$result='green';



		break;



	case 2:



		$result='red';



		break;



	case 3:



		$result='maroon';



	}



	return $result;



}



function check_form($data){
    $data = trim($data); $data = stripslashes($data);
    $data = FC::getClassInstance("Db")->link->real_escape_string($data);
    $data = str_replace("^**^^**^", "&", $data);
    $data = str_replace("^**--**^", "#", $data);return $data; }



function get_transactions(



   $uid, $dir='both', $type=-1, $status=-1, $start=0,



   $count=0, $order='', $suser='', $sdate=''



){



	global $data;



	if($suser||$sdata){



		$start=0;



		$count=0;



	}



	$order=($order?$order:'ORDER BY `tdate` DESC');



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$trans=db_rows(



		"SELECT a.*,b.website as registered_website, (TO_DAYS(NOW())-TO_DAYS(`tdate`)) as `period`".



		" FROM `{$data['DbPrefix']}transactions` a LEFT JOIN `{$data['DbPrefix']}members` b on a.receiver = b.id ".



		($uid?" WHERE ".set_trtype($uid, $dir):'').



		($type<0?'':($uid?' AND ':' WHERE ')."`type`={$type}").



		($status<0?'':($uid||$type>=0?' AND ':' WHERE ')."`status`={$status}").



		" {$order}{$limit}"



	);



	$result=array();



	foreach($trans as $key=>$value){



		if($suser){



			if(



				strpos(get_member_username($value['sender']), $suser)===false



				&&



				strpos(get_member_username($value['receiver']), $suser)===false



			)continue;



		}elseif($sdate){



			if(strpos($value['tdate'], $sdate)===false)continue;



		}



		$dir=(bool)($value['sender']!=$uid);



		$result[$key]['id']=$value['id'];
                $result[$key]['transaction_id']=$value['transaction_id'];
                $result[$key]['names']=ucwords($value['names']);
                $result[$key]['website']=$value['website'];
                $result[$key]['registered_website']=$value['registered_website'];
                $result[$key]['referral']=$value['referral'];


		$result[$key]['direction']=$dir?'FROM':'TO';



		$result[$key]['sender']=$value['sender'];



		$result[$key]['senduser']=prnuser($value['sender']);



		$result[$key]['receiver']=$value['receiver'];



		$result[$key]['recvuser']=prnuser($value['receiver']);



		$result[$key]['userid']=$dir?$value['sender']:$value['receiver'];



		$result[$key]['username']=prnuser($result[$key]['userid']);



		$result[$key]['oamount']=$dir?$value['amount']:-$value['amount'];



		$result[$key]['amount']=prnpays($result[$key]['oamount']);



		$result[$key]['ofees']=$value['sender']>0&&$value['sender']==$uid&&$value['receiver']>0?-$value['amount']:$value['amount']-$value['fees'];



		$result[$key]['fees_m']=prnpays($result[$key]['ofees']);



		$result[$key]['ofees1']=$value['sender']>0&&$value['sender']==$uid&&$value['receiver']>0?$value['amount']-$value['fees']:-$value['amount'];



		$result[$key]['fees_m1']=prnpays($result[$key]['ofees1']);





		$result[$key]['mfees']=$value['sender']>0&&$value['sender']==$uid&&$value['receiver']>0?-($value['amount']-$value['fees']):$value['amount']-$value['fees'];



		$result[$key]['fees_o']=prnpays($result[$key]['mfees']);



		$result[$key]['tdate']=prndate($value['tdate']);



		$result[$key]['period']=$value['period'];



		$result[$key]['ostatus']=$value['status'];



		$result[$key]['type']=$data['TransactionType'][$value['type']];

      $data['wdatatype']= $value['type'];

      

   $data['wstatus']= $value['status']; 

		$result[$key]['status']=



			"<font color=".get_status_color($value['status']).">".



			$data['TransactionStatus'][$value['status']].



			'</font>'



		;



		if($value['fees']>0&&($value['type']==1||$value['type']==2||($dir&&($value['type']==0||$value['type']==3)))){



			$result[$key]['ofees']=-$value['fees'];



		}else{



			$result[$key]['ofees']=0;



		}



		$result[$key]['fees']=prnfees($result[$key]['ofees']);



		$result[$key]['fees_dir']=prnpays_fee($value['sender']>0&&$value['sender']==$uid&&$value['receiver']>0?-$value['fees']:0);



		$result[$key]['onets']=$value['sender']>0&&$value['sender']==$uid&&$value['receiver']>0?$value['amount']:$value['amount']-$value['fees'];



		$result[$key]['nets']=prnpays($result[$key]['onets'], false);



		$result[$key]['comments']=prntext($value['comments']);



		$result[$key]['ecomments']=prntext($value['ecomments']);



		$result[$key]['canview']=($value['type']>=0&&$value['type']<=3);



		$result[$key]['canrefund']=can_refund($value['id'], $uid);



	}



	return $result;



}

###############################################################################

function get_transaction_detail_table($trans_id, $uid){



	global $data;



	$trans=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}transactions` WHERE `transaction_id`={$trans_id} LIMIT 1"



	);



	$trans=$trans[0];

    if($trans==null||$trans=='')

    {

        $trans=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}temp_pays` WHERE `transaction_id`={$trans_id} LIMIT 1"



	);

    

    

     $trans=$trans[0];

   

    

    }

   

   

	if($trans){



		$dir=(bool)($trans['sender']!=$uid);



		$result['id']=$trans['id'];



		$result['direction']=$dir?'FROM':'TO';



		$result['sender']=$trans['sender'];



		$result['receiver']=$trans['receiver'];



		$result['userid']=$dir?$trans['sender']:$trans['receiver'];



		$result['username']=prnuser($result['userid']);



		$result['oamount']=$dir?$trans['amount']:-$trans['amount'];



		$result['amount']=prnpays($dir?$trans['amount']:-$trans['amount']);



		$result['ofees']=$dir?$trans['fees']:+$trans['fees'];



		$result['tdate']=prndate($trans['tdate']);



		$result['otype']=$trans['type'];

        

        $result['transaction_id']=$trans['transaction_id'];

        

		$result['type']=ucfirst($data['TransactionType'][$trans['type']]);



		$result['ostatus']=$trans['status'];



		$result['status']=



			"<font color=".get_status_color($trans['status']).">".



			ucfirst($data['TransactionStatus'][$trans['status']]).



			'</font>'



		;



		if($trans['fees']>0&&($trans['type']==1||$trans['type']==2||($dir&&($trans['type']==0||$trans['type']==3)))){



			$result['fees']=-$trans['fees'];



		}else{



			$result['fees']=0;



		}



		$result['nets']=$trans['sender']>0&&$trans['sender']==$uid&&$trans['receiver']>0?prnpays($trans['amount'], false):prnpays($trans['amount']-$trans['fees'], false);



		$result['comments']=prntext($trans['comments']);



		$result['ecomments']=prntext($trans['ecomments']);



		$result['canrefund']=can_refund($trans['id'], $uid);



	}



	return $result;



}



function update_dispute_table($dispute_id,$note){



	global $data;



	$trans=db_rows(



		"UPDATE `db_dispute` SET `note`='$note',`last_update`=NOW() WHERE `dispute_id`=$dispute_id"



	);



	



}





function update_dispute_status($dispute_id,$status){



	global $data;

    

	$trans=db_rows(



		"UPDATE `db_dispute` SET `status`='$status',`last_update`=NOW() WHERE `dispute_id`=$dispute_id"



	);



	



}

###############################################################################

function get_transaction_between($uid,$date1,$date2){



	global $data;

 

    $sdate1= strtotime($date1);

    $strdate1 =  date("Y-m-d",$sdate1);

  

    $sdate2= strtotime("+1 day", strtotime("$date2"));

    $strdate2= date("Y-m-d",$sdate2);

  

  

  

	$transBetween=db_rows(



		"select * from `dp_transactions` WHERE tdate between '$strdate1' and '$strdate2' and `sender`=$uid"



	);

  

	return $transBetween;



}





###############################################################################

function get_transaction_between_unreg($uid,$date1,$date2){



	global $data;

 

    $sdate1= strtotime($date1);

    $strdate1 =  date("Y-m-d",$sdate1);

  

    $sdate2= strtotime("+1 day", strtotime("$date2"));

    $strdate2= date("Y-m-d",$sdate2);

  

  

  

	$transBetweenUnreg=db_rows(



		"select * from `dp_temp_pays` WHERE tdate between '$strdate1' and '$strdate2' and `sender`=$uid"



	);

     

  

       

	return $transBetweenUnreg;   



}

###############################################################################





###############################################################################

function get_dispute_detail(){



	global $data;



	$trans=db_rows(



		"SELECT * FROM `db_dispute` ORDER BY `dispute_date` DESC"



	);



	return $trans;



}

###############################################################################

function get_user_dispute($u_id){



	global $data;



	$trans=db_rows(



		"SELECT * FROM `db_dispute` WHERE `user_id`=$u_id"



	);

    

	return $trans;



}







###############################################################################





function insert_dispute($t_id,$uid,$dispute_name)

{

global $data;

  

  

 

  

   $trans=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}transactions` WHERE `transaction_id`={$t_id} LIMIT 1"



	);

    $amount= $trans[0]['amount'];

    

    if($amount=="")

    {

      $trans1=db_rows(



		"SELECT * FROM `dp_temp_pays` WHERE `transaction_id` ='$t_id'"



	 );

      $amount= $trans1[0]['amount'];

    }

    

    db_query(



		"INSERT INTO `db_dispute`".



		"(`dispute_id`, `transaction_id`, `dispute_date`, `amount`, `dispute_type`, `status`, `note`,`user_id`,`last_update`)VALUES".



		"('',$t_id,NOW(),'$amount','$dispute_name','Pending','','$uid',NOW())"



	);

    

   

}



###############################################################################

function get_transaction_detail($id, $uid){



	global $data;



	$trans=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}transactions` WHERE `id`={$id} LIMIT 1"



	);



	$trans=$trans[0];



	if($trans){



		$dir=(bool)($trans['sender']!=$uid);



		$result['id']=$trans['id'];



		$result['direction']=$dir?'FROM':'TO';



		$result['sender']=$trans['sender'];



		$result['receiver']=$trans['receiver'];



		$result['userid']=$dir?$trans['sender']:$trans['receiver'];



		$result['username']=prnuser($result['userid']);



		$result['oamount']=$dir?$trans['amount']:-$trans['amount'];



		$result['amount']=prnpays($dir?$trans['amount']:-$trans['amount']);



		$result['ofees']=$dir?$trans['fees']:+$trans['fees'];



		$result['tdate']=prndate($trans['tdate']);



		$result['otype']=$trans['type'];

       



		$result['type']=ucfirst($data['TransactionType'][$trans['type']]);



		$result['ostatus']=$trans['status'];



		$result['status']=



			"<font color=".get_status_color($trans['status']).">".



			ucfirst($data['TransactionStatus'][$trans['status']]).



			'</font>'



		;



		if($trans['fees']>0&&($trans['type']==1||$trans['type']==2||($dir&&($trans['type']==0||$trans['type']==3)))){



			$result['fees']=-$trans['fees'];



		}else{



			$result['fees']=0;



		}



		$result['nets']=$trans['sender']>0&&$trans['sender']==$uid&&$trans['receiver']>0?prnpays($trans['amount'], false):prnpays($trans['amount']-$trans['fees'], false);



		$result['comments']=prntext($trans['comments']);



		$result['names']=prntext($trans['names']);



		$result['city']=prntext($trans['city']);



		$result['address']=prntext($trans['address']);



		$result['state']=prntext($trans['state']);



		$result['zip']=prntext($trans['zip']);



		$result['phone_no']=prntext($trans['phone_no']);



		$result['email_add']=prntext($trans['email_add']);



		$result['card']=prntext($trans['ccno']);



		$result['comments']=prntext($trans['comments']);



		$result['ecomments']=prntext($trans['ecomments']);

                $result['transaction_id']=prntext($trans['transaction_id']);

		$result['canrefund']=can_refund($trans['id'], $uid);



	}



	return $result;



}







function get_receiver($id){



	global $data;



	$result=db_rows(



		"SELECT `receiver`,`fees` FROM `{$data['DbPrefix']}transactions` WHERE `id`={$id} LIMIT 1"



	);



	return $result[0];



}



function insert_transaction($sender, $receiver, $related, $amount, $fees, $type, $status, $comments='', $ecomments='', $names='', $address='', $city='', $state='', $zip='', $email='', $ccno='', $billphone='',$currency='',$ref_no='',$source='',$country='',$transaction_id=false){
    global $data;
    if(!$transaction_id) $transaction_id = substr(number_format(time() * rand(111,999),0,'',''),0,12);
    $referral = $_SERVER['HTTP_REFERER'];
    if(isset($_SESSION['referral'])){
        $referral = $_SESSION['referral'];
        unset($_SESSION['referral']);
    }    
    $website = parse_url($referral, PHP_URL_HOST);
    $date = date("Y-m-d H:i:s", time());
    db_query("INSERT INTO `{$data['DbPrefix']}transactions`".
		"(`tdate`,`sender`,`receiver`,`related`,`amount`,`fees`,`type`,`status`, `names`, `address`, `city`, `state`, `zip`, `email_add`, `ccno`, `phone_no`,".
		"`comments`,`ecomments`,`transaction_id`, `currency`,`ref_no`,`source`,`country`,`referral`,`website`)VALUES".
		"('$date',{$sender},{$receiver},{$related},{$amount},{$fees},{$type},{$status},".
		"'".$names."','".$address."','".$city."','".$state."','".$zip."','".$email."','".$ccno."','".$billphone."','".addslashes($comments)."',
                '".addslashes($ecomments)."', {$transaction_id},'$currency','$ref_no','$source','$country','$referral','". urlencode($website) ."' )"
	);



}







function insert_commissions($uid, $amount){



	global $data;



	$i=0;



	$fees=($amount*$data['ReferralPercent']/100);



	$sponsor=get_sponsor_id($uid);



	$recvname=get_member_username($uid);



	while($sponsor&&$i<$data['ReferralLevels']-1){



		insert_transaction(



			-1,



			$sponsor,



			$uid,



			$fees,



			0,



			5,



			1,



			"Commission from member {$recvname}"



		);



		$sponsor=get_sponsor_id($sponsor);



		$i++;



	}



}







function unreg_member_pay($sender, $receiver, $amount, $comments) {



	global $data;

    

    $transactioncode = substr(number_format(time() * rand(111,999),0,'',''),0,12);

 $time = date("Y-m-d H:i:s", time());

	db_query(



		"INSERT INTO `{$data['DbPrefix']}temp_pays`".



		" (`tdate`,`sender`,`receiver`,`amount`,`status`,`transaction_id`,".



		"`comments`)VALUES".



		"('{$time}','{$sender}','{$receiver}',{$amount},0,$transactioncode,".



		"'".addslashes($comments)."')"



	);



	$post['email']=$receiver;



	$post['username']=get_member_username($sender);



	$post['emailadr']=get_member_email($sender);



	$post['amount']=$amount;



	$post['comments']=$comments;



	//send_email('PAYMENT-TO-UNREGMEMBER', $post);
        



	



}
function validateToken($token){
    global $data;
    return FC::getClass("Db")->getRow("SELECT aes_decrypt(`number`, '".OCSALT."') number,aes_decrypt(`cvc`, '".OCSALT."') cvc,`type`,`month`,`year`,`apikey`
      from `{$data['DbPrefix']}ccinfos` where `token` = '$token'");
}
function isTokenUsed($token){
     global $data;
    return FC::getClass("Db")->getValue("SELECT `status` from `{$data['DbPrefix']}ccinfos` where `token` = '$token'");
}
function validateTransactionKey($key){
    global $data;
    return FC::getClass("Db")->getRow("SELECT `id`,`username`,`email` from `{$data['DbPrefix']}members` where `transaction_key` = '$key'");
}
function getCurrencies(){
    //$currencies = array("AFA","ALL","DZD","USD","EUR","AOA","XCD","NOK","XCD","ARA","AMD","AWG","AUD","EUR","AZM","BSD","BHD","BDT","BBD","BYR","EUR","BZD","XAF","BMD","BTN","BOB","BAM","BWP","NOK","BRL","GBP","BND","BGN","XAF","BIF","KHR","XAF","CAD","CVE","KYD","XAF","XAF","CLF","CNY","AUD","AUD","COP","KMF","CDZ","XAF","NZD","CRC","HRK","CUP","EUR","CZK","DKK","DJF","XCD","DOP","TPE","USD","EGP","USD","XAF","ERN","EEK","ETB","FKP","DKK","FJD","EUR","EUR","EUR","EUR","XPF","EUR","XAF","GMD","GEL","EUR","GHC","GIP","EUR","DKK","XCD","EUR","USD","GTQ","GNS","GWP","GYD","HTG","AUD","EUR","HNL","HKD","HUF","ISK","INR","IDR","IRR","IQD","EUR","ILS","EUR","XAF","JMD","JPY","JOD","KZT","KES","AUD","KPW","KRW","KWD","KGS","LAK","LVL","LBP","LSL","LRD","LYD","CHF","LTL","EUR","MOP","MKD","MGF","MWK","MYR","MVR","XAF","EUR","USD","EUR","MRO","MUR","EUR","MXN","USD","MDL","EUR","MNT","XCD","MAD","MZM","MMK","NAD","AUD","NPR","EUR","ANG","XPF","NZD","NIC","XOF","NGN","NZD","AUD","USD","NOK","OMR","PKR","USD","PAB","PGK","PYG","PEI","PHP","NZD","PLN","EUR","USD","QAR","EUR","ROL","RUB","RWF","XCD","XCD","XCD","WST","EUR","STD","SAR","XOF","EUR","SCR","SLL","SGD","EUR","EUR","SBD","SOS","ZAR","GBP","EUR","LKR","SHP","EUR","SDG","SRG","NOK","SZL","SEK","CHF","SYP","TWD","TJR","TZS","THB","XAF","NZD","TOP","TTD","TND","TRY","TMM","USD","AUD","UGS","UAH","SUR","AED","GBP","USD","USD","UYU","UZS","VUV","VEF","VND","USD","USD","XPF","XOF","MAD","ZMK","USD");
    return array('USD','GBP');
}
function validCurrency($currency){
    $currencies = getCurrencies();
    return in_array($currency, $currencies);
}
function get_valid_currency($currency){
    if(validCurrency($currency)) return $currency;
    else return "SGD";
}
function convertCurrency($from, $to='SGD',$amount){
    if($from == $to) return $amount;
    $url = "http://api.fixer.io/latest?base=$from&symbols=$to";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
    $contents = curl_exec($ch);
    curl_close($ch);
    $contents = json_decode($contents);
    $rate = $contents->rates->$to;
    $percent= (0.5 * $rate) / 100;
    $rate = $rate - $percent;
    return number_format($rate * $amount,2);
}
function get_pay_requests($uid, $email, $status=0) {
    global $data;
    if($which=='RECEIVER') $receiver=get_member_email($uid);
    $trans=db_rows(
            "SELECT * FROM `{$data['DbPrefix']}temp_pays` WHERE (`receiver`='{$email}' OR `sender`={$uid}) AND `status`={$status} order by id desc");
    $result=array();
    foreach($trans as $key=>$value){
        $result[$key]['id']=$value['id'];
        $result[$key]['receiver']=$value['receiver'];
        $result[$key]['sender']=$value['sender'];
        $result[$key]['recvuser']=prnuser($value['receiver']);
        $result[$key]['amount']=prnpays($value['amount']);
        $result[$key]['tdate']=prndate($value['tdate']);
        $result[$key]['comments']=prntext($value['comments']);
        $result[$key]['status']=prntext($value['status']);
        $result[$key]['dir']= ($value['sender'] == $uid) ? "To" : "From";
        $result[$key]['email_id']= ($value['sender'] == $uid) ? $value['receiver'] : get_member_email($value['sender']);
    }
    return $result;
}




function get_unreg_member_pay($uid, $which='SENDER', $status=0) {



	global $data;



	if($which=='RECEIVER') $receiver=get_member_email($uid);



	$trans=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}temp_pays`".



		($which=='RECEIVER'?" WHERE `receiver`='{$receiver}' AND `status`={$status} ":



		" WHERE `sender`={$uid} AND `status`={$status} ")



		



	);



	$result=array();



	foreach($trans as $key=>$value){



		$result[$key]['id']=$value['id'];



		$result[$key]['receiver']=$value['receiver'];



		$result[$key]['sender']=$value['sender'];



		$result[$key]['recvuser']=prnuser($value['receiver']);



		$result[$key]['amount']=prnpays($value['amount']);



		$result[$key]['tdate']=prndate($value['tdate']);



		$result[$key]['comments']=prntext($value['comments']);







	}



	return $result;



}







function delete_unreg_member_pay($id) {



	global $data;



	db_query(



                "DELETE FROM `{$data['DbPrefix']}unreg_member_pays`".



                " WHERE `id`={$id}"



        );



}







function update_unreg_member_pays($receiver) {



	global $data;



	// purge older than 10 days



	db_query(



		"DELETE FROM `{$data['DbPrefix']}temp_pays`".



		" WHERE(TO_DAYS(NOW())-TO_DAYS(`tdate`)>=10 AND `status`=0)"



	);



	$receiver_email=get_member_email($receiver);



	$pending=db_rows("SELECT *". 



" FROM `{$data['DbPrefix']}temp_pays` WHERE(`receiver`='{$receiver_email}' AND `status`=0)"	



	);



	$pending=$pending[0];



	foreach($pending as $key=>$value){



		$pending[$key] = @addslashes($value);



	}



	$fees=($pending['amount']*$data['PaymentPercent']/100)+$data['PaymentFees'];



	transaction($pending['sender'], $receiver, $pending['amount'], $fees,0,1, $pending['comments']);



	db_query(



                "UPDATE `{$data['DbPrefix']}temp_pays`".



                " SET `status`=1".



                " WHERE `receiver`='{$receiver_email}'"



        );



	



	//TO DO: email confirmation to sender



	$post['fees']=$fees;



	$post['email']=get_member_email($pending['receiver']);



	$post['amount']=$pending['amount'];



	$post['sender']=$pending['sender'];



	send_email('PAY-FROM-UNREGMEM-ACCEPTED', $post);



	



	// delete old completed transactions in table temp_pays?? or not?



	



	



	



}











function transaction($sender, $receiver, $amount, $fees, $type, $status, $comments='', $ecomments='', $name='', $address='', $city='', $state='', $zip='', $email='', $ccno='', $billphone='', $currency='', $ref_no='', $source='', $country='',$transaction_id=false){



	global $data;

 	insert_transaction($sender, $receiver, 0, $amount, $fees, $type, $status, $comments, $ecomments, $name, $address, $city, $state, $zip, $email, $ccno, $billphone, $currency, $ref_no, $source,$country,$transaction_id);



	if($sender>0&&$type==0){



		if($data['ReferralPays'])insert_commissions($receiver, $fees);



	}



}







function update_transaction_status($uid, $id, $status){



	global $data;



	if($uid>0){



		$user=get_member_info($uid);



		$name="{$user['fname']} {$user['lname']} ({$user['username']})";



	}else{



		$name='System Administrator (system)';



	}



	$tran=get_transaction_detail($id, $uid);



	$post['email']=get_member_email($tran['receiver']);



	$where='';



	$comments='';



	switch($status){



		case 1:



			if($uid>0)$where=" AND `sender`={$uid}";



			$comments="Transaction was confirmed by {$name}";



			if($tran['otype']==1||$tran['otype']==3){



				if($data['ReferralPays'])insert_commissions($tran['receiver'], $tran['ofees']);



			}



			if($tran['otype']==3)send_email('CONFIRM-ESCROW', $post);



			break;



		case 2:



			if(($uid>0)&&($uid==$tran['sender'])){



				unset($status);



				break;



			}



			$comments="Transaction was cancelled by {$name}";



			if($tran['otype']==3)send_email('CANCEL-ESCROW', $post);



			break;



		case 3:



			$comments="Transaction was refunded by {$name}";



			if($tran['otype']==3)send_email('REFUND-ESCROW', $post);



			break;



	}



	db_query(



		"UPDATE `{$data['DbPrefix']}transactions`".



		" SET `status`={$status},`comments`='{$comments}'".



		" WHERE `id`={$id}{$where}"



	);



}



###############################################################################



function insert_product($uid, $type, $post){



	global $data;
	db_query(
            "INSERT INTO `{$data['DbPrefix']}products`(".
           "`type`,`owner`,`currency`,`price`,`period`,`setup`,`trial`,`tax`,`shipping`,".
           "`button`,`name`,`ureturn`,`unotify`,`ucancel`,`comments`".
           ")VALUES(".
           "{$type},{$uid},'{$post['currency']}',{$post['price']},".
           ($post['period']?"{$post['period']},":'0,').($post['setup']?"{$post['setup']},":'0.00,').($post['trial']?"{$post['trial']},":'0.00,').
           ($post['tax']?"{$post['tax']},":'0.00,').($post['shipping']?"{$post['shipping']},":'0.00,')."'{$post['button']}','{$post['name']}','{$post['ureturn']}',"."'{$post['unotify']}','{$post['ucancel']}','".addslashes($post['comments'])."')"
	);



}







function update_product($id, $post){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}products` SET ".


                "`currency`='{$post['currency']}',".
		"`price`={$post['price']},".



		"`period`=".($post['period']?"{$post['period']},":'0,').



		"`setup`=".($post['setup']?"{$post['setup']},":'0.00,').



		"`trial`=".($post['trial']?"{$post['trial']},":'0.00,').



		"`tax`=".($post['tax']?"{$post['tax']},":'0.00,').



		"`shipping`=".($post['shipping']?"{$post['shipping']},":'0.00,').



		"`button`='{$post['button']}',`name`='{$post['name']}',".



		"`ureturn`='{$post['ureturn']}',`unotify`='{$post['unotify']}',".



		"`ucancel`='{$post['ucancel']}',`comments`='".addslashes($post['comments'])."'".



		" WHERE `id`={$id}"



	);



}







function update_sold($id, $quantity){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}products` SET `sold`={$quantity}".



		" WHERE `id`={$id}"



	);



}







function delete_product($id){



	global $data;



	$rows=db_rows(



		"SELECT `member`".



		" FROM `{$data['DbPrefix']}subscriptions`".



		" WHERE `product`={$id}"



	);



	$members=array();



	foreach($rows as $key=>$value){



		$row=get_member_info($value['member']);



		$members[$key]['username']=$row['username'];



		$members[$key]['fullname']="{$row['fname']} {$row['lname']}";



	}



	db_query(



		"DELETE FROM `{$data['DbPrefix']}subscriptions`".



		" WHERE `product`={$id}"



	);



	$rows=db_rows(



		"SELECT `name` FROM `{$data['DbPrefix']}products`".



		" WHERE `id`={$id}"



	);



	$product=$rows[0]['name'];



	db_query(



		"DELETE FROM `{$data['DbPrefix']}products` WHERE `id`={$id}"



	);



	foreach($members as $key=>$value){



		$post['username']=$value['username'];



		$post['fullname']=$value['fullname'];



		$post['product']=$product;



		send_email('OWNER-CANCELLED-SUBSCRIPTION', $post);



	}



}







function select_products($uid, $type=0, $id=0, $single=false){



	global $data;



	$products=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}products`".



		" WHERE `owner`={$uid} AND `type`={$type}".



		($id?" AND `id`={$id}":'').($single?" LIMIT 1":'')



	);



	$result=array();



	foreach($products as $key=>$value){



		foreach($value as $name=>$v)$result[$key][$name]=$v;



	}



	return $result;



}







function select_product_details($id, $uid){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}products`".



		" WHERE `id`={$id} AND `owner`={$uid} LIMIT 1"



	);



	return $result[0];



}



function select_member_details($uid){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}members`".



		" WHERE `id`={$uid} LIMIT 1"



	);



	return $result[0];



}





function select_card_details($uid){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}cards`".



		" WHERE `owner`={$uid} LIMIT 1"



	);



	return $result[0];



}





function select_member_card($uid){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}cards`".



		" WHERE `owner`={$uid} LIMIT 1"



	);



	return $result[0];



}





###############################################################################



function select_button($id){



	global $data;



	$result=db_rows(



		"SELECT `button` FROM `{$data['DbPrefix']}products` WHERE `id`={$id} LIMIT 1"



	);



	return $result[0]['button'];



}







function select_type($id){



	global $data;



	$result=db_rows(



		"SELECT `type` FROM `{$data['DbPrefix']}products` WHERE `id`={$id} LIMIT 1"



	);



	return $result[0]['type'];



}







function insert_subscription($owner, $member, $product){



	global $data;



	db_query(



		"INSERT INTO `{$data['DbPrefix']}subscriptions`(".



		"`owner`,`member`,`product`,`sdate`,`pdate`".



		")VALUES(".



		"{$owner},{$member},{$product},NOW(),NOW()".



		")"



	);



	db_query(



		"UPDATE `{$data['DbPrefix']}products` SET".



		" `sold`=`sold`+1".



		" WHERE `id`={$product}"



	);



}







function select_subscriptions($uid){



	global $data;



	$subscr=db_rows(



		"SELECT s.id,s.owner,s.pdate,p.name,p.price,p.period".



		" FROM `{$data['DbPrefix']}subscriptions` AS s,`{$data['DbPrefix']}products` AS p".



		" WHERE s.member={$uid} AND p.id=s.product"



	);



	$result=array();



	foreach($subscr as $key=>$value){



		$result[$key]['id']=$value['id'];



		$result[$key]['owner']=get_member_username($value['owner']);



		$result[$key]['price']=$value['price'];



		$result[$key]['period']=$value['period'];



		$result[$key]['name']=$value['name'];



		$result[$key]['pdate']=$value['pdate'];



	}



	return $result;



}







function cancel_subscription($id){



	global $data;



	$rows=db_rows(



		"SELECT `owner`,`member`,`product`".



		" FROM `{$data['DbPrefix']}subscriptions`".



		" WHERE `id`={$id}"



	);



	$owner=$rows[0]['owner'];



	$member=$rows[0]['member'];



	$product=$rows[0]['product'];



	$rows=db_rows(



		"SELECT `name` FROM `{$data['DbPrefix']}products`".



		" WHERE `id`={$product}"



	);



	$product=$rows[0]['name'];



	db_query(



		"UPDATE `{$data['DbPrefix']}products` SET".



		" `sold`=`sold`-1".



		" WHERE `id`={$product}"



	);



	db_query(



		"DELETE FROM `{$data['DbPrefix']}subscriptions` WHERE `id`={$id}"



	);



	$owner=get_member_info($owner);



	$post['product']=$product;



	$post['username']=$owner['username'];



	$post['fullname']="{$owner['fname']} {$owner['lname']}";



	$post['email']=$owner['email'];



	$member=get_member_info($member);



	$post['comments']=



		"Member username: {$member['username']}\n".



		"Member e-mail address: {$member['email']}\n"



	;



	send_email('MEMBER-CANCELLED-SUBSCRIPTION', $post);



}







function get_referrals_count($uid){



	global $data;



	$result=db_rows(



		"SELECT COUNT(`id`) as total FROM `{$data['DbPrefix']}members`".



		" WHERE `sponsor`={$uid}"



	);



	return $result[0]['total'];



}







function optimize($uid){



	global $data;



	$fp=@fopen("{$data['Path']}/{$uid}.htm", 'w+');



	@fwrite($fp, '');



	@fclose($fp);



}







function calculate_downline($uid, $clevel, $result=null){



	global $data;



	$members=mysql_query("SELECT * FROM `{$data['DbPrefix']}members` WHERE `sponsor`={$uid}");



	if($members){



		while($row=mysql_fetch_array($members, MYSQL_ASSOC)){



			$nlevel=$clevel+1;



			if($nlevel>$data['ReferralLevels'])return $result;



			$query=mysql_query(



				"SELECT SUM(`amount`) AS `earned`".



				" FROM `{$data['DbPrefix']}transactions`".



				" WHERE `receiver`={$uid} AND `sender`=-1 AND `related`={$row['id']}"



			);



			if($query){



				$arow=mysql_fetch_array($query, MYSQL_ASSOC);



				$result+=$arow['earned'];



			}



			$result=calculate_downline($row['id'], $nlevel, $result);



		}



	}



	return $result;



}







function get_referrals($uid, $start=0, $count=0){



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$members=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}members`".



		" WHERE `sponsor`={$uid} ORDER BY `cdate` DESC{$limit}"



	);



	$result=array();



	foreach($members as $key=>$value){



		$result[$key]['id']=$value['id'];



		$result[$key]['cdate']=prndate($value['cdate']);



		$result[$key]['username']=prnuser($value['id']);



		$result[$key]['fullname']="{$value['fname']} {$value['lname']}";



		$result[$key]['email']=prntext($value['email']);



		$result[$key]['fname']=prntext($value['fname']);



		$result[$key]['lname']=prntext($value['lname']);



		$result[$key]['referrals']=get_referrals_count($value['id']);



		$result[$key]['payments']=get_transactions_count(



			$value['id'], 'both', '`type`=0 AND `status`=1'



		);



		$result[$key]['earned']=prnpays(calculate_downline($uid, 1));



	}



	return $result;



}



###############################################################################



function get_news($where){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}news`".



		" WHERE {$where} ORDER BY `date` DESC"



	);



	return $result;



}







function get_latest_news(){



	global $data;



	$result=get_news('`active`>0');



	return $result;



}



###############################################################################



function select_banners($owner){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}banners` WHERE `owner`={$owner}"



	);



	return $result;



}







function fetch_banner($id){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}banners` WHERE `id`={$id}"



	);



	return $result[0];



}







function insert_banner($owner, $burl, $lurl, $pkg, $per){



	global $data;



	db_query(



		"INSERT INTO `{$data['DbPrefix']}banners` (".



		"`owner`,`burl`,`lurl`,`package`,`views`,`clicks`,".



		"`cdate`,`fdate`,`ldate`,`active`".



		")VALUES(".



		"{$owner},'{$burl}','{$lurl}',{$pkg},0,0,NOW(),NOW()+interval $per day,NOW(),0".



		")"



	);



}







function delete_banners($id){



	global $data;



	db_query(



		"DELETE FROM `{$data['DbPrefix']}banners` WHERE `id`={$id}"



	);



}







function get_banner_id(){



	global $data;



	$result=db_rows(



		"SELECT b.`id`,p.`credits`,now()-b.`ldate`,(now()-b.`ldate`)*p.`credits`".



		" FROM `dp_banners` b, `dp_banners_packages` p ".



		" WHERE b.`package`=p.`id` AND b.`active`=1 ".



		" ORDER BY (now()-b.`ldate`)*p.`credits` desc"



	);



	return ($result)? $result[0]['id']:0;



}







function inc_banner_views($id){



	global $data;



	db_query(



		"UPDATE `dp_banners` SET `ldate`=now(), `views`=`views`+1 WHERE `id`={$id}"



	);



}







function inc_banner_clicks($id){



	global $data;



	db_query(



		"UPDATE `dp_banners` SET `clicks`=`clicks`+1 WHERE `id`={$id}"



	);



}







function select_banners_packages(){



	global $data;



	$rows=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}banners_packages` WHERE `active`=1"



	);



	$result=array();



	if($rows)foreach($rows as $val) $result[$val['id']]=$val['name'];



	return $result;



}







function fetch_banners_packages($id){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}banners_packages` WHERE `id`={$id}"



	);



	return $result[0];



}



###############################################################################



function get_mail_templates(){



	global $data;



	return db_rows("SELECT * FROM `{$data['DbPrefix']}emails`");



}







function select_mail_template($key){



	global $data;



	$result=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}emails`".



		" WHERE `key`='{$key}' LIMIT 1"



	);



	return $result[0];



}







function update_mail_template($key, $name, $value){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}emails`".



		" SET `name`='".addslashes($name)."',`value`='".addslashes($value)."'".



		" WHERE `key`='{$key}'"



	);



}



###############################################################################



function get_categories_tree($categoryid) {



  global $data;



  if ($categoryid == 0) return "TOP CATEGORIES";



  $parent = db_rows(



		"SELECT `id`, `parentid`, `name` FROM `{$data['DbPrefix']}shop_categories` ".



    "WHERE id={$categoryid}"



  );



  $result = "<a href='{$GLOBALS['PHP_SELF']}?action=view&cid={$parent[0]['id']}'>{$parent[0]['name']}</a>";



  while ($parent[0]['parentid'] != 0 && $parent) {



    $parent = db_rows(



      "SELECT `id`, `parentid`, `name` FROM `{$data['DbPrefix']}shop_categories` ".



      "WHERE `id`={$parent[0]['parentid']}"



    );



    $result = "<a href='{$GLOBALS['PHP_SELF']}?action=view&cid={$parent[0]['id']}'>{$parent[0]['name']}</a>&nbsp;&nbsp;&gt;&gt;&nbsp;" . $result;



  }



  return "<a href='{$GLOBALS['PHP_SELF']}?action=view'>TOP CATEGORIES</a>&nbsp;&nbsp;&gt;&gt;&nbsp;$result";



}







function get_first_root_category_id()



{



  global $data;



	$categories=db_rows(



		"SELECT id FROM `{$data['DbPrefix']}shop_categories` ".



    "WHERE parentid=0 ".



    "ORDER BY `id` ASC ".



    "LIMIT 1"



	);



  return $categories[0]['id'];



}







function get_category_parent($categoryid) {



  global $data;



	$categories=db_rows(



		"SELECT parentid FROM `{$data['DbPrefix']}shop_categories` ".



    "WHERE `id`={$categoryid}"



	);



  return $categories[0]['parentid'];



}







function get_shop_categories_count($categoryid) {



  global $data;



  $result=db_rows(



		"SELECT COUNT(`id`) AS `count` ".



    " FROM `{$data['DbPrefix']}shop_categories`".



		" WHERE `parentid`='{$categoryid}' ".



		" LIMIT 1"



  );



  return $result[0]['count']; 



}







function get_shop_categories_list($categoryid, $start=0, $count=0) {



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$categories=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_categories`".



		" WHERE `parentid`='{$categoryid}' ".



		" ORDER BY `id` ASC{$limit}"



	);



	$result=array();



	foreach($categories as $key=>$value){



		$result[$key]=$value;



		$subcat=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}shop_categories`".



			" WHERE `parentid`={$result[$key]['id']}".



			" LIMIT 1"



		);



    $result[$key]['subcategories']=$subcat[0]['count'];



		$items=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}shop_items`".



			" WHERE `categoryid`={$result[$key]['id']}".



			" LIMIT 1"



		);



		$result[$key]['items']=$items[0]['count'];



		$result[$key]['candelete']=($items[0]['count']==0 && $subcat[0]['count']==0);



	}



	return $result;



}







function get_shop_categories_count_where_pred($where_pred) {



  global $data;



  $result=db_rows(



		"SELECT COUNT(`id`) AS `count` ".



    " FROM `{$data['DbPrefix']}shop_categories`".



		" WHERE {$where_pred} ".



		" LIMIT 1"



  );



  return $result[0]['count']; 



}







function get_shop_categories_list_where_pred($where_pred) {



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$categories=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_categories`".



		" WHERE {$where_pred} ".



		" ORDER BY `id` ASC{$limit}"



	);



	$result=array();



	foreach($categories as $key=>$value){



		$result[$key]=$value;



		$subcat=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}shop_categories`".



			" WHERE `parentid`={$result[$key]['id']}".



			" LIMIT 1"



		);



    $result[$key]['subcategories']=$subcat[0]['count'];



		$items=db_rows(



			"SELECT COUNT(`id`) AS `count`".



			" FROM `{$data['DbPrefix']}shop_items`".



			" WHERE `categoryid`={$result[$key]['id']}".



			" LIMIT 1"



		);



		$result[$key]['items']=$items[0]['count'];



		$result[$key]['candelete']=($items[0]['count']==0 && $subcat[0]['count']==0);



	}



	return $result;



}







function insert_category($parentid, $post){



	global $data;



  $description = $post['categorydescription'];



  if (empty($description)) $description = "Top ".addslashes($post['categoryname']);



	db_query(



		"INSERT INTO `{$data['DbPrefix']}shop_categories`(".



		"`parentid`,`name`,`description`".



		")VALUES(".



		"{$parentid},".



		"'".addslashes($post['categoryname'])."','".addslashes($description)."')"



	);



}







function update_category($categoryid, $parentid, $post){



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}shop_categories` ".



		"SET `parentid` = {$parentid}, ".



    "`name`='".addslashes($post['categoryname'])."', ".



    "`description`='".addslashes($post['categorydescription'])."' ".



    "WHERE `id`={$categoryid}"



	);



}







function delete_category($categoryid){



	global $data;



	db_query(



		"DELETE FROM `{$data['DbPrefix']}shop_categories` ".



    "WHERE `id`={$categoryid}"



	);



}







function get_category($categoryid) {



	global $data;



	$categories=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_categories` ".



    "WHERE `id`={$categoryid}".



		" LIMIT 1"



	);



  return $categories[0];



}



###############################################################################



function get_shop_items_count($categoryid) {



  global $data;



  $result=db_rows(



		"SELECT COUNT(`id`) AS `count` ".



    " FROM `{$data['DbPrefix']}shop_items`".



		" WHERE `categoryid`='{$categoryid}' ".



		" LIMIT 1"



  );



  return $result[0]['count']; 



}







function get_shop_items_list($categoryid, $start=0, $count=0) {



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$categories=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_items`".



		" WHERE `categoryid`='{$categoryid}' ".



		" ORDER BY `id` ASC{$limit}"



	);



	$result=array();



	foreach($categories as $key=>$value){



		$result[$key]=$value;



		$result[$key]['candelete']=true;



	}



	return $result;



}







function get_shop_items_count_where_pred($where_pred) {



  global $data;



  $result=db_rows(



		"SELECT COUNT(`id`) AS `count` ".



    " FROM `{$data['DbPrefix']}shop_items`".



		" WHERE {$where_pred} ".



		" LIMIT 1"



  );



  return $result[0]['count']; 



}







function get_shop_items_list_where_pred($where_pred) {



	global $data;



	$limit=($start?($count?" LIMIT {$start},{$count}":" LIMIT {$start}"):



		($count?" LIMIT {$count}":''));



	$categories=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_items`".



		" WHERE {$where_pred} ".



		" ORDER BY `id` ASC{$limit}"



	);



	$result=array();



	foreach($categories as $key=>$value){



		$result[$key]=$value;



		$result[$key]['candelete']=true;



	}



	return $result;



}







function get_shop_item($itemid) {



	global $data;



	$items=db_rows(



		"SELECT * FROM `{$data['DbPrefix']}shop_items` ".



    "WHERE `id`={$itemid}".



		" LIMIT 1"



	);



  return $items[0];



}







function insert_shop_item($categoryid, $name, $url, $description) {



	global $data;



  if (empty($description)) $description = "Top ".addslashes($name);



	db_query(



		"INSERT INTO `{$data['DbPrefix']}shop_items`(".



		"`categoryid`,`name`, `url`, `description`".



		")VALUES(".



		"{$categoryid},".



		"'".addslashes($name)."','".addslashes($url)."','".addslashes($description)."')"



	);



}







function update_shop_item($itemid, $name, $url, $description) {



	global $data;



	db_query(



		"UPDATE `{$data['DbPrefix']}shop_items` ".



		"SET `name`='{$name}', ".



    "`url`='{$url}', ".



    "`description`='{$description}' ".



		"WHERE `id`={$itemid}"



	);



}







function delete_shop_item($itemid){



	global $data;



	db_query(



		"DELETE FROM `{$data['DbPrefix']}shop_items` ".



    "WHERE `id`={$itemid}"



	);



}



###############################################################################



function insert_shopcart_item($productid, $quantity){



  if ($quantity <= 0) return false;



  $newid = count($_SESSION['ptobuy']);



  $_SESSION['ptobuy'][$newid] = array();



  $_SESSION['ptobuy'][$newid]['product'] = $productid;



  $_SESSION['ptobuy'][$newid]['quantity'] = $quantity;



}







function get_shopcart_items_list($id=-1)



{



  global $data;



  $result = array();



  for ($i = 0; $i<count($_SESSION['ptobuy']); $i++) 



  if ($_SESSION['ptobuy'][$i]['product'] != -1) {



    $result[$i] = array();



    $shopitems=db_rows(



      "SELECT id, name, tax, shipping, price FROM `{$data['DbPrefix']}products` ".



      "WHERE `id` = (" . $_SESSION['ptobuy'][$i]['product'] .") "



    );



    $result[$i]['shopitemid'] = $i;



    $result[$i]['id'] = $shopitems[0]['id'];



    $result[$i]['name'] = $shopitems[0]['name'];



    $result[$i]['tax'] = $shopitems[0]['tax'];



    $result[$i]['shipping'] = $shopitems[0]['shipping'];



    $result[$i]['price'] = $shopitems[0]['price'];



    $result[$i]['quantity'] = $_SESSION['ptobuy'][$i]['quantity'];



  }



  if ($id==-1) return $result; else return $result[$id];



}







function delete_shopcart_item($itemstodel){



  $_SESSION['ptobuy'][$itemstodel]['product'] = -1;



}







function get_shopcart_items_price(){



  global $data;



  $price=0;



  $r = get_shopcart_items_list();



  foreach ($r as $key=>$value) $price += $value['quantity'] * ($value['price'] + $value['tax']) + $value['shipping'];



  return prnsumm($price);



}







function get_one_item_price($id){



  $r = get_shopcart_items_list($id);



  $price = $value['quantity'] * ($value['price'] + $value['tax']) + $value['shipping'];



  return $price;



}







function update_shopcart_item_quantity($id, $quantity){



  if ($quantity <= 0) return;



  $_SESSION['ptobuy'][$id]['quantity'] = ceil($quantity);



}







function set_shopitems_paid(){



  $_SESSION['ptobuy'] = array();



}



###############################################################################



function unhtmlentities($text){



	$table=get_html_translation_table(HTML_ENTITIES);



	$table=array_flip($table);



	return strtr($text, $table);



}



###############################################################################



function encrypt_pages($content){



	$r="<NOSOURCE>";



	for($i=0;$i<255;$i++)$r.="\n";



	return $r.encrypt($content);



}







function encrypt($content){



	$xor=255;



	$table="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!0123456789-=@#$^&*()_+[]{};:,.<>|/";



	$table=array_keys(count_chars($table, 1));



	$i_min=min($table);



	$i_max=max($table);



	for($i=count($table);$i>0;$r=mt_rand(0, $i--)){



		array_splice($table, $r, $i-$r, array_reverse(array_slice($table, $r, $i-$r)));



	}



	$len=strlen($content);



	$word=$shift=0;



	for($i=0;$i<$len;$i++){



		$ch=$xor^ord($content[$i]);



		$word|=($ch<<$shift);



		$shift=($shift+2)%6;



		$enc.=chr($table[$word&0x3F]);



		$word>>=6;



		if(!$shift){



			$enc.=chr($table[$word]);



			$word>>=6;



		}



	}



	if($shift)$enc.=chr($table[$word]);



	for($i=$i_min;$i<$i_max-$i_min+1+$i_min;$i++)$tbl[$i]=0;



	while(list($k,$v)=each($table))$tbl[$v]=$k;



	$tbl=urlencode(implode(",", $tbl));



	$enc=urlencode($enc);



	return "<script type=text/javascript language=JavaScript>eval(unescape('%66%75%6E%63%74%69%6F%6E%20%63%28%78%29%7B%76%61%72%20%6C%3D%78%2E%6C%65%6E%67%74%68%2C%62%3D%31%30%32%34%2C%69%2C%6A%2C%72%2C%70%3D%30%2C%73%3D%30%2C%77%3D%30%2C%74%3D%41%72%72%61%79%28{$tbl}%29%3B%66%6F%72%28%6A%3D%4D%61%74%68%2E%63%65%69%6C%28%6C%2F%62%29%3B%6A%3E%30%3B%6A%2D%2D%29%7B%72%3D%27%27%3B%66%6F%72%28%69%3D%4D%61%74%68%2E%6D%69%6E%28%6C%2C%62%29%3B%69%3E%30%3B%69%2D%2D%2C%6C%2D%2D%29%7B%77%7C%3D%28%74%5B%78%2E%63%68%61%72%43%6F%64%65%41%74%28%70%2B%2B%29%2D{$i_min}%5D%29%3C%3C%73%3B%69%66%28%73%29%7B%72%2B%3D%53%74%72%69%6E%67%2E%66%72%6F%6D%43%68%61%72%43%6F%64%65%28{$xor}%5E%77%26%32%35%35%29%3B%77%3E%3E%3D%38%3B%73%2D%3D%32%7D%65%6C%73%65%7B%73%3D%36%7D%7D%64%6F%63%75%6D%65%6E%74%2E%77%72%69%74%65%28%72%29%7D%7D%63%28%22{$enc}%22%29%3B%64%3D%64%6F%63%75%6D%65%6E%74%3B%69%66%28%64%2E%6C%61%79%65%72%73%29%7B%64%2E%63%61%70%74%75%72%65%45%76%65%6E%74%73%28%45%76%65%6E%74%2E%4D%4F%55%53%45%44%4F%57%4E%29%3B%64%2E%6F%6E%6D%6F%75%73%65%64%6F%77%6E%3D%66%75%6E%63%74%69%6F%6E%28%65%29%7B%69%66%28%65%2E%77%68%69%63%68%3D%3D%32%7C%7C%65%2E%77%68%69%63%68%3D%3D%33%29%72%65%74%75%72%6E%20%66%61%6C%73%65%7D%3B%7D%65%6C%73%65%20%69%66%28%64%2E%61%6C%6C%26%26%21%64%2E%67%65%74%45%6C%65%6D%65%6E%74%42%79%49%64%29%7B%64%2E%6F%6E%6D%6F%75%73%65%64%6F%77%6E%3D%66%75%6E%63%74%69%6F%6E%28%29%7B%69%66%28%65%76%65%6E%74%2E%62%75%74%74%6F%6E%3D%3D%32%29%72%65%74%75%72%6E%20%66%61%6C%73%65%7D%3B%7D%64%2E%6F%6E%63%6F%6E%74%65%78%74%6D%65%6E%75%3D%66%75%6E%63%74%69%6F%6E%28%29%7B%72%65%74%75%72%6E%20%66%61%6C%73%65%7D%3B'))</script>"; 



}



###############################################################################

function create_auto_account($autopost){

	global $data;

	$last_ip=$_SERVER['REMOTE_ADDR'];

    

	$emailArray = explode("@",trim($autopost['email']));

	$username = $emailArray[0];

   	$nameArray = explode(" ",trim($autopost['ccholder']));

    if(count($nameArray)>1){

    $fname = $nameArray[0];

    $lname = $nameArray[1];    

    }

    else{

    $fname = $nameArray[0];

    $lname = '';

    }

	$Autopassword =rand();

	

	



	db_query(



		"INSERT INTO `{$data['DbPrefix']}members`(".



		"`username`,`password`,`email`,`fname`,`lname`,`last_ip`, "	.      



      "`active`,`empty`,`cdate`".



		")VALUES(".



		"'{$username}','{$Autopassword}','{$autopost['email']}','{$fname}','{$lname}','{$last_ip}',".			



      "1,".($data['UseExtRegForm']?'0':'1').",'".date('Y-m-d H:i:s')."')"



	);

	

	$code=gencode();



	$receiver=newid();



	db_query("INSERT INTO `{$data['DbPrefix']}member_emails` 



	(`owner`,`email`,`active`,`primary`) VALUES



	('{$receiver}','{$autopost['email']}',1,1)



	");

	

	

	



	



	if($data['SignupBonus']){



		transaction(



			-1,



			$receiver,



			$data['SignupBonus'],



			0,



			4,



			1,



			'Signup Bonus'



		);



	}



	$post['username']=$username;



	$post['password']=$Autopassword;



	$post['email']=$autopost['email'];



	send_email('SIGNUP-TO-MEMBER', $post);



	



	



}



function generate_pin_code(){



	$code=str_split(strrev(md5(microtime())));



	$index=0;



	foreach($code as $value){



		if((int)$value>0){



			$key.=$value;



			if($index==3){



				$key.='-';



				$index=0;



			}



			$index++;



		}



	}



	$key=substr($key, 0, 14);



	if(strlen($key)<14)$key.=strrev(substr($key, 0, 14-strlen($key)));



	return $key;



}





###############################################################################



if(isset($_GET['sid']))$post['sid']=$_GET['sid'];



if(isset($_GET['bid']))$post['bid']=$_GET['bid'];



if(isset($_GET['id']))$post['gid']=$_GET['id'];



if(isset($_GET['bp']))$post['bp']=$_GET['bp'];



if(isset($_GET['cid']))$post['cid']=$_GET['cid'];



if(isset($_GET['updateid']))$post['updateid']=$_GET['updateid'];



if(isset($_GET['itemid']))$post['itemid']=$_GET['itemid'];



if(isset($_GET['type']))$post['type']=$_GET['type'];



if(isset($_GET['email']))$post['email']=$_GET['email'];



if(isset($_GET['status']))$post['status']=$_GET['status'];



if(isset($_GET['page']))$post['StartPage']=$_GET['page'];



if(isset($_GET['order']))$post['order']=$_GET['order'];



if(isset($_GET['action']))$post['action']=$_GET['action'];



if(isset($_GET['member']))$post['member']=$_GET['member'];



if(isset($_GET['product']))$post['product']=$_GET['product'];



if(isset($_GET['keyword']))$post['keyword']=$_GET['keyword'];



###############################################################################



if(isset($_GET['rid']))$post['sponsor']=$_GET['rid'];



elseif(isset($_COOKIE['rid']))$post['sponsor']=$_COOKIE['rid'];



reset($_GET);



###############################################################################



if(!session_id())session_start();



$data['sid']=session_id();



header("Cache-control: private");



###############################################################################



if($_POST)$post=get_post();



if(!$post['StartPage'])$post['StartPage']=0;



###############################################################################




###############################################################################



if(!$uid)$uid=$_SESSION['uid'];



if($uid){



	$balance=select_balance($uid);



	$post['Balance']=$balance;



	$post['Address']=$data['Addr'];



	$post['MailAddr']=get_member_email($uid);



	$post['Username']=get_member_username($uid);



	set_last_access_date($uid);



}



###############################################################################



if($data['ReferralPays']){



	if(get_member_id($post['sponsor'], '', "`active`=1")){



		$_SESSION['sponsor']=$post['sponsor'];



		setcookie('rid', $post['sponsor']);



	}elseif(!$_POST['sponsor'])unset($post['sponsor']);



}unset($_POST['sponsor']);



###############################################################################



?>