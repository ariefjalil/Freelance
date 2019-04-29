<?php 
session_start();
include(dirname(__FILE__)."/constants.php"); 
/** Check business id is set or not **/
if(!isset($_SESSION['business_id'])){
	header("location:".SITE_URL."business-directory.php");exit;
}else if(!is_numeric($_SESSION["business_id"])){
	header("location:".SITE_URL."business-directory.php");exit;
}

include(dirname(__FILE__)."/classes/class_connection.php");

$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_database->check_superadmin_setup_detail($conn);
$obj_database->saasappoint_version_update($conn);

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
		
		<style>
			.saasappoint .jumbotron{
				background-color: #ffffff;
			}
		</style>
		<!-- Custom scripts -->
		<script type="text/javascript">
			var timer = 3; /* seconds */
			frontpage = '<?php echo SITE_URL; ?>';
			function delayer() {
				window.location = frontpage;
			}
			setTimeout('delayer()', 1000 * timer);
		</script>
	</head>
	<body class="saasappoint">
		<center class="pt-5">
			<!-- Thank you page content start -->
			<div class="jumbotron text-xs-center">
			  <i class="fa fa-calendar-check-o fa-5x text-success" aria-hidden="true"></i>
			  <br />
			  <h1 class="display-3">Terima Kasih!</h1>
			  <br />
			  <p class="lead"><strong>Terima Kasih Kerana Menempah Servis Dari Kami. Sila Rujuk Di Halaman 'My Appointment' untuk keterangan lanjut.</strong></p>
			</div>
			<!-- Thank you page content end -->
		</center>
	</body>
</html>