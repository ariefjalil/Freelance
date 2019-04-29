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

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

/** register customer ajax **/
if(isset($_POST["customer_register"])){
	$obj_frontend->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_frontend->password = md5($_POST['password']);
	$obj_frontend->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$obj_frontend->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$obj_frontend->phone = $_POST['phone'];
	$obj_frontend->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$obj_frontend->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$obj_frontend->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$obj_frontend->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$obj_frontend->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
	$customer_id = $obj_frontend->add_customers();
	if(is_numeric($customer_id)){
		/* Set session values for logged in customer */
		unset($_SESSION['admin_id']);
		unset($_SESSION['superadmin_id']);
		$_SESSION['customer_id'] = $customer_id;
		$_SESSION['login_type'] = "customer";
		echo "registered";
	}
}