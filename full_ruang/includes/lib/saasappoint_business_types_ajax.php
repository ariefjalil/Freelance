<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_business_type.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_business_type = new saasappoint_business_type();
$obj_business_type->conn = $conn;

/** Change business type status Ajax **/
if(isset($_POST['change_business_type_status'])){
	$obj_business_type->id = $_POST['id'];
	$obj_business_type->status = $_POST['status'];
	$updated = $obj_business_type->change_business_type_status();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Add business type Ajax **/
else if(isset($_POST['add_business_type'])){
	$obj_business_type->business_type = filter_var($_POST['business_type'], FILTER_SANITIZE_STRING);
	$obj_business_type->status = $_POST['status'];
	$added = $obj_business_type->add_business_type();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/** Update subscription plan Ajax **/
else if(isset($_POST['update_business_type'])){
	$obj_business_type->id = $_POST['id'];
	$obj_business_type->business_type = filter_var($_POST['business_type'], FILTER_SANITIZE_STRING);
	$updated = $obj_business_type->update_business_type();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Delete subscription plan detail Ajax **/
else if(isset($_POST['delete_business_type'])){
	$obj_business_type->id = $_POST['id'];
	$check_subscription = $obj_business_type->check_subscription_before_delete_business_type();
	if($check_subscription==0){
		$deleted = $obj_business_type->delete_business_type(); 
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		echo "exist";
	}
}

/** Update business type modal detail Ajax **/
else if(isset($_POST['update_btype_modal_detail'])){
	$obj_business_type->id = $_POST['id'];
	$btype_detail = $obj_business_type->readone_business_type(); 
	?>
	<form name="saasappoint_update_btype_form" id="saasappoint_update_btype_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_btypename">Business Type</label>
		<input class="form-control" id="saasappoint_update_btypename" name="saasappoint_update_btypename" value="<?php echo $btype_detail["business_type"]; ?>" type="text" placeholder="e.g. Cleaning, Spa, Saloon" />
	  </div>
	</form>
	<?php 
}