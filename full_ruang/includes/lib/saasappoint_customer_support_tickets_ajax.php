<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_support_tickets.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_support_ticket_discussions.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_support_tickets = new saasappoint_support_tickets();
$obj_support_tickets->conn = $conn;

$obj_support_ticket_discussions = new saasappoint_support_ticket_discussions();
$obj_support_ticket_discussions->conn = $conn;

/** Generate support ticket ajax **/
if(isset($_POST['generate_support_ticket'])){
	$obj_support_tickets->business_id = $_POST['business_id'];
	$obj_support_tickets->generated_by_id = $_SESSION['customer_id'];
	$obj_support_tickets->ticket_title = filter_var($_POST['ticket_title'], FILTER_SANITIZE_STRING);
	$obj_support_tickets->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	$obj_support_tickets->generated_on = date("Y-m-d H:i:s");
	$obj_support_tickets->generated_by = $_SESSION['login_type'];
	$obj_support_tickets->status = "active";
	$obj_support_tickets->read_status = "U";
	$added = $obj_support_tickets->add_support_ticket();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/** Update support ticket ajax **/
else if(isset($_POST['update_support_ticket'])){
	$obj_support_tickets->id = $_POST['id'];
	$obj_support_tickets->ticket_title = filter_var($_POST['ticket_title'], FILTER_SANITIZE_STRING);
	$obj_support_tickets->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	$obj_support_tickets->read_status = "U";
	$updated = $obj_support_tickets->update_support_ticket();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Mark support ticket as completed ajax **/
else if(isset($_POST['markascomplete_support_ticket'])){
	$obj_support_tickets->id = $_POST['id'];
	$obj_support_tickets->status = "completed";
	$obj_support_tickets->read_status = "U";
	$updated = $obj_support_tickets->markascomplete_support_ticket();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/** Mark support ticket replies as read ajax **/
else if(isset($_POST['markasread_all_support_ticket_reply'])){
	$obj_support_ticket_discussions->replied_by = $_SESSION['login_type'];
	$obj_support_ticket_discussions->ticket_id = $_POST['id'];
	$obj_support_ticket_discussions->markasread_all_support_ticket_reply(); 
}

/** Delete support ticket ajax **/
else if(isset($_POST['delete_support_ticket'])){
	$obj_support_ticket_discussions->ticket_id = $_POST['id'];
	$count_reply = $obj_support_ticket_discussions->count_all_ticket_discussion_reply();
	if($count_reply>0 && $_SESSION['login_type'] != "superadmin"){
		echo "replyexist";
	}else if($_SESSION['login_type'] == "superadmin"){
		$obj_support_ticket_discussions->ticket_id = $_POST['id'];
		$obj_support_ticket_discussions->delete_ticket_discussion_reply();
		$obj_support_tickets->id = $_POST['id'];
		$deleted = $obj_support_tickets->delete_support_ticket();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		$obj_support_tickets->id = $_POST['id'];
		$deleted = $obj_support_tickets->delete_support_ticket();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}
}

/** Update support ticket modal detail ajax **/
else if(isset($_POST['update_supportticket_modal_detail'])){
	$obj_support_tickets->id = $_POST['id'];
	$support_ticket_detail = $obj_support_tickets->readone_support_ticket(); 
	?>
	<form name="saasappoint_update_support_ticket_form" id="saasappoint_update_support_ticket_form" method="post">
	  <div class="form-group">
		<label for="saasappoint_update_tickettitle">Ticket Title</label>
		<input class="form-control" id="saasappoint_update_tickettitle" name="saasappoint_update_tickettitle" type="text" placeholder="Enter Ticket Title" value="<?php echo $support_ticket_detail['ticket_title']; ?>" />
	  </div>
	  <div class="form-group">
		<label for="saasappoint_update_ticketdescription">Ticket Description</label>
		<textarea class="form-control" id="saasappoint_update_ticketdescription" name="saasappoint_update_ticketdescription" placeholder="Enter Ticket Description" rows="7"><?php echo $support_ticket_detail['description']; ?></textarea>
	  </div>
	</form>
	<?php 
}