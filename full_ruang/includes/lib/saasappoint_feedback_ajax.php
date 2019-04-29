<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_feedback.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_feedback = new saasappoint_feedback();
$obj_feedback->conn = $conn;
$obj_feedback->business_id = $_SESSION['business_id'];
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}

/* Refresh feedbacks ajax */
if(isset($_REQUEST['refresh_feedbacks'])){
	$all_feedbacks = $obj_feedback->get_all_feedbacks_within_limit($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$feedbacks = array();
	$feedbacks["draw"] = $_POST['draw'];
	$count_all_feedbacks = $obj_feedback->count_all_feedbacks($_POST['search']['value']);
	$feedbacks["recordsTotal"] = $count_all_feedbacks;
	$feedbacks["recordsFiltered"] = $count_all_feedbacks;
	$feedbacks['data'] =array();
	$rating_star_array = array(
		"0" => '-',
		"1" => '<i class="fa fa-star" aria-hidden="true"></i>',
		"2" => '<i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>',
		"3" => '<i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>',
		"4" => '<i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>',
		"5" => '<i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>',
	);
	if(mysqli_num_rows($all_feedbacks)>0){
		$i=$_POST['start'];
		while($feedback = mysqli_fetch_assoc($all_feedbacks)){
			$i++;
			$feedback_arr = array();
			array_push($feedback_arr, ucwords($feedback['name']));
			array_push($feedback_arr, $feedback['email']);
			array_push($feedback_arr, $rating_star_array[$feedback['rating']]);
			array_push($feedback_arr, $feedback['review']);
			array_push($feedback_arr, date($saasappoint_date_format." ".$saasappoint_time_format, strtotime($feedback['review_datetime'])));

			$checked = '';
			if($feedback['status'] == "Y"){ $checked = "checked"; }
			array_push($feedback_arr, '<label class="saasappoint-toggle-switch">
				  <input type="checkbox" data-id="'.$feedback['id'].'" class="saasappoint-toggle-switch-input saasappoint_change_feedback_status" '.$checked.' />
				  <span class="saasappoint-toggle-switch-slider"></span>
				</label>');
			array_push($feedbacks['data'], $feedback_arr);
		}
	}
	echo json_encode($feedbacks);
}
/* Update feedback status ajax */
else if(isset($_POST['change_feedback_status'])){
	$obj_feedback->id = $_POST['id'];
	$obj_feedback->status = $_POST['status'];
	$feedback_updated = $obj_feedback->change_feedback_status();
	if($feedback_updated){
		echo "updated";
	}else{
		echo "failed";
	}
}