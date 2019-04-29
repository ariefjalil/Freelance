<?php 
class saasappoint_services{
	public $conn;
	public $id;
	public $business_id;
	public $cat_id;
	public $title;
	public $image;
	public $description;
	public $status;
	public $duration;
	public $padding_before;
	public $padding_after;
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_bookings = 'saasappoint_bookings';
	
	/* Function to add service */
	public function add_service(){
		$query = "INSERT INTO `".$this->saasappoint_services."` (`id`, `business_id`, `cat_id`, `title`, `image`, `description`, `status`, `duration`, `padding_before`, `padding_after`) VALUES (NULL, '".$this->business_id."', '".$this->cat_id."', '".$this->title."', '".$this->image."', '".$this->description."', '".$this->status."', '".$this->duration."', '".$this->padding_before."', '".$this->padding_after."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update service */
	public function update_service(){
		$query = "update `".$this->saasappoint_services."` set `duration` = '".$this->duration."', `padding_before` = '".$this->padding_before."', `padding_after` = '".$this->padding_after."', `title` = '".$this->title."', `image` = '".$this->image."', `description` = '".$this->description."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change service status */
	public function change_service_status(){
		$query = "update `".$this->saasappoint_services."` set `status` = '".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all services title */
	public function get_all_services_title(){
		$query = "select `id`, `title` from `".$this->saasappoint_services."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one category */
	public function readone_service(){
		$query = "select * from `".$this->saasappoint_services."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to get all services according category id */
	public function get_all_services_according_cat_id(){
		$query = "select `id`, `title` from `".$this->saasappoint_services."` where `cat_id`='".$this->cat_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all services according category id */
	public function count_all_services_by_cat_id(){
		$query = "select count(`id`) from `".$this->saasappoint_services."` where `cat_id`='".$this->cat_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to count all services */
	public function count_all_services($search){
		if($search != ''){
			$query = "select count(`s`.`id`) from `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `s`.`cat_id` = `c`.`id` and (`s`.`title` like '%".$search."%' or `c`.`cat_name` like '%".$search."%') and `s`.`cat_id`='".$this->cat_id."' and `s`.`business_id`='".$this->business_id."'";
		}else{
			$query = "select count(`id`) from `".$this->saasappoint_services."` where `cat_id`='".$this->cat_id."' and `business_id`='".$this->business_id."'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all services within limit */
	public function get_all_services_within_limit($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `s`.`id` DESC';
		}else if($column == 1){
			$order_by_qry = 'order by `s`.`title` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `c`.`cat_name` '.$direction;
		}else{
			$order_by_qry = 'order by `s`.`id` '.$direction;
		}
		if($search != ''){
			$query = "select `s`.`id`, `s`.`title`, `c`.`cat_name`, `s`.`status` from `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `s`.`cat_id` = `c`.`id` and (`s`.`title` like '%".$search."%' or `c`.`cat_name` like '%".$search."%') and `s`.`cat_id`='".$this->cat_id."' and `s`.`business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `s`.`id`, `s`.`title`, `c`.`cat_name`, `s`.`status` from `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `s`.`cat_id` = `c`.`id` and `s`.`cat_id`='".$this->cat_id."' and `s`.`business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete services */
	public function delete_service(){
		$query = "delete from `".$this->saasappoint_services."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check appointments before delete services */
	public function check_appointments_before_delete_service(){
		$query = "select `id` from `".$this->saasappoint_bookings."` where `service_id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count;
	}
}
?>