<?php 
include 'c_header.php';
include(dirname(dirname(__FILE__))."/classes/class_customers.php");
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn; 
?>
<!-- Breadcrumbs-->
<ol class="breadcrumb">
	<li class="breadcrumb-item">
		<a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
	</li>
	<li class="breadcrumb-item active">Earned referral coupons</li>
</ol>
<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="saasappoint_customer_referrals_list_table" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>Referred Friend Name</th>
								<th>Coupon</th>
								<th>Discount</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$all_customer_referrals = $obj_customers->get_all_customer_referrals($_SESSION["customer_id"]);
						if(mysqli_num_rows($all_customer_referrals)>0){
							while($customer_referral = mysqli_fetch_array($all_customer_referrals)){ 
								$customer_name = $obj_customers->get_reff_customer_name($customer_referral["customer_id"]); 
								?>
								<tr>
								  <td><?php echo $customer_referral['id']; ?></td>
								  <td><?php echo $customer_name; ?></td>
								  <td><?php echo $customer_referral['coupon']; ?> </td>
								  <td><?php if($customer_referral["discount_type"] == "percentage"){ echo $customer_referral['discount']."%"; }else{ echo "FLAT".$customer_referral['discount']; } ?> </td>
								  <td>
									<?php if($customer_referral["used"] == "Y"){ echo "<span class='text-danger'>USED</span>"; }else{ echo "<span class='text-success'>AVAILABLE</span>"; } ?>
								  </td>
								</tr>
								<?php 
							} 
						} 
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>	 
<?php include 'c_footer.php'; ?>