<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_businesses.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;

/* Update business status ajax */
if(isset($_POST['change_business_status'])){
	$obj_businesses->id = $_POST['id'];
	$obj_businesses->status = $_POST['status'];
	$updated = $obj_businesses->change_business_status();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}
/* Save business Points */
else if(isset($_POST['bussiness_points'])){
	$obj_businesses->id = $_POST['bid'];
	$obj_businesses->business_type_id = $_POST['points'];
	$updated = $obj_businesses->save_business_points();
	if($updated){
		echo "save";
	}else{
		echo "failed";
	}
}
/* Bussines Points Visibility status ajax */
else if(isset($_POST['change_business_point_status'])){
	$obj_businesses->id = $_POST['id'];
	$obj_businesses->status = $_POST['status'];
	$updated = $obj_businesses->change_business_points_status();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}
/* Delete business ajax */
else if(isset($_POST['delete_business'])){
	$deleted = $obj_businesses->delete_business($_POST['id']);
	if($deleted){
		echo "deleted";
	}else{
		echo "failed";
	}
}