</div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#saasappoint-page-top">
      <i class="fa fa-angle-up"></i>
    </a>
	
    <!-- Logout Modal-->
    <div class="modal fade" id="saasappoint-logout-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-logout-modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-logout-modal-label">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a id="saasappoint_logout_btn" class="btn btn-primary" href="javascript:void(0)">Logout</a>
          </div>
        </div>
      </div>
    </div>
	
    <!-- Change Password Modal-->
    <div class="modal fade" id="saasappoint-change-password-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-change-password-modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-change-password-modal-label">Change Password</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
			<form name="saasappoint_change_password_form" id="saasappoint_change_password_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_old_password">Old Password</label>
				<input class="form-control" id="saasappoint_old_password" name="saasappoint_old_password" type="password" placeholder="Enter Old Password" autocomplete="off" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_new_password">New Password</label>
				<input class="form-control" id="saasappoint_new_password" name="saasappoint_new_password" type="password" placeholder="Enter New Password" autocomplete="off" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_rtype_password">Retype New Password</label>
				<input class="form-control" id="saasappoint_rtype_password" name="saasappoint_rtype_password" type="password" placeholder="Enter Retype New Password" autocomplete="off" />
			  </div>
			</form>
		  </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary saasappoint_change_password_btn" data-id="<?php if(isset($_SESSION['superadmin_id'])){ echo $_SESSION['superadmin_id']; } ?>" href="javascript:void(0);">Change Password</a>
          </div>
        </div>
      </div>
    </div>
	
    <!-- Setup instruction Modal-->
    <div class="modal fade" id="saasappoint-setup-instruction-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-setup-instruction-modal-label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-setup-instruction-modal-label">Setup instructions</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
			<div class="row border p-4">
				<label><b>Step 1: </b> Add business types to setup your business criteria.</label>
			</div>
			<div class="row border p-4">
				<label><b>Step 2: </b> Add subscription plan to setup monthly/yearly recurrence for your business.</label>
			</div>
			<div class="row border p-4">
				<label><b>Step 3: </b> Configure your company, payment & email settings from settings section to manage your business process</label>
			</div>
		  </div>
        </div>
      </div>
    </div>
	<?php 
	$check_for_setup_instruction_modal = $obj_business_type->check_for_setup_instruction_modal(); 
	if($check_for_setup_instruction_modal == "N"){
		if($obj_settings->get_superadmin_option("saasappoint_email_sender_name") == "" || $obj_settings->get_superadmin_option("saasappoint_email_sender_email") == "" || $obj_settings->get_superadmin_option("saasappoint_company_name") == "" || $obj_settings->get_superadmin_option("saasappoint_company_email") == "" || $obj_settings->get_superadmin_option("saasappoint_company_phone") == ""){
			$check_for_setup_instruction_modal = "Y";
		}
	}	
	?>	
	<!-- Bootstrap core JavaScript-->
    <script src='<?php echo SITE_URL; ?>includes/vendor/calendar/moment.min.js?<?php echo time(); ?>'></script>
	<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.validate.min.js?<?php echo time(); ?>"></script>
	<script src='<?php echo SITE_URL; ?>includes/vendor/calendar/fullcalendar.min.js?<?php echo time(); ?>'></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap.bundle.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap-select.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo SITE_URL; ?>includes/vendor/jquery-easing/jquery.easing.min.js?<?php echo time(); ?>"></script>
    <!-- Page level plugin JavaScript-->
    <script src="<?php echo SITE_URL; ?>includes/vendor/datatables/datatables.min.js?<?php echo time(); ?>"></script>
    <!-- Custom scripts for all pages-->
	<script>
		var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'setup_instruction_modal_status': '<?php echo $check_for_setup_instruction_modal; ?>' };
	</script>
	<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
	<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-superadmin.js?<?php echo time(); ?>"></script>
  </div>
</body>
</html>