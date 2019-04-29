<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;
$obj_admins->business_id = $_SESSION['business_id'];

$image_upload_path = SITE_URL."/includes/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/images/";

/** Change Password Ajax **/
if(isset($_POST['change_admin_password'])){
	$obj_admins->id = $_POST['admin_id'];
	$obj_admins->password = md5($_POST['old_password']);
	$check_old_password = $obj_admins->check_old_password();
	if($check_old_password){
		$obj_admins->id = $_POST['admin_id'];
		$obj_admins->password = md5($_POST['new_password']);
		$change_password = $obj_admins->change_password();
		if($change_password){
			echo "changed";
		}
	}else{
		echo "wrong";
	}
}

/** Update Profile Ajax **/
else if(isset($_POST['update_profile'])){
	$obj_admins->id = $_POST['id'];
	$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$obj_admins->phone = $_POST['phone'];
	$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_admins->get_image_name_of_admin();
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_admins->image = $uploaded_filename;
		$updated = $obj_admins->update_profile_with_image();
		if($updated){
			echo "updated";
		}
	}else{
		$updated = $obj_admins->update_profile_without_image();
		if($updated){
			echo "updated";
		}
	}
}

/** Change Email Ajax **/
else if(isset($_POST['change_email'])){
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_admins->id = $_SESSION["admin_id"];
	$obj_admins->email = $email;
	$admin_email = $obj_admins->get_admin_email();

	if($email == $admin_email){
		echo "updated";
	}else{
		$is_available = $obj_admins->check_email_availability($admin_email);
		if($is_available){
			$updated = $obj_admins->update_email();
			if($updated){
				echo "updated";
			}
		}else{
			echo "exist";
		}
	}
}
