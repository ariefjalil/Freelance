<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frequently_discount.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$obj_frequently_discount = new saasappoint_frequently_discount();
$obj_frequently_discount->conn = $conn;
$obj_frequently_discount->business_id = $_SESSION['business_id'];

/* Change coupon status ajax */
if(isset($_POST['change_fd_status'])){
	$obj_frequently_discount->id = $_POST['id'];
	$obj_frequently_discount->fd_status = $_POST['fd_status'];
	$status_changed = $obj_frequently_discount->change_frequently_discount_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}
/* Update Frequently Discount modal ajax */
else if(isset($_REQUEST['update_fd_modal'])){
	$obj_frequently_discount->id = $_POST['id'];
	$fd = $obj_frequently_discount->readone_frequently_discount();
	?>
	<form name="saasappoint_update_fd_form" id="saasappoint_update_fd_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_fdlabel">Frequently Discount Label</label>
		<input class="form-control" id="saasappoint_fdlabel" name="saasappoint_fdlabel" type="text" placeholder="Enter Frequently Discount Label" value="<?php echo $fd['fd_label']; ?>" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_fdtype">Frequently Discount Type</label>
		<select class="form-control" id="saasappoint_fdtype" name="saasappoint_fdtype">
		  <option value="percentage" <?php if($fd['fd_type'] == "percentage"){ echo "selected"; } ?>>Percentage</option>
		  <option value="flat" <?php if($fd['fd_type'] == "flat"){ echo "selected"; } ?>>Flat</option>
		</select>
	  </div>
	  <div class="form-group">
		<label for="saasappoint_fdvalue">Frequently Discount Value</label>
		<input class="form-control" id="saasappoint_fdvalue" name="saasappoint_fdvalue" type="text" placeholder="Enter Frequently Discount Value" value="<?php echo $fd['fd_value']; ?>" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_fddescription">Frequently Discount Description</label>
		<textarea class="form-control" id="saasappoint_fddescription" name="saasappoint_fddescription" placeholder="Enter Frequently Discount Description"><?php echo $fd['fd_description']; ?></textarea>
	  </div>
	  <div class="form-group pull-right">
		<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
		<a class="btn btn-primary saasappoint_update_fd_btn" href="javascript:void(0);" data-id="<?php echo $fd['id']; ?>">Update</a>
	  </div>
	</form>
	<?php
}

/* Update Frequently Discount ajax */
else if(isset($_POST['update_frequently_discount'])){
	$obj_frequently_discount->id = $_POST['id'];
	$obj_frequently_discount->fd_label = filter_var($_POST['fd_label'], FILTER_SANITIZE_STRING);
	$obj_frequently_discount->fd_type = $_POST['fd_type'];
	$obj_frequently_discount->fd_value = $_POST['fd_value'];
	$obj_frequently_discount->fd_description = filter_var($_POST['fd_description'], FILTER_SANITIZE_STRING);
	$fd_updated = $obj_frequently_discount->update_frequently_discount();
	if($fd_updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/* Refresh Frequently Discount ajax */
else if(isset($_POST['refresh_frequently_discount'])){
	$all_frequently_discount = $obj_frequently_discount->get_all_frequently_discount();
	while($frequently_discount = mysqli_fetch_array($all_frequently_discount)){
		?>
		<tr>
		  <td><?php echo $frequently_discount['fd_label']; ?></td>
		  <td><?php echo ucwords($frequently_discount['fd_type']); ?></td>
		  <td><?php if($frequently_discount['fd_type'] == 'flat'){ echo $obj_settings->get_option('saasappoint_currency_symbol').$frequently_discount['fd_value']; }else{ echo $frequently_discount['fd_value'].'%'; } ?></td>
		  <td><?php echo $frequently_discount['fd_description']; ?></td>
		  <td>
			<label class="saasappoint-toggle-switch">
			  <input type="checkbox" class="saasappoint-toggle-switch-input saasappoint_change_fd_status" data-id="<?php echo $frequently_discount['id']; ?>" <?php if($frequently_discount['fd_status'] == 'Y'){ echo 'checked'; } ?> />
			  <span class="saasappoint-toggle-switch-slider"></span>
			</label>
		  </td>
		  <td>
			<a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-fdmodal" data-id="<?php echo $frequently_discount['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
		  </td>
		</tr>
		<?php
	}
}