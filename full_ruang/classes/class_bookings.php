<?php 
class saasappoint_bookings{
	public $conn;
	public $id;
	public $business_id;
	public $order_id;
	public $customer_id;
	public $booking_datetime;
	public $booking_end_datetime;
	public $order_date;
	public $cat_id;
	public $service_id;
	public $addons;
	public $booking_status;
	public $reschedule_reason;
	public $reject_reason;
	public $cancel_reason;
	public $reminder_status;
	public $read_status;
	public $lastmodified;
	public $saasappoint_bookings = 'saasappoint_bookings';
	public $saasappoint_customers = 'saasappoint_customers';
	public $saasappoint_customer_orderinfo = 'saasappoint_customer_orderinfo';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_payments = 'saasappoint_payments';
	public $saasappoint_appointment_feedback = 'saasappoint_appointment_feedback';
	public $saasappoint_customer_referrals = 'saasappoint_customer_referrals';
	
	/* Function to delete appointments detail */
	public function delete_appointment(){		
		$result = mysqli_query($this->conn,"delete from `".$this->saasappoint_bookings."` where `order_id`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->saasappoint_customer_orderinfo."` where `order_id`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->saasappoint_payments."` where `order_id`='".$this->order_id."'");
		return $result;
	}
	
	/* Function to get count of latest unread appointments detail */
	public function get_count_of_latest_unread_appointments(){
		$query = "select count(`order_id`) as `total_unread_appointments` from `".$this->saasappoint_bookings."` where `read_status` = 'U' and `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_unread_appointments'];
	}
	
	/* Function to get all latest unread appointments detail */
	public function get_all_latest_unread_appointments(){
				
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`read_status` = 'U' and `b`.`business_id`='".$this->business_id."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all appointments detail */
	public function get_all_appointments(){
				
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`business_id`='".$this->business_id."' ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get all customer appointments detail */
	public function get_all_customer_appointments(){
				
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`business_id`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`business_id` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`customer_id`='".$this->customer_id."' ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to appointment detail from order id */
	public function get_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `b`.`addons`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`';
		
		$from_qry = "`".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`, `".$this->saasappoint_payments."` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."' and `b`.`business_id`='".$this->business_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get reschedule appointment tab detail */
	public function get_reschedule_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `b`.`booking_datetime`, `b`.`reschedule_reason`, `b`.`business_id`, `b`.`booking_end_datetime`, `b`.`service_id`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`";
		
		$where_qry = "`b`.`order_id`='".$this->order_id."'";
		
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Function to get reject appointment tab detail */
	public function get_reject_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `b`.`reject_reason`, `b`.`business_id`, `b`.`booking_datetime`, `p`.`net_total`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_payments."` as `p`";
		
		$where_qry = "`b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get appointment detail tab detail */
	public function get_appointment_detail_tab(){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `b`.`business_id`';
		
		$from_qry = "`".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get payment detail tab detail */
	public function get_payment_detail_tab(){
		$selected_fields = '`b`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`, `b`.`business_id`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_payments."` as `p`";
		
		$where_qry = "`b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get customer detail tab detail */
	public function get_customer_detail_tab(){
		$selected_fields = '`b`.`order_id`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `b`.`business_id`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`order_id` = `o`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reschedule appointment detail */
	public function reschedule_appointment(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reschedule_reason` = '".$this->reschedule_reason."', `booking_datetime` = '".$this->booking_datetime."', `booking_end_datetime` = '".$this->booking_end_datetime."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->saasappoint_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reject appointment detail */
	public function reject_appointment(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reject_reason` = '".$this->reject_reason."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->saasappoint_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to confirm appointment detail */
	public function change_appointment_status(){
		if($this->booking_status == 'completed'){
			mysqli_query($this->conn, "update `".$this->saasappoint_customer_referrals."` set `completed`='Y' where `order_id`='".$this->order_id."'");
		}
		$update_fields = "`booking_status` = '".$this->booking_status."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."' and `business_id`='".$this->business_id."'";
		
		$query = "update `".$this->saasappoint_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to Mark appointment as read */
	public function mark_appointment_as_read(){
		$update_fields = "`read_status` = 'R'";
		
		$where_qry = "`order_id`='".$this->order_id."' and `business_id`='".$this->business_id."'";
		
		$query = "update `".$this->saasappoint_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get appointment status detail */
	public function get_appointment_status(){
		$where_qry = "`order_id`='".$this->order_id."' and `business_id`='".$this->business_id."'";
		$query = "select `booking_status` from `".$this->saasappoint_bookings."` where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['booking_status'];
	}
	
	
	/* Function to get appointment status detail */
	public function get_appointment_status_and_datetime(){
		$where_qry = "`order_id`='".$this->order_id."'";
		$query = "select `booking_status`, `booking_datetime`, `business_id` from `".$this->saasappoint_bookings."` where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to get all customers appointment details for export */
	public function get_all_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`, `".$this->saasappoint_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only registered customers appointment details for export */
	public function all_registered_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`, `".$this->saasappoint_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` <> '0' and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` <> '0' and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only guest customers appointment details for export */
	public function all_guest_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`, `".$this->saasappoint_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** function to get appointment feedback ratings **/
	public function get_appointment_rating($order_id){
		$query = "select * from `".$this->saasappoint_appointment_feedback."` where `order_id` = '".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** function to get appointment feedback ratings **/
	public function add_appointment_feedback($order_id, $rating, $review){
		$query = "INSERT INTO `".$this->saasappoint_appointment_feedback."` (`id`, `order_id`, `rating`, `review`, `review_datetime`, `status`) VALUES (NULL, '".$order_id."', '".$rating."', '".$review."', '".date("Y-m-d H:i:s")."', 'Y')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}  
?>