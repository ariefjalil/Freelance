<?php 
class saasappoint_frequently_discount{
	public $conn;
	public $id;
	public $business_id;
	public $fd_key;
	public $fd_label;
	public $fd_type;
	public $fd_value;
	public $fd_description;
	public $fd_status;
	public $saasappoint_frequently_discount = 'saasappoint_frequently_discount';
	
	/* Function to get all frequently discount */
	public function get_all_frequently_discount(){
		$query = "select * from ".$this->saasappoint_frequently_discount." where business_id='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change frequently discount status */
	public function change_frequently_discount_status(){
		$query = "update `".$this->saasappoint_frequently_discount."` set `fd_status`='".$this->fd_status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update frequently discount */
	public function update_frequently_discount(){
		$query = "update `".$this->saasappoint_frequently_discount."` set `fd_label`='".$this->fd_label."', `fd_type`='".$this->fd_type."', `fd_value`='".$this->fd_value."', `fd_description`='".$this->fd_description."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one frequently discount */
	public function readone_frequently_discount(){
		$query = "select * from `".$this->saasappoint_frequently_discount."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/** Function to add default frequently discount while registering as administrator **/
	public function add_default_frequently_discount(){
		$query = "INSERT INTO `".$this->saasappoint_frequently_discount."` (`id`, `business_id`, `fd_key`, `fd_label`, `fd_type`, `fd_value`, `fd_description`, `fd_status`) VALUES
		(NULL, '".$this->business_id."', 'one time', 'ONE TIME', 'flat', 0, 'No Discount', 'Y'),
		(NULL, '".$this->business_id."', 'weekly', 'WEEKLY', 'percentage', 10, '10% OFF', 'Y'),
		(NULL, '".$this->business_id."', 'bi weekly', 'BI-WEEKLY', 'percentage', 5, '5% OFF', 'Y'),
		(NULL, '".$this->business_id."', 'monthly', 'MONTHLY', 'percentage', 2, '2% OFF', 'Y')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>