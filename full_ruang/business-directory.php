<?php 
session_start();
include("constants.php"); 
/** Include class files and create their objects to call functions **/
include(dirname(__FILE__)."/classes/class_connection.php");
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);   
$obj_database->saasappoint_version_update($conn);

include(dirname(__FILE__)."/classes/class.phpmailer.php");
include(dirname(__FILE__)."/classes/class_businesses.php");
$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;
$obj_mail = new saasappoint_phpmailer();

include(dirname(__FILE__)."/classes/class_settings.php");
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

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

$companyname = $obj_settings->get_superadmin_option("saasappoint_company_name"); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
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

		<!-- Bootstrap core CSS -->
		<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
		
		<!-- Custom fonts for this template -->
		<link href='https://fonts.googleapis.com/css?family=Varela' rel='stylesheet'>
		
		<!-- Custom styles for this template -->
		<link href="<?php echo SITE_URL; ?>includes/css/saasappoint-business-directory.css?<?php echo time(); ?>" rel="stylesheet">
	</head>
	<body>
		<!-- Brand and toggle get grouped for better mobile display -->
		<div id="saasappoint-main-menu-collapse" class="navbar navbar-expand navbar-light bg-light saasappoint_header_bg_clr">
			<div class="navbar-brand" style="width:100%">
				<a href="https://www.utm.my/">
					<img src = "http://www.utm.my/wp-content/uploads/2018/07/utmmyheader.png" alt = "Official Web Portal of Universiti Teknologi Malaysia" data-height-percentage="83" data-actual-width="1069" data-actual-height="154">
				</a> 
				<!-- <a class="pull-left btn btn-link btn-sm" href="<?php echo SITE_URL; ?>business-directory.php"><?php echo ucwords($companyname); ?></a> -->
				<div class="pull-right">
					<a class="btn btn-link btn-sm saasappoint-bd-link-font" href="<?php echo SITE_URL; ?>backend"><i class="fa fa-sign-in" aria-hidden="true"></i> Log Masuk</a>
					<a class="btn btn-link btn-sm saasappoint-bd-link-font" href="<?php echo SITE_URL; ?>backend/signup-as.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Log Keluar</a>
				</div>
			</div>	
		</div>
		<div class= "saasappoint-banner-overlay ">
			<div class="container text-center">
				<div class="col-md-12">
					<div class="saasappoint-banner-heading ">
						<div class="row justify-content-center">
							<div class="col-12 col-md-10 col-lg-8">
								<div class="saasappoint-bsearch-result-heading ">
									<h3>Book Package Kami Dengan Satu Klik!</h3>
									<p>Cara Mudah Untuk Tempah Dengan Kami.</p>
									<div class="saasappoint-heading-border text-center"></div>
									<p>
										<!-- Search form -->
										<div class="card card-sm">
											<div class="card-body row no-gutters align-items-center">
												<!--end of col-->
												<div class="col">
													<input id="saasappoint_pagination_search_keyword" class="form-control form-control-lg saasappoint-form-control-borderless" type="search" placeholder="Cari Kami Melalui Kata Kunci" autocomplete="off" />
												</div>
												<!--end of col-->
												<div class="col-auto">
													<a id="saasappoint_search_business_btn" class="btn btn-lg btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></a>
												</div>
												<!--end of col-->
											</div>
										</div>
									</p>
									<p>
										<!-- Reset Search -->
										<a id="saasappoint_reset_business_btn" class="btn btn-sm btn-default" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset Carian</a>
									</p>
								</div>
							</div>
							<!--end of col-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="saasappoint-paddingtb60">
			<div class="container">
				<div id="saasappoint_get_all_business_list">
					<!-- BUSINESS LISTING CONTENT HERE -->
					<input type="hidden" name="saasappoint_pagination_rowcount" id="saasappoint_pagination_rowcount" />
					<div class="saasappoint-bsearch-result-heading "> <h3>Tunggu kami sedang memproses carian anda...</h3> </div>
				</div>
			</div>
		</div>
		<!-- Footer section START -->
		<footer class="py-5 saasappoint-footer-bg">
			<div class="container">
				<p class="m-0 text-center text-white">Hak Cipta Milik &copy; <?php echo ucwords($companyname); ?> <?php echo date("Y"); ?></p>
			</div>
		<!-- /.container -->
		</footer>
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.min.js?<?php echo time(); ?>"></script>
		<script>
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>' };
		</script>
		<script src="<?php echo SITE_URL; ?>includes/js/saasappoint-business-directory.js?<?php echo time(); ?>"></script>
	</body>
</html>