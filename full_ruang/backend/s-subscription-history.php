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

$obj_subscriptions_history->business_id = $_GET['bid'];
$subscriptions_history = $obj_subscriptions_history->read_subscription_history_of_business();
$subscription_detail = $obj_subscriptions_history->read_current_subscription_detail_of_business();
$obj_subscription_plans->id = $subscription_detail['plan_id'];
$subscription_plan_name = $obj_subscription_plans->read_subscription_planname(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
		<li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php">Businesses</a>
        </li>
        <li class="breadcrumb-item active">Subscription History</li>
      </ol>
	  <div class="mb-3">
		<table class="saasappoint-subscription-detail-box">
			<tr class="saasappoint-bb">
				<th colspan="2"><center>Current Subscription Detail</center></th>
			</tr>
			<tr>
				<th>Member since</th>
				<td><b>:</b> <?php echo date($saasappoint_datetime_format, strtotime($subscription_detail['joined_on'])); ?></td>
			</tr>
			<tr>
				<th>Current Subscription Plan</th>
				<td><b>:</b> <?php echo ucwords($subscription_plan_name); ?></td>
			</tr>
			<tr>
				<th>Renewal Invoice Period</th>
				<td><b>:</b> <?php echo ucwords($subscription_detail['renewal']); ?></td>
			</tr>
			<tr>
				<th>Subscription Begins</th>
				<td><b>:</b> <?php echo date($saasappoint_date_format, strtotime($subscription_detail['subscribed_on'])); ?></td>
			</tr>
			<tr>
				<th>Subscription Ends</th>
				<td><b>:</b> <?php echo date($saasappoint_date_format, strtotime($subscription_detail['expired_on'])); ?></td>
			</tr>
		</table>
	  </div>
	  <br/>
	  <!-- DataTables Card-->
      <div class="card mb-3">
		  <div class="card-body">
			  <div class="table-responsive">
				<table id="saasappoint_subscription_history_table" width="100%" cellspacing="0">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Plan Name</th>
					  <th>Transaction ID</th>
					  <th>Subscribed On</th>
					  <th>Subscription Expires On</th>
					  <th>Renewal</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$i = 1;
					while($history = mysqli_fetch_array($subscriptions_history)){ 
						?>
						<tr>
						  <td><?php echo $i; ?></td>
						  <td><?php $obj_subscription_plans->id = $history['plan_id']; echo $obj_subscription_plans->read_subscription_planname(); ?></td>
						  <td><?php echo $history['transaction_id']; ?></td>
						  <td><?php echo date($saasappoint_datetime_format, strtotime($history['subscribed_on'])); ?></td>
						  <td><?php echo date($saasappoint_datetime_format, strtotime($history['expired_on'])); ?></td>
						  <td><?php echo strtoupper($history['renewal']); ?></td>
						</tr>
						<?php 
						$i++;
					} 
					?>
				  </tbody>
			   </table>
			  </div>
		  </div>
<?php include 's_footer.php'; ?>