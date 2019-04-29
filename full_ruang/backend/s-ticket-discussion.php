<?php 
include 's_header.php'; 
include(dirname(dirname(__FILE__))."/classes/class_support_tickets.php");
include(dirname(dirname(__FILE__))."/classes/class_support_ticket_discussions.php");

if(!isset($_GET['tid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/s-support-tickets.php";
	</script>
	<?php 
	exit;
}
$tid = $_GET['tid'];

$obj_support_tickets = new saasappoint_support_tickets();
$obj_support_tickets->conn = $conn;
$obj_support_tickets->id = $tid;

$obj_support_ticket_discussions = new saasappoint_support_ticket_discussions();
$obj_support_ticket_discussions->conn = $conn;
$obj_support_ticket_discussions->ticket_id = $tid;

$saasappoint_date_format = $obj_settings->get_superadmin_option('saasappoint_date_format');
$time_format = $obj_settings->get_superadmin_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format; 
$support_ticket_detail = $obj_support_tickets->readone_support_ticket(); 
if(sizeof($support_ticket_detail)==0){ 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/s-support-tickets.php";
	</script>
	<?php  
	exit;
} 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/s-support-tickets.php">Support Tickets</a>
        </li>
        <li class="breadcrumb-item active">Discussion</li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-comments-o"></i> Discussion on Ticket
		  <a class="btn btn-primary btn-sm saasappoint-white pull-right" href="<?php echo SITE_URL; ?>backend/s-support-tickets.php"><i class="fa fa-angle-double-left"></i> Back to Support Tickets</a>
		</div>
        <div class="card-body">
			<div class="saasappoint_support_ticket_window">
				<div class="saasappoint_support_ticket_topmenu">
					<p class="saasappoint_support_ticket_topmenu_datetime col-md-12"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date($saasappoint_datetime_format, strtotime($support_ticket_detail['generated_on'])); ?></p>
					<div class="saasappoint_support_ticket_topmenu_title"><?php echo ucfirst($support_ticket_detail['ticket_title']); ?></div>
					<p class="saasappoint_support_ticket_topmenu_description">&nbsp; &nbsp; &nbsp; &nbsp; <?php echo ucfirst($support_ticket_detail['description']); ?></p>
				</div>
				<ul class="saasappoint_support_ticket_reply_list">
					<?php 
					$get_all_replies = $obj_support_ticket_discussions->get_all_support_ticket_replies();
					if(mysqli_num_rows($get_all_replies)>0){
						while($discussion_detail = mysqli_fetch_assoc($get_all_replies)){ 
							?>
							<li class="saasappoint_support_ticket_reply saasappoint_show_support_ticket_reply <?php if($_SESSION['login_type'] == $discussion_detail['replied_by']){ echo "saasappoint_show_support_ticket_on_right"; }else{ echo "saasappoint_show_support_ticket_on_left"; } ?>">
								<div class="saasappoint_support_ticket_reply_wrapper">
									<p class="pull-left col-md-12"><i class="fa fa-user" aria-hidden="true"></i> <?php 
									if($_SESSION['login_type'] == $discussion_detail['replied_by']){
										echo "You";
									}else{
										$obj_support_ticket_discussions->replied_by_id = $discussion_detail['replied_by_id'];
										$obj_support_ticket_discussions->replied_by = $discussion_detail['replied_by'];
										echo $obj_support_ticket_discussions->get_support_ticket_replied_by_name();
									} 
									?></p>
									<div class="saasappoint_support_ticket_reply_wrapper_content"><?php echo $discussion_detail['reply']; ?></div>
									<p class="pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date($saasappoint_datetime_format, strtotime($discussion_detail['replied_on'])); ?></p>
								</div>
							</li>
							<?php 
						} 
					}else{
						?>
						<li class="saasappoint_remove_empty_discussion_li">
							There is no discussion on this ticket yet...
						</li>
						<?php 
					} 
					?>
				</ul>
				<?php 
				if($support_ticket_detail['status'] == "active"){ 
					?>
					<div class="saasappoint_support_ticket_send_reply_wrapper clearfix">
						<div class="saasappoint_support_ticket_send_reply_input_wrapper">
							<input class="saasappoint_support_ticket_reply_input" data-id="<?php echo $tid; ?>" placeholder="Type here..." />
						</div>
						<div class="saasappoint_support_ticket_send_reply_btndiv" data-id="<?php echo $tid; ?>">
							<div class="saasappoint_support_ticket_send_reply_btnicon"></div>
							<div class="saasappoint_support_ticket_reply_wrapper_content">Send</div>
						</div>
					</div>
					<?php 
				} 
				?>
			</div>
			<div class="saasappoint_support_ticket_reply_template">
				<li class="saasappoint_support_ticket_reply">
					<div class="saasappoint_support_ticket_reply_wrapper">
						<p class="pull-left col-md-12"><i class="fa fa-user" aria-hidden="true"></i> You</p>
						<div class="saasappoint_support_ticket_reply_wrapper_content"></div>
						<p class="pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> Just Now</p>
					</div>
				</li>
			</div>
        </div>
      </div>
<?php include 's_footer.php'; ?>