<?php 
include 'header.php'; 
$all_refund_requests = $obj_refund_request->readall_refund_request_detail(); 

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
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Refund Request & Settings</li>
      </ol>
	  <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_refund_request_list"><i class="fa fa-exchange"></i> Refund Request</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#saasappoint_refund_settings"><i class="fa fa-cog"></i> Refund Settings</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_refund_request_list">
					  <br />
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table id="saasappoint_refund_request_list_table" width="100%" cellspacing="0">
							  <thead>
								<tr>
								  <th>#</th>
								  <th>Customer Name</th>
								  <th>Customer Email</th>
								  <th>Customer Phone</th>
								  <th>Appointment Detail</th>
								  <th>Refund Amount</th>
								  <th>Requested On</th>
								  <th>Status</th>
								  <th>Refund Request</th>
								</tr>
							  </thead>
							  <tbody>
								<?php 
								if(mysqli_num_rows($all_refund_requests)>0){
									while($refund_request = mysqli_fetch_array($all_refund_requests)){ 
										$appointment = $obj_refund_request->get_appointment_detail_by_order_id($refund_request["order_id"]); 
										?>
										<tr>
										  <td><?php echo $refund_request['order_id']; ?></td>
										  <td><?php echo ucwords($appointment['c_firstname']." ".$appointment['c_lastname']); ?></td>
										  <td><?php echo $appointment['c_email']; ?> </td>
										  <td><?php echo $appointment['c_phone']; ?> </td>
										  <td>
											<?php echo ucwords($appointment['cat_name']." - ".$appointment['title'])." on ".date($saasappoint_datetime_format, strtotime($appointment["booking_datetime"]));  
											?>
										  </td>
										  <td><?php echo $saasappoint_currency_symbol.$refund_request['amount']; ?></td>
										  <td><?php echo date($saasappoint_datetime_format, strtotime($refund_request['requested_on'])); ?></td>
										  <td><?php if($refund_request['status'] == "refunded"){ ?><label class="text-success"><?php echo ucwords($refund_request['status']); ?></label><?php }else if($refund_request['status'] == "cancelled_by_admin"){ ?><label class="text-primary"><?php echo "Cancelled by You"; ?></label><?php }else if($refund_request['status'] == "cancelled_by_customer"){ ?><label class="text-danger"><?php echo "Cancelled by Customer"; ?></label><?php }else{ ?><label class="text-primary"><?php echo ucwords($refund_request['status']); ?></label><?php } ?></td>
										  <td>
											<?php if($refund_request['status'] == "pending"){ ?>
												<a class="btn btn-success btn-sm saasappoint_markasrefunded_btn" href="javascript:void(0);" data-id="<?php echo $refund_request['id']; ?>"><i class="fa fa-fw fa-exchange"></i></a> <a class="btn btn-danger btn-sm saasappoint_cancel_refundrequest_btn" href="javascript:void(0);" data-id="<?php echo $refund_request['id']; ?>"><i class="fa fa-fw fa-ban"></i></a>
											<?php 
											}else if($refund_request['status'] == "refunded"){
												echo '<i class="fa fa-fw fa-2x text-success fa-exchange"></i>';
											}else if($refund_request['status'] == "cancelled_by_customer"){
												echo '<i class="fa fa-fw fa-ban text-danger fa-2x"></i>'; 
											}else{
												echo '<i class="fa fa-fw fa-minus-circle text-primary fa-2x"></i>'; 
											} ?>
										  </td>
										</tr>
										<?php 
									} 
								} 
								?>
							  </tbody>
						   </table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_refund_settings">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
							<form name="saasappoint_refund_settings_form" id="saasappoint_refund_settings_form" method="post">
								<div class="row">
									<label class="col-md-2">Allow Refund?</label>
									<label class="saasappoint-toggle-switch">
										<input type="checkbox" name="saasappoint_refund_status" id="saasappoint_refund_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_refund_status")=="Y"){ echo "checked"; } ?> />
										<span class="saasappoint-toggle-switch-slider"></span>
									</label>
								</div>
								<hr />
								<div class="form-group row">								
									<div class="col-md-3">
										<label class="control-label">Refund Type</label>
										<?php $saasappoint_refund_type = $obj_settings->get_option("saasappoint_refund_type"); ?>
										<select name="saasappoint_refund_type" id="saasappoint_refund_type" class="form-control selectpicker">
										  <option value="percentage" <?php if($saasappoint_refund_type == "percentage"){ echo "selected"; } ?>>Percentage</option>
										  <option value="flat" <?php if($saasappoint_refund_type == "flat"){ echo "selected"; } ?>>Flat</option>
										</select>
									</div>
									<div class="col-md-3">
										<label class="control-label">Refund Value</label>
										<input type="text" name="saasappoint_refund_value" id="saasappoint_refund_value" placeholder="e.g. 10" class="form-control" value="<?php echo $obj_settings->get_option("saasappoint_refund_value"); ?>" />
									</div>
									<div class="col-md-6">
										<label class="control-label">Refund Request Buffer Time</label>
										<?php $saasappoint_refund_request_buffer_time = $obj_settings->get_option("saasappoint_refund_request_buffer_time"); ?>
										<select name="saasappoint_refund_request_buffer_time" id="saasappoint_refund_request_buffer_time" class="form-control selectpicker">
										  <option value="20" <?php if($saasappoint_refund_request_buffer_time == "20"){ echo "selected"; } ?>>20 Minutes</option>
										  <option value="30" <?php if($saasappoint_refund_request_buffer_time == "30"){ echo "selected"; } ?>>30 Minutes</option>
										  <option value="45" <?php if($saasappoint_refund_request_buffer_time == "45"){ echo "selected"; } ?>>45 Minutes</option>
										  <option value="60" <?php if($saasappoint_refund_request_buffer_time == "60"){ echo "selected"; } ?>>1 Hour</option>
										  <option value="75" <?php if($saasappoint_refund_request_buffer_time == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
										  <option value="90" <?php if($saasappoint_refund_request_buffer_time == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
										  <option value="105" <?php if($saasappoint_refund_request_buffer_time == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
										  <option value="120" <?php if($saasappoint_refund_request_buffer_time == "120"){ echo "selected"; } ?>>2 Hour</option>
										  <option value="135" <?php if($saasappoint_refund_request_buffer_time == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
										  <option value="150" <?php if($saasappoint_refund_request_buffer_time == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
										  <option value="165" <?php if($saasappoint_refund_request_buffer_time == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
										  <option value="180" <?php if($saasappoint_refund_request_buffer_time == "180"){ echo "selected"; } ?>>3 Hour</option>
										  <option value="195" <?php if($saasappoint_refund_request_buffer_time == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
										  <option value="210" <?php if($saasappoint_refund_request_buffer_time == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
										  <option value="225" <?php if($saasappoint_refund_request_buffer_time == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
										  <option value="240" <?php if($saasappoint_refund_request_buffer_time == "240"){ echo "selected"; } ?>>4 Hour</option>
										  <option value="300" <?php if($saasappoint_refund_request_buffer_time == "300"){ echo "selected"; } ?>>5 Hour</option>
										  <option value="360" <?php if($saasappoint_refund_request_buffer_time == "360"){ echo "selected"; } ?>>6 Hour</option>
										  <option value="420" <?php if($saasappoint_refund_request_buffer_time == "420"){ echo "selected"; } ?>>7 Hour</option>
										  <option value="480" <?php if($saasappoint_refund_request_buffer_time == "480"){ echo "selected"; } ?>>8 Hour</option>
										  <option value="540" <?php if($saasappoint_refund_request_buffer_time == "540"){ echo "selected"; } ?>>9 Hour</option>
										  <option value="600" <?php if($saasappoint_refund_request_buffer_time == "600"){ echo "selected"; } ?>>10 Hour</option>
										  <option value="660" <?php if($saasappoint_refund_request_buffer_time == "660"){ echo "selected"; } ?>>11 Hour</option>
										  <option value="720" <?php if($saasappoint_refund_request_buffer_time == "720"){ echo "selected"; } ?>>12 Hour</option>
										  <option value="1440" <?php if($saasappoint_refund_request_buffer_time == "1440"){ echo "selected"; } ?>>24 Hour</option>
										  <option value="2160" <?php if($saasappoint_refund_request_buffer_time == "2160"){ echo "selected"; } ?>>36 Hour</option>
										  <option value="2880" <?php if($saasappoint_refund_request_buffer_time == "2880"){ echo "selected"; } ?>>48 Hour</option>
										  <option value="3600" <?php if($saasappoint_refund_request_buffer_time == "3600"){ echo "selected"; } ?>>60 Hour</option>
										  <option value="4320" <?php if($saasappoint_refund_request_buffer_time == "4320"){ echo "selected"; } ?>>72 Hour</option>
										</select>
									</div>
								</div>
								<hr />
								<div class="form-group row">
									<div class="col-md-12">
										<label class="control-label">Refund Policy</label>
										<textarea name="saasappoint_refund_summary" class="saasappoint_refund_summary saasappoint_text_editor_container" id="saasappoint_refund_summary" autocomplete="off"><?php echo base64_decode($obj_settings->get_option("saasappoint_refund_summary")); ?></textarea>
									</div>
								</div>
								<a id="update_refund_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
							</form>
						</div>
					  </div>
				    </div>
				</div>
			</div>
		</div>
	 </div>
<?php include 'footer.php'; ?>