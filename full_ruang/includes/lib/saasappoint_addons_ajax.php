<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_categories = new saasappoint_categories();
$obj_categories->conn = $conn;
$obj_categories->business_id = $_SESSION['business_id'];

$obj_services = new saasappoint_services();
$obj_services->conn = $conn;
$obj_services->business_id = $_SESSION['business_id'];

$obj_addons = new saasappoint_addons();
$obj_addons->conn = $conn;
$obj_addons->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$image_upload_path = SITE_URL."/includes/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/images/";

/* Refresh addons ajax */
if(isset($_REQUEST['refresh_addons'])){
	$obj_addons->service_id = $_REQUEST['service_id'];
	$all_addons = $obj_addons->get_all_addons_within_limit($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$addons = array();
	$addons["draw"] = $_POST['draw'];
	$count_all_addons = $obj_addons->count_all_addons($_POST['search']['value']);
	$addons["recordsTotal"] = $count_all_addons;
	$addons["recordsFiltered"] = $count_all_addons;
	$addons['data'] =array();
	if(mysqli_num_rows($all_addons)>0){
		$i=$_POST['start'];
		while($addon = mysqli_fetch_assoc($all_addons)){
			$i++;
			$addon_arr = array();
			array_push($addon_arr, $addon['id']);
			array_push($addon_arr, ucwords($addon['title']));
			array_push($addon_arr, ucwords($addon['cat_name']));
			array_push($addon_arr, ucwords($addon['service_title']));
			array_push($addon_arr, $obj_settings->get_option('saasappoint_currency_symbol').$addon['rate']);
			
			$multi_checked = '';
			if($addon['multiple_qty'] == "Y"){ $multi_checked = "checked"; }
			array_push($addon_arr, '<label class="saasappoint-toggle-switch">
				  <input type="checkbox" data-id="'.$addon['id'].'" class="saasappoint-toggle-switch-input saasappoint_change_addon_multiple_qty_status" '.$multi_checked.' />
				  <span class="saasappoint-toggle-switch-slider"></span>
				</label>');
				
			$checked = '';
			if($addon['status'] == "Y"){ $checked = "checked"; }
			array_push($addon_arr, '<label class="saasappoint-toggle-switch">
				  <input type="checkbox" data-id="'.$addon['id'].'" class="saasappoint-toggle-switch-input saasappoint_change_addon_status" '.$checked.' />
				  <span class="saasappoint-toggle-switch-slider"></span>
				</label>');
			
			array_push($addon_arr, '<a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-addonmodal" data-id="'.$addon['id'].'"><i class="fa fa-fw fa-pencil"></i></a> &nbsp; <a class="btn btn-danger saasappoint-white btn-sm saasappoint_delete_addon_btn" data-id="'.$addon['id'].'"><i class="fa fa-fw fa-trash"></i></a> &nbsp; <a class="btn btn-warning btn-sm saasappoint-view-addonmodal" data-id="'.$addon['id'].'"><i class="fa fa-fw fa-eye"></i></a>');
			array_push($addons['data'], $addon_arr);
		}
	}
	echo json_encode($addons);
}

/* Add addon ajax */
else if(isset($_POST['add_addon'])){
	$obj_addons->service_id = $_POST['service_id'];
	$obj_addons->title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
	$obj_addons->rate = $_POST['rate'];
	$obj_addons->description = htmlspecialchars(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
	$obj_addons->multiple_qty = $_POST['multiple_qty'];
	$obj_addons->status = $_POST['status'];
	
	if($_POST['uploaded_file'] != ""){
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_addons->image = $uploaded_filename;
	}else{
		$obj_addons->image = "";
	}
	$added = $obj_addons->add_addon();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/* Update addon modal detail ajax */
else if(isset($_POST['update_addon_modal_detail'])){
	$obj_addons->id = $_POST['id'];
	$addon = $obj_addons->readone_addon();
	?>
	<form name="saasappoint_update_addon_form" id="saasappoint_update_addon_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_addonname">Addon Name</label>
		<input class="form-control" id="saasappoint_update_addonname" name="saasappoint_update_addonname" type="text" placeholder="Enter Addon Name" value="<?php echo $addon['title'] ?>" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_addonrate">Addon Rate</label>
		<input class="form-control" id="saasappoint_update_addonrate" name="saasappoint_update_addonrate" type="text" placeholder="Enter Addon Rate" value="<?php echo $addon['rate'] ?>" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_addondescription">Addon Description</label>
		<textarea class="form-control" id="saasappoint_update_addondescription" name="saasappoint_update_addondescription" placeholder="Enter Addon Description"><?php echo $addon['description'] ?></textarea>
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_addonimage">Addon Image</label>
		<div class="saasappoint-image-upload">
			<div class="saasappoint-image-edit-icon">
				<input type='hidden' id="saasappoint-update-image-upload-file-hidden" name="saasappoint-update-image-upload-file-hidden" />
				<input type='file' id="saasappoint-update-image-upload-file" accept=".png, .jpg, .jpeg" />
				<label for="saasappoint-update-image-upload-file"></label>
			</div>
			<div class="saasappoint-image-preview">
				<div id="saasappoint-update-image-upload-file-preview" style="<?php $addon_image = $addon['image']; if($addon_image != '' && file_exists("../images/".$addon_image)){ echo "background-image: url(".SITE_URL."includes/images/".$addon_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-service.png);"; } ?>">
				</div>
			</div>
		</div>
	  </div>
	</form>
	<?php
}

/* Update addon ajax */
else if(isset($_POST['update_addon'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
	$obj_addons->rate = $_POST['rate'];
	$obj_addons->description = htmlspecialchars(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
	$addon = $obj_addons->readone_addon();
	$old_image = $addon['image'];
	if($_POST['uploaded_file'] != ""){
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_addons->image = $uploaded_filename;
	}else{
		$obj_addons->image = $old_image;
	}
	$updated = $obj_addons->update_addon();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/* View addon modal detail ajax */
else if(isset($_POST['view_addon_modal_detail'])){
	$obj_addons->id = $_POST['id'];
	$addon = $obj_addons->readone_addon();
	?>
	<div class="row">
		<div class="col-md-8">
			<div class="content-heading"><h3><?php echo ucwords($addon['title']); ?></h3></div>
			<p>Rate: <?php echo $obj_settings->get_option('saasappoint_currency_symbol').$addon['rate']; ?></p>
			<p>Multiple Qty.: <?php if($addon['multiple_qty'] == "Y"){ ?><label class="text-success">Activated</label><?php }else{ ?><label class="text-danger">Deactivated</label><?php } ?></p>
			<p>Status: <?php if($addon['status'] == "Y"){ ?><label class="text-success">Activated</label><?php }else{ ?><label class="text-danger">Deactivated</label><?php } ?></p>
		</div>
		<div class="col-md-2">
			<img class="saasappoint-view-addon-modal-image" src="<?php $addon_image = $addon['image']; if($addon_image != '' && file_exists("../images/".$addon_image)){ echo SITE_URL."includes/images/".$addon_image; }else{ echo SITE_URL."includes/images/default-service.png"; } ?>"/>
		</div>
		<div class="col-md-12">
			<p><?php echo $addon['description']; ?></p>
		</div>
	</div>
	<?php
}

/* Delete addon ajax */
else if(isset($_POST['delete_addon'])){
	$obj_addons->service_id = $_POST['service_id'];
	$obj_addons->id = $_POST['id'];
	$check_appointments = $obj_addons->check_appointments_before_delete_addon();
	if($check_appointments == "appointmentexist"){
		echo "appointments exist";
	}else if($check_appointments == "noappointmentexist"){
		$addon = $obj_addons->readone_addon();
		$old_image = $addon['image'];
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$deleted = $obj_addons->delete_addon();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}
}

/* Change addon status ajax */
else if(isset($_POST['change_addon_status'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->status = $_POST['status'];
	$status_changed = $obj_addons->change_addon_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

/* Change addon multiple qty status ajax */
else if(isset($_POST['change_addon_multiple_qty_status'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->multiple_qty = $_POST['multiple_qty'];
	$status_changed = $obj_addons->change_addon_multiple_qty_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}