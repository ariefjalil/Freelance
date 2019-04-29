<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_customers.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->business_id = $_SESSION['business_id'];
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* Refresh Guest customers ajax */
if(isset($_REQUEST['refresh_gc_detail'])){
	$all_rc_detail = $obj_customers->get_all_gc_detail($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$customers = array();
	$customers["draw"] = $_POST['draw'];
	$count_all_payments = $obj_customers->count_all_gc($_POST['search']['value']);
	if($count_all_payments == "" || $count_all_payments == null){
		$count_all_payments = 0;
	}
	$customers["recordsTotal"] = $count_all_payments;
	$customers["recordsFiltered"] = $count_all_payments;
	$customers['data'] =array();
	if(mysqli_num_rows($all_rc_detail)>0){
		$i=$_POST['start'];
		while($rc = mysqli_fetch_assoc($all_rc_detail)){
			$i++;
			$total_appointments = $obj_customers->count_all_gc_booked_appt($rc['order_id']);
			$rc_arr = array();
			array_push($rc_arr, ucwords($rc['c_firstname'].' '.$rc['c_lastname']));
			array_push($rc_arr, $rc['c_phone']);
			array_push($rc_arr, $rc['c_email']);
			array_push($rc_arr, ucwords($rc['c_address'].', '.$rc['c_city'].', '.$rc['c_state'].', '.$rc['c_country'].'-'.$rc['c_zip']));
			array_push($rc_arr, '<a data-ctype="G" data-id="'.$rc['order_id'].'" class="btn btn-outline-secondary saasappoint_customer_appointments_btn"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Appointments <span class="badge badge-success">'.$total_appointments.'</span></a>');
			array_push($customers['data'], $rc_arr);
		}
	}
	echo json_encode($customers);
}