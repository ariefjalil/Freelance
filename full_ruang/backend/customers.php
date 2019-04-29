<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Customers</li>
      </ol>
      <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_registered_customers"><i class="fa fa-user-plus"></i> Registered Customers</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#saasappoint_guest_customers"><i class="fa fa-user"></i> Guest Customers</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_registered_customers">
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table cellspacing="0" id="saasappoint_registered_customers_detail">
							  <thead>
								<tr>
								  <th>Customer Name</th>
								  <th>Email</th>
								  <th>Phone</th>
								  <th>Address</th>
								  <th>Referral Code</th>
								  <th>Booked Appointments</th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_guest_customers">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table cellspacing="0" id="saasappoint_guest_customers_detail">
							  <thead>
								<tr>
								  <th>Customer Name</th>
								  <th>Email</th>
								  <th>Phone</th>
								  <th>Address</th>
								  <th>Booked Appointments</th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
			  </div>
			</div>
		</div>
	 </div>
	 <!-- Appointments Modal-->
	<div class="modal fade" id="saasappoint_customer_appointment_modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint_customer_appointment_modal_label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint_customer_appointment_modal_label">Booked Appointments</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint_customer_appointment_modal_body">
			<div class="table-responsive">
				<table cellspacing="0" id="saasappoint_customer_appointments_listing">
				  <thead>
					<tr>
					  <th>Order ID</th>
					  <th>Category</th>
					  <th>Service</th>
					  <th>Addons</th>
					  <th>Booking DateTime</th>
					  <th>Booking Status</th>
					  <th>Payment Method</th>
					</tr>
				  </thead>
				  <tbody>
				  </tbody>
				</table>
			  </div>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>