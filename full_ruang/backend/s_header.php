<?php 
session_start();

/* Include class files */
include(dirname(dirname(__FILE__))."/constants.php");

/* Redirect if user logged in */
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
} else if($_SESSION['login_type'] == "admin") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/appointments.php";
	</script>
	<?php 
	exit; 
}else{}
if(!isset($_SESSION['superadmin_id'])) { 
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
$obj_database->saasappoint_version_update($conn);

include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class.phpmailer.php");
include(dirname(dirname(__FILE__))."/classes/class_businesses.php");
include(dirname(dirname(__FILE__))."/classes/class_business_type.php");
include(dirname(dirname(__FILE__))."/classes/class_sms_subscriptions_history.php");
include(dirname(dirname(__FILE__))."/classes/class_subscriptions_history.php");
include(dirname(dirname(__FILE__))."/classes/class_subscription_plans.php");
include(dirname(dirname(__FILE__))."/classes/class_sms_plans.php");
include(dirname(dirname(__FILE__))."/classes/class_superadmins.php");

/* Create object of classes */

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_mail = new saasappoint_phpmailer();

$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;

$obj_business_type = new saasappoint_business_type();
$obj_business_type->conn = $conn;

$obj_sms_subscription_history = new saasappoint_sms_subscriptions_history();
$obj_sms_subscription_history->conn = $conn;

$obj_subscriptions_history = new saasappoint_subscriptions_history();
$obj_subscriptions_history->conn = $conn;

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_sms_plans = new saasappoint_sms_plans();
$obj_sms_plans->conn = $conn;

$obj_superadmins = new saasappoint_superadmins();
$obj_superadmins->conn = $conn;

$dash_title = "Super Admin Dashboard"; 
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
	<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-superadmin.css?<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
</head>

<body class="saasappoint fixed-nav sticky-footer bg-dark" id="saasappoint-page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="saasappoint-mainnav">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>backend/businesses.php"><?php echo $dash_title; ?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#saasappoint-navbarresponsive" aria-controls="saasappoint-navbarresponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
	<div class="collapse navbar-collapse" id="saasappoint-navbarresponsive">
	  <ul class="navbar-nav navbar-sidenav" id="saasappoint-menu-accordion">
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'businesses.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'add_business.php') != false || strpos($_SERVER['SCRIPT_NAME'], 's-subscription-history.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'subscription-detail.php') != false || strpos($_SERVER['SCRIPT_NAME'], 's-sms-credit-history.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/businesses.php">
				<i class="fa fa-fw fa-th-list"></i>
				<span class="nav-link-text">Business List</span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'business-types.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/business-types.php">
				<i class="fa fa-fw fa-briefcase"></i>
				<span class="nav-link-text">Business Types</span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'subscription-plan.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/subscription-plan.php">
				<i class="fa fa-fw fa-rss"></i>
				<span class="nav-link-text">Subscription Plans</span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'sms-plan.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/sms-plan.php">
				<i class="fa fa-fw fa-comment-o"></i>
				<span class="nav-link-text">SMS Plans</span>
			</a>
		</li>
		<li class="nav-item <?php if(strpos($_SERVER['SCRIPT_NAME'], 'reminder.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/reminder.php">
				<i class="fa fa-fw fa-bell-o"></i>
				<span class="nav-link-text">Appointment Reminder</span>
			</a>
		</li>
		<li class="nav-item <?php if(strpos($_SERVER['SCRIPT_NAME'], 's-support-tickets.php') != false || strpos($_SERVER['SCRIPT_NAME'], 's-ticket-discussion.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/s-support-tickets.php">
				<i class="fa fa-fw fa-ticket"></i>
				<span class="nav-link-text">Tickets</span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'business-settings.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/business-settings.php">
				<i class="fa fa-fw fa-cog"></i>
				<span class="nav-link-text">Settings</span>
			</a>
		</li>
	  </ul>
	  <ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link <?php if (strpos($_SERVER['SCRIPT_NAME'], 's_profile.php') != false) { echo 'active'; } ?>" href="<?php echo SITE_URL; ?>backend/s_profile.php">
				<i class="fa fa-fw fa-user-o" aria-hidden="true"></i>
				<span class="nav-link-text">Profile</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="modal" data-target="#saasappoint-change-password-modal">
				<i class="fa fa-fw fa-key" aria-hidden="true"></i>
				<span class="nav-link-text">Change Password</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" target="_blank" href="https://codecanyon.net/checkout/from_item/22901414?support=renew_6month"><i class="fa fa-fw fa-handshake-o" aria-hidden="true"></i> Extend Support </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo SITE_URL; ?>documentation"><i class="fa fa-fw fa-file" aria-hidden="true"></i> Documentation </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="modal" data-target="#saasappoint-logout-modal">
				<i class="fa fa-fw fa-sign-out"></i>
				<span class="nav-link-text">Logout</span>
			</a>
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