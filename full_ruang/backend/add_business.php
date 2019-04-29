<?php 
include 's_header.php'; 
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');
$subscription_plans = $obj_subscription_plans->readall_subscription_plans();
$business_types = $obj_business_type->readall_business_type(); 
?>
	 <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php">Businesses</a>
        </li>
        <li class="breadcrumb-item active">Add New Business</li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
		<div class="card-header"><i class="fa fa-briefcase"></i> Add New Business</div>
        <div class="card-body">
			<form id="saasappoint_add_new_business_form" name="saasappoint_add_new_business_form" method="post">
				<div class="row">
					<div class="col-md-12">
						<center><h4>Personal Information</h4></center><hr />
						<div class="row">
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_firstname">First Name</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_firstname" name="saasappoint_add_business_admin_firstname" placeholder="Enter first name" />
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_lastname">Last Name</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_lastname" name="saasappoint_add_business_admin_lastname" placeholder="Enter last name" />
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_email">Email</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_email" name="saasappoint_add_business_admin_email" placeholder="Enter email" />
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_password">Password</label>
								<input type="password" class="form-control" id="saasappoint_add_business_admin_password" name="saasappoint_add_business_admin_password" placeholder="Enter password" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_phone">Phone</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_phone" name="saasappoint_add_business_admin_phone" placeholder="Enter phone" />
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_businesstype">Business Type</label>
								<select class="form-control" name="saasappoint_add_business_admin_businesstype" id="saasappoint_add_business_admin_businesstype">
									<?php 
									$j=1;
									while($type = mysqli_fetch_assoc($business_types)){ 
										?>
										<option value="<?php echo $type['id']; ?>" <?php if($j==1){ echo "selected"; } ?>><?php echo ucwords($type['business_type']); ?></option>
										<?php 
										$j++;
									} 
									?>
								</select>
							</div>
							<div class="form-group col-md-5">
								<label for="saasappoint_add_business_admin_address">Address</label>
								<textarea class="form-control" id="saasappoint_add_business_admin_address" name="saasappoint_add_business_admin_address" placeholder="Enter address" rows="1"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_city">City</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_city" name="saasappoint_add_business_admin_city" placeholder="Enter city">
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_state">State</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_state" name="saasappoint_add_business_admin_state" placeholder="Enter state">
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_zip">Zip</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_zip" name="saasappoint_add_business_admin_zip" placeholder="Enter zip">
							</div>
							<div class="form-group col-md-3">
								<label for="saasappoint_add_business_admin_country">Country</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_country" name="saasappoint_add_business_admin_country" placeholder="Enter country">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<hr /><center><h4>Company Information</h4></center><hr />
						<div class="row">
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companyname">Company Name</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companyname" name="saasappoint_add_business_admin_companyname" placeholder="Enter company name" />
							</div>
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companyemail">Company Email</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companyemail" name="saasappoint_add_business_admin_companyemail" placeholder="Enter company email" />
							</div>
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companyphone">Company Phone</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companyphone" name="saasappoint_add_business_admin_companyphone" placeholder="Enter company phone" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="saasappoint_add_business_admin_companyaddress">Company Address</label>
								<textarea class="form-control" id="saasappoint_add_business_admin_companyaddress" name="saasappoint_add_business_admin_companyaddress" rows="1" placeholder="Enter company address"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label for="saasappoint_add_business_admin_companycity">Company City</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companycity" name="saasappoint_add_business_admin_companycity" placeholder="Enter company city">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companystate">Company State</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companystate" name="saasappoint_add_business_admin_companystate" placeholder="Enter company state">
							</div>
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companyzip">Company Zip</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companyzip" name="saasappoint_add_business_admin_companyzip" placeholder="Enter company zip">
							</div>
							<div class="form-group col-md-4">
								<label for="saasappoint_add_business_admin_companycountry">Company Country</label>
								<input type="text" class="form-control" id="saasappoint_add_business_admin_companycountry" name="saasappoint_add_business_admin_companycountry" placeholder="Enter company country">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<hr /><center><h4>Subscription & Payment Information</h4></center><hr />	
						<div class="row">
							<div class="form-group col-md-12">
								<label>Subscribe to:</label>
								<?php 
								$i=1;
								while($plan = mysqli_fetch_assoc($subscription_plans)){
									?>
									<div class="form-check">
										<label class="form-check-label">
											<input type="radio" class="form-check-input saasappoint_add_business_plans_radio" name="saasappoint_add_business_plans_radio" value="<?php echo $plan['id']; ?>" <?php if($i==1){ echo "checked"; } ?>><?php 
												echo ucwords($plan['plan_name'])." for"; 
												if($plan['plan_rate']>0){
													echo " ".$saasappoint_currency_symbol.$plan['plan_rate']." ";
												}else{
													echo " FREE ";
												}
												if($plan['renewal_type'] == "monthly"){
													$year_month = "Month";
												}else{
													$year_month = "Year";
												}
												if($plan['plan_period'] > 1){ 
													echo " - [".$plan['plan_period']." ".$year_month."s]"; 
												}else{ 
													echo " - [".$plan['plan_period']." ".$year_month."]"; 
												} 
											?>
										</label>
									</div>
									<?php 
									$i++;
								} 
								?>
							</div>
						</div>
						<div class="mt-3 mb-4">
							<label class="mb-3">Payment method: <b> Pay Manually</b></label>
						</div>
						<div class="col-md-12 form-check">
							<a id="saasappoint_add_new_business_btn" class="btn btn-success btn-block" href="javascript:void(0);">Add Business</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php include 's_footer.php'; ?>