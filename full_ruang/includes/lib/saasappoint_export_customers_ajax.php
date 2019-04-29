<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_customers.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->business_id = $_SESSION['business_id'];

$export_path = SITE_URL."/includes/csv/";
$export_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/csv/";

/** Condition to export all customers details **/
if(isset($_POST['export_customers'])){
	$filename = base64_encode($_SESSION['business_id']."_all_customers").".csv";
	$filepath = $export_abs_path.$filename;
	$exported_file = $export_path.$filename;
	$file = fopen($filepath, "w");
	$header = array(
		"#",
		"Customer FirstName",
		"Customer LastName",
		"Customer Email",
		"Customer Phone",
		"Customer Address",
		"Customer City",
		"Customer State",
		"Customer Country",
		"Customer Zip",
		"Customer Type"
	);
	fputcsv($file, $header);
	
	if($_POST['customer_type'] == "registered"){
		$all_customers = $obj_customers->all_registered_customers_to_export();
	}else if($_POST['customer_type'] == "guest"){
		$all_customers = $obj_customers->all_guest_customers_to_export();
	}else{
		$all_customers = $obj_customers->get_all_customers_to_export();
	}
	
	$i=1;
	while($customer = mysqli_fetch_assoc($all_customers)){
		$customer['c_firstname'] = ucwords($customer['c_firstname']);
		$customer['c_lastname'] = ucwords($customer['c_lastname']);
		
		if($customer['customer_id'] == 0){
			$customer['customer_type'] = "Guest";
		}else{
			$customer['customer_type'] = "Registered";
		}
		$customer['customer_id'] = $i;
		fputcsv($file, $customer);
		$i++;
	}
	echo $exported_file;
}