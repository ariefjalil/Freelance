<?php 
session_start();
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_login.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_login = new saasappoint_login();
$obj_login->conn = $conn;

/* Email checking process ajax */
if(isset($_POST['check_email_exist'])){
	$obj_login->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$exist = $obj_login->check_email_exist();
	if($exist){
		echo "true";
	}else{
		echo "false";
	}
}

/* Email checking process ajax */
if(isset($_POST['check_front_email_exist'])){
	if(isset($_SESSION['customer_id'])){
		echo "true";
	}else{
		$obj_login->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
		$exist = $obj_login->check_email_exist();
		if($exist){
			echo "true";
		}else{
			echo "false";
		}
	}
}