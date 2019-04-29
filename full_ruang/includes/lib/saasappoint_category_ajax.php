<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
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

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* Refresh categories ajax */
if(isset($_REQUEST['refresh_categories'])){
	$all_categories = $obj_categories->get_all_categories_within_limit($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$categories = array();
	$categories["draw"] = $_POST['draw'];
	$count_all_categories = $obj_categories->count_all_categories($_POST['search']['value']);
	$categories["recordsTotal"] = $count_all_categories;
	$categories["recordsFiltered"] = $count_all_categories;
	$categories['data'] =array();
	if(mysqli_num_rows($all_categories)>0){
		$i=$_POST['start'];
		while($category = mysqli_fetch_assoc($all_categories)){
			$i++;
			$category_arr = array();
			array_push($category_arr, $category['id']);
			array_push($category_arr, ucwords($category['cat_name']));
			
			$checked = '';
			if($category['status'] == "Y"){ $checked = "checked"; }
			array_push($category_arr, '<label class="saasappoint-toggle-switch">
				  <input type="checkbox" data-id="'.$category['id'].'" class="saasappoint-toggle-switch-input saasappoint_change_category_status" '.$checked.' />
				  <span class="saasappoint-toggle-switch-slider"></span>
				</label>');
			
			$obj_services->cat_id = $category['id'];
			$total_services = $obj_services->count_all_services_by_cat_id();
			
			array_push($category_arr, '<a class="btn btn-secondary btn-sm saasappoint_set_catid" href="'.SITE_URL.'backend/services.php" data-id="'.$category['id'].'"><i class="fa fa-fw fa-th-list"></i> Services <span class="badge badge-light">'.$total_services.'</span></a> &nbsp; <a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-categorymodal" data-id="'.$category['id'].'"><i class="fa fa-fw fa-pencil"></i></a> &nbsp; <a class="btn btn-danger saasappoint-white btn-sm saasappoint_delete_category_btn" data-id="'.$category['id'].'"><i class="fa fa-fw fa-trash"></i></a>');
			array_push($categories['data'], $category_arr);
		}
	}
	echo json_encode($categories);
}
/* Change category status ajax */
else if(isset($_POST['change_category_status'])){
	$obj_categories->id = $_POST['id'];
	$obj_categories->status = $_POST['category_status'];
	$status_changed = $obj_categories->change_category_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}
/* Delete category ajax */
else if(isset($_POST['delete_category'])){
	$obj_categories->id = $_POST['id'];
	$check_appointments = $obj_categories->check_appointments_before_delete_category();
	if($check_appointments==0){
		$deleted = $obj_categories->delete_category();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		echo "appointments exist";
	}
}
/* Add category ajax */
else if(isset($_POST['add_category'])){
	$obj_categories->cat_name = filter_var($_POST['cat_name'], FILTER_SANITIZE_STRING);
	$obj_categories->status = $_POST['status'];
	$added = $obj_categories->add_category();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}
/* Update category ajax */
else if(isset($_POST['update_category'])){
	$obj_categories->id = $_POST['id'];
	$obj_categories->cat_name = filter_var($_POST['cat_name'], FILTER_SANITIZE_STRING);
	$updated = $obj_categories->update_category();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}
/* Update category modal detail ajax */
else if(isset($_POST['update_category_modal_detail'])){
	$obj_categories->id = $_POST['id'];
	$category = $obj_categories->readone_category();
	
	?>
	<form name="saasappoint_update_category_form" id="saasappoint_update_category_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_categoryname">Category Name</label>
		<input class="form-control" id="saasappoint_update_categoryname" name="saasappoint_update_categoryname" type="text" placeholder="Enter Category Name" value="<?php echo $category['cat_name']; ?>" />
	  </div>
	</form>
	<?php
}