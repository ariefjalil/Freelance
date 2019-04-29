<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

/** Update company settings Ajax **/
if(isset($_POST['update_company_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_company_name',$_POST['saasappoint_company_name']);
	$obj_settings->update_superadmin_option('saasappoint_company_email',$_POST['saasappoint_company_email']);
	$obj_settings->update_superadmin_option('saasappoint_company_phone',$_POST['saasappoint_company_phone']);
	$obj_settings->update_superadmin_option('saasappoint_currency',$_POST['saasappoint_currency']);
	$obj_settings->update_superadmin_option('saasappoint_currency_symbol',$_POST['saasappoint_currency_symbol']);
	$obj_settings->update_superadmin_option('saasappoint_timezone',$_POST['saasappoint_timezone']);
	$obj_settings->update_superadmin_option('saasappoint_date_format',$_POST['saasappoint_date_format']);
	$obj_settings->update_superadmin_option('saasappoint_time_format',$_POST['saasappoint_time_format']);
}

/** change reminder buffer time settings Ajax **/
else if(isset($_POST['change_reminder_buffer_time'])){
	$obj_settings->update_superadmin_option('saasappoint_reminder_buffer_time',$_POST['saasappoint_reminder_buffer_time']);
}

/** Update Pay at Venue Payment Status Ajax **/
else if(isset($_POST['change_pay_at_venue_status'])){
	$obj_settings->update_superadmin_option('saasappoint_pay_at_venue_status',$_POST['saasappoint_pay_at_venue_status']);
}

/** Update email settings Ajax **/
else if(isset($_POST['update_email_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_email_sender_name',$_POST['saasappoint_email_sender_name']);
	$obj_settings->update_superadmin_option('saasappoint_email_sender_email',$_POST['saasappoint_email_sender_email']);
	$obj_settings->update_superadmin_option('saasappoint_email_smtp_hostname',$_POST['saasappoint_email_smtp_hostname']);
	$obj_settings->update_superadmin_option('saasappoint_email_smtp_username',$_POST['saasappoint_email_smtp_username']);
	$obj_settings->update_superadmin_option('saasappoint_email_smtp_password',$_POST['saasappoint_email_smtp_password']);
	$obj_settings->update_superadmin_option('saasappoint_email_smtp_port',$_POST['saasappoint_email_smtp_port']);
	$obj_settings->update_superadmin_option('saasappoint_email_encryption_type',$_POST['saasappoint_email_encryption_type']);
	$obj_settings->update_superadmin_option('saasappoint_email_smtp_authentication',$_POST['saasappoint_email_smtp_authentication']);
}

/** Update Twilio SMS settings Ajax **/
else if(isset($_POST['update_twilio_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_twilio_sms_status',$_POST['saasappoint_twilio_sms_status']);
	$obj_settings->update_superadmin_option('saasappoint_twilio_account_SID',$_POST['saasappoint_twilio_account_SID']);
	$obj_settings->update_superadmin_option('saasappoint_twilio_auth_token',$_POST['saasappoint_twilio_auth_token']);
	$obj_settings->update_superadmin_option('saasappoint_twilio_sender_number',$_POST['saasappoint_twilio_sender_number']);
}

/** Update Plivo SMS settings Ajax **/
else if(isset($_POST['update_plivo_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_plivo_sms_status',$_POST['saasappoint_plivo_sms_status']);
	$obj_settings->update_superadmin_option('saasappoint_plivo_account_SID',$_POST['saasappoint_plivo_account_SID']);
	$obj_settings->update_superadmin_option('saasappoint_plivo_auth_token',$_POST['saasappoint_plivo_auth_token']);
	$obj_settings->update_superadmin_option('saasappoint_plivo_sender_number',$_POST['saasappoint_plivo_sender_number']);
}

/** Update Nexmo SMS settings Ajax **/
else if(isset($_POST['update_nexmo_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_nexmo_sms_status',$_POST['saasappoint_nexmo_sms_status']);
	$obj_settings->update_superadmin_option('saasappoint_nexmo_api_key',$_POST['saasappoint_nexmo_api_key']);
	$obj_settings->update_superadmin_option('saasappoint_nexmo_api_secret',$_POST['saasappoint_nexmo_api_secret']);
	$obj_settings->update_superadmin_option('saasappoint_nexmo_from',$_POST['saasappoint_nexmo_from']);
}

/** Update TextLocal SMS settings Ajax **/
else if(isset($_POST['update_textlocal_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_textlocal_sms_status',$_POST['saasappoint_textlocal_sms_status']);
	$obj_settings->update_superadmin_option('saasappoint_textlocal_api_key',$_POST['saasappoint_textlocal_api_key']);
	$obj_settings->update_superadmin_option('saasappoint_textlocal_sender',$_POST['saasappoint_textlocal_sender']);
	$obj_settings->update_superadmin_option('saasappoint_textlocal_country',$_POST['saasappoint_textlocal_country']);
}

/** Update Paypal Payment settings Ajax **/
else if(isset($_POST['update_paypal_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_paypal_payment_status',$_POST['saasappoint_paypal_payment_status']);
	$obj_settings->update_superadmin_option('saasappoint_paypal_guest_payment',$_POST['saasappoint_paypal_guest_payment']);
	$obj_settings->update_superadmin_option('saasappoint_paypal_api_username',$_POST['saasappoint_paypal_api_username']);
	$obj_settings->update_superadmin_option('saasappoint_paypal_api_password',$_POST['saasappoint_paypal_api_password']);
	$obj_settings->update_superadmin_option('saasappoint_paypal_signature',$_POST['saasappoint_paypal_signature']);
}

/** Update stripe Payment settings Ajax **/
else if(isset($_POST['update_stripe_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_authorizenet_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_stripe_payment_status',$_POST['saasappoint_stripe_payment_status']);
	$obj_settings->update_superadmin_option('saasappoint_stripe_secretkey',$_POST['saasappoint_stripe_secret_key']);
	$obj_settings->update_superadmin_option('saasappoint_stripe_publickey',$_POST['saasappoint_stripe_publishable_key']);
}

/** Update Authorize.net Payment settings Ajax **/
else if(isset($_POST['update_authorizenet_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_stripe_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_authorizenet_payment_status',$_POST['saasappoint_authorizenet_payment_status']);
	$obj_settings->update_superadmin_option('saasappoint_authorizenet_api_login_id',$_POST['saasappoint_authorizenet_api_login_id']);
	$obj_settings->update_superadmin_option('saasappoint_authorizenet_transaction_key',$_POST['saasappoint_authorizenet_transaction_key']);
}

/** Update 2Checkout Payment settings Ajax **/
else if(isset($_POST['update_twocheckout_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_stripe_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_authorizenet_payment_status',"N");
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_payment_status',$_POST['saasappoint_twocheckout_payment_status']);
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_publishable_key',$_POST['saasappoint_twocheckout_publishable_key']);
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_private_key',$_POST['saasappoint_twocheckout_private_key']);
	$obj_settings->update_superadmin_option('saasappoint_twocheckout_seller_id',$_POST['saasappoint_twocheckout_seller_id']);
}

/* Get payment setting form ajax */
else if(isset($_POST['get_payment_settings'])){
	if($_POST['get_payment_settings'] == "1"){
		?>
		<form name="saasappoint_paypal_payment_settings_form" id="saasappoint_paypal_payment_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Paypal Payment Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_paypal_payment_status" id="saasappoint_paypal_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_paypal_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="row">
				<label class="col-md-6">Paypal Guest Payment</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_paypal_guest_payment" id="saasappoint_paypal_guest_payment" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_paypal_guest_payment")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_api_username">API Username</label>
				<input class="form-control" id="saasappoint_paypal_api_username" name="saasappoint_paypal_api_username" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_paypal_api_username"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_api_password">API Password</label>
				<input class="form-control" id="saasappoint_paypal_api_password" name="saasappoint_paypal_api_password" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_paypal_api_password"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_signature">Signature</label>
				<input class="form-control" id="saasappoint_paypal_signature" name="saasappoint_paypal_signature" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_paypal_signature"); ?>" />
			</div>
		</form>
		<?php
	}
	else if($_POST['get_payment_settings'] == "2"){
		?>
		<form name="saasappoint_stripe_payment_settings_form" id="saasappoint_stripe_payment_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Stripe Payment Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_stripe_payment_status" id="saasappoint_stripe_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_stripe_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_stripe_secret_key">Secret Key</label>
				<input class="form-control" id="saasappoint_stripe_secret_key" name="saasappoint_stripe_secret_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_stripe_secretkey"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_stripe_publishable_key">Publishable Key</label>
				<input class="form-control" id="saasappoint_stripe_publishable_key" name="saasappoint_stripe_publishable_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_stripe_publickey"); ?>" />
			</div>
		</form>
		<?php
	}
	else if($_POST['get_payment_settings'] == "3"){
		?>
		<form name="saasappoint_authorizenet_payment_settings_form" id="saasappoint_authorizenet_payment_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Authorize.net Payment Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_authorizenet_payment_status" id="saasappoint_authorizenet_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_authorizenet_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_authorizenet_api_login_id">API Login ID</label>
				<input class="form-control" id="saasappoint_authorizenet_api_login_id" name="saasappoint_authorizenet_api_login_id" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_authorizenet_api_login_id"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_authorizenet_transaction_key">Transaction Key</label>
				<input class="form-control" id="saasappoint_authorizenet_transaction_key" name="saasappoint_authorizenet_transaction_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_authorizenet_transaction_key"); ?>" />
			</div>
		</form>
		<?php
	}
	else if($_POST['get_payment_settings'] == "4"){
		?>
		<form name="saasappoint_twocheckout_payment_settings_form" id="saasappoint_twocheckout_payment_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">2Checkout Payment Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_twocheckout_payment_status" id="saasappoint_twocheckout_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_twocheckout_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_publishable_key">Publishable Key</label>
				<input class="form-control" id="saasappoint_twocheckout_publishable_key" name="saasappoint_twocheckout_publishable_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twocheckout_publishable_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_private_key">Private Key</label>
				<input class="form-control" id="saasappoint_twocheckout_private_key" name="saasappoint_twocheckout_private_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twocheckout_private_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_seller_id">Seller ID</label>
				<input class="form-control" id="saasappoint_twocheckout_seller_id" name="saasappoint_twocheckout_seller_id" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twocheckout_seller_id"); ?>" />
			</div>
		</form>
		<?php
	}
}

/* Get SMS setting form ajax */
else if(isset($_POST['get_sms_settings'])){
	if($_POST['get_sms_settings'] == "1"){ 
		?>
		<form name="saasappoint_twilio_sms_settings_form" id="saasappoint_twilio_sms_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Twilio SMS Gateway Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_twilio_sms_status" id="saasappoint_twilio_sms_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_twilio_sms_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_twilio_account_SID">Account SID</label>
				<input class="form-control" id="saasappoint_twilio_account_SID" name="saasappoint_twilio_account_SID" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twilio_account_SID"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twilio_auth_token">Auth Token</label>
				<input class="form-control" id="saasappoint_twilio_auth_token" name="saasappoint_twilio_auth_token" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twilio_auth_token"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twilio_sender_number">Twilio Sender Number</label>
				<input class="form-control" id="saasappoint_twilio_sender_number" name="saasappoint_twilio_sender_number" type="text" placeholder="e.g. 3899815981" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_twilio_sender_number"); ?>" />
			</div>
		</form>
		<?php 
	}
	else if($_POST['get_sms_settings'] == "2"){ 
		?>
		<form name="saasappoint_plivo_sms_settings_form" id="saasappoint_plivo_sms_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Plivo SMS Gateway Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_plivo_sms_status" id="saasappoint_plivo_sms_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_plivo_sms_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_plivo_account_SID">Account SID</label>
				<input class="form-control" id="saasappoint_plivo_account_SID" name="saasappoint_plivo_account_SID" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_plivo_account_SID"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_plivo_auth_token">Auth Token</label>
				<input class="form-control" id="saasappoint_plivo_auth_token" name="saasappoint_plivo_auth_token" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_plivo_auth_token"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_plivo_sender_number">Plivo Sender Number</label>
				<input class="form-control" id="saasappoint_plivo_sender_number" name="saasappoint_plivo_sender_number" type="text" placeholder="e.g. 7513842981" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_plivo_sender_number"); ?>" />
			</div>
		</form>
		<?php 
	}
	else if($_POST['get_sms_settings'] == "3"){ 
		?>
		<form name="saasappoint_nexmo_sms_settings_form" id="saasappoint_nexmo_sms_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Nexmo SMS Gateway Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_nexmo_sms_status" id="saasappoint_nexmo_sms_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_nexmo_sms_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_nexmo_api_key">API Key</label>
				<input class="form-control" id="saasappoint_nexmo_api_key" name="saasappoint_nexmo_api_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_nexmo_api_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_nexmo_api_secret">API Secret</label>
				<input class="form-control" id="saasappoint_nexmo_api_secret" name="saasappoint_nexmo_api_secret" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_nexmo_api_secret"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_nexmo_from">Nexmo From</label>
				<input class="form-control" id="saasappoint_nexmo_from" name="saasappoint_nexmo_from" type="text" placeholder="e.g. NEXMO" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_nexmo_from"); ?>" />
			</div>
		</form>
		<?php 
	}
	else if($_POST['get_sms_settings'] == "4"){ 
		?>
		<form name="saasappoint_textlocal_sms_settings_form" id="saasappoint_textlocal_sms_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">TextLocal SMS Gateway Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_textlocal_sms_status" id="saasappoint_textlocal_sms_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_superadmin_option("saasappoint_textlocal_sms_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_textlocal_api_key">API Key</label>
				<input class="form-control" id="saasappoint_textlocal_api_key" name="saasappoint_textlocal_api_key" type="text" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_textlocal_api_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_textlocal_sender">TextLocal Sender</label>
				<input class="form-control" id="saasappoint_textlocal_sender" name="saasappoint_textlocal_sender" type="text" placeholder="e.g. TXTLCL" value="<?php echo $obj_settings->get_superadmin_option("saasappoint_textlocal_sender"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_textlocal_country">TextLocal Country</label>
				<?php $saasappoint_textlocal_country = $obj_settings->get_superadmin_option('saasappoint_textlocal_country'); ?>
				<select name="saasappoint_textlocal_country" id="saasappoint_textlocal_country" class="form-control">
					<optgroup label="Europe">
						<option <?php if($saasappoint_textlocal_country == 'Denmark'){ echo "selected"; } ?> value="Denmark">Denmark (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Finland'){ echo "selected"; } ?> value="Finland">Finland (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'France'){ echo "selected"; } ?> value="France">France (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Germany'){ echo "selected"; } ?> value="Germany">Germany (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Iceland'){ echo "selected"; } ?> value="Iceland">Iceland (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Ireland'){ echo "selected"; } ?> value="Ireland">Ireland (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Italy'){ echo "selected"; } ?> value="Italy">Italy (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Netherlands'){ echo "selected"; } ?> value="Netherlands">Netherlands (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Norway'){ echo "selected"; } ?> value="Norway">Norway (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Portugal'){ echo "selected"; } ?> value="Portugal">Portugal (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'Espana'){ echo "selected"; } ?> value="Espana">Espana (Espanol)</option>
						<option <?php if($saasappoint_textlocal_country == 'Sweden'){ echo "selected"; } ?> value="Sweden">Sweden (English)</option>
						<option <?php if($saasappoint_textlocal_country == 'UnitedKingdom'){ echo "selected"; } ?> value="UnitedKingdom">United Kingdom (English)</option>
					</optgroup>
					<optgroup label="Asia">
						<option <?php if($saasappoint_textlocal_country == 'India'){ echo "selected"; } ?> value="India">India</option>
					</optgroup>
				</select>
			</div>
		</form>
		<?php 
	}
}

/** Update SEO settings Ajax **/
else if(isset($_POST['update_seo_settings'])){
	$obj_settings->update_superadmin_option('saasappoint_seo_ga_code',$_POST['saasappoint_seo_ga_code']);
	$obj_settings->update_superadmin_option('saasappoint_seo_meta_tag',$_POST['saasappoint_seo_meta_tag']);
	$obj_settings->update_superadmin_option('saasappoint_seo_meta_description',$_POST['saasappoint_seo_meta_description']);
	$obj_settings->update_superadmin_option('saasappoint_seo_og_meta_tag',$_POST['saasappoint_seo_og_meta_tag']);
	$obj_settings->update_superadmin_option('saasappoint_seo_og_tag_type',$_POST['saasappoint_seo_og_tag_type']);
	$obj_settings->update_superadmin_option('saasappoint_seo_og_tag_url',$_POST['saasappoint_seo_og_tag_url']);
	
	$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/images/";
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_settings->get_superadmin_option("saasappoint_seo_og_tag_image");
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = "sa_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_settings->update_superadmin_option('saasappoint_seo_og_tag_image',$uploaded_filename);
	}
}
