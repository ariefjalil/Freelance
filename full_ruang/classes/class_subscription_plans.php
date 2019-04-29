<?php 
class saasappoint_subscription_plans{
	public $conn;
	public $id;
	public $plan_name;
	public $plan_rate;
	public $plan_period;
	public $sms_credit;
	public $plan_features;
	public $renewal_type;
	public $status;
	public $saasappoint_subscription_plans = 'saasappoint_subscription_plans';
	public $saasappoint_subscriptions = 'saasappoint_subscriptions';
		
	/* Function to insert subscription plans */
	public function add_subscription_plan(){
		$query = "INSERT INTO `".$this->saasappoint_subscription_plans."` (`id`, `plan_name`, `plan_rate`, `plan_period`, `sms_credit`, `plan_features`, `renewal_type`, `status`) VALUES (NULL, '".$this->plan_name."', '".$this->plan_rate."', '".$this->plan_period."', '0', '', '".$this->renewal_type."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to update subscription plans */
	public function update_subscription_plan(){
		$query = "update `".$this->saasappoint_subscription_plans."` set `plan_name`='".$this->plan_name."', `plan_rate`='".$this->plan_rate."', `plan_period`='".$this->plan_period."', `renewal_type`='".$this->renewal_type."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read all subscription plans */
	public function readall_subscription_plans(){
		$query = "select * from `".$this->saasappoint_subscription_plans."` where `status`='Y' order by `renewal_type` ASC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to change subscription plan status */
	public function change_splan_status(){
		$query = "update `".$this->saasappoint_subscription_plans."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read all subscription plans for superadmin */
	public function readall_subscription_plans_superadmin(){
		$query = "select * from `".$this->saasappoint_subscription_plans."` order by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one subscription plan */
	public function readone_subscription_plan(){
		$query = "select * from `".$this->saasappoint_subscription_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to delete subscription plan */
	public function check_subscription_before_delete_plan(){
		$query = "select `id` from `".$this->saasappoint_subscriptions."` where `plan_id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return mysqli_num_rows($result);
	}
	
	/* Function to delete subscription plan */
	public function delete_subscription_plan(){
		$query = "delete from `".$this->saasappoint_subscription_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read subscription plan name */
	public function read_subscription_planname(){
		$query = "select `plan_name` from `".$this->saasappoint_subscription_plans."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value['plan_name'];
	}
}
?>