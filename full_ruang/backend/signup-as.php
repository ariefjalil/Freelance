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

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_mail = new saasappoint_phpmailer();

$company_name = $obj_settings->get_superadmin_option("saasappoint_company_name"); 
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
		<!-- Custom styles for this template-->
		<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-register.css?<?php echo time(); ?>" rel="stylesheet">
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
		<div class="navbar-brand" style="width:100%">
				<a href="https://e-tempahanruangutm.aiwebdev.com/business-directory.php">
					<img src="http://www.utm.my/wp-content/uploads/2018/07/utmmyheader.png" alt="Official Web Portal of Universiti Teknologi Malaysia" data-height-percentage="83" data-actual-width="1069" data-actual-height="154">
				</a> 
				<!-- <a class="pull-left btn btn-link btn-sm" href="http://e-tempahanruangutm.aiwebdev.com/business-directory.php">UTM TTC</a> -->
			</div>
		<section class="saasappoint-register-main">
			<div class="col-md-12">

			</div>
			<div class="container saasappoint-register-container">
				<form id="saasappoint_admin_register_form" name="saasappoint_admin_register_form" method="post">
					<div class="row">
						<div class="col-md-6 saasappoint-register-right-block saasappoint-border-right text-center">
							<div class="row mt-5 mb-5">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div class="card saasappoint-bs-border-shadow">
										<<a href="<?php echo SITE_URL; ?>backend/"> <!-- backend/register.php"> -->
										  <i class="fa fa-user-o fa-5x pt-4 text-white" aria-hidden="true"></i>
										  <div class="card-body">
											<h2 class="text-white">Register as Administrator</h2>
										  </div>
										</a>
									</div>
								</div>
								<div class="col-md-2"></div>
							</div>
						</div>
						<div class="col-md-6 saasappoint-register-right-block text-center">
							<div class="row mt-5 mb-5">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div class="card saasappoint-bs-border-shadow">
									  <a href="<?php echo SITE_URL; ?>backend/c-register.php">
										  <i class="fa fa-user-plus fa-5x pt-4 text-white" aria-hidden="true"></i>
										  <div class="card-body">
											<h2 class="text-white">Register as Customer</h2>
										  </div>
									  </a>
									</div>
								</div>
								<div class="col-md-2"></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	</body>
</html>