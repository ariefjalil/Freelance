<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscriptions.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscription_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;
$obj_admins->business_id = $_SESSION['business_id'];
$obj_admins->id = $_SESSION['admin_id'];

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_subscriptions = new saasappoint_subscriptions();
$obj_subscriptions->conn = $conn;
$obj_subscriptions->business_id = $_SESSION['business_id'];
$obj_subscriptions->admin_id = $_SESSION['admin_id'];

/** Upgrade subscription Ajax **/
if(isset($_POST['upgrade_subscription_stripe'])){
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/stripe/init.php");
	$admin_detail = $obj_admins->readone_profile();
	$subscription_detail = $obj_subscriptions->readone_subscription();
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
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$description = ucwords($admin_detail['firstname']." ".$admin_detail['lastname'])." (".$admin_detail['phone'].") subscribed for subscription plan: ".ucwords($plan_detail['plan_name'])." - [".$plan_period_detail."]";
	$admin_email = $admin_detail['email'];
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['sms_credit'];
	
	try {
		\Stripe\Stripe::setApiKey($skey);
		$charge = \Stripe\Charge::create(array(
			'amount' => round($plan_detail["plan_rate"]*100),
			'currency' => $currency,
			'description' => $description,
			'source' => $token,
			'receipt_email' => $admin_email
		));
		
		$obj_subscriptions->plan_id = $_POST['plan_id'];
		$obj_subscriptions->transaction_id = $charge->id;
		$obj_subscriptions->subscribed_on = $subscribed_date;
		$obj_subscriptions->expired_on = $exipred_date;
		$obj_subscriptions->renewal = $plan_detail['renewal_type'];
		$obj_subscriptions->payment_method = "stripe";
		$upgraded = $obj_subscriptions->upgrade_subscription();
		
		$obj_subscriptions->admin_id = $_SESSION['admin_id'];
		$obj_subscriptions->add_subscription_history();
		
		if($upgraded){
			$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
			echo "upgraded";
		}
	}
	catch (Exception $e) {
		$error = $e->getMessage();
		echo $error;die;
	}
}

else if(isset($_POST['upgrade_subscription_pay_manually'])){
	
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
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
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['sms_credit'];
	
		
	$obj_subscriptions->plan_id = $_POST['plan_id'];
	$obj_subscriptions->transaction_id = "";
	$obj_subscriptions->subscribed_on = $subscribed_date;
	$obj_subscriptions->expired_on = $exipred_date;
	$obj_subscriptions->renewal = $plan_detail['renewal_type'];
	$obj_subscriptions->payment_method = "pay manually";
	$upgraded = $obj_subscriptions->upgrade_subscription();
	
	$obj_subscriptions->admin_id = $_SESSION['admin_id'];
	$obj_subscriptions->add_subscription_history();
	
	if($upgraded){
		$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
		echo "upgraded";
	}
}

/* Upgrade ajax */
else if(isset($_POST['upgrade_subscription_twocheckout'])){
	$admin_detail = $obj_admins->readone_profile();
	$subscription_detail = $obj_subscriptions->readone_subscription();
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
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
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$description = ucwords($admin_detail['firstname']." ".$admin_detail['lastname'])." (".$admin_detail['phone'].") subscribed for subscription plan: ".ucwords($plan_detail['plan_name'])." - [".$plan_period_detail."]";
	$admin_email = $admin_detail['email'];
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['sms_credit'];
			
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/twocheckout/Twocheckout.php");
	$saasappoint_twocheckout_private_key = $obj_settings->get_superadmin_option("saasappoint_twocheckout_private_key");
	$saasappoint_twocheckout_seller_id = $obj_settings->get_superadmin_option("saasappoint_twocheckout_seller_id");
	$twocc_sandbox_mode = "Y";
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
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
	$saasappoint_company_address = $obj_settings->get_option('saasappoint_company_address');
	$saasappoint_company_city = $obj_settings->get_option('saasappoint_company_city');
	$saasappoint_company_state = $obj_settings->get_option('saasappoint_company_state');
	$saasappoint_company_zip = $obj_settings->get_option('saasappoint_company_zip');
	$saasappoint_company_country = $obj_settings->get_option('saasappoint_company_country');

	try {
		$charge = Twocheckout_Charge::auth(array(
			"merchantOrderId" => $order_id,
			"token"      => $_POST['token'],
			"currency"   => $saasappoint_currency,
			"total"      => $net_total,
			"billingAddr" => array(
				"name" => ucwords($saasappoint_company_name),
				"addrLine1" => $saasappoint_company_address,
				"city" => $saasappoint_company_city,
				"state" => $saasappoint_company_state,
				"zipCode" => $saasappoint_company_zip,
				"country" => $saasappoint_company_country,
				"email" => $saasappoint_company_email,
				"phoneNumber" => $saasappoint_company_phone
			)
		));
		
		if ($charge['response']['responseCode'] == 'APPROVED') {
			$transaction_id = $charge['response']['transactionId'];
			$obj_subscriptions->plan_id = $_POST['plan_id'];
			$obj_subscriptions->transaction_id = $transaction_id;
			$obj_subscriptions->subscribed_on = $subscribed_date;
			$obj_subscriptions->expired_on = $exipred_date;
			$obj_subscriptions->renewal = $plan_detail['renewal_type'];
			$obj_subscriptions->payment_method = "2checkout";
			$upgraded = $obj_subscriptions->upgrade_subscription();
			
			$obj_subscriptions->admin_id = $_SESSION['admin_id'];
			$obj_subscriptions->add_subscription_history();
			
			if($upgraded){
				$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
				echo "upgraded";
			}
		}
	}
	catch (Exception $e) {
		$error = $e->getMessage();
		echo $error;die;
	}
}

/* Upgrade ajax */
else if(isset($_POST['upgrade_subscription_authorizenet'])){
	$admin_detail = $obj_admins->readone_profile();
	$subscription_detail = $obj_subscriptions->readone_subscription();
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
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
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$description = ucwords($admin_detail['firstname']." ".$admin_detail['lastname'])." (".$admin_detail['phone'].") subscribed for subscription plan: ".ucwords($plan_detail['plan_name'])." - [".$plan_period_detail."]";
	$admin_email = $admin_detail['email'];
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['sms_credit'];

	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/authorize.net/autoload.php");
	$saasappoint_authorizenet_api_login_id = $obj_settings->get_superadmin_option('saasappoint_authorizenet_api_login_id');
	$saasappoint_authorizenet_transaction_key = $obj_settings->get_superadmin_option('saasappoint_authorizenet_transaction_key');
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
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
	$aim_sale->first_name = ucwords($saasappoint_company_name);
	$aim_sale->email      = $saasappoint_company_email;
	$aim_sale->phone      = $saasappoint_company_phone;
	$payment_response = $aim_sale->authorizeAndCapture();
	
	if ( $payment_response->approved ) {				
		$transaction_id = $payment_response->transaction_id;
		$obj_subscriptions->plan_id = $_POST['plan_id'];
		$obj_subscriptions->transaction_id = $transaction_id;
		$obj_subscriptions->subscribed_on = $subscribed_date;
		$obj_subscriptions->expired_on = $exipred_date;
		$obj_subscriptions->renewal = $plan_detail['renewal_type'];
		$obj_subscriptions->payment_method = "authorize.net";
		$upgraded = $obj_subscriptions->upgrade_subscription();
		
		$obj_subscriptions->admin_id = $_SESSION['admin_id'];
		$obj_subscriptions->add_subscription_history();
		
		if($upgraded){
			$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
			echo "upgraded";
		}
	}else {
		echo $payment_response->error_message;
		exit;
	}
}