<?php 
class saasappoint_admins{
	public $conn;
	public $id;
	public $business_id;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	public $phone;
	public $address;
	public $city;
	public $state;
	public $zip;
	public $country;
	public $image;
	public $status;
	public $points;
	public $pointstatus;
	public $saasappoint_admins = 'saasappoint_admins';
	public $saasappoint_customers = 'saasappoint_customers';
	public $saasappoint_superadmins = 'saasappoint_superadmins';
	
	/* Function to add admin */
	public function add_admin(){
		$query = "INSERT INTO `saasappoint_admins`(`id`, `business_id`, `email`, `password`, `firstname`, `lastname`, `phone`, `address`, `city`, `state`, `zip`, `country`, `image`, `status`, `points`, `pointstatus`) VALUES (NULL, '".$this->business_id."', '".$this->email."', '".$this->password."', '".$this->firstname."', '".$this->lastname."', '".$this->phone."', '".$this->address."', '".$this->city."', '".$this->state."', '".$this->zip."', '".$this->country."', '".$this->image."', '".$this->status."', '0', 'N')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);
		return $value;
	}
	
	/* Function to change password */
	public function change_password(){
		$query = "update `".$this->saasappoint_admins."` set `password`='".$this->password."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check old password */
	public function check_old_password(){
		$query = "select `id` from `".$this->saasappoint_admins."` where `id`='".$this->id."' and `password`='".$this->password."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count>0;
	}
	
	/* Function to update profile with image */
	public function update_profile_with_image(){
		$query = "update `".$this->saasappoint_admins."` set `firstname`='".$this->firstname."', `lastname`='".$this->lastname."', `phone`='".$this->phone."', `address`='".$this->address."', `city`='".$this->city."', `state`='".$this->state."', `country`='".$this->country."', `zip`='".$this->zip."', `image`='".$this->image."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update profile without image */
	public function update_profile_without_image(){
		$query = "update `".$this->saasappoint_admins."` set `firstname`='".$this->firstname."', `lastname`='".$this->lastname."', `phone`='".$this->phone."', `address`='".$this->address."', `city`='".$this->city."', `state`='".$this->state."', `country`='".$this->country."', `zip`='".$this->zip."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read particular admin's profile data */
	public function readone_profile(){
		$query = "select * from `".$this->saasappoint_admins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to read particular admin's profile image name */
	public function get_image_name_of_admin(){
		$query = "select `image` from `".$this->saasappoint_admins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['image'];
	}
	
	/* Function to read particular admin's name */
	public function get_admin_name(){
		$query = "select `firstname`, `lastname` from `".$this->saasappoint_admins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return ucwords($value['firstname']." ".$value['lastname']);
	}
	
	/* Function to read particular admin's email */
	public function get_admin_email(){
		$query = "select `email` from `".$this->saasappoint_admins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['email'];
	}
	
	/* Function to update profile email */
	public function update_email(){
		$query = "update `".$this->saasappoint_admins."` set `email`='".$this->email."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read particular admin's email */
	public function check_email_availability($admin_email){
		/* Check email address correct or not in customers table */
		$query = "select `id` from `".$this->saasappoint_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return false;
        }else{
			/* Check email address correct or not in admins table */
            $query = "select `id` from `".$this->saasappoint_admins."` where `email`='".$this->email."' and `email`<>'".$admin_email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				return false;
            }else{
				/* Check email address correct or not in superadmin table */
				$query = "select `id` from `".$this->saasappoint_superadmins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				
				/* To check superadmin exist or not */
				if(mysqli_num_rows($result)>0){
					return false;
				}else{
					return true;
				}
            }
        }
	}
}
?>