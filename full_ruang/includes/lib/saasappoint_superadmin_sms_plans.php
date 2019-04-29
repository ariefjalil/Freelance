<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_sms_plans.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_sms_plans = new saasappoint_sms_plans();
$obj_sms_plans->conn = $conn;

/** Add SMS plan detail Ajax **/
if(isset($_POST['add_sms_plan'])){ 
	$obj_sms_plans->plan_name = $_POST["plan_name"];
	$obj_sms_plans->plan_rate = $_POST["plan_rate"];
	$obj_sms_plans->credit = $_POST["credit"];
	$obj_sms_plans->status = $_POST["status"];
	$added = $obj_sms_plans->add_sms_plan(); 
	if($added){
		echo "added";
	}
}

/** Update SMS plan detail Ajax **/
else if(isset($_POST['update_sms_plan'])){ 
	$obj_sms_plans->id = $_POST["id"];
	$obj_sms_plans->plan_name = $_POST["plan_name"];
	$obj_sms_plans->plan_rate = $_POST["plan_rate"];
	$obj_sms_plans->credit = $_POST["credit"];
	$updated = $obj_sms_plans->update_sms_plan(); 
	if($updated){
		echo "updated";
	}
}

/** Update SMS plan modal detail Ajax **/
else if(isset($_POST['update_smsplan_modal_detail'])){
	$obj_sms_plans->id = $_POST['id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan(); 
	?>
	<form name="saasappoint_update_smsplan_form" id="saasappoint_update_smsplan_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_smsplanname">SMS Plan Name</label>
		<input class="form-control" id="saasappoint_update_smsplanname" name="saasappoint_update_smsplanname" value="<?php echo $plan_detail["plan_name"]; ?>" type="text" placeholder="Enter SMS Plan Name" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_smsplanrate">SMS Plan Rate</label>
		<input class="form-control" id="saasappoint_update_smsplanrate" name="saasappoint_update_smsplanrate" value="<?php echo $plan_detail["plan_rate"]; ?>" type="text" placeholder="e.g. 27.99" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_smscredit">SMS Credit</label>
		<input class="form-control" id="saasappoint_update_smscredit" name="saasappoint_update_smscredit" value="<?php echo $plan_detail["credit"]; ?>" type="text" placeholder="e.g. 10" />
	  </div>
	</form>
	<?php 
}

/** Delete SMS plan detail Ajax **/
else if(isset($_POST['delete_sms_plan'])){
	$obj_sms_plans->id = $_POST['id'];
	$check_subscription = $obj_sms_plans->check_subscription_before_delete_plan();
	if($check_subscription==0){
		$deleted = $obj_sms_plans->delete_sms_plan(); 
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		echo "exist";
	}
}