<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscription_plans.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

/** Check selected plan detail Ajax **/
if(isset($_POST['check_selected_plan'])){
	$obj_subscription_plans->id = $_POST['id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	if(sizeof($plan_detail)>0){
		if($plan_detail['plan_rate']>0){
			echo "notfree";
		}else{
			echo "freeplan";
		}
	}else{
		echo "notexist";
	}
}

/** Change plan status Ajax **/
else if(isset($_POST['change_splan_status'])){
	$obj_subscription_plans->id = $_POST['id'];
	$obj_subscription_plans->status = $_POST['status'];
	$updated = $obj_subscription_plans->change_splan_status();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Add subscription plan Ajax **/
else if(isset($_POST['add_subscription_plan'])){
	$obj_subscription_plans->plan_name = filter_var($_POST['plan_name'], FILTER_SANITIZE_STRING);
	$obj_subscription_plans->plan_rate = $_POST['plan_rate'];
	$obj_subscription_plans->plan_period = $_POST['plan_period'];
	$obj_subscription_plans->renewal_type = $_POST['renewal_type'];
	$obj_subscription_plans->status = $_POST['status'];
	$added = $obj_subscription_plans->add_subscription_plan();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/** Update subscription plan Ajax **/
else if(isset($_POST['update_subscription_plan'])){
	$obj_subscription_plans->id = $_POST['id'];
	$obj_subscription_plans->plan_name = filter_var($_POST['plan_name'], FILTER_SANITIZE_STRING);
	$obj_subscription_plans->plan_rate = $_POST['plan_rate'];
	$obj_subscription_plans->plan_period = $_POST['plan_period'];
	$obj_subscription_plans->renewal_type = $_POST['renewal_type'];
	$updated = $obj_subscription_plans->update_subscription_plan();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Delete subscription plan detail Ajax **/
else if(isset($_POST['delete_subscription_plan'])){
	$obj_subscription_plans->id = $_POST['id'];
	$check_subscription = $obj_subscription_plans->check_subscription_before_delete_plan();
	if($check_subscription==0){
		$deleted = $obj_subscription_plans->delete_subscription_plan(); 
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		echo "exist";
	}
}

/** Update subscription plan modal detail Ajax **/
else if(isset($_POST['update_splan_modal_detail'])){
	$obj_subscription_plans->id = $_POST['id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan(); 
	?>
	<form name="saasappoint_update_splan_form" id="saasappoint_update_splan_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_splanname">Subscription Plan Name</label>
		<input class="form-control" id="saasappoint_update_splanname" name="saasappoint_update_splanname" value="<?php echo $plan_detail["plan_name"]; ?>" type="text" placeholder="Enter Subscription Plan Name" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_splanrate">Subscription Plan Rate</label>
		<input class="form-control" id="saasappoint_update_splanrate" name="saasappoint_update_splanrate" value="<?php echo $plan_detail["plan_rate"]; ?>" type="text" placeholder="e.g. 27.99" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_splanperiod">Subscription Plan Period</label>
		<input class="form-control" id="saasappoint_update_splanperiod" name="saasappoint_update_splanperiod" value="<?php echo $plan_detail["plan_period"]; ?>" type="text" placeholder="e.g. 3" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_splantype">Subscription Plan Type</label>
		<select class="form-control" name="saasappoint_update_splantype" id="saasappoint_update_splantype">
			<option <?php if($plan_detail["renewal_type"] == "monthly"){ echo "selected"; } ?> value="monthly">Monthly</option>
			<option <?php if($plan_detail["renewal_type"] == "yearly"){ echo "selected"; } ?> value="yearly">Yearly</option>
		</select>
	  </div>
	</form>
	<?php 
}