<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_customers.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->business_id = $_SESSION['business_id'];
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
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

/* Refresh Registered customer's appointments ajax */
if(isset($_REQUEST['refresh_appt_detail']) && $_REQUEST['ctype'] == 'R'){
	$obj_customers->id = $_REQUEST['c_id'];
	$all_rc_detail = $obj_customers->get_all_rc_appointments($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$customers = array();
	$customers["draw"] = $_POST['draw'];
	$count_all_appt = $obj_customers->count_all_rc_appointments($_POST['search']['value']);
	$customers["recordsTotal"] = $count_all_appt;
	$customers["recordsFiltered"] = $count_all_appt;
	$customers['data'] =array();
	if(mysqli_num_rows($all_rc_detail)>0){
		$i=$_POST['start'];
		while($rc = mysqli_fetch_assoc($all_rc_detail)){
			$i++;
			$flag = true;
			$addons_detail = '';
			$unserialized_addons = unserialize($rc['addons']);
			foreach($unserialized_addons as $addon){
				$obj_addons->id = $addon['id'];
				$addon_name = $obj_addons->get_addon_name();
				if($flag){
					$addons_detail .= $addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
					$flag = false;
				}else{
					$addons_detail .= "<hr class='saasappoint_hr' />".$addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
				}
			}
			
			$booking_datetime = date($saasappoint_date_format, strtotime($rc['booking_datetime']))." ".date($saasappoint_time_format, strtotime($rc['booking_datetime']));
			
			$rc_arr = array();
			array_push($rc_arr, $rc['order_id']);
			array_push($rc_arr, ucwords($rc['cat_name']));
			array_push($rc_arr, ucwords($rc['title']));
			array_push($rc_arr, $addons_detail);
			array_push($rc_arr, $booking_datetime);
			array_push($rc_arr, ucwords(str_replace("_"," ",$rc['booking_status'])));
			array_push($rc_arr, ucwords($rc['payment_method']));
			array_push($customers['data'], $rc_arr);
		}
	}
	echo json_encode($customers);
}
/* Refresh Guest customer's appointments ajax */
else if(isset($_REQUEST['refresh_appt_detail']) && $_REQUEST['ctype'] == 'G'){
	$obj_customers->order_id = $_REQUEST['c_id'];
	$all_gc_detail = $obj_customers->get_all_gc_appointments($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$customers = array();
	$customers["draw"] = $_POST['draw'];
	$count_all_appt = '1';
	$customers["recordsTotal"] = $count_all_appt;
	$customers["recordsFiltered"] = $count_all_appt;
	$customers['data'] =array();
	if(mysqli_num_rows($all_gc_detail)>0){
		$i=$_POST['start'];
		while($rc = mysqli_fetch_assoc($all_gc_detail)){
			$i++;
			
			$flag = true;
			$addons_detail = '';
			$unserialized_addons = unserialize($rc['addons']);
			foreach($unserialized_addons as $addon){
				$obj_addons->id = $addon['id'];
				$addon_name = $obj_addons->get_addon_name();
				if($flag){
					$addons_detail .= $addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
					$flag = false;
				}else{
					$addons_detail .= "<hr class='saasappoint_hr' />".$addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
				}
			}
			
			$booking_datetime = date($saasappoint_date_format, strtotime($rc['booking_datetime']))." ".date($saasappoint_time_format, strtotime($rc['booking_datetime']));
			
			$gc_arr = array();
			array_push($gc_arr, $rc['order_id']);
			array_push($gc_arr, ucwords($rc['cat_name']));
			array_push($gc_arr, ucwords($rc['title']));
			array_push($gc_arr, $addons_detail);
			array_push($gc_arr, $booking_datetime);
			array_push($gc_arr, ucwords(str_replace("_"," ",$rc['booking_status'])));
			array_push($gc_arr, ucwords($rc['payment_method']));
			array_push($customers['data'], $gc_arr);
		}
	}
	echo json_encode($customers);
}