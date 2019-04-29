<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_refund_request.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_refund_request = new saasappoint_refund_request();
$obj_refund_request->conn = $conn;
$obj_refund_request->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

/* Mark as refunded appointment ajax */
if(isset($_POST['markasrefunded_appointment'])){
	$obj_refund_request->id = $_POST['id'];
	$obj_refund_request->status = "refunded";
	$obj_refund_request->read_status = "U";
	$status_changed = $obj_refund_request->change_refund_request_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

/* cancel refund request ajax */
else if(isset($_POST['cancel_refundrequest'])){
	$obj_refund_request->id = $_POST['id'];
	$obj_refund_request->status = "cancelled_by_admin";
	$obj_refund_request->read_status = "U";
	$status_changed = $obj_refund_request->change_refund_request_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

/** Refund request notifications detail modal content */
else if(isset($_POST['get_refund_request_detail'])){ 
	$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
	$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
	$time_format = $obj_settings->get_option('saasappoint_time_format');
	if($time_format == "24"){
		$saasappoint_time_format = "H:i";
	}else{
		$saasappoint_time_format = "h:i A";
	}
	$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format; 
	?>
	<center><h5 class="dropdown-menu-titles">New Refund Request</h5></center>
	<div class="dropdown-divider"></div>
	<?php
	$all_refund_request = $obj_refund_request->readall_unread_refund_requests();
	$status_array = array(
		'pending' => array(
			"status" => "Pending",
			"icon" => '<i class="fa fa-info-circle fa-fw" title="Pending"></i>',
			"class" => 'saasappoint_noti_pending'
		),
		'cancelled_by_customer' => array(
			"status" => "Cancelled By Customer",
			"icon" => '<i class="fa fa-close fa-fw" title="Cancelled By Customer"></i>',
			"class" => 'saasappoint_noti_cancelled_by_customer'
		),
		'cancelled_by_admin' => array(
			"status" => "Cancelled by You",
			"icon" => '<i class="fa fa-ban fa-fw" title="Cancelled by You"></i>',
			"class" => 'saasappoint_noti_rejected_by_you'
		),
		'refunded' => array(
			"status" => "Refunded",
			"icon" => '<i class="fa fa-exchange fa-fw" title="Refunded"></i>',
			"class" => 'text-success'
		)
	);
	if(mysqli_num_rows($all_refund_request)>0){
		while($refundrequest = mysqli_fetch_array($all_refund_request)){
			$appointment = $obj_refund_request->get_appointment_detail_by_order_id($refundrequest["order_id"]);
			$customer_name = ucwords($appointment['c_firstname']." ".$appointment['c_lastname']);			
			if($appointment["booking_status"] == "rejected_by_you"){
				$event_title = "<p>You have requested refund for <b>".$customer_name."</b> on <b>".date($saasappoint_datetime_format, strtotime($refundrequest["requested_on"]))."</b><br />Refund amount: <b>".$saasappoint_currency_symbol.$refundrequest['amount']."</b></p>"; 
			}else{
				$event_title = "<p><b>".$customer_name."</b> raised refund request on <b>".date($saasappoint_datetime_format, strtotime($refundrequest["requested_on"]))."</b><br />Refund amount: <b>".$saasappoint_currency_symbol.$refundrequest['amount']."</b></p>"; 
			} 
			?>
			<div class="saasappoint-notification-refundrequest-modal-link" data-id="<?php echo $refundrequest['id']; ?>">
				<div class="row">
					<div class="col-md-12">
						<strong class="<?php echo $status_array[$refundrequest['status']]['class']; ?>"><?php echo $status_array[$refundrequest['status']]['icon']." ".$status_array[$refundrequest['status']]['status']; ?></strong>
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
		<center>Opps!, You have no unread refund request.</center>
		<div class="dropdown-divider"></div>
		<?php
	}
}

else if(isset($_POST['mark_refund_request_as_read'])){
	$obj_refund_request->id = $_POST['id'];
	$obj_refund_request->read_status = 'R';
	$updated = $obj_refund_request->mark_as_read_refund_request_status();
	if($updated){
		echo "updated";
	}
}