<?php 
session_start();
/* Include class files */
include(dirname(__FILE__)."/constants.php");
include(dirname(__FILE__)."/classes/class_connection.php"); 
include(dirname(__FILE__)."/classes/class_settings.php");
include(dirname(__FILE__)."/classes/class_subscription_plans.php");
include(dirname(__FILE__)."/classes/class_business_type.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail_setup_page($conn);

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_business_type = new saasappoint_business_type();
$obj_business_type->conn = $conn;

$company_name = $obj_settings->get_superadmin_option("saasappoint_company_name");
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');
$subscription_plans = $obj_subscription_plans->readall_subscription_plans();
$business_types = $obj_business_type->readall_business_type();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="SaaSAppoint- Online Multi Business Appointment Scheduling & Reservation Booking Calendar --- SaaS Booking Software, Cleaner Booking, Multi Business Booking Software, Online Appointment Scheduling, Appointment Booking Calendar, Reservation System, Multi Business directory, Rendez-vous logiciel, Limpieza reserva, Saas appointment, Cleaning services business software, Scheduling SaaS, Booking Calendar, SAAS Appointment Calendar, Cleaning Appointment, Maid Booking Software">
		<meta name="author" content="SaasAppoint - Wpminds">
		
		<title>SaasAppoint Administrator Setup</title>
		<!-- Bootstrap core CSS-->
		<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-sadmin-setup.css?<?php echo time(); ?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	</head>
	<body class="saasappoint">
		<section class="saasappoint-sadminsetup-main">
			<div class="col-md-12">
				<div class="text-center">
					<h1 class="pb-3 saasappoint-sadminsetup-center-block-title"><img src="<?php echo SITE_URL; ?>includes/installation/image/logo.png" alt="SaasAppoint" /></h1>
				</div>
			</div>
			<div class="container saasappoint-sadminsetup-container">
				<form id="saasappoint_sadminsetup_form" name="saasappoint_sadminsetup_form" method="post">
					<div class="row">
						<div class="col-md-12 saasappoint-sadminsetup-right-block">
							<h2 class="text-center">Configure default settings</h2>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 saasappoint-sadminsetup-right-block saasappoint-border-right">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_firstname">First Name</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_firstname" name="saasappoint_sadminsetup_firstname" placeholder="Enter first name" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_lastname">Last Name</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_lastname" name="saasappoint_sadminsetup_lastname" placeholder="Enter last name" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_email">Email</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_email" name="saasappoint_sadminsetup_email" placeholder="Enter email" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_password">Password</label>
									<input type="password" class="form-control" id="saasappoint_sadminsetup_password" name="saasappoint_sadminsetup_password" placeholder="Enter password" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_phone">Phone</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_phone" name="saasappoint_sadminsetup_phone" placeholder="Enter phone" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_zip">Zip</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_zip" name="saasappoint_sadminsetup_zip" placeholder="Enter zip">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label for="saasappoint_sadminsetup_address">Address</label>
									<textarea class="form-control" id="saasappoint_sadminsetup_address" name="saasappoint_sadminsetup_address" placeholder="Enter address"></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-6 saasappoint-sadminsetup-right-block">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_city">City</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_city" name="saasappoint_sadminsetup_city" placeholder="Enter city">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_state">State</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_state" name="saasappoint_sadminsetup_state" placeholder="Enter state">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_country">Country</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_country" name="saasappoint_sadminsetup_country" placeholder="Enter country">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_companyname">Company Name</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_companyname" name="saasappoint_sadminsetup_companyname" placeholder="Enter company name">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_companyemail">Company Email</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_companyemail" name="saasappoint_sadminsetup_companyemail" placeholder="Enter company email">
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_sadminsetup_companyphone">Company Phone</label>
									<input type="text" class="form-control" id="saasappoint_sadminsetup_companyphone" name="saasappoint_sadminsetup_companyphone" placeholder="Enter phone">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<a class="btn saasappoint-submit-btn pull-right" id="saasappoint_sadminsetup_btn" href="javascript:void(0)">Configure settings</a>
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
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>' };
		</script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-sadmin-setup.js?<?php echo time(); ?>"></script>
	</body>
</html>