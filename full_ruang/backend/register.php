<?php 
session_start();
/* Include class files */
include(dirname(dirname(__FILE__))."/constants.php");

/* Redirect if user logged in */
if(isset($_SESSION['login_type'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php 
	exit;
}

include(dirname(dirname(__FILE__))."/classes/class_connection.php"); 
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class.phpmailer.php");
include(dirname(dirname(__FILE__))."/classes/class_subscription_plans.php");
include(dirname(dirname(__FILE__))."/classes/class_business_type.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);
$obj_database->saasappoint_version_update($conn);

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_mail = new saasappoint_phpmailer();

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_business_type = new saasappoint_business_type();
$obj_business_type->conn = $conn;

$company_name = $obj_settings->get_superadmin_option("saasappoint_company_name");
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');
$subscription_plans = $obj_subscription_plans->readall_subscription_plans();
$business_types = $obj_business_type->readall_business_type();

$saasappoint_pay_at_venue_status = $obj_settings->get_superadmin_option("saasappoint_pay_at_venue_status");
$saasappoint_paypal_payment_status = $obj_settings->get_superadmin_option("saasappoint_paypal_payment_status");
$saasappoint_stripe_payment_status = $obj_settings->get_superadmin_option("saasappoint_stripe_payment_status"); 
$saasappoint_authorizenet_payment_status = $obj_settings->get_superadmin_option("saasappoint_authorizenet_payment_status"); 
$saasappoint_twocheckout_payment_status = $obj_settings->get_superadmin_option("saasappoint_twocheckout_payment_status"); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php 
		$saasappoint_seo_ga_code = $obj_settings->get_superadmin_option('saasappoint_seo_ga_code');
		$saasappoint_seo_meta_tag = $obj_settings->get_superadmin_option('saasappoint_seo_meta_tag');
		$saasappoint_seo_meta_description = $obj_settings->get_superadmin_option('saasappoint_seo_meta_description');
		$saasappoint_seo_og_meta_tag = $obj_settings->get_superadmin_option('saasappoint_seo_og_meta_tag');
		$saasappoint_seo_og_tag_type = $obj_settings->get_superadmin_option('saasappoint_seo_og_tag_type');
		$saasappoint_seo_og_tag_url = $obj_settings->get_superadmin_option('saasappoint_seo_og_tag_url');
		$saasappoint_seo_og_tag_image = $obj_settings->get_superadmin_option('saasappoint_seo_og_tag_image'); 
		?>
		
		<title><?php if($saasappoint_seo_meta_tag != ""){ echo $saasappoint_seo_meta_tag; }else{ echo $obj_settings->get_superadmin_option("saasappoint_company_name"); } ?></title>
		
		<?php 
		if($saasappoint_seo_meta_description != ''){ 
			?>
			<meta name="description" content="<?php echo $saasappoint_seo_meta_description; ?>">
			<?php 
		} 
		if($saasappoint_seo_og_meta_tag != ''){ 
			?>
			<meta property="og:title" content="<?php  echo $saasappoint_seo_og_meta_tag; ?>" />
			<?php 
		} 
		if($saasappoint_seo_og_tag_type != ''){ 
			?>
			<meta property="og:type" content="<?php echo $saasappoint_seo_og_tag_type; ?>" />
			<?php 
		} 
		if($saasappoint_seo_og_tag_url != ''){ 
			?>
			<meta property="og:url" content="<?php echo $saasappoint_seo_og_tag_url; ?>" />
			<?php 
		} 
		if($saasappoint_seo_og_tag_image != '' && file_exists("../includes/images/".$saasappoint_seo_og_tag_image)){ 
			?>
			<meta property="og:image" content="<?php  echo SITE_URL; ?>includes/images/<?php echo $saasappoint_seo_og_tag_image; ?>" />
			<?php 
		} 
		if($saasappoint_seo_ga_code != ''){ 
			?>
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $saasappoint_seo_ga_code; ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', '<?php echo $saasappoint_seo_ga_code; ?>');
			</script>
			<?php  
		} 
		?>
		<!-- Bootstrap core CSS-->
		<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-register.css?<?php echo time(); ?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	</head>
	<body class="saasappoint">
		<div id="saasappoint-loader-overlay" class="saasappoint_main_loader saasappoint_hide_loader">
			<div id="saasappoint-loader" class="saasappoint_main_loader saasappoint_hide_loader">
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-dot"></div>
				<div class="saasappoint-loader-loading"></div>
			</div>
		</div>
		<section class="saasappoint-register-main">
			<div class="col-md-12">
				<div class="text-center">
					<h1 class="pb-3 saasappoint-register-center-block-title"><?php echo ucwords($company_name); ?></h1>
				</div>
			</div>
			<div class="container saasappoint-register-container">
				<form id="saasappoint_admin_register_form" name="saasappoint_admin_register_form" method="post">
					<div class="row">
						<div class="col-md-6 saasappoint-register-right-block saasappoint-border-right">
							<h2 class="text-center">Register as administrator</h2>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_firstname">First Name</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_firstname" name="saasappoint_register_admin_firstname" placeholder="Enter first name" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_lastname">Last Name</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_lastname" name="saasappoint_register_admin_lastname" placeholder="Enter last name" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_email">Email</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_email" name="saasappoint_register_admin_email" placeholder="Enter email" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_password">Password</label>
									<input type="password" class="form-control" id="saasappoint_register_admin_password" name="saasappoint_register_admin_password" placeholder="Enter password" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_phone">Phone</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_phone" name="saasappoint_register_admin_phone" placeholder="Enter phone" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_businesstype">Business Type</label>
									<select class="form-control" name="saasappoint_register_admin_businesstype" id="saasappoint_register_admin_businesstype">
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
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label for="saasappoint_register_admin_address">Address</label>
									<textarea class="form-control" id="saasappoint_register_admin_address" name="saasappoint_register_admin_address" placeholder="Enter address"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_city">City</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_city" name="saasappoint_register_admin_city" placeholder="Enter city">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_state">State</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_state" name="saasappoint_register_admin_state" placeholder="Enter state">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_zip">Zip</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_zip" name="saasappoint_register_admin_zip" placeholder="Enter zip">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_country">Country</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_country" name="saasappoint_register_admin_country" placeholder="Enter country">
								</div>
							</div>
						</div>
						<div class="col-md-6 saasappoint-register-right-block">
							<div class="row">
								<div class="form-group col-md-12">
									<label for="saasappoint_register_admin_companyname">Company Name</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companyname" name="saasappoint_register_admin_companyname" placeholder="Enter company name" />
								</div>
							</div>							
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companyemail">Company Email</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companyemail" name="saasappoint_register_admin_companyemail" placeholder="Enter company email" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companyphone">Company Phone</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companyphone" name="saasappoint_register_admin_companyphone" placeholder="Enter phone" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label for="saasappoint_register_admin_companyaddress">Company Address</label>
									<textarea class="form-control" id="saasappoint_register_admin_companyaddress" name="saasappoint_register_admin_companyaddress" placeholder="Enter company address"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companycity">Company City</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companycity" name="saasappoint_register_admin_companycity" placeholder="Enter company city">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companystate">Company State</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companystate" name="saasappoint_register_admin_companystate" placeholder="Enter company state">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companyzip">Company Zip</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companyzip" name="saasappoint_register_admin_companyzip" placeholder="Enter company zip">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_admin_companycountry">Company Country</label>
									<input type="text" class="form-control" id="saasappoint_register_admin_companycountry" name="saasappoint_register_admin_companycountry" placeholder="Enter company country">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label>Subscribe to:</label>
									<?php 
									$i=1;
									while($plan = mysqli_fetch_assoc($subscription_plans)){
										?>
										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" class="form-check-input saasappoint_register_plans_radio" name="saasappoint_register_plans_radio" value="<?php echo $plan['id']; ?>" <?php if($i==1){ echo "checked"; } ?>><?php 
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
									<label class="error" id="saasappoint_register_plans_radio_error">Please select subscription plan</label>
								</div>
							</div>					
							
							<!--Payment Tab Start-->
							<?php if($saasappoint_pay_at_venue_status == "Y" || $saasappoint_paypal_payment_status == "Y" || $saasappoint_stripe_payment_status == "Y" || $saasappoint_authorizenet_payment_status == "Y" || $saasappoint_twocheckout_payment_status == "Y"){ ?>
								<div class="mt-3 mb-3">
									<label class="mb-3 row">Payment method:</label>
									<?php 
									if($saasappoint_pay_at_venue_status == "Y"){ 
										?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="pay manually" checked />Pay Manually
											</label>
										</div>
										<?php 
									}
									if($saasappoint_paypal_payment_status == "Y"){ 
										?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="paypal" />PayPal
											</label>
										</div>
										<?php 
									} 
									
									if($saasappoint_stripe_payment_status == "Y" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "N"){ 
										$payment_method = "stripe";
									} else if($saasappoint_stripe_payment_status == "N" && $saasappoint_authorizenet_payment_status == "Y" && $saasappoint_twocheckout_payment_status == "N"){ 
										$payment_method = "authorize.net";
									}  else if($saasappoint_stripe_payment_status == "N" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "Y"){ 
										$payment_method = "2checkout";
									} else{
										$payment_method = "N";
									}
									if($payment_method != "N"){ 
										?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="<?php echo $payment_method; ?>" />Card Payment
											</label>
										</div>
										<?php 
									} 
									?>
								</div>
							<?php 
							}
							if($saasappoint_stripe_payment_status == "Y" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "N"){ 
								?>
								<div class="mb-4 saasappoint-card-payment-div p-3">
									<div id="saasappoint_stripe_plan_card_element">
										<!-- A Stripe Element will be inserted here. -->
									</div>
									<!-- Used to display form errors. -->
									<div id="saasappoint_stripe_plan_card_errors" role="alert"></div>
								</div>
								<?php 
							}else{ 
								?>
								<div class="mb-2 saasappoint-card-payment-div p-3" <?php if($saasappoint_paypal_payment_status == "N" && ($saasappoint_stripe_payment_status == "Y" || $saasappoint_authorizenet_payment_status == "Y" || $saasappoint_twocheckout_payment_status == "Y")){ echo "style='display:block'"; } ?>>
									<input type="hidden" id="saasappoint-payment-method-id" />
									<div class="row">
										<div class="form-group col-md-9">
											<input maxlength="20" size="20" type="tel" placeholder="Card number" class="form-control" name="saasappoint-cardnumber" id="saasappoint-cardnumber" value="" />
										</div>
										<div class="form-group col-md-3">
											<input type="password" maxlength="4" size="4" placeholder="CVV" class="form-control"  name="saasappoint-cardcvv" id="saasappoint-cardcvv" value="" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<input maxlength="2" type="tel" placeholder="MM" class="form-control" name="saasappoint-cardexmonth" id="saasappoint-cardexmonth" value="" />
										</div>
										<div class="col-md-3">
											<input maxlength="4" type="tel" placeholder="YYYY" class="form-control" name="saasappoint-cardexyear" id="saasappoint-cardexyear" value="" />
										</div>
										<div class="col-md-6">
											<input type="text" placeholder="Name as on Card" class="form-control" name="saasappoint-cardholdername" id="saasappoint-cardholdername" value="" />
										</div>
									</div>
								</div>
								<?php 
							} 
							?>
							<!--Payment Tab Ends-->
							
							
							<div class="col-md-12 form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" id="saasappoint_accept_admin_tandc" name="saasappoint_accept_admin_tandc" />
									<small>I read and agree to the <a href="javascript:void(0)">terms & conditions</a></small>
								</label>
								<button type="submit" id="saasappoint_admin_register_btn" class="btn float-right saasappoint_register_btn">Register Now</button>
								<label class="error" id="saasappoint_accept_admin_tandc_error">Please accept our terms and conditions</label>
							</div>
							<div class="col-md-12 mt-5">
								<div class="text-center">
									<p class="pb-3">Already have an account? <a href="<?php echo SITE_URL; ?>backend/">Login</a></p>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
		<!-- Bootstrap core JavaScript-->
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.validate.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
		<!-- Custom scripts for all pages-->
		<script>
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'twocheckout_status' : '<?php echo $saasappoint_twocheckout_payment_status; ?>', 'twocheckout_sid' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_twocheckout_seller_id'); ?>', 'twocheckout_pkey' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_twocheckout_publishable_key'); ?>', 'stripe_status' : '<?php echo $saasappoint_stripe_payment_status; ?>', 'stripe_pkey' : '<?php echo $obj_settings->get_superadmin_option('saasappoint_stripe_publickey'); ?>' };
		</script>
		
		<?php if($saasappoint_authorizenet_payment_status == "Y" || $saasappoint_twocheckout_payment_status == "Y"){ ?>
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.payment.min.js?<?php echo time(); ?>" type="text/javascript"></script>
		<script>
		$(document).ready(function(){
			/** card payment validation **/
			$('#saasappoint-cardnumber').payment('formatCardNumber');
			$('#saasappoint-cardcvv').payment('formatCardCVC');
			$('#saasappoint-cardexmonth').payment('restrictNumeric');
			$('#saasappoint-cardexyear').payment('restrictNumeric');
		});
		</script>
		<?php } ?>
		
		<?php if($saasappoint_stripe_payment_status == 'Y'){ ?>
		<script src="https://js.stripe.com/v3/"></script>
		<?php } ?>
		
		<?php if($saasappoint_twocheckout_payment_status == 'Y'){ ?>
		<script src="https://www.2checkout.com/checkout/api/2co.min.js" type="text/javascript"></script>	
		<?php } ?>
		<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-register.js?<?php echo time(); ?>"></script>
	</body>
</html>