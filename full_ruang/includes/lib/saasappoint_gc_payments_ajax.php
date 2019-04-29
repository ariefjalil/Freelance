<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_payments = new saasappoint_payments();
$obj_payments->conn = $conn;
$obj_payments->business_id = $_SESSION['business_id'];
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');

/* Refresh Guest customers payment ajax */
if(isset($_REQUEST['refresh_gc_payments'])){
	$all_payments = $obj_payments->get_all_gc_payment_detail($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$payments = array();
	$payments["draw"] = $_POST['draw'];
	$count_all_payments = $obj_payments->count_all_gc_payments($_POST['search']['value']);
	$payments["recordsTotal"] = $count_all_payments;
	$payments["recordsFiltered"] = $count_all_payments;
	$payments['data'] =array();
	
	if(mysqli_num_rows($all_payments)>0){
		$i=$_POST['start'];
		while($payment = mysqli_fetch_assoc($all_payments)){
			$i++;
			$payment_arr = array();
			array_push($payment_arr, $payment['order_id']);
			array_push($payment_arr, ucwords($payment['c_firstname'].' '.$payment['c_lastname']));
			array_push($payment_arr, ucwords($payment['payment_method']));
			array_push($payment_arr, date($saasappoint_date_format, strtotime($payment['payment_date'])));
			array_push($payment_arr, $payment['transaction_id']);
			array_push($payment_arr, $saasappoint_currency_symbol.$payment['sub_total']);
			array_push($payment_arr, $saasappoint_currency_symbol.$payment['discount']);
			array_push($payment_arr, $saasappoint_currency_symbol.$payment['tax']);
			array_push($payment_arr, $saasappoint_currency_symbol.$payment['net_total']);
			array_push($payment_arr, strtoupper($payment['fd_key']));
			array_push($payment_arr, $saasappoint_currency_symbol.$payment['fd_amount']);
			array_push($payments['data'], $payment_arr);
		}
	}
	echo json_encode($payments);
}
