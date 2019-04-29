<?php 
class saasappoint_login{
	public $conn;
	public $email;
	public $password;
	public $business_id;
	public $remember_me;
	public $saasappoint_customers = "saasappoint_customers";
	public $saasappoint_admins = "saasappoint_admins";
	public $saasappoint_superadmins = "saasappoint_superadmins";
	
	/* Function to check login details */
	public function login_process(){
		/* Check email address and password are correct or not in customers table */
		$query = "select * from `".$this->saasappoint_customers."` where `email`='".$this->email."' and `password`='".md5($this->password)."' and `status`='Y'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_assoc($result);
		
			/* Set session values for logged in customer */
			unset($_SESSION['business_id']);
			unset($_SESSION['admin_id']);
			unset($_SESSION['superadmin_id']);
			$_SESSION['customer_id'] = $value['id'];
			$_SESSION['login_type'] = "customer";
			
			/* Set cookie if remember me is checked */
			if($this->remember_me == "Y"){
				setcookie('saasappoint_email',$this->email, time() + (86400 * 30), "/");
				setcookie('saasappoint_password',$this->password, time() + (86400 * 30), "/");
				setcookie('saasappoint_remember_me',"checked", time() + (86400 * 30), "/");
			}else{
				unset($_COOKIE['saasappoint_email']);
				unset($_COOKIE['saasappoint_password']);
				unset($_COOKIE['saasappoint_remember_me']);
				setcookie('saasappoint_email',null, -1, '/');
				setcookie('saasappoint_password',null, -1, '/');
				setcookie('saasappoint_remember_me',null, -1, '/');
			}
            echo "customer";
        }else{
			/* Check email address and password are correct or not in admins table */
            $query = "select * from `".$this->saasappoint_admins."` where `email`='".$this->email."' and `password`='".md5($this->password)."' and `status`='Y'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				$value=mysqli_fetch_assoc($result);
			
				/* Set session values for logged in user */
				unset($_SESSION['customer_id']);
				unset($_SESSION['superadmin_id']);
				$_SESSION['business_id'] = $value['business_id'];
				$_SESSION['admin_id'] = $value['id'];
				$_SESSION['login_type'] = "admin";
				
				/* Set cookie if remember me is checked */
				if($this->remember_me == "Y"){
					setcookie('saasappoint_email',$this->email, time() + (86400 * 30), "/");
					setcookie('saasappoint_password',$this->password, time() + (86400 * 30), "/");
					setcookie('saasappoint_remember_me',"checked", time() + (86400 * 30), "/");
				}else{
					unset($_COOKIE['saasappoint_email']);
					unset($_COOKIE['saasappoint_password']);
					unset($_COOKIE['saasappoint_remember_me']);
					setcookie('saasappoint_email',null, -1, '/');
					setcookie('saasappoint_password',null, -1, '/');
					setcookie('saasappoint_remember_me',null, -1, '/');
				}
				echo 'admin';
            }else{
				/* Check email address and password are correct or not in superadmin table */
				$query = "select * from `".$this->saasappoint_superadmins."` where `email`='".$this->email."' and `password`='".md5($this->password)."' and `status`='Y'";
				$result=mysqli_query($this->conn,$query);
				
				/* To check superadmin exist or not */
				if(mysqli_num_rows($result)>0){
					$value=mysqli_fetch_assoc($result);
					
					/* Set session values for logged in user */
					unset($_SESSION['business_id']);
					unset($_SESSION['admin_id']);
					unset($_SESSION['customer_id']);
					$_SESSION['superadmin_id'] = $value['id'];
					$_SESSION['login_type'] = "superadmin";
					
					/* Set cookie if remember me is checked */
					if($this->remember_me == "Y"){
						setcookie('saasappoint_email',$this->email, time() + (86400 * 30), "/");
						setcookie('saasappoint_password',$this->password, time() + (86400 * 30), "/");
						setcookie('saasappoint_remember_me',"checked", time() + (86400 * 30), "/");
					}else{
						unset($_COOKIE['saasappoint_email']);
						unset($_COOKIE['saasappoint_password']);
						unset($_COOKIE['saasappoint_remember_me']);
						setcookie('saasappoint_email',null, -1, '/');
						setcookie('saasappoint_password',null, -1, '/');
						setcookie('saasappoint_remember_me',null, -1, '/');
					}
					echo 'superadmin';
				}else{
					echo 'no';
				}
            }
        }
	}
	
	/* Function to check autologin details */
	public function autologin_process(){
		/* Check business id correct or not in admins table */
		$query = "select * from `".$this->saasappoint_admins."` where `business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check admin exist or not */
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_assoc($result);
		
			/* Set session values for logged in user */
			unset($_SESSION['customer_id']);
			unset($_SESSION['superadmin_id']);
			$_SESSION['business_id'] = $value['business_id'];
			$_SESSION['admin_id'] = $value['id'];
			$_SESSION['login_type'] = "admin";
			echo 'admin';
		}else{
			echo 'no';
		}
	}
	
	/*** Function to check existing email ***/
	public function check_email_exist(){
		/* Check email address correct or not in customers table */
		$query = "select * from `".$this->saasappoint_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return false;
        }else{
			/* Check email address correct or not in admins table */
            $query = "select * from `".$this->saasappoint_admins."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				return false;
            }else{
				/* Check email address correct or not in superadmin table */
				$query = "select * from `".$this->saasappoint_superadmins."` where `email`='".$this->email."'";
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
	
	/*** Function to check existing email ***/
	public function existing_email_check(){
		/* Check email address correct or not in customers table */
		$query = "select `id`, `firstname`, `lastname` from `".$this->saasappoint_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return $result;
        }else{
			/* Check email address correct or not in admins table */
            $query = "select `id`, `firstname`, `lastname` from `".$this->saasappoint_admins."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				return $result;
            }else{
				/* Check email address correct or not in superadmin table */
				$query = "select `id`, `firstname`, `lastname` from `".$this->saasappoint_superadmins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				return $result;
            }
        }
	}
	
	/*** Function to reset password ***/
	public function reset_password(){
		/* Check email address correct or not in customers table */
		$query = "select `id` from `".$this->saasappoint_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			$res = mysqli_query($this->conn,"update `".$this->saasappoint_customers."` set `password`='".$this->password."' where `email`='".$this->email."'");
			return $res;
        }else{
			/* Check email address correct or not in admins table */
            $query = "select `id` from `".$this->saasappoint_admins."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check admin exist or not */
			if(mysqli_num_rows($result)>0){
				$res = mysqli_query($this->conn,"update `".$this->saasappoint_admins."` set `password`='".$this->password."' where `email`='".$this->email."'");
				return $res;
            }else{
				/* Check email address correct or not in superadmin table */
				$query = "select `id` from `".$this->saasappoint_superadmins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				
				if(mysqli_num_rows($result)>0){
					$res = mysqli_query($this->conn,"update `".$this->saasappoint_superadmins."` set `password`='".$this->password."' where `email`='".$this->email."'");
					return $res;
				}else{
					return false;
				}
            }
        }
	}
}
?>