<?php 
include 'header.php';
include 'currency.php'; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Referral Discount Settings</li>
      </ol>
	  <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_referral_settings"><i class="fa fa-gift"></i> Referral Settings</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_referral_settings">
					  <div class="row">
						<div class="col-md-12">
						  <form name="saasappoint_referral_settings_form" id="saasappoint_referral_settings_form" method="post">
							<div class="row my-3">
								<label class="col-md-3">Referral Discount Status</label>
								<label class="saasappoint-toggle-switch">
									<input type="checkbox" name="saasappoint_referral_discount_status" id="saasappoint_referral_discount_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_referral_discount_status")=="Y"){ echo "checked"; } ?> />
									<span class="saasappoint-toggle-switch-slider"></span>
								</label>
							</div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label">Referral Discount Type</label>
									<?php $saasappoint_referral_discount_type = $obj_settings->get_option("saasappoint_referral_discount_type"); ?>
									<select name="saasappoint_referral_discount_type" id="saasappoint_referral_discount_type" class="form-control selectpicker">
									  <option value="percentage" <?php if($saasappoint_referral_discount_type == "percentage"){ echo "selected"; } ?>>Percentage</option>
									  <option value="flat" <?php if($saasappoint_referral_discount_type == "flat"){ echo "selected"; } ?>>Flat</option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="control-label">Referral Discount Value</label>
									<input type="text" name="saasappoint_referral_discount_value" id="saasappoint_referral_discount_value" placeholder="e.g. 10" class="form-control" value="<?php echo $obj_settings->get_option("saasappoint_referral_discount_value"); ?>" />
								</div>
							  </div>
							  <a id="update_referral_settings_btn" class="btn btn-success btn-block" href="javascript:void(0);">Update Settings</a>
						 </form>
						</div>
					  </div>
					</div>
			  </div>
			</div>
		</div>
	 </div>
<?php include 'footer.php'; ?>