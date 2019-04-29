<?php 
class saasappoint_subscriptions_history{
	public $conn;
	public $id;
	public $business_id;
	public $admin_id;
	public $plan_id;
	public $transaction_id;
	public $subscribed_on;
	public $expired_on;
	public $joined_on;
	public $renewal;
	public $saasappoint_subscriptions_history = 'saasappoint_subscriptions_history';
	public $saasappoint_subscriptions = 'saasappoint_subscriptions';
		
	/* Function to read one subscription history */
	public function read_subscription_history_of_business(){
		$query = "select * from `".$this->saasappoint_subscriptions_history."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one subscription */
	public function read_current_subscription_detail_of_business(){
		$query = "select * from `".$this->saasappoint_subscriptions."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
}
?>