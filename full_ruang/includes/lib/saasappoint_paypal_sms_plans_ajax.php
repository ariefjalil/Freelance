<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_sms_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_sms_subscriptions_history.php");
include(dirname(dirname(dirname(__FILE__)))."/includes/payments/paypal/saasappoint_paypal_express_checkout.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_sms_plans = new saasappoint_sms_plans();
$obj_sms_plans->conn = $conn;

$obj_sms_subscriptions_history = new saasappoint_sms_subscriptions_history();
$obj_sms_subscriptions_history->conn = $conn;
$obj_sms_subscriptions_history->business_id = $_SESSION['business_id'];

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;
$obj_admins->business_id = $_SESSION['business_id'];
$obj_admins->id = $_SESSION['admin_id'];

$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');

/* Upgrade SMS Plan ajax */
if(isset($_POST['upgrade_sms_plan_paypal'])){
	$obj_sms_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan();
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['credit'];

	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	$extended_on = date("Y-m-d H:i:s", $currDateTime_withTZ);
	
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
	
	$description = ucwords($saasappoint_company_name)." (".$saasappoint_company_phone.") subscribed for SMS plan: ".ucwords($plan_detail['plan_name'])." [Credit: ".$plan_detail['credit']."]";
	$admin_email = $saasappoint_company_email;
	
	
	$_SESSION["saasappoint_ppsmsplan_detail"] = array();
	$_SESSION["saasappoint_ppsmsplan_detail"]["plan_id"] = $_POST["plan_id"];
	$_SESSION["saasappoint_ppsmsplan_detail"]["updated_sms_credit"] = $updated_sms_credit;
	$_SESSION["saasappoint_ppsmsplan_detail"]["description"] = $description;
	$_SESSION["saasappoint_ppsmsplan_detail"]["extended_on"] = $extended_on;
	$_SESSION["saasappoint_ppsmsplan_detail"]["plan_rate"] = $plan_detail["plan_rate"];
	$_SESSION["saasappoint_ppsmsplan_detail"]["plan_name"] = $plan_detail["plan_name"];
	$_SESSION["saasappoint_ppsmsplan_detail"]["credit"] = $plan_detail["credit"];
}




$saasappoint_paypal_guest_payment = $obj_settings->get_superadmin_option('saasappoint_paypal_guest_payment');
$saasappoint_paypal_api_username = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_api_username'));
$saasappoint_paypal_api_password = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_api_password'));
$saasappoint_paypal_signature = urlencode($obj_settings->get_superadmin_option('saasappoint_paypal_signature'));
$saasappoint_currency = $obj_settings->get_superadmin_option('saasappoint_currency');
$saasappoint_company_logo = "";

$paypaltestmode = "off";

$version = urlencode('109.0');
$paypal_return_url = urlencode(SITE_URL.'includes/lib/saasappoint_paypal_sms_plans_ajax.php');
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
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_NAME$cart_item_counter=".$_SESSION["saasappoint_ppsmsplan_detail"]["plan_name"];
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_DESC$cart_item_counter=".$_SESSION["saasappoint_ppsmsplan_detail"]["description"];		
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_AMT$cart_item_counter=".$_SESSION["saasappoint_ppsmsplan_detail"]["plan_rate"];		
$obj_saasappoint_paypal->pv .= "&L_PAYMENTREQUEST_0_QTY$cart_item_counter=1";			

$cart_item_counter++;

$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_ITEMAMT=".$_SESSION["saasappoint_ppsmsplan_detail"]["plan_rate"];
$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_TAXAMT=0";
$obj_saasappoint_paypal->pv .= "&PAYMENTREQUEST_0_AMT=".$_SESSION["saasappoint_ppsmsplan_detail"]["plan_rate"];

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
		$obj_sms_subscriptions_history->plan_id = $_SESSION["saasappoint_ppsmsplan_detail"]["plan_id"];
		$obj_sms_subscriptions_history->amount = $_SESSION["saasappoint_ppsmsplan_detail"]['plan_rate'];
		$obj_sms_subscriptions_history->credit = $_SESSION["saasappoint_ppsmsplan_detail"]['credit'];
		$obj_sms_subscriptions_history->transaction_id = $transaction_id;
		$obj_sms_subscriptions_history->payment_method = "paypal";
		$obj_sms_subscriptions_history->extended_on = $_SESSION["saasappoint_ppsmsplan_detail"]['extended_on'];
		$obj_sms_subscriptions_history->add_sms_subscription_history();
		$obj_settings->update_option("saasappoint_sms_credit", $_SESSION["saasappoint_ppsmsplan_detail"]['updated_sms_credit']);
						
		/* Set session values for logged in user */
		unset($_SESSION["saasappoint_ppsmsplan_detail"]);
		header('location:'.SITE_URL.'backend/settings.php');
	}
}