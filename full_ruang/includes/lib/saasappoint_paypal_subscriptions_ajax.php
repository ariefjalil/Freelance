<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscriptions.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscription_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");
include(dirname(dirname(dirname(__FILE__)))."/includes/payments/paypal/saasappoint_paypal_express_checkout.php");

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
if(isset($_POST['upgrade_subscription_paypal'])){
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
	

	$_SESSION["saasappoint_ppsplan_detail"] = array();
	$_SESSION["saasappoint_ppsplan_detail"]["plan_id"] = $_POST["plan_id"];
	$_SESSION["saasappoint_ppsplan_detail"]["updated_sms_credit"] = $updated_sms_credit;
	$_SESSION["saasappoint_ppsplan_detail"]["description"] = $description;
	$_SESSION["saasappoint_ppsplan_detail"]["subscribed_date"] = $subscribed_date;
	$_SESSION["saasappoint_ppsplan_detail"]["exipred_date"] = $exipred_date;
	$_SESSION["saasappoint_ppsplan_detail"]["renewal_type"] = $plan_detail["renewal_type"];
	$_SESSION["saasappoint_ppsplan_detail"]["plan_name"] = $plan_detail["plan_name"];
	$_SESSION["saasappoint_ppsplan_detail"]["plan_rate"] = $plan_detail["plan_rate"];
}



$saasappoint_paypal_guest_payment = $obj_settings->get_superadmin_option('saasappoint_paypal_guest_payment');
$saasappoint_paypal_api_username = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_api_username'));
$saasappoint_paypal_api_password = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_api_password'));
$saasappoint_paypal_signature = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_signature'));
$saasappoint_currency = $obj_settings->get_superadmin_option('saasappoint_currency');
$saasappoint_company_logo = "";

$paypaltestmode = "off";

$version = urlencode('109.0');
$paypal_return_url = urlencode(SITE_URL.'includes/lib/saasappoint_paypal_subscriptions_ajax.php');
$paypal_cancel_url = urlencode(SITE_URL);
$currency_code = $saasappoint_currency;
$payment_action = urlencode("SALE");
$locale_code = 'US';

$company_logo = $saasappoint_company_logo;

if($company_logo!='') {	
	$site_logo = SITE_URL."assets/images/".$saasappoint_company_logo;
}else{
	$site_logo='';
}
$border_color = '343a40';
$allow_note = 1;
$obj_saasappoint_paypal = new saasappoint_paypal();

if($paypaltestmode=='off'){
	$obj_saasappoint_paypal->mode = '';  /* leave empty for 'Live' mode */
}else{
	$obj_saasappoint_paypal->mode = 'SANDBOX'; 
}
	

/*set basic name and value pairs for curl post*/
$basic_NVP = array(
				'VERSION'=>$version,
				'USER'=>$saasappoint_paypal_api_username,
				'PWD'=>$saasappoint_paypal_api_password,
				'SIGNATURE'=>$saasappoint_paypal_signature,
				'RETURNURL'=>$paypal_return_url,
				'CANCELURL'=>$paypal_cancel_url,
				'PAYMENTREQUEST_0_CURRENCYCODE'=>$currency_code,
				'NOSHIPPING'=>1,
				'PAYMENTREQUEST_0_PAYMENTACTION'=>$payment_action,
				'LOCALECODE'=>$locale_code,
				'CARTBORDERCOLOR'=>$border_color,
				'LOGOIMG'=>$site_logo,
				'ALLOWNOTE'=>1
			);  
if($saasappoint_paypal_guest_payment=='on'){
	$basic_NVP['SOLUTIONTYPE']='Sole';
	$basic_NVP['LANDINGPAGE']='Billing';
}

foreach($basic_NVP as $key => $value) {
  $obj_saasappoint_paypal->pv .= "&$key=$value";
}

$cart_item_counter=0;	
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_NAME$cart_item_counter=".$_SESSION["saasappoint_ppsplan_detail"]["plan_name"];
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_DESC$cart_item_counter=".$_SESSION["saasappoint_ppsplan_detail"]["description"];		
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_AMT$cart_item_counter=".$_SESSION["saasappoint_ppsplan_detail"]["plan_rate"];		
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_QTY$cart_item_counter=1";			

$cart_item_counter++;

$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_ITEMAMT=".$_SESSION["saasappoint_ppsplan_detail"]["plan_rate"];
$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_TAXAMT=0";
$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_AMT=".$_SESSION["saasappoint_ppsplan_detail"]["plan_rate"];

$obj_saasappoint_paypal->pp_method_name = 'SetExpressCheckout';  /*method name using for API call*/
$resultarray = array();


if(!isset($_GET["token"])) {
	$response_array = $obj_saasappoint_paypal->paypal_nvp_api_call();
	/*Respond according to message we receive from Paypal*/
	if("SUCCESS" == strtoupper($response_array["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response_array["ACK"]))
	{
			if(strtoupper($obj_saasappoint_paypal->mode)=='SANDBOX') {
			  $obj_saasappoint_paypal->mode = '.sandbox';
			}
			/*Redirect user to PayPal store with Token received.*/
			$paypal_url ='https://www'.$obj_saasappoint_paypal->mode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$response_array["TOKEN"].'';	
			
			$resultarray['status']='success';
			$resultarray['value']=$paypal_url;
			echo json_encode($resultarray);die();
			
					 
	}else{
		$resultarray['status']='error';
		$resultarray['value']=urldecode($response_array["L_LONGMESSAGE0"]);
		echo json_encode($resultarray);die();			
	}
}	

if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{
	$token = $_GET["token"];
	$payer_id = $_GET["PayerID"];	
	$obj_saasappoint_paypal->pv .= "&TOKEN=".urlencode($token)."&PAYERID=".urlencode($payer_id);
	$obj_saasappoint_paypal->pp_method_name = 'DoExpressCheckoutPayment';  /*method name using for API call*/
	$payment_response_array = $obj_saasappoint_paypal->paypal_nvp_api_call(); 
	if("SUCCESS" == strtoupper($payment_response_array["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($payment_response_array["ACK"])){ 
		
	    $transaction_id = urldecode($payment_response_array["PAYMENTINFO_0_TRANSACTIONID"]);			
		$obj_subscriptions->plan_id = $_SESSION["saasappoint_ppsplan_detail"]['plan_id'];
		$obj_subscriptions->transaction_id = $transaction_id;
		$obj_subscriptions->subscribed_on = $_SESSION["saasappoint_ppsplan_detail"]['subscribed_date'];
		$obj_subscriptions->expired_on = $_SESSION["saasappoint_ppsplan_detail"]['exipred_date'];
		$obj_subscriptions->renewal = $_SESSION["saasappoint_ppsplan_detail"]['renewal_type'];
		$obj_subscriptions->payment_method = "paypal";
		$upgraded = $obj_subscriptions->upgrade_subscription();
		
		$obj_subscriptions->admin_id = $_SESSION['admin_id'];
		$obj_subscriptions->add_subscription_history();
		
		if($upgraded){
			unset($_SESSION["saasappoint_ppsplan_detail"]);
			$obj_settings->update_option("saasappoint_sms_credit", $_SESSION["saasappoint_ppsplan_detail"]['updated_sms_credit']);
			header('location:'.SITE_URL.'backend/subscription.php');
		}
		
	}
}