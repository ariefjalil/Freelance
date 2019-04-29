<?php 
include 'c_header.php'; 
include(dirname(dirname(__FILE__))."/classes/class_support_tickets.php");
include(dirname(dirname(__FILE__))."/classes/class_support_ticket_discussions.php");

$obj_support_tickets = new saasappoint_support_tickets();
$obj_support_tickets->conn = $conn;

$obj_support_ticket_discussions = new saasappoint_support_ticket_discussions();
$obj_support_ticket_discussions->conn = $conn;

$saasappoint_date_format = $obj_settings->get_superadmin_option('saasappoint_date_format');
$time_format = $obj_settings->get_superadmin_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Support Tickets</li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-comments"></i> Support Ticket List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-generate-ticket-modal"><i class="fa fa-plus"></i> Generate Ticket</a>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_support_ticket_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Ticket Title</th>
				  <th>Generated On</th>
				  <th>Generated For</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$obj_support_tickets->generated_by_id = $_SESSION['customer_id'];
				$all_support_tickets = $obj_support_tickets->get_all_support_tickets_of_customer();
				$i = 1;
				while($support_ticket = mysqli_fetch_array($all_support_tickets)){ 
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php if(strlen($support_ticket['ticket_title']) < 30){ echo ucfirst($support_ticket['ticket_title']); }else{ echo substr(ucfirst($support_ticket['ticket_title']), 0, 30)."..."; } ?></td>
						<td><?php echo date($saasappoint_datetime_format, strtotime($support_ticket['generated_on'])); ?></td>
						<td>
							<?php 
							$obj_settings->business_id = $support_ticket['business_id'];
							echo ucwords($obj_settings->get_option("saasappoint_company_name")); 
							?>
						</td>
						<td><?php if($support_ticket['status'] == "active"){ ?><label class="text-primary"><?php echo ucwords($support_ticket['status']); ?></label><?php }else{ ?><label class="text-success"><?php echo ucwords($support_ticket['status']); ?></label><?php } ?></td>
						<td>
							<?php 
							$obj_support_ticket_discussions->ticket_id = $support_ticket['id'];
							$reply_count = $obj_support_ticket_discussions->count_all_ticket_discussion_reply();
							$obj_support_ticket_discussions->replied_by = $_SESSION['login_type'];
							$unread_reply_count = $obj_support_ticket_discussions->count_all_unread_ticket_discussion_reply();
							if($support_ticket['status'] != "completed" && ($reply_count==0 || $_SESSION['login_type'] == "superadmin")){ 
								?>
								<a href="javascript:void(0);" class="btn btn-primary saasappoint-white btn-sm saasappoint-update-supportticketmodal" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> &nbsp; 
								<a href="javascript:void(0);" class="btn btn-danger saasappoint-white btn-sm saasappoint_delete_support_ticket_btn" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-trash"></i></a> &nbsp; 
								<?php 
							} 
							?>
							<a class="btn btn-warning btn-sm markasread_all_support_ticket_reply" href="javascript:void(0)" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-eye"></i> <?php if($unread_reply_count>0){ ?><span class="badge badge-success"><?php echo $unread_reply_count; ?></span><?php } ?></a> &nbsp; 
							<?php 
							if($support_ticket['status'] == "active"){ 
							?>
							<a class="btn btn-success btn-sm saasappoint_markascomplete_support_ticket_btn" href="javascript:void(0);" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-check-square-o" aria-hidden="true"></i></a>
							<?php 
							} 
							?>
						</td>
					</tr>
					<?php 
					$i++;
				} 
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint-generate-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-generate-ticket-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-generate-ticket-modal-label">Generate Support Ticket</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_generate_support_ticket_form" id="saasappoint_generate_support_ticket_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_ticket_business">Generate Ticket for</label>
				<select class="form-control" id="saasappoint_ticket_business" name="saasappoint_ticket_business">
					<?php 
					$i = 1;
					$obj_support_tickets->customer_id = $_SESSION['customer_id'];
					$all_businesses = $obj_support_tickets->get_customer_support_ticket_business_ids();
					while($business_detail = mysqli_fetch_assoc($all_businesses)){ 
						$business_id = $business_detail['business_id'];
						$obj_settings->business_id = $business_id;
						$business_name = ucwords($obj_settings->get_option("saasappoint_company_name"));
						$selected = "";
						if($i==1){
							$selected = "selected";
						}
						echo '<option value="'.$business_id.'" '.$selected.'>'.$business_name.'</option>';
						$i++;
					} 
					?>
				</select>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_tickettitle">Ticket Title</label>
				<input class="form-control" id="saasappoint_tickettitle" name="saasappoint_tickettitle" type="text" placeholder="Enter Ticket Title" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_ticketdescription">Ticket Description</label>
				<textarea class="form-control" id="saasappoint_ticketdescription" name="saasappoint_ticketdescription" placeholder="Enter Ticket Description" rows="7"></textarea>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_generate_support_ticket_btn" class="btn btn-primary" href="javascript:void(0);">Generate</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-ticket-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-ticket-modal-label">Update Support Ticket</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-ticket-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_support_ticket_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 'c_footer.php'; ?>