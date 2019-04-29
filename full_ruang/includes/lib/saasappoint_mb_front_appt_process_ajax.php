<?php 
session_start();
/* book appointment process */
if(sizeof($_SESSION['saasappoint_mb_customer_detail'])>0){
	
	/* Include class files */
	include(dirname(dirname(dirname(__FILE__)))."/constants.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_manual_booking.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

	/* Create object of classes */
	$obj_database = new saasappoint_database();
	$conn = $obj_database->connect();

	$obj_frontend = new saasappoint_manual_booking();
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
	
	$saasappoint_mb_customer_detail = $_SESSION['saasappoint_mb_customer_detail'];
	$order_id = $obj_frontend->get_order_id();
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	/** Pass values to the public variable in class file **/
	$obj_frontend->order_id = $order_id;
	$obj_frontend->booking_datetime = $_SESSION['saasappoint_mb_cart_datetime'];
	$obj_frontend->booking_end_datetime = $_SESSION['saasappoint_mb_cart_end_datetime'];
	/* $obj_frontend->booking_end_datetime = date("Y-m-d H:i:s", strtotime("+".$saasappoint_timeslot_interval." minutes", strtotime($_SESSION['saasappoint_mb_cart_datetime']))); */
	$obj_frontend->order_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->category_id = $_SESSION['saasappoint_mb_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['saasappoint_mb_cart_service_id'];
	$obj_frontend->addons = serialize($_SESSION['saasappoint_mb_cart_items']);
	$obj_frontend->booking_status = $booking_status;
	$obj_frontend->lastmodified = date("Y-m-d H:i:s");

	$obj_frontend->email = trim(strip_tags(mysqli_real_escape_string($conn, $saasappoint_mb_customer_detail['email'])));
	$obj_frontend->password = md5($saasappoint_mb_customer_detail['password']);
	$obj_frontend->firstname = filter_var($saasappoint_mb_customer_detail['firstname'], FILTER_SANITIZE_STRING);
	$obj_frontend->lastname = filter_var($saasappoint_mb_customer_detail['lastname'], FILTER_SANITIZE_STRING);
	$obj_frontend->phone = $saasappoint_mb_customer_detail['phone'];
	$obj_frontend->address = filter_var($saasappoint_mb_customer_detail['address'], FILTER_SANITIZE_STRING);
	$obj_frontend->city = filter_var($saasappoint_mb_customer_detail['city'], FILTER_SANITIZE_STRING);
	$obj_frontend->state = filter_var($saasappoint_mb_customer_detail['state'], FILTER_SANITIZE_STRING);
	$obj_frontend->country = filter_var($saasappoint_mb_customer_detail['country'], FILTER_SANITIZE_STRING);
	$obj_frontend->zip = filter_var($saasappoint_mb_customer_detail['zip'], FILTER_SANITIZE_STRING);

	$obj_frontend->payment_method = $saasappoint_mb_customer_detail['payment_method'];
	$obj_frontend->payment_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->transaction_id = $_SESSION['mb_transaction_id'];
	$obj_frontend->sub_total = $_SESSION['saasappoint_mb_cart_subtotal'];
	$obj_frontend->discount = $_SESSION['saasappoint_mb_cart_coupondiscount'];
	$obj_frontend->tax = $_SESSION['saasappoint_mb_cart_tax'];
	$obj_frontend->net_total = $_SESSION['saasappoint_mb_cart_nettotal'];
	$obj_frontend->fd_key = $_SESSION['saasappoint_mb_cart_freqdiscount_key'];
	$obj_frontend->fd_amount = $_SESSION['saasappoint_mb_cart_freqdiscount'];

	$obj_frontend->coupon_id = $_SESSION['saasappoint_mb_cart_couponid'];
	$obj_frontend->is_expired = "Y";
	$obj_frontend->used_on = date("Y-m-d", $currDateTime_withTZ);

	$obj_frontend->fd_id = $_SESSION['saasappoint_mb_cart_freqdiscount_id'];
		
	/** check customer type **/
	if($saasappoint_mb_customer_detail['customertype'] == "ec"){
		$customer_id = $_SESSION['mb_customer_id'];
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
						if($_SESSION['saasappoint_mb_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
					}
				}
			}
		}
	}else if($saasappoint_mb_customer_detail['customertype'] == "gc"){
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
						if($_SESSION['saasappoint_mb_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
					}
				}
			}
		}
	} else {
		$customer_id = $obj_frontend->add_customers();
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
						if($_SESSION['saasappoint_mb_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
					}
				}
			}
		}
	}
	/** Unset related sessions **/
	$_SESSION['saasappoint_mb_customer_detail'] = array();
	$_SESSION['saasappoint_mb_cart_items'] = array();
	$_SESSION['mb_customer_id'] = "";
	$_SESSION['saasappoint_mb_cart_category_id'] = "";
	$_SESSION['saasappoint_mb_cart_service_id'] = "";
	$_SESSION['saasappoint_mb_cart_datetime'] = "";
	$_SESSION['saasappoint_mb_cart_freqdiscount_label'] = "";
	$_SESSION['saasappoint_mb_cart_freqdiscount_key'] = "";
	$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = "";
	$_SESSION['saasappoint_mb_cart_subtotal'] = 0;
	$_SESSION['saasappoint_mb_cart_freqdiscount'] = 0;
	$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
	$_SESSION['saasappoint_mb_cart_couponid'] = "";
	$_SESSION['saasappoint_mb_cart_tax'] = 0;
	$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
	echo "BOOKED";
	die;
}