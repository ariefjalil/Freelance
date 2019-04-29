<?php 
include 's_header.php';
$saasappoint_date_format = $obj_settings->get_superadmin_option('saasappoint_date_format');
$time_format = $obj_settings->get_superadmin_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');

$sms_plans = $obj_sms_plans->readall_sms_plans_for_superadmin(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">SMS Subscription</li>
      </ol>
	        <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> SMS Plan List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-smsplan-modal"><i class="fa fa-plus"></i> Add Plan</a>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_smsplan_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Plan Name</th>
				  <th>Rate</th>
				  <th>Credit</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				while($plan = mysqli_fetch_array($sms_plans)){ 
					?>
					<tr>
						<td><?php echo $plan['id']; ?></td>
						<td><?php echo ucwords($plan['plan_name']); ?></td>
						<td><?php echo $saasappoint_currency_symbol.$plan['plan_rate']; ?></td>
						<td><?php echo $plan['credit']; ?></td>
						<td>
							<label class="saasappoint-toggle-switch">
							  <input type="checkbox" data-id="<?php echo $plan['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_smsplan_status" <?php if($plan['status'] == "Y"){ echo "checked"; } ?> />
							  <span class="saasappoint-toggle-switch-slider"></span>
							</label>
						</td>
						<td><a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-smsplanmodal" data-id="<?php echo $plan['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> <a data-id="<?php echo $plan['id']; ?>" class="btn btn-danger saasappoint-white btn-sm saasappoint-delete-smsplan-btn"><i class="fa fa-fw fa-trash"></i></a></td>
					</td>
					<?php 
				} 
				?>
			  </tbody>
			</table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint-add-smsplan-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-smsplan-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-smsplan-modal-label">Add SMS Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_smsplan_form" id="saasappoint_add_smsplan_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_smsplanname">SMS Plan Name</label>
				<input class="form-control" id="saasappoint_smsplanname" name="saasappoint_smsplanname" type="text" placeholder="Enter SMS Plan Name" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_smsplanrate">SMS Plan Rate</label>
				<input class="form-control" id="saasappoint_smsplanrate" name="saasappoint_smsplanrate" type="text" placeholder="e.g. 27.99" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_smsplancredit">SMS Credit</label>
				<input class="form-control" id="saasappoint_smsplancredit" name="saasappoint_smsplancredit" type="text" placeholder="e.g. 10" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_smsplanstatus">SMS Plan Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_smsplanstatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_smsplanstatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_smsplan_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-smsplan-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-smsplan-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-smsplan-modal-label">Update SMS Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-smsplan-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_smsplan_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 's_footer.php'; ?>