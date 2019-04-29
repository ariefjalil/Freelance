<?php 
class saasappoint_businesses{
	public $conn;
	public $id;
	public $business_id;
	public $business_type_id;
	public $registered_on;
	public $status;
	public $saasappoint_businesses = 'saasappoint_businesses';
	public $saasappoint_business_type = 'saasappoint_business_type';
	public $saasappoint_admins = 'saasappoint_admins';
	public $saasappoint_settings = 'saasappoint_settings';
		
	/* Function to add business */
	public function add_business(){
		$query = "INSERT INTO `".$this->saasappoint_businesses."`(`id`, `business_type_id`, `registered_on`, `status`) VALUES (NULL, '".$this->business_type_id."', '".$this->registered_on."', '".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);
		return $value;
	}
		
	/* Function to get businesses */
	public function get_all_business(){
		$query = "select `b`.`id`, `t`.`business_type`, `b`.`registered_on`, `b`.`status`,`a`.`points`, `a`.`pointstatus`, `a`.`email`, `a`.`firstname`, `a`.`lastname` from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_business_type."` as `t`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`business_type_id` = `t`.`id` order by `b`.`id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get active businesses */
	public function get_all_active_businesses(){
		$query = "select `b`.`id`, `a`.`firstname`, `a`.`lastname` from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`status`='Y' order by `b`.`id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get active businesses */
	public function get_countof_all_active_businesses($keyword){
		$search_keywords = "";
		$bids = array();
		
		if($keyword != ""){
			$sbusinesses = mysqli_query($this->conn, "select `b`.`id`, `a`.`firstname`, `a`.`lastname` from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`status`='Y'");
			$search_input = strtolower($keyword);
			
			if(mysqli_num_rows($sbusinesses)>0){
				while($bdetail = mysqli_fetch_array($sbusinesses)){
					$business_id = $bdetail["id"];
					$cname = $this->get_companydetail("saasappoint_company_name", $business_id);
					$cemail = $this->get_companydetail("saasappoint_company_email", $business_id);
					$cphone = $this->get_companydetail("saasappoint_company_phone", $business_id);
					$caddress = $this->get_companydetail("saasappoint_company_address", $business_id);
					$ccity = $this->get_companydetail("saasappoint_company_city", $business_id);
					$cstate = $this->get_companydetail("saasappoint_company_state", $business_id);
					$czip = $this->get_companydetail("saasappoint_company_zip", $business_id);
					$ccountry = $this->get_companydetail("saasappoint_company_country", $business_id);
					$afname = $bdetail["firstname"];
					$alname = $bdetail["lastname"];
					
					$company_address = strtolower($cname)." ".strtolower($cemail)." ".strtolower($cphone)." ".strtolower($caddress)." ".strtolower($ccity)." ".strtolower($cstate)." ".strtolower($czip)." ".strtolower($ccountry)." ".strtolower($afname)." ".strtolower($alname);
					
					$compared_strings = $this->saasappoint_compare_strings($company_address, $search_input);
					if($compared_strings == "add") {
						array_push($bids, $bdetail["id"]);
					}
				}
				$imploded_bids = implode(",",$bids);
				if($imploded_bids != ""){
					$search_keywords = " and `b`.`id` in (".$imploded_bids.") ";
				}else{
					$search_keywords = " and `b`.`id` in (0) ";
				}
			}
		}
		$query = "select count(`b`.`id`) from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`status`='Y' ".$search_keywords;
		$result = mysqli_query($this->conn,$query);
		$value = mysqli_fetch_array($result);
		return $value[0];
	}
	
	/** function to compare search string **/
	public function saasappoint_compare_strings($s1, $s2) {
		if (strlen($s1)==0 || strlen($s2)==0) { return "no"; }
		while (strpos($s1, "  ")!==false) { $s1 = str_replace("  ", " ", $s1); }
		while (strpos($s2, "  ")!==false) { $s2 = str_replace("  ", " ", $s2); }

		$ar1 = explode(" ",$s1);
		$ar2 = explode(" ",$s2);
		$l1 = count($ar1);
		$l2 = count($ar2);
		$meaning="";
		$rightorder="";
		$compare=0;

		for ($i=0;$i<$l1;$i++) {
			for ($j=0;$j<$l2;$j++) {
				$compare = (similar_text($ar1[$i],$ar2[$j],$percent)) ;
				if ($percent>=85) {
					$meaning=$meaning." ".$ar1[$i];
					$rightorder=$rightorder." ".$ar1[$j];
					$compare=0;
				}
			}
		}
		$rightorder1 = explode(" ", $rightorder);
		$rightorder2 = array_filter($rightorder1, function($vals) { return $vals !== ''; });
		if (count($rightorder2)>0) {
			return "add";
		} else {
			return "no";
		}
	}
	
	/* Function to get option value from settings table */
	public function get_companydetail($option_name, $business_id){
		$query = "select `option_value` from `".$this->saasappoint_settings."` where `option_name`='".$option_name."' and `business_id`='".$business_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['option_value'];
	}
	
	/* Function to get active businesses */
	public function get_all_active_businesses_by_limit($start, $perpage, $keyword){
		$search_keywords = "";
		$bids = array();
		
		if($keyword != ""){
			$sbusinesses = mysqli_query($this->conn, "select `b`.`id`,`a`.`points`, `a`.`pointstatus`,  `a`.`firstname`, `a`.`lastname` from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`status`='Y' order by `a`.`points` desc");
			$search_input = strtolower($keyword);
			
			if(mysqli_num_rows($sbusinesses)>0){
				while($bdetail = mysqli_fetch_array($sbusinesses)){
					$business_id = $bdetail["id"];
					$cname = $this->get_companydetail("saasappoint_company_name", $business_id);
					$cemail = $this->get_companydetail("saasappoint_company_email", $business_id);
					$cphone = $this->get_companydetail("saasappoint_company_phone", $business_id);
					$caddress = $this->get_companydetail("saasappoint_company_address", $business_id);
					$ccity = $this->get_companydetail("saasappoint_company_city", $business_id);
					$cstate = $this->get_companydetail("saasappoint_company_state", $business_id);
					$czip = $this->get_companydetail("saasappoint_company_zip", $business_id);
					$ccountry = $this->get_companydetail("saasappoint_company_country", $business_id);
					$afname = $bdetail["firstname"];
					$alname = $bdetail["lastname"];
					
					$company_address = strtolower($cname)." ".strtolower($cemail)." ".strtolower($cphone)." ".strtolower($caddress)." ".strtolower($ccity)." ".strtolower($cstate)." ".strtolower($czip)." ".strtolower($ccountry)." ".strtolower($afname)." ".strtolower($alname);
					
					$compared_strings = $this->saasappoint_compare_strings($company_address, $search_input);
					if($compared_strings == "add") {
						array_push($bids, $bdetail["id"]);
					}
				}
				$imploded_bids = implode(",",$bids);
				if($imploded_bids != ""){
					$search_keywords = " and `b`.`id` in (".$imploded_bids.") ";
				}else{
					$search_keywords = " and `b`.`id` in (0) ";
				}
			}
		}
		
		$query = "select `b`.`id`, `a`.`firstname`, `a`.`points`, `a`.`pointstatus`, `a`.`lastname` from `".$this->saasappoint_businesses."` as `b`, `".$this->saasappoint_admins."` as `a` where `b`.`id` = `a`.`business_id` and `b`.`status`='Y' ".$search_keywords." order by `a`.`points` desc LIMIT $start, $perpage";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to change business status */
	public function change_business_status(){
		$query = "update `".$this->saasappoint_businesses."` set `status` = '".$this->status."' where `id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to delete business */
	public function delete_business($businessid){
		/** Delete all ticket discussions related ticket **/
		$res1 = mysqli_query($this->conn, "select `id` from `saasappoint_support_tickets` where `business_id`='".$businessid."';");
		if(mysqli_num_rows($res1)>0){
			while($val1 = mysqli_fetch_array($res1)){
				mysqli_query($this->conn, "delete from `saasappoint_support_ticket_discussions` where `ticket_id`='".$val1['id']."';");
			}
			$result=mysqli_query($this->conn, "delete from `saasappoint_support_tickets` where `business_id`='".$businessid."';");
		}
		
		$res2 = mysqli_query($this->conn, "select `order_id` from `saasappoint_bookings` where `business_id`='".$businessid."';");
		if(mysqli_num_rows($res2)>0){
			while($val2 = mysqli_fetch_array($res2)){
				mysqli_query($this->conn, "delete from `saasappoint_appointment_feedback` where `order_id`='".$val2['order_id']."';");
				mysqli_query($this->conn, "delete from `saasappoint_customer_orderinfo` where `order_id`='".$val2['order_id']."';");
				mysqli_query($this->conn, "delete from `saasappoint_customer_referrals` where `order_id`='".$val2['order_id']."';");
				mysqli_query($this->conn, "delete from `saasappoint_payments` where `order_id`='".$val2['order_id']."';");
			}
			$result=mysqli_query($this->conn, "delete from `saasappoint_bookings` where `business_id`='".$businessid."';");
		}
		
		$result=mysqli_query($this->conn, "delete from `saasappoint_addons` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_admins` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_block_off` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_businesses` where `id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_categories` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_coupons` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_feedback` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_frequently_discount` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_refund_request` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_schedule` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_services` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_settings` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_sms_subscriptions_history` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_subscriptions` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_subscriptions_history` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_support_tickets` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_templates` where `business_id`='".$businessid."';");
		$result=mysqli_query($this->conn, "delete from `saasappoint_used_coupons_by_customer` where `business_id`='".$businessid."';");
		return $result;
	}
	
	/* Save Business Points */
	public function save_business_points(){
		$query = "update `".$this->saasappoint_admins."` set `points` = '".$this->business_type_id."' where `business_id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change business status */
	public function change_business_points_status(){
		$query = "update `".$this->saasappoint_admins."` set `pointstatus` = '".$this->status."' where `business_id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>