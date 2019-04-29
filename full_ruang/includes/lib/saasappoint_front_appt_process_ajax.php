<?php 
session_start();
/* book appointment process */
if(sizeof($_SESSION['saasappoint_customer_detail'])>0){
	
	/* Include class files */
	include(dirname(dirname(dirname(__FILE__)))."/constants.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

	/* Create object of classes */
	$obj_database = new saasappoint_database();
	$conn = $obj_database->connect();

	$obj_frontend = new saasappoint_frontend();
	$obj_frontend->conn = $conn;
	$obj_frontend->business_id = $_SESSION['business_id'];

	$obj_settings = new saasappoint_settings();
	$obj_settings->conn = $conn;
	$obj_settings->business_id = $_SESSION['business_id'];

	$saasappoint_timeslot_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
	$saasappoint_auto_confirm_appointment = $obj_settings->get_option('saasappoint_auto_confirm_appointment');
	if($saasappoint_auto_confirm_appointment == "24"){
		$booking_status = "confirmed";
	}else{
		$booking_status = "pending";
	}
	
	$saasappoint_customer_detail = $_SESSION['saasappoint_customer_detail'];
	$order_id = $obj_frontend->get_order_id();
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	/** Pass values to the public variable in class file **/
	$obj_frontend->order_id = $order_id;
	$obj_frontend->booking_datetime = $_SESSION['saasappoint_cart_datetime'];
	$obj_frontend->booking_end_datetime = $_SESSION['saasappoint_cart_end_datetime'];
	/* $obj_frontend->booking_end_datetime = date("Y-m-d H:i:s", strtotime("+".$saasappoint_timeslot_interval." minutes", strtotime($_SESSION['saasappoint_cart_datetime']))); */
	$obj_frontend->order_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->category_id = $_SESSION['saasappoint_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['saasappoint_cart_service_id'];
	$obj_frontend->addons = serialize($_SESSION['saasappoint_cart_items']);
	$obj_frontend->booking_status = $booking_status;
	$obj_frontend->lastmodified = date("Y-m-d H:i:s");

	$obj_frontend->email = trim(strip_tags(mysqli_real_escape_string($conn, $saasappoint_customer_detail['email'])));
	$obj_frontend->password = md5($saasappoint_customer_detail['password']);
	$obj_frontend->firstname = filter_var($saasappoint_customer_detail['firstname'], FILTER_SANITIZE_STRING);
	$obj_frontend->lastname = filter_var($saasappoint_customer_detail['lastname'], FILTER_SANITIZE_STRING);
	$obj_frontend->phone = $saasappoint_customer_detail['phone'];
	$obj_frontend->address = filter_var($saasappoint_customer_detail['address'], FILTER_SANITIZE_STRING);
	$obj_frontend->city = filter_var($saasappoint_customer_detail['city'], FILTER_SANITIZE_STRING);
	$obj_frontend->state = filter_var($saasappoint_customer_detail['state'], FILTER_SANITIZE_STRING);
	$obj_frontend->country = filter_var($saasappoint_customer_detail['country'], FILTER_SANITIZE_STRING);
	$obj_frontend->zip = filter_var($saasappoint_customer_detail['zip'], FILTER_SANITIZE_STRING);

	$obj_frontend->payment_method = $saasappoint_customer_detail['payment_method'];
	$obj_frontend->payment_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->transaction_id = $_SESSION['transaction_id'];
	$obj_frontend->sub_total = $_SESSION['saasappoint_cart_subtotal'];
	$obj_frontend->discount = $_SESSION['saasappoint_cart_coupondiscount'];
	$obj_frontend->tax = $_SESSION['saasappoint_cart_tax'];
	$obj_frontend->net_total = $_SESSION['saasappoint_cart_nettotal'];
	$obj_frontend->fd_key = $_SESSION['saasappoint_cart_freqdiscount_key'];
	$obj_frontend->fd_amount = $_SESSION['saasappoint_cart_freqdiscount'];
	$obj_frontend->refer_discount = $_SESSION['saasappoint_referral_discount_amount'];
	$obj_frontend->refer_discount_id = $_SESSION['saasappoint_applied_ref_customer_id'];

	$obj_frontend->coupon_id = $_SESSION['saasappoint_cart_couponid'];
	$obj_frontend->is_expired = "Y";
	$obj_frontend->used_on = date("Y-m-d", $currDateTime_withTZ);

	$obj_frontend->fd_id = $_SESSION['saasappoint_cart_freqdiscount_id'];
	
	/** check customer type **/
	if($saasappoint_customer_detail['customertype'] == "ec"){
		$customer_id = $_SESSION['customer_id'];
		if(is_numeric($customer_id)){
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
					if($payment_added){
						/** add used coupon detail **/
						if($_SESSION['saasappoint_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
						/** add referral discount detail **/
						if($_SESSION['saasappoint_ref_customer_id'] != ""){
							$ref_discount_type = $obj_settings->get_option('saasappoint_referral_discount_type');
							$ref_discount = $obj_settings->get_option('saasappoint_referral_discount_value');
							$obj_frontend->ref_customer_id = $_SESSION['saasappoint_ref_customer_id'];
							$obj_frontend->ref_discount = $ref_discount;
							$obj_frontend->ref_discount_type = $ref_discount_type;
							$customer_referrals_added = $obj_frontend->add_customer_referral();
						}
						/** update referral discount detail **/
						if($_SESSION['saasappoint_applied_ref_customer_id'] != ""){
							$update_customer_referral_used = $obj_frontend->update_customer_referral_used($_SESSION['saasappoint_applied_ref_customer_id']);
						}
					}
				}
			}
		}
	}else if($saasappoint_customer_detail['customertype'] == "gc"){
		$customer_id = 0;
		if(is_numeric($customer_id)){
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
					if($payment_added){
						/** add used coupon detail **/
						if($_SESSION['saasappoint_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
					}
				}
			}
		}
	} else {
		$customer_id = $obj_frontend->add_customers();
		if(is_numeric($customer_id)){
			
			/* Set session values for logged in customer */
			unset($_SESSION['admin_id']);
			unset($_SESSION['superadmin_id']);
			$_SESSION['customer_id'] = $customer_id;
			$_SESSION['login_type'] = "customer";
			
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
					if($payment_added){
						/** add used coupon detail **/
						if($_SESSION['saasappoint_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
							/** add referral discount detail **/
							if($_SESSION['saasappoint_ref_customer_id'] != ""){
								$ref_discount_type = $obj_settings->get_option('saasappoint_referral_discount_type');
								$ref_discount = $obj_settings->get_option('saasappoint_referral_discount_value');
								$obj_frontend->ref_customer_id = $_SESSION['saasappoint_ref_customer_id'];
								$obj_frontend->ref_discount = $ref_discount;
								$obj_frontend->ref_discount_type = $ref_discount_type;
								$customer_referrals_added = $obj_frontend->add_customer_referral();
							}
						}
					}
				}
			}
		}
	}
	
	/********************** Send SMS & Email code start ***************************/
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
	$obj_es_information = new saasappoint_es_information();
	$obj_es_information->conn = $conn;
	$obj_es_information->business_id = $_SESSION['business_id'];
	
	$es_template = "new";
	$es_category_id = $_SESSION['saasappoint_cart_category_id'];
	$es_service_id = $_SESSION['saasappoint_cart_service_id'];
	$es_booking_datetime = $_SESSION['saasappoint_cart_datetime']." to ".$_SESSION['saasappoint_cart_datetime'];
	$es_transaction_id = $_SESSION['transaction_id'];
	$es_subtotal = $_SESSION['saasappoint_cart_subtotal'];
	$es_coupondiscount = $_SESSION['saasappoint_cart_coupondiscount'];
	$es_freqdiscount = $_SESSION['saasappoint_cart_freqdiscount'];
	$es_tax = $_SESSION['saasappoint_cart_tax'];
	$es_nettotal = $_SESSION['saasappoint_cart_nettotal'];
	$es_payment_method = $saasappoint_customer_detail['payment_method'];
	$es_firstname = $saasappoint_customer_detail['firstname'];
	$es_lastname = $saasappoint_customer_detail['lastname'];
	$es_email = $saasappoint_customer_detail['email'];
	$es_phone = $saasappoint_customer_detail['phone'];
	$es_address = $saasappoint_customer_detail['address'];
	$es_city = $saasappoint_customer_detail['city'];
	$es_state = $saasappoint_customer_detail['state'];
	$es_country = $saasappoint_customer_detail['country'];
	$es_zip = $saasappoint_customer_detail['zip'];
	$es_addons_items_arr = $_SESSION['saasappoint_cart_items'];
	$es_reschedule_reason = "";
	$es_reject_reason = "";
	$es_cancel_reason = "";
	include("saasappoint_send_sms_email_process.php");
	/********************** Send SMS & Email code END ****************************/
	
	
	/** Unset related sessions **/
	if($saasappoint_customer_detail['payment_method'] == "paypal"){
		$_SESSION['saasappoint_customer_detail'] = array();
		$_SESSION['saasappoint_cart_items'] = array();
		$_SESSION['saasappoint_cart_category_id'] = "";
		$_SESSION['saasappoint_cart_service_id'] = "";
		$_SESSION['saasappoint_cart_datetime'] = "";
		$_SESSION['saasappoint_cart_end_datetime'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_label'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_key'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_id'] = "";
		$_SESSION['saasappoint_cart_subtotal'] = 0;
		$_SESSION['saasappoint_cart_freqdiscount'] = 0;
		$_SESSION['saasappoint_cart_coupondiscount'] = 0;
		$_SESSION['saasappoint_cart_couponid'] = "";
		$_SESSION['saasappoint_cart_tax'] = 0;
		$_SESSION['saasappoint_cart_nettotal'] = 0;
		header("location:".SITE_URL."thankyou.php");
		exit;
	}else{
		$_SESSION['saasappoint_customer_detail'] = array();
		$_SESSION['saasappoint_cart_items'] = array();
		$_SESSION['saasappoint_cart_category_id'] = "";
		$_SESSION['saasappoint_cart_service_id'] = "";
		$_SESSION['saasappoint_cart_datetime'] = "";
		$_SESSION['saasappoint_cart_end_datetime'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_label'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_key'] = "";
		$_SESSION['saasappoint_cart_freqdiscount_id'] = "";
		$_SESSION['saasappoint_cart_subtotal'] = 0;
		$_SESSION['saasappoint_cart_freqdiscount'] = 0;
		$_SESSION['saasappoint_cart_coupondiscount'] = 0;
		$_SESSION['saasappoint_cart_couponid'] = "";
		$_SESSION['saasappoint_cart_tax'] = 0;
		$_SESSION['saasappoint_cart_nettotal'] = 0;
		@ob_clean(); ob_start();
		echo "BOOKED";
		exit;
	}
}