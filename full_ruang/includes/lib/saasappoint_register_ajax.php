<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscriptions.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscription_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_businesses.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_schedule.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frequently_discount.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_templates.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_subscriptions = new saasappoint_subscriptions();
$obj_subscriptions->conn = $conn;

$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;

$obj_schedule = new saasappoint_schedule();
$obj_schedule->conn = $conn;

$obj_frequently_discount = new saasappoint_frequently_discount();
$obj_frequently_discount->conn = $conn;

$obj_templates = new saasappoint_templates();
$obj_templates->conn = $conn;

/** Register admin freeplan Ajax **/
if(isset($_POST['register_admin_freeplan'])){
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
	if($plan_detail["plan_rate"] == 0){
		if($plan_detail['renewal_type'] == "monthly"){
			$date_year_month = "months";
		}else{
			$date_year_month = "years";
		}
		
		$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
		$saasappoint_server_timezone = date_default_timezone_get();
		$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
		
		$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
		$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
		
		$updated_sms_credit = $plan_detail['sms_credit'];
				
		/** add business **/
		$obj_businesses->business_type_id = $_POST['business_type_id'];
		$obj_businesses->registered_on = $subscribed_date;
		$obj_businesses->status = "Y";
		$business_id = $obj_businesses->add_business();
		
		if(is_numeric($business_id)){
			/** add admin **/
			$obj_admins->business_id = $business_id;
			$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
			$obj_admins->password = md5($_POST['password']);
			$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
			$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
			$obj_admins->phone = $_POST['phone'];
			$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
			$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
			$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
			$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
			$obj_admins->image = "";
			$obj_admins->status = "Y";
			$admin_id = $obj_admins->add_admin();
			
			if(is_numeric($admin_id)){
				/** add subscription **/
				$obj_subscriptions->business_id = $business_id;
				$obj_subscriptions->admin_id = $admin_id;
				$obj_subscriptions->plan_id = $_POST['plan_id'];
				$obj_subscriptions->transaction_id = "";
				$obj_subscriptions->subscribed_on = $subscribed_date;
				$obj_subscriptions->expired_on = $exipred_date;
				$obj_subscriptions->joined_on = $subscribed_date;
				$obj_subscriptions->renewal = $plan_detail['renewal_type'];
				$obj_subscriptions->payment_method = "free";
				$added = $obj_subscriptions->add_subscription();
				$obj_subscriptions->add_subscription_history();
			
				if($added){
					
					/** add default frequently discount **/
					$obj_frequently_discount->business_id = $business_id;
					$obj_frequently_discount->add_default_frequently_discount();
						
					/** add default schedule **/
					$obj_schedule->business_id = $business_id;
					$obj_schedule->add_default_schedule();
					
					/** add default settings **/
					$obj_settings->business_id = $business_id;
					$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
					$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
					$companyphone = $_POST['companyphone'];
					$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
					$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
					$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
					$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
					$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
					
					$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
					
					if($settings_added){
						/** add default templates **/
						$obj_templates->business_id = $business_id;
						$obj_templates->add_default_email_sms_templates();
							
						/** update SMS credit **/
						$obj_settings->business_id = $business_id;
						$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
						
						/** Add language file **/
						$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
						$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
						fputs($fh, $fileContents);
						fclose($fh);
						
						/* Set session values for logged in user */
						unset($_SESSION['customer_id']);
						unset($_SESSION['superadmin_id']);
						$_SESSION['business_id'] = $business_id;
						$_SESSION['admin_id'] = $admin_id;
						$_SESSION['login_type'] = "admin";
						
						echo "registered";
					}
				}
			}
		}
	}
}

/** Register admin Pay manually Ajax **/
if(isset($_POST['register_admin_pay_manually'])){
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
	if($plan_detail['renewal_type'] == "monthly"){
		$date_year_month = "months";
	}else{
		$date_year_month = "years";
	}
	
	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$updated_sms_credit = $plan_detail['sms_credit'];
			
	/** add business **/
	$obj_businesses->business_type_id = $_POST['business_type_id'];
	$obj_businesses->registered_on = $subscribed_date;
	$obj_businesses->status = "Y";
	$business_id = $obj_businesses->add_business();
	
	if(is_numeric($business_id)){
		/** add admin **/
		$obj_admins->business_id = $business_id;
		$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
		$obj_admins->password = md5($_POST['password']);
		$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$obj_admins->phone = $_POST['phone'];
		$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
		$obj_admins->image = "";
		$obj_admins->status = "Y";
		$admin_id = $obj_admins->add_admin();
		
		if(is_numeric($admin_id)){
			/** add subscription **/
			$obj_subscriptions->business_id = $business_id;
			$obj_subscriptions->admin_id = $admin_id;
			$obj_subscriptions->plan_id = $_POST['plan_id'];
			$obj_subscriptions->transaction_id = "";
			$obj_subscriptions->subscribed_on = $subscribed_date;
			$obj_subscriptions->expired_on = $exipred_date;
			$obj_subscriptions->joined_on = $subscribed_date;
			$obj_subscriptions->renewal = $plan_detail['renewal_type'];
			$obj_subscriptions->payment_method = "pay manually";
			$added = $obj_subscriptions->add_subscription();
			$obj_subscriptions->add_subscription_history();
		
			if($added){
				
				/** add default frequently discount **/
				$obj_frequently_discount->business_id = $business_id;
				$obj_frequently_discount->add_default_frequently_discount();
					
				/** add default schedule **/
				$obj_schedule->business_id = $business_id;
				$obj_schedule->add_default_schedule();
				
				/** add default settings **/
				$obj_settings->business_id = $business_id;
				$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
				$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
				$companyphone = $_POST['companyphone'];
				$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
				$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
				$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
				$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
				$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
				
				$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
				
				if($settings_added){
					/** add default templates **/
					$obj_templates->business_id = $business_id;
					$obj_templates->add_default_email_sms_templates();
					
					/** update SMS credit **/
					$obj_settings->business_id = $business_id;
					$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
					
					/** Add language file **/
					$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
					$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
					fputs($fh, $fileContents);
					fclose($fh);
					
					/* Set session values for logged in user */
					unset($_SESSION['customer_id']);
					unset($_SESSION['superadmin_id']);
					$_SESSION['business_id'] = $business_id;
					$_SESSION['admin_id'] = $admin_id;
					$_SESSION['login_type'] = "admin";
					
					echo "registered";
				}
			}
		}
	}
}

/** Register admin authorize.net Ajax **/
else if(isset($_POST['register_admin_authorizenet'])){
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
	if($plan_detail['renewal_type'] == "monthly"){
		$date_year_month = "months";
	}else{
		$date_year_month = "years";
	}
	
	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$updated_sms_credit = $plan_detail['sms_credit'];
	
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/authorize.net/autoload.php");
	$saasappoint_authorizenet_api_login_id = $obj_settings->get_superadmin_option('saasappoint_authorizenet_api_login_id');
	$saasappoint_authorizenet_transaction_key = $obj_settings->get_superadmin_option('saasappoint_authorizenet_transaction_key');
	$testmode = "off";

	if($testmode=='on'){   
		define('AUTHORIZENET_SANDBOX',true); 
	}else{  
		define('AUTHORIZENET_SANDBOX',false);
	}

	$payment_response = null;
	$net_total = $plan_detail["plan_rate"];
	define('AUTHORIZENET_API_LOGIN_ID',$saasappoint_authorizenet_api_login_id);
	define('AUTHORIZENET_TRANSACTION_KEY',$saasappoint_authorizenet_transaction_key);
	$expirydate = $_POST['cardexmonth'].'/'.$_POST['cardexyear'];
	$aim_sale = new AuthorizeNetAIM();
	$aim_sale->amount     = $net_total;
	$aim_sale->card_num   = $_POST['cardnumber'];
	$aim_sale->card_code  = $_POST['cardcvv'];
	$aim_sale->exp_date   = $expirydate;
	$aim_sale->first_name = ucwords($_POST['firstname'].' '.$_POST['lastname']);
	$aim_sale->email      = $_POST['email'];
	$aim_sale->phone      = $_POST['phone'];
	$payment_response = $aim_sale->authorizeAndCapture();
	if ( $payment_response->approved ) {				
		$transaction_id = $payment_response->transaction_id;
		
		/** add business **/
		$obj_businesses->business_type_id = $_POST['business_type_id'];
		$obj_businesses->registered_on = $subscribed_date;
		$obj_businesses->status = "Y";
		$business_id = $obj_businesses->add_business();
		
		if(is_numeric($business_id)){
			/** add admin **/
			$obj_admins->business_id = $business_id;
			$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
			$obj_admins->password = md5($_POST['password']);
			$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
			$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
			$obj_admins->phone = $_POST['phone'];
			$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
			$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
			$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
			$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
			$obj_admins->image = "";
			$obj_admins->status = "Y";
			$admin_id = $obj_admins->add_admin();
			
			if(is_numeric($admin_id)){
				/** add subscription **/
				$obj_subscriptions->business_id = $business_id;
				$obj_subscriptions->admin_id = $admin_id;
				$obj_subscriptions->plan_id = $_POST['plan_id'];
				$obj_subscriptions->transaction_id = $transaction_id;
				$obj_subscriptions->subscribed_on = $subscribed_date;
				$obj_subscriptions->expired_on = $exipred_date;
				$obj_subscriptions->joined_on = $subscribed_date;
				$obj_subscriptions->renewal = $plan_detail['renewal_type'];
				$obj_subscriptions->payment_method = "authorize.net";
				$added = $obj_subscriptions->add_subscription();
				$obj_subscriptions->add_subscription_history();
			
				if($added){
					
					/** add default frequently discount **/
					$obj_frequently_discount->business_id = $business_id;
					$obj_frequently_discount->add_default_frequently_discount();
						
					/** add default schedule **/
					$obj_schedule->business_id = $business_id;
					$obj_schedule->add_default_schedule();
					
					/** add default settings **/
					$obj_settings->business_id = $business_id;
					$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
					$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
					$companyphone = $_POST['companyphone'];
					$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
					$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
					$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
					$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
					$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
					
					$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
					
					if($settings_added){
						/** add default templates **/
						$obj_templates->business_id = $business_id;
						$obj_templates->add_default_email_sms_templates();
						
						/** update SMS credit **/
						$obj_settings->business_id = $business_id;
						$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);

						/** Add language file **/
						$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
						$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
						fputs($fh, $fileContents);
						fclose($fh);
						
						/* Set session values for logged in user */
						unset($_SESSION['customer_id']);
						unset($_SESSION['superadmin_id']);
						$_SESSION['business_id'] = $business_id;
						$_SESSION['admin_id'] = $admin_id;
						$_SESSION['login_type'] = "admin";
						
						echo "registered";
					}
				}
			}
		}
	} else {
		echo $payment_response->error_message;
		exit;
	}
}

/** Register admin 2checkout Ajax **/
else if(isset($_POST['register_admin_twocheckout'])){
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
	if($plan_detail['renewal_type'] == "monthly"){
		$date_year_month = "months";
	}else{
		$date_year_month = "years";
	}
	
	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$updated_sms_credit = $plan_detail['sms_credit'];
	
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/twocheckout/Twocheckout.php");
	$saasappoint_twocheckout_private_key = $obj_settings->get_superadmin_option("saasappoint_twocheckout_private_key");
	$saasappoint_twocheckout_seller_id = $obj_settings->get_superadmin_option("saasappoint_twocheckout_seller_id");
	$twocc_sandbox_mode = "N";
	if($twocc_sandbox_mode == 'Y'){
		$twocc_sandbox = true;
	}else{
		$twocc_sandbox = false;
	}
	Twocheckout::privateKey($saasappoint_twocheckout_private_key); 
	Twocheckout::sellerId($saasappoint_twocheckout_seller_id); 
	Twocheckout::sandbox($twocc_sandbox);
	/* Twocheckout::verifySSL(false); */

	$net_total = $plan_detail['plan_rate'];
	$order_id = mt_rand();
	$saasappoint_currency = $obj_settings->get_superadmin_option('saasappoint_currency');
	$saasappoint_company_country = $_POST["country"];

	try {
		$charge = Twocheckout_Charge::auth(array(
			"merchantOrderId" => $order_id,
			"token"      => $_POST['token'],
			"currency"   => $saasappoint_currency,
			"total"      => $net_total,
			"billingAddr" => array(
				"name" => ucwords($_POST['firstname'].' '.$_POST['lastname']),
				"addrLine1" => $_POST['address'],
				"city" => $_POST['city'],
				"state" => $_POST['state'],
				"zipCode" => $_POST['zip'],
				"country" => $saasappoint_company_country,
				"email" => $_POST['email'],
				"phoneNumber" => $_POST['phone']
			)
		));
		
		if ($charge['response']['responseCode'] == 'APPROVED') {
			$transaction_id = $charge['response']['transactionId'];
			/** add business **/
			$obj_businesses->business_type_id = $_POST['business_type_id'];
			$obj_businesses->registered_on = $subscribed_date;
			$obj_businesses->status = "Y";
			$business_id = $obj_businesses->add_business();
			
			if(is_numeric($business_id)){
				/** add admin **/
				$obj_admins->business_id = $business_id;
				$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
				$obj_admins->password = md5($_POST['password']);
				$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
				$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
				$obj_admins->phone = $_POST['phone'];
				$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
				$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
				$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
				$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
				$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
				$obj_admins->image = "";
				$obj_admins->status = "Y";
				$admin_id = $obj_admins->add_admin();
				
				if(is_numeric($admin_id)){
					/** add subscription **/
					$obj_subscriptions->business_id = $business_id;
					$obj_subscriptions->admin_id = $admin_id;
					$obj_subscriptions->plan_id = $_POST['plan_id'];
					$obj_subscriptions->transaction_id = $transaction_id;
					$obj_subscriptions->subscribed_on = $subscribed_date;
					$obj_subscriptions->expired_on = $exipred_date;
					$obj_subscriptions->joined_on = $subscribed_date;
					$obj_subscriptions->renewal = $plan_detail['renewal_type'];
					$obj_subscriptions->payment_method = "2checkout";
					$added = $obj_subscriptions->add_subscription();
					$obj_subscriptions->add_subscription_history();
				
					if($added){
						
						/** add default frequently discount **/
						$obj_frequently_discount->business_id = $business_id;
						$obj_frequently_discount->add_default_frequently_discount();
							
						/** add default schedule **/
						$obj_schedule->business_id = $business_id;
						$obj_schedule->add_default_schedule();
						
						/** add default settings **/
						$obj_settings->business_id = $business_id;
						$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
						$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
						$companyphone = $_POST['companyphone'];
						$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
						$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
						$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
						$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
						$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
						
						$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
						
						if($settings_added){
							/** add default templates **/
							$obj_templates->business_id = $business_id;
							$obj_templates->add_default_email_sms_templates();
							
							/** update SMS credit **/
							$obj_settings->business_id = $business_id;
							$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
							
							/** Add language file **/
							$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
							$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
							fputs($fh, $fileContents);
							fclose($fh);
							
							/* Set session values for logged in user */
							unset($_SESSION['customer_id']);
							unset($_SESSION['superadmin_id']);
							$_SESSION['business_id'] = $business_id;
							$_SESSION['admin_id'] = $admin_id;
							$_SESSION['login_type'] = "admin";
							
							echo "registered";
						}
					}
				}
			}
		}
	} catch (Twocheckout_Error $e) {
		echo $e->getMessage();
		exit;
	}
}

/** Register admin stripe Ajax **/
else if(isset($_POST['register_admin_stripe'])){
	if(isset($_POST['token'])){
		include(dirname(dirname(dirname(__FILE__)))."/includes/payments/stripe/init.php");
		$obj_subscription_plans->id = $_POST['plan_id'];
		$plan_detail = $obj_subscription_plans->readone_subscription_plan();
		
		$skey = $obj_settings->get_superadmin_option('saasappoint_stripe_secretkey');
		$currency = $obj_settings->get_superadmin_option('saasappoint_currency');
		$token = $_POST['token'];
		
		if($plan_detail['renewal_type'] == "monthly"){
			$date_year_month = "months";
			$year_month = "Month";
		}else{
			$date_year_month = "years";
			$year_month = "Year";
		}
		if($plan_detail['plan_period'] > 1){ 
			$plan_period_detail = $plan_detail['plan_period']." ".$year_month."s"; 
		}else{ 
			$plan_period_detail = $plan_detail['plan_period']." ".$year_month; 
		}
		
		$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
		$saasappoint_server_timezone = date_default_timezone_get();
		$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
		
		$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
		$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
		
		$description = ucwords($_POST['firstname']." ".$_POST['lastname'])." (".$_POST['phone'].") subscribed for subscription plan: ".ucwords($plan_detail['plan_name'])." - [".$plan_period_detail."]";
		$admin_email = $_POST['email'];
		
		$updated_sms_credit = $plan_detail['sms_credit'];
		
		try {
			\Stripe\Stripe::setApiKey($skey);
			$charge = \Stripe\Charge::create( array(
				'amount' => round($plan_detail["plan_rate"]*100),
				'currency' => $currency,
				'description' => $description,
				'source' => $token,
				'receipt_email' => $admin_email
			));
			
			/** add business **/
			$obj_businesses->business_type_id = $_POST['business_type_id'];
			$obj_businesses->registered_on = $subscribed_date;
			$obj_businesses->status = "Y";
			$business_id = $obj_businesses->add_business();
			
			if(is_numeric($business_id)){
				/** add admin **/
				$obj_admins->business_id = $business_id;
				$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
				$obj_admins->password = md5($_POST['password']);
				$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
				$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
				$obj_admins->phone = $_POST['phone'];
				$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
				$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
				$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
				$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
				$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
				$obj_admins->image = "";
				$obj_admins->status = "Y";
				$admin_id = $obj_admins->add_admin();
				
				if(is_numeric($admin_id)){
					/** add subscription **/
					$obj_subscriptions->business_id = $business_id;
					$obj_subscriptions->admin_id = $admin_id;
					$obj_subscriptions->plan_id = $_POST['plan_id'];
					$obj_subscriptions->transaction_id = $charge->id;
					$obj_subscriptions->subscribed_on = $subscribed_date;
					$obj_subscriptions->expired_on = $exipred_date;
					$obj_subscriptions->joined_on = $subscribed_date;
					$obj_subscriptions->renewal = $plan_detail['renewal_type'];
					$obj_subscriptions->payment_method = "stripe";
					$added = $obj_subscriptions->add_subscription();
					$obj_subscriptions->add_subscription_history();
				
					if($added){
						
						/** add default frequently discount **/
						$obj_frequently_discount->business_id = $business_id;
						$obj_frequently_discount->add_default_frequently_discount();
												
						/** add default schedule **/
						$obj_schedule->business_id = $business_id;
						$obj_schedule->add_default_schedule();
						
						/** add default settings **/
						$obj_settings->business_id = $business_id;
						$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
						$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
						$companyphone = $_POST['companyphone'];
						$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
						$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
						$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
						$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
						$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
												
						$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
						
						if($settings_added){
							/** add default templates **/
							$obj_templates->business_id = $business_id;
							$obj_templates->add_default_email_sms_templates();
							
							/** update SMS credit **/
							$obj_settings->business_id = $business_id;
							$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
							
							/** Add language file **/
							$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
							$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
							fputs($fh, $fileContents);
							fclose($fh);
							
							/* Set session values for logged in user */
							unset($_SESSION['customer_id']);
							unset($_SESSION['superadmin_id']);
							$_SESSION['business_id'] = $business_id;
							$_SESSION['admin_id'] = $admin_id;
							$_SESSION['login_type'] = "admin";
							
							echo "registered";
						}
					}
				}
			}
		}
		catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;die;
		}
	}
}