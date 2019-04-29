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
				<form id="saasappoint_customer_register_form" name="saasappoint_customer_register_form" method="post">
					<div class="row">
						<div class="col-md-12">
							<span class="saasappoint-text-float-right pb-0 pt-3">Already have an account? <a href="<?php echo SITE_URL; ?>backend/">Login</a></span>
						</div>
						<div class="col-md-12 pt-3 saasappoint-register-right-block">
							<h2 class="text-center">Register as customer</h2>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="saasappoint_register_customer_firstname">First Name</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_firstname" name="saasappoint_register_customer_firstname" placeholder="Enter first name" />
								</div>
								<div class="form-group col-md-6">
									<label for="saasappoint_register_customer_lastname">Last Name</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_lastname" name="saasappoint_register_customer_lastname" placeholder="Enter last name" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-5">
									<label for="saasappoint_register_customer_email">Email</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_email" name="saasappoint_register_customer_email" placeholder="Enter email" />
								</div>
								<div class="form-group col-md-4">
									<label for="saasappoint_register_customer_password">Password</label>
									<input type="password" class="form-control" id="saasappoint_register_customer_password" name="saasappoint_register_customer_password" placeholder="Enter password" />
								</div>
								<div class="form-group col-md-3">
									<label for="saasappoint_register_customer_phone">Phone</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_phone" name="saasappoint_register_customer_phone" placeholder="Enter phone" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label for="saasappoint_register_customer_address">Address</label>
									<textarea class="form-control" id="saasappoint_register_customer_address" name="saasappoint_register_customer_address" placeholder="Enter address" rows="1"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-3">
									<label for="saasappoint_register_customer_city">City</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_city" name="saasappoint_register_customer_city" placeholder="Enter city">
								</div>
								<div class="form-group col-md-3">
									<label for="saasappoint_register_customer_state">State</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_state" name="saasappoint_register_customer_state" placeholder="Enter state">
								</div>
								<div class="form-group col-md-3">
									<label for="saasappoint_register_customer_zip">Zip</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_zip" name="saasappoint_register_customer_zip" placeholder="Enter zip">
								</div>
								<div class="form-group col-md-3">
									<label for="saasappoint_register_customer_country">Country</label>
									<input type="text" class="form-control" id="saasappoint_register_customer_country" name="saasappoint_register_customer_country" placeholder="Enter country">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-12">
									<button type="submit" id="saasappoint_customer_register_btn" class="saasappoint_register_btn btn p-2 w-100">Register Now</button>
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
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'twocheckout_status' : 'N', 'twocheckout_sid' : '', 'twocheckout_pkey' : '', 'stripe_status' : 'N', 'stripe_pkey' : '' };
		</script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-register.js?<?php echo time(); ?>"></script>
	</body>
</html>