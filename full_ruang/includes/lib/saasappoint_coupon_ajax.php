<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_coupons.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_coupons = new saasappoint_coupons();
$obj_coupons->conn = $conn;
$obj_coupons->business_id = $_SESSION['business_id'];
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* Add coupon ajax */
if(isset($_POST['add_coupon'])){
	$obj_coupons->coupon_code = filter_var($_POST['coupon_code'], FILTER_SANITIZE_STRING);
	$obj_coupons->coupon_type = $_POST['coupon_type'];
	$obj_coupons->coupon_value = $_POST['coupon_value'];
	$obj_coupons->coupon_expiry = date('Y-m-d', strtotime($_POST['coupon_expiry']));
	$obj_coupons->status = $_POST['status'];
	$coupon_added = $obj_coupons->add_coupon();
	if($coupon_added){
		echo "added";
	}else{
		echo "failed";
	}
}
/* Change coupon status ajax */
else if(isset($_POST['change_coupon_status'])){
	$obj_coupons->id = $_POST['id'];
	$obj_coupons->status = $_POST['status'];
	$status_changed = $obj_coupons->change_coupon_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}
/* Delete coupon ajax */
else if(isset($_POST['delete_coupon'])){
	$obj_coupons->id = $_POST['id'];
	$coupon_deleted = $obj_coupons->delete_coupon();
	if($coupon_deleted){
		echo "deleted";
	}else{
		echo "failed";
	}
}
/* Refresh coupon ajax */
else if(isset($_REQUEST['refresh_coupon'])){
	$all_coupons = $obj_coupons->get_all_coupons_within_limit($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$coupons = array();
	$coupons["draw"] = $_POST['draw'];
	$count_all_coupons = $obj_coupons->count_all_coupons($_POST['search']['value']);
	$coupons["recordsTotal"] = $count_all_coupons;
	$coupons["recordsFiltered"] = $count_all_coupons;
	$coupons['data'] =array();
	if(mysqli_num_rows($all_coupons)>0){
		$i=$_POST['start'];
		while($coupon = mysqli_fetch_assoc($all_coupons)){
			$i++;
			$coupon_arr = array();
			array_push($coupon_arr, $coupon['coupon_code']);
			array_push($coupon_arr, ucwords($coupon['coupon_type']));
			array_push($coupon_arr, $obj_settings->get_option('saasappoint_currency_symbol').$coupon['coupon_value']);
			array_push($coupon_arr, date($obj_settings->get_option('saasappoint_date_format'), strtotime($coupon['coupon_expiry'])));

			$checked = '';
			if($coupon['status'] == "Y"){ $checked = "checked"; }
			array_push($coupon_arr, '<label class="saasappoint-toggle-switch">
				  <input type="checkbox" data-id="'.$coupon['id'].'" class="saasappoint-toggle-switch-input saasappoint_change_coupon_status" '.$checked.' />
				  <span class="saasappoint-toggle-switch-slider"></span>
				</label>');
	
			array_push($coupon_arr, '<a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-couponmodal" data-id="'.$coupon['id'].'"><i class="fa fa-fw fa-pencil"></i></a> &nbsp;<a data-id="'.$coupon['id'].'" class="btn btn-danger saasappoint-white btn-sm saasappoint-delete-coupon-sweetalert"><i class="fa fa-fw fa-trash"></i></a>');
			array_push($coupons['data'], $coupon_arr);
		}
	}
	echo json_encode($coupons);
}
/* Update coupon modal ajax */
else if(isset($_REQUEST['update_coupon_modal'])){
	$obj_coupons->id = $_POST['id'];
	$coupon = $obj_coupons->readone_coupon();
	?>
	<form name="saasappoint_update_coupon_form" id="saasappoint_update_coupon_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_couponcode">Coupon Code</label>
		<input class="form-control" id="saasappoint_update_couponcode" name="saasappoint_update_couponcode" type="text" value="<?php echo $coupon['coupon_code']; ?>" placeholder="Enter Coupon Code" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_coupontype">Coupon Type</label>
		<select class="form-control" id="saasappoint_update_coupontype" name="saasappoint_update_coupontype">
		  <option value="percentage" <?php if($coupon['coupon_type'] == "percentage"){ echo "selected"; } ?>>Percentage</option>
		  <option value="flat" <?php if($coupon['coupon_type'] == "flat"){ echo "selected"; } ?>>Flat</option>
		</select>
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_couponvalue">Coupon Value</label>
		<input class="form-control" id="saasappoint_update_couponvalue" name="saasappoint_update_couponvalue" type="text" value="<?php echo $coupon['coupon_value']; ?>" placeholder="Enter Coupon Value" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_couponexpiry">Coupon Expiry</label>
		<input class="form-control" id="saasappoint_update_couponexpiry" name="saasappoint_update_couponexpiry" type="date" value="<?php echo $coupon['coupon_expiry'];?>" />
	  </div>
	  <div class="form-group pull-right">
		  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
		  <a class="btn btn-primary saasappoint_update_coupon_btn" data-id="<?php echo $coupon['id']; ?>" href="javascript:void(0);">Update</a>
	  </div>
	</form>
	<?php
}

/* Update coupon ajax */
else if(isset($_POST['update_coupon'])){
	$obj_coupons->id = $_POST['id'];
	$obj_coupons->coupon_code = filter_var($_POST['coupon_code'], FILTER_SANITIZE_STRING);
	$obj_coupons->coupon_type = $_POST['coupon_type'];
	$obj_coupons->coupon_value = $_POST['coupon_value'];
	$obj_coupons->coupon_expiry = date('Y-m-d', strtotime($_POST['coupon_expiry']));
	$coupon_updated = $obj_coupons->update_coupon();
	if($coupon_updated){
		echo "updated";
	}else{
		echo "failed";
	}
}