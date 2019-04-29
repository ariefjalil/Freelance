<?php 
class saasappoint_superadmins{
	public $conn;
	public $id;
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
	public $role;
	public $status;
	public $saasappoint_superadmins = 'saasappoint_superadmins';
	public $saasappoint_superadmin_settings = 'saasappoint_superadmin_settings';
	public $saasappoint_customers = 'saasappoint_customers';
	public $saasappoint_admins = 'saasappoint_admins';
		
	/* Function to read super admin's profile data */
	public function readone_profile(){
		$query = "select * from `".$this->saasappoint_superadmins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to update profile data */
	public function update_profile(){
		$query = "update `".$this->saasappoint_superadmins."` set `firstname`='".$this->firstname."', `lastname`='".$this->lastname."', `phone`='".$this->phone."', `address`='".$this->address."', `city`='".$this->city."', `state`='".$this->state."', `country`='".$this->country."', `zip`='".$this->zip."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to change password */
	public function change_password(){
		$query = "update `".$this->saasappoint_superadmins."` set `password`='".$this->password."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check old password */
	public function check_old_password(){
		$query = "select `id` from `".$this->saasappoint_superadmins."` where `id`='".$this->id."' and `password`='".$this->password."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count>0;
	}
	
	/* Function to update value in superadmin table */
	public function update_sadminsetup_settings($firstname, $lastname, $email, $password, $phone, $address, $city, $state, $country, $zip, $companyname, $companyemail, $companyphone){
		$query = "select `id` from `".$this->saasappoint_superadmins."` where `role`='superadmin' limit 1";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$data = mysqli_fetch_array($result);
			$id = $data["id"];
			$query = "update `".$this->saasappoint_superadmins."` set `email`='".$email."', `password`='".$password."', `firstname`='".$firstname."', `lastname`='".$lastname."', `phone`='".$phone."', `address`='".$address."', `city`='".$city."', `state`='".$state."', `zip`='".$zip."', `country`='".$country."', `status`='Y' where `id`='".$id."'";
			$result=mysqli_query($this->conn,$query);
			if($result){
				mysqli_query($this->conn,"TRUNCATE TABLE `".$this->saasappoint_superadmin_settings."`");
				$res = mysqli_query($this->conn,"INSERT INTO `".$this->saasappoint_superadmin_settings."` (`id`, `option_name`, `option_value`) VALUES
						(NULL, 'saasappoint_company_name', '".$companyname."'),
						(NULL, 'saasappoint_company_email', '".$companyemail."'),
						(NULL, 'saasappoint_company_phone', '".$companyphone."'),
						(NULL, 'saasappoint_stripe_publickey', ''),
						(NULL, 'saasappoint_stripe_secretkey', ''),
						(NULL, 'saasappoint_currency', 'USD'),
						(NULL, 'saasappoint_currency_symbol', '$'),
						(NULL, 'saasappoint_date_format', 'd-m-Y'),
						(NULL, 'saasappoint_time_format', '12'),
						(NULL, 'saasappoint_email_sender_name', '".$companyname."'),
						(NULL, 'saasappoint_email_sender_email', '".$companyemail."'),
						(NULL, 'saasappoint_email_smtp_hostname', ''),
						(NULL, 'saasappoint_email_smtp_username', ''),
						(NULL, 'saasappoint_email_smtp_password', ''),
						(NULL, 'saasappoint_email_smtp_port', ''),
						(NULL, 'saasappoint_email_encryption_type', 'tls'),
						(NULL, 'saasappoint_email_smtp_authentication', 'true'),
						
						(NULL, 'saasappoint_paypal_payment_status', 'N'),
						(NULL, 'saasappoint_paypal_guest_payment', 'N'),
						(NULL, 'saasappoint_paypal_api_username', ''),
						(NULL, 'saasappoint_paypal_api_password', ''),
						(NULL, 'saasappoint_paypal_signature', ''),
						(NULL, 'saasappoint_stripe_payment_status', 'N'),
						(NULL, 'saasappoint_authorizenet_payment_status', 'N'),
						(NULL, 'saasappoint_authorizenet_api_login_id', ''),
						(NULL, 'saasappoint_authorizenet_transaction_key', ''),
						(NULL, 'saasappoint_twocheckout_payment_status', 'N'),
						(NULL, 'saasappoint_twocheckout_publishable_key', ''),
						(NULL, 'saasappoint_twocheckout_private_key', ''),
						(NULL, 'saasappoint_twocheckout_seller_id', ''),
						(NULL, 'saasappoint_version', '2.6'),
						(NULL, 'saasappoint_timezone', '".date_default_timezone_get()."'),
						(NULL, 'saasappoint_reminder_buffer_time', '60'),
						
						(NULL, 'saasappoint_seo_ga_code', ''),
						(NULL, 'saasappoint_seo_meta_tag', '".$companyname."'),
						(NULL, 'saasappoint_seo_og_meta_tag', ''),
						(NULL, 'saasappoint_seo_og_tag_type', ''),
						(NULL, 'saasappoint_seo_og_tag_url', ''),
						(NULL, 'saasappoint_seo_meta_description', ''),
						(NULL, 'saasappoint_seo_og_tag_image', ''),
						(NULL, 'saasappoint_pay_at_venue_status', 'Y');");
				return $res;
			}else{
				return false;
			}
		}else{
			$query = "INSERT INTO `".$this->saasappoint_superadmins."` (`id`, `email`, `password`, `firstname`, `lastname`, `phone`, `address`, `city`, `state`, `zip`, `country`, `role`, `status`) VALUES (NULL, '".$email."', '".$password."', '".$firstname."', '".$lastname."', '".$phone."', '".$address."', '".$city."', '".$state."', '".$zip."', '".$country."', 'superadmin', 'Y');";
			$result=mysqli_query($this->conn,$query);
			if($result){
				mysqli_query($this->conn,"TRUNCATE TABLE `".$this->saasappoint_superadmin_settings."`");
				$res = mysqli_query($this->conn,"INSERT INTO `".$this->saasappoint_superadmin_settings."` (`id`, `option_name`, `option_value`) VALUES
						(NULL, 'saasappoint_company_name', '".$companyname."'),
						(NULL, 'saasappoint_company_email', '".$companyemail."'),
						(NULL, 'saasappoint_company_phone', '".$companyphone."'),
						(NULL, 'saasappoint_stripe_publickey', ''),
						(NULL, 'saasappoint_stripe_secretkey', ''),
						(NULL, 'saasappoint_currency', 'USD'),
						(NULL, 'saasappoint_currency_symbol', '$'),
						(NULL, 'saasappoint_date_format', 'd-m-Y'),
						(NULL, 'saasappoint_time_format', '12'),
						(NULL, 'saasappoint_email_sender_name', '".$companyname."'),
						(NULL, 'saasappoint_email_sender_email', '".$companyemail."'),
						(NULL, 'saasappoint_email_smtp_hostname', ''),
						(NULL, 'saasappoint_email_smtp_username', ''),
						(NULL, 'saasappoint_email_smtp_password', ''),
						(NULL, 'saasappoint_email_smtp_port', ''),
						(NULL, 'saasappoint_email_encryption_type', 'tls'),
						(NULL, 'saasappoint_email_smtp_authentication', 'true'),
						
						(NULL, 'saasappoint_paypal_payment_status', 'N'),
						(NULL, 'saasappoint_paypal_guest_payment', 'N'),
						(NULL, 'saasappoint_paypal_api_username', ''),
						(NULL, 'saasappoint_paypal_api_password', ''),
						(NULL, 'saasappoint_paypal_signature', ''),
						(NULL, 'saasappoint_stripe_payment_status', 'N'),
						(NULL, 'saasappoint_authorizenet_payment_status', 'N'),
						(NULL, 'saasappoint_authorizenet_api_login_id', ''),
						(NULL, 'saasappoint_authorizenet_transaction_key', ''),
						(NULL, 'saasappoint_twocheckout_payment_status', 'N'),
						(NULL, 'saasappoint_twocheckout_publishable_key', ''),
						(NULL, 'saasappoint_twocheckout_private_key', ''),
						(NULL, 'saasappoint_twocheckout_seller_id', ''),
						(NULL, 'saasappoint_version', '2.6'),
						(NULL, 'saasappoint_timezone', '".date_default_timezone_get()."'),
						(NULL, 'saasappoint_reminder_buffer_time', '60'),
						
						(NULL, 'saasappoint_seo_ga_code', ''),
						(NULL, 'saasappoint_seo_meta_tag', '".$companyname."'),
						(NULL, 'saasappoint_seo_og_meta_tag', ''),
						(NULL, 'saasappoint_seo_og_tag_type', ''),
						(NULL, 'saasappoint_seo_og_tag_url', ''),
						(NULL, 'saasappoint_seo_meta_description', ''),
						(NULL, 'saasappoint_seo_og_tag_image', ''),
						(NULL, 'saasappoint_pay_at_venue_status', 'Y');");
				return $res;
			}else{
				return false;
			}
		}
	}
	
	/* Function to read particular admin's email */
	public function get_superadmin_email(){
		$query = "select `email` from `".$this->saasappoint_superadmins."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['email'];
	}
	
	/* Function to update profile email */
	public function update_email(){
		$query = "update `".$this->saasappoint_superadmins."` set `email`='".$this->email."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read particular admin's email */
	public function check_email_availability($superadmin_email){
		/* Check email address correct or not in customers table */
		$query = "select `id` from `".$this->saasappoint_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return false;
        }else{
			/* Check email address correct or not in admins table */
            $query = "select `id` from `".$this->saasappoint_admins."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				return false;
            }else{
				/* Check email address correct or not in superadmin table */
				$query = "select `id` from `".$this->saasappoint_superadmins."` where `email`='".$this->email."' and `email`<>'".$superadmin_email."'";
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