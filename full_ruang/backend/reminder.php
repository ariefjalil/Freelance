<?php include 's_header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Email & SMS Templates</li>
      </ol>
      <!-- DataTables Card-->
	  <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				 <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_templates_reminder_settings"><i class="fa fa-bell"></i> Email & SMS Reminder Cron job Settings</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active container" id="saasappoint_templates_reminder_settings">
						<div class="row mb-3 mt-3">
							<div class="col-md-12">
								<h5>Appointment Reminder Buffer Time</h5>
							</div>
							<div class="col-md-4">
								<?php $saasappoint_reminder_buffer_time = $obj_settings->get_superadmin_option("saasappoint_reminder_buffer_time"); ?>
								<select name="saasappoint_reminder_buffer_time" id="saasappoint_reminder_buffer_time" class="form-control selectpicker">
								  <option value="15" <?php if($saasappoint_reminder_buffer_time == "15"){ echo "selected"; } ?>>15 Minutes</option>
								  <option value="20" <?php if($saasappoint_reminder_buffer_time == "20"){ echo "selected"; } ?>>20 Minutes</option>
								  <option value="30" <?php if($saasappoint_reminder_buffer_time == "30"){ echo "selected"; } ?>>30 Minutes</option>
								  <option value="45" <?php if($saasappoint_reminder_buffer_time == "45"){ echo "selected"; } ?>>45 Minutes</option>
								  <option value="60" <?php if($saasappoint_reminder_buffer_time == "60"){ echo "selected"; } ?>>1 Hour</option>
								  <option value="75" <?php if($saasappoint_reminder_buffer_time == "75"){ echo "selected"; } ?>>1 Hour 15 Minutes</option>
								  <option value="90" <?php if($saasappoint_reminder_buffer_time == "90"){ echo "selected"; } ?>>1 Hour 30 Minutes</option>
								  <option value="105" <?php if($saasappoint_reminder_buffer_time == "105"){ echo "selected"; } ?>>1 Hour 45 Minutes</option>
								  <option value="120" <?php if($saasappoint_reminder_buffer_time == "120"){ echo "selected"; } ?>>2 Hour</option>
								  <option value="135" <?php if($saasappoint_reminder_buffer_time == "135"){ echo "selected"; } ?>>2 Hour 15 Minutes</option>
								  <option value="150" <?php if($saasappoint_reminder_buffer_time == "150"){ echo "selected"; } ?>>2 Hour 30 Minutes</option>
								  <option value="165" <?php if($saasappoint_reminder_buffer_time == "165"){ echo "selected"; } ?>>2 Hour 45 Minutes</option>
								  <option value="180" <?php if($saasappoint_reminder_buffer_time == "180"){ echo "selected"; } ?>>3 Hour</option>
								  <option value="195" <?php if($saasappoint_reminder_buffer_time == "195"){ echo "selected"; } ?>>3 Hour 15 Minutes</option>
								  <option value="210" <?php if($saasappoint_reminder_buffer_time == "210"){ echo "selected"; } ?>>3 Hour 30 Minutes</option>
								  <option value="225" <?php if($saasappoint_reminder_buffer_time == "225"){ echo "selected"; } ?>>3 Hour 45 Minutes</option>
								  <option value="240" <?php if($saasappoint_reminder_buffer_time == "240"){ echo "selected"; } ?>>4 Hour</option>
								  <option value="300" <?php if($saasappoint_reminder_buffer_time == "300"){ echo "selected"; } ?>>5 Hour</option>
								  <option value="360" <?php if($saasappoint_reminder_buffer_time == "360"){ echo "selected"; } ?>>6 Hour</option>
								  <option value="420" <?php if($saasappoint_reminder_buffer_time == "420"){ echo "selected"; } ?>>7 Hour</option>
								  <option value="480" <?php if($saasappoint_reminder_buffer_time == "480"){ echo "selected"; } ?>>8 Hour</option>
								  <option value="540" <?php if($saasappoint_reminder_buffer_time == "540"){ echo "selected"; } ?>>9 Hour</option>
								  <option value="600" <?php if($saasappoint_reminder_buffer_time == "600"){ echo "selected"; } ?>>10 Hour</option>
								  <option value="660" <?php if($saasappoint_reminder_buffer_time == "660"){ echo "selected"; } ?>>11 Hour</option>
								  <option value="720" <?php if($saasappoint_reminder_buffer_time == "720"){ echo "selected"; } ?>>12 Hour</option>
								  <option value="1440" <?php if($saasappoint_reminder_buffer_time == "1440"){ echo "selected"; } ?>>24 Hour</option>
								  <option value="2160" <?php if($saasappoint_reminder_buffer_time == "2160"){ echo "selected"; } ?>>36 Hour</option>
								  <option value="2880" <?php if($saasappoint_reminder_buffer_time == "2880"){ echo "selected"; } ?>>48 Hour</option>
								</select>
							</div>
						</div>
						<div class="row mb-3 mt-3">
							<div class="col-md-12 mb-3 border p-3">
								<h4 class="mb-3 text-center"><i class="fa fa-bell-o"></i> Appointment Reminder Cron job</h4>
								<p class="border p-2">Use this URL in your Cron job: <code><?php echo SITE_URL; ?>includes/cron/saasappoint_appointment_reminder.php</code></p>
								<p class="border p-2"><i class="fa fa-info-circle"></i> Manually setup a Cron job on your host's administration panel (cPanel or Plesk) that will access above Cron URL.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
<?php include 's_footer.php'; ?>