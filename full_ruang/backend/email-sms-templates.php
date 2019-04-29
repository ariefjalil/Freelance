<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Email & SMS Templates</li>
      </ol>
      <!-- DataTables Card-->
	  <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_emailtemplates_settings"><i class="fa fa-envelope"></i> Email Templates</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#saasappoint_smstemplates_settings"><i class="fa fa-comments"></i> SMS Templates</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_emailtemplates_settings">
						<div class="row mb-3">
							<div class="col-md-6 mb-3">
								<div class="card saasappoint-boxshadow mt-1 mr-1 saasappoint_emailtemplate_settings_customer">
								  <div class="card-body text-primary text-center">
									<i class="fa fa-columns" aria-hidden="true"></i> Customer Email Templates
								  </div>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="mt-1 mr-1 card saasappoint-boxshadow saasappoint_emailtemplate_settings_admin">
								  <div class="card-body text-primary text-center">
									<i class="fa fa-columns" aria-hidden="true"></i> Admin Email Templates
								  </div>
								</div>
							</div>
						</div>
						<div class="saasappoint_customer_email_templates">
							<div class="saasappoint-es-box bg-info rounded">
								<center><h4 class="text-white pt-3 pb-0 mb-0">Customer Email Templates</h4></center>
								<div class="row mt-2 mb-4 ml-4 mr-4">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-info-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Book New Appointment</h4></div>
											<a href="javascript:void(0)" data-template="new" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Confirm Appointment</h4></div>
											<a href="javascript:void(0)" data-template="confirm" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-refresh fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by Customer</h4></div>
											<a href="javascript:void(0)" data-template="reschedulec" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Cancel by Customer</h4></div>
											<a href="javascript:void(0)" data-template="cancelc" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-repeat fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by You</h4></div>
											<a href="javascript:void(0)" data-template="reschedulea" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-ban fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reject by You</h4></div>
											<a href="javascript:void(0)" data-template="rejecta" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-calendar-check-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Complete Appointment</h4></div>
											<a href="javascript:void(0)" data-template="complete" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-bell-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Appointment Reminder</h4></div>
											<a href="javascript:void(0)" data-template="reminder" data-template_for="customer" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
								</div>
							</div>
						</div>
						<div class="saasappoint_admin_email_templates">
							<div class="saasappoint-es-box bg-dark rounded">
								<center><h4 class="text-white pt-3 pb-0 mb-0">Admin Email Templates</h4></center>
								<div class="row mt-2 mb-4 ml-4 mr-4">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-info-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Book New Appointment</h4></div>
											<a href="javascript:void(0)" data-template="new" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Confirm Appointment</h4></div>
											<a href="javascript:void(0)" data-template="confirm" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-refresh fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by Customer</h4></div>
											<a href="javascript:void(0)" data-template="reschedulec" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Cancel by Customer</h4></div>
											<a href="javascript:void(0)" data-template="cancelc" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-repeat fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by You</h4></div>
											<a href="javascript:void(0)" data-template="reschedulea" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-ban fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reject by You</h4></div>
											<a href="javascript:void(0)" data-template="rejecta" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-calendar-check-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Complete Appointment</h4></div>
											<a href="javascript:void(0)" data-template="complete" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-bell-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Appointment Reminder</h4></div>
											<a href="javascript:void(0)" data-template="reminder" data-template_for="admin" class="saasappoint-es-box-a saasappoint_email_template_modal_btn">Customize Template</a>
										 </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane container" id="saasappoint_smstemplates_settings">
						<div class="row mb-3">
							<div class="col-md-6 mb-3">
								<div class="card saasappoint-boxshadow mt-1 mr-1 saasappoint_smstemplate_settings_customer">
								  <div class="card-body text-primary text-center">
									<i class="fa fa-columns" aria-hidden="true"></i> Customer SMS Templates
								  </div>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="mt-1 mr-1 card saasappoint-boxshadow saasappoint_smstemplate_settings_admin">
								  <div class="card-body text-primary text-center">
									<i class="fa fa-columns" aria-hidden="true"></i> Admin SMS Templates
								  </div>
								</div>
							</div>
						</div>
						<div class="saasappoint_customer_sms_templates">
							<div class="saasappoint-es-box bg-info rounded">
								<center><h4 class="text-white pt-3 pb-0 mb-0">Customer SMS Templates</h4></center>
								<div class="row mt-2 mb-4 ml-4 mr-4">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-info-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Book New Appointment</h4></div>
											<a href="javascript:void(0)" data-template="new" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Confirm Appointment</h4></div>
											<a href="javascript:void(0)" data-template="confirm" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-refresh fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by Customer</h4></div>
											<a href="javascript:void(0)" data-template="reschedulec" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Cancel by Customer</h4></div>
											<a href="javascript:void(0)" data-template="cancelc" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-repeat fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by You</h4></div>
											<a href="javascript:void(0)" data-template="reschedulea" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-ban fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reject by You</h4></div>
											<a href="javascript:void(0)" data-template="rejecta" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-calendar-check-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Complete Appointment</h4></div>
											<a href="javascript:void(0)" data-template="complete" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-bell-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Appointment Reminder</h4></div>
											<a href="javascript:void(0)" data-template="reminder" data-template_for="customer" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
								</div>
							</div>
						</div>
						<div class="saasappoint_admin_sms_templates">
							<div class="saasappoint-es-box bg-dark rounded">
								<center><h4 class="text-white pt-3 pb-0 mb-0">Admin SMS Templates</h4></center>
								<div class="row mt-2 mb-4 ml-4 mr-4">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-info-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Book New Appointment</h4></div>
											<a href="javascript:void(0)" data-template="new" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Confirm Appointment</h4></div>
											<a href="javascript:void(0)" data-template="confirm" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-refresh fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by Customer</h4></div>
											<a href="javascript:void(0)" data-template="reschedulec" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Cancel by Customer</h4></div>
											<a href="javascript:void(0)" data-template="cancelc" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-repeat fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reschedule by You</h4></div>
											<a href="javascript:void(0)" data-template="reschedulea" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-ban fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Reject by You</h4></div>
											<a href="javascript:void(0)" data-template="rejecta" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-calendar-check-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Complete Appointment</h4></div>
											<a href="javascript:void(0)" data-template="complete" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="saasappoint-es-box-part text-center">
											<i class="fa fa-bell-o fa-3x" aria-hidden="true"></i>
											<div class="saasappoint-es-box-title"><h4>Appointment Reminder</h4></div>
											<a href="javascript:void(0)" data-template="reminder" data-template_for="admin" class="saasappoint-es-box-a saasappoint_sms_template_modal_btn">Customize Template</a>
										 </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	  </div>
	  <!-- emailtemplate Setting Form Modal-->
    <div class="modal fade" id="saasappoint-emailtemplate-setting-form-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-emailtemplate-setting-form-modal-label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-emailtemplate-setting-form-modal-label">Customize Email Template</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body saasappoint-emailtemplate-setting-form-modal-content">
		  </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a id="update_emailtemplate_settings_btn" class="btn btn-primary" href="javascript:void(0);">Save Template</a>
          </div>
        </div>
      </div>
    </div>
	  <!-- smstemplate Setting Form Modal-->
    <div class="modal fade" id="saasappoint-smstemplate-setting-form-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-smstemplate-setting-form-modal-label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saasappoint-smstemplate-setting-form-modal-label">Customize SMS Template</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body saasappoint-smstemplate-setting-form-modal-content">
		  </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a id="update_smstemplate_settings_btn" class="btn btn-primary" href="javascript:void(0);">Save Template</a>
          </div>
        </div>
      </div>
    </div>
<?php include 'footer.php'; ?>