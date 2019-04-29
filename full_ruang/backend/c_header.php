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
}else if($_SESSION['login_type'] == "admin") {
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/appointments.php";
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
if(!isset($_SESSION['customer_id'])) {
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
include(dirname(dirname(__FILE__))."/classes/class_refund_request.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);
$obj_database->saasappoint_version_update($conn);

$obj_mail = new saasappoint_phpmailer();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$obj_refund_request = new saasappoint_refund_request();
$obj_refund_request->conn = $conn;

$dash_title = "Dashboard Pelanggan"; 
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
	<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-customer.css?<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
</head>

<body class="saasappoint fixed-nav sticky-footer bg-dark" id="saasappoint-page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="saasappoint-mainnav">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>backend/my-appointments.php"><?php echo $dash_title; ?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#saasappoint-navbarresponsive" aria-controls="saasappoint-navbarresponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
	<div class="collapse navbar-collapse" id="saasappoint-navbarresponsive">
	  <ul class="navbar-nav navbar-sidenav" id="saasappoint-menu-accordion">
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'my-appointments.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/my-appointments.php">
			<i class="fa fa-fw fa-calendar-check-o"></i>
			<span class="nav-link-text">Tempahan Saya</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_refund.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/c_refund.php">
			<i class="fa fa-fw fa-exchange"></i>
			<span class="nav-link-text">Pengembalian Wang</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_profile.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/c_profile.php">
				<i class="fa fa-fw fa-user-o" aria-hidden="true"></i>
				<span class="nav-link-text">Profil</span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'tentang_kami.php') != false) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/tentang_kami.php">
				<i class="fa fa-fw fa-ticket" aria-hidden="true"></i>
				<span class="nav-link-text">Carta Organisasi</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="modal" data-target="#saasappoint-change-password-modal">
				<i class="fa fa-fw fa-key" aria-hidden="true"></i>
				<span class="nav-link-text">Kemaskini Kata Laluan</span>
			</a>
		</li>
		<!--<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c-support-tickets.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'c-ticket-discussion.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/c-support-tickets.php">
			<i class="fa fa-fw fa-comments-o"></i>
			<span class="nav-link-text">Tiket Pertolongan</span>
		  </a>-->
		</li>
		<!--<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'referral-coupons.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/referral-coupons.php">
			<i class="fa fa-fw fa-ticket"></i>
			<span class="nav-link-text">Referral Coupons</span>
		  </a>-->
		</li>
		<!--<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'refer.php') != false) { echo 'active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/refer.php">
			<i class="fa fa-fw fa-gift"></i>
			<span class="nav-link-text">Refer a Friend</span>
		  </a>-->
		</li>
		<li class="nav-item">
		  <a class="nav-link" data-toggle="modal" data-target="#saasappoint-logout-modal">
			<i class="fa fa-fw fa-sign-out"></i>
			<span class="nav-link-text">Log Keluar</span>
		  </a>
		</li>
	  </ul>
	  <ul class="navbar-nav ml-auto">
		<li class="nav-item">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>">
			<i class="fa fa-fw fa-backward"></i>
			<span class="nav-link-text">Meneruskan Tempahan</span>
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