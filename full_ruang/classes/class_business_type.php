<?php 
class saasappoint_business_type{
	public $conn;
	public $id;
	public $business_type;
	public $status;
	public $saasappoint_business_type = 'saasappoint_business_type';
	public $saasappoint_businesses = 'saasappoint_businesses';
	public $saasappoint_subscription_plans = 'saasappoint_subscription_plans';
		
	/* Function to check for setup instruction modal */
	public function check_for_setup_instruction_modal(){
		$query = "select * from `".$this->saasappoint_business_type."`";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		if($count==0){
			return "Y";
		}else{
			$query = "select * from `".$this->saasappoint_subscription_plans."`";
			$result=mysqli_query($this->conn,$query);
			$count=mysqli_num_rows($result);
			if($count==0){
				return "Y";
			}else{
				return "N";
			}
		}
	}
	
	/* Function to read one business type */
	public function readone_business_type(){
		$query = "select * from `".$this->saasappoint_business_type."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to read all business type */
	public function readall_business_type(){
		$query = "select * from `".$this->saasappoint_business_type."` where `status`='Y'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all business type for superadmin */
	public function readall_business_type_sadmin(){
		$query = "select * from `".$this->saasappoint_business_type."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add business type */
	public function add_business_type(){
		$query = "INSERT INTO `".$this->saasappoint_business_type."` (`id`, `business_type`, `status`) VALUES (NULL, '".$this->business_type."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change status of business type */
	public function change_business_type_status(){
		$query = "update `".$this->saasappoint_business_type."` set `status` = '".$this->status."' where `id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update business type */
	public function update_business_type(){
		$query = "update `".$this->saasappoint_business_type."` set `business_type` = '".$this->business_type."' where `id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check before delete business type */
	public function check_subscription_before_delete_business_type(){
		$query = "select `id` from `".$this->saasappoint_businesses."` where `business_type_id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return mysqli_num_rows($result);
	}
	
	/* Function to delete business type */
	public function delete_business_type(){
		$query = "delete from `".$this->saasappoint_business_type."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>