<?php 
class saasappoint_feedback{
	public $conn;
	public $id;
	public $business_id;
	public $name;
	public $email;
	public $rating;
	public $review;
	public $review_datetime;
	public $status;
	public $saasappoint_feedback = 'saasappoint_feedback';
	
	/* Function to count all coupons */
	public function count_all_feedbacks($search){
		if($search != ''){
			$query = "select count(`id`) from `".$this->saasappoint_feedback."` where (`name` like '%".$search."%' or `email` like '%".$search."%' or `rating` like '%".$search."%' or `review` like '%".$search."%' or `review_datetime` like '%".$search."%') and `business_id`='".$this->business_id."'";
		}else{
			$query = "select count(`id`) from `".$this->saasappoint_feedback."` where `business_id`='".$this->business_id."'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all feedbacks within limit */
	public function get_all_feedbacks_within_limit($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `name` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `email` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `rating` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `review` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `review_datetime` '.$direction;
		}else if($column == 5){
			$order_by_qry = 'order by `status` '.$direction;
		}else{
			$order_by_qry = 'order by `id` '.$direction;
		}
		if($search != ''){
			$query = "select * from `".$this->saasappoint_feedback."` where (`name` like '%".$search."%' or `email` like '%".$search."%' or `rating` like '%".$search."%' or `review` like '%".$search."%' or `review_datetime` like '%".$search."%') and `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select * from `".$this->saasappoint_feedback."` where `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change feedback status */
	public function change_feedback_status(){
		$query = "update `".$this->saasappoint_feedback."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>