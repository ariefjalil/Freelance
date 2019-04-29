<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_superadmins.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_superadmins = new saasappoint_superadmins();
$obj_superadmins->conn = $conn;

/** Change Password Ajax **/
if(isset($_POST['change_superadmin_password'])){
	$obj_superadmins->id = $_POST['superadmin_id'];
	$obj_superadmins->password = md5($_POST['old_password']);
	$check_old_password = $obj_superadmins->check_old_password();
	if($check_old_password){
		$obj_superadmins->id = $_POST['superadmin_id'];
		$obj_superadmins->password = md5($_POST['new_password']);
		$change_password = $obj_superadmins->change_password();
		if($change_password){
			echo "changed";
		}
	}else{
		echo "wrong";
	}
}

/** Update Profile Ajax **/
else if(isset($_POST['update_profile'])){
	$obj_superadmins->id = $_POST['id'];
	$obj_superadmins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$obj_superadmins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$obj_superadmins->phone = $_POST['phone'];
	$obj_superadmins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$obj_superadmins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$obj_superadmins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$obj_superadmins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$obj_superadmins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
		
	$updated = $obj_superadmins->update_profile();
	if($updated){
		echo "updated";
	}
}

/** Setup settings and Profile from setup page Ajax **/
else if(isset($_POST['sadminsetup_settings'])){
	$firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$password = md5($_POST['password']);
	$phone = $_POST['phone'];
	$address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
	
	$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
	$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
	$companyphone = $_POST['companyphone'];
		
	$updated = $obj_superadmins->update_sadminsetup_settings($firstname, $lastname, $email, $password, $phone, $address, $city, $state, $country, $zip, $companyname, $companyemail, $companyphone);
	if($updated){
		echo "updated";
	}
}

/** Change Email Ajax **/
else if(isset($_POST['change_email'])){
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_superadmins->id = $_SESSION["superadmin_id"];
	$obj_superadmins->email = $email;
	$superadmin_email = $obj_superadmins->get_superadmin_email();

	if($email == $superadmin_email){
		echo "updated";
	}else{
		$is_available = $obj_superadmins->check_email_availability($superadmin_email);
		if($is_available){
			$updated = $obj_superadmins->update_email();
			if($updated){
				echo "updated";
			}
		}else{
			echo "exist";
		}
	}
}
