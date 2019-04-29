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
$obj_settings->business_id = $_SESSION['business_id'];

$image_upload_path = SITE_URL."/includes/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/includes/images/";

/** Update company settings Ajax **/
if(isset($_POST['update_company_settings'])){
	$obj_settings->update_option('saasappoint_company_name',$_POST['saasappoint_company_name']);
	$obj_settings->update_option('saasappoint_company_email',$_POST['saasappoint_company_email']);
	$obj_settings->update_option('saasappoint_company_phone',$_POST['saasappoint_company_phone']);
	$obj_settings->update_option('saasappoint_company_address',$_POST['saasappoint_company_address']);
	$obj_settings->update_option('saasappoint_company_city',$_POST['saasappoint_company_city']);
	$obj_settings->update_option('saasappoint_company_state',$_POST['saasappoint_company_state']);
	$obj_settings->update_option('saasappoint_company_zip',$_POST['saasappoint_company_zip']);
	$obj_settings->update_option('saasappoint_company_country',$_POST['saasappoint_company_country']);
	
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_settings->get_option("saasappoint_company_logo");
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_settings->update_option('saasappoint_company_logo',$uploaded_filename);
	}
}

/** Update appearance settings Ajax **/
else if(isset($_POST['update_appearance_settings'])){
	$obj_settings->update_option('saasappoint_timeslot_interval',$_POST['saasappoint_timeslot_interval']);
	$obj_settings->update_option('saasappoint_endtimeslot_selection_status',$_POST['saasappoint_endtimeslot_selection_status']);
	$obj_settings->update_option('saasappoint_maximum_endtimeslot_limit',$_POST['saasappoint_maximum_endtimeslot_limit']);
	$obj_settings->update_option('saasappoint_currency',$_POST['saasappoint_currency']);
	$obj_settings->update_option('saasappoint_currency_symbol',$_POST['saasappoint_currency_symbol']);
	$obj_settings->update_option('saasappoint_auto_confirm_appointment',$_POST['saasappoint_auto_confirm_appointment']);
	$obj_settings->update_option('saasappoint_tax_status',$_POST['saasappoint_tax_status']);
	$obj_settings->update_option('saasappoint_tax_type',$_POST['saasappoint_tax_type']);
	$obj_settings->update_option('saasappoint_tax_value',$_POST['saasappoint_tax_value']);
	$obj_settings->update_option('saasappoint_minimum_booking_amount',$_POST['saasappoint_minimum_booking_amount']);
	$obj_settings->update_option('saasappoint_minimum_advance_booking_time',$_POST['saasappoint_minimum_advance_booking_time']);
	$obj_settings->update_option('saasappoint_maximum_advance_booking_time',$_POST['saasappoint_maximum_advance_booking_time']);
	$obj_settings->update_option('saasappoint_cancellation_buffer_time',$_POST['saasappoint_cancellation_buffer_time']);
	$obj_settings->update_option('saasappoint_reschedule_buffer_time',$_POST['saasappoint_reschedule_buffer_time']);
	$obj_settings->update_option('saasappoint_date_format',$_POST['saasappoint_date_format']);
	$obj_settings->update_option('saasappoint_time_format',$_POST['saasappoint_time_format']);
	$obj_settings->update_option('saasappoint_timezone',$_POST['saasappoint_timezone']);
	$obj_settings->update_option('saasappoint_show_frontend_rightside_feedback_list',$_POST['saasappoint_show_frontend_rightside_feedback_list']);
	$obj_settings->update_option('saasappoint_show_frontend_rightside_feedback_form',$_POST['saasappoint_show_frontend_rightside_feedback_form']);
	$obj_settings->update_option('saasappoint_show_guest_user_checkout',$_POST['saasappoint_show_guest_user_checkout']);
	$obj_settings->update_option('saasappoint_show_existing_new_user_checkout',$_POST['saasappoint_show_existing_new_user_checkout']);
	$obj_settings->update_option('saasappoint_hide_already_booked_slots_from_frontend_calendar',$_POST['saasappoint_hide_already_booked_slots_from_frontend_calendar']);
	$obj_settings->update_option('saasappoint_thankyou_page_url',$_POST['saasappoint_thankyou_page_url']);
	$obj_settings->update_option('saasappoint_terms_and_condition_link',$_POST['saasappoint_terms_and_condition_link']);
}

/** Update email settings Ajax **/
else if(isset($_POST['update_email_settings'])){
	$obj_settings->update_option('saasappoint_admin_email_notification_status',$_POST['saasappoint_admin_email_notification_status']);
	$obj_settings->update_option('saasappoint_customer_email_notification_status',$_POST['saasappoint_customer_email_notification_status']);
	$obj_settings->update_option('saasappoint_email_sender_name',$_POST['saasappoint_email_sender_name']);
	$obj_settings->update_option('saasappoint_email_sender_email',$_POST['saasappoint_email_sender_email']);
}

/** Update SMS settings Ajax **/
else if(isset($_POST['update_sms_settings'])){
	$obj_settings->update_option('saasappoint_admin_sms_notification_status',$_POST['saasappoint_admin_sms_notification_status']);
	$obj_settings->update_option('saasappoint_customer_sms_notification_status',$_POST['saasappoint_customer_sms_notification_status']);
}

/** Update Pay at Venue Payment Status Ajax **/
else if(isset($_POST['change_pay_at_venue_status'])){
	$obj_settings->update_option('saasappoint_pay_at_venue_status',$_POST['saasappoint_pay_at_venue_status']);
}

/** Update Referral settings Ajax **/
else if(isset($_POST['update_referral_discount_settings'])){
	$obj_settings->update_option('saasappoint_referral_discount_status',$_POST['saasappoint_referral_discount_status']);
	$obj_settings->update_option('saasappoint_referral_discount_type',$_POST['saasappoint_referral_discount_type']);
	$obj_settings->update_option('saasappoint_referral_discount_value',$_POST['saasappoint_referral_discount_value']);
}

/** Update SEO settings Ajax **/
else if(isset($_POST['update_seo_settings'])){
	$obj_settings->update_option('saasappoint_seo_ga_code',$_POST['saasappoint_seo_ga_code']);
	$obj_settings->update_option('saasappoint_seo_meta_tag',$_POST['saasappoint_seo_meta_tag']);
	$obj_settings->update_option('saasappoint_seo_meta_description',$_POST['saasappoint_seo_meta_description']);
	$obj_settings->update_option('saasappoint_seo_og_meta_tag',$_POST['saasappoint_seo_og_meta_tag']);
	$obj_settings->update_option('saasappoint_seo_og_tag_type',$_POST['saasappoint_seo_og_tag_type']);
	$obj_settings->update_option('saasappoint_seo_og_tag_url',$_POST['saasappoint_seo_og_tag_url']);
	
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_settings->get_option("saasappoint_seo_og_tag_image");
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_settings->update_option('saasappoint_seo_og_tag_image',$uploaded_filename);
	}
}

/** Update Welcome Message settings Ajax **/
else if(isset($_POST['update_welcome_message_settings'])){
	$obj_settings->update_option('saasappoint_welcome_message_container',base64_encode($_POST['saasappoint_welcome_message_container']));
	$obj_settings->update_option('saasappoint_welcome_message_status',$_POST['saasappoint_welcome_message_status']);
}

/** Update Booking Form settings Ajax **/
else if(isset($_POST['update_bookingform_settings'])){
	$obj_settings->update_option('saasappoint_single_category_autotrigger_status',$_POST['saasappoint_single_category_autotrigger_status']);
	$obj_settings->update_option('saasappoint_single_service_autotrigger_status',$_POST['saasappoint_single_service_autotrigger_status']);
	$obj_settings->update_option('saasappoint_bookingform_bg',$_POST['saasappoint_bookingform_bg']);
	$obj_settings->update_option('saasappoint_bookingform_bg_type',$_POST['saasappoint_bookingform_bg_type']);
	$obj_settings->update_option('saasappoint_bookingform_bg_color',$_POST['saasappoint_bookingform_bg_color']);
	
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_settings->get_option("saasappoint_bookingform_bg_image");
		if($old_image != ""){
			if(file_exists("../images/".$old_image)){
				unlink("../images/".$old_image);
			}
		}
		$new_filename = $_SESSION['business_id']."_".time();
		$uploaded_filename = $obj_settings->saasappoint_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_settings->update_option('saasappoint_bookingform_bg_image',$uploaded_filename);
	}
}
/** Update Location Selector settings Ajax **/
else if(isset($_POST['save_location_selector_settings'])){
	$saasappoint_location_selector = filter_var($_POST['saasappoint_location_selector'], FILTER_SANITIZE_STRING);
	$saasappoint_location_selector_container = base64_encode($_POST['saasappoint_location_selector_container']);
	$obj_settings->update_option('saasappoint_location_selector_status',$_POST["saasappoint_location_selector_status"]);
	$obj_settings->update_option('saasappoint_location_selector',$saasappoint_location_selector);
	$obj_settings->update_option('saasappoint_location_selector_container',$saasappoint_location_selector_container);
}

/** Update Refund settings Ajax **/
else if(isset($_POST['update_refund_settings'])){
	$saasappoint_refund_summary = base64_encode($_POST['saasappoint_refund_summary']);
	$obj_settings->update_option('saasappoint_refund_status', $_POST["saasappoint_refund_status"]);
	$obj_settings->update_option('saasappoint_refund_type', $_POST["saasappoint_refund_type"]);
	$obj_settings->update_option('saasappoint_refund_value', $_POST["saasappoint_refund_value"]);
	$obj_settings->update_option('saasappoint_refund_request_buffer_time', $_POST["saasappoint_refund_request_buffer_time"]);
	$obj_settings->update_option('saasappoint_refund_summary', $saasappoint_refund_summary);
}

/** Update Paypal Payment settings Ajax **/
else if(isset($_POST['update_paypal_settings'])){
	$obj_settings->update_option('saasappoint_paypal_payment_status',$_POST['saasappoint_paypal_payment_status']);
	$obj_settings->update_option('saasappoint_paypal_guest_payment',$_POST['saasappoint_paypal_guest_payment']);
	$obj_settings->update_option('saasappoint_paypal_api_username',$_POST['saasappoint_paypal_api_username']);
	$obj_settings->update_option('saasappoint_paypal_api_password',$_POST['saasappoint_paypal_api_password']);
	$obj_settings->update_option('saasappoint_paypal_signature',$_POST['saasappoint_paypal_signature']);
}

/** Update stripe Payment settings Ajax **/
else if(isset($_POST['update_stripe_settings'])){
	$obj_settings->update_option('saasappoint_authorizenet_payment_status',"N");
	$obj_settings->update_option('saasappoint_twocheckout_payment_status',"N");
	$obj_settings->update_option('saasappoint_stripe_payment_status',$_POST['saasappoint_stripe_payment_status']);
	$obj_settings->update_option('saasappoint_stripe_secret_key',$_POST['saasappoint_stripe_secret_key']);
	$obj_settings->update_option('saasappoint_stripe_publishable_key',$_POST['saasappoint_stripe_publishable_key']);
}

/** Update Authorize.net Payment settings Ajax **/
else if(isset($_POST['update_authorizenet_settings'])){
	$obj_settings->update_option('saasappoint_stripe_payment_status',"N");
	$obj_settings->update_option('saasappoint_twocheckout_payment_status',"N");
	$obj_settings->update_option('saasappoint_authorizenet_payment_status',$_POST['saasappoint_authorizenet_payment_status']);
	$obj_settings->update_option('saasappoint_authorizenet_api_login_id',$_POST['saasappoint_authorizenet_api_login_id']);
	$obj_settings->update_option('saasappoint_authorizenet_transaction_key',$_POST['saasappoint_authorizenet_transaction_key']);
}

/** Update 2Checkout Payment settings Ajax **/
else if(isset($_POST['update_twocheckout_settings'])){
	$obj_settings->update_option('saasappoint_stripe_payment_status',"N");
	$obj_settings->update_option('saasappoint_authorizenet_payment_status',"N");
	$obj_settings->update_option('saasappoint_twocheckout_payment_status',$_POST['saasappoint_twocheckout_payment_status']);
	$obj_settings->update_option('saasappoint_twocheckout_publishable_key',$_POST['saasappoint_twocheckout_publishable_key']);
	$obj_settings->update_option('saasappoint_twocheckout_private_key',$_POST['saasappoint_twocheckout_private_key']);
	$obj_settings->update_option('saasappoint_twocheckout_seller_id',$_POST['saasappoint_twocheckout_seller_id']);
}

/* Get payment setting form ajax */
else if(isset($_POST['get_payment_settings'])){
	if($_POST['get_payment_settings'] == "1"){
		?>
		<form name="saasappoint_paypal_payment_settings_form" id="saasappoint_paypal_payment_settings_form" method="post">
			<div class="row">
				<label class="col-md-6">Paypal Payment Status</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_paypal_payment_status" id="saasappoint_paypal_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_paypal_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="row">
				<label class="col-md-6">Paypal Guest Payment</label>
				<label class="saasappoint-toggle-switch">
					<input type="checkbox" name="saasappoint_paypal_guest_payment" id="saasappoint_paypal_guest_payment" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_paypal_guest_payment")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_api_username">API Username</label>
				<input class="form-control" id="saasappoint_paypal_api_username" name="saasappoint_paypal_api_username" type="text" value="<?php echo $obj_settings->get_option("saasappoint_paypal_api_username"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_api_password">API Password</label>
				<input class="form-control" id="saasappoint_paypal_api_password" name="saasappoint_paypal_api_password" type="text" value="<?php echo $obj_settings->get_option("saasappoint_paypal_api_password"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_paypal_signature">Signature</label>
				<input class="form-control" id="saasappoint_paypal_signature" name="saasappoint_paypal_signature" type="text" value="<?php echo $obj_settings->get_option("saasappoint_paypal_signature"); ?>" />
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
					<input type="checkbox" name="saasappoint_stripe_payment_status" id="saasappoint_stripe_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_stripe_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_stripe_secret_key">Secret Key</label>
				<input class="form-control" id="saasappoint_stripe_secret_key" name="saasappoint_stripe_secret_key" type="text" value="<?php echo $obj_settings->get_option("saasappoint_stripe_secret_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_stripe_publishable_key">Publishable Key</label>
				<input class="form-control" id="saasappoint_stripe_publishable_key" name="saasappoint_stripe_publishable_key" type="text" value="<?php echo $obj_settings->get_option("saasappoint_stripe_publishable_key"); ?>" />
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
					<input type="checkbox" name="saasappoint_authorizenet_payment_status" id="saasappoint_authorizenet_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_authorizenet_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_authorizenet_api_login_id">API Login ID</label>
				<input class="form-control" id="saasappoint_authorizenet_api_login_id" name="saasappoint_authorizenet_api_login_id" type="text" value="<?php echo $obj_settings->get_option("saasappoint_authorizenet_api_login_id"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_authorizenet_transaction_key">Transaction Key</label>
				<input class="form-control" id="saasappoint_authorizenet_transaction_key" name="saasappoint_authorizenet_transaction_key" type="text" value="<?php echo $obj_settings->get_option("saasappoint_authorizenet_transaction_key"); ?>" />
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
					<input type="checkbox" name="saasappoint_twocheckout_payment_status" id="saasappoint_twocheckout_payment_status" class="saasappoint-toggle-switch-input" <?php if($obj_settings->get_option("saasappoint_twocheckout_payment_status")=="Y"){ echo "checked"; } ?> />
					<span class="saasappoint-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_publishable_key">Publishable Key</label>
				<input class="form-control" id="saasappoint_twocheckout_publishable_key" name="saasappoint_twocheckout_publishable_key" type="text" value="<?php echo $obj_settings->get_option("saasappoint_twocheckout_publishable_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_private_key">Private Key</label>
				<input class="form-control" id="saasappoint_twocheckout_private_key" name="saasappoint_twocheckout_private_key" type="text" value="<?php echo $obj_settings->get_option("saasappoint_twocheckout_private_key"); ?>" />
			</div>
			<div class="form-group">
				<label for="saasappoint_twocheckout_seller_id">Seller ID</label>
				<input class="form-control" id="saasappoint_twocheckout_seller_id" name="saasappoint_twocheckout_seller_id" type="text" value="<?php echo $obj_settings->get_option("saasappoint_twocheckout_seller_id"); ?>" />
			</div>
		</form>
		<?php
	}
}
