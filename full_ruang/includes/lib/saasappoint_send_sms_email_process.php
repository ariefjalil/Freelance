<?php 
	/*send SMS & Email code start **/
	$saasappoint_admin_email_notification_status = $obj_settings->get_option('saasappoint_admin_email_notification_status');
	$saasappoint_customer_email_notification_status = $obj_settings->get_option('saasappoint_customer_email_notification_status');
	$saasappoint_admin_sms_notification_status = $obj_settings->get_option('saasappoint_admin_sms_notification_status');
	$saasappoint_customer_sms_notification_status = $obj_settings->get_option('saasappoint_customer_sms_notification_status');
	
	if($saasappoint_admin_email_notification_status == "Y" || $saasappoint_customer_email_notification_status == "Y" || $saasappoint_admin_sms_notification_status == "Y" || $saasappoint_customer_sms_notification_status == "Y"){
	
		$admin_email_sms_template_data = $obj_es_information->get_email_template($es_template, "admin");
		$customer_email_sms_template_data = $obj_es_information->get_email_template($es_template, "customer");
		
		$admin_email_sms_template_row_count = mysqli_num_rows($admin_email_sms_template_data);
		$customer_email_sms_template_row_count = mysqli_num_rows($customer_email_sms_template_data);
		
		if($admin_email_sms_template_row_count>0 || $customer_email_sms_template_row_count>0){
			
			/** Get detail for email **/
			$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
			$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
			$time_format = $obj_settings->get_option('saasappoint_time_format');
			if($time_format == "24"){
				$saasappoint_time_format = "H:i";
			}else{
				$saasappoint_time_format = "h:i A";
			}
		
			$obj_es_information->category_id = $es_category_id;
			$obj_es_information->service_id = $es_service_id;

			$category_title = $obj_es_information->readone_category_name();
			$service_title = $obj_es_information->readone_service_name();
			$booking_date = date($saasappoint_date_format, strtotime($es_booking_datetime));
			$booking_time = date($saasappoint_time_format, strtotime($es_booking_datetime));
			
			$payment_method = $es_payment_method;
			$payment_date = date("Y-m-d");
			$transaction_id = "-";
			if($es_transaction_id != ""){ $transaction_id = $es_transaction_id; }
			$sub_total = $saasappoint_currency_symbol.$es_subtotal;
			$coupon_discount = $saasappoint_currency_symbol.$es_coupondiscount;
			$frequently_discount = $saasappoint_currency_symbol.$es_freqdiscount;
			$tax = $saasappoint_currency_symbol.$es_tax;
			$net_total = $saasappoint_currency_symbol.$es_nettotal;
		
			$customer_name = ucwords(filter_var($es_firstname, FILTER_SANITIZE_STRING)." ".filter_var($es_lastname, FILTER_SANITIZE_STRING));
			$customer_email = trim(strip_tags(mysqli_real_escape_string($conn, $es_email)));
			$customer_phone = $es_phone;
			$customer_address = filter_var($es_address, FILTER_SANITIZE_STRING).", ".filter_var($es_city, FILTER_SANITIZE_STRING).", ".filter_var($es_state, FILTER_SANITIZE_STRING).", ".filter_var($es_country, FILTER_SANITIZE_STRING)." - ".filter_var($es_zip, FILTER_SANITIZE_STRING);
			
			$admin_email = $obj_es_information->get_admin_email();
			$admin_name = $obj_es_information->get_admin_name();
			$company_name = $obj_settings->get_option('saasappoint_company_name');
			$company_email = $obj_settings->get_option('saasappoint_company_email');
			$company_phone = $obj_settings->get_option('saasappoint_company_phone');
			$company_address = $obj_settings->get_option('saasappoint_company_address').", ".$obj_settings->get_option('saasappoint_company_city').", ".$obj_settings->get_option('saasappoint_company_state').", ".$obj_settings->get_option('saasappoint_company_country')." - ".$obj_settings->get_option('saasappoint_company_zip');
			$company_logo = $obj_settings->get_option('saasappoint_company_logo');
			
			$addons_detail = '';
			$flag = true;
			foreach($es_addons_items_arr as $addon){
				$obj_es_information->addon_id = $addon['id'];
				$addon_name = $obj_es_information->get_addon_name();
				if($flag){
					$addons_detail .= $addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
					$flag = false;
				}else{
					$addons_detail .= "<br/>".$addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
				}
			}
			
			$tags_array = array('{{{category}}}', '{{{service}}}', '{{{addons}}}', '{{{booking_date}}}', '{{{booking_time}}}', '{{{payment_method}}}', '{{{payment_date}}}', '{{{transaction_id}}}', '{{{sub_total}}}', '{{{coupon_discount}}}', '{{{frequently_discount}}}', '{{{tax}}}', '{{{net_total}}}', '{{{customer_name}}}', '{{{customer_email}}}', '{{{customer_phone}}}', '{{{customer_address}}}', '{{{admin_name}}}', '{{{company_name}}}', '{{{company_email}}}', '{{{company_phone}}}', '{{{company_address}}}', '{{{company_logo}}}', '{{{reschedule_reason}}}', '{{{reject_reason}}}', '{{{cancel_reason}}}');

			$replace_array = array($category_title, $service_title, $addons_detail, $booking_date, $booking_time, $payment_method, $payment_date, $transaction_id, $sub_total, $coupon_discount, $frequently_discount, $tax, $net_total, $customer_name, $customer_email, $customer_phone, $customer_address, $admin_name, $company_name, $company_email, $company_phone, $company_address, $company_logo, $reschedule_reason, $reject_reason, $cancel_reason);
		
			$saasappoint_email_sender_name = $obj_settings->get_option('saasappoint_email_sender_name');
			$saasappoint_email_sender_email = $obj_settings->get_option('saasappoint_email_sender_email');
			$saasappoint_email_smtp_hostname = $obj_settings->get_superadmin_option("saasappoint_email_smtp_hostname");
			$saasappoint_email_smtp_username = $obj_settings->get_superadmin_option("saasappoint_email_smtp_username");
			$saasappoint_email_smtp_password = $obj_settings->get_superadmin_option("saasappoint_email_smtp_password");
			$saasappoint_email_smtp_port = $obj_settings->get_superadmin_option("saasappoint_email_smtp_port");
			$saasappoint_email_encryption_type = $obj_settings->get_superadmin_option("saasappoint_email_encryption_type");
			$saasappoint_email_smtp_authentication = $obj_settings->get_superadmin_option("saasappoint_email_smtp_authentication");
			
			include(dirname(dirname(dirname(__FILE__)))."/classes/class.phpmailer.php");
			include(dirname(dirname(dirname(__FILE__))).'/includes/sms/twilio/Twilio.php');
			include(dirname(dirname(dirname(__FILE__))).'/includes/sms/plivo/saasappoint_plivo.php');
			include(dirname(dirname(dirname(__FILE__))).'/includes/sms/nexmo/saasappoint_nexmo.php');
			include(dirname(dirname(dirname(__FILE__))).'/includes/sms/textlocal/saasappoint_textlocal.php');
			
			/********************* Admin Email & SMS ************************/
			if($admin_email_sms_template_row_count>0){
				$admin_email_sms_template = mysqli_fetch_array($admin_email_sms_template_data);
				
				/* Admin Email */
				if($saasappoint_admin_email_notification_status == "Y"){
					$saasappoint_admin_email_template = $admin_email_sms_template["email_content"];
					$admin_template = base64_decode($saasappoint_admin_email_template);
					$admin_email_body = str_replace($tags_array,$replace_array,$admin_template);
					
					/* Send Mail code start here */
					try {
						$amail = new saasappoint_phpmailer();
						if($saasappoint_email_smtp_hostname != '' && $saasappoint_email_sender_name != '' && $saasappoint_email_sender_email != '' && $saasappoint_email_smtp_username != '' && $saasappoint_email_smtp_password != '' && $saasappoint_email_smtp_port != ''){
							$amail->IsSMTP();
							$amail->Host = $saasappoint_email_smtp_hostname;
							$amail->SMTPAuth = $saasappoint_email_smtp_authentication;
							$amail->Username = $saasappoint_email_smtp_username;
							$amail->Password = $saasappoint_email_smtp_password;
							$amail->SMTPSecure = $saasappoint_email_encryption_type;
							$amail->Port = $saasappoint_email_smtp_port;
						}else{
							$amail->IsMail();
						}
						$amail->IsHTML(true);
						$amail->SMTPDebug  = 0;
						$amail->From = $saasappoint_email_sender_email;
						$amail->FromName = $saasappoint_email_sender_name;
						$amail->Sender = $saasappoint_email_sender_email;
						$amail->Subject = $admin_email_sms_template["subject"];
						$amail->Body = $admin_email_body;
						$amail->AddAddress($admin_email, $admin_name);
						$amail->Send();
					} catch (phpmailerException $e) { }
				}
				
				/* Admin SMS */
				if($saasappoint_admin_sms_notification_status == "Y"){
					$saasappoint_admin_sms_template = $admin_email_sms_template["sms_content"];
					$admin_smstemplate = base64_decode($saasappoint_admin_sms_template);
					$admin_sms_body = str_replace($tags_array,$replace_array,$admin_smstemplate);
					
					/** Send SMS using Twilio **/
					if($obj_settings->get_superadmin_option("saasappoint_twilio_sms_status") == "Y"){
						$saasappoint_twilio_account_SID = $obj_settings->get_superadmin_option("saasappoint_twilio_account_SID");
						$saasappoint_twilio_auth_token = $obj_settings->get_superadmin_option("saasappoint_twilio_auth_token");
						$saasappoint_twilio_sender_number = $obj_settings->get_superadmin_option("saasappoint_twilio_sender_number");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_twilio_account_SID != "" && $saasappoint_twilio_auth_token != "" && $saasappoint_twilio_sender_number != ""){
							try {
								$twilio_obj = new Services_Twilio($saasappoint_twilio_account_SID, $saasappoint_twilio_auth_token);
								$response = $twilio_obj->account->messages->create(array(
									"From" => $saasappoint_twilio_sender_number,
									"To" => $company_phone,
									"Body" => $admin_sms_body));
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using Plivo **/
					if($obj_settings->get_superadmin_option("saasappoint_plivo_sms_status") == "Y"){
						$saasappoint_plivo_account_SID = $obj_settings->get_superadmin_option("saasappoint_plivo_account_SID");
						$saasappoint_plivo_auth_token = $obj_settings->get_superadmin_option("saasappoint_plivo_auth_token");
						$saasappoint_plivo_sender_number = $obj_settings->get_superadmin_option("saasappoint_plivo_sender_number");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_plivo_account_SID != "" && $saasappoint_plivo_auth_token != "" && $saasappoint_plivo_sender_number != ""){
							try {
								$plivo_obj = new saasappoint_Plivo\RestAPI($saasappoint_plivo_account_SID, $saasappoint_plivo_auth_token, '', '');
								$params = array(
									'src' => $saasappoint_plivo_sender_number,
									'dst' => $company_phone,
									'text' => $admin_sms_body,
									'method' => 'POST'
								);
								$response = $plivo_obj->send_message($params);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using Nexmo **/
					if($obj_settings->get_superadmin_option("saasappoint_nexmo_sms_status") == "Y"){
						$saasappoint_nexmo_api_key = $obj_settings->get_superadmin_option("saasappoint_nexmo_api_key");
						$saasappoint_nexmo_api_secret = $obj_settings->get_superadmin_option("saasappoint_nexmo_api_secret");
						$saasappoint_nexmo_from = $obj_settings->get_superadmin_option("saasappoint_nexmo_from");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_nexmo_api_key != "" && $saasappoint_nexmo_api_secret != "" && $saasappoint_nexmo_from != ""){
							try {
								$nexmo_obj = new saasappoint_nexmo();
								$response = $nexmo_obj->saasappoint_send_nexmo_sms($company_phone,$admin_sms_body,$saasappoint_nexmo_api_key,$saasappoint_nexmo_api_secret,$saasappoint_nexmo_from);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using TextLocal **/
					if($obj_settings->get_superadmin_option("saasappoint_textlocal_sms_status") == "Y"){
						$saasappoint_textlocal_api_key = $obj_settings->get_superadmin_option("saasappoint_textlocal_api_key");
						$saasappoint_textlocal_sender = $obj_settings->get_superadmin_option("saasappoint_textlocal_sender");
						$saasappoint_textlocal_country = $obj_settings->get_superadmin_option("saasappoint_textlocal_country");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_textlocal_api_key != "" && $saasappoint_nexmo_api_secret != "" && $saasappoint_textlocal_sender != ""){
							try {
								$textlocal_obj = new saasappoint_textlocal();
								$response = $textlocal_obj->saasappoint_send_textlocal_sms($company_phone,$admin_sms_body,$saasappoint_textlocal_api_key,$saasappoint_textlocal_country,$saasappoint_textlocal_sender);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
				}
			}
			
			/********************* Customer Email & SMS ************************/
			if($customer_email_sms_template_row_count>0){
				$customer_email_sms_template = mysqli_fetch_array($customer_email_sms_template_data);
				
				/* Customer Email */
				if($saasappoint_customer_email_notification_status == "Y"){					
					$saasappoint_customer_email_template = $customer_email_sms_template["email_content"];
					$customer_template = base64_decode($saasappoint_customer_email_template);
					$customer_email_body = str_replace($tags_array,$replace_array,$admin_template);
					
					/* Send Mail code start here */
					try {
						$cmail = new saasappoint_phpmailer();
						if($saasappoint_email_smtp_hostname != '' && $saasappoint_email_sender_name != '' && $saasappoint_email_sender_email != '' && $saasappoint_email_smtp_username != '' && $saasappoint_email_smtp_password != '' && $saasappoint_email_smtp_port != ''){
							$cmail->IsSMTP();
							$cmail->Host = $saasappoint_email_smtp_hostname;
							$cmail->SMTPAuth = $saasappoint_email_smtp_authentication;
							$cmail->Username = $saasappoint_email_smtp_username;
							$cmail->Password = $saasappoint_email_smtp_password;
							$cmail->SMTPSecure = $saasappoint_email_encryption_type;
							$cmail->Port = $saasappoint_email_smtp_port;
						}else{
							$cmail->IsMail();
						}
						$cmail->IsHTML(true);
						$cmail->SMTPDebug  = 0;
						$cmail->From = $saasappoint_email_sender_email;
						$cmail->FromName = $saasappoint_email_sender_name;
						$cmail->Sender = $saasappoint_email_sender_email;
						$cmail->Subject = $customer_email_sms_template["subject"];
						$cmail->Body = $customer_email_body;
						$cmail->AddAddress($customer_email, $customer_name);
						$cmail->Send();
					} catch (phpmailerException $e) { }
				}
				
				/* Customer SMS */
				if($saasappoint_customer_sms_notification_status == "Y"){
					$saasappoint_customer_sms_template = $customer_email_sms_template["sms_content"];
					$customer_smstemplate = base64_decode($saasappoint_customer_sms_template);
					$customer_sms_body = str_replace($tags_array,$replace_array,$customer_smstemplate);
					
					/** Send SMS using Twilio **/
					if($obj_settings->get_superadmin_option("saasappoint_twilio_sms_status") == "Y"){
						$saasappoint_twilio_account_SID = $obj_settings->get_superadmin_option("saasappoint_twilio_account_SID");
						$saasappoint_twilio_auth_token = $obj_settings->get_superadmin_option("saasappoint_twilio_auth_token");
						$saasappoint_twilio_sender_number = $obj_settings->get_superadmin_option("saasappoint_twilio_sender_number");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_twilio_account_SID != "" && $saasappoint_twilio_auth_token != "" && $saasappoint_twilio_sender_number != ""){
							try {
								$twilio_obj = new Services_Twilio($saasappoint_twilio_account_SID, $saasappoint_twilio_auth_token);
								$response = $twilio_obj->account->messages->create(array(
									"From" => $saasappoint_twilio_sender_number,
									"To" => $customer_phone,
									"Body" => $customer_sms_body));
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using Plivo **/
					if($obj_settings->get_superadmin_option("saasappoint_plivo_sms_status") == "Y"){
						$saasappoint_plivo_account_SID = $obj_settings->get_superadmin_option("saasappoint_plivo_account_SID");
						$saasappoint_plivo_auth_token = $obj_settings->get_superadmin_option("saasappoint_plivo_auth_token");
						$saasappoint_plivo_sender_number = $obj_settings->get_superadmin_option("saasappoint_plivo_sender_number");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_plivo_account_SID != "" && $saasappoint_plivo_auth_token != "" && $saasappoint_plivo_sender_number != ""){
							try {
								$plivo_obj = new saasappoint_Plivo\RestAPI($saasappoint_plivo_account_SID, $saasappoint_plivo_auth_token, '', '');
								$params = array(
									'src' => $saasappoint_plivo_sender_number,
									'dst' => $customer_phone,
									'text' => $customer_sms_body,
									'method' => 'POST'
								);
								$response = $plivo_obj->send_message($params);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using Nexmo **/
					if($obj_settings->get_superadmin_option("saasappoint_nexmo_sms_status") == "Y"){
						$saasappoint_nexmo_api_key = $obj_settings->get_superadmin_option("saasappoint_nexmo_api_key");
						$saasappoint_nexmo_api_secret = $obj_settings->get_superadmin_option("saasappoint_nexmo_api_secret");
						$saasappoint_nexmo_from = $obj_settings->get_superadmin_option("saasappoint_nexmo_from");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_nexmo_api_key != "" && $saasappoint_nexmo_api_secret != "" && $saasappoint_nexmo_from != ""){
							try {
								$nexmo_obj = new saasappoint_nexmo();
								$response = $nexmo_obj->saasappoint_send_nexmo_sms($customer_phone,$customer_sms_body,$saasappoint_nexmo_api_key,$saasappoint_nexmo_api_secret,$saasappoint_nexmo_from);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
					
					/** Send SMS using TextLocal **/
					if($obj_settings->get_superadmin_option("saasappoint_textlocal_sms_status") == "Y"){
						$saasappoint_textlocal_api_key = $obj_settings->get_superadmin_option("saasappoint_textlocal_api_key");
						$saasappoint_textlocal_sender = $obj_settings->get_superadmin_option("saasappoint_textlocal_sender");
						$saasappoint_textlocal_country = $obj_settings->get_superadmin_option("saasappoint_textlocal_country");
						$saasappoint_sms_credit = $obj_settings->get_option("saasappoint_sms_credit");
						
						if($saasappoint_sms_credit>0 && $saasappoint_textlocal_api_key != "" && $saasappoint_nexmo_api_secret != "" && $saasappoint_textlocal_sender != ""){
							try {
								$textlocal_obj = new saasappoint_textlocal();
								$response = $textlocal_obj->saasappoint_send_textlocal_sms($customer_phone,$customer_sms_body,$saasappoint_textlocal_api_key,$saasappoint_textlocal_country,$saasappoint_textlocal_sender);
								if($response){
									$updated_sms_credit = ($saasappoint_sms_credit-1);
									$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
								}
							} catch (phpmailerException $e) { }
						}
					}
				}
			}
		}
	}
	/*send SMS & Email code end **/