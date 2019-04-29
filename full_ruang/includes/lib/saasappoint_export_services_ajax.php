<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_categories = new saasappoint_categories();
$obj_categories->conn = $conn;
$obj_categories->business_id = $_SESSION['business_id'];

$obj_services = new saasappoint_services();
$obj_services->conn = $conn;
$obj_services->business_id = $_SESSION['business_id'];

$obj_addons = new saasappoint_addons();
$obj_addons->conn = $conn;
$obj_addons->business_id = $_SESSION['business_id'];

$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$export_path = SITE_URL."/includes/csv/";
$export_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/csv/";

/** Export customized services ajax  **/
if(isset($_POST["export_services"])){
	/** this condition is to export only all categories **/
	if(isset($_POST['categories']) && !isset($_POST['services']) && !isset($_POST['addons'])){
		$filename = base64_encode($_SESSION['business_id']."_all_categories").".csv";
		$filepath = $export_abs_path.$filename;
		$exported_file = $export_path.$filename;
		$file = fopen($filepath, "w");
		$header = array(
			"#",
			"Category Name",
			"Status"
		);
		fputcsv($file, $header);
		
		foreach($_POST['categories'] as $cat){
			$obj_categories->id = $cat;
			$category = $obj_categories->readone_category();
			unset($category['business_id']);
			if($category['status'] == "Y"){
				$category['status'] = "Activated";
			}else{
				$category['status'] = "Deactivated";
			}
			fputcsv($file, $category);
		}
		
		echo $exported_file;
	}
	
	/** this condition is to export only all services **/
	if(isset($_POST['services']) && !isset($_POST['categories']) && !isset($_POST['addons'])){
		$filename = base64_encode($_SESSION['business_id']."_all_services").".csv";
		$filepath = $export_abs_path.$filename;
		$exported_file = $export_path.$filename;
		$file = fopen($filepath, "w");
		$header = array(
			"#",
			"Category",
			"Service Title",
			"Service Description",
			"Status"
		);
		fputcsv($file, $header);
		
		foreach($_POST['services'] as $ser){
			$obj_services->id = $ser;
			$service = $obj_services->readone_service();
			$obj_categories->id = $service['cat_id'];
			$category_name = $obj_categories->readone_category_name();
			unset($service['business_id']);
			unset($service['image']);
			$service['cat_id'] = $category_name;
			if($service['status'] == "Y"){
				$service['status'] = "Activated";
			}else{
				$service['status'] = "Deactivated";
			}
			fputcsv($file, $service);
		}
		
		echo $exported_file;
	}
	
	/** this condition is to export only all addons **/
	if(isset($_POST['addons']) && !isset($_POST['services']) && !isset($_POST['categories'])){
		
		$filename = base64_encode($_SESSION['business_id']."_all_addons").".csv";
		$filepath = $export_abs_path.$filename;
		$exported_file = $export_path.$filename;
		$file = fopen($filepath, "w");
		$header = array(
			"#",
			"Category",
			"Service",
			"Addon Title",
			"Addon Rate",
			"Multiple Quantity",
			"Status"
		);
		fputcsv($file, $header);
		
		foreach($_POST['addons'] as $add){
			$obj_addons->id = $add;
			$addons = $obj_addons->export_all_addons();
			if($addons['multiple_qty'] == "Y"){
				$addons['multiple_qty'] = "Yes";
			}else{
				$addons['multiple_qty'] = "No";
			}
			$addons['rate'] = $saasappoint_currency_symbol.$addons['rate'];
			if($addons['status'] == "Y"){
				$addons['status'] = "Activated";
			}else{
				$addons['status'] = "Deactivated";
			}
			fputcsv($file, $addons);
		}
		
		echo $exported_file;
	}
	
	/** this condition is to export all categories, services and addons **/
	if(isset($_POST['categories']) && isset($_POST['services']) && isset($_POST['addons'])){
		$filename = base64_encode($_SESSION['business_id']."_export_all").".csv";
		$filepath = $export_abs_path.$filename;
		$exported_file = $export_path.$filename;
		$file = fopen($filepath, "w");
		
		/** Export category **/
		fputcsv($file, array("Categories:"));
		$header_cat = array(
			"#",
			"Category Name",
			"Status"
		);
		fputcsv($file, $header_cat);		
		foreach($_POST['categories'] as $cat){
			$obj_categories->id = $cat;
			$category = $obj_categories->readone_category();
			unset($category['business_id']);
			if($category['status'] == "Y"){
				$category['status'] = "Activated";
			}else{
				$category['status'] = "Deactivated";
			}
			fputcsv($file, $category);
		}
		
		/** Export services **/
		fputcsv($file, array("Services:"));
		$header_ser = array(
			"#",
			"Category",
			"Service Title",
			"Service Description",
			"Status"
		);
		fputcsv($file, $header_ser);		
		foreach($_POST['services'] as $ser){
			$obj_services->id = $ser;
			$service = $obj_services->readone_service();
			$obj_categories->id = $service['cat_id'];
			$category_name = $obj_categories->readone_category_name();
			unset($service['business_id']);
			unset($service['image']);
			$service['cat_id'] = $category_name;
			if($service['status'] == "Y"){
				$service['status'] = "Activated";
			}else{
				$service['status'] = "Deactivated";
			}
			fputcsv($file, $service);
		}
		
		/** Export addons **/
		fputcsv($file, array("Addons:"));
		$header_addon = array(
			"#",
			"Category",
			"Service",
			"Addon Title",
			"Addon Rate",
			"Multiple Quantity",
			"Status"
		);
		fputcsv($file, $header_addon);		
		foreach($_POST['addons'] as $add){
			$obj_addons->id = $add;
			$addons = $obj_addons->export_all_addons();
			if($addons['multiple_qty'] == "Y"){
				$addons['multiple_qty'] = "Yes";
			}else{
				$addons['multiple_qty'] = "No";
			}
			$addons['rate'] = $saasappoint_currency_symbol.$addons['rate'];
			if($addons['status'] == "Y"){
				$addons['status'] = "Activated";
			}else{
				$addons['status'] = "Deactivated";
			}
			fputcsv($file, $addons);
		}
		
		echo $exported_file;
	}
}

/** Get services and categories according category selection ajax  **/
else if(isset($_POST["get_services_and_addons"])){
	$service_options = "";
	$addon_options = "";
	foreach($_POST['categories'] as $cat){
		$obj_services->cat_id = $cat;
		$services = $obj_services->get_all_services_according_cat_id();
		while($service = mysqli_fetch_assoc($services)){
			$service_options .= "<option value='".$service['id']."'>".$service['title']."</option>";
			$obj_addons->service_id = $service['id'];
			$addons = $obj_addons->get_all_addons_according_service_id();
			while($addon = mysqli_fetch_assoc($addons)){
				$addon_options .= "<option value='".$addon['id']."'>".$addon['title']."</option>";
			}
		}
	}
	$json_array = array();
	$json_array['service_options'] = $service_options;
	$json_array['addon_options'] = $addon_options;
	echo json_encode($json_array);die;
}

/** Get addons according services selection ajax  **/
else if(isset($_POST["get_addons"])){
	$addon_options = "";
	foreach($_POST['services'] as $ser){
		$obj_addons->service_id = $ser;
		$addons = $obj_addons->get_all_addons_according_service_id();
		while($addon = mysqli_fetch_assoc($addons)){
			$addon_options .= "<option value='".$addon['id']."'>".$addon['title']."</option>";
		}
	}
	$json_array = array();
	$json_array['addon_options'] = $addon_options;
	echo json_encode($json_array);die;
}
