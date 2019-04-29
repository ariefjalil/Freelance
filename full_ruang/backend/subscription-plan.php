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

$subscription_plans = $obj_subscription_plans->readall_subscription_plans_superadmin(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Subscription</li>
      </ol>
	        <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> Subscription Plan List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-splan-modal"><i class="fa fa-plus"></i> Add Plan</a>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_splan_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Plan Name</th>
				  <th>Rate</th>
				  <th>Period</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				while($plan = mysqli_fetch_array($subscription_plans)){ 
					?>
					<tr>
						<td><?php echo $plan['id']; ?></td>
						<td><?php echo $plan['plan_name']; ?></td>
						<td><?php echo $saasappoint_currency_symbol.$plan['plan_rate']; ?></td>
						<td><?php 
							if($plan['renewal_type'] == "monthly"){
								$year_month = "Month";
							}else{
								$year_month = "Year";
							}
							if($plan['plan_period'] > 1){ 
								echo $plan['plan_period']." ".$year_month."s"; 
							}else{ 
								echo $plan['plan_period']." ".$year_month; 
							} ?></td>
						<td>
							<label class="saasappoint-toggle-switch">
							  <input type="checkbox" data-id="<?php echo $plan['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_splan_status" <?php if($plan['status'] == "Y"){ echo "checked"; } ?> />
							  <span class="saasappoint-toggle-switch-slider"></span>
							</label>
						</td>
						<td><a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-splanmodal" data-id="<?php echo $plan['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> <a data-id="<?php echo $plan['id']; ?>" class="btn btn-danger saasappoint-white btn-sm saasappoint-delete-splan-btn"><i class="fa fa-fw fa-trash"></i></a></td>
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
	<div class="modal fade" id="saasappoint-add-splan-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-splan-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-splan-modal-label">Add Subscription Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_splan_form" id="saasappoint_add_splan_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_splanname">Subscription Plan Name</label>
				<input class="form-control" id="saasappoint_splanname" name="saasappoint_splanname" type="text" placeholder="Enter Subscription Plan Name" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_splanrate">Subscription Plan Rate</label>
				<input class="form-control" id="saasappoint_splanrate" name="saasappoint_splanrate" type="text" placeholder="e.g. 27.99" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_splanperiod">Subscription Plan Period</label>
				<input class="form-control" id="saasappoint_splanperiod" name="saasappoint_splanperiod" type="text" placeholder="e.g. 3" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_splantype">Subscription Plan Type</label>
				<select class="form-control" name="saasappoint_splantype" id="saasappoint_splantype">
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
				</select>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_splanstatus">Subscription Plan Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_splanstatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_splanstatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_splan_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-splan-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-splan-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-splan-modal-label">Update Subscription Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-splan-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_splan_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 's_footer.php'; ?>