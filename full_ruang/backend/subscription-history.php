<?php 
include 'header.php';
include(dirname(dirname(__FILE__))."/classes/class_subscriptions_history.php");

$obj_subscriptions_history = new saasappoint_subscriptions_history();
$obj_subscriptions_history->conn = $conn;

$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');

$obj_subscriptions_history->business_id = $_SESSION['business_id'];
$subscriptions_history = $obj_subscriptions_history->read_subscription_history_of_business();
$subscription_detail = $obj_subscriptions_history->read_current_subscription_detail_of_business();
$obj_subscription_plans->id = $subscription_detail['plan_id'];
$subscription_plan_name = $obj_subscription_plans->read_subscription_planname(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
		<li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/subscription.php">Subscription</a>
        </li>
        <li class="breadcrumb-item active">Subscription History</li>
      </ol>
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
<?php include 'footer.php'; ?>