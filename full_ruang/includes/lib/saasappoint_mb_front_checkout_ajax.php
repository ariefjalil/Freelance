<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_manual_booking.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_frontend = new saasappoint_manual_booking();
$obj_frontend->conn = $conn;
$obj_frontend->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}

/* pay at venue appointment ajax */
if(isset($_POST['pay_at_venue_appointment'])){
	$saasappoint_location_selector_status = $obj_settings->get_option("saasappoint_location_selector_status"); 
	if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
		$zip = "N/A";
	}else{
		$zip = $_POST["zip"];
	}
	$payment_method = $_POST['payment_method'];
	$cust_arr = array();
	$cust_arr['email'] = $_POST['email'];
	$cust_arr['password'] = $_POST['password'];
	$cust_arr['firstname'] = $_POST['firstname'];
	$cust_arr['lastname'] = $_POST['lastname'];
	$cust_arr['zip'] = $zip;
	$cust_arr['phone'] = $_POST['phone'];
	$cust_arr['address'] = $_POST['address'];
	$cust_arr['city'] = $_POST['city'];
	$cust_arr['state'] = $_POST['state'];
	$cust_arr['country'] = $_POST['country'];
	$cust_arr['customertype'] = $_POST['type'];
	$cust_arr['payment_method'] = $payment_method;
	
	$_SESSION['saasappoint_mb_customer_detail'] = $cust_arr;
	
	$_SESSION['mb_customer_id'] = $_POST['customer_id'];
	$_SESSION['mb_transaction_id'] = '';
	header('location:'.AJAX_URL.'saasappoint_mb_front_appt_process_ajax.php');
	exit(0);
}