<?php
class signup implements IController{
	public function main() {
		$view = new View();
		$fc = FC::getInstance();
		FC::loadClass("Db");
		$db = new Db();
		echo "Under construction"; exit();
		$session = FC::getClassInstance("Session");
        $view->acc_typ = 3;
		$users = FC::getClassInstance("Users");
		$params = $fc->getParams();
		global $data;
		$payment = FC::getClass("Payment");
		$fc->css_files = array(SITEURL."styles/dropzone.css");
		$fc->js_files = array(SITEURL."scripts/dropzone.js");
		$view->acc_typ = $acc_typ = 3;
		if(Tools::isSubmit("acc_typ"))
			$view->acc_typ = $acc_typ = Tools::getValue("acc_typ");
		if ( isset($_POST['submit'])) {
			$_SESSION['email'] = $email = Tools::getValue('email') ;
			$first_name = Tools::getValue('first_name');
			$last_name = Tools::getValue('last_name');
            $b_name = Tools::getValue('b_name');
			$national_id = Tools::getValue('national_id');
			//$google_id = Tools::getValue('google_id');
			$profile_id = Tools::getValue('profile_id');
			$about_me = Tools::getValue('about_me');
			$website = Tools::getValue("website");
            $ad_pwd = $_POST['password'];
			$max_id = $db->getValue("SELECT MAX(id) as id_user FROM user_pro");
			$username = "BTTEU11".$max_id;
			$gump = FC::getClass("Gump");
			$gump->validation_rules(array(
			    'first_name'  	=> 'required',
			    'last_name'	  	=> 'required',
			    'email'       	=> 'required|valid_email',
                'address'  		=> 'required',
                'city'  		=> 'required',
                'state'  		=> 'required',
                'country'  		=> 'required',
                'national_id'	=> 'required',
				'website'		=> 'required',
				'id_front'		=> 'required',
				'processing_history'		=> 'required',
				'company_registration'			=> 'required',
				'utility_bill'	=> 'required'
            ));
            $validated_data = $gump->run($_REQUEST);
			if($validated_data === false) {
			    $fc->error = $gump->get_readable_errors();
			}
			
			if($db->getValue("SELECT COUNT(`id`) FROM `user_pro` WHERE `ad_email` = '$email'") >  0 ){
				$fc->error[] = "This email already exits try another";
			}
			if(empty($fc->error)){
                        $files = ['id_front','id_back','company_registration','cl_img'];
                        if($acc_typ != 2 && $acc_typ != 3){
                            echo "Invalid Account"; exit();
                        }
            $vcode = hash("md5", time());
            $country = Tools::getValue("country");
			$currency = $payment->getCountryCurrency($country);
			$symbol = $payment->getSymbol($currency);
			$id_client = $db->insert( array( "user_pro" => array(
				"first_name"    	=> $first_name,
				"last_name"     	=> $last_name,
				"nic_no"     		=> $national_id,
				"ad_user"			=> $username,
				"ad_email"      	=> $email,
				"ad_pwd"      		=> hashing($ad_pwd),
				"profile_picture"	=> Tools::getValue("cl_img"),
				"status"			=> "0",
				"acc_typ"     		=> $acc_typ,
				"c_reg_no"          => Tools::getValue("c_reg_no"),
				"phone"             => Tools::getValue("phone1") . Tools::getValue("phone2"),
				"address"           => Tools::getValue("address"),
				"city"              => strtolower(Tools::getValue("city")),
				"state"             => strtolower(Tools::getValue("state")),
				"country"                   => strtolower(Tools::getValue("country")),
				"currency"                  => $currency,
				"curr"                      => $symbol,
				"zipcode"                   => Tools::getValue("zipcode"),
				"website"					=> $website,
				"id_front"              	=> Tools::getValue("id_front"),
				"processing_history"        => Tools::getValue("processing_history"),
				"utility_bill"              => Tools::getValue("utility_bill"),
				"c_ssm"                 	=> Tools::getValue("company_registration"),
				"vcode"						=> $vcode,
				"b_name"                    => $b_name,
				"transactions"				=> Tools::getValue("transactions"),
				"business_description"      => Tools::getValue('business_description'),
				"ip_address"				=> $_SERVER['REMOTE_ADDR']
			)));
			$name = Tools::safePrint($first_name . " " . $last_name) ;
			$mails = FC::getClassInstance("Mail");
			$mails->to = Tools::getValue('email');
			$mails->subject = "Welcome to ".SITE_TITLE;
			$mails->message = "Hello ".$name.",<br>Thank you for registering with BttPay.com . You are currently in a test mode account, your account will be activated if no further documents is required by our compliance team<br>";
			$mails->message .= "<br>Do make sure to update your national ID and company incorporation properly to avoid any delay in the approval process.<br>";
			$mails->message .= "<br>Your login details are: <br>Merchant ID: $username <br>Password: $ad_pwd <br>
			<br>Below are some details for you to look into.<br><table style='font-size:12px'>
				<tr><td>Pricing:</td><td>- 3.8% + USD 2.00 per successful transaction</td></tr>
				<tr><td>Payout minimum:</td><td>- $2500</td></tr>
				<tr><td>Chargeback %:</td><td>- 0.3% from total transactions per month</td></tr>
			</table><br>
			You can also go through below for some of our useful links.<br>
FAQ 					- https://www.bttpay.com/faq/ <br>
Terms of use 			- https://www.bttpay.com/terms-and-conditions/<br>
API Docs 				- https://www.bttpay.com/api-documentation/<br>
<br>

			To complete your sign up please <a href='".SITEURL."emailverification/?id_user=".$id_client."&vcode=".$vcode."'>Click Here</a> to verify your email.
                                <br><br>Regards,<br>".SITE_TITLE;
				if($mails->sendMail()){
					//echo "Your message has been mailed to ".$mails->to;
				}
				$mails->to = ADMINEMAILID;
				$mails->subject = "New Signup Bttpay.com";
				$mails->message = "New member has just created an account. <br>Name: $name<br><a href='".SITEURL."members'>Click Here</a> to view members";
				$mails->sendMail();
				$fc->success = "Account created successfully";
				Tools::redirect( SITEURL."thankyou" );
			}
		}
		
		if ( isset($_POST['log_in'] ) ) {
			if ( isset( $_GET['back'] ) ) {
				FC::getClassInstance("ObjectPhp")->redirect( SITEURL."login/?back=interprofile&id=".$_GET['id'] );
			}
			else {
				FC::getClassInstance("ObjectPhp")->redirect( SITEURL."login" );
			}
		}
		
		if(!file_exists('../views/signup.php')){
		    $fc->_controllerFile = "404page";
		}
		$result = $view->render('../views/signup.php');
		$fc->setBody($result);
		
	}
}
?>