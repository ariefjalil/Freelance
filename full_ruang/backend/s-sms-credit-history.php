<?php 
include 's_header.php';
if(!isset($_GET['bid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
	</script>
	<?php 
	exit;
} else if(!is_numeric($_GET['bid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
	</script>
	<?php 
	exit;
}
$saasappoint_date_format = $obj_settings->get_superadmin_option('saasappoint_date_format');
$time_format = $obj_settings->get_superadmin_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');

$obj_settings->business_id = $_GET['bid']; 
$obj_sms_subscription_history->business_id = $_GET['bid']; 
$readall_sms_subscription_history_of_admin = $obj_sms_subscription_history->readall_sms_subscription_history_of_admin();

?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
		<li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php">Businesses</a>
        </li>
        <li class="breadcrumb-item active">SMS Credit Purchase History</li>
      </ol>
	  <div class="m-5">
		<h5>Total SMS credit left: <?php echo $obj_settings->get_option("saasappoint_sms_credit"); ?></h5>
	  </div>
	  <br/>
	  <!-- DataTables Card-->
      <div class="card mb-3">
		  <div class="card-body">
			  <div class="table-responsive">
				<table id="saasappoint_sms_subscription_history_table" width="100%" cellspacing="0">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Plan Name</th>
					  <th>Amount</th>
					  <th>Credit</th>
					  <th>Transaction ID</th>
					  <th>Payment Method</th>
					  <th>Credit Extended On</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$i = 1;
					if(mysqli_num_rows($readall_sms_subscription_history_of_admin)>0){ 
						while($history = mysqli_fetch_array($readall_sms_subscription_history_of_admin)){ 
							?>
							<tr>
							  <td><?php echo $i; ?></td>
							  <td><?php $obj_sms_plans->id = $history['plan_id']; echo $obj_sms_plans->readone_sms_plan_name(); ?></td>
							  <td><?php echo $saasappoint_currency_symbol.$history['amount']; ?></td>
							  <td><?php echo $history['credit']; ?></td>
							  <td><?php echo $history['transaction_id']; ?></td>
							  <td><?php echo ucwords($history['payment_method']); ?></td>
							  <td><?php echo date($saasappoint_datetime_format, strtotime($history['extended_on'])); ?></td>
							</tr>
							<?php 
							$i++;
						} 
					} 
					?>
				  </tbody>
			   </table>
			  </div>
		  </div>
<?php include 's_footer.php'; ?>