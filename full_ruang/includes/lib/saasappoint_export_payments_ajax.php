<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$obj_payments = new saasappoint_payments();
$obj_payments->conn = $conn;
$obj_payments->business_id = $_SESSION['business_id'];
$obj_addons = new saasappoint_addons();
$obj_addons->conn = $conn;
$obj_addons->business_id = $_SESSION['business_id'];

$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$export_path = SITE_URL."/includes/csv/";
$export_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/csv/";

/** Condition to export all payments details **/
if(isset($_POST['export_payments'])){
	$filename = base64_encode($_SESSION['business_id']."_all_payments").".csv";
	$filepath = $export_abs_path.$filename;
	$exported_file = $export_path.$filename;
	$file = fopen($filepath, "w");
	$header = array(
		"#",
		"Payment Method",
		"Payment Date",
		"Transaction ID",
		"Sub Total",
		"Discount",
		"Tax",
		"Net Total",
		"Frequently Discount",
		"Frequently Discount Amount",
		"Category",
		"Service",
		"Addons",
		"Booking Start Time",
		"Booking End Time",
		"Customer FirstName",
		"Customer LastName",
		"Customer Email",
		"Customer Phone",
		"Customer Type"
	);
	fputcsv($file, $header);
	$start = $_POST['from_date'];
	$end = $_POST['to_date'];
	if($_POST['payment_type'] == "registered"){
		$all_payments = $obj_payments->all_registered_customers_payments($start, $end);
	}else if($_POST['payment_type'] == "guest"){
		$all_payments = $obj_payments->all_guest_customers_payments($start, $end);
	}else{
		$all_payments = $obj_payments->get_all_customers_payments($start, $end);
	}
	
	while($payment = mysqli_fetch_assoc($all_payments)){
		$payment['c_firstname'] = ucwords($payment['c_firstname']);
		$payment['c_lastname'] = ucwords($payment['c_lastname']);
		$payment['booking_datetime'] = date($saasappoint_datetime_format, strtotime($payment['booking_datetime']));
		$payment['booking_end_datetime'] = date($saasappoint_datetime_format, strtotime($payment['booking_end_datetime']));
		
		$flag = true;
		$addons_detail = '';
		$unserialized_addons = unserialize($payment['addons']);
		foreach($unserialized_addons as $addon){
			$obj_addons->id = $addon['id'];
			$addon_name = $obj_addons->get_addon_name();
			if($flag){
				$addons_detail .= $addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
				$flag = false;
			}else{
				$addons_detail .= ", ".$addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
			}
		}
		$payment['addons'] = $addons_detail;
		
		$payment['payment_method'] = ucwords($payment['payment_method']);
		$payment['payment_date'] = date($saasappoint_date_format, strtotime($payment['payment_date']));
		$payment['sub_total'] = $saasappoint_currency_symbol.$payment['sub_total'];
		$payment['discount'] = $saasappoint_currency_symbol.$payment['discount'];
		$payment['tax'] = $saasappoint_currency_symbol.$payment['tax'];
		$payment['net_total'] = $saasappoint_currency_symbol.$payment['net_total'];
		$payment['fd_key'] = strtoupper($payment['fd_key']);
		$payment['fd_amount'] = $saasappoint_currency_symbol.$payment['fd_amount'];
		
		if($payment['customer_id'] == 0){
			$payment['customer_id'] = "Guest";
		}else{
			$payment['customer_id'] = "Registered";
		}
				
		fputcsv($file, $appointment);
	}
	echo $exported_file;
}