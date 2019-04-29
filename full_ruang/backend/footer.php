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
				<a class="btn btn-danger saasappoint-white saasappoint_delete_appt_btn" data-id=""><i class="fa fa-fw fa-trash"></i></a>
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
            <a class="btn btn-primary saasappoint_change_password_btn" data-id="<?php if(isset($_SESSION['admin_id'])){ echo $_SESSION['admin_id']; } ?>" href="javascript:void(0);">Change Password</a>
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
				<label><b>Step 1: </b> Add Categories, Services & Addons to setup your business.</label>
			</div>
			<div class="row border p-4">
				<label><b>Step 2: </b> Configure your business schedule to manage booking slots.</label>
			</div>
			<div class="row border p-4">
				<label><b>Step 3: </b> Configure your company settings from settings section to manage your business details</label>
			</div>
		  </div>
        </div>
      </div>
    </div>
	<?php 
	$check_for_setup_instruction_modal = $obj_settings->check_for_setup_instruction_modal(); 
	if($check_for_setup_instruction_modal == "N"){
		if($obj_settings->get_option("saasappoint_company_name") == "" || $obj_settings->get_option("saasappoint_company_email") == "" || $obj_settings->get_option("saasappoint_company_phone") == ""){
			$check_for_setup_instruction_modal = "Y";
		}
	} 
	$saasappoint_paypal_payment_status = $obj_settings->get_superadmin_option("saasappoint_paypal_payment_status");
	$saasappoint_stripe_payment_status = $obj_settings->get_superadmin_option("saasappoint_stripe_payment_status"); 
	$saasappoint_authorizenet_payment_status = $obj_settings->get_superadmin_option("saasappoint_authorizenet_payment_status"); 
	$saasappoint_twocheckout_payment_status = $obj_settings->get_superadmin_option("saasappoint_twocheckout_payment_status"); 
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
		var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'current_date' : '<?php echo date('Y-m-d', $currDateTime_withTZ); ?>', 'twocheckout_status' : '<?php echo $saasappoint_twocheckout_payment_status; ?>', 'twocheckout_sid' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_twocheckout_seller_id'); ?>', 'twocheckout_pkey' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_twocheckout_publishable_key'); ?>', 'stripe_status' : '<?php echo $saasappoint_stripe_payment_status; ?>', 'stripe_pkey' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_stripe_publickey'); ?>', 'setup_instruction_modal_status': '<?php echo $check_for_setup_instruction_modal; ?>', 'ty_link' : '<?php echo $obj_settings->get_option('saasappoint_thankyou_page_url'); ?>', 'endslot_status' : '<?php echo $obj_settings->get_option('saasappoint_endtimeslot_selection_status'); ?>', 'single_category_status' : '<?php echo $obj_settings->get_option('saasappoint_single_category_autotrigger_status'); ?>', 'single_service_status' : '<?php echo $obj_settings->get_option('saasappoint_single_service_autotrigger_status'); ?>' };
	</script>
	
	<?php if($saasappoint_authorizenet_payment_status == "Y" || $saasappoint_twocheckout_payment_status == "Y"){ ?>
	<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.payment.min.js?<?php echo time(); ?>" type="text/javascript"></script>
	<script>
	$(document).ready( function(){
		/** card payment validation **/
		$('#saasappoint-cardnumber').payment('formatCardNumber');
		$('#saasappoint-cardcvv').payment('formatCardCVC');
		$('#saasappoint-cardexmonth').payment('restrictNumeric');
		$('#saasappoint-cardexyear').payment('restrictNumeric');
	});
	$(document).ajaxComplete( function(){
		/** card payment validation **/
		$('#saasappoint-cardnumber').payment('formatCardNumber');
		$('#saasappoint-cardcvv').payment('formatCardCVC');
		$('#saasappoint-cardexmonth').payment('restrictNumeric');
		$('#saasappoint-cardexyear').payment('restrictNumeric');
	});
	</script>
	<?php } ?>
	
	<?php if($saasappoint_twocheckout_payment_status == 'Y'){ ?>
	<script src="https://www.2checkout.com/checkout/api/2co.min.js" type="text/javascript"></script>	
	<?php } ?>
	
	<?php if ($saasappoint_stripe_payment_status == 'Y' && (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'subscription.php') != false)) { ?>
		<script src="https://js.stripe.com/v3/" type="text/javascript"></script>
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { ?>
		<script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap-tagsinput.js?<?php echo time(); ?>"></script>
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { ?>
		<!-- include text editor -->
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/text-editor/text-editor.js?<?php echo time(); ?>"></script>
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false) { ?>
		<!-- Bootstrap core JavaScript and Custom Page level plugin JavaScript-->
		<script src="<?php echo SITE_URL; ?>includes/manual-booking/js/popper.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/manual-booking/js/slick.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/manual-booking/js/datepicker.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/manual-booking/js/saasappoint-mb-jquery.js?<?php echo time(); ?>"></script>
	<?php } ?>
	<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
	<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-admin.js?<?php echo time(); ?>"></script>
  </div>
</body>
</html>