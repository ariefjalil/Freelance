<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_slots.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_bookings = new saasappoint_bookings();
$obj_bookings->conn = $conn;
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$obj_addons = new saasappoint_addons();
$obj_addons->conn = $conn;
$obj_slots = new saasappoint_slots();
$obj_slots->conn = $conn;

/* Get appointment detail from order id ajax */
if(isset($_POST['get_appointment_detail'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();
	
	$appointment_datetime = $appointment_detail['booking_datetime'];
	$appointment_status = $appointment_detail['booking_status'];
	
	$obj_settings->business_id = $appointment_detail['business_id'];
	$saasappoint_reschedule_buffer_time = $obj_settings->get_option("saasappoint_reschedule_buffer_time");
	$saasappoint_cancellation_buffer_time = $obj_settings->get_option("saasappoint_cancellation_buffer_time");
	
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	$current_time = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	
	$reschedule_time = strtotime(date("Y-m-d H:i:s", strtotime("+".$saasappoint_reschedule_buffer_time." minutes", strtotime($appointment_datetime))));
	$is_reschedule = "Y";
	if($current_time > $reschedule_time){
		$is_reschedule = "N";
	}
	
	$cancellation_time = strtotime(date("Y-m-d H:i:s", strtotime("+".$saasappoint_cancellation_buffer_time." minutes", strtotime($appointment_datetime))));
	$is_cancellation = "Y";
	if($current_time > $cancellation_time){
		$is_cancellation = "N";
	}
	$counter_i = 0;
	?>
	<div class="saasappoint-tabbable-panel">
		<div class="saasappoint-tabbable-line">
			<ul class="nav nav-tabs">
			  <li class="nav-item active custom-nav-item">
				<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_appointment_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-calendar-check-o"></i> Appointment Detail</a>
			  </li>
			  <?php $counter_i++; ?>
			  <li class="nav-item custom-nav-item">
				<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_payment_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-money"></i> Payment Detail</a>
			  </li>
			  <?php $counter_i++; ?>
			  <li class="nav-item custom-nav-item">
				<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_customer_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-user"></i> Customer Detail</a>
			  </li>
			  <?php $counter_i++; ?>
			  <?php 
			  if($is_reschedule == "Y"){ 
				  ?>
				  <li class="nav-item custom-nav-item <?php if($appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "completed"){ echo "saasappoint-hide"; } ?>">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_reschedule_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-pencil"></i> Reschedule Appointment</a>
				  </li>
				  <?php 
				  $counter_i++;
			  }
			  if($is_cancellation == "Y"){ 
				  ?>
				  <li class="nav-item custom-nav-item <?php if($appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "completed"){ echo "saasappoint-hide"; } ?>">
					<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_reject_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-ban"></i> Cancel Appointment</a>
				  </li>
				  <?php 
				  $counter_i++;
			  } 
			  ?>
			  <li class="nav-item custom-nav-item <?php if($appointment_status != "cancelled_by_customer" && $appointment_status != "completed"){ echo "saasappoint-hide"; } ?>">
				<a class="nav-link custom-nav-link saasappoint_tab_view_nav_link saasappoint_feedback_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-star"></i> Rating & Review</a>
			  </li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane container" id="saasappoint_appointment_detail">
				  
				</div>
				<div class="tab-pane container" id="saasappoint_payment_detail">
				  
				</div>
				<div class="tab-pane container" id="saasappoint_customer_detail">
				  
				</div>
				<?php 
				if($is_reschedule == "Y"){ 
					?>
					<div class="tab-pane container" id="saasappoint_reschedule_appointment">
					  
					</div>
					<?php 
				} 
				if($is_cancellation == "Y"){ 
					?>
					<div class="tab-pane container" id="saasappoint_reject_appointment">
					  
					</div>
					<?php 
				} 
				?>
				<div class="tab-pane container" id="saasappoint_feedback_appointment">
			  
				</div>
		  </div>
		</div>
	</div>
	<?php
}

/* Get Appointment Detail tab */
else if(isset($_POST['appointment_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_appointment_detail_tab();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){			
			$obj_settings->business_id = $appt['business_id'];
			$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
			$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
			$time_format = $obj_settings->get_option('saasappoint_time_format');
			if($time_format == "24"){
				$saasappoint_time_format = "H:i";
			}else{
				$saasappoint_time_format = "h:i A";
			}
			
			$flag = true;
			$addons_detail = '';
			$unserialized_addons = unserialize($appt['addons']);
			foreach($unserialized_addons as $addon){
				$obj_addons->id = $addon['id'];
				$addon_name = $obj_addons->get_addon_name();
				if($flag){
					$addons_detail .= $addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
					$flag = false;
				}else{
					$addons_detail .= "<br/>".$addon['qty']." ".$addon_name." of ".$saasappoint_currency_symbol.$addon['rate'];
				}
			}
			
			$booking_datetime = date($saasappoint_date_format, strtotime($appt['booking_datetime']))." ".date($saasappoint_time_format, strtotime($appt['booking_datetime']));
			
			$booking_end_datetime = date($saasappoint_date_format, strtotime($appt['booking_end_datetime']))." ".date($saasappoint_time_format, strtotime($appt['booking_end_datetime']));
			
			$category_name = ucwords($appt['cat_name']);
			$service_name = ucwords($appt['title']);

			if (strpos($appt['booking_status'], 'you') !== false) {
				$booking_status = strtoupper(str_replace('you', 'customer', str_replace('_', ' ', $appt['booking_status'])));
			}else if (strpos($appt['booking_status'], 'customer') !== false) {
				$booking_status = strtoupper(str_replace('customer', 'you', str_replace('_', ' ', $appt['booking_status'])));
			}else {
				$booking_status = strtoupper(str_replace('_', ' ', $appt['booking_status']));
			} 
			?>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Appointment Starts:</b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_datetime; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Appointment Ends:</b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_end_datetime; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Status:</b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_status; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Category:</b>
				</div>
				<div class="col-md-9">
					<?php echo $category_name; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Service:</b>
				</div>
				<div class="col-md-9">
					<?php echo $service_name; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Addons:</b>
				</div>
				<div class="col-md-9">
					<?php echo $addons_detail; ?>
				</div>
			  </div>
			<?php
		}
	}
}

/* Get Payment Detail tab */
else if(isset($_POST['payment_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_payment_detail_tab();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){	
			$obj_settings->business_id = $appt['business_id'];
			$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
			$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
			
			$payment_method = ucwords($appt['payment_method']);
			$transaction_id = $appt['transaction_id'];
			$payment_date = date($saasappoint_date_format, strtotime($appt['payment_date']));
			$sub_total = $saasappoint_currency_symbol.$appt['sub_total'];
			$discount = $saasappoint_currency_symbol.$appt['discount'];
			$refer_discount = $saasappoint_currency_symbol.$appt['refer_discount'];
			$tax = $saasappoint_currency_symbol.$appt['tax'];
			$net_total = $saasappoint_currency_symbol.$appt['net_total'];
			$fd_key = strtoupper($appt['fd_key']);
			$fd_amount = $saasappoint_currency_symbol.$appt['fd_amount'];
			?>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Payment Method:</b>
				</div>
				<div class="col-md-9">
					<?php echo $payment_method; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Payment Date:</b>
				</div>
				<div class="col-md-9">
					<?php echo $payment_date; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Transaction ID:</b>
				</div>
				<div class="col-md-9">
					<?php if($transaction_id != ""){ echo $transaction_id; }else{ echo "-"; } ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Sub Total:</b>
				</div>
				<div class="col-md-9">
					<?php echo $sub_total; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Coupon Discount:</b>
				</div>
				<div class="col-md-9">
					<?php echo $discount; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Referral Discount:</b>
				</div>
				<div class="col-md-9">
					<?php echo $refer_discount; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Frequently Discount:</b>
				</div>
				<div class="col-md-9">
					<?php if($fd_key != ""){ echo $fd_key." - ".$fd_amount; }else{ echo "-"; } ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Tax:</b>
				</div>
				<div class="col-md-9">
					<?php echo $tax; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Net Total:</b>
				</div>
				<div class="col-md-9">
					<?php echo $net_total; ?>
				</div>
			  </div>
			<?php
		}
	}
}

/* Get Customer Detail tab */
else if(isset($_POST['customer_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_customer_detail_tab();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){	
			$customer_name = ucwords($appt['c_firstname']." ".$appt['c_lastname']);
			$customer_phone = $appt['c_phone'];
			$customer_email = $appt['c_email'];
			$customer_address = $appt['c_address'];
			$customer_city = $appt['c_city'];
			$customer_state = $appt['c_state'];
			$customer_country = $appt['c_country'];
			$customer_zip = $appt['c_zip'];
			?>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Name:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_name; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Email:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_email; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Phone:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_phone; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Address:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_address; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>City:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_city; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>State:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_state; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Country:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_country; ?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Zip:</b>
				</div>
				<div class="col-md-10">
					<?php echo $customer_zip; ?>
				</div>
			  </div>
			<?php
		}
	}
}

/* Get Reschedule Appointment detail tab */
else if(isset($_POST['saasappoint_reschedule_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reschedule_appointment_detail();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			$service_id = $appt['service_id'];
			$obj_settings->business_id = $appt['business_id'];
			$saasappoint_endtimeslot_selection_status = $obj_settings->get_option('saasappoint_endtimeslot_selection_status');
			$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
			$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
			$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
			$time_format = $obj_settings->get_option('saasappoint_time_format');
			if($time_format == "24"){
				$saasappoint_time_format = "H:i";
			}else{
				$saasappoint_time_format = "h:i A";
			}
			$booking_datetime = $appt['booking_datetime'];
			$booking_date = date("Y-m-d", strtotime($booking_datetime));
			$booking_time = date("H:i:s", strtotime($booking_datetime));
			
			$booking_end_datetime = $appt['booking_end_datetime'];
			$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));
			$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
			
			$reschedule_reason = $appt['reschedule_reason']; 
			$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
			$saasappoint_server_timezone = date_default_timezone_get();
			$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
			?>
			<input type="hidden" name="saasappoint_appt_rs_sid" id="saasappoint_appt_rs_sid" value="<?php echo $service_id; ?>" />
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Date & Time:</b>
				</div>
				<div class="col-md-4">
					<input class="form-control" id="saasappoint_appt_rs_date" name="saasappoint_appt_rs_date" type="date" data-datetime="<?php echo $booking_datetime; ?>" data-oid="<?php echo $order_id; ?>" value="<?php echo $booking_date; ?>" required />
				</div>
			  </div>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Start Time:</b>
				</div>
				<div class="col-md-4">
					<select class="form-control saasappoint_appt_rs_timeslot">
						<?php 
						
						$selected_date = date("Y-m-d", strtotime($booking_date));
						$selected_date = date($selected_date, $currDateTime_withTZ);
						$current_date = date("Y-m-d", $currDateTime_withTZ);
	
						if(strtotime($selected_date)<strtotime($current_date)){
							$bdate = date("Y-m-d", strtotime($booking_datetime));
							if($bdate == $selected_date){ 
								?>
								<option value="<?php echo $booking_time; ?>" selected>
									<?php echo date($saasappoint_time_format,strtotime($booking_datetime)); ?>
								</option>
								<?php 
							}else{
								/** No slots for previous dates booking **/
							}
						}else{
							$isEndTime = false;
							$obj_slots->business_id = $appt['business_id'];
							$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime,$service_id);
							
							$j = 0;
							$i = 1;
							if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0)
							{
								foreach($available_slots['slots'] as $slot) 
								{
									$booked_slot_exist = false;
									foreach($available_slots['booked'] as $bslot){
										if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot)){
											$booked_slot_exist = true;
											continue;
										} 
									}
									if($booked_slot_exist){
										if($booking_time != $slot){
											continue;
										}
									}
									
									$blockoff_exist = false;
									if(sizeof($available_slots['block_off'])>0){
										foreach($available_slots['block_off'] as $block_off){
											if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
												$blockoff_exist = true;
												continue;
											} 
										}
									} 
									if($blockoff_exist){
										continue;
									} 
									?>
									<option value="<?php echo $slot; ?>" <?php if($booking_time == $slot){ echo "selected"; } ?>>
										<?php echo date($saasappoint_time_format,strtotime($booking_date." ".$slot)); ?>
									</option>
									<?php
								}
							}
						} 
						?>
					</select>
				</div>
			  </div>
			  <?php 
			  if($saasappoint_endtimeslot_selection_status == "Y"){
				  ?>
				  <div class="row saasappoint-mb-5">
					<div class="col-md-3">
						<b>End Time:</b>
					</div>
					<div class="col-md-4">
						<select class="form-control saasappoint_appt_rs_endtimeslot">
							<?php 
							$selected_enddate = date("Y-m-d", strtotime($booking_enddate));
							$selected_enddate = date($selected_enddate, $currDateTime_withTZ);
							$current_date = date("Y-m-d", $currDateTime_withTZ);
		
							if(strtotime($selected_enddate)<strtotime($current_date)){
								$bdate = date("Y-m-d", strtotime($booking_end_datetime));
								if($bdate == $selected_enddate){ 
									?>
									<option value="<?php echo $booking_endtime; ?>" selected>
										<?php echo date($saasappoint_time_format,strtotime($booking_end_datetime)); ?>
									</option>
									<?php 
								}else{
									/** No slots for previous dates booking **/
								}
							}else{
								$isEndTime = true;
								$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_enddate, $advance_bookingtime, $currDateTime_withTZ, $isEndTime,$service_id);
								
								/** check for maximum end time slot limit **/
								$saasappoint_maximum_endtimeslot_limit = $obj_settings->get_option('saasappoint_maximum_endtimeslot_limit');
								$selected_slot_check = strtotime($booking_datetime);
								$maximum_endslot_limit = date("Y-m-d H:i:s", strtotime("+".$saasappoint_maximum_endtimeslot_limit." minutes", $selected_slot_check)); 
								
								if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
									foreach($available_slots['slots'] as $slot){
										if(strtotime($selected_enddate." ".$slot) < strtotime($selected_enddate." ".$booking_endtime)){
											continue;
										}elseif(strtotime($selected_enddate." ".$slot) > strtotime($maximum_endslot_limit)){
											continue;
										}else{
											$booked_slot_exist = false;
											foreach($available_slots['booked'] as $bslot){
												if($bslot["start_time"] <= strtotime($selected_enddate." ".$slot) && $bslot["end_time"] > strtotime($selected_enddate." ".$slot)){
													$booked_slot_exist = true;
													continue;
												} 
											}
											if($booked_slot_exist){
												break;
											}else{ 
												$blockoff_exist = false;
												if(sizeof($available_slots['block_off'])>0){
													foreach($available_slots['block_off'] as $block_off){
														if((strtotime($selected_enddate." ".$block_off["start_time"]) <= strtotime($selected_enddate." ".$slot)) && (strtotime($selected_enddate." ".$block_off["end_time"]) > strtotime($selected_enddate." ".$slot))){
															$blockoff_exist = true;
															continue;
														} 
													}
												} 
												if($blockoff_exist){
													break;
												} 
												?>
												<option value="<?php echo $slot; ?>" <?php if($booking_endtime == $slot){ echo "selected"; } ?>>
													<?php echo date($saasappoint_time_format,strtotime($booking_enddate." ".$slot)); ?>
												</option>
												<?php
												$j++;
											}
										}
										$i++;
									}
									if($j == 0){ 
										if(is_numeric($service_id) && $service_id != "0"){
											$time_interval=$obj_slots->get_service_time_interval($service_id,$time_interval);
										}
										$sdate_stime = strtotime($selected_enddate." ".$booking_time);
										$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
										$sdate_estime = date("H:i:s", strtotime($sdate_etime)); 
										?>
										<option value="<?php echo $sdate_estime; ?>" selected>
											<?php echo date($saasappoint_time_format,strtotime($sdate_etime)); ?>
										</option>
										<?php
									}
								}
							} 
							?>
						</select>
					</div>
				  </div>
				<?php 
			  } else{ 
				  ?>
				  <input type="hidden" name="saasappoint_appt_rs_endtimeslot" class="saasappoint_appt_rs_endtimeslot saasappoint_appt_rs_endtimeslot_input" value="<?php echo $booking_endtime; ?>" />
				  <?php 
			  } 
			  ?>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Reschedule Reason:</b>
				</div>
				<div class="col-md-9">
					<textarea class="form-control" id="saasappoint_appt_rs_reason" name="saasappoint_appt_rs_reason" placeholder="Enter reschedule reason"><?php echo $reschedule_reason; ?></textarea>
				</div>
			  </div>
			  <div class="row saasappoint-mt-20">
				<div class="col-md-12">
					<a href="javascript:void(0)" data-id="<?php echo $order_id; ?>" class="btn btn-success saasappoint-fullwidth saasappoint_appt_rs_now_btn">Reschedule Now</a>
				</div>
			  </div>
			<?php
		}
	}
}

/* Get Reject Appointment detail tab */
else if(isset($_POST['saasappoint_reject_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reject_appointment_detail();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			$reject_reason = $appt['reject_reason']; 
			?>
			  <div class="row saasappoint-mb-5">
				<div class="col-md-3">
					<b>Cancellation Reason:</b>
				</div>
				<div class="col-md-9">
					<textarea class="form-control" id="saasappoint_appt_reject_reason" name="saasappoint_appt_reject_reason" placeholder="Enter reject reason"><?php echo $reject_reason; ?></textarea>
				</div>
			  </div>
			  <div class="row saasappoint-mt-20">
				<div class="col-md-12">
					<a href="javascript:void(0)" data-id="<?php echo $order_id; ?>" class="btn btn-danger saasappoint-fullwidth saasappoint_appt_reject_now_btn">Cancel Now</a>
				</div>
			  </div>
			  <?php 
			  $obj_settings->business_id = $appt['business_id'];
			  if($obj_settings->get_option("saasappoint_refund_status") == "Y"){ 
				$saasappoint_refund_request_buffer_time = $obj_settings->get_option("saasappoint_refund_request_buffer_time");
				$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
				$saasappoint_server_timezone = date_default_timezone_get();
				$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

				$cdate = date("Y-m-d H:i:s", $currDateTime_withTZ);
				$bdate = date("Y-m-d H:i:s", strtotime("-".$saasappoint_refund_request_buffer_time." minutes", strtotime($appt['booking_datetime']))); 
				?>
				<hr />
                <div class="row mt-3">
					<div class="col-md-12">
						<?php 
						$saasappoint_refund_summary = base64_decode($obj_settings->get_option("saasappoint_refund_summary"));
						if(strtotime($cdate)<strtotime($bdate)){ 
							$saasappoint_currency_symbol = $obj_settings->get_option("saasappoint_currency_symbol");
							$saasappoint_refund_type = $obj_settings->get_option("saasappoint_refund_type");
							$saasappoint_refund_value = $obj_settings->get_option("saasappoint_refund_value");
							
							if($saasappoint_refund_type == "percentage"){
								$ramount = ($appt['net_total']*$saasappoint_refund_value/100);
							}else{
								$ramount = $saasappoint_refund_value;
							}
							$ramount = number_format($ramount,2,".",',');
							if($ramount < $appt['net_total']){
								echo "<h5><i class='fa fa-check-square-o text-success'></i> You are eligible to get refund: <b>".$saasappoint_currency_symbol.$ramount."</b></h5>"; 
							}else{
								echo "<p><i class='fa fa-exclamation-triangle text-warning'></i> <span class='text-dark'>Opps! You are not eligible to get refund. Minimum net amount should be <b class='text-danger'>".$saasappoint_currency_symbol.$ramount."</b></span></p>";
							} 
							if($saasappoint_refund_summary != ""){ 
								?>
								<div id="saasappoint-refund-policy-block" class="row">
									<div class="col-md-12">
										<?php echo $saasappoint_refund_summary; ?>
									</div>
								</div>
								<?php 
							}
						}else{ 
							if($saasappoint_refund_request_buffer_time<60){
								$hours = $saasappoint_refund_request_buffer_time." minutes";
							}else if($saasappoint_refund_request_buffer_time==60){
								$hours = "1 hour";
							}else{
								$hours = floor($saasappoint_refund_request_buffer_time / 60)." hours";
							}
							echo "<p><i class='fa fa-exclamation-triangle text-warning'></i> <span class='text-dark'>Opps! You are not eligible to get refund for this appointment.</span></p>";
							echo "<p><i class='fa fa-info-circle text-dark'></i> <span class='text-dark'>You can receive a refund if you cancel at least <b>".$hours."</b> before the appointment time.</span></p>"; 
							if($saasappoint_refund_summary != ""){ 
								?>
								<div id="saasappoint-refund-policy-block" class="row">
									<div class="col-md-12">
										<?php echo $saasappoint_refund_summary; ?>
									</div>
								</div>
								<?php 
							}
						} 
						?>
						
					</div>
				</div>
				<?php 
			  }
		}
	}
}

/* Get Slots On Date change */
else if(isset($_POST['saasappoint_slots_on_date_change'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();
		
	$obj_settings->business_id = $appointment_detail['business_id'];
	$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
	$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
	$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
	$time_format = $obj_settings->get_option('saasappoint_time_format');
	if($time_format == "24"){
		$saasappoint_time_format = "H:i";
	}else{
		$saasappoint_time_format = "h:i A";
	}
		
	$booking_datetime = $_POST['booking_datetime'];
	$booked_time = date("H:i:s", strtotime($booking_datetime));
	$booking_time = date("H:i:s", strtotime($booking_datetime));

	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);
	$current_date = date("Y-m-d", $currDateTime_withTZ);
	
	if(strtotime($selected_date)<strtotime($current_date)){
		$bdate = date("Y-m-d", strtotime($booking_datetime));
		if($bdate == $selected_date){ 
			?>
			<option value="<?php echo $booked_time; ?>" selected>
				<?php echo date($saasappoint_time_format,strtotime($booking_datetime)); ?>
			</option>
			<?php 
		}else{
			/** No slots for previous dates booking **/
		}
	}else{
		$isEndTime = false;
		$obj_slots->business_id = $appointment_detail['business_id'];
		$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_POST["service_id"]);
		if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0)
		{
			foreach($available_slots['slots'] as $slot) 
			{
				$booked_slot_exist = false;
				foreach($available_slots['booked'] as $bslot){
					if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot)){
						$booked_slot_exist = true;
						continue;
					} 
				}
				if($booked_slot_exist){
					if($booking_time != $slot){
						continue;
					}
				}
				
				$blockoff_exist = false;
				if(sizeof($available_slots['block_off'])>0){
					foreach($available_slots['block_off'] as $block_off){
						if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
							$blockoff_exist = true;
							continue;
						} 
					}
				} 
				if($blockoff_exist){
					continue;
				} 
				?>
				<option value="<?php echo $slot; ?>" <?php if(strtotime($booking_datetime) == strtotime($selected_date." ".$slot)){ echo "selected"; } ?>>
					<?php echo date($saasappoint_time_format,strtotime($selected_date." ".$slot)); ?>
				</option>
				<?php
			}
		}
	}
}

/* Reschedule Appointment Ajax */
else if(isset($_POST['reschedule_appointment_detail'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();
		
	$obj_settings->business_id = $appointment_detail['business_id'];
	$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
		
	$reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
	$booking_datetime = date("Y-m-d H:i:s", strtotime($_POST['date']." ".$_POST['slot']));
	$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['date']." ".$_POST['endslot']));
	/* $booking_end_datetime = date("Y-m-d H:i:s", strtotime('+'.$time_interval.' minutes',strtotime($booking_datetime))); */
	$obj_bookings->order_id = $order_id;
	$obj_bookings->booking_status = "rescheduled_by_customer";
	$obj_bookings->reschedule_reason = $reason;
	$obj_bookings->booking_datetime = $booking_datetime;
	$obj_bookings->booking_end_datetime = $booking_end_datetime;
	$updated = $obj_bookings->reschedule_appointment();
	if($updated){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new saasappoint_es_information();
		$obj_es_information->conn = $conn;
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
				
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			$obj_settings->business_id = $es_appt_detail['business_id'];
			$obj_es_information->business_id = $es_appt_detail['business_id'];
			$es_template = "reschedulec";
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			include("saasappoint_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "updated";
	}
}

/* Reject Appointment Ajax */
else if(isset($_POST['reject_customerappointment_detail'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reject_appointment_detail();
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			$obj_settings->business_id = $appt['business_id'];
			if($obj_settings->get_option("saasappoint_refund_status") == "Y"){ 
				$saasappoint_refund_request_buffer_time = $obj_settings->get_option("saasappoint_refund_request_buffer_time");
				$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
				$saasappoint_server_timezone = date_default_timezone_get();
				$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

				$cdate = date("Y-m-d H:i:s", $currDateTime_withTZ);
				$bdate = date("Y-m-d H:i:s", strtotime("-".$saasappoint_refund_request_buffer_time." minutes", strtotime($appt['booking_datetime']))); 
			
				if(strtotime($cdate)<strtotime($bdate)){ 
					$saasappoint_refund_type = $obj_settings->get_option("saasappoint_refund_type");
					$saasappoint_refund_value = $obj_settings->get_option("saasappoint_refund_value");
					
					if($saasappoint_refund_type == "percentage"){
						$ramount = ($appt['net_total']*$saasappoint_refund_value/100);
					}else{
						$ramount = $saasappoint_refund_value;
					}
					$ramount = number_format($ramount,2,".",',');
					if($ramount < $appt['net_total']){
						/** Insert refund request function **/
						include(dirname(dirname(dirname(__FILE__)))."/classes/class_refund_request.php");
						$obj_refund_request = new saasappoint_refund_request();
						$obj_refund_request->conn = $conn;
						$obj_refund_request->business_id = $appt['business_id'];
						$obj_refund_request->order_id = $order_id;
						$obj_refund_request->amount = $ramount;
						$obj_refund_request->requested_on = $cdate;
						$obj_refund_request->status = "pending";
						$obj_refund_request->read_status = "U";
						$obj_refund_request->add_refund_request();
					}
				}
			}
		}
	}
	$reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
	$obj_bookings->order_id = $order_id;
	$obj_bookings->booking_status = "cancelled_by_customer";
	$obj_bookings->reject_reason = $reason;
	$updated = $obj_bookings->reject_appointment();
	if($updated){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new saasappoint_es_information();
		$obj_es_information->conn = $conn;
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
				
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			$obj_settings->business_id = $es_appt_detail['business_id'];
			$obj_es_information->business_id = $es_appt_detail['business_id'];
			$es_template = "cancelc";
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			include("saasappoint_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "updated";
	}
}

/* Get Appointment Feedback Detail tab */
else if(isset($_POST['saasappoint_feedback_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$feedback_detail = $obj_bookings->get_appointment_rating($order_id);
	
	if(mysqli_num_rows($feedback_detail)>0){
		while($feedback = mysqli_fetch_assoc($feedback_detail)){			
			?>
			<div class="m-3">
			  <div class="row saasappoint-mb-5">
				<div class="col-md-2">
					<b>Rating:</b>
				</div>
				<div class="col-md-9">
					<?php 
					if($feedback['rating']>0){
						for($star_i=0;$star_i<$feedback['rating'];$star_i++){ 
							?>
							<i class="fa fa-star saasappoint_feedback_star_list" aria-hidden="true"></i>
							<?php 
						} 
						for($star_j=0;$star_j<(5-$feedback['rating']);$star_j++){ 
							?>
							<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
							<?php 
						} 
					}else{ 
						?>
						<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o saasappoint_feedback_star_list" aria-hidden="true"></i>
						<?php 
					} 
					?>
				</div>
			  </div>
			  <div class="row saasappoint-mb-5 mt-3">
				<div class="col-md-2">
					<b>Review:</b>
				</div>
				<div class="col-md-9">
					<?php echo ucfirst($feedback['review']); ?>
				</div>
			  </div>
			</div>
			<?php 
		}
	}else{ 
		?>
		<form method="post" name="saasappoint_feedback_form" id="saasappoint_feedback_form">
		  <input type="hidden" id="saasappoint_fb_rating" name="saasappoint_fb_rating" value="0" />
		  <div class="row mb-4 mt-2">
			<div class="col-md-2">
				<b>Rating:</b>
			</div>
			<div class="col-md-9">
				<span class="fa fa-star-o saasappoint-sidebar-feedback-star" id="saasappoint-sidebar-feedback-star1" onclick="saasappoint_add_star_rating(this,1)"></span>
				<span class="fa fa-star-o saasappoint-sidebar-feedback-star" id="saasappoint-sidebar-feedback-star2" onclick="saasappoint_add_star_rating(this,2)"></span>
				<span class="fa fa-star-o saasappoint-sidebar-feedback-star" id="saasappoint-sidebar-feedback-star3" onclick="saasappoint_add_star_rating(this,3)"></span>
				<span class="fa fa-star-o saasappoint-sidebar-feedback-star" id="saasappoint-sidebar-feedback-star4" onclick="saasappoint_add_star_rating(this,4)"></span>
				<span class="fa fa-star-o saasappoint-sidebar-feedback-star" id="saasappoint-sidebar-feedback-star5" onclick="saasappoint_add_star_rating(this,5)"></span>
			</div>
		  </div>
		  <div class="row mb-4">
			<div class="col-md-2">
				<b>Review:</b>
			</div>
			<div class="col-md-9">
				<textarea class="form-control" id="saasappoint_fb_review" name="saasappoint_fb_review" placeholder="Write your honest experience, and help us to make better choices"></textarea>
			</div>
		  </div>
		  <div class="row saasappoint-mt-20">
			<div class="col-md-12">
				<a href="javascript:void(0)" data-id="<?php echo $order_id; ?>" class="btn btn-success saasappoint-fullwidth saasappoint_submit_feedback_btn"><i class="fa fa-thumbs-up"></i> Submit Review</a>
			</div>
		  </div>
		</form>
		<?php 
	}
}

/** add appointment feedback ajax **/
else if(isset($_POST["add_feedback"])){
	$added = $obj_bookings->add_appointment_feedback($_POST["order_id"], $_POST["rating"], $_POST["review"]);
	if($added){
		echo "added";
	}
}

/** get end time slot ajax for reschedule **/
else if(isset($_POST['get_endtimeslots'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();
		
	$obj_settings->business_id = $appointment_detail['business_id'];
	$saasappoint_endtimeslot_selection_status = $obj_settings->get_option('saasappoint_endtimeslot_selection_status');
	$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
	$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
	$time_format = $obj_settings->get_option('saasappoint_time_format');
	$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
	if($time_format == "24"){
		$saasappoint_time_format = "H:i";
	}else{
		$saasappoint_time_format = "h:i A";
	}
	
	/** get without end time slot ajax for reschedule **/
	if($saasappoint_endtimeslot_selection_status != "Y"){
		$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST["selected_startslot"]));
		$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
		$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));	
		if(is_numeric($_POST['service_id']) && $_POST['service_id'] != "0"){
			$time_interval=$obj_slots->get_service_time_interval($_POST['service_id'],$time_interval);
		}
		$sdate_stime = strtotime($booking_end_datetime);
		$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
		$sdate_estime = date("H:i:s", strtotime($sdate_etime)); 
		echo $sdate_estime;
	}else{
		$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST["selected_startslot"]));
		$booking_datetime = $booking_end_datetime;
		$booking_date = date("Y-m-d", strtotime($booking_datetime));
		$booking_time = date("H:i:s", strtotime($booking_datetime));
		$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));
		$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
		
		$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
		$saasappoint_server_timezone = date_default_timezone_get();
		$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

		$selected_enddate = date("Y-m-d", strtotime($booking_enddate));
		$selected_enddate = date($selected_enddate, $currDateTime_withTZ);
		$current_date = date("Y-m-d", $currDateTime_withTZ);
		
		$isEndTime = true;
		$obj_slots->business_id = $appointment_detail['business_id'];
		$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_enddate, $advance_bookingtime, $currDateTime_withTZ, $isEndTime,$_POST['service_id']);
		
		/** check for maximum end time slot limit **/
		$saasappoint_maximum_endtimeslot_limit = $obj_settings->get_option('saasappoint_maximum_endtimeslot_limit');
		$selected_slot_check = strtotime($booking_end_datetime);
		$maximum_endslot_limit = date("Y-m-d H:i:s", strtotime("+".$saasappoint_maximum_endtimeslot_limit." minutes", $selected_slot_check)); 
		
		$j = 0;
		$i = 1;
		if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
			foreach($available_slots['slots'] as $slot){
				if(strtotime($selected_enddate." ".$slot) <= strtotime($selected_enddate." ".$booking_time)){
					continue;
				}elseif(strtotime($selected_enddate." ".$slot) > strtotime($maximum_endslot_limit)){
					continue;
				}else{
					$booked_slot_exist = false;
					foreach($available_slots['booked'] as $bslot){
						if($bslot["start_time"] <= strtotime($selected_enddate." ".$slot) && $bslot["end_time"] > strtotime($selected_enddate." ".$slot)){
							$booked_slot_exist = true;
							continue;
						} 
					}
					if($booked_slot_exist){
						break;
					}else{ 
						$blockoff_exist = false;
						if(sizeof($available_slots['block_off'])>0){
							foreach($available_slots['block_off'] as $block_off){
								if((strtotime($selected_enddate." ".$block_off["start_time"]) <= strtotime($selected_enddate." ".$slot)) && (strtotime($selected_enddate." ".$block_off["end_time"]) > strtotime($selected_enddate." ".$slot))){
									$blockoff_exist = true;
									continue;
								} 
							}
						} 
						if($blockoff_exist){
							break;
						} 
						?>
						<option value="<?php echo $slot; ?>" <?php if($booking_endtime == $slot){ echo "selected"; } ?>>
							<?php echo date($saasappoint_time_format,strtotime($booking_enddate." ".$slot)); ?>
						</option>
						<?php
						$j++;
					}
				}
				$i++;
			}
		}
		if($j == 0){ 
			if(is_numeric($_POST['service_id']) && $_POST['service_id'] != "0"){
				$time_interval=$obj_slots->get_service_time_interval($_POST['service_id'],$time_interval);
			}
			
			$sdate_stime = strtotime($selected_enddate." ".$booking_time);
			$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
			$sdate_estime = date("H:i:s", strtotime($sdate_etime)); 
			?>
			<option value="<?php echo $sdate_estime; ?>" selected>
				<?php echo date($saasappoint_time_format,strtotime($sdate_etime)); ?>
			</option>
			<?php
		}
	}
}