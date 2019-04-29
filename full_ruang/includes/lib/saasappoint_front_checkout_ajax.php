<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_frontend = new saasappoint_frontend();
$obj_frontend->conn = $conn;
$obj_frontend->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* pay at venue appointment ajax */
if(isset($_POST['pay_at_venue_appointment'])){
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $_SESSION['saasappoint_location_selector_zipcode'];
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_customer_detail'] = $cust_arr;
	
	$_SESSION['transaction_id'] = '';
	header('location:'.AJAX_URL.'saasappoint_front_appt_process_ajax.php');
	exit(0);
}

/* paypal appointment ajax */
else if(isset($_POST['paypal_appointment'])){
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $_SESSION['saasappoint_location_selector_zipcode'];
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_customer_detail'] = $cust_arr;
	
	header('location:'.SITE_URL.'includes/payments/paypal/saasappoint_front_paypal_payment_process.php');
	exit(0);
}

/* authorize.net appointment ajax */
else if(isset($_POST['authorizenet_appointment'])){
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $_SESSION['saasappoint_location_selector_zipcode'];
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_customer_detail'] = $cust_arr;
	
	$_SESSION["saasappoint_card_num"] = $_POST["cardnumber"];
	$_SESSION["saasappoint_card_cvv"] = $_POST["cardcvv"];
	$_SESSION["saasappoint_card_exp_month"] = $_POST["cardexmonth"];
	$_SESSION["saasappoint_card_exp_year"] = $_POST["cardexyear"];
	$_SESSION["saasappoint_card_holdername"] = $_POST["cardholdername"];
	
	header('location:'.SITE_URL.'includes/payments/authorize.net/saasappoint_front_authorizenet_payment_process.php');
	exit(0);
}

/* 2checkout appointment ajax */
else if(isset($_POST['2checkout_appointment'])){
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $_SESSION['saasappoint_location_selector_zipcode'];
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_customer_detail'] = $cust_arr;
	$_SESSION['saasappoint_twocheckout_token'] = $_POST["token"];
	
	header('location:'.SITE_URL.'includes/payments/twocheckout/saasappoint_front_twocheckout_payment_process.php');
	exit(0);
}

/* stripe appointment ajax */
else if(isset($_POST['stripe_appointment'])){
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $_SESSION['saasappoint_location_selector_zipcode'];
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_customer_detail'] = $cust_arr;
	$_SESSION['saasappoint_stripe_token'] = $_POST["token"];
	
	header('location:'.SITE_URL.'includes/payments/stripe/saasappoint_front_stripe_payment_process.php');
	exit(0);
}