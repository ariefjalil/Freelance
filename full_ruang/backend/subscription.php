<?php 
include 'header.php';
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');
$subscription_detail = $obj_subscriptions->readone_subscription();
$obj_subscription_plans->id = $subscription_detail['plan_id'];
$subscription_plan_name = $obj_subscription_plans->read_subscription_planname();
$subscription_plans = $obj_subscription_plans->readall_subscription_plans();
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Subscription</li>
      </ol>
	  <div class="mb-3">
		<table class="saasappoint-subscription-detail-box">
			<tr class="saasappoint-bb">
				<th colspan="2"><center>Your Current Subscription Detail</center></th>
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
			<tr>
				<td colspan="2"><a href="<?php echo SITE_URL; ?>backend/subscription-history.php" class="pull-right mt-3">Check Subscription history</a></td>
			</tr>
		</table>
	  </div>
	  <br/>
	  <center><h3><b>Choose a Subscription Package</b></h3></center>
      <div class="saasappoint-subscription-pricing">
		<?php 
		$i=0;
		$j=0;
		while($plan = mysqli_fetch_assoc($subscription_plans)){
			$i++;
			$j++;
			if($i=="1"){ 
				?>
				<div class="row <?php if($j!="1"){ echo "mt-4"; } ?>">
				<?php 
			}
			if($i=="2"){
				$colour_class = "green";
			} else if($i=="3"){
				$colour_class = "orange";
			} else if($i=="4"){
				$colour_class = "purple";
			} else {
				$colour_class = "";
			}
			?>
			<div class="col-md-3 col-sm-6">
				<div class="saasappoint-subscription-pricing-table <?php echo $colour_class; ?>">
					<center>
						<div class="saasappoint-subscription-pricing-table-plan-name"><?php echo $plan['plan_name']; ?></div>
						<h2 class="saasappoint-subscription-pricing-table-title"><?php echo $plan['plan_rate']; ?><span class="saasappoint-subscription-table-currency"><?php echo $saasappoint_currency_symbol; ?></span></h2>
						<h4 class="saasappoint-white">
							[ <?php 
							if($plan['renewal_type'] == "monthly"){
								$year_month = "Month";
							}else{
								$year_month = "Year";
							}
							if($plan['plan_period'] > 1){ 
								echo $plan['plan_period']." ".$year_month."s"; 
							}else{ 
								echo $plan['plan_period']." ".$year_month; 
							} ?> ]
						</h4>
					</center>
					<br />
					<a href="javascript:void(0)" data-id="<?php echo $plan['id']; ?>" class="saasappoint-subscription-pricing-table-button">Upgrade</a>
				</div>
			</div>
			<?php 
			if($i=="4"){
				$i=0; 
				?>
				</div>
				<?php 
			}
		} 
		?>
	</div>
	<!-- Upgrade Plan Modal -->
	<div class="modal fade" id="saasappoint-upgrade-plan-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-upgrade-plan-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-upgrade-plan-modal-label">Upgrade Subscription Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<?php 
			$saasappoint_pay_at_venue_status  = $obj_settings->get_superadmin_option("saasappoint_pay_at_venue_status");
			$saasappoint_paypal_payment_status = $obj_settings->get_superadmin_option("saasappoint_paypal_payment_status");
			$saasappoint_stripe_payment_status = $obj_settings->get_superadmin_option("saasappoint_stripe_payment_status"); 
			$saasappoint_authorizenet_payment_status = $obj_settings->get_superadmin_option("saasappoint_authorizenet_payment_status"); 
			$saasappoint_twocheckout_payment_status = $obj_settings->get_superadmin_option("saasappoint_twocheckout_payment_status"); 
			?>
			<form name="saasappoint_upgrade_plan_form" id="saasappoint_upgrade_plan_form" method="post">
				<div class="m-3">
					<label class="mb-3 row">Payment method:</label>
					<?php 
					if($saasappoint_pay_at_venue_status == "Y"){ 
						?>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="pay manually" checked />Pay Manually
							</label>
						</div>
						<?php 
					}
					if($saasappoint_paypal_payment_status == "Y"){ 
						?>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="paypal" />PayPal
							</label>
						</div>
						<?php 
					} 
					
					if($saasappoint_stripe_payment_status == "Y" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "N"){ 
						$payment_method = "stripe";
					} else if($saasappoint_stripe_payment_status == "N" && $saasappoint_authorizenet_payment_status == "Y" && $saasappoint_twocheckout_payment_status == "N"){ 
						$payment_method = "authorize.net";
					}  else if($saasappoint_stripe_payment_status == "N" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "Y"){ 
						$payment_method = "2checkout";
					} else{
						$payment_method = "N";
					}
					if($payment_method != "N"){ 
						?>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input saasappoint_payment_method_radio" name="saasappoint_payment_method_radio" value="<?php echo $payment_method; ?>" />Card Payment
							</label>
						</div>
						<?php 
					} 
					?>
				</div>
				<?php 
				if($saasappoint_stripe_payment_status == "Y" && $saasappoint_authorizenet_payment_status == "N" && $saasappoint_twocheckout_payment_status == "N"){ 
					?>
					<div class="mb-4 saasappoint-card-payment-div p-3">
						<div id="saasappoint_stripe_plan_card_element">
							<!-- A Stripe Element will be inserted here. -->
						</div>
						<!-- Used to display form errors. -->
						<div id="saasappoint_stripe_plan_card_errors" role="alert"></div>
					</div>
					<?php 
				}else{ 
					?>
					<div class="mb-2 saasappoint-card-payment-div p-3" <?php if($saasappoint_paypal_payment_status == "N" && ($saasappoint_stripe_payment_status == "Y" || $saasappoint_authorizenet_payment_status == "Y" || $saasappoint_twocheckout_payment_status == "Y")){ echo "style='display:block'"; } ?>>
						<input type="hidden" id="saasappoint-payment-method-id" />
						<div class="row">
							<div class="form-group col-md-9">
								<input maxlength="20" size="20" type="tel" placeholder="Card number" class="form-control" name="saasappoint-cardnumber" id="saasappoint-cardnumber" value="" />
							</div>
							<div class="form-group col-md-3">
								<input type="password" maxlength="4" size="4" placeholder="CVV" class="form-control"  name="saasappoint-cardcvv" id="saasappoint-cardcvv" value="" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<input maxlength="2" type="tel" placeholder="MM" class="form-control" name="saasappoint-cardexmonth" id="saasappoint-cardexmonth" value="" />
							</div>
							<div class="col-md-3">
								<input maxlength="4" type="tel" placeholder="YYYY" class="form-control" name="saasappoint-cardexyear" id="saasappoint-cardexyear" value="" />
							</div>
							<div class="col-md-6">
								<input type="text" placeholder="Name as on Card" class="form-control" name="saasappoint-cardholdername" id="saasappoint-cardholdername" value="" />
							</div>
						</div>
					</div>
					<?php 
				} 
				?>
				<!--Payment Tab Ends-->
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_upgrade_plan_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Upgrade</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>