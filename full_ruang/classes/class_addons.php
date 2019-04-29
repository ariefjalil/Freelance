<?php 
class saasappoint_addons{
	public $conn;
	public $id;
	public $business_id;
	public $service_id;
	public $title;
	public $rate;
	public $image;
	public $multiple_qty;
	public $status;
	public $description;
	public $saasappoint_addons = 'saasappoint_addons';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_bookings = 'saasappoint_bookings';
	
	/* Function to get addon name */
	public function get_addon_name(){
		$query = "select `title` from `".$this->saasappoint_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}
	
	/* Function to get addon detail */
	public function readone_addon(){
		$query = "select * from `".$this->saasappoint_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to get all addons for export */
	public function export_all_addons(){
		$query = "SELECT `a`.`id`, `c`.`cat_name`, `s`.`title` as `service_title`, `a`.`title`, `a`.`rate`, `a`.`multiple_qty`, `a`.`status` FROM `".$this->saasappoint_addons."` as `a`, `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s` WHERE `a`.`service_id` = `s`.`id` AND `s`.`cat_id` = `c`.`id` AND `a`.`id`='".$this->id."' AND `a`.`business_id`='".$this->business_id."' GROUP BY `a`.`id`, `c`.`cat_name`, `s`.`title`, `a`.`title`, `a`.`rate`, `a`.`multiple_qty`, `a`.`status`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to get all addons title */
	public function get_all_addons_title(){
		$query = "SELECT `id`, `title` FROM `".$this->saasappoint_addons."` WHERE `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all addons according service id selection */
	public function get_all_addons_according_service_id(){
		$query = "SELECT `id`, `title` FROM `".$this->saasappoint_addons."` WHERE `service_id`='".$this->service_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all addons according service id */
	public function count_all_addons_by_service_id(){
		$query = "select count(`id`) from `".$this->saasappoint_addons."` where `service_id`='".$this->service_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to count all addons */
	public function count_all_addons($search){
		if($search != ''){
			$query = "select count(`a`.`id`) from `".$this->saasappoint_addons."` as `a`,`".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `a`.`service_id` = `s`.`id` and `s`.`cat_id` = `c`.`id` and (`a`.`title` like '%".$search."%' or `a`.`rate` like '%".$search."%' or `s`.`title` like '%".$search."%' or `c`.`cat_name` like '%".$search."%') and `a`.`service_id`='".$this->service_id."' and `s`.`business_id`='".$this->business_id."'";
		}else{
			$query = "select count(`id`) from `".$this->saasappoint_addons."` where `service_id`='".$this->service_id."' and `business_id`='".$this->business_id."'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all addons within limit */
	public function get_all_addons_within_limit($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `a`.`id` DESC';
		}else if($column == 1){
			$order_by_qry = 'order by `a`.`title` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `c`.`cat_name` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `s`.`title` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `a`.`rate` '.$direction;
		}else{
			$order_by_qry = 'order by `a`.`id` '.$direction;
		}
		if($search != ''){
			$query = "select `a`.`id`, `a`.`title`, `c`.`cat_name`, `s`.`title` as `service_title`, `a`.`rate`, `a`.`multiple_qty`, `a`.`status` from `".$this->saasappoint_addons."` as `a`,`".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `a`.`service_id` = `s`.`id` and `s`.`cat_id` = `c`.`id` and (`a`.`title` like '%".$search."%' or `a`.`rate` like '%".$search."%' or `s`.`title` like '%".$search."%' or `c`.`cat_name` like '%".$search."%') and `a`.`service_id`='".$this->service_id."' and `a`.`business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `a`.`id`, `a`.`title`, `c`.`cat_name`, `s`.`title` as `service_title`, `a`.`rate`, `a`.`multiple_qty`, `a`.`status` from `".$this->saasappoint_addons."` as `a`,`".$this->saasappoint_services."` as `s`, `".$this->saasappoint_categories."` as `c` where `a`.`service_id` = `s`.`id` and `s`.`cat_id` = `c`.`id` and `a`.`service_id`='".$this->service_id."' and `a`.`business_id`='".$this->business_id."' ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change addons status */
	public function change_addon_status(){
		$query = "update `".$this->saasappoint_addons."` set `status` = '".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change addons multiple qty status */
	public function change_addon_multiple_qty_status(){
		$query = "update `".$this->saasappoint_addons."` set `multiple_qty` = '".$this->multiple_qty."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update addons */
	public function update_addon(){
		$query = "UPDATE `".$this->saasappoint_addons."` SET `title` = '".$this->title."', `rate` = '".$this->rate."', `description` = '".$this->description."', `image` = '".$this->image."' WHERE `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add addons */
	public function add_addon(){
		$query = "INSERT INTO `".$this->saasappoint_addons."`(`id`, `business_id`, `service_id`, `title`, `rate`, `image`, `multiple_qty`, `status`, `description`) VALUES (NULL, '".$this->business_id."', '".$this->service_id."', '".$this->title."', '".$this->rate."', '".$this->image."', '".$this->multiple_qty."', '".$this->status."', '".$this->description."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete addons */
	public function delete_addon(){
		$query = "delete from `".$this->saasappoint_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check appointments before delete addons */
	public function check_appointments_before_delete_addon(){
		$query = "select `addons` from `".$this->saasappoint_bookings."` where `service_id`='".$this->service_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			while($value=mysqli_fetch_assoc($result)){
				$unserialized_addons = unserialize($value['addons']);
				foreach($unserialized_addons as $addon){
					if($this->id == $addon['id']){
						return "appointmentexist";
					}
				}
			}
		}else{
			return "noappointmentexist";
		}
	}
}
?>