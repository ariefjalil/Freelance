<?php 
class saasappoint_refund_request{
	public $conn;
	public $id;
	public $customer_id;
	public $business_id;
	public $order_id;
	public $amount;
	public $requested_on;
	public $status;
	public $read_status;
	public $saasappoint_refund_request = 'saasappoint_refund_request';
	public $saasappoint_customer_orderinfo = 'saasappoint_customer_orderinfo';
	public $saasappoint_bookings = 'saasappoint_bookings';
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_categories = 'saasappoint_categories';
	
	/* Function to get appointment detail to diplay in list */
	public function get_appointment_detail_by_order_id($order_id){
		$query = "select `co`.`c_firstname`, `co`.`c_lastname`, `co`.`c_email`, `co`.`c_phone`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_status` from `".$this->saasappoint_refund_request."` as `rr`, `".$this->saasappoint_customer_orderinfo."` as `co`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `rr`.`order_id` = `b`.`order_id` and `rr`.`order_id` = `co`.`order_id` and `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `rr`.`order_id`='".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to read all refund request */
	public function readall_refund_request_detail(){
		$query = "select * from `".$this->saasappoint_refund_request."` where `business_id`='".$this->business_id."' order by `requested_on` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all refund request */
	public function readall_refund_request_detail_for_customer(){
		$query = "select `rr`.* from `".$this->saasappoint_refund_request."` as `rr`, `".$this->saasappoint_bookings."` as `b` where `rr`.`order_id` = `b`.`order_id` and `b`.`customer_id`='".$this->customer_id."' order by `requested_on` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add refund request */
	public function add_refund_request(){
		$query = "insert into `".$this->saasappoint_refund_request."` (`id`, `business_id`, `order_id`, `amount`, `requested_on`, `status`, `read_status`) VALUES (NULL, '".$this->business_id."', '".$this->order_id."', '".$this->amount."', '".$this->requested_on."', '".$this->status."', '".$this->read_status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all unread refund request */
	public function readall_unread_refund_requests(){
		$query = "select * from `".$this->saasappoint_refund_request."` where `read_status`='U' and `business_id`='".$this->business_id."' order by `requested_on` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}	
	
	/* Function to count all unread refund request */
	public function get_count_of_latest_unread_refund_requests(){
		$query = "select count(`id`) from `".$this->saasappoint_refund_request."` where `read_status`='U' and `business_id`='".$this->business_id."' order by `requested_on` DESC";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to change status of business type */
	public function change_refund_request_status(){
		$query = "update `".$this->saasappoint_refund_request."` set `status` = '".$this->status."', `read_status` = '".$this->read_status."' where `id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change status of business type */
	public function mark_as_read_refund_request_status(){
		$query = "update `".$this->saasappoint_refund_request."` set `read_status` = '".$this->read_status."' where `id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>