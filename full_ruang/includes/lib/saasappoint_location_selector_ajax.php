<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/** check zip location Ajax **/
if(isset($_POST['check_zip_location'])){
	/* check location selector status */
	$saasappoint_location_selector_status = $obj_settings->get_option("saasappoint_location_selector_status"); 
	if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
		$_SESSION['saasappoint_location_selector_zipcode'] = "N/A";
		echo "available";
	}else{
		$zipcode = str_replace(' ', '', $_POST["zipcode"]);
		$saasappoint_location_selector = $obj_settings->get_option('saasappoint_location_selector');
		$exploded_saasappoint_location_selector = explode(",", $saasappoint_location_selector);
		if(in_array($zipcode, $exploded_saasappoint_location_selector)){
			$_SESSION['saasappoint_location_selector_zipcode'] = $zipcode;
			echo "available";
		}
	}
}
