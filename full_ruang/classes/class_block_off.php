<?php 
class saasappoint_block_off{
	public $conn;
	public $id;
	public $business_id;
	public $title;
	public $from_date;
	public $to_date;
	public $pattern;
	public $blockoff_type;
	public $from_time;
	public $to_time;
	public $status;
	public $saasappoint_block_off = 'saasappoint_block_off';
	
	/* Function to get all block_off detail */
	public function readall_block_off(){
		$query = "select * from `".$this->saasappoint_block_off."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get block_off detail */
	public function readone_block_off(){
		$query = "select * from `".$this->saasappoint_block_off."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
		
	/* Function to update block_off */
	public function update_block_off(){
		$query = "UPDATE `".$this->saasappoint_block_off."` SET `title` = '".$this->title."', `from_date` = '".$this->from_date."', `to_date` = '".$this->to_date."', `pattern` = '".$this->pattern."', `blockoff_type` = '".$this->blockoff_type."', `from_time` = '".$this->from_time."', `to_time` = '".$this->to_time."' WHERE `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to update block_off */
	public function update_block_off_status(){
		$query = "UPDATE `".$this->saasappoint_block_off."` SET `status` = '".$this->status."' WHERE `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add block_off */
	public function add_block_off(){
		$query = "INSERT INTO `".$this->saasappoint_block_off."`(`id`, `business_id`, `title`, `from_date`, `to_date`, `pattern`, `blockoff_type`, `from_time`, `to_time`, `status`) VALUES (NULL, '".$this->business_id."', '".$this->title."', '".$this->from_date."', '".$this->to_date."', '".$this->pattern."', '".$this->blockoff_type."', '".$this->from_time."', '".$this->to_time."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete block_off */
	public function delete_block_off(){
		$query = "delete from `".$this->saasappoint_block_off."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
} 
?>