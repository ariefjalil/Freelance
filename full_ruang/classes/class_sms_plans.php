<?php 
class saasappoint_sms_plans{
	public $conn;
	public $id;
	public $plan_name;
	public $plan_rate;
	public $credit;
	public $status;
	public $saasappoint_sms_subscriptions_history = 'saasappoint_sms_subscriptions_history';
	public $saasappoint_sms_plans = 'saasappoint_sms_plans';
		
	/* Function to add sms plans */
	public function add_sms_plan(){
		$query = "INSERT INTO `".$this->saasappoint_sms_plans."` (`id`, `plan_name`, `plan_rate`, `credit`, `status`) VALUES (NULL, '".$this->plan_name."', '".$this->plan_rate."', '".$this->credit."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update sms plans */
	public function update_sms_plan(){
		$query = "update `".$this->saasappoint_sms_plans."` set `plan_name`='".$this->plan_name."', `plan_rate`='".$this->plan_rate."', `credit`='".$this->credit."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all sms plans */
	public function readall_sms_plans(){
		$query = "select * from `".$this->saasappoint_sms_plans."` where `status`='Y' order by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all sms plans for super admin */
	public function readall_sms_plans_for_superadmin(){
		$query = "select * from `".$this->saasappoint_sms_plans."` order by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read one sms plan */
	public function readone_sms_plan(){
		$query = "select * from `".$this->saasappoint_sms_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
		
	/* Function to delete sms plan */
	public function delete_sms_plan(){
		$query = "delete from `".$this->saasappoint_sms_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	/* Function to read one sms plan name */
	public function readone_sms_plan_name(){
		$query = "select `plan_name` from `".$this->saasappoint_sms_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value["plan_name"];
	}	
	
	/* Function to check sms plan */
	public function check_subscription_before_delete_plan(){
		$query = "select `id` from `".$this->saasappoint_sms_subscriptions_history."` where `plan_id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return mysqli_num_rows($result);
	}
} 
?>