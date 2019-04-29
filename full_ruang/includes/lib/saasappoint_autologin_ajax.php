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

/* Login process ajax */
if(isset($_POST['autologin_process'])){
	$obj_login->business_id = $_POST['id'];
	/* Function to check login details */
	$obj_login->autologin_process();
}