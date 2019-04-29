<?php 
class saasappoint_categories{
	public $conn;
	public $id;
	public $business_id;
	public $cat_name;
	public $status;
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_bookings = 'saasappoint_bookings';
	
	/* Function to add categories */
	public function add_category(){
		$query = "INSERT INTO `".$this->saasappoint_categories."` (`id`, `business_id`, `cat_name`, `status`) VALUES (NULL, '".$this->business_id."', '".$this->cat_name."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all categories */
	public function get_all_categories(){
		$query = "select * from `".$this->saasappoint_categories."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all categories name */
	public function get_all_categories_name(){
		$query = "select `id`, `cat_name` from `".$this->saasappoint_categories."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change category status */
	public function change_category_status(){
		$query = "update `".$this->saasappoint_categories."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update category */
	public function update_category(){
		$query = "update `".$this->saasappoint_categories."` set `cat_name`='".$this->cat_name."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete category */
	public function delete_category(){
		$query = "delete from `".$this->saasappoint_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check appointments before delete category */
	public function check_appointments_before_delete_category(){
		$query = "select `id` from `".$this->saasappoint_bookings."` where `cat_id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count;
	}
	
	/* Function to get one categories name */
	public function readone_category_name(){
		$query = "select `cat_name` from `".$this->saasappoint_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value['cat_name'];
	}
	
	/* Function to read one category */
	public function readone_category(){
		$query = "select * from `".$this->saasappoint_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to count all categories */
	public function count_all_categories($search){
		if($search != ''){
			$query = "select count(`id`) from `".$this->saasappoint_categories."` where (`cat_name` like '%".$search."%') and `business_id`='".$this->business_id."'";
		}else{
			$query = "select count(`id`) from `".$this->saasappoint_categories."` where `business_id`='".$this->business_id."'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all categories within limit */
	public function get_all_categories_within_limit($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `id` DESC';
		}else if($column == 1){
			$order_by_qry = 'order by `cat_name` '.$direction;
		}else{
			$order_by_qry = 'order by `id` '.$direction;
		}
		if($search != ''){
			$query = "select * from `".$this->saasappoint_categories."` where (`cat_name` like '%".$search."%') and `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select * from `".$this->saasappoint_categories."` where `business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>