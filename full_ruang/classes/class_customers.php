<?php 
class saasappoint_customers{
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
	public $saasappoint_customers = 'saasappoint_customers';
	public $saasappoint_bookings = 'saasappoint_bookings';
	public $saasappoint_customer_orderinfo = 'saasappoint_customer_orderinfo';
	public $saasappoint_categories = 'saasappoint_categories';
	public $saasappoint_services = 'saasappoint_services';
	public $saasappoint_payments = 'saasappoint_payments';
	public $saasappoint_admins = 'saasappoint_admins';
	public $saasappoint_superadmins = 'saasappoint_superadmins';
	public $saasappoint_customer_referrals = 'saasappoint_customer_referrals';
	
	/* Function to count all registered customers */
	public function get_all_customer_referrals($customer_id){
		$query = "select * from `".$this->saasappoint_customer_referrals."` where `ref_customer_id`='".$customer_id."' and `completed`='Y' order by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all registered customers */
	public function count_all_rc($search){
		$group_by_qry = 'group by `c`.`id`';
		if($search != ''){
			$query = "select count(`c`.`id`) from `".$this->saasappoint_customers."` as `c`, `".$this->saasappoint_bookings."` as `b` where ((`c`.`firstname` like '%".$search."%' or `c`.`lastname` like '%".$search."%') or `c`.`email` like '%".$search."%' or `c`.`phone` like '%".$search."%' or (`c`.`address` like '%".$search."%' or `c`.`city` like '%".$search."%' or `c`.`state` like '%".$search."%' or `c`.`zip` like '%".$search."%' or `c`.`country` like '%".$search."%')) and `b`.`customer_id` = `c`.`id` and `b`.`business_id`='".$this->business_id."' ".$group_by_qry;
		}else{
			$query = "select count(`c`.`id`) from `".$this->saasappoint_customers."` as `c`, `".$this->saasappoint_bookings."` as `b` where `b`.`customer_id` = `c`.`id` and `b`.`business_id`='".$this->business_id."' ".$group_by_qry;
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all registered customers detail */
	public function get_all_rc_detail($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `c`.`id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `c`.`firstname` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `c`.`email` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `c`.`phone` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `c`.`address` '.$direction;
		}else{
			$order_by_qry = 'order by `c`.`id` '.$direction;
		}
		$group_by_qry = 'group by `c`.`id`, `c`.`firstname`, `c`.`lastname`, `c`.`email`, `c`.`phone`, `c`.`address`, `c`.`city`, `c`.`state`, `c`.`zip`, `c`.`country`, `c`.`refferral_code`';
		if($search != ''){
			$query = "select `c`.`id`, `c`.`firstname`, `c`.`lastname`, `c`.`email`, `c`.`phone`, `c`.`address`, `c`.`city`, `c`.`state`, `c`.`zip`, `c`.`country`, `c`.`refferral_code` from `".$this->saasappoint_customers."` as `c`, `".$this->saasappoint_bookings."` as `b` where ((`c`.`firstname` like '%".$search."%' or `c`.`lastname` like '%".$search."%') or `c`.`email` like '%".$search."%' or `c`.`phone` like '%".$search."%' or (`c`.`address` like '%".$search."%' or `c`.`city` like '%".$search."%' or `c`.`state` like '%".$search."%' or `c`.`zip` like '%".$search."%' or `c`.`country` like '%".$search."%')) and `b`.`customer_id` = `c`.`id` and `b`.`business_id`='".$this->business_id."' ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `c`.`id`, `c`.`firstname`, `c`.`lastname`, `c`.`email`, `c`.`phone`, `c`.`address`, `c`.`city`, `c`.`state`, `c`.`zip`, `c`.`country`, `c`.`refferral_code` from `".$this->saasappoint_customers."` as `c`, `".$this->saasappoint_bookings."` as `b` where `b`.`customer_id` = `c`.`id` and `b`.`business_id`='".$this->business_id."' ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to count all registered customer appointments */
	public function count_all_rc_booked_appt($customer_id){
		$query = "select count(`b`.`order_id`) as `total_appointments` from `".$this->saasappoint_bookings."` as `b` where `b`.`customer_id` = '".$customer_id."' and `b`.`business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_appointments'];
	}
		
	/* Function to count all guest customers */
	public function count_all_gc($search){
		$group_by_qry = 'group by `b`.`order_id`';
		if($search != ''){
			$query = "select count(`b`.`order_id`) from `".$this->saasappoint_customer_orderinfo."` as `c`, `".$this->saasappoint_bookings."` as `b` where ((`c`.`c_firstname` like '%".$search."%' or `c`.`c_lastname` like '%".$search."%') or `c`.`c_email` like '%".$search."%' or `c`.`c_phone` like '%".$search."%' or (`c`.`c_address` like '%".$search."%' or `c`.`c_city` like '%".$search."%' or `c`.`c_state` like '%".$search."%' or `c`.`c_zip` like '%".$search."%' or `c`.`c_country` like '%".$search."%')) and `b`.`order_id` = `c`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' ".$group_by_qry;
		}else{
			$query = "select count(`b`.`order_id`) from `".$this->saasappoint_customer_orderinfo."` as `c`, `".$this->saasappoint_bookings."` as `b` where `b`.`order_id` = `c`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' ".$group_by_qry;
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to get all guest customers detail */
	public function get_all_gc_detail($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `c`.`id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `c`.`firstname` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `c`.`email` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `c`.`phone` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `c`.`address` '.$direction;
		}else{
			$order_by_qry = 'order by `c`.`id` '.$direction;
		}
		$group_by_qry = 'group by `c`.`order_id`, `c`.`c_firstname`, `c`.`c_lastname`, `c`.`c_email`, `c`.`c_phone`, `c`.`c_address`, `c`.`c_city`, `c`.`c_state`, `c`.`c_zip`, `c`.`c_country`';
		if($search != ''){
			$query = "select `c`.`order_id`, `c`.`c_firstname`, `c`.`c_lastname`, `c`.`c_email`, `c`.`c_phone`, `c`.`c_address`, `c`.`c_city`, `c`.`c_state`, `c`.`c_zip`, `c`.`c_country` from `".$this->saasappoint_customer_orderinfo."` as `c`, `".$this->saasappoint_bookings."` as `b` where ((`c`.`c_firstname` like '%".$search."%' or `c`.`c_lastname` like '%".$search."%') or `c`.`c_email` like '%".$search."%' or `c`.`c_phone` like '%".$search."%' or (`c`.`c_address` like '%".$search."%' or `c`.`c_city` like '%".$search."%' or `c`.`c_state` like '%".$search."%' or `c`.`c_zip` like '%".$search."%' or `c`.`c_country` like '%".$search."%')) and `b`.`order_id` = `c`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `c`.`order_id`, `c`.`c_firstname`, `c`.`c_lastname`, `c`.`c_email`, `c`.`c_phone`, `c`.`c_address`, `c`.`c_city`, `c`.`c_state`, `c`.`c_zip`, `c`.`c_country` from `".$this->saasappoint_customer_orderinfo."` as `c`, `".$this->saasappoint_bookings."` as `b` where `b`.`order_id` = `c`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."' ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all guest customer appointments */
	public function count_all_gc_booked_appt($order_id){
		$query = "select count(`b`.`order_id`) as `total_appointments` from `".$this->saasappoint_bookings."` as `b` where `b`.`order_id` = '".$order_id."' and `b`.`business_id`='".$this->business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_appointments'];
	}
		
	/* Function to get all registered customer's appointments detail */
	public function get_all_rc_appointments($start, $end, $search, $column, $direction, $draw){
		$order_by_qry = '';
		$search_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `b`.`order_id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `b`.`order_id` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `c`.`cat_name` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `s`.`title` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `b`.`booking_datetime` '.$direction;
		}else if($column == 5){
			$order_by_qry = 'order by `b`.`booking_status` '.$direction;
		}else if($column == 6){
			$order_by_qry = 'order by `p`.`payment_method` '.$direction;
		}else{
			$order_by_qry = 'order by `b`.`order_id` '.$direction;
		}
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_status` , `p`.`payment_method`';
		
		if($search != ''){
			$search_by_qry = "and (`b`.`order_id` like '%".$search."%' or `c`.`cat_name` like '%".$search."%' or `s`.`title` like '%".$search."%' or `b`.`booking_datetime` like '%".$search."%' or `b`.`booking_status` like '%".$search."%' or `p`.`payment_method` like '%".$search."%')";
		}
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_status` , `p`.`payment_method` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_payments."` as `p` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id`='".$this->id."' and `b`.`business_id`='".$this->business_id."' ".$search_by_qry." ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all registered customer's appointments detail */
	public function count_all_rc_appointments($search){
		$search_by_qry = '';
		$group_by_qry = 'group by `b`.`order_id`';
		
		if($search != ''){
			$search_by_qry = "and (`b`.`order_id` like '%".$search."%' or `c`.`cat_name` like '%".$search."%' or `s`.`title` like '%".$search."%' or `b`.`booking_datetime` like '%".$search."%' or `b`.`booking_status` like '%".$search."%' or `p`.`payment_method` like '%".$search."%')";
		}
		
		$query = "select `b`.`order_id` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_payments."` as `p` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id`='".$this->id."' and `b`.`business_id`='".$this->business_id."' ".$search_by_qry." ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_num_rows($result);
		return $value;
	}
	
	/* Function to get all guest customer's appointments detail */
	public function get_all_gc_appointments($start, $end, $search, $column, $direction, $draw){
		$order_by_qry = '';
		$search_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `b`.`order_id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `b`.`order_id` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `c`.`cat_name` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `s`.`title` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `b`.`booking_datetime` '.$direction;
		}else if($column == 5){
			$order_by_qry = 'order by `b`.`booking_status` '.$direction;
		}else if($column == 6){
			$order_by_qry = 'order by `p`.`payment_method` '.$direction;
		}else{
			$order_by_qry = 'order by `b`.`order_id` '.$direction;
		}
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_status` , `p`.`payment_method`';
		
		if($search != ''){
			$search_by_qry = "and (`b`.`order_id` like '%".$search."%' or `c`.`cat_name` like '%".$search."%' or `s`.`title` like '%".$search."%' or `b`.`booking_datetime` like '%".$search."%' or `b`.`booking_status` like '%".$search."%' or `p`.`payment_method` like '%".$search."%')";
		}
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_status` , `p`.`payment_method` from `".$this->saasappoint_categories."` as `c`, `".$this->saasappoint_services."` as `s`, `".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_payments."` as `p` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."' and `b`.`business_id`='".$this->business_id."' ".$search_by_qry." ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get all customers details for export */
	public function get_all_customers_to_export(){
		$selected_fields = '`b`.`customer_id`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_email`, `o`.`c_phone`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`order_id` = `o`.`order_id` and `b`.`business_id`='".$this->business_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only registered customers details for export */
	public function all_registered_customers_to_export(){
		$selected_fields = '`b`.`customer_id`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`order_id` = `o`.`order_id` and `b`.`customer_id` <> '0' and `b`.`business_id`='".$this->business_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only guest customers details for export */
	public function all_guest_customers_to_export(){
		$selected_fields = '`b`.`customer_id`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`';
		
		$from_qry = "`".$this->saasappoint_bookings."` as `b`, `".$this->saasappoint_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`order_id` = `o`.`order_id` and `b`.`customer_id` = '0' and `b`.`business_id`='".$this->business_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only registered customers details for export */
	public function get_customer_name(){
		$query = "select `firstname`, `lastname` from `".$this->saasappoint_customers."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return ucwords($value['firstname']." ".$value['lastname']);
	}
	
	/* Function to get all only registered customers details for export */
	public function get_reff_customer_name($customer_id){
		$query = "select `firstname`, `lastname` from `".$this->saasappoint_customers."` where `id`='".$customer_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return ucwords($value['firstname']." ".$value['lastname']);
	}
	
	/* Function to get registered customer details for profile */
	public function readone_customer(){
		$query = "select * from `".$this->saasappoint_customers."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to update profile with image */
	public function update_profile_with_image(){
		$query = "update `".$this->saasappoint_customers."` set `firstname`='".$this->firstname."', `lastname`='".$this->lastname."', `phone`='".$this->phone."', `address`='".$this->address."', `city`='".$this->city."', `state`='".$this->state."', `country`='".$this->country."', `zip`='".$this->zip."', `image`='".$this->image."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update profile without image */
	public function update_profile_without_image(){
		$query = "update `".$this->saasappoint_customers."` set `firstname`='".$this->firstname."', `lastname`='".$this->lastname."', `phone`='".$this->phone."', `address`='".$this->address."', `city`='".$this->city."', `state`='".$this->state."', `country`='".$this->country."', `zip`='".$this->zip."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read particular customer's profile image name */
	public function get_image_name_of_customer(){
		$query = "select `image` from `".$this->saasappoint_customers."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['image'];
	}
	
	/* Function to get detail of all business customers */
	public function get_business_customers(){
		$query = "select `c`.`id`, `c`.`firstname`, `c`.`lastname`, `c`.`email` from `".$this->saasappoint_customers."` as `c`, `".$this->saasappoint_bookings."` as `b` where `b`.`customer_id` = `c`.`id` and `b`.`business_id`='".$this->business_id."' group by `c`.`id`, `c`.`firstname`, `c`.`lastname`, `c`.`email`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change password */
	public function change_password(){
		$query = "update `".$this->saasappoint_customers."` set `password`='".$this->password."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check old password */
	public function check_old_password(){
		$query = "select `id` from `".$this->saasappoint_customers."` where `id`='".$this->id."' and `password`='".$this->password."'";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count>0;
	}
	
	/* Function to read particular admin's email */
	public function get_customer_email(){
		$query = "select `email` from `".$this->saasappoint_customers."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['email'];
	}
	
	/* Function to update profile email */
	public function update_email(){
		$query = "update `".$this->saasappoint_customers."` set `email`='".$this->email."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read particular admin's email */
	public function check_email_availability($customer_email){
		/* Check email address correct or not in customers table */
		$query = "select `id` from `".$this->saasappoint_customers."` where `email`='".$this->email."' and `email`<>'".$customer_email."'";
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