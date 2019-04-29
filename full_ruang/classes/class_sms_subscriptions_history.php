<?php 
class saasappoint_sms_subscriptions_history{
	public $conn;
	public $id;
	public $business_id;
	public $plan_id;
	public $amount;
	public $credit;
	public $transaction_id;
	public $payment_method;
	public $extended_on;
	public $saasappoint_sms_subscriptions_history = 'saasappoint_sms_subscriptions_history';
		
	/* Function to read all subscription history */
	public function readall_sms_subscription_history_of_admin(){
		$query = "select * from `".$this->saasappoint_sms_subscriptions_history."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add subscription history */
	public function add_sms_subscription_history(){
		$query = "insert into `".$this->saasappoint_sms_subscriptions_history."` (`id`, `business_id`, `plan_id`, `amount`, `credit`, `transaction_id`, `payment_method`, `extended_on`) VALUES (NULL, '".$this->business_id."', '".$this->plan_id."', '".$this->amount."', '".$this->credit."', '".$this->transaction_id."', '".$this->payment_method."', '".$this->extended_on."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
} 
?>