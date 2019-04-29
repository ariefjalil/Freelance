<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_block_off.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_block_off = new saasappoint_block_off();
$obj_block_off->conn = $conn;
$obj_block_off->business_id = $_SESSION['business_id'];

/* Change block off status ajax */
if(isset($_POST['change_blockoff_status'])){
	$obj_block_off->id = $_POST['id'];
	$obj_block_off->status = $_POST['status'];
	$status_changed = $obj_block_off->update_block_off_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}
/* Delete block off ajax */
else if(isset($_POST['delete_blockoff'])){
	$obj_block_off->id = $_POST['id'];
	$deleted = $obj_block_off->delete_block_off();
	if($deleted){
		echo "deleted";
	}else{
		echo "failed";
	}
}
/* Add block off ajax */
else if(isset($_POST['add_blockoff'])){
	$obj_block_off->title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
	$obj_block_off->from_date = date("Y-m-d", strtotime($_POST['from_date']));
	$obj_block_off->to_date = date("Y-m-d", strtotime($_POST['to_date']));
	$obj_block_off->pattern = "daily";
	$obj_block_off->blockoff_type = $_POST["blockoff_type"];
	$obj_block_off->from_time = date("H:i:s", strtotime($_POST['from_time']));
	$obj_block_off->to_time = date("H:i:s", strtotime($_POST['to_time']));
	$obj_block_off->status = $_POST["status"];
	$added = $obj_block_off->add_block_off();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}
/* Update Block Off ajax */
else if(isset($_POST['update_blockoff'])){
	$obj_block_off->id = $_POST['id'];
	$obj_block_off->title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
	$obj_block_off->from_date = date("Y-m-d", strtotime($_POST['from_date']));
	$obj_block_off->to_date = date("Y-m-d", strtotime($_POST['to_date']));
	$obj_block_off->pattern = "daily";
	$obj_block_off->blockoff_type = $_POST["blockoff_type"];
	$obj_block_off->from_time = date("H:i:s", strtotime($_POST['from_time']));
	$obj_block_off->to_time = date("H:i:s", strtotime($_POST['to_time']));
	$updated = $obj_block_off->update_block_off();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/* Update Block Off modal detail ajax */
else if(isset($_POST['update_blockoff_modal_detail'])){
	$obj_block_off->id = $_POST['id'];
	$block_off = $obj_block_off->readone_block_off(); 
	?>
	<form name="saasappoint_update_blockoff_form" id="saasappoint_update_blockoff_form" method="post">
		<div class="row">
		  <div class="form-group col-md-12">
			<label for="saasappoint_update_blockofftitle">Block Off Title</label>
			<input class="form-control" id="saasappoint_update_blockofftitle" name="saasappoint_update_blockofftitle" type="text" placeholder="Enter Block Off Title" value="<?php echo $block_off["title"]; ?>" />
		  </div>
		</div>
		<div class="row">
		  <div class="form-group col-md-6">
			<label for="saasappoint_update_blockoff_fromdate">From Date</label>
			<input class="form-control" id="saasappoint_update_blockoff_fromdate" name="saasappoint_update_blockoff_fromdate" type="date"  value="<?php echo $block_off["from_date"]; ?>" />
		  </div>
		  <div class="form-group col-md-6">
			<label for="saasappoint_update_blockoff_todate">To Date</label>
			<input class="form-control" id="saasappoint_update_blockoff_todate" name="saasappoint_update_blockoff_todate" type="date" value="<?php echo $block_off["to_date"]; ?>" />
		  </div>
		</div>
		<div class="row">
		  <div class="form-group col-md-12">
			<label for="saasappoint_update_blockoff_type">Block Off Type</label>
			<div>
				<label><input type="radio" class="saasappoint_update_blockoff_type" name="saasappoint_update_blockoff_type" value="fullday" <?php if($block_off["blockoff_type"] == "fullday"){ echo "checked"; } ?> /> FullDay</label> &nbsp; <label><input type="radio" class="saasappoint_update_blockoff_type" name="saasappoint_update_blockoff_type" value="custom" <?php if($block_off["blockoff_type"] == "custom"){ echo "checked"; } ?> /> Custom</label>
			</div>
		  </div>
		</div>
		<div class="saasappoint_hide_blockoff_custom_box" <?php if($block_off["blockoff_type"] == "custom"){ echo "style='display:block'"; } ?>>
			<div class="row">
			  <div class="form-group col-md-6">
				<label for="saasappoint_update_blockoff_fromtime">From Time</label>
				<input class="form-control" id="saasappoint_update_blockoff_fromtime" name="saasappoint_update_blockoff_fromtime" type="time" value="<?php echo date("H:i", strtotime($block_off["from_time"])); ?>" />
			  </div>
			  <div class="form-group col-md-6">
				<label for="saasappoint_update_blockoff_totime">To Time</label>
				<input class="form-control" id="saasappoint_update_blockoff_totime" name="saasappoint_update_blockoff_totime" type="time" value="<?php echo date("H:i", strtotime($block_off["to_time"])); ?>" />
			  </div>
			</div>
		</div>
	</form>
	<?php
}