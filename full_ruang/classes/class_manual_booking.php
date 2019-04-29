<?php 
class saasappoint_manual_booking{
	public $conn;
	public $business_id;
	public $category_id;
	public $service_id;
	public $addon_id;
	public $frequently_discount_id;
	public $coupon_id;
	public $customer_id;
	public $feedback_name;
	public $feedback_email;
	public $feedback_rating;
	public $feedback_review;
	public $feedback_review_datetime;
	public $email;
	public $password;
	public $order_id;
	public $booking_datetime;
	public $booking_end_datetime;
	public $order_date;
	public $addons;
	public $booking_status;
	public $lastmodified;
	public $firstname;
	public $lastname;
	public $phone;
	public $address;
	public $city;
	public $state;
	public $zip;
	public $country;
	public $payment_method;
	public $payment_date;
	public $transaction_id;
	public $sub_total;
	public $discount;
	public $tax;
	public $net_total;
	public $fd_key;
	public $fd_amount;
	public $is_expired;
	public $used_on;
	public $fd_id;
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_addons = 'saasappoint_addons';
	public $saasappoint_frequently_discount = 'saasappoint_frequently_discount';
	public $saasappoint_feedback = 'saasappoint_feedback';
	public $saasappoint_customers = 'saasappoint_customers';
	public $saasappoint_coupons = 'saasappoint_coupons';
	public $saasappoint_used_coupons_by_customer = 'saasappoint_used_coupons_by_customer';
	public $saasappoint_used_fd_by_customer = 'saasappoint_used_fd_by_customer';
	public $saasappoint_bookings = 'saasappoint_bookings';
	public $saasappoint_customer_orderinfo = 'saasappoint_customer_orderinfo';
	public $saasappoint_payments = 'saasappoint_payments';
		
	/* Function to add feedback */
	public function add_feedback(){
		$query = "INSERT INTO `".$this->saasappoint_feedback."` (`id`, `business_id`, `name`, `email`, `rating`, `review`, `review_datetime`, `status`) VALUES (NULL, '".$this->business_id."', '".$this->feedback_name."', '".$this->feedback_email."', '".$this->feedback_rating."', '".$this->feedback_review."', '".$this->feedback_review_datetime."', 'Y')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all feedbacks */
	public function get_all_feedbacks(){
		$query = "select * from `".$this->saasappoint_feedback."` where `business_id`='".$this->business_id."' and `status` = 'Y' ORDER BY `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to apply coupon code */
	public function apply_coupon(){
		$query = "select * from `".$this->saasappoint_coupons."` where `id`='".$this->coupon_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to get all frequently discount */
	public function get_all_frequently_discount(){
		$query = "select * from `".$this->saasappoint_frequently_discount."` where `business_id`='".$this->business_id."' and `fd_status` = 'Y' ORDER BY `id` ASC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to readone frequently discount */
	public function readone_frequently_discount(){
		$query = "select * from `".$this->saasappoint_frequently_discount."` where `business_id`='".$this->business_id."' and `id` = '".$this->frequently_discount_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
		
	/* Function to get all categories */
	public function get_all_categories(){
		$query = "select `c`.`id`, `c`.`cat_name` 
		from `".$this->saasappoint_categories."` as `c`, 
		`".$this->saasappoint_services."` as `s`, 
		`".$this->saasappoint_addons."` as `a` 
		where 
		`c`.`business_id`='".$this->business_id."' 
		and `c`.`status` = 'Y'
		and `s`.`status` = 'Y' 
		and `a`.`status` = 'Y' 
		and `s`.`cat_id` = `c`.`id` 
		and `a`.`service_id` = `s`.`id` group by `c`.`id`, `c`.`cat_name` ORDER BY `c`.`id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get services by category id */
	public function get_services_by_cat_id(){
		$query = "select `s`.* 
		from 
		`".$this->saasappoint_services."` as `s`, 
		`".$this->saasappoint_addons."` as `a` 
		where 
		`s`.`business_id`='".$this->business_id."' 
		and `s`.`cat_id`='".$this->category_id."' 
		and `s`.`status` = 'Y' 
		and `a`.`status` = 'Y' 
		and `a`.`service_id` = `s`.`id` 
		group by `s`.`id`, `s`.`business_id`, `s`.`cat_id`, `s`.`title`, `s`.`image`, `s`.`description`, `s`.`status` 
		ORDER BY `s`.`id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get multiple qty addons by service id */
	public function get_multiple_qty_addons_by_service_id(){
		$query = "select * 
		from 
		`".$this->saasappoint_addons."` 
		where 
		`business_id`='".$this->business_id."' 
		and `service_id`='".$this->service_id."' 
		and `multiple_qty` = 'Y' 
		and `status` = 'Y' 
		ORDER BY `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get single qty addons by service id */
	public function get_single_qty_addons_by_service_id(){
		$query = "select * 
		from 
		`".$this->saasappoint_addons."` 
		where 
		`business_id`='".$this->business_id."' 
		and `service_id`='".$this->service_id."' 
		and `multiple_qty` = 'N' 
		and `status` = 'Y' 
		ORDER BY `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get addon rate by addon id */
	public function get_addon_rate(){
		$query = "select `rate` from `".$this->saasappoint_addons."` where `id`='".$this->addon_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['rate'];
	}

	/* Function to read one addon name */
	public function readone_addon_name(){
		$query = "select `title` from `".$this->saasappoint_addons."` where `id`='".$this->addon_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}

	/* Function to read one service name */
	public function readone_service_name(){
		$query = "select `title` from `".$this->saasappoint_services."` where `id`='".$this->service_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}

	/* Function to read one category name */
	public function readone_category_name(){
		$query = "select `cat_name` from `".$this->saasappoint_categories."` where `id`='".$this->category_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['cat_name'];
	}
	
	/* Function to check existing cart item */
	public function saasappoint_check_existing_cart_item($arr, $id){
		foreach($arr as $key => $val){
			if ( $val["id"] === $id ){
				return $key;
			}
		}
		return false;
	}
	
	/* Function to get new order id for appointment */
	public function get_order_id(){
		$query = "select order_id from `".$this->saasappoint_bookings."` order by `order_id` DESC limit 1";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_assoc($result);
			return ($value['order_id']+1);
		}else{
			return 100;
		}
	}
	
	/* Function to readone customer details */
	public function readone_customer(){
		$query = "select * from `".$this->saasappoint_customers."` where `id`='".$this->customer_id."'";
		$result=mysqli_query($this->conn,$query);		
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to get available coupons for customer */
	public function get_available_coupons(){
		$query = "select * from `".$this->saasappoint_coupons."` where `business_id`='".$this->business_id."' and `status`='Y' and `coupon_expiry` >= CURDATE()";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get available coupons for customer */
	public function check_available_coupon_of_existing_customer(){
		$query = "select `id` from `".$this->saasappoint_used_coupons_by_customer."` where `customer_id`='".$this->customer_id."' and `business_id`='".$this->business_id."' and `coupon_id`='".$this->coupon_id."' and `is_expired`='Y'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return "used";
		}else{
			return "not used";
		}
	}
	
	/** Function to add appointment detail in booking table **/
	public function add_bookings(){
		$query = "INSERT INTO `".$this->saasappoint_bookings."` (`id`, `business_id`, `order_id`, `customer_id`, `booking_datetime`, `booking_end_datetime`, `order_date`, `cat_id`, `service_id`, `addons`, `booking_status`, `reschedule_reason`, `reject_reason`, `cancel_reason`, `reminder_status`, `read_status`, `lastmodified`) VALUES (NULL, '".$this->business_id."', '".$this->order_id."', '".$this->customer_id."', '".$this->booking_datetime."', '".$this->booking_end_datetime."', '".$this->order_date."', '".$this->category_id."', '".$this->service_id."', '".$this->addons."', '".$this->booking_status."', '', '', '', 'N', 'U', '".$this->lastmodified."')";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** Function to add appointment detail in customer orderinfo table **/
	public function add_customer_orderinfo(){
		$query = "INSERT INTO `".$this->saasappoint_customer_orderinfo."` (`id`, `order_id`, `c_firstname`, `c_lastname`, `c_email`, `c_phone`, `c_address`, `c_city`, `c_state`, `c_country`, `c_zip`) VALUES (NULL, '".$this->order_id."', '".$this->firstname."', '".$this->lastname."', '".$this->email."', '".$this->phone."', '".$this->address."', '".$this->city."', '".$this->state."', '".$this->country."', '".$this->zip."')";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** Function to add new customer detail in customer table **/
	public function add_customers(){
		$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$refferral_code = "";
		for ($i = 0; $i < 15; $i++) {
			$refferral_code .= $chars[mt_rand(0, strlen($chars)-1)];
		}
		$query = "INSERT INTO `".$this->saasappoint_customers."` (`id`, `email`, `password`, `firstname`, `lastname`, `phone`, `address`, `city`, `state`, `zip`, `country`, `image`, `status`, `refferral_code`) VALUES (NULL, '".$this->email."', '".$this->password."', '".$this->firstname."', '".$this->lastname."', '".$this->phone."', '".$this->address."', '".$this->city."', '".$this->state."', '".$this->zip."', '".$this->country."', '', 'Y', '".$refferral_code."')";
		$result = mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);
		return $value;
	}
	
	/** Function to add appointment detail in payment table **/
	public function add_payments(){
		$query = "INSERT INTO `".$this->saasappoint_payments."` (`id`, `order_id`, `payment_method`, `payment_date`, `transaction_id`, `sub_total`, `discount`, `tax`, `net_total`, `fd_key`, `fd_amount`, `lastmodified`, `refer_discount`, `refer_discount_id`) VALUES (NULL, '".$this->order_id."', '".$this->payment_method."', '".$this->payment_date."', '".$this->transaction_id."', '".$this->sub_total."', '".$this->discount."', '".$this->tax."', '".$this->net_total."', '".$this->fd_key."', '".$this->fd_amount."', '".$this->lastmodified."', '0', '0')";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** Function to add applied coupon detail in used coupons by customer table **/
	public function add_used_coupons_by_customer(){
		$query = "INSERT INTO `".$this->saasappoint_used_coupons_by_customer."` (`id`, `business_id`, `customer_id`, `coupon_id`, `is_expired`, `used_on`) VALUES (NULL, '".$this->business_id."', '".$this->customer_id."', '".$this->coupon_id."', '".$this->is_expired."', '".$this->used_on."')";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** Function to add applied fd detail in used fd by customer table **/
	public function add_used_fd_by_customer(){
		$query = "INSERT INTO `".$this->saasappoint_used_fd_by_customer."` (`id`, `business_id`, `order_id`, `customer_id`, `fd_id`, `is_expired`, `used_on`) VALUES (NULL, '".$this->business_id."', '".$this->order_id."', '".$this->customer_id."', '".$this->fd_id."', '".$this->is_expired."', '".$this->used_on."')";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
} 
?>