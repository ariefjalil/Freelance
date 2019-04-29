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
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;

$image_upload_path = SITE_URL."/includes/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/images/";

/** Change Password Ajax **/
if(isset($_POST['change_customer_password'])){
	$obj_customers->id = $_POST['customer_id'];
	$obj_customers->password = md5($_POST['old_password']);
	$check_old_password = $obj_customers->check_old_password();
	if($check_old_password){
		$obj_customers->id = $_POST['customer_id'];
		$obj_customers->password = md5($_POST['new_password']);
		$change_password = $obj_customers->change_password();
		if($change_password){
			echo "changed";
		}
	}else{
		echo "wrong";
	}
}

/** Update Profile Ajax **/
else if(isset($_POST['update_profile'])){
	$obj_customers->id = $_POST['id'];
	$obj_customers->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$obj_customers->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$obj_customers->phone = $_POST['phone'];
	$obj_customers->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$obj_customers->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$obj_customers->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$obj_customers->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$obj_customers->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
	
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_customers->get_image_name_of_customer();
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = "cust_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_customers->image = $uploaded_filename;
		$updated = $obj_customers->update_profile_with_image();
		if($updated){
			echo "updated";
		}
	}else{
		$updated = $obj_customers->update_profile_without_image();
		if($updated){
			echo "updated";
		}
	}
}

/** Change Email Ajax **/
else if(isset($_POST['change_email'])){
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_customers->id = $_SESSION["customer_id"];
	$obj_customers->email = $email;
	$customer_email = $obj_customers->get_customer_email();

	if($email == $customer_email){
		echo "updated";
	}else{
		$is_available = $obj_customers->check_email_availability($customer_email);
		if($is_available){
			$updated = $obj_customers->update_email();
			if($updated){
				echo "updated";
			}
		}else{
			echo "exist";
		}
	}
}
