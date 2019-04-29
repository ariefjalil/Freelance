<?php 
session_start();
/* Include class files */
include(dirname(dirname(__FILE__))."/constants.php");

/* Redirect if user logged in */
if(isset($_SESSION['login_type'])) { 
	if($_SESSION['login_type'] == "superadmin") { 
		?>
		<script>
		window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
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
}

include(dirname(dirname(__FILE__))."/classes/class_connection.php"); 
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class.phpmailer.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);
$obj_database->saasappoint_version_update($conn);

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
		<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-login.css?<?php echo time(); ?>" rel="stylesheet">
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
		<section class="saasappoint-login-main">
			<div class="container saasappoint-login-container">
				<div class="row">
					<div class="col-md-8 saasappoint-login-left-block">
						<a href ="http://e-tempahanruangutm.aiwebdev.com/business-directory.php">
						<img class="d-block img-fluid saasappoint-bg-image-border" src="<?php echo SITE_URL; ?>includes/login-images/login-bg.jpg" alt="<?php echo $saasappoint_seo_meta_description; ?>">
						<!--<div class="saasappoint-banner-text-top-left">
							<h2><?php echo ucwords($company_name); ?></h2>
							<a href="<?php echo SITE_URL; ?>" class="saasappoint-continue-booking-link mt-3 text-white">Teruskan Tempahan >></a>
						</div> -->
					</a>
					</div>
					<div class="col-md-4 saasappoint-login-right-block">
						<h2 class="text-center">Login Now</h2>
						<form id="saasappoint_login_form" name="saasappoint_login_form">
							<div class="form-group">
								<label for="saasappoint_login_email">Email</label>
								<input type="text" class="form-control" id="saasappoint_login_email" name="saasappoint_login_email" placeholder="Masukkan email" value="<?php echo isset($_COOKIE['saasappoint_email'])?$_COOKIE['saasappoint_email'] : ""; ?>" />
							</div>
							<div class="form-group">
								<label for="saasappoint_login_password">Password</label>
								<input type="password" class="form-control" id="saasappoint_login_password" name="saasappoint_login_password" placeholder="Masukkan kata laluan" value="<?php echo isset($_COOKIE['saasappoint_password'])?$_COOKIE['saasappoint_password'] : ""; ?>" />
								<label id="saasappoint-login-error" class="error">Sorry! Wrong email address or password</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" id="saasappoint_login_remember_me" name="saasappoint_login_remember_me" <?php if(isset($_COOKIE['saasappoint_remember_me'])){ echo "checked"; } ?> />
									<small>Remember Me</small>
								</label>
								<button type="submit" id="saasappoint_login_btn" class="btn float-right">Login</button>
							</div>
						</form>

						<p class="text-left mt-4"><a href="<?php echo SITE_URL; ?>backend/forgot-password.php">Lupa kata laluan?</a></p>

						<a href="<?php echo SITE_URL; ?>" <p> <button  class="btn btn-secondary">Teruskan Tempahan </button></a>			
						<hr />
						<p class="text-center mt-4">Register akaun anda di <a href="<?php echo SITE_URL; ?>backend/signup-as.php">SINI</a></p>
						
					</div>
				</div>
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
		<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-login.js?<?php echo time(); ?>"></script>
	</body>
</html>