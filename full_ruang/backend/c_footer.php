</div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#saasappoint-page-top">
      <i class="fa fa-angle-up"></i>
    </a>
	
	<!-- Appointment Detail Modal-->
	<div class="modal fade" id="saasappoint_appointment_detail_modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint_appointment_detail_modal_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint_appointment_detail_modal_label">Appointment Detail</h5>
			<div class="pull-right">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
		  </div>
		  <div class="modal-body saasappoint_appointment_detail_modal_body">
			<center><h2>Please wait...</h2></center>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>	
	
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
            <a class="btn btn-primary saasappoint_change_password_btn" data-id="<?php if(isset($_SESSION['customer_id'])){ echo $_SESSION['customer_id']; } ?>" href="javascript:void(0);">Change Password</a>
          </div>
        </div>
      </div>
    </div>
	
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
		var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'current_date' : '<?php echo date('Y-m-d'); ?>' };
	</script>
	<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
	<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-customer.js?<?php echo time(); ?>"></script>
  </div>
</body>
</html>