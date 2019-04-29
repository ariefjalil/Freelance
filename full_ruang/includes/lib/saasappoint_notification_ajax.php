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
$datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;

if(isset($_POST['get_notification_appointment_detail'])){
	?>
	<center><h5 class="dropdown-menu-titles">New Appointments</h5></center>
	<div class="dropdown-divider"></div>
	<?php
	$all_appointments = $obj_bookings->get_all_latest_unread_appointments();
	$status_array = array(
		'pending' => array(
			"status" => "Pending",
			"icon" => '<i class="fa fa-info-circle fa-fw" title="Pending"></i>',
			"class" => 'saasappoint_noti_pending'
		),
		'confirmed' => array(
			"status" => "Confirmed",
			"icon" => '<i class="fa fa-check fa-fw" title="Confirmed"></i>',
			"class" => 'saasappoint_noti_confirmed'
		),
		'rescheduled_by_customer' => array(
			"status" => "Rescheduled By Customer",
			"icon" => '<i class="fa fa-refresh fa-fw" title="Rescheduled By Customer"></i>',
			"class" => 'saasappoint_noti_rescheduled_by_customer'
		),
		'rescheduled_by_you' => array(
			"status" => "Rescheduled By You",
			"icon" => '<i class="fa fa-repeat fa-fw" title="Rescheduled By You"></i>',
			"class" => 'saasappoint_noti_rescheduled_by_you'
		),
		'cancelled_by_customer' => array(
			"status" => "Cancelled By Customer",
			"icon" => '<i class="fa fa-close fa-fw" title="Cancelled By Customer"></i>',
			"class" => 'saasappoint_noti_cancelled_by_customer'
		),
		'rejected_by_you' => array(
			"status" => "Rejected By You",
			"icon" => '<i class="fa fa-ban fa-fw" title="Rejected By You"></i>',
			"class" => 'saasappoint_noti_rejected_by_you'
		),
		'completed' => array(
			"status" => "Completed",
			"icon" => '<i class="fa fa-calendar-check-o fa-fw" title="Completed"></i>',
			"class" => 'saasappoint_noti_completed'
		)
	);
	if(mysqli_num_rows($all_appointments)>0){
		while($appointment = mysqli_fetch_array($all_appointments)){
			$customer_name = ucwords($appointment['c_firstname']." ".$appointment['c_lastname']);
			$event_title = "<b>".$appointment['cat_name'].":</b> ".$appointment['title']." with ".$customer_name." on <b>".date($datetime_format, strtotime($appointment['booking_datetime']))."</b>";
			?>
			<div class="saasappoint-notification-appointment-modal-link" data-id="<?php echo $appointment['order_id']; ?>">
				<div class="row">
					<div class="col-md-12">
						<strong class="<?php echo $status_array[$appointment['booking_status']]['class']; ?>"><?php echo $status_array[$appointment['booking_status']]['icon']; echo $status_array[$appointment['booking_status']]['status']; ?></strong>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="saasappoint_noti_deatil"><?php echo $event_title; ?></span>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<?php
		}
	}else{
		?>
		<center>Opps!, You have no unread notifications.</center>
		<div class="dropdown-divider"></div>
		<?php
	}
}
else if(isset($_POST['mark_appointment_as_read'])){
	$obj_bookings->order_id = $_POST['order_id'];
	$updated = $obj_bookings->mark_appointment_as_read();
	if($updated){
		echo "updated";
	}
}