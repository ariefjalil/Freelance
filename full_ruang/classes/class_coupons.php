<?php 
class saasappoint_coupons{
	public $conn;
	public $id;
	public $business_id;
	public $coupon_code;
	public $coupon_type;
	public $coupon_value;
	public $coupon_expiry;
	public $status;
	public $saasappoint_coupons = 'saasappoint_coupons';
	
	/* Function to get all coupons */
	public function get_all_coupons(){
		$query = "select * from `".$this->saasappoint_coupons."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all coupons */
	public function count_all_coupons($search){
		if($search != ''){
			$query = "select count(`id`) from `".$this->saasappoint_coupons."` where (`coupon_code` like '%".$search."%' or `coupon_type` like '%".$search."%' or `coupon_value` like '%".$search."%' or `coupon_expiry` like '%".$search."%') and `business_id`='".$this->business_id."'";
		}else{
			$query = "select count(`id`) from `".$this->saasappoint_coupons."` where `business_id`='".$this->business_id."'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all coupons within limit */
	public function get_all_coupons_within_limit($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `coupon_code` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `coupon_type` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `coupon_value` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `coupon_expiry` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `status` '.$direction;
		}else{
			$order_by_qry = 'order by `id` '.$direction;
		}
		if($search != ''){
			$query = "select * from `".$this->saasappoint_coupons."` where (`coupon_code` like '%".$search."%' or `coupon_type` like '%".$search."%' or `coupon_value` like '%".$search."%' or `coupon_expiry` like '%".$search."%') and `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select * from `".$this->saasappoint_coupons."` where `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add coupon */
	public function add_coupon(){
		$query = "INSERT INTO `".$this->saasappoint_coupons."`(`id`, `business_id`, `coupon_code`, `coupon_type`, `coupon_value`, `coupon_expiry`, `status`) VALUES (NULL,'".$this->business_id."','".$this->coupon_code."','".$this->coupon_type."','".$this->coupon_value."','".$this->coupon_expiry."','".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	/* Function to change coupon status */
	public function change_coupon_status(){
		$query = "update `".$this->saasappoint_coupons."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update coupon */
	public function update_coupon(){
		$query = "update `".$this->saasappoint_coupons."` set `coupon_code`='".$this->coupon_code."', `coupon_type`='".$this->coupon_type."', `coupon_value`='".$this->coupon_value."', `coupon_expiry`='".$this->coupon_expiry."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete coupon */
	public function delete_coupon(){
		$query = "delete from `".$this->saasappoint_coupons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one coupon */
	public function readone_coupon(){
		$query = "select * from `".$this->saasappoint_coupons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
}
?>