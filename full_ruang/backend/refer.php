<?php 
include 'c_header.php';
include(dirname(dirname(__FILE__))."/classes/class_customers.php");
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->id = $_SESSION['customer_id'];
$profile_data = $obj_customers->readone_customer();
?>
<!-- Breadcrumbs-->
<ol class="breadcrumb">
	<li class="breadcrumb-item">
		<a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
	</li>
	<li class="breadcrumb-item active">Refer a Friend</li>
</ol>
<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<?php 
				if($profile_data["refferral_code"] != ""){ 
					?>
					<div class="p-3">
						<div class="mb-3 p-3 border_double saasappoint_refer_box">
							<div class="text-center pb-3"><i class="fa fa-gift fa-5x text-danger"></i></div>
							<h3 class="text-center text-dark">Refer to your friends & get exciting coupon</h3>
							<p class="text-center text-muted">Ask your friends to register & book an appointment from the referral code you share and you can get exciting coupon</p>
							<div class="saasappoint_refer_input">
								<input class="text-secondary" type="text" readonly="readonly" value="<?php echo $profile_data["refferral_code"]; ?>"/>
							</div>
							<div class="p-3 text-muted">
								<h3>Step to Refer: </h3>
								<p>1. Copy & share your referral code with your friends.</p>
								<p>2. Ask your friend to book an appointment from given referral code.</p>
								<p>3. You will get a coupon after one of your friends has successfully completed appointment.</p>
							</div>
						</div>
					</div>
					<?php 
				}else{ 
					?>
					<div class="p-3">
						<div class="mb-3 p-3 border_double saasappoint_refer_box">
							<div class="text-center pb-3"><i class="fa fa-gift fa-5x text-danger"></i></div>
							<h3 class="text-center text-dark pb-3">Opps! You are not eligible to get referral code</h3>
						</div>
					</div>
					<?php 
				} 
				?>
			</div>
		</div>
	</div>
</div>	 
<?php include 'c_footer.php'; ?>