<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_sms_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_sms_subscriptions_history.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_sms_plans = new saasappoint_sms_plans();
$obj_sms_plans->conn = $conn;

$obj_sms_subscriptions_history = new saasappoint_sms_subscriptions_history();
$obj_sms_subscriptions_history->conn = $conn;
$obj_sms_subscriptions_history->business_id = $_SESSION['business_id'];

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;
$obj_admins->business_id = $_SESSION['business_id'];
$obj_admins->id = $_SESSION['admin_id'];

$saasappoint_currency_symbol = $obj_settings->get_superadmin_option('saasappoint_currency_symbol');

/** Get SMS plan modal detail Ajax **/
if(isset($_POST['get_sms_plan_modal_detail'])){ 
	$sms_plans = $obj_sms_plans->readall_sms_plans(); 
	if(mysqli_num_rows($sms_plans)>0){
		$saasappoint_pay_at_venue_status = $obj_settings->get_superadmin_option("saasappoint_pay_at_venue_status");
		$saasappoint_paypal_payment_status = $obj_settings->get_superadmin_option("saasappoint_paypal_payment_status");
		$saasappoint_stripe_payment_status = $obj_settings->get_superadmin_option("saasappoint_stripe_payment_status"); 
		$saasappoint_authorizenet_payment_status = $obj_settings->get_superadmin_option("saasappoint_authorizenet_payment_status"); 
		$saasappoint_twocheckout_payment_status = $obj_settings->get_superadmin_option("saasappoint_twocheckout_payment_status"); 
		?>
		<form name="saasappoint_upgrade_plan_form" id="saasappoint_upgrade_plan_form" method="post">
			<h5>Choose your SMS plan:</h5>
			<?php 
			$flag=true;
			while($sms_plan = mysqli_fetch_assoc($sms_plans)){ 
				?>
				<div class="form-check ml-4 mb-2">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="saasappoint_sms_plans_group" value="<?php echo $sms_plan['id']; ?>" <?php if($flag){ echo "checked"; } ?> /> <?php echo ucwords($sms_plan['plan_name'])." for <b>".$sms_plan['credit']." SMS</b> of <b>".$saasappoint_currency_symbol.$sms_plan['plan_rate']."</b>"; ?>
					</label>
				</div>
				<?php 
				$flag=false;
			} 				
			?>
			<br />
			
			<!--Payment Tab Start-->
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
		<?php 
	} else{
		echo "Please contact super admin to set SMS plans.";
	}
}

/* Upgrade SMS Plan ajax */
else if(isset($_POST['upgrade_sms_plan_stripe'])){
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/stripe/init.php");
	$obj_sms_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan();
	
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$skey = $obj_settings->get_superadmin_option('saasappoint_stripe_secretkey');
	$currency = $obj_settings->get_superadmin_option('saasappoint_currency');
	$token = $_POST['token'];
	
	$updated_sms_credit = $sms_credit+$plan_detail['credit'];
		
	$description = ucwords($saasappoint_company_name)." (".$saasappoint_company_phone.") subscribed for SMS plan: ".ucwords($plan_detail['plan_name'])." [Credit: ".$plan_detail['credit']."]";
	$admin_email = $saasappoint_company_email;
	
	try {
		\Stripe\Stripe::setApiKey($skey);
		$charge = \Stripe\Charge::create(array(
			'amount' => round($plan_detail["plan_rate"]*100),
			'currency' => $currency,
			'description' => $description,
			'source' => $token,
			'receipt_email' => $admin_email
		));
		
		$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
		$saasappoint_server_timezone = date_default_timezone_get();
		$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
		$extended_on = date("Y-m-d H:i:s", $currDateTime_withTZ);
		
		$obj_sms_subscriptions_history->plan_id = $_POST["plan_id"];
		$obj_sms_subscriptions_history->amount = $plan_detail['plan_rate'];
		$obj_sms_subscriptions_history->credit = $plan_detail['credit'];
		$obj_sms_subscriptions_history->transaction_id = $charge->id;
		$obj_sms_subscriptions_history->payment_method = "stripe";
		$obj_sms_subscriptions_history->extended_on = $extended_on;
		$obj_sms_subscriptions_history->add_sms_subscription_history();
		$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
		echo "upgraded";
	}
	catch (Exception $e) {
		$error = $e->getMessage();
		echo $error;die;
	}
}

/* Upgrade SMS Plan ajax */
else if(isset($_POST['upgrade_sms_plan_twocheckout'])){
	$obj_sms_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan();
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$currency = $obj_settings->get_superadmin_option('saasappoint_currency');
	$token = $_POST['token'];
	
	$updated_sms_credit = $sms_credit+$plan_detail['credit'];
			
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/twocheckout/Twocheckout.php");
	$saasappoint_twocheckout_private_key = $obj_settings->get_superadmin_option("saasappoint_twocheckout_private_key");
	$saasappoint_twocheckout_seller_id = $obj_settings->get_superadmin_option("saasappoint_twocheckout_seller_id");
	$twocc_sandbox_mode = "N";
	if($twocc_sandbox_mode == 'Y'){
		$twocc_sandbox = true;
	}else{
		$twocc_sandbox = false;
	}
	Twocheckout::privateKey($saasappoint_twocheckout_private_key); 
	Twocheckout::sellerId($saasappoint_twocheckout_seller_id); 
	Twocheckout::sandbox($twocc_sandbox);
	/* Twocheckout::verifySSL(false); */

	$net_total = $plan_detail['plan_rate'];
	$order_id = mt_rand();
	$saasappoint_currency = $obj_settings->get_superadmin_option('saasappoint_currency');
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
	$saasappoint_company_address = $obj_settings->get_option('saasappoint_company_address');
	$saasappoint_company_city = $obj_settings->get_option('saasappoint_company_city');
	$saasappoint_company_state = $obj_settings->get_option('saasappoint_company_state');
	$saasappoint_company_zip = $obj_settings->get_option('saasappoint_company_zip');
	$saasappoint_company_country = $obj_settings->get_option('saasappoint_company_country');

	try {
		$charge = Twocheckout_Charge::auth(array(
			"merchantOrderId" => $order_id,
			"token"      => $_POST['token'],
			"currency"   => $saasappoint_currency,
			"total"      => $net_total,
			"billingAddr" => array(
				"name" => ucwords($saasappoint_company_name),
				"addrLine1" => $saasappoint_company_address,
				"city" => $saasappoint_company_city,
				"state" => $saasappoint_company_state,
				"zipCode" => $saasappoint_company_zip,
				"country" => $saasappoint_company_country,
				"email" => $saasappoint_company_email,
				"phoneNumber" => $saasappoint_company_phone
			)
		));
		
		if ($charge['response']['responseCode'] == 'APPROVED') {
			$transaction_id = $charge['response']['transactionId'];
			$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
			$saasappoint_server_timezone = date_default_timezone_get();
			$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
			$extended_on = date("Y-m-d H:i:s", $currDateTime_withTZ);
			
			$obj_sms_subscriptions_history->plan_id = $_POST["plan_id"];
			$obj_sms_subscriptions_history->amount = $plan_detail['plan_rate'];
			$obj_sms_subscriptions_history->credit = $plan_detail['credit'];
			$obj_sms_subscriptions_history->transaction_id = $transaction_id;
			$obj_sms_subscriptions_history->payment_method = "2checkout";
			$obj_sms_subscriptions_history->extended_on = $extended_on;
			$obj_sms_subscriptions_history->add_sms_subscription_history();
			$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
			echo "upgraded";
		}
	}
	catch (Exception $e) {
		$error = $e->getMessage();
		echo $error;die;
	}
}

/* Upgrade SMS Plan ajax */
else if(isset($_POST['upgrade_sms_plan_authorizenet'])){
	$obj_sms_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan();
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$currency = $obj_settings->get_superadmin_option('saasappoint_currency');
	
	$updated_sms_credit = $sms_credit+$plan_detail['credit'];
	
	include(dirname(dirname(dirname(__FILE__)))."/includes/payments/authorize.net/autoload.php");
	$saasappoint_authorizenet_api_login_id = $obj_settings->get_superadmin_option('saasappoint_authorizenet_api_login_id');
	$saasappoint_authorizenet_transaction_key = $obj_settings->get_superadmin_option('saasappoint_authorizenet_transaction_key');
	$saasappoint_company_name = $obj_settings->get_option('saasappoint_company_name');
	$saasappoint_company_email = $obj_settings->get_option('saasappoint_company_email');
	$saasappoint_company_phone = $obj_settings->get_option('saasappoint_company_phone');
	$testmode = "off";

	if($testmode=='on'){   
		define('AUTHORIZENET_SANDBOX',true); 
	}else{  
		define('AUTHORIZENET_SANDBOX',false);
	}

	$payment_response = null;
	$net_total = $plan_detail["plan_rate"];
	define('AUTHORIZENET_API_LOGIN_ID',$saasappoint_authorizenet_api_login_id);
	define('AUTHORIZENET_TRANSACTION_KEY',$saasappoint_authorizenet_transaction_key);
	$expirydate = $_POST['cardexmonth'].'/'.$_POST['cardexyear'];
	$aim_sale = new AuthorizeNetAIM();
	$aim_sale->amount     = $net_total;
	$aim_sale->card_num   = $_POST['cardnumber'];
	$aim_sale->card_code  = $_POST['cardcvv'];
	$aim_sale->exp_date   = $expirydate;
	$aim_sale->first_name = ucwords($saasappoint_company_name);
	$aim_sale->email      = $saasappoint_company_email;
	$aim_sale->phone      = $saasappoint_company_phone;
	$payment_response = $aim_sale->authorizeAndCapture();
	
	if ( $payment_response->approved ) {				
		$transaction_id = $payment_response->transaction_id;
		$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
		$saasappoint_server_timezone = date_default_timezone_get();
		$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
		$extended_on = date("Y-m-d H:i:s", $currDateTime_withTZ);
		
		$obj_sms_subscriptions_history->plan_id = $_POST["plan_id"];
		$obj_sms_subscriptions_history->amount = $plan_detail['plan_rate'];
		$obj_sms_subscriptions_history->credit = $plan_detail['credit'];
		$obj_sms_subscriptions_history->transaction_id = $transaction_id;
		$obj_sms_subscriptions_history->payment_method = "authorize.net";
		$obj_sms_subscriptions_history->extended_on = $extended_on;
		$obj_sms_subscriptions_history->add_sms_subscription_history();
		$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
		echo "upgraded";
	}else {
		echo $payment_response->error_message;
		exit;
	}
}

/* Upgrade SMS Plan ajax */
else if(isset($_POST['upgrade_sms_plan_pay_manually'])){
	$obj_sms_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_sms_plans->readone_sms_plan();
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['credit'];

	$transaction_id = "";
	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	$extended_on = date("Y-m-d H:i:s", $currDateTime_withTZ);
	
	$obj_sms_subscriptions_history->plan_id = $_POST["plan_id"];
	$obj_sms_subscriptions_history->amount = $plan_detail['plan_rate'];
	$obj_sms_subscriptions_history->credit = $plan_detail['credit'];
	$obj_sms_subscriptions_history->transaction_id = $transaction_id;
	$obj_sms_subscriptions_history->payment_method = "pay manually";
	$obj_sms_subscriptions_history->extended_on = $extended_on;
	$obj_sms_subscriptions_history->add_sms_subscription_history();
	$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
	echo "upgraded";
}