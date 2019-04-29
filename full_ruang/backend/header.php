<?php 
session_start();

/* Include class files */
include(dirname(dirname(__FILE__))."/constants.php");

if(!isset($_SESSION['login_type'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}else if($_SESSION['login_type'] == "customer") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/my-appointments.php";
	</script>
	<?php  
	exit;
} else if($_SESSION['login_type'] == "superadmin") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
	</script>
	<?php  
	exit;
}else{}
if(!isset($_SESSION['business_id'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}
if(!isset($_SESSION['admin_id'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}

include(dirname(dirname(__FILE__))."/classes/class_connection.php");
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);

include(dirname(dirname(__FILE__))."/classes/class.phpmailer.php");
include(dirname(dirname(__FILE__))."/classes/class_coupons.php");
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class_frequently_discount.php");
include(dirname(dirname(__FILE__))."/classes/class_schedule.php");
include(dirname(dirname(__FILE__))."/classes/class_bookings.php");
include(dirname(dirname(__FILE__))."/classes/class_categories.php");
include(dirname(dirname(__FILE__))."/classes/class_services.php");
include(dirname(dirname(__FILE__))."/classes/class_addons.php");
include(dirname(dirname(__FILE__))."/classes/class_admins.php");
include(dirname(dirname(__FILE__))."/classes/class_subscriptions.php");
include(dirname(dirname(__FILE__))."/classes/class_subscription_plans.php");
include(dirname(dirname(__FILE__))."/classes/class_block_off.php");
include(dirname(dirname(__FILE__))."/classes/class_refund_request.php");

/* Create object of classes */
$obj_coupons = new saasappoint_coupons();
$obj_coupons->conn = $conn;
$obj_coupons->business_id = $_SESSION['business_id'];
$obj_mail = new saasappoint_phpmailer();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_refund_request = new saasappoint_refund_request();
$obj_refund_request->conn = $conn;
$obj_refund_request->business_id = $_SESSION['business_id'];

$obj_frequently_discount = new saasappoint_frequently_discount();
$obj_frequently_discount->conn = $conn;
$obj_frequently_discount->business_id = $_SESSION['business_id'];

$obj_schedule = new saasappoint_schedule();
$obj_schedule->conn = $conn;
$obj_schedule->business_id = $_SESSION['business_id'];

$obj_bookings = new saasappoint_bookings();
$obj_bookings->conn = $conn;
$obj_bookings->business_id = $_SESSION['business_id'];

$obj_categories = new saasappoint_categories();
$obj_categories->conn = $conn;
$obj_categories->business_id = $_SESSION['business_id'];

$obj_services = new saasappoint_services();
$obj_services->conn = $conn;
$obj_services->business_id = $_SESSION['business_id'];

$obj_addons = new saasappoint_addons();
$obj_addons->conn = $conn;
$obj_addons->business_id = $_SESSION['business_id'];

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;
$obj_admins->business_id = $_SESSION['business_id'];

$obj_block_off = new saasappoint_block_off();
$obj_block_off->conn = $conn;
$obj_block_off->business_id = $_SESSION['business_id'];

$obj_subscriptions = new saasappoint_subscriptions();
$obj_subscriptions->conn = $conn;
$obj_subscriptions->business_id = $_SESSION['business_id'];
$obj_subscriptions->admin_id = $_SESSION['admin_id'];

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
$saasappoint_server_timezone = date_default_timezone_get();
$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone);

$check_expiry = $obj_settings->check_subscription_expiry();
if($check_expiry == "business_not_exist"){}else{
	$expiry_date = strtotime($check_expiry);
	$current_date = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	if($current_date>$expiry_date && strpos($_SERVER['SCRIPT_NAME'], 'subscription.php') == false){ 
		?>
		<script>
		window.location.href = "<?php echo SITE_URL; ?>backend/subscription.php";
		</script>
		<?php  
		exit;
	}
}

$dash_title = "Admin Dashboard";  
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
	<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-select.min.css?<?php echo time(); ?>" rel="stylesheet">
	<!-- Custom fonts for this template-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<!-- Page level plugin CSS-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/datatables.min.css?<?php echo time(); ?>" rel="stylesheet">
	<!-- Include all css file for calendar -->
	<link href='<?php echo SITE_URL; ?>includes/vendor/calendar/fullcalendar.min.css?<?php echo time(); ?>' rel='stylesheet' />
	<!-- Custom styles for this template-->
	<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-admin.css?<?php echo time(); ?>" rel="stylesheet">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false) { ?>
	<!-- Custom frontend CSS -->
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/pe-icon-7-stroke.css?<?php echo time(); ?>" />
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/datepicker.min.css?<?php echo time(); ?>" />
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/saasappoint-mb-style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/saasappoint-mb-calendar-style.css?<?php echo time(); ?>">
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-tagsinput.css?<?php echo time(); ?>" />
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { ?>
		<!-- include text editor -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/text-editor/text-editor.css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
</head>

<body class="saasappoint fixed-nav sticky-footer bg-dark" id="saasappoint-page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="saasappoint-mainnav">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>backend/appointments.php"><?php echo $dash_title; ?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#saasappoint-navbarresponsive" aria-controls="saasappoint-navbarresponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
	<div class="collapse navbar-collapse" id="saasappoint-navbarresponsive">
	  <ul class="navbar-nav navbar-sidenav" id="saasappoint-menu-accordion">
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/appointments.php">
			<i class="fa fa-fw fa-calendar-check-o"></i>
			<span class="nav-link-text">Appointments</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'category.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'addons.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/category.php">
			<i class="fa fa-fw fa-th-list"></i>
			<span class="nav-link-text">Services</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/customers.php">
			<i class="fa fa-fw fa-users"></i>
			<span class="nav-link-text">Customers</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'payments.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/payments.php">
			<i class="fa fa-fw fa-money"></i>
			<span class="nav-link-text">Payments</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'schedule.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'manage-blockoff.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/schedule.php">
			<i class="fa fa-fw fa-calendar"></i>
			<span class="nav-link-text">Schedule</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/location-selector.php">
			<i class="fa fa-fw fa-map-marker"></i>
			<span class="nav-link-text">Location Selector</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/refund.php">
			<i class="fa fa-fw fa-exchange"></i>
			<span class="nav-link-text">Refund Request</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'coupons.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/coupons.php">
			<i class="fa fa-fw fa-ticket"></i>
			<span class="nav-link-text">Coupons</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'referral-setting.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/referral-setting.php">
			<i class="fa fa-fw fa-gift"></i>
			<span class="nav-link-text">Referral Settings</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'frequently-discount.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/frequently-discount.php">
			<i class="fa fa-fw fa-percent"></i>
			<span class="nav-link-text">Frequently Discount</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/email-sms-templates.php">
			<i class="fa fa-fw fa-columns"></i>
			<span class="nav-link-text">Email & SMS Templates</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/export.php">
			<i class="fa fa-fw fa-cloud-upload"></i>
			<span class="nav-link-text">Export</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'subscription.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'subscription-history.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/subscription.php">
			<i class="fa fa-fw fa-lock"></i>
			<span class="nav-link-text">Subscription</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'feedback.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/feedback.php">
			<i class="fa fa-fw fa-comments"></i>
			<span class="nav-link-text">Feedback</span>
		  </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo SITE_URL; ?>documentation"><i class="fa fa-fw fa-file" aria-hidden="true"></i> <span class="nav-link-text">Documentation</span></a>
		</li>
	  </ul>
	  <ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown saasappoint-notification-dd">
		  <a class="nav-link dropdown-toggle mr-lg-2 saasappoint-notification-dropdown-link" id="saasappoint-notification-dropdown" href="javascript:void(0)">
			<i class="fa fa-fw fa-bell"></i>
			<span class="indicator text-warning d-lg-block"><?php echo $obj_bookings->get_count_of_latest_unread_appointments(); ?></span>
		  </a>
		  <div class="dropdown-menu new-appointments-dropdown-menu" aria-labelledby="saasappoint-notification-dropdown" id="saasappoint-notification-dropdown-content">
			
		  </div>
		</li>
		
		<li class="nav-item dropdown saasappoint-refundrequest-dd">
		  <a class="nav-link dropdown-toggle mr-lg-2 saasappoint-refund-dropdown-link" id="saasappoint-refund-dropdown" href="javascript:void(0)">
			<i class="fa fa-fw fa-exchange"></i>
			<span class="indicator text-warning d-lg-block"><?php echo $obj_refund_request->get_count_of_latest_unread_refund_requests(); ?></span>
		  </a>
		  <div class="dropdown-menu new-refund-request-dropdown-menu" aria-labelledby="saasappoint-refund-dropdown" id="saasappoint-refund-dropdown-content">
			
		  </div>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'support-tickets.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'ticket-discussion.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/support-tickets.php">
			<i class="fa fa-fw fa-comments-o"></i>
			<span class="nav-link-text">Support Tickets</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'languages.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'languages.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/languages.php">
			<i class="fa fa-fw fa-language"></i>
			<span class="nav-link-text">Languages</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'embed.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/embed.php">
			<i class="fa fa-fw fa-code"></i>
			<span class="nav-link-text">Embed Frontend</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/settings.php">
			<i class="fa fa-fw fa-cog"></i>
			<span class="nav-link-text">Settings</span>
		  </a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php if (strpos($_SERVER['SCRIPT_NAME'], 'profile.php') != false) { echo 'active'; } ?>" href="<?php echo SITE_URL; ?>backend/profile.php"><i class="fa fa-fw fa-user-o" aria-hidden="true"></i> Profile </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="modal" data-target="#saasappoint-change-password-modal"><i class="fa fa-fw fa-key" aria-hidden="true"></i> Change Password </a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" data-toggle="modal" data-target="#saasappoint-logout-modal">
			<i class="fa fa-fw fa-sign-out"></i>Logout</a>
		</li>
	  </ul>
	</div>
  </nav>
  <div class="saasappoint-content-wrapper">
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
    <div class="container-fluid">