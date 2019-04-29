<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_slots.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_calendar.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_calendar = new saasappoint_calendar();
$obj_calendar->conn = $conn;
$obj_calendar->business_id = $_SESSION['business_id'];

$obj_slots = new saasappoint_slots();
$obj_slots->conn = $conn;
$obj_slots->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* Get calendar on load ajax */
if(isset($_POST['get_calendar_on_load'])){ 
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

	$selected_date = date("Y-m-d");
	$selected_date = date($selected_date, $currDateTime_withTZ);

	$today_date = date("Y-m-d");
	$today_date = date($today_date, $currDateTime_withTZ);
	$time = strtotime($selected_date);
	
	$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
	$time_format = $obj_settings->get_option('saasappoint_time_format');
	$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
	
	$saasappoint_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('saasappoint_hide_already_booked_slots_from_frontend_calendar');
	$saasappoint_minimum_advance_booking_time = $obj_settings->get_option('saasappoint_minimum_advance_booking_time');

	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$advance_bookingtime.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$saasappoint_minimum_advance_booking_time." minutes", $current_datetime));  
	
	$prev_next_date = date("Y-m-01");
	$prev_next_date = date($prev_next_date, $currDateTime_withTZ);
	$next_month = date("Y-m-01", strtotime('+1 months', strtotime($prev_next_date)));
	$prev_month = "";
	if(strtotime($next_month)>strtotime($maximum_date)){
		$next_month = "";
	}
	$pn = array('<i class="fa fa-chevron-left"></i>' => $prev_month, '<i class="fa fa-chevron-right"></i>' => $next_month);
	if(isset($_POST["online"])){
		$service_id = $_SESSION['saasappoint_cart_service_id'];
	}else if(isset($_POST["offline"])){
		$service_id = $_SESSION['saasappoint_mb_cart_service_id'];
	}else{
		$service_id = 0;
	}
	echo $obj_calendar->saasappoint_generate_calendar(date('Y', $time), date('n', $time), 3, 1, $pn, $time_interval, $time_format, $advance_bookingtime, $currDateTime_withTZ, $saasappoint_hide_already_booked_slots_from_frontend_calendar, $minimum_date, $maximum_date, $today_date,$service_id);  
	?>
	<div class="saasappoint-radio-group-block saasappoint_available_slots_block">
		<!-- Slots will goes here -->
	</div>
	<?php 
}
/* Get calendar on Next Previous ajax */
else if(isset($_POST['get_calendar_on_next_prev'])){ 
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

	$selected_date = date("Y-m-d", strtotime($_POST["selected_month"]));
	$selected_date = date($selected_date, $currDateTime_withTZ);
	
	$today_date = date("Y-m-d");
	$today_date = date($today_date, $currDateTime_withTZ);
	$time = strtotime($selected_date);
	
	$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
	$time_format = $obj_settings->get_option('saasappoint_time_format');
	$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
	
	$saasappoint_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('saasappoint_hide_already_booked_slots_from_frontend_calendar');
	$saasappoint_minimum_advance_booking_time = $obj_settings->get_option('saasappoint_minimum_advance_booking_time');

	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$advance_bookingtime.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$saasappoint_minimum_advance_booking_time." minutes", $current_datetime));  
	
	$prev_next_cdate = date("Y-m-01", $currDateTime_withTZ);
	$prev_next_date = date($selected_date, $currDateTime_withTZ);
	$next_month = date("Y-m-01", strtotime('+1 months', strtotime($prev_next_date)));
	$prev_month = date("Y-m-01", strtotime('-1 months', strtotime($prev_next_date)));
	if(strtotime($prev_next_cdate)>strtotime($prev_month)){
		$prev_month = "";
	}
	if(strtotime($next_month)>strtotime($maximum_date)){
		$next_month = "";
	}
	$pn = array('<i class="fa fa-chevron-left"></i>' => $prev_month, '<i class="fa fa-chevron-right"></i>' => $next_month);
	if(isset($_POST["online"])){
		$service_id = $_SESSION['saasappoint_cart_service_id'];
	}else if(isset($_POST["offline"])){
		$service_id = $_SESSION['saasappoint_mb_cart_service_id'];
	}else{
		$service_id = 0;
	}
	echo $obj_calendar->saasappoint_generate_calendar(date('Y', $time), date('n', $time), 3, 1, $pn, $time_interval, $time_format, $advance_bookingtime, $currDateTime_withTZ, $saasappoint_hide_already_booked_slots_from_frontend_calendar, $minimum_date, $maximum_date, $today_date,$service_id);  
	?>
	<div class="saasappoint-radio-group-block saasappoint_available_slots_block">
		<!-- Slots will goes here -->
	</div>
	<?php 
}