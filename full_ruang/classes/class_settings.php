<?php 
class saasappoint_settings{
	public $conn;
	public $id;
	public $business_id;
	public $option_name;
	public $option_value;
	public $saasappoint_settings = 'saasappoint_settings';
	public $saasappoint_superadmin_settings = 'saasappoint_superadmin_settings';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_schedule = 'saasappoint_schedule';
	public $saasappoint_addons = 'saasappoint_addons';
	public $saasappoint_subscriptions = 'saasappoint_subscriptions';
	
	/* Function to add default settings while register as admin */
	public function add_default_settings($siteurl, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry){
		$query = "INSERT INTO `".$this->saasappoint_settings."` (`id`, `business_id`, `option_name`, `option_value`) VALUES
				(NULL, '".$this->business_id."', 'saasappoint_currency', 'USD'),
				(NULL, '".$this->business_id."', 'saasappoint_currency_symbol', '$'),
				(NULL, '".$this->business_id."', 'saasappoint_date_format', 'd-m-Y'),
				(NULL, '".$this->business_id."', 'saasappoint_timeslot_interval', '30'),
				(NULL, '".$this->business_id."', 'saasappoint_time_format', '12'),
				(NULL, '".$this->business_id."', 'saasappoint_maximum_advance_booking_time', '3'),
				(NULL, '".$this->business_id."', 'saasappoint_company_name', '".$companyname."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_email', '".$companyemail."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_phone', '".$companyphone."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_address', '".$companyaddress."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_city', '".$companycity."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_state', '".$companystate."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_zip', '".$companyzip."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_country', '".$companycountry."'),
				(NULL, '".$this->business_id."', 'saasappoint_company_logo', ''),
				(NULL, '".$this->business_id."', 'saasappoint_thankyou_page_url', '".$siteurl."thankyou.php'),
				(NULL, '".$this->business_id."', 'saasappoint_auto_confirm_appointment', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_tax_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_tax_type', 'percentage'),
				(NULL, '".$this->business_id."', 'saasappoint_tax_value', ''),
				(NULL, '".$this->business_id."', 'saasappoint_minimum_advance_booking_time', '60'),
				(NULL, '".$this->business_id."', 'saasappoint_cancellation_buffer_time', '60'),
				(NULL, '".$this->business_id."', 'saasappoint_reschedule_buffer_time', '60'),
				(NULL, '".$this->business_id."', 'saasappoint_show_frontend_rightside_feedback_list', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_show_frontend_rightside_feedback_form', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_show_guest_user_checkout', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_show_existing_new_user_checkout', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_hide_already_booked_slots_from_frontend_calendar', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_terms_and_condition_link', '".$siteurl."'),
				(NULL, '".$this->business_id."', 'saasappoint_admin_email_notification_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_customer_email_notification_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_email_sender_name', '".$companyname."'),
				(NULL, '".$this->business_id."', 'saasappoint_email_sender_email', '".$companyemail."'),
				(NULL, '".$this->business_id."', 'saasappoint_admin_sms_notification_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_customer_sms_notification_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_seo_ga_code', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_meta_tag', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_meta_description', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_og_meta_tag', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_og_tag_type', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_og_tag_url', ''),
				(NULL, '".$this->business_id."', 'saasappoint_seo_og_tag_image', ''),
				(NULL, '".$this->business_id."', 'saasappoint_sms_credit', '0'),
				(NULL, '".$this->business_id."', 'saasappoint_paypal_payment_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_paypal_guest_payment', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_paypal_api_username', ''),
				(NULL, '".$this->business_id."', 'saasappoint_paypal_api_password', ''),
				(NULL, '".$this->business_id."', 'saasappoint_paypal_signature', ''),
				(NULL, '".$this->business_id."', 'saasappoint_stripe_payment_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_stripe_secret_key', ''),
				(NULL, '".$this->business_id."', 'saasappoint_stripe_publishable_key', ''),
				(NULL, '".$this->business_id."', 'saasappoint_authorizenet_payment_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_authorizenet_api_login_id', ''),
				(NULL, '".$this->business_id."', 'saasappoint_authorizenet_transaction_key', ''),
				(NULL, '".$this->business_id."', 'saasappoint_twocheckout_payment_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_twocheckout_publishable_key', ''),
				(NULL, '".$this->business_id."', 'saasappoint_twocheckout_private_key', ''),
				(NULL, '".$this->business_id."', 'saasappoint_twocheckout_seller_id', ''),
				
				(NULL, '".$this->business_id."', 'saasappoint_timezone', '".date_default_timezone_get()."'),
				(NULL, '".$this->business_id."', 'saasappoint_location_selector', '".$companyzip."'),
				(NULL, '".$this->business_id."', 'saasappoint_location_selector_container', ''),
				(NULL, '".$this->business_id."', 'saasappoint_refund_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_refund_type', 'percentage'),
				(NULL, '".$this->business_id."', 'saasappoint_refund_value', ''),
				(NULL, '".$this->business_id."', 'saasappoint_refund_request_buffer_time', '120'),
				(NULL, '".$this->business_id."', 'saasappoint_refund_summary', ''),
				(NULL, '".$this->business_id."', 'saasappoint_referral_discount_type', 'percentage'),
				(NULL, '".$this->business_id."', 'saasappoint_referral_discount_value', '5'),
				(NULL, '".$this->business_id."', 'saasappoint_reminder_buffer_time', '60'),
				
				(NULL, '".$this->business_id."', 'saasappoint_location_selector_status', 'Y'),
				
				(NULL, '".$this->business_id."', 'saasappoint_maximum_endtimeslot_limit', '60'),
				(NULL, '".$this->business_id."', 'saasappoint_endtimeslot_selection_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_referral_discount_status', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_pay_at_venue_status', 'Y'),
				(NULL, '".$this->business_id."', 'saasappoint_welcome_message_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_welcome_message_container', ''),
				(NULL, '".$this->business_id."', 'saasappoint_bookingform_bg', 'default'),
				(NULL, '".$this->business_id."', 'saasappoint_bookingform_bg_type', 'color'),
				(NULL, '".$this->business_id."', 'saasappoint_bookingform_bg_color', '#000000'),
				(NULL, '".$this->business_id."', 'saasappoint_bookingform_bg_image', ''),
				(NULL, '".$this->business_id."', 'saasappoint_minimum_booking_amount', '10'),
				
				(NULL, '".$this->business_id."', 'saasappoint_single_category_autotrigger_status', 'N'),
				(NULL, '".$this->business_id."', 'saasappoint_single_service_autotrigger_status', 'N');";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get option value from settings table */
	public function get_option($option_name){
		$query = "select `option_value` from `".$this->saasappoint_settings."` where `option_name`='".$option_name."' and `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['option_value'];
	}
	
	/* Function to get super admin option value from settings table */
	public function get_superadmin_option($option_name){
		$query = "select `option_value` from `".$this->saasappoint_superadmin_settings."` where `option_name`='".$option_name."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['option_value'];
	}
	
	/* Function to update option value in settings table */
	public function update_option($option_name,$option_value){
		$query = "select `option_value` from `".$this->saasappoint_settings."` where `option_name`='".$option_name."' and `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$option_value = filter_var($option_value, FILTER_SANITIZE_STRING);
			$query = "update `".$this->saasappoint_settings."` set `option_value`='".$option_value."' where `option_name`='".$option_name."' and `business_id`='".$this->business_id."'";
		}else{
			$option_value = filter_var($option_value, FILTER_SANITIZE_STRING);
			$query = "INSERT INTO `".$this->saasappoint_settings."` (`id`, `business_id`, `option_name`, `option_value`) VALUES (NULL, '".$this->business_id."', '".$option_name."', '".$option_value."');";
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update option value in superadmin settings table */
	public function update_superadmin_option($option_name,$option_value){
		$query = "select `option_value` from `".$this->saasappoint_superadmin_settings."` where `option_name`='".$option_name."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$option_value = filter_var($option_value, FILTER_SANITIZE_STRING);
			$query = "update `".$this->saasappoint_superadmin_settings."` set `option_value`='".$option_value."' where `option_name`='".$option_name."'";
		}else{
			$option_value = filter_var($option_value, FILTER_SANITIZE_STRING);
			$query = "INSERT INTO `".$this->saasappoint_superadmin_settings."` (`id`, `option_name`, `option_value`) VALUES (NULL, '".$option_name."', '".$option_value."');";
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check subscription expiry of business */
	public function check_subscription_expiry(){
		$query = "select `expired_on` from `".$this->saasappoint_subscriptions."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		if($count==0){
			return "business_not_exist";
		}else{
			$val = mysqli_fetch_array($result);
			return $val["expired_on"];
		}
	}
	
	/* Function to check for setup instruction modal */
	public function check_for_setup_instruction_modal(){
		$query = "select * from `".$this->saasappoint_categories."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		if($count==0){
			return "Y";
		}else{
			$query = "select * from `".$this->saasappoint_services."` where `business_id`='".$this->business_id."'";
			$result=mysqli_query($this->conn,$query);
			$count=mysqli_num_rows($result);
			if($count==0){
				return "Y";
			}else{
				$query = "select * from `".$this->saasappoint_addons."` where `business_id`='".$this->business_id."'";
				$result=mysqli_query($this->conn,$query);
				$count=mysqli_num_rows($result);
				if($count==0){
					return "Y";
				}else{
					$query = "select * from `".$this->saasappoint_schedule."` where `business_id`='".$this->business_id."'";
					$result=mysqli_query($this->conn,$query);
					$count=mysqli_num_rows($result);
					if($count==0){
						return "Y";
					}else{
						return "N";
					}
				}
			}
		}
	}
	
	/** Convert Base64 string to an image file **/
	public function saasappoint_base64_to_jpeg($base64_string, $output_filepath, $output_filename) {
		$data = explode( ',', $base64_string );
		$image_parts = explode(";base64,", $data[0]);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = explode(";", $image_type_aux[1]);
		$output_filetype = $image_type[0];
		$output_filename = $output_filename.".".$output_filetype;
		$output_file = $output_filepath.$output_filename;
		$ifp = fopen( $output_file, 'wb' );
		fwrite( $ifp, base64_decode( $data[1] ) );
		fclose( $ifp );
		return $output_filename; 
	}
	
	/** Get time according to saved timezone **/
	public function get_current_time_according_selected_timezone($remote_tz, $origin_tz = null) {
		if($origin_tz === null) {
			if(!is_string($origin_tz = date_default_timezone_get())) {
				return false; /* A UTC timestamp was returned -- bail out! */
			}
		}
		if(isset($origin_tz) && $origin_tz!=''){
			$origin_dtz = new DateTimeZone($origin_tz);
			$remote_dtz = new DateTimeZone($remote_tz);
			$origin_dt = new DateTime("now", $origin_dtz);
			$remote_dt = new DateTime("now", $remote_dtz);
			$offset = $origin_dtz->getOffset($remote_dt) - $remote_dtz->getOffset($origin_dt);
			$timezonediff = $offset/3600;  
		}else{
			$timezonediff =0;
		}
		if(is_numeric(strpos($timezonediff,'-'))){
			$timediffmis = str_replace('-','',$timezonediff)*60;
			$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
		}else{
			$timediffmis = str_replace('+','',$timezonediff)*60;
			$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
		}
		return $currDateTime_withTZ;
	}
}
?>