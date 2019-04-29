<?php 
include 'header.php';
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Payments</li>
      </ol>
      <div class="mb-3">
		<div class="saasappoint-tabbable-panel">
			<div class="saasappoint-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#saasappoint_registered_customers_payment"><i class="fa fa-credit-card"></i> Registered Customers Payment</a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#saasappoint_guest_customers_payment"><i class="fa fa-money"></i> Guest Customers Payment</a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="saasappoint_registered_customers_payment">
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table cellspacing="0" id="saasappoint_rc_payment_table">
							  <thead>
								<tr>
								  <th>Order ID</th>
								  <th>Customer Name</th>
								  <th>Payment Method</th>
								  <th>Payment Date</th>
								  <th>Transaction ID</th>
								  <th>Sub Total</th>
								  <th>Discount</th>
								  <th>Referral Discount</th>
								  <th>Tax</th>
								  <th>Net Total</th>
								  <th>Frequently Discount</th>
								  <th>Frequently Discount Amount</th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="saasappoint_guest_customers_payment">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table cellspacing="0" id="saasappoint_gc_payment_table">
							  <thead>
								<tr>
								  <th>Order ID</th>
								  <th>Customer Name</th>
								  <th>Payment Method</th>
								  <th>Payment Date</th>
								  <th>Transaction ID</th>
								  <th>Sub Total</th>
								  <th>Discount</th>
								  <th>Tax</th>
								  <th>Net Total</th>
								  <th>Frequently Discount</th>
								  <th>Frequently Discount Amount</th>
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
<?php include 'footer.php'; ?>