<?php 
class saasappoint_subscriptions{
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
	public $payment_method;
	public $saasappoint_subscriptions = 'saasappoint_subscriptions';
	public $saasappoint_subscriptions_history = 'saasappoint_subscriptions_history';
		
	/* Function to read one subscription */
	public function readone_subscription(){
		$query = "select * from `".$this->saasappoint_subscriptions."` where `admin_id`='".$this->admin_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
		
	/* Function to add subscription */
	public function add_subscription(){
		$query = "INSERT INTO `".$this->saasappoint_subscriptions."`(`id`, `business_id`, `admin_id`, `plan_id`, `transaction_id`, `subscribed_on`, `expired_on`, `joined_on`, `renewal`, `payment_method`) VALUES (NULL, '".$this->business_id."', '".$this->admin_id."', '".$this->plan_id."', '".$this->transaction_id."', '".$this->subscribed_on."', '".$this->expired_on."', '".$this->joined_on."', '".$this->renewal."', '".$this->payment_method."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to add subscription history */
	public function add_subscription_history(){
		$query = "INSERT INTO `".$this->saasappoint_subscriptions_history."`(`id`, `business_id`, `admin_id`, `plan_id`, `transaction_id`, `subscribed_on`, `expired_on`, `renewal`, `payment_method`) VALUES (NULL, '".$this->business_id."', '".$this->admin_id."', '".$this->plan_id."', '".$this->transaction_id."', '".$this->subscribed_on."', '".$this->expired_on."', '".$this->renewal."', '".$this->payment_method."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to upgrade subscription */
	public function upgrade_subscription(){
		$query = "update `".$this->saasappoint_subscriptions."` set `plan_id` = '".$this->plan_id."', `transaction_id` = '".$this->transaction_id."', `subscribed_on` = '".$this->subscribed_on."', `expired_on` = '".$this->expired_on."', `renewal` = '".$this->renewal."' where `admin_id`='".$this->admin_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>