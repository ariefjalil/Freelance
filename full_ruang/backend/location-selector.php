<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Location Selector</li>
      </ol>
      <!-- Coupon DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-map-marker"></i> Location List
		  </div>
        <div class="card-body">
			<div class="row mb-3 pl-3">
				<label class="col-md-3">Location Selector Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_location_selector_status" id="saasappoint_location_selector_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_location_selector_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="col-md-12">
				<input id="saasappoint_location_selector" type="text" class="w-100" value="<?php echo $obj_settings->get_option("saasappoint_location_selector"); ?>" data-role="tagsinput" placeholder="Enter zipcode" />
			</div>
			<div class="col-md-12 mt-4">
				<div class="form-group">
					<textarea name="saasappoint_location_selector_container" class="saasappoint_location_selector_container saasappoint_text_editor_container" id="saasappoint_location_selector_container" autocomplete="off"><?php echo base64_decode($obj_settings->get_option("saasappoint_location_selector_container")); ?></textarea>
				</div>
			</div>
			<div class="col-md-12 mt-4">
				<a id="save_location_selector_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Save Location Selector Settings</a>
			</div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
<?php include 'footer.php'; ?>