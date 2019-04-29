<?php 
include 'header.php';
include 'currency.php'; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Settings</li>
      </ol>
	  <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_general_settings"><i class="fa fa-home"></i> Company Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#saasappoint_appearance_settings"><i class="fa fa-tachometer"></i> Appearance Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="2" data-toggle="tab" href="#saasappoint_payment_settings"><i class="fa fa-money"></i> Payment Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="3" data-toggle="tab" href="#saasappoint_email_settings"><i class="fa fa-envelope"></i> Email Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="4" data-toggle="tab" href="#saasappoint_sms_settings"><i class="fa fa-comments"></i> SMS Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="5" data-toggle="tab" href="#saasappoint_seo_settings"><i class="fa fa-line-chart"></i> SEO Settings</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="6" data-toggle="tab" href="#saasappoint_welcome_settings"><i class="fa fa-handshake-o"></i> Welcome Message</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="7" data-toggle="tab" href="#saasappoint_bookingform_settings"><i class="fa fa-laptop"></i> Booking Form</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_general_settings">
					  <div class="row">
						<div class="col-md-12">
						  <form name="saasappoint_company_settings_form" id="saasappoint_company_settings_form" method="post">
							  <div class="form-group">
								<div class="saasappoint-image-upload">
									<div class="saasappoint-image-edit-icon">
										<input type='hidden' id="saasappoint-image-upload-file-hidden" name="saasappoint-image-upload-file-hidden" />
										<input type='file' id="saasappoint-image-upload-file" accept=".png, .jpg, .jpeg" />
										<label for="saasappoint-image-upload-file"></label>
									</div>
									<div class="saasappoint-image-preview">
										<div id="saasappoint-image-upload-file-preview" style="<?php $logo_image = $obj_settings->get_option("saasappoint_company_logo"); if($logo_image != '' && file_exists("../includes/images/".$logo_image)){ echo "background-image: url(".SITE_URL."includes/images/".$logo_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/logo-placeholder.png);"; } ?>">
										</div>
									</div>
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Company Name</label>
									<input name="saasappoint_company_name" id="saasappoint_company_name" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_name"); ?>" placeholder="Enter Name" />
								</div>
								<div class="col-md-4">
									<label class="control-label">Company Email</label>
									<input name="saasappoint_company_email" id="saasappoint_company_email" class="form-control" type="email" value="<?php echo $obj_settings->get_option("saasappoint_company_email"); ?>" placeholder="Enter Email" />
								</div>
								<div class="col-md-4">
									<label class="control-label">Company Phone</label>
									<input name="saasappoint_company_phone" id="saasappoint_company_phone" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_phone"); ?>" placeholder="Enter Phone" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-12">
									<label class="control-label">Company Address</label>
									<textarea name="saasappoint_company_address" id="saasappoint_company_address" class="form-control" rows="1" placeholder="Enter Address" ><?php echo $obj_settings->get_option("saasappoint_company_address"); ?></textarea>
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">City</label>
									<input name="saasappoint_company_city" id="saasappoint_company_city" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_city"); ?>" placeholder="Enter City" />
								</div>
								<div class="col-md-6">
									<label class="control-label">State</label>
									<input name="saasappoint_company_state" id="saasappoint_company_state" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_state"); ?>" placeholder="Enter State" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">Zip</label>
									<input name="saasappoint_company_zip" id="saasappoint_company_zip" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_zip"); ?>" placeholder="Enter Zip" />
								</div>
								<div class="col-md-6">
									<label class="control-label">Country</label>
									<input name="saasappoint_company_country" id="saasappoint_company_country" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_company_country"); ?>" placeholder="Enter Country" />
								</div>
							  </div>
							  <a id="update_company_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						 </form>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_appearance_settings">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <form name="saasappoint_appearance_settings_form" id="saasappoint_appearance_settings_form" method="post">
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Time Slot Interval</label>
									<?php $saasappoint_timeslot_interval = $obj_settings->get_option("saasappoint_timeslot_interval"); ?>
									<select name="saasappoint_timeslot_interval" id="saasappoint_timeslot_interval" class="form-control selectpicker">
									  <option value="5" <?php if($saasappoint_timeslot_interval == "5"){ echo "selected"; } ?>>5 Minutes</option>
									  <option value="10" <?php if($saasappoint_timeslot_interval == "10"){ echo "selected"; } ?>>10 Minutes</option>
									  <option value="15" <?php if($saasappoint_timeslot_interval == "15"){ echo "selected"; } ?>>15 Minutes</option>
									  <option value="20" <?php if($saasappoint_timeslot_interval == "20"){ echo "selected"; } ?>>20 Minutes</option>
									  <option value="30" <?php if($saasappoint_timeslot_interval == "30"){ echo "selected"; } ?>>30 Minutes</option>
									  <option value="45" <?php if($saasappoint_timeslot_interval == "45"){ echo "selected"; } ?>>45 Minutes</option>
									  <option value="60" <?php if($saasappoint_timeslot_interval == "60"){ echo "selected"; } ?>>1 Hour</option>
									  <option value="75" <?php if($saasappoint_timeslot_interval == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
									  <option value="90" <?php if($saasappoint_timeslot_interval == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
									  <option value="105" <?php if($saasappoint_timeslot_interval == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
									  <option value="120" <?php if($saasappoint_timeslot_interval == "120"){ echo "selected"; } ?>>2 Hour</option>
									  <option value="135" <?php if($saasappoint_timeslot_interval == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
									  <option value="150" <?php if($saasappoint_timeslot_interval == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
									  <option value="165" <?php if($saasappoint_timeslot_interval == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
									  <option value="180" <?php if($saasappoint_timeslot_interval == "180"){ echo "selected"; } ?>>3 Hour</option>
									  <option value="195" <?php if($saasappoint_timeslot_interval == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
									  <option value="210" <?php if($saasappoint_timeslot_interval == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
									  <option value="225" <?php if($saasappoint_timeslot_interval == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
									  <option value="240" <?php if($saasappoint_timeslot_interval == "240"){ echo "selected"; } ?>>4 Hour</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">End Time Slot Selection</label>
									<?php $saasappoint_endtimeslot_selection_status = $obj_settings->get_option("saasappoint_endtimeslot_selection_status"); ?>
									<select name="saasappoint_endtimeslot_selection_status" id="saasappoint_endtimeslot_selection_status" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_endtimeslot_selection_status == "Y"){ echo "selected"; } ?>>Enable</option>
									  <option value="N" <?php if($saasappoint_endtimeslot_selection_status == "N"){ echo "selected"; } ?>>Disable</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Maximum End Time Slot Limit</label>
									<?php $saasappoint_maximum_endtimeslot_limit = $obj_settings->get_option("saasappoint_maximum_endtimeslot_limit"); ?>
									<select name="saasappoint_maximum_endtimeslot_limit" id="saasappoint_maximum_endtimeslot_limit" class="form-control selectpicker">
									  <option value="5" <?php if($saasappoint_maximum_endtimeslot_limit == "5"){ echo "selected"; } ?>>5 Minutes</option>
									  <option value="10" <?php if($saasappoint_maximum_endtimeslot_limit == "10"){ echo "selected"; } ?>>10 Minutes</option>
									  <option value="15" <?php if($saasappoint_maximum_endtimeslot_limit == "15"){ echo "selected"; } ?>>15 Minutes</option>
									  <option value="20" <?php if($saasappoint_maximum_endtimeslot_limit == "20"){ echo "selected"; } ?>>20 Minutes</option>
									  <option value="30" <?php if($saasappoint_maximum_endtimeslot_limit == "30"){ echo "selected"; } ?>>30 Minutes</option>
									  <option value="45" <?php if($saasappoint_maximum_endtimeslot_limit == "45"){ echo "selected"; } ?>>45 Minutes</option>
									  <option value="60" <?php if($saasappoint_maximum_endtimeslot_limit == "60"){ echo "selected"; } ?>>1 Hour</option>
									  <option value="75" <?php if($saasappoint_maximum_endtimeslot_limit == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
									  <option value="90" <?php if($saasappoint_maximum_endtimeslot_limit == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
									  <option value="105" <?php if($saasappoint_maximum_endtimeslot_limit == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
									  <option value="120" <?php if($saasappoint_maximum_endtimeslot_limit == "120"){ echo "selected"; } ?>>2 Hour</option>
									  <option value="135" <?php if($saasappoint_maximum_endtimeslot_limit == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
									  <option value="150" <?php if($saasappoint_maximum_endtimeslot_limit == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
									  <option value="165" <?php if($saasappoint_maximum_endtimeslot_limit == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
									  <option value="180" <?php if($saasappoint_maximum_endtimeslot_limit == "180"){ echo "selected"; } ?>>3 Hour</option>
									  <option value="195" <?php if($saasappoint_maximum_endtimeslot_limit == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
									  <option value="210" <?php if($saasappoint_maximum_endtimeslot_limit == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
									  <option value="225" <?php if($saasappoint_maximum_endtimeslot_limit == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
									  <option value="240" <?php if($saasappoint_maximum_endtimeslot_limit == "240"){ echo "selected"; } ?>>4 Hour</option>
									</select>
								</div>
							  </div>
							  <hr />
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Tax/Vat/GST Status</label>
									<?php $saasappoint_tax_status = $obj_settings->get_option("saasappoint_tax_status"); ?>
									<select name="saasappoint_tax_status" id="saasappoint_tax_status" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_tax_status == "Y"){ echo "selected"; } ?>>Enable</option>
									  <option value="N" <?php if($saasappoint_tax_status == "N"){ echo "selected"; } ?>>Disable</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Tax/Vat/GST Type</label>
									<?php $saasappoint_tax_type = $obj_settings->get_option("saasappoint_tax_type"); ?>
									<select name="saasappoint_tax_type" id="saasappoint_tax_type" class="form-control selectpicker">
									  <option value="percentage" <?php if($saasappoint_tax_type == "percentage"){ echo "selected"; } ?>>Percentage</option>
									  <option value="flat" <?php if($saasappoint_tax_type == "flat"){ echo "selected"; } ?>>Flat</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Tax/Vat/GST Value</label>
									<input type="text" name="saasappoint_tax_value" id="saasappoint_tax_value" placeholder="e.g. 10" class="form-control" value="<?php echo $obj_settings->get_option("saasappoint_tax_value"); ?>" />
								</div>
							  </div>
							  <hr />
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Minimum Advance Booking Time</label>
									<?php $saasappoint_minimum_advance_booking_time = $obj_settings->get_option("saasappoint_minimum_advance_booking_time"); ?>
									<select name="saasappoint_minimum_advance_booking_time" id="saasappoint_minimum_advance_booking_time" class="form-control selectpicker">
									  <option value="5" <?php if($saasappoint_minimum_advance_booking_time == "5"){ echo "selected"; } ?>>5 Minutes</option>
									  <option value="10" <?php if($saasappoint_minimum_advance_booking_time == "10"){ echo "selected"; } ?>>10 Minutes</option>
									  <option value="15" <?php if($saasappoint_minimum_advance_booking_time == "15"){ echo "selected"; } ?>>15 Minutes</option>
									  <option value="20" <?php if($saasappoint_minimum_advance_booking_time == "20"){ echo "selected"; } ?>>20 Minutes</option>
									  <option value="30" <?php if($saasappoint_minimum_advance_booking_time == "30"){ echo "selected"; } ?>>30 Minutes</option>
									  <option value="45" <?php if($saasappoint_minimum_advance_booking_time == "45"){ echo "selected"; } ?>>45 Minutes</option>
									  <option value="60" <?php if($saasappoint_minimum_advance_booking_time == "60"){ echo "selected"; } ?>>1 Hour</option>
									  <option value="75" <?php if($saasappoint_minimum_advance_booking_time == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
									  <option value="90" <?php if($saasappoint_minimum_advance_booking_time == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
									  <option value="105" <?php if($saasappoint_minimum_advance_booking_time == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
									  <option value="120" <?php if($saasappoint_minimum_advance_booking_time == "120"){ echo "selected"; } ?>>2 Hour</option>
									  <option value="135" <?php if($saasappoint_minimum_advance_booking_time == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
									  <option value="150" <?php if($saasappoint_minimum_advance_booking_time == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
									  <option value="165" <?php if($saasappoint_minimum_advance_booking_time == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
									  <option value="180" <?php if($saasappoint_minimum_advance_booking_time == "180"){ echo "selected"; } ?>>3 Hour</option>
									  <option value="195" <?php if($saasappoint_minimum_advance_booking_time == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
									  <option value="210" <?php if($saasappoint_minimum_advance_booking_time == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
									  <option value="225" <?php if($saasappoint_minimum_advance_booking_time == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
									  <option value="240" <?php if($saasappoint_minimum_advance_booking_time == "240"){ echo "selected"; } ?>>4 Hour</option>
									  <option value="300" <?php if($saasappoint_minimum_advance_booking_time == "300"){ echo "selected"; } ?>>5 Hour</option>
									  <option value="360" <?php if($saasappoint_minimum_advance_booking_time == "360"){ echo "selected"; } ?>>6 Hour</option>
									  <option value="420" <?php if($saasappoint_minimum_advance_booking_time == "420"){ echo "selected"; } ?>>7 Hour</option>
									  <option value="480" <?php if($saasappoint_minimum_advance_booking_time == "480"){ echo "selected"; } ?>>8 Hour</option>
									  <option value="540" <?php if($saasappoint_minimum_advance_booking_time == "540"){ echo "selected"; } ?>>9 Hour</option>
									  <option value="600" <?php if($saasappoint_minimum_advance_booking_time == "600"){ echo "selected"; } ?>>10 Hour</option>
									  <option value="660" <?php if($saasappoint_minimum_advance_booking_time == "660"){ echo "selected"; } ?>>11 Hour</option>
									  <option value="720" <?php if($saasappoint_minimum_advance_booking_time == "720"){ echo "selected"; } ?>>12 Hour</option>
									  <option value="1440" <?php if($saasappoint_minimum_advance_booking_time == "1440"){ echo "selected"; } ?>>24 Hour</option>
									  <option value="2160" <?php if($saasappoint_minimum_advance_booking_time == "2160"){ echo "selected"; } ?>>36 Hour</option>
									  <option value="2880" <?php if($saasappoint_minimum_advance_booking_time == "2880"){ echo "selected"; } ?>>48 Hour</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Maximum Advance Booking Time</label>
									<?php $saasappoint_maximum_advance_booking_time = $obj_settings->get_option("saasappoint_maximum_advance_booking_time"); ?>
									<select name="saasappoint_maximum_advance_booking_time" id="saasappoint_maximum_advance_booking_time" class="form-control selectpicker">
									  <option value="1" <?php if($saasappoint_maximum_advance_booking_time == "1"){ echo "selected"; } ?>>1 Month</option>
									  <option value="3" <?php if($saasappoint_maximum_advance_booking_time == "3"){ echo "selected"; } ?>>3 Month</option>
									  <option value="6" <?php if($saasappoint_maximum_advance_booking_time == "6"){ echo "selected"; } ?>>6 Month</option>
									  <option value="9" <?php if($saasappoint_maximum_advance_booking_time == "9"){ echo "selected"; } ?>>9 Month</option>
									  <option value="12" <?php if($saasappoint_maximum_advance_booking_time == "12"){ echo "selected"; } ?>>1 Year</option>
									  <option value="18" <?php if($saasappoint_maximum_advance_booking_time == "18"){ echo "selected"; } ?>>1.5 Year</option>
									  <option value="24" <?php if($saasappoint_maximum_advance_booking_time == "24"){ echo "selected"; } ?>>2 Year</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Cancellation Buffer Time</label>
									<?php $saasappoint_cancellation_buffer_time = $obj_settings->get_option("saasappoint_cancellation_buffer_time"); ?>
									<select name="saasappoint_cancellation_buffer_time" id="saasappoint_cancellation_buffer_time" class="form-control selectpicker">
									  <option value="5" <?php if($saasappoint_cancellation_buffer_time == "5"){ echo "selected"; } ?>>5 Minutes</option>
									  <option value="10" <?php if($saasappoint_cancellation_buffer_time == "10"){ echo "selected"; } ?>>10 Minutes</option>
									  <option value="15" <?php if($saasappoint_cancellation_buffer_time == "15"){ echo "selected"; } ?>>15 Minutes</option>
									  <option value="20" <?php if($saasappoint_cancellation_buffer_time == "20"){ echo "selected"; } ?>>20 Minutes</option>
									  <option value="30" <?php if($saasappoint_cancellation_buffer_time == "30"){ echo "selected"; } ?>>30 Minutes</option>
									  <option value="45" <?php if($saasappoint_cancellation_buffer_time == "45"){ echo "selected"; } ?>>45 Minutes</option>
									  <option value="60" <?php if($saasappoint_cancellation_buffer_time == "60"){ echo "selected"; } ?>>1 Hour</option>
									  <option value="75" <?php if($saasappoint_cancellation_buffer_time == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
									  <option value="90" <?php if($saasappoint_cancellation_buffer_time == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
									  <option value="105" <?php if($saasappoint_cancellation_buffer_time == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
									  <option value="120" <?php if($saasappoint_cancellation_buffer_time == "120"){ echo "selected"; } ?>>2 Hour</option>
									  <option value="135" <?php if($saasappoint_cancellation_buffer_time == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
									  <option value="150" <?php if($saasappoint_cancellation_buffer_time == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
									  <option value="165" <?php if($saasappoint_cancellation_buffer_time == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
									  <option value="180" <?php if($saasappoint_cancellation_buffer_time == "180"){ echo "selected"; } ?>>3 Hour</option>
									  <option value="195" <?php if($saasappoint_cancellation_buffer_time == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
									  <option value="210" <?php if($saasappoint_cancellation_buffer_time == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
									  <option value="225" <?php if($saasappoint_cancellation_buffer_time == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
									  <option value="240" <?php if($saasappoint_cancellation_buffer_time == "240"){ echo "selected"; } ?>>4 Hour</option>
									  <option value="300" <?php if($saasappoint_cancellation_buffer_time == "300"){ echo "selected"; } ?>>5 Hour</option>
									  <option value="360" <?php if($saasappoint_cancellation_buffer_time == "360"){ echo "selected"; } ?>>6 Hour</option>
									  <option value="420" <?php if($saasappoint_cancellation_buffer_time == "420"){ echo "selected"; } ?>>7 Hour</option>
									  <option value="480" <?php if($saasappoint_cancellation_buffer_time == "480"){ echo "selected"; } ?>>8 Hour</option>
									  <option value="540" <?php if($saasappoint_cancellation_buffer_time == "540"){ echo "selected"; } ?>>9 Hour</option>
									  <option value="600" <?php if($saasappoint_cancellation_buffer_time == "600"){ echo "selected"; } ?>>10 Hour</option>
									  <option value="660" <?php if($saasappoint_cancellation_buffer_time == "660"){ echo "selected"; } ?>>11 Hour</option>
									  <option value="720" <?php if($saasappoint_cancellation_buffer_time == "720"){ echo "selected"; } ?>>12 Hour</option>
									  <option value="1440" <?php if($saasappoint_cancellation_buffer_time == "1440"){ echo "selected"; } ?>>24 Hour</option>
									  <option value="2160" <?php if($saasappoint_cancellation_buffer_time == "2160"){ echo "selected"; } ?>>36 Hour</option>
									  <option value="2880" <?php if($saasappoint_cancellation_buffer_time == "2880"){ echo "selected"; } ?>>48 Hour</option>
									</select>
								</div>
							  </div>
							  <hr />
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Reschedule Buffer Time</label>
									<?php $saasappoint_reschedule_buffer_time = $obj_settings->get_option("saasappoint_reschedule_buffer_time"); ?>
									<select name="saasappoint_reschedule_buffer_time" id="saasappoint_reschedule_buffer_time" class="form-control selectpicker">
									  <option value="5" <?php if($saasappoint_reschedule_buffer_time == "5"){ echo "selected"; } ?>>5 Minutes</option>
									  <option value="10" <?php if($saasappoint_reschedule_buffer_time == "10"){ echo "selected"; } ?>>10 Minutes</option>
									  <option value="15" <?php if($saasappoint_reschedule_buffer_time == "15"){ echo "selected"; } ?>>15 Minutes</option>
									  <option value="20" <?php if($saasappoint_reschedule_buffer_time == "20"){ echo "selected"; } ?>>20 Minutes</option>
									  <option value="30" <?php if($saasappoint_reschedule_buffer_time == "30"){ echo "selected"; } ?>>30 Minutes</option>
									  <option value="45" <?php if($saasappoint_reschedule_buffer_time == "45"){ echo "selected"; } ?>>45 Minutes</option>
									  <option value="60" <?php if($saasappoint_reschedule_buffer_time == "60"){ echo "selected"; } ?>>1 Hour</option>
									  <option value="75" <?php if($saasappoint_reschedule_buffer_time == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
									  <option value="90" <?php if($saasappoint_reschedule_buffer_time == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
									  <option value="105" <?php if($saasappoint_reschedule_buffer_time == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
									  <option value="120" <?php if($saasappoint_reschedule_buffer_time == "120"){ echo "selected"; } ?>>2 Hour</option>
									  <option value="135" <?php if($saasappoint_reschedule_buffer_time == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
									  <option value="150" <?php if($saasappoint_reschedule_buffer_time == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
									  <option value="165" <?php if($saasappoint_reschedule_buffer_time == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
									  <option value="180" <?php if($saasappoint_reschedule_buffer_time == "180"){ echo "selected"; } ?>>3 Hour</option>
									  <option value="195" <?php if($saasappoint_reschedule_buffer_time == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
									  <option value="210" <?php if($saasappoint_reschedule_buffer_time == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
									  <option value="225" <?php if($saasappoint_reschedule_buffer_time == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
									  <option value="240" <?php if($saasappoint_reschedule_buffer_time == "240"){ echo "selected"; } ?>>4 Hour</option>
									  <option value="300" <?php if($saasappoint_reschedule_buffer_time == "300"){ echo "selected"; } ?>>5 Hour</option>
									  <option value="360" <?php if($saasappoint_reschedule_buffer_time == "360"){ echo "selected"; } ?>>6 Hour</option>
									  <option value="420" <?php if($saasappoint_reschedule_buffer_time == "420"){ echo "selected"; } ?>>7 Hour</option>
									  <option value="480" <?php if($saasappoint_reschedule_buffer_time == "480"){ echo "selected"; } ?>>8 Hour</option>
									  <option value="540" <?php if($saasappoint_reschedule_buffer_time == "540"){ echo "selected"; } ?>>9 Hour</option>
									  <option value="600" <?php if($saasappoint_reschedule_buffer_time == "600"){ echo "selected"; } ?>>10 Hour</option>
									  <option value="660" <?php if($saasappoint_reschedule_buffer_time == "660"){ echo "selected"; } ?>>11 Hour</option>
									  <option value="720" <?php if($saasappoint_reschedule_buffer_time == "720"){ echo "selected"; } ?>>12 Hour</option>
									  <option value="1440" <?php if($saasappoint_reschedule_buffer_time == "1440"){ echo "selected"; } ?>>24 Hour</option>
									  <option value="2160" <?php if($saasappoint_reschedule_buffer_time == "2160"){ echo "selected"; } ?>>36 Hour</option>
									  <option value="2880" <?php if($saasappoint_reschedule_buffer_time == "2880"){ echo "selected"; } ?>>48 Hour</option>
									</select>
								</div>
								<div class="col-md-3">
									<label class="control-label">Auto Confirm Appointment</label>
									<?php $saasappoint_auto_confirm_appointment = $obj_settings->get_option("saasappoint_auto_confirm_appointment"); ?>
									<select name="saasappoint_auto_confirm_appointment" id="saasappoint_auto_confirm_appointment" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_auto_confirm_appointment == "Y"){ echo "selected"; } ?>>Enable</option>
									  <option value="N" <?php if($saasappoint_auto_confirm_appointment == "N"){ echo "selected"; } ?>>Disable</option>
									</select>
								</div>
								<div class="col-md-5">
									<label class="control-label">TimeZone</label>
									<?php $saasappoint_timezone = $obj_settings->get_option("saasappoint_timezone"); ?>
									<select name="saasappoint_timezone" id="saasappoint_timezone" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search your TimeZone">
									  	<option <?php if($saasappoint_timezone=='Pacific/Niue'){ echo "selected"; } ?> value="Pacific/Niue" data-posinset="3">(GMT-11:00) Niue Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Pago_Pago'){ echo "selected"; } ?> value="Pacific/Pago_Pago" data-posinset="4">(GMT-11:00) Samoa Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Rarotonga'){ echo "selected"; } ?> value="Pacific/Rarotonga" data-posinset="5">(GMT-10:00) Cook Islands Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Honolulu'){ echo "selected"; } ?> value="Pacific/Honolulu" data-posinset="6">(GMT-10:00) Hawaii-Aleutian Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Tahiti'){ echo "selected"; } ?> value="Pacific/Tahiti" data-posinset="7">(GMT-10:00) Tahiti Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Marquesas'){ echo "selected"; } ?> value="Pacific/Marquesas" data-posinset="8">(GMT-09:30) Marquesas Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Gambier'){ echo "selected"; } ?> value="Pacific/Gambier" data-posinset="9">(GMT-09:30) Gambier Time</option>
										<option <?php if($saasappoint_timezone=='America/Anchorage'){ echo "selected"; } ?> value="America/Anchorage" data-posinset="10">(GMT-08:00) Alaska Time - Anchorage</option>
										<option <?php if($saasappoint_timezone=='Pacific/Pitcairn'){ echo "selected"; } ?> value="Pacific/Pitcairn" data-posinset="11">(GMT-08:00) Pitcairn Time</option>
										<option <?php if($saasappoint_timezone=='America/Hermosillo'){ echo "selected"; } ?> value="America/Hermosillo" data-posinset="12">(GMT-07:00) Mexican Pacific Standard Time</option>
										<option <?php if($saasappoint_timezone=='America/Dawson_Creek'){ echo "selected"; } ?> value="America/Dawson_Creek" data-posinset="13">(GMT-07:00) Mountain Standard Time - Dawson Creek</option>
										<option <?php if($saasappoint_timezone=='America/Phoenix'){ echo "selected"; } ?> value="America/Phoenix" data-posinset="14">(GMT-07:00) Mountain Standard Time - Phoenix</option>
										<option <?php if($saasappoint_timezone=='America/Dawson'){ echo "selected"; } ?> value="America/Dawson" data-posinset="15">(GMT-07:00) Pacific Time - Dawson</option>
										<option <?php if($saasappoint_timezone=='America/Los_Angeles'){ echo "selected"; } ?> value="America/Los_Angeles" data-posinset="16">(GMT-07:00) Pacific Time - Los Angeles</option>
										<option <?php if($saasappoint_timezone=='America/Tijuana'){ echo "selected"; } ?> value="America/Tijuana" data-posinset="17">(GMT-07:00) Pacific Time - Tijuana</option>
										<option <?php if($saasappoint_timezone=='America/Vancouver'){ echo "selected"; } ?> value="America/Vancouver" data-posinset="18">(GMT-07:00) Pacific Time - Vancouver</option>
										<option <?php if($saasappoint_timezone=='America/Whitehorse'){ echo "selected"; } ?> value="America/Whitehorse" data-posinset="19">(GMT-07:00) Pacific Time - Whitehorse</option>
										<option <?php if($saasappoint_timezone=='America/Belize'){ echo "selected"; } ?> value="America/Belize" data-posinset="20">(GMT-06:00) Central Standard Time - Belize</option>
										<option <?php if($saasappoint_timezone=='America/Costa_Rica'){ echo "selected"; } ?> value="America/Costa_Rica" data-posinset="21">(GMT-06:00) Central Standard Time - Costa Rica</option>
										<option <?php if($saasappoint_timezone=='America/El_Salvador'){ echo "selected"; } ?> value="America/El_Salvador" data-posinset="22">(GMT-06:00) Central Standard Time - El Salvador</option>
										<option <?php if($saasappoint_timezone=='America/Guatemala'){ echo "selected"; } ?> value="America/Guatemala" data-posinset="23">(GMT-06:00) Central Standard Time - Guatemala</option>
										<option <?php if($saasappoint_timezone=='America/Managua'){ echo "selected"; } ?> value="America/Managua" data-posinset="24">(GMT-06:00) Central Standard Time - Managua</option>
										<option <?php if($saasappoint_timezone=='America/Regina'){ echo "selected"; } ?> value="America/Regina" data-posinset="25">(GMT-06:00) Central Standard Time - Regina</option>
										<option <?php if($saasappoint_timezone=='America/Tegucigalpa'){ echo "selected"; } ?> value="America/Tegucigalpa" data-posinset="26">(GMT-06:00) Central Standard Time - Tegucigalpa</option>
										<option <?php if($saasappoint_timezone=='Pacific/Easter'){ echo "selected"; } ?> value="Pacific/Easter" data-posinset="27">(GMT-06:00) Easter Island Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Galapagos'){ echo "selected"; } ?> value="Pacific/Galapagos" data-posinset="28">(GMT-06:00) Galapagos Time</option>
										<option <?php if($saasappoint_timezone=='America/Mazatlan'){ echo "selected"; } ?> value="America/Mazatlan" data-posinset="29">(GMT-06:00) Mexican Pacific Time - Mazatlan</option>
										<option <?php if($saasappoint_timezone=='America/Boise'){ echo "selected"; } ?> value="America/Boise" data-posinset="30">(GMT-06:00) Mountain Time - Boise</option>
										<option <?php if($saasappoint_timezone=='America/Denver'){ echo "selected"; } ?> value="America/Denver" data-posinset="31">(GMT-06:00) Mountain Time - Denver</option>
										<option <?php if($saasappoint_timezone=='America/Edmonton'){ echo "selected"; } ?> value="America/Edmonton" data-posinset="32">(GMT-06:00) Mountain Time - Edmonton</option>
										<option <?php if($saasappoint_timezone=='America/Yellowknife'){ echo "selected"; } ?> value="America/Yellowknife" data-posinset="33">(GMT-06:00) Mountain Time - Yellowknife</option>
										<option <?php if($saasappoint_timezone=='America/Rio_Branco'){ echo "selected"; } ?> value="America/Rio_Branco" data-posinset="34">(GMT-05:00) Acre Standard Time - Rio Branco</option>
										<option <?php if($saasappoint_timezone=='America/Chicago'){ echo "selected"; } ?> value="America/Chicago" data-posinset="35">(GMT-05:00) Central Time - Chicago</option>
										<option <?php if($saasappoint_timezone=='America/Mexico_City'){ echo "selected"; } ?> value="America/Mexico_City" data-posinset="36">(GMT-05:00) Central Time - Mexico City</option>
										<option <?php if($saasappoint_timezone=='America/Winnipeg'){ echo "selected"; } ?> value="America/Winnipeg" data-posinset="37">(GMT-05:00) Central Time - Winnipeg</option>
										<option <?php if($saasappoint_timezone=='America/Bogota'){ echo "selected"; } ?> value="America/Bogota" data-posinset="38">(GMT-05:00) Colombia Standard Time</option>
										<option <?php if($saasappoint_timezone=='America/Cancun'){ echo "selected"; } ?> value="America/Cancun" data-posinset="39">(GMT-05:00) Eastern Standard Time - Cancun</option>
										<option <?php if($saasappoint_timezone=='America/Jamaica'){ echo "selected"; } ?> value="America/Jamaica" data-posinset="40">(GMT-05:00) Eastern Standard Time - Jamaica</option>
										<option <?php if($saasappoint_timezone=='America/Panama'){ echo "selected"; } ?> value="America/Panama" data-posinset="41">(GMT-05:00) Eastern Standard Time - Panama</option>
										<option <?php if($saasappoint_timezone=='America/Guayaquil'){ echo "selected"; } ?> value="America/Guayaquil" data-posinset="42">(GMT-05:00) Ecuador Time</option>
										<option <?php if($saasappoint_timezone=='America/Lima'){ echo "selected"; } ?> value="America/Lima" data-posinset="43">(GMT-05:00) Peru Standard Time</option>
										<option <?php if($saasappoint_timezone=='America/Boa_Vista'){ echo "selected"; } ?> value="America/Boa_Vista" data-posinset="44">(GMT-04:00) Amazon Standard Time - Boa Vista</option>
										<option <?php if($saasappoint_timezone=='America/Manaus'){ echo "selected"; } ?> value="America/Manaus" data-posinset="45">(GMT-04:00) Amazon Standard Time - Manaus</option>
										<option <?php if($saasappoint_timezone=='America/Porto_Velho'){ echo "selected"; } ?> value="America/Porto_Velho" data-posinset="46">(GMT-04:00) Amazon Standard Time - Porto Velho</option>
										<option <?php if($saasappoint_timezone=='America/Campo_Grande'){ echo "selected"; } ?> value="America/Campo_Grande" data-posinset="47">(GMT-04:00) Amazon Time - Campo Grande</option>
										<option <?php if($saasappoint_timezone=='America/Cuiaba'){ echo "selected"; } ?> value="America/Cuiaba" data-posinset="48">(GMT-04:00) Amazon Time - Cuiaba</option>
										<option <?php if($saasappoint_timezone=='America/Barbados'){ echo "selected"; } ?> value="America/Barbados" data-posinset="49">(GMT-04:00) Atlantic Standard Time - Barbados</option>
										<option <?php if($saasappoint_timezone=='America/Curacao'){ echo "selected"; } ?> value="America/Curacao" data-posinset="50">(GMT-04:00) Atlantic Standard Time - Curaçao</option>
										<option <?php if($saasappoint_timezone=='America/Martinique'){ echo "selected"; } ?> value="America/Martinique" data-posinset="51">(GMT-04:00) Atlantic Standard Time - Martinique</option>
										<option <?php if($saasappoint_timezone=='America/Port_of_Spain'){ echo "selected"; } ?> value="America/Port_of_Spain" data-posinset="52">(GMT-04:00) Atlantic Standard Time - Port of Spain</option>
										<option <?php if($saasappoint_timezone=='America/Puerto_Rico'){ echo "selected"; } ?> value="America/Puerto_Rico" data-posinset="53">(GMT-04:00) Atlantic Standard Time - Puerto Rico</option>
										<option <?php if($saasappoint_timezone=='America/Santo_Domingo'){ echo "selected"; } ?> value="America/Santo_Domingo" data-posinset="54">(GMT-04:00) Atlantic Standard Time - Santo Domingo</option>
										<option <?php if($saasappoint_timezone=='America/La_Paz'){ echo "selected"; } ?> value="America/La_Paz" data-posinset="55">(GMT-04:00) Bolivia Time</option>
										<option <?php if($saasappoint_timezone=='America/Santiago'){ echo "selected"; } ?> value="America/Santiago" data-posinset="56">(GMT-04:00) Chile Time</option>
										<option <?php if($saasappoint_timezone=='America/Havana'){ echo "selected"; } ?> value="America/Havana" data-posinset="57">(GMT-04:00) Cuba Time</option>
										<option <?php if($saasappoint_timezone=='America/Detroit'){ echo "selected"; } ?> value="America/Detroit" data-posinset="58">(GMT-04:00) Eastern Time - Detroit</option>
										<option <?php if($saasappoint_timezone=='America/Grand_Turk'){ echo "selected"; } ?> value="America/Grand_Turk" data-posinset="59">(GMT-04:00) Eastern Time - Grand Turk</option>
										<option <?php if($saasappoint_timezone=='America/Iqaluit'){ echo "selected"; } ?> value="America/Iqaluit" data-posinset="60">(GMT-04:00) Eastern Time - Iqaluit</option>
										<option <?php if($saasappoint_timezone=='America/Nassau'){ echo "selected"; } ?> value="America/Nassau" data-posinset="61">(GMT-04:00) Eastern Time - Nassau</option>
										<option <?php if($saasappoint_timezone=='America/New_York'){ echo "selected"; } ?> value="America/New_York" data-posinset="62">(GMT-04:00) Eastern Time - New York</option>
										<option <?php if($saasappoint_timezone=='America/Port-au-Prince'){ echo "selected"; } ?> value="America/Port-au-Prince" data-posinset="63">(GMT-04:00) Eastern Time - Port-au-Prince</option>
										<option <?php if($saasappoint_timezone=='America/Toronto'){ echo "selected"; } ?> value="America/Toronto" data-posinset="64">(GMT-04:00) Eastern Time - Toronto</option>
										<option <?php if($saasappoint_timezone=='America/Guyana'){ echo "selected"; } ?> value="America/Guyana" data-posinset="65">(GMT-04:00) Guyana Time</option>
										<option <?php if($saasappoint_timezone=='America/Asuncion'){ echo "selected"; } ?> value="America/Asuncion" data-posinset="66">(GMT-04:00) Paraguay Time</option>
										<option <?php if($saasappoint_timezone=='America/Caracas'){ echo "selected"; } ?> value="America/Caracas" data-posinset="67">(GMT-04:00) Venezuela Time</option>
										<option <?php if($saasappoint_timezone=='America/Argentina/Buenos_Aires'){ echo "selected"; } ?> value="America/Argentina/Buenos_Aires" data-posinset="68">(GMT-03:00) Argentina Standard Time - Buenos Aires</option>
										<option <?php if($saasappoint_timezone=='America/Argentina/Cordoba'){ echo "selected"; } ?> value="America/Argentina/Cordoba" data-posinset="69">(GMT-03:00) Argentina Standard Time - Cordoba</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Bermuda'){ echo "selected"; } ?> value="Atlantic/Bermuda" data-posinset="70">(GMT-03:00) Atlantic Time - Bermuda</option>
										<option <?php if($saasappoint_timezone=='America/Halifax'){ echo "selected"; } ?> value="America/Halifax" data-posinset="71">(GMT-03:00) Atlantic Time - Halifax</option>
										<option <?php if($saasappoint_timezone=='America/Thule'){ echo "selected"; } ?> value="America/Thule" data-posinset="72">(GMT-03:00) Atlantic Time - Thule</option>
										<option <?php if($saasappoint_timezone=='America/Araguaina'){ echo "selected"; } ?> value="America/Araguaina" data-posinset="73">(GMT-03:00) Brasilia Standard Time - Araguaina</option>
										<option <?php if($saasappoint_timezone=='America/Bahia'){ echo "selected"; } ?> value="America/Bahia" data-posinset="74">(GMT-03:00) Brasilia Standard Time - Bahia</option>
										<option <?php if($saasappoint_timezone=='America/Belem'){ echo "selected"; } ?> value="America/Belem" data-posinset="75">(GMT-03:00) Brasilia Standard Time - Belem</option>
										<option <?php if($saasappoint_timezone=='America/Fortaleza'){ echo "selected"; } ?> value="America/Fortaleza" data-posinset="76">(GMT-03:00) Brasilia Standard Time - Fortaleza</option>
										<option <?php if($saasappoint_timezone=='America/Maceio'){ echo "selected"; } ?> value="America/Maceio" data-posinset="77">(GMT-03:00) Brasilia Standard Time - Maceio</option>
										<option <?php if($saasappoint_timezone=='America/Recife'){ echo "selected"; } ?> value="America/Recife" data-posinset="78">(GMT-03:00) Brasilia Standard Time - Recife</option>
										<option <?php if($saasappoint_timezone=='America/Sao_Paulo'){ echo "selected"; } ?> value="America/Sao_Paulo" data-posinset="79">(GMT-03:00) Brasilia Time</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Stanley'){ echo "selected"; } ?> value="Atlantic/Stanley" data-posinset="80">(GMT-03:00) Falkland Islands Standard Time</option>
										<option <?php if($saasappoint_timezone=='America/Cayenne'){ echo "selected"; } ?> value="America/Cayenne" data-posinset="81">(GMT-03:00) French Guiana Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Palmer'){ echo "selected"; } ?> value="Antarctica/Palmer" data-posinset="82">(GMT-03:00) Palmer Time</option>
										<option <?php if($saasappoint_timezone=='America/Punta_Arenas'){ echo "selected"; } ?> value="America/Punta_Arenas" data-posinset="83">(GMT-03:00) Punta Arenas Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Rothera'){ echo "selected"; } ?> value="Antarctica/Rothera" data-posinset="84">(GMT-03:00) Rothera Time</option>
										<option <?php if($saasappoint_timezone=='America/Paramaribo'){ echo "selected"; } ?> value="America/Paramaribo" data-posinset="85">(GMT-03:00) Suriname Time</option>
										<option <?php if($saasappoint_timezone=='America/Montevideo'){ echo "selected"; } ?> value="America/Montevideo" data-posinset="86">(GMT-03:00) Uruguay Standard Time</option>
										<option <?php if($saasappoint_timezone=='America/St_Johns'){ echo "selected"; } ?> value="America/St_Johns" data-posinset="87">(GMT-02:30) Newfoundland Time</option>
										<option <?php if($saasappoint_timezone=='America/Noronha'){ echo "selected"; } ?> value="America/Noronha" data-posinset="88">(GMT-02:00) Fernando de Noronha Standard Time</option>
										<option <?php if($saasappoint_timezone=='Atlantic/South_Georgia'){ echo "selected"; } ?> value="Atlantic/South_Georgia" data-posinset="89">(GMT-02:00) South Georgia Time</option>
										<option <?php if($saasappoint_timezone=='America/Miquelon'){ echo "selected"; } ?> value="America/Miquelon" data-posinset="90">(GMT-02:00) St. Pierre &amp; Miquelon Time</option>
										<option <?php if($saasappoint_timezone=='America/Godthab'){ echo "selected"; } ?> value="America/Godthab" data-posinset="91">(GMT-02:00) West Greenland Time</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Cape_Verde'){ echo "selected"; } ?> value="Atlantic/Cape_Verde" data-posinset="92">(GMT-01:00) Cape Verde Standard Time</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Azores'){ echo "selected"; } ?> value="Atlantic/Azores" data-posinset="93">(GMT+00:00) Azores Time</option>
										<option <?php if($saasappoint_timezone=='America/Scoresbysund'){ echo "selected"; } ?> value="America/Scoresbysund" data-posinset="94">(GMT+00:00) East Greenland Time</option>
										<option <?php if($saasappoint_timezone=='Etc/GMT'){ echo "selected"; } ?> value="Etc/GMT" data-posinset="95">(GMT+00:00) Greenwich Mean Time</option>
										<option <?php if($saasappoint_timezone=='Africa/Abidjan'){ echo "selected"; } ?> value="Africa/Abidjan" data-posinset="96">(GMT+00:00) Greenwich Mean Time - Abidjan</option>
										<option <?php if($saasappoint_timezone=='Africa/Accra'){ echo "selected"; } ?> value="Africa/Accra" data-posinset="97">(GMT+00:00) Greenwich Mean Time - Accra</option>
										<option <?php if($saasappoint_timezone=='Africa/Bissau'){ echo "selected"; } ?> value="Africa/Bissau" data-posinset="98">(GMT+00:00) Greenwich Mean Time - Bissau</option>
										<option <?php if($saasappoint_timezone=='America/Danmarkshavn'){ echo "selected"; } ?> value="America/Danmarkshavn" data-posinset="99">(GMT+00:00) Greenwich Mean Time - Danmarkshavn</option>
										<option <?php if($saasappoint_timezone=='Africa/Monrovia'){ echo "selected"; } ?> value="Africa/Monrovia" data-posinset="100">(GMT+00:00) Greenwich Mean Time - Monrovia</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Reykjavik'){ echo "selected"; } ?> value="Atlantic/Reykjavik" data-posinset="101">(GMT+00:00) Greenwich Mean Time - Reykjavik</option>
										<option <?php if($saasappoint_timezone=='UTC'){ echo "selected"; } ?> value="UTC" data-posinset="102">UTC</option>
										<option <?php if($saasappoint_timezone=='Africa/Algiers'){ echo "selected"; } ?> value="Africa/Algiers" data-posinset="103">(GMT+01:00) Central European Standard Time - Algiers</option>
										<option <?php if($saasappoint_timezone=='Africa/Tunis'){ echo "selected"; } ?> value="Africa/Tunis" data-posinset="104">(GMT+01:00) Central European Standard Time - Tunis</option>
										<option <?php if($saasappoint_timezone=='Europe/Dublin'){ echo "selected"; } ?> value="Europe/Dublin" data-posinset="105">(GMT+01:00) Ireland Time</option>
										<option <?php if($saasappoint_timezone=='Europe/London'){ echo "selected"; } ?> value="Europe/London" data-posinset="106">(GMT+01:00) United Kingdom Time</option>
										<option <?php if($saasappoint_timezone=='Africa/Lagos'){ echo "selected"; } ?> value="Africa/Lagos" data-posinset="107">(GMT+01:00) West Africa Standard Time - Lagos</option>
										<option <?php if($saasappoint_timezone=='Africa/Ndjamena'){ echo "selected"; } ?> value="Africa/Ndjamena" data-posinset="108">(GMT+01:00) West Africa Standard Time - Ndjamena</option>
										<option <?php if($saasappoint_timezone=='Africa/Sao_Tome'){ echo "selected"; } ?> value="Africa/Sao_Tome" data-posinset="109">(GMT+01:00) West Africa Standard Time - São Tomé</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Canary'){ echo "selected"; } ?> value="Atlantic/Canary" data-posinset="110">(GMT+01:00) Western European Time - Canary</option>
										<option <?php if($saasappoint_timezone=='Africa/Casablanca'){ echo "selected"; } ?> value="Africa/Casablanca" data-posinset="111">(GMT+01:00) Western European Time - Casablanca</option>
										<option <?php if($saasappoint_timezone=='Africa/El_Aaiun'){ echo "selected"; } ?> value="Africa/El_Aaiun" data-posinset="112">(GMT+01:00) Western European Time - El Aaiun</option>
										<option <?php if($saasappoint_timezone=='Atlantic/Faroe'){ echo "selected"; } ?> value="Atlantic/Faroe" data-posinset="113">(GMT+01:00) Western European Time - Faroe</option>
										<option <?php if($saasappoint_timezone=='Europe/Lisbon'){ echo "selected"; } ?> value="Europe/Lisbon" data-posinset="114">(GMT+01:00) Western European Time - Lisbon</option>
										<option <?php if($saasappoint_timezone=='Africa/Khartoum'){ echo "selected"; } ?> value="Africa/Khartoum" data-posinset="115">(GMT+02:00) Central Africa Time - Khartoum</option>
										<option <?php if($saasappoint_timezone=='Africa/Maputo'){ echo "selected"; } ?> value="Africa/Maputo" data-posinset="116">(GMT+02:00) Central Africa Time - Maputo</option>
										<option <?php if($saasappoint_timezone=='Africa/Windhoek'){ echo "selected"; } ?> value="Africa/Windhoek" data-posinset="117">(GMT+02:00) Central Africa Time - Windhoek</option>
										<option <?php if($saasappoint_timezone=='Europe/Amsterdam'){ echo "selected"; } ?> value="Europe/Amsterdam" data-posinset="118">(GMT+02:00) Central European Time - Amsterdam</option>
										<option <?php if($saasappoint_timezone=='Europe/Andorra'){ echo "selected"; } ?> value="Europe/Andorra" data-posinset="119">(GMT+02:00) Central European Time - Andorra</option>
										<option <?php if($saasappoint_timezone=='Europe/Belgrade'){ echo "selected"; } ?> value="Europe/Belgrade" data-posinset="120">(GMT+02:00) Central European Time - Belgrade</option>
										<option <?php if($saasappoint_timezone=='Europe/Berlin'){ echo "selected"; } ?> value="Europe/Berlin" data-posinset="121">(GMT+02:00) Central European Time - Berlin</option>
										<option <?php if($saasappoint_timezone=='Europe/Brussels'){ echo "selected"; } ?> value="Europe/Brussels" data-posinset="122">(GMT+02:00) Central European Time - Brussels</option>
										<option <?php if($saasappoint_timezone=='Europe/Budapest'){ echo "selected"; } ?> value="Europe/Budapest" data-posinset="123">(GMT+02:00) Central European Time - Budapest</option>
										<option <?php if($saasappoint_timezone=='Africa/Ceuta'){ echo "selected"; } ?> value="Africa/Ceuta" data-posinset="124">(GMT+02:00) Central European Time - Ceuta</option>
										<option <?php if($saasappoint_timezone=='Europe/Copenhagen'){ echo "selected"; } ?> value="Europe/Copenhagen" data-posinset="125">(GMT+02:00) Central European Time - Copenhagen</option>
										<option <?php if($saasappoint_timezone=='Europe/Gibraltar'){ echo "selected"; } ?> value="Europe/Gibraltar" data-posinset="126">(GMT+02:00) Central European Time - Gibraltar</option>
										<option <?php if($saasappoint_timezone=='Europe/Luxembourg'){ echo "selected"; } ?> value="Europe/Luxembourg" data-posinset="127">(GMT+02:00) Central European Time - Luxembourg</option>
										<option <?php if($saasappoint_timezone=='Europe/Madrid'){ echo "selected"; } ?> value="Europe/Madrid" data-posinset="128">(GMT+02:00) Central European Time - Madrid</option>
										<option <?php if($saasappoint_timezone=='Europe/Malta'){ echo "selected"; } ?> value="Europe/Malta" data-posinset="129">(GMT+02:00) Central European Time - Malta</option>
										<option <?php if($saasappoint_timezone=='Europe/Monaco'){ echo "selected"; } ?> value="Europe/Monaco" data-posinset="130">(GMT+02:00) Central European Time - Monaco</option>
										<option <?php if($saasappoint_timezone=='Europe/Oslo'){ echo "selected"; } ?> value="Europe/Oslo" data-posinset="131">(GMT+02:00) Central European Time - Oslo</option>
										<option <?php if($saasappoint_timezone=='Europe/Paris'){ echo "selected"; } ?> value="Europe/Paris" data-posinset="132">(GMT+02:00) Central European Time - Paris</option>
										<option <?php if($saasappoint_timezone=='Europe/Prague'){ echo "selected"; } ?> value="Europe/Prague" data-posinset="133">(GMT+02:00) Central European Time - Prague</option>
										<option <?php if($saasappoint_timezone=='Europe/Rome'){ echo "selected"; } ?> value="Europe/Rome" data-posinset="134">(GMT+02:00) Central European Time - Rome</option>
										<option <?php if($saasappoint_timezone=='Europe/Stockholm'){ echo "selected"; } ?> value="Europe/Stockholm" data-posinset="135">(GMT+02:00) Central European Time - Stockholm</option>
										<option <?php if($saasappoint_timezone=='Europe/Tirane'){ echo "selected"; } ?> value="Europe/Tirane" data-posinset="136">(GMT+02:00) Central European Time - Tirane</option>
										<option <?php if($saasappoint_timezone=='Europe/Vienna'){ echo "selected"; } ?> value="Europe/Vienna" data-posinset="137">(GMT+02:00) Central European Time - Vienna</option>
										<option <?php if($saasappoint_timezone=='Europe/Warsaw'){ echo "selected"; } ?> value="Europe/Warsaw" data-posinset="138">(GMT+02:00) Central European Time - Warsaw</option>
										<option <?php if($saasappoint_timezone=='Europe/Zurich'){ echo "selected"; } ?> value="Europe/Zurich" data-posinset="139">(GMT+02:00) Central European Time - Zurich</option>
										<option <?php if($saasappoint_timezone=='Africa/Cairo'){ echo "selected"; } ?> value="Africa/Cairo" data-posinset="140">(GMT+02:00) Eastern European Standard Time - Cairo</option>
										<option <?php if($saasappoint_timezone=='Europe/Kaliningrad'){ echo "selected"; } ?> value="Europe/Kaliningrad" data-posinset="141">(GMT+02:00) Eastern European Standard Time - Kaliningrad</option>
										<option <?php if($saasappoint_timezone=='Africa/Tripoli'){ echo "selected"; } ?> value="Africa/Tripoli" data-posinset="142">(GMT+02:00) Eastern European Standard Time - Tripoli</option>
										<option <?php if($saasappoint_timezone=='Africa/Johannesburg'){ echo "selected"; } ?> value="Africa/Johannesburg" data-posinset="143">(GMT+02:00) South Africa Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Baghdad'){ echo "selected"; } ?> value="Asia/Baghdad" data-posinset="144">(GMT+03:00) Arabian Standard Time - Baghdad</option>
										<option <?php if($saasappoint_timezone=='Asia/Qatar'){ echo "selected"; } ?> value="Asia/Qatar" data-posinset="145">(GMT+03:00) Arabian Standard Time - Qatar</option>
										<option <?php if($saasappoint_timezone=='Asia/Riyadh'){ echo "selected"; } ?> value="Asia/Riyadh" data-posinset="146">(GMT+03:00) Arabian Standard Time - Riyadh</option>
										<option <?php if($saasappoint_timezone=='Africa/Nairobi'){ echo "selected"; } ?> value="Africa/Nairobi" data-posinset="147">(GMT+03:00) East Africa Time - Nairobi</option>
										<option <?php if($saasappoint_timezone=='Asia/Amman'){ echo "selected"; } ?> value="Asia/Amman" data-posinset="148">(GMT+03:00) Eastern European Time - Amman</option>
										<option <?php if($saasappoint_timezone=='Europe/Athens'){ echo "selected"; } ?> value="Europe/Athens" data-posinset="149">(GMT+03:00) Eastern European Time - Athens</option>
										<option <?php if($saasappoint_timezone=='Asia/Beirut'){ echo "selected"; } ?> value="Asia/Beirut" data-posinset="150">(GMT+03:00) Eastern European Time - Beirut</option>
										<option <?php if($saasappoint_timezone=='Europe/Bucharest'){ echo "selected"; } ?> value="Europe/Bucharest" data-posinset="151">(GMT+03:00) Eastern European Time - Bucharest</option>
										<option <?php if($saasappoint_timezone=='Europe/Chisinau'){ echo "selected"; } ?> value="Europe/Chisinau" data-posinset="152">(GMT+03:00) Eastern European Time - Chisinau</option>
										<option <?php if($saasappoint_timezone=='Asia/Damascus'){ echo "selected"; } ?> value="Asia/Damascus" data-posinset="153">(GMT+03:00) Eastern European Time - Damascus</option>
										<option <?php if($saasappoint_timezone=='Asia/Gaza'){ echo "selected"; } ?> value="Asia/Gaza" data-posinset="154">(GMT+03:00) Eastern European Time - Gaza</option>
										<option <?php if($saasappoint_timezone=='Europe/Helsinki'){ echo "selected"; } ?> value="Europe/Helsinki" data-posinset="155">(GMT+03:00) Eastern European Time - Helsinki</option>
										<option <?php if($saasappoint_timezone=='Europe/Kiev'){ echo "selected"; } ?> value="Europe/Kiev" data-posinset="156">(GMT+03:00) Eastern European Time - Kiev</option>
										<option <?php if($saasappoint_timezone=='Asia/Nicosia'){ echo "selected"; } ?> value="Asia/Nicosia" data-posinset="157">(GMT+03:00) Eastern European Time - Nicosia</option>
										<option <?php if($saasappoint_timezone=='Europe/Riga'){ echo "selected"; } ?> value="Europe/Riga" data-posinset="158">(GMT+03:00) Eastern European Time - Riga</option>
										<option <?php if($saasappoint_timezone=='Europe/Sofia'){ echo "selected"; } ?> value="Europe/Sofia" data-posinset="159">(GMT+03:00) Eastern European Time - Sofia</option>
										<option <?php if($saasappoint_timezone=='Europe/Tallinn'){ echo "selected"; } ?> value="Europe/Tallinn" data-posinset="160">(GMT+03:00) Eastern European Time - Tallinn</option>
										<option <?php if($saasappoint_timezone=='Europe/Vilnius'){ echo "selected"; } ?> value="Europe/Vilnius" data-posinset="161">(GMT+03:00) Eastern European Time - Vilnius</option>
										<option <?php if($saasappoint_timezone=='Asia/Jerusalem'){ echo "selected"; } ?> value="Asia/Jerusalem" data-posinset="162">(GMT+03:00) Israel Time</option>
										<option <?php if($saasappoint_timezone=='Europe/Minsk'){ echo "selected"; } ?> value="Europe/Minsk" data-posinset="163">(GMT+03:00) Moscow Standard Time - Minsk</option>
										<option <?php if($saasappoint_timezone=='Europe/Moscow'){ echo "selected"; } ?> value="Europe/Moscow" data-posinset="164">(GMT+03:00) Moscow Standard Time - Moscow</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Syowa'){ echo "selected"; } ?> value="Antarctica/Syowa" data-posinset="165">(GMT+03:00) Syowa Time</option>
										<option <?php if($saasappoint_timezone=='Europe/Istanbul'){ echo "selected"; } ?> value="Europe/Istanbul" data-posinset="166">(GMT+03:00) Turkey Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Yerevan'){ echo "selected"; } ?> value="Asia/Yerevan" data-posinset="167">(GMT+04:00) Armenia Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Baku'){ echo "selected"; } ?> value="Asia/Baku" data-posinset="168">(GMT+04:00) Azerbaijan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Tbilisi'){ echo "selected"; } ?> value="Asia/Tbilisi" data-posinset="169">(GMT+04:00) Georgia Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Dubai'){ echo "selected"; } ?> value="Asia/Dubai" data-posinset="170">(GMT+04:00) Gulf Standard Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Mauritius'){ echo "selected"; } ?> value="Indian/Mauritius" data-posinset="171">(GMT+04:00) Mauritius Standard Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Reunion'){ echo "selected"; } ?> value="Indian/Reunion" data-posinset="172">(GMT+04:00) Réunion Time</option>
										<option <?php if($saasappoint_timezone=='Europe/Samara'){ echo "selected"; } ?> value="Europe/Samara" data-posinset="173">(GMT+04:00) Samara Standard Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Mahe'){ echo "selected"; } ?> value="Indian/Mahe" data-posinset="174">(GMT+04:00) Seychelles Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Kabul'){ echo "selected"; } ?> value="Asia/Kabul" data-posinset="175">(GMT+04:30) Afghanistan Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Tehran'){ echo "selected"; } ?> value="Asia/Tehran" data-posinset="176">(GMT+04:30) Iran Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Kerguelen'){ echo "selected"; } ?> value="Indian/Kerguelen" data-posinset="177">(GMT+05:00) French Southern &amp; Antarctic Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Maldives'){ echo "selected"; } ?> value="Indian/Maldives" data-posinset="178">(GMT+05:00) Maldives Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Mawson'){ echo "selected"; } ?> value="Antarctica/Mawson" data-posinset="179">(GMT+05:00) Mawson Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Karachi'){ echo "selected"; } ?> value="Asia/Karachi" data-posinset="180">(GMT+05:00) Pakistan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Dushanbe'){ echo "selected"; } ?> value="Asia/Dushanbe" data-posinset="181">(GMT+05:00) Tajikistan Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Ashgabat'){ echo "selected"; } ?> value="Asia/Ashgabat" data-posinset="182">(GMT+05:00) Turkmenistan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Tashkent'){ echo "selected"; } ?> value="Asia/Tashkent" data-posinset="183">(GMT+05:00) Uzbekistan Standard Time - Tashkent</option>
										<option <?php if($saasappoint_timezone=='Asia/Aqtau'){ echo "selected"; } ?> value="Asia/Aqtau" data-posinset="184">(GMT+05:00) West Kazakhstan Time - Aqtau</option>
										<option <?php if($saasappoint_timezone=='Asia/Aqtobe'){ echo "selected"; } ?> value="Asia/Aqtobe" data-posinset="185">(GMT+05:00) West Kazakhstan Time - Aqtobe</option>
										<option <?php if($saasappoint_timezone=='Asia/Yekaterinburg'){ echo "selected"; } ?> value="Asia/Yekaterinburg" data-posinset="186">(GMT+05:00) Yekaterinburg Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Colombo'){ echo "selected"; } ?> value="Asia/Colombo" data-posinset="187">(GMT+05:30) India Standard Time - Colombo</option>
										<option <?php if($saasappoint_timezone=='Asia/Calcutta'){ echo "selected"; } ?> value="Asia/Calcutta" data-posinset="188">(GMT+05:30) India Standard Time - Kolkata</option>
										<option <?php if($saasappoint_timezone=='Asia/Katmandu'){ echo "selected"; } ?> value="Asia/Katmandu" data-posinset="189">(GMT+05:45) Nepal Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Dhaka'){ echo "selected"; } ?> value="Asia/Dhaka" data-posinset="190">(GMT+06:00) Bangladesh Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Thimphu'){ echo "selected"; } ?> value="Asia/Thimphu" data-posinset="191">(GMT+06:00) Bhutan Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Almaty'){ echo "selected"; } ?> value="Asia/Almaty" data-posinset="192">(GMT+06:00) East Kazakhstan Time - Almaty</option>
										<option <?php if($saasappoint_timezone=='Indian/Chagos'){ echo "selected"; } ?> value="Indian/Chagos" data-posinset="193">(GMT+06:00) Indian Ocean Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Bishkek'){ echo "selected"; } ?> value="Asia/Bishkek" data-posinset="194">(GMT+06:00) Kyrgyzstan Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Omsk'){ echo "selected"; } ?> value="Asia/Omsk" data-posinset="195">(GMT+06:00) Omsk Standard Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Vostok'){ echo "selected"; } ?> value="Antarctica/Vostok" data-posinset="196">(GMT+06:00) Vostok Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Cocos'){ echo "selected"; } ?> value="Indian/Cocos" data-posinset="197">(GMT+06:30) Cocos Islands Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Yangon'){ echo "selected"; } ?> value="Asia/Yangon" data-posinset="198">(GMT+06:30) Myanmar Time</option>
										<option <?php if($saasappoint_timezone=='Indian/Christmas'){ echo "selected"; } ?> value="Indian/Christmas" data-posinset="199">(GMT+07:00) Christmas Island Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Davis'){ echo "selected"; } ?> value="Antarctica/Davis" data-posinset="200">(GMT+07:00) Davis Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Hovd'){ echo "selected"; } ?> value="Asia/Hovd" data-posinset="201">(GMT+07:00) Hovd Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Bangkok'){ echo "selected"; } ?> value="Asia/Bangkok" data-posinset="202">(GMT+07:00) Indochina Time - Bangkok</option>
										<option <?php if($saasappoint_timezone=='Asia/Saigon'){ echo "selected"; } ?> value="Asia/Saigon" data-posinset="203">(GMT+07:00) Indochina Time - Ho Chi Minh City</option>
										<option <?php if($saasappoint_timezone=='Asia/Krasnoyarsk'){ echo "selected"; } ?> value="Asia/Krasnoyarsk" data-posinset="204">(GMT+07:00) Krasnoyarsk Standard Time - Krasnoyarsk</option>
										<option <?php if($saasappoint_timezone=='Asia/Jakarta'){ echo "selected"; } ?> value="Asia/Jakarta" data-posinset="205">(GMT+07:00) Western Indonesia Time - Jakarta</option>
										<option <?php if($saasappoint_timezone=='Antarctica/Casey'){ echo "selected"; } ?> value="Antarctica/Casey" data-posinset="206">(GMT+08:00) Australian Western Standard Time - Casey</option>
										<option <?php if($saasappoint_timezone=='Australia/Perth'){ echo "selected"; } ?> value="Australia/Perth" data-posinset="207">(GMT+08:00) Australian Western Standard Time - Perth</option>
										<option <?php if($saasappoint_timezone=='Asia/Brunei'){ echo "selected"; } ?> value="Asia/Brunei" data-posinset="208">(GMT+08:00) Brunei Darussalam Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Makassar'){ echo "selected"; } ?> value="Asia/Makassar" data-posinset="209">(GMT+08:00) Central Indonesia Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Macau'){ echo "selected"; } ?> value="Asia/Macau" data-posinset="210">(GMT+08:00) China Standard Time - Macau</option>
										<option <?php if($saasappoint_timezone=='Asia/Shanghai'){ echo "selected"; } ?> value="Asia/Shanghai" data-posinset="211">(GMT+08:00) China Standard Time - Shanghai</option>
										<option <?php if($saasappoint_timezone=='Asia/Choibalsan'){ echo "selected"; } ?> value="Asia/Choibalsan" data-posinset="212">(GMT+08:00) Choibalsan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Hong_Kong'){ echo "selected"; } ?> value="Asia/Hong_Kong" data-posinset="213">(GMT+08:00) Hong Kong Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Irkutsk'){ echo "selected"; } ?> value="Asia/Irkutsk" data-posinset="214">(GMT+08:00) Irkutsk Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Kuala_Lumpur'){ echo "selected"; } ?> value="Asia/Kuala_Lumpur" data-posinset="215">(GMT+08:00) Malaysia Time - Kuala Lumpur</option>
										<option <?php if($saasappoint_timezone=='Asia/Manila'){ echo "selected"; } ?> value="Asia/Manila" data-posinset="216">(GMT+08:00) Philippine Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Singapore'){ echo "selected"; } ?> value="Asia/Singapore" data-posinset="217">(GMT+08:00) Singapore Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Taipei'){ echo "selected"; } ?> value="Asia/Taipei" data-posinset="218">(GMT+08:00) Taipei Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Ulaanbaatar'){ echo "selected"; } ?> value="Asia/Ulaanbaatar" data-posinset="219">(GMT+08:00) Ulaanbaatar Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Dili'){ echo "selected"; } ?> value="Asia/Dili" data-posinset="220">(GMT+09:00) East Timor Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Jayapura'){ echo "selected"; } ?> value="Asia/Jayapura" data-posinset="221">(GMT+09:00) Eastern Indonesia Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Tokyo'){ echo "selected"; } ?> value="Asia/Tokyo" data-posinset="222">(GMT+09:00) Japan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Pyongyang'){ echo "selected"; } ?> value="Asia/Pyongyang" data-posinset="223">(GMT+09:00) Korean Standard Time - Pyongyang</option>
										<option <?php if($saasappoint_timezone=='Asia/Seoul'){ echo "selected"; } ?> value="Asia/Seoul" data-posinset="224">(GMT+09:00) Korean Standard Time - Seoul</option>
										<option <?php if($saasappoint_timezone=='Pacific/Palau'){ echo "selected"; } ?> value="Pacific/Palau" data-posinset="225">(GMT+09:00) Palau Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Yakutsk'){ echo "selected"; } ?> value="Asia/Yakutsk" data-posinset="226">(GMT+09:00) Yakutsk Standard Time - Yakutsk</option>
										<option <?php if($saasappoint_timezone=='Australia/Darwin'){ echo "selected"; } ?> value="Australia/Darwin" data-posinset="227">(GMT+09:30) Australian Central Standard Time</option>
										<option <?php if($saasappoint_timezone=='Australia/Adelaide'){ echo "selected"; } ?> value="Australia/Adelaide" data-posinset="228">(GMT+09:30) Central Australia Time - Adelaide</option>
										<option <?php if($saasappoint_timezone=='Australia/Brisbane'){ echo "selected"; } ?> value="Australia/Brisbane" data-posinset="229">(GMT+10:00) Australian Eastern Standard Time - Brisbane</option>
										<option <?php if($saasappoint_timezone=='Pacific/Guam'){ echo "selected"; } ?> value="Pacific/Guam" data-posinset="230">(GMT+10:00) Chamorro Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Chuuk'){ echo "selected"; } ?> value="Pacific/Chuuk" data-posinset="231">(GMT+10:00) Chuuk Time</option>
										<option <?php if($saasappoint_timezone=='Antarctica/DumontDUrville'){ echo "selected"; } ?> value="Antarctica/DumontDUrville" data-posinset="232">(GMT+10:00) Dumont-d’Urville Time</option>
										<option <?php if($saasappoint_timezone=='Australia/Hobart'){ echo "selected"; } ?> value="Australia/Hobart" data-posinset="233">(GMT+10:00) Eastern Australia Time - Hobart</option>
										<option <?php if($saasappoint_timezone=='Australia/Melbourne'){ echo "selected"; } ?> value="Australia/Melbourne" data-posinset="234">(GMT+10:00) Eastern Australia Time - Melbourne</option>
										<option <?php if($saasappoint_timezone=='Australia/Sydney'){ echo "selected"; } ?> value="Australia/Sydney" data-posinset="235">(GMT+10:00) Eastern Australia Time - Sydney</option>
										<option <?php if($saasappoint_timezone=='Pacific/Port_Moresby'){ echo "selected"; } ?> value="Pacific/Port_Moresby" data-posinset="236">(GMT+10:00) Papua New Guinea Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Vladivostok'){ echo "selected"; } ?> value="Asia/Vladivostok" data-posinset="237">(GMT+10:00) Vladivostok Standard Time - Vladivostok</option>
										<option <?php if($saasappoint_timezone=='Pacific/Kosrae'){ echo "selected"; } ?> value="Pacific/Kosrae" data-posinset="238">(GMT+11:00) Kosrae Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Magadan'){ echo "selected"; } ?> value="Asia/Magadan" data-posinset="239">(GMT+11:00) Magadan Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Noumea'){ echo "selected"; } ?> value="Pacific/Noumea" data-posinset="240">(GMT+11:00) New Caledonia Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Norfolk'){ echo "selected"; } ?> value="Pacific/Norfolk" data-posinset="241">(GMT+11:00) Norfolk Island Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Pohnpei'){ echo "selected"; } ?> value="Pacific/Pohnpei" data-posinset="242">(GMT+11:00) Ponape Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Guadalcanal'){ echo "selected"; } ?> value="Pacific/Guadalcanal" data-posinset="243">(GMT+11:00) Solomon Islands Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Efate'){ echo "selected"; } ?> value="Pacific/Efate" data-posinset="244">(GMT+11:00) Vanuatu Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Fiji'){ echo "selected"; } ?> value="Pacific/Fiji" data-posinset="245">(GMT+12:00) Fiji Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Tarawa'){ echo "selected"; } ?> value="Pacific/Tarawa" data-posinset="246">(GMT+12:00) Gilbert Islands Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Kwajalein'){ echo "selected"; } ?> value="Pacific/Kwajalein" data-posinset="247">(GMT+12:00) Marshall Islands Time - Kwajalein</option>
										<option <?php if($saasappoint_timezone=='Pacific/Majuro'){ echo "selected"; } ?> value="Pacific/Majuro" data-posinset="248">(GMT+12:00) Marshall Islands Time - Majuro</option>
										<option <?php if($saasappoint_timezone=='Pacific/Nauru'){ echo "selected"; } ?> value="Pacific/Nauru" data-posinset="249">(GMT+12:00) Nauru Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Auckland'){ echo "selected"; } ?> value="Pacific/Auckland" data-posinset="250">(GMT+12:00) New Zealand Time</option>
										<option <?php if($saasappoint_timezone=='Asia/Kamchatka'){ echo "selected"; } ?> value="Asia/Kamchatka" data-posinset="251">(GMT+12:00) Petropavlovsk-Kamchatski Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Funafuti'){ echo "selected"; } ?> value="Pacific/Funafuti" data-posinset="252">(GMT+12:00) Tuvalu Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Wake'){ echo "selected"; } ?> value="Pacific/Wake" data-posinset="253">(GMT+12:00) Wake Island Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Wallis'){ echo "selected"; } ?> value="Pacific/Wallis" data-posinset="254">(GMT+12:00) Wallis &amp; Futuna Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Apia'){ echo "selected"; } ?> value="Pacific/Apia" data-posinset="255">(GMT+13:00) Apia Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Enderbury'){ echo "selected"; } ?> value="Pacific/Enderbury" data-posinset="256">(GMT+13:00) Phoenix Islands Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Fakaofo'){ echo "selected"; } ?> value="Pacific/Fakaofo" data-posinset="257">(GMT+13:00) Tokelau Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Tongatapu'){ echo "selected"; } ?> value="Pacific/Tongatapu" data-posinset="258">(GMT+13:00) Tonga Standard Time</option>
										<option <?php if($saasappoint_timezone=='Pacific/Kiritimati'){ echo "selected"; } ?> value="Pacific/Kiritimati" data-posinset="259">(GMT+14:00) Line Islands Time</option>
									</select>
								</div>
							  </div>
							  <hr />
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Currency</label>
									<select name="saasappoint_currency" id="saasappoint_currency" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search">
										<?php
										$saasappoint_currency = $obj_settings->get_option("saasappoint_currency");
										foreach($saasappoint_currency_array as $key=>$value){
											$selected = "";
											if($saasappoint_currency == $key){
												$selected = "selected";
											}
											echo '<option value="'.$key.'" data-symbol="'.html_entity_decode($saasappoint_currency_symbols[$key]).'" '.$selected.'>'.$value.' '.html_entity_decode($saasappoint_currency_symbols[$key]).'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Date Format</label>
									<?php $saasappoint_date_format = $obj_settings->get_option("saasappoint_date_format"); ?>
									<select name="saasappoint_date_format" id="saasappoint_date_format" class="form-control selectpicker">
										<option value="Y-m-d" <?php if($saasappoint_date_format == "d-m-Y"){ echo "selected"; } ?>>yyyy-mm-dd (eg. 2018-06-13)</option>
										<option value="d-m-Y" <?php if($saasappoint_date_format == "d-m-Y"){ echo "selected"; } ?>>dd-mm-yyyy (eg. 13-06-2018)</option>
										<option value="j-m-Y" <?php if($saasappoint_date_format == "j-m-Y"){ echo "selected"; } ?>>d-mm-yyyy (eg. 13-6-2018)</option>
										<option value="d-M-Y" <?php if($saasappoint_date_format == "d-M-Y"){ echo "selected"; } ?>>dd-m-yyyy (eg. 13-Jun-2018)</option>
										<option value="d-F-Y" <?php if($saasappoint_date_format == "d-F-Y"){ echo "selected"; } ?>>dd-m-yyyy (eg. 13-June-2018)</option>
										<option value="j-M-Y" <?php if($saasappoint_date_format == "j-M-Y"){ echo "selected"; } ?>>d-m-yyyy (eg. 13-Jun-2018)</option>
										<option value="j-F-Y" <?php if($saasappoint_date_format == "j-F-Y"){ echo "selected"; } ?>>dd-m-yyyy (eg. 13-June-2018)</option>

										<!-- With Slashes -->
										<option value="d/m/Y" <?php if($saasappoint_date_format == "d/m/Y"){ echo "selected"; } ?>>dd/mm/yyyy (eg. 13/06/2018)</option>
										<option value="j/m/Y" <?php if($saasappoint_date_format == "j/m/Y"){ echo "selected"; } ?>>d/mm/yyyy (eg. 13/06/2018)</option>
										<option value="d/M/Y" <?php if($saasappoint_date_format == "d/M/Y"){ echo "selected"; } ?>>dd/m/yyyy (eg. 13/Jun/2018)</option>
										<option value="d/F/Y" <?php if($saasappoint_date_format == "d/F/Y"){ echo "selected"; } ?>>dd/M/yyyy (eg. 13/June/2018)</option>
										<option value="j/M/Y" <?php if($saasappoint_date_format == "j/M/Y"){ echo "selected"; } ?>>d/m/yyyy (eg. 13/Jun/2018)</option>
										<option value="j/F/Y" <?php if($saasappoint_date_format == "j/F/Y"){ echo "selected"; } ?>>d/M/yyyy (eg. 13/June/2018)</option>

										<!-- Month Day Year Suffled -->
										<option value="m-d-Y" <?php if($saasappoint_date_format == "m-d-Y"){ echo "selected"; } ?>>mm-dd-yyyy (eg. 06-13-2018)</option>
										<option value="m-j-Y" <?php if($saasappoint_date_format == "m-j-Y"){ echo "selected"; } ?>>mm-d-yyyy (eg. 06-13-2018)</option>
										<option value="M-d-Y" <?php if($saasappoint_date_format == "M-d-Y"){ echo "selected"; } ?>>m-dd-yyyy (eg. Jun-13-2018)</option>
										<option value="F-d-Y" <?php if($saasappoint_date_format == "F-d-Y"){ echo "selected"; } ?>>m-dd-yyyy (eg. June-13-2018)</option>
										<option value="M-j-Y" <?php if($saasappoint_date_format == "M-j-Y"){ echo "selected"; } ?>>m-d-yyyy (eg. Jun-13-2018)</option>
										<option value="F-j-Y" <?php if($saasappoint_date_format == "F-j-Y"){ echo "selected"; } ?>>m-dd-yyyy (eg. June-13-2018)</option>
										<!-- With Slashes -->
										<option value="m/d/Y" <?php if($saasappoint_date_format == "m/d/Y"){ echo "selected"; } ?>>mm/dd/yyyy (eg. 06/13/2018)</option>
										<option value="m/j/Y" <?php if($saasappoint_date_format == "m/j/Y"){ echo "selected"; } ?>>mm/d/yyyy (eg. 06/13/2018)</option>
										<option value="M/d/Y" <?php if($saasappoint_date_format == "M/d/Y"){ echo "selected"; } ?>>m/dd/yyyy (eg. Jun/13/2018)</option>
										<option value="F/d/Y" <?php if($saasappoint_date_format == "F/d/Y"){ echo "selected"; } ?>>m/dd/yyyy (eg. June/13/2018)</option>
										<option value="M/j/Y" <?php if($saasappoint_date_format == "M/j/Y"){ echo "selected"; } ?>>m/d/yyyy (eg. Jun/13/2018)</option>
										<option value="F/j/Y" <?php if($saasappoint_date_format == "F/j/Y"){ echo "selected"; } ?>>m/dd/yyyy (eg. June/13/2018)</option>

										<option value="j M,Y" <?php if($saasappoint_date_format == "j M,Y"){ echo "selected"; } ?>>dd m,yyyy (eg. 13 Jun,2018)</option>
										<option value="M j, Y" <?php if($saasappoint_date_format == "M j, Y"){ echo "selected"; } ?>>m dd,yyyy (eg. Jun 13, 2018)</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Time Format</label>
									<?php $saasappoint_time_format = $obj_settings->get_option("saasappoint_time_format"); ?>
									<select name="saasappoint_time_format" id="saasappoint_time_format" class="form-control selectpicker">
									  <option value="12" <?php if($saasappoint_time_format == "12"){ echo "selected"; } ?>>12 Hours</option>
									  <option value="24" <?php if($saasappoint_time_format == "24"){ echo "selected"; } ?>>24 Hours</option>
									</select>
								</div>
							  </div>
							  <hr />
							  <div class="form-group row">
								<div class="col-md-4">
									<label class="control-label">Show Frontend Rightside Feedback List</label>
									<?php $saasappoint_show_frontend_rightside_feedback_list = $obj_settings->get_option("saasappoint_show_frontend_rightside_feedback_list"); ?>
									<select name="saasappoint_show_frontend_rightside_feedback_list" id="saasappoint_show_frontend_rightside_feedback_list" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_show_frontend_rightside_feedback_list == "Y"){ echo "selected"; } ?>>Yes</option>
									  <option value="N" <?php if($saasappoint_show_frontend_rightside_feedback_list == "N"){ echo "selected"; } ?>>No</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Show Frontend Rightside Feedback Form</label>
									<?php $saasappoint_show_frontend_rightside_feedback_form = $obj_settings->get_option("saasappoint_show_frontend_rightside_feedback_form"); ?>
									<select name="saasappoint_show_frontend_rightside_feedback_form" id="saasappoint_show_frontend_rightside_feedback_form" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_show_frontend_rightside_feedback_form == "Y"){ echo "selected"; } ?>>Yes</option>
									  <option value="N" <?php if($saasappoint_show_frontend_rightside_feedback_form == "N"){ echo "selected"; } ?>>No</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="control-label">Show Guest User Checkout</label>
									<?php $saasappoint_show_guest_user_checkout = $obj_settings->get_option("saasappoint_show_guest_user_checkout"); ?>
									<select name="saasappoint_show_guest_user_checkout" id="saasappoint_show_guest_user_checkout" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_show_guest_user_checkout == "Y"){ echo "selected"; } ?>>Yes</option>
									  <option value="N" <?php if($saasappoint_show_guest_user_checkout == "N"){ echo "selected"; } ?>>No</option>
									</select>
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-3">
									<label class="control-label">Minimum Booking Amount</label>
									<input type="text" name="saasappoint_minimum_booking_amount" id="saasappoint_minimum_booking_amount" value="<?php echo $obj_settings->get_option("saasappoint_minimum_booking_amount"); ?>" class="form-control" placeholder="e.g. 10" />
								</div>
								<div class="col-md-4">
									<label class="control-label">Show Existing & New User Checkout</label>
									<?php $saasappoint_show_existing_new_user_checkout = $obj_settings->get_option("saasappoint_show_existing_new_user_checkout"); ?>
									<select name="saasappoint_show_existing_new_user_checkout" id="saasappoint_show_existing_new_user_checkout" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_show_existing_new_user_checkout == "Y"){ echo "selected"; } ?>>Yes</option>
									  <option value="N" <?php if($saasappoint_show_existing_new_user_checkout == "N"){ echo "selected"; } ?>>No</option>
									</select>
								</div>
								<div class="col-md-5">
									<label class="control-label">Hide Already Booked Slots From Frontend Calendar</label>
									<?php $saasappoint_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option("saasappoint_hide_already_booked_slots_from_frontend_calendar"); ?>
									<select name="saasappoint_hide_already_booked_slots_from_frontend_calendar" id="saasappoint_hide_already_booked_slots_from_frontend_calendar" class="form-control selectpicker">
									  <option value="Y" <?php if($saasappoint_hide_already_booked_slots_from_frontend_calendar == "Y"){ echo "selected"; } ?>>Yes</option>
									  <option value="N" <?php if($saasappoint_hide_already_booked_slots_from_frontend_calendar == "N"){ echo "selected"; } ?>>No</option>
									</select>
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">Thank You Page URL</label>
									<input type="text" name="saasappoint_thankyou_page_url" id="saasappoint_thankyou_page_url" value="<?php echo $obj_settings->get_option("saasappoint_thankyou_page_url"); ?>" class="form-control" placeholder="e.g. <?php echo SITE_URL; ?>frontend/thankyou.php" />
								</div>
								<div class="col-md-6">
									<label class="control-label">Terms & Condition Link</label>
									<input type="text" name="saasappoint_terms_and_condition_link" id="saasappoint_terms_and_condition_link" value="<?php echo $obj_settings->get_option("saasappoint_terms_and_condition_link"); ?>" class="form-control" placeholder="e.g. <?php echo SITE_URL; ?>frontend/terms-condition.php" />
								</div>
							  </div>
							  <a id="update_appearance_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						 </form>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_payment_settings">
					  <br/>
					  <div class="row pl-3">
						<label class="col-md-3">Pay at Venue Payment Status</label>
						<label class="saasappoint-toggle-switch">
							<input type="checkbox" name="saasappoint_pay_at_venue_status" id="saasappoint_pay_at_venue_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_pay_at_venue_status")=="Y"){ echo "checked"; } ?> />
							<span class="saasappoint-toggle-switch-slider"></span>
						</label>
    				  </div>
					  <hr />
					  <div class="row">
						<div class="col-md-3">
							<div class="card saasappoint-boxshadow mt-1 mr-1 saasappoint_payment_settings_admin" id="saasappoint_payment_settings_admin_1" data-id="1">
							  <div class="card-body text-primary text-center">
								<i class="fa fa-cog" aria-hidden="true"></i> Paypal Settings
							  </div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mt-1 mr-1 card saasappoint-boxshadow saasappoint_payment_settings_admin" id="saasappoint_payment_settings_admin_2" data-id="2">
							  <div class="card-body text-primary text-center">
								<i class="fa fa-cog" aria-hidden="true"></i> Stripe Settings
							  </div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mt-1 mr-1 card saasappoint-boxshadow saasappoint_payment_settings_admin" id="saasappoint_payment_settings_admin_3" data-id="3">
							  <div class="card-body text-primary text-center">
								<i class="fa fa-cog" aria-hidden="true"></i> Authorize.net Settings
							  </div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mt-1 mr-1 card saasappoint-boxshadow saasappoint_payment_settings_admin" id="saasappoint_payment_settings_admin_4" data-id="4">
							  <div class="card-body text-primary text-center">
								<i class="fa fa-cog" aria-hidden="true"></i> 2Checkout Settings
							  </div>
							</div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_email_settings">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
							<form name="saasappoint_email_settings_form" id="saasappoint_email_settings_form" method="post">
								<div class="row">
									<label class="col-md-3">Admin Email Notifications</label>
									<label class="saasappoint-toggle-switch">
										<input type="checkbox" name="saasappoint_admin_email_notification_status" id="saasappoint_admin_email_notification_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_admin_email_notification_status")=="Y"){ echo "checked"; } ?> />
										<span class="saasappoint-toggle-switch-slider"></span>
									</label>
								</div>
								<div class="row">
									<label class="col-md-3">Customer Email Notifications</label>
									<label class="saasappoint-toggle-switch">
										<input type="checkbox" name="saasappoint_customer_email_notification_status" id="saasappoint_customer_email_notification_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_customer_email_notification_status")=="Y"){ echo "checked"; } ?> />
										<span class="saasappoint-toggle-switch-slider"></span>
									</label>
								</div>
								<div class="form-group row">
									<div class="col-md-6">
										<label class="control-label">Sender Name</label>
										<input name="saasappoint_email_sender_name" id="saasappoint_email_sender_name" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_email_sender_name"); ?>" placeholder="Enter Sender Name" />
									</div>
									<div class="col-md-6">
										<label class="control-label">Sender Email</label>
										<input name="saasappoint_email_sender_email" id="saasappoint_email_sender_email" class="form-control" type="email" value="<?php echo $obj_settings->get_option("saasappoint_email_sender_email"); ?>" placeholder="Enter Sender Email" />
									</div>
								</div>
								<a id="update_email_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
							</form>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_sms_settings">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
							<div class="row">
								<label class="col-md-3 control-label">Total SMS credit left: <b><?php echo $obj_settings->get_option("saasappoint_sms_credit"); ?></b></label>
								<a href="javascript:void(0)" id="saasappoint-buy-sms-credit-btn">Buy SMS Credit</a>
							</div>
							<div class="row">
								<label class="col-md-3">Admin SMS Notifications</label>
								<label class="saasappoint-toggle-switch">
									<input type="checkbox" id="saasappoint_admin_sms_notification_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_admin_sms_notification_status")=="Y"){ echo "checked"; } ?> />
									<span class="saasappoint-toggle-switch-slider"></span>
								</label>
							</div>
							<div class="row">
								<label class="col-md-3">Customer SMS Notifications</label>
								<label class="saasappoint-toggle-switch">
									<input type="checkbox" id="saasappoint_customer_sms_notification_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_customer_sms_notification_status")=="Y"){ echo "checked"; } ?> />
									<span class="saasappoint-toggle-switch-slider"></span>
								</label>
							</div>
							<a id="update_sms_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_seo_settings">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <form name="saasappoint_seo_settings_form" id="saasappoint_seo_settings_form" method="post" enctype="multipart/form-data">
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">Google Analytics Code</label>
									<input name="saasappoint_seo_ga_code" id="saasappoint_seo_ga_code" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_seo_ga_code"); ?>" placeholder="e.g. XX-XXXXXXXXX-X" />
								</div>
								<div class="col-md-6">
									<label class="control-label">Page Title (Meta Tag)</label>
									<input name="saasappoint_seo_meta_tag" id="saasappoint_seo_meta_tag" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_seo_meta_tag"); ?>" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">og Page Title (og Meta Tag)</label>
									<input name="saasappoint_seo_og_meta_tag" id="saasappoint_seo_og_meta_tag" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_seo_og_meta_tag"); ?>" />
								</div>
								<div class="col-md-6">
									<label class="control-label">og Tag Type</label>
									<input name="saasappoint_seo_og_tag_type" id="saasappoint_seo_og_tag_type" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_seo_og_tag_type"); ?>" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">og Tag URL</label>
									<input name="saasappoint_seo_og_tag_url" id="saasappoint_seo_og_tag_url" class="form-control" type="text" value="<?php echo $obj_settings->get_option("saasappoint_seo_og_tag_url"); ?>" />
								</div>
								<div class="col-md-6">
									<label class="control-label">Meta Description</label>
									<textarea name="saasappoint_seo_meta_description" id="saasappoint_seo_meta_description" class="form-control"><?php echo $obj_settings->get_option("saasappoint_seo_meta_description"); ?></textarea>
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">og Tag Image</label>
									<div class="saasappoint-image-upload">
										<div class="saasappoint-image-edit-icon">
											<input type='hidden' id="saasappoint_seo_og_tag_image-hidden" name="saasappoint_seo_og_tag_image-hidden" />
											<input type='file' id="saasappoint_seo_og_tag_image" accept=".png, .jpg, .jpeg" />
											<label for="saasappoint_seo_og_tag_image"></label>
										</div>
										<div class="saasappoint-image-preview">
											<div id="saasappoint_seo_og_tag_image-preview" style="<?php $og_tag_image = $obj_settings->get_option("saasappoint_seo_og_tag_image"); if($og_tag_image != '' && file_exists("../includes/images/".$og_tag_image)){ echo "background-image: url(".SITE_URL."includes/images/".$og_tag_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-avatar.jpg);"; } ?>">
											</div>
										</div>
									</div>
								</div>
							  </div>
							  <a id="update_seo_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						 </form>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_welcome_settings">
					  <br/>
						  <div class="form-group row pl-4">
							<div class="col-md-4">
								<label class="control-label">Welcome Message Status</label>
								<?php $saasappoint_welcome_message_status = $obj_settings->get_option("saasappoint_welcome_message_status"); ?>
								<select name="saasappoint_welcome_message_status" id="saasappoint_welcome_message_status" class="form-control selectpicker">
								  <option value="Y" <?php if($saasappoint_welcome_message_status == "Y"){ echo "selected"; } ?>>Enable</option>
								  <option value="N" <?php if($saasappoint_welcome_message_status == "N" || $saasappoint_welcome_message_status == ""){ echo "selected"; } ?>>Disable</option>
								</select>
							</div>
						  </div>	  
						<div class="row pl-4">
							<h5 class="col-md-12">Welcome Message Content:</h5>
						</div>
						<div class="col-md-12">
							<div class="col-md-12 mt-2">
								<div class="form-group">
									<textarea name="saasappoint_welcome_message_container" class="saasappoint_welcome_message_container saasappoint_text_editor_container" id="saasappoint_welcome_message_container" autocomplete="off"><?php echo base64_decode($obj_settings->get_option("saasappoint_welcome_message_container")); ?></textarea>
								</div>
							</div>
							<a id="update_welcome_message_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						</div>
					</div>
					
					<div class="tab-pane container fade" id="saasappoint_bookingform_settings">
					  <br/>	
					  <form name="saasappoint_bookingform_settings_form" id="saasappoint_bookingform_settings_form" method="post" enctype="multipart/form-data">
						  <div class="form-group row">
							<div class="col-md-6">
								<label class="control-label">Single Category Auto Trigger Status</label>
								<?php $saasappoint_single_category_autotrigger_status = $obj_settings->get_option("saasappoint_single_category_autotrigger_status"); ?>
								<select name="saasappoint_single_category_autotrigger_status" id="saasappoint_single_category_autotrigger_status" class="form-control selectpicker">
								  <option value="Y" <?php if($saasappoint_single_category_autotrigger_status == "Y"){ echo "selected"; } ?>>Enable</option>
								  <option value="N" <?php if($saasappoint_single_category_autotrigger_status == "N" || $saasappoint_single_category_autotrigger_status == ""){ echo "selected"; } ?>>Disable</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="control-label">Single Service Auto Trigger Status</label>
								<?php $saasappoint_single_service_autotrigger_status = $obj_settings->get_option("saasappoint_single_service_autotrigger_status"); ?>
								<select name="saasappoint_single_service_autotrigger_status" id="saasappoint_single_service_autotrigger_status" class="form-control selectpicker">
								  <option value="Y" <?php if($saasappoint_single_service_autotrigger_status == "Y"){ echo "selected"; } ?>>Enable</option>
								  <option value="N" <?php if($saasappoint_single_service_autotrigger_status == "N" || $saasappoint_single_service_autotrigger_status == ""){ echo "selected"; } ?>>Disable</option>
								</select>
							</div>
						  </div>
						  <div class="form-group row">
							<div class="col-md-4">
								<label class="control-label">Booking Form Background</label>
								<?php $saasappoint_bookingform_bg = $obj_settings->get_option("saasappoint_bookingform_bg"); ?>
								<select name="saasappoint_bookingform_bg" id="saasappoint_bookingform_bg" class="form-control selectpicker">
								  <option value="default" <?php if($saasappoint_bookingform_bg == "default"){ echo "selected"; } ?>>Default</option>
								  <option value="custom" <?php if($saasappoint_bookingform_bg == "custom"){ echo "selected"; } ?>>Custom</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="control-label">Booking Form Background Type</label>
								<?php $saasappoint_bookingform_bg_type = $obj_settings->get_option("saasappoint_bookingform_bg_type"); ?>
								<select name="saasappoint_bookingform_bg_type" id="saasappoint_bookingform_bg_type" class="form-control selectpicker">
								  <option value="image" <?php if($saasappoint_bookingform_bg_type == "image"){ echo "selected"; } ?>>Image</option>
								  <option value="color" <?php if($saasappoint_bookingform_bg_type == "color"){ echo "selected"; } ?>>Color</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="control-label">Booking Form Background Color</label><br />
								<input name="saasappoint_bookingform_bg_color" id="saasappoint_bookingform_bg_color" class="form-control col-md-3" type="color" value="<?php echo $obj_settings->get_option("saasappoint_bookingform_bg_color"); ?>"  />
							</div>
						  </div>	  
						  <div class="form-group row">
							<div class="col-md-6">
								<label class="control-label">Booking Form Background Image</label>
								<div class="saasappoint-image-upload">
									<div class="saasappoint-image-edit-icon">
										<input type='hidden' id="saasappoint_bookingform_bg_image-hidden" name="saasappoint_bookingform_bg_image-hidden" />
										<input type='file' id="saasappoint_bookingform_bg_image" accept=".png, .jpg, .jpeg" />
										<label for="saasappoint_bookingform_bg_image"></label>
									</div>
									<div class="saasappoint-image-preview">
										<div id="saasappoint_bookingform_bg_image-preview" style="<?php $og_tag_image = $obj_settings->get_option("saasappoint_bookingform_bg_image"); if($og_tag_image != '' && file_exists("../includes/images/".$og_tag_image)){ echo "background-image: url(".SITE_URL."includes/images/".$og_tag_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-avatar.jpg);"; } ?>">
										</div>
									</div>
								</div>
							</div>
						  </div>
						  <a id="update_bookingform_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						</form>
					</div>
			  </div>
			</div>
		</div>
	 </div>
	<!-- Payment Setting Form Modal-->
    <div class="modal fade" id="saasappoint-payment-setting-form-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-payment-setting-form-modal-label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-payment-setting-form-modal-label">Payment Settings</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body saasappoint-payment-setting-form-modal-content">
			
		  </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a id="update_payment_settings_btn" data-payment="" class="btn btn-primary" href="javascript:void(0);">Save Settings</a>
          </div>
        </div>
      </div>
    </div>
	<!-- Buy SMS Credit Modal-->
    <div class="modal fade" id="saasappoint-buy-sms-credit-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-buy-sms-credit-modal-label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-buy-sms-credit-modal-label">Buy SMS Credit</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body saasappoint-buy-sms-credit-modal-body">
			
		  </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a id="saasappoint_buy_sms_plan_credit_btn" class="btn btn-primary" href="javascript:void(0);">Buy Credit</a>
          </div>
        </div>
      </div>
    </div>
<?php include 'footer.php'; ?>