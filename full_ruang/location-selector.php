<?php 
session_start();
include(dirname(__FILE__)."/constants.php"); 
include(dirname(__FILE__)."/classes/class_connection.php");

$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);
$obj_database->saasappoint_version_update($conn);

/** Check business id is set or not **/
if(isset($_GET['bid'])){
	$bid = base64_decode($_GET['bid']);
	if(is_numeric($bid)){
		$_SESSION['business_id'] = $bid;
		header("location:".SITE_URL."location-selector.php"); 
		exit;
	}
}
if(!isset($_SESSION['business_id'])){
	header("location:".SITE_URL."business-directory.php");exit;
}else if(!is_numeric($_SESSION["business_id"])){
	header("location:".SITE_URL."business-directory.php");exit;
}

$obj_database->check_business_status($_SESSION['business_id'], $conn);

$_SESSION['saasappoint_customer_detail'] = array();
$_SESSION['saasappoint_cart_items'] = array();
$_SESSION['saasappoint_cart_category_id'] = "";
$_SESSION['saasappoint_cart_service_id'] = "";
$_SESSION['saasappoint_cart_datetime'] = "";
$_SESSION['saasappoint_cart_end_datetime'] = "";
$_SESSION['saasappoint_cart_freqdiscount_label'] = "";
$_SESSION['saasappoint_cart_freqdiscount_key'] = "";
$_SESSION['saasappoint_cart_freqdiscount_id'] = "";
$_SESSION['saasappoint_cart_subtotal'] = 0;
$_SESSION['saasappoint_cart_freqdiscount'] = 0;
$_SESSION['saasappoint_cart_coupondiscount'] = 0;
$_SESSION['saasappoint_cart_couponid'] = "";
$_SESSION['saasappoint_cart_tax'] = 0;
$_SESSION['saasappoint_cart_nettotal'] = 0;
$_SESSION['saasappoint_location_selector_zipcode'] = "";

/* Include class files */
include(dirname(__FILE__)."/classes/class_frontend.php");
include(dirname(__FILE__)."/classes/class_settings.php");

/* Create object of classes */

$obj_frontend = new saasappoint_frontend();
$obj_frontend->conn = $conn; 
$obj_frontend->business_id = $_SESSION['business_id']; 

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];
$check_expiry = $obj_settings->check_subscription_expiry();
if($check_expiry == "business_not_exist"){
	echo "<b>Check <a href='".SITE_URL."business-directory.php'>Business Directory</a> to book an appointment with active businesses.</b>";exit;
}else{
	$expiry_date = strtotime($check_expiry);
	$current_date = strtotime(date("Y-m-d H:i:s"));
	if($current_date>$expiry_date){
		echo "<b>Subscription of this business is expired. Please upgrade your subscription to use our services. <a href='".SITE_URL."backend'>Login Now</a></b>";exit;
	}
}

/* check location selector status */
$saasappoint_location_selector_status = $obj_settings->get_option("saasappoint_location_selector_status"); 
if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
	header("location:".SITE_URL);
	$_SESSION['saasappoint_location_selector_zipcode'] = "N/A"; 
	exit;
} 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="-1" />
		<?php 
		$saasappoint_seo_ga_code = $obj_settings->get_option('saasappoint_seo_ga_code');
		$saasappoint_seo_meta_tag = $obj_settings->get_option('saasappoint_seo_meta_tag');
		$saasappoint_seo_meta_description = $obj_settings->get_option('saasappoint_seo_meta_description');
		$saasappoint_seo_og_meta_tag = $obj_settings->get_option('saasappoint_seo_og_meta_tag');
		$saasappoint_seo_og_tag_type = $obj_settings->get_option('saasappoint_seo_og_tag_type');
		$saasappoint_seo_og_tag_url = $obj_settings->get_option('saasappoint_seo_og_tag_url');
		$saasappoint_seo_og_tag_image = $obj_settings->get_option('saasappoint_seo_og_tag_image'); 
		?>
		
		<title><?php if($saasappoint_seo_meta_tag != ""){ echo $saasappoint_seo_meta_tag; }else{ echo $obj_settings->get_option("saasappoint_company_name"); } ?></title>
		
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
		if($saasappoint_seo_og_tag_image != '' && file_exists("includes/images/".$saasappoint_seo_og_tag_image)){ 
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
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/bootstrap.min.css?<?php echo time(); ?>" />
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/font-awesome.min.css?<?php echo time(); ?>" />
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/saasappoint-location-selector-style.css?<?php echo time(); ?>">
		<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		
		<!-- Bootstrap core JavaScript and Page level plugin JavaScript-->
		<script src="<?php echo SITE_URL; ?>includes/front/js/jquery-3.2.1.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/popper.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/bootstrap.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
		
		<!-- Custom scripts -->
		<script>
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>' };
		</script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/saasappoint-location-selector-jquery.js?<?php echo time(); ?>"></script>
	</head>
	<body class="saasappoint">
		<section class="saasappoint-booking-detail-block saasappoint-center-block saasappoint-main-block-before">
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
			<div class="container">
				<div class="row">
					<div class="col-md-12 saasappoint-set-sm-fit mb-4">
						<div class="text-center saasappoint-location-selector-bg">
							<div class="row">
								<div class="col-md-12"><a href="<?php echo SITE_URL; ?>business-directory.php" class="btn btn-link pull-right text-white">Book with another Business &raquo;</a></div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="border-0 saasappoint-location-selector-content-box">
										<div class="col-md-12">
											<div class="border-0 pb-5 pt-5">
												<h3 class="text-white">Check for services available at your location</h3>
											</div>
										</div>
										<div id="saasappoint_location_selector_form">
											<div class="pb-5">
												<div class="row">
													<div class="col-md-12">
														<center>
															<!-- Search form -->
															<div class="card card-sm">
																<div class="card-body row no-gutters align-items-center">
																	<!--end of col-->
																	<div class="col">
																		<input id="saasappoint_ls_input_keyword" class="form-control form-control-lg saasappoint-form-control-borderless" type="text" placeholder="Enter zip" autocomplete="off" />
																	</div>
																	<!--end of col-->
																	<div class="col-auto">
																		<button id="saasappoint_location_check_btn" class="btn saasappoint-block-btn pl-3 pr-3" type="submit"><i class="fa fa-map-marker"></i></button>
																	</div>
																	<!--end of col-->
																</div>
															</div>
														</center>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="saasappoint-booking-detail-main text-center">
							<?php 
							$saasappoint_company_logo = $obj_settings->get_option("saasappoint_company_logo"); 
							$saasappoint_company_name = $obj_settings->get_option("saasappoint_company_name"); 
							$saasappoint_company_email = $obj_settings->get_option("saasappoint_company_email"); 
							$saasappoint_company_phone = $obj_settings->get_option("saasappoint_company_phone"); 
							$saasappoint_company_address = $obj_settings->get_option("saasappoint_company_address"); 
							$saasappoint_company_city = $obj_settings->get_option("saasappoint_company_city"); 
							$saasappoint_company_state = $obj_settings->get_option("saasappoint_company_state"); 
							$saasappoint_company_zip = $obj_settings->get_option("saasappoint_company_zip"); 
							$saasappoint_company_country = $obj_settings->get_option("saasappoint_company_country"); 
							?>
							<div class="row">
								<div class="col-md-12"><?php if($saasappoint_company_logo != "" && file_exists("includes/images/".$saasappoint_company_logo)){ ?><img class="saasappoint-companylogo" src="<?php echo SITE_URL; ?>includes/images/<?php echo $saasappoint_company_logo; ?>" /> <?php } ?></div>
							</div>
							<div class="row">
								<div class="col-md-12"><b class="saasappoint-companytitle"><?php echo $saasappoint_company_name; ?></b></div>
							</div>
							<div class="row">
								<div class="col-md-12"><span class="text-muted"><?php echo $saasappoint_company_address.", ".$saasappoint_company_city.", ".$saasappoint_company_state.", ".$saasappoint_company_country."-".$saasappoint_company_zip; ?></span></div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<span class="col-md-6 text-dark font-weight-bold"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $saasappoint_company_email; ?></span>
									<span class="col-md-6 text-dark font-weight-bold"><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $saasappoint_company_phone; ?></span></div>
							</div>
						</div>
						<div class="saasappoint_location_selector_container_bg pt-4">
							<div class="col-md-12">
								<?php echo base64_decode($obj_settings->get_option("saasappoint_location_selector_container")); ?>
							</div>
						</div>
						
						<!-- Footer section START -->
						<footer class="py-5 saasappoint-footer-bg">
							<div class="container">
								<p class="m-0 text-center text-white">Copyright &copy; <?php echo ucwords($saasappoint_company_name); ?> <?php echo date("Y"); ?></p>
							</div>
						<!-- /.container -->
						</footer>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>