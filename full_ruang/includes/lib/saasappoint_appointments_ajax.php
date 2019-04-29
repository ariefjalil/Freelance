<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$obj_bookings = new saasappoint_bookings();
$obj_bookings->conn = $conn;
$obj_bookings->business_id = $_SESSION['business_id'];

$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}

$appointments = array();
$all_appointments = $obj_bookings->get_all_appointments();
$status_array = array(
	'pending' => array(
		"status" => "Pending",
		"icon" => '<i class="fa fa-info-circle" title="Pending"></i>',
		"color" => '#1589FF'
	),
	'confirmed' => array(
		"status" => "Confirmed",
		"icon" => '<i class="fa fa-check" title="Confirmed"></i>',
		"color" => 'green'
	),
	'rescheduled_by_customer' => array(
		"status" => "Rescheduled By Customer",
		"icon" => '<i class="fa fa-refresh" title="Rescheduled By Customer"></i>',
		"color" => '#04B4AE'
	),
	'rescheduled_by_you' => array(
		"status" => "Rescheduled By You",
		"icon" => '<i class="fa fa-repeat" title="Rescheduled By You"></i>',
		"color" => '#6960EC'
	),
	'cancelled_by_customer' => array(
		"status" => "Cancelled By Customer",
		"icon" => '<i class="fa fa-close" title="Cancelled By Customer"></i>',
		"color" => '#FF4500'
	),
	'rejected_by_you' => array(
		"status" => "Rejected By You",
		"icon" => '<i class="fa fa-ban" title="Rejected By You"></i>',
		"color" => '#F70D1A'
	),
	'completed' => array(
		"status" => "Completed",
		"icon" => '<i class="fa fa-calendar-check-o" title="Completed"></i>',
		"color" => '#b7950b'
	)
);
while($appointment = mysqli_fetch_array($all_appointments)){
	$customer_name = ucwords($appointment['c_firstname']." ".$appointment['c_lastname']);
	$event_title = $appointment['title']." with ".$customer_name." on ".date($saasappoint_time_format, strtotime($appointment['booking_datetime']))." to ".date($saasappoint_time_format, strtotime($appointment['booking_end_datetime']));
	$get_feedback = $obj_bookings->get_appointment_rating($appointment['order_id']);
	$ratings = "";
	if(mysqli_num_rows($get_feedback)>0){
		$feedback = mysqli_fetch_array($get_feedback);
		if($feedback['rating']>0){
			for($star_i=0;$star_i<$feedback['rating'];$star_i++){ 
				$ratings .= '<i class="fa fa-star" aria-hidden="true"></i>';
			} 
			for($star_j=0;$star_j<(5-$feedback['rating']);$star_j++){ 
				$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
			} 
		}else{ 
			$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i>';
		} 
	}else{
		$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i> Rating pending';
	} 
	
	$appointment_array = array(
		  "id" => $appointment['order_id'],
		  "cat_name" => $appointment['cat_name'],
		  "title" => $event_title,
		  "start" => $appointment['booking_datetime'],
		  "end" => $appointment['booking_end_datetime'],
		  "customer_name" => $customer_name,
		  "customer_phone" => $appointment['c_phone'],
		  "customer_email" => $appointment['c_email'],
		  "event_status" => $status_array[$appointment['booking_status']]['status'],
		  "event_icon" => $status_array[$appointment['booking_status']]['icon'],
		  "color" => $status_array[$appointment['booking_status']]['color'],
		  "rating" => $ratings
	);
	array_push($appointments,$appointment_array);
}
echo json_encode($appointments);