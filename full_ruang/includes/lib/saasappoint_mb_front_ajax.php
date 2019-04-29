<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_manual_booking.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_slots.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_frontend = new saasappoint_manual_booking();
$obj_frontend->conn = $conn;
$obj_frontend->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_slots = new saasappoint_slots();
$obj_slots->conn = $conn;
$obj_slots->business_id = $_SESSION['business_id'];

$time_interval = $obj_settings->get_option('saasappoint_timeslot_interval');
$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol');
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
$advance_bookingtime = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}

/* get services by category id ajax */
if(isset($_POST['get_services_by_cat_id'])){
	$obj_frontend->category_id = $_POST['id'];
	$all_services = $obj_frontend->get_services_by_cat_id();
	if(mysqli_num_rows($all_services)>0){
		$_SESSION['saasappoint_mb_cart_category_id'] = $_POST['id'];
		$_SESSION['saasappoint_mb_cart_items'] = array();
		$_SESSION['saasappoint_mb_cart_service_id'] = "";
		while($service = mysqli_fetch_array($all_services)){ 
			?>
			<div class="col-xs-12 col-md-4 saasappoint-sm-box">
				<div class="saasappoint-styled-radio">
					<input type="radio" id="saasappoint-services-radio-<?php echo $service['id']; ?>" value="<?php echo $service['id']; ?>" name="saasappoint-services-radio" class="saasappoint-services-radio-change">
					<label for="saasappoint-services-radio-<?php echo $service['id']; ?>" <?php if($service['description'] != ""){ echo ' data-toggle="tooltip" data-placement="top" title="'.$service['description'].'"'; } ?>><img src="<?php echo SITE_URL; ?>includes/images/<?php if($service['image'] != "" && file_exists("../images/".$service['image'])){ echo $service['image']; }else{ echo "no-image.jpg"; } ?>" /> <p><?php echo ucwords($service['title']); ?></p></label>
				</div>
			</div>
			<?php 
		}
	}else{
		?>
		<div class="row">
			<div class="col-xs-12 col-md-12 saasappoint-sm-box">
				<label>There is no services for this category</label>
			</div>
		</div>
		<?php
	}
}

/* get addons by service id ajax */
else if(isset($_POST['get_multiple_qty_addons_by_service_id'])){
	$obj_frontend->service_id = $_POST['id'];
	$all_addons = $obj_frontend->get_multiple_qty_addons_by_service_id(); 
	$_SESSION['saasappoint_mb_cart_service_id'] = $_POST['id'];
	$_SESSION['saasappoint_mb_cart_items'] = array();
	?>
	<div class="row">
		<?php 
		while($addon = mysqli_fetch_array($all_addons)){ 
			?>
			<div class="col-md-4 saasappoint-addons-multipleqty-box-<?php echo $addon['id']; ?>">
				<div class="saasappoint-addons-multipleqty-box-icon saasappoint_make_multipleqty_addon_selected" data-id="<?php echo $addon['id']; ?>">
					<img src="<?php echo SITE_URL; ?>includes/images/<?php if($addon['image'] != "" && file_exists("../images/".$addon['image'])){ echo $addon['image']; }else{ echo "no-image.jpg"; } ?>">
					<p><?php echo $obj_settings->get_option('saasappoint_currency_symbol').$addon['rate']; ?></p>
					<p><?php echo ucwords($addon['title']); ?></p>
				</div>
				<div class="saasappoint-addons-multipleqty-box-content">
					<div class="saasappoint-addons-multipleqty-counter saasappoint-addons-multipleqty-js-counter">
						<div class="saasappoint-addons-multipleqty-counter-item">
							<a class="saasappoint-addons-multipleqty-counter-minus saasappoint-addons-multipleqty-js-counter-btn fa fa-minus" id="saasappoint-addons-multipleqty-minus-js-counter-btn-<?php echo $addon['id']; ?>" aria-hidden="true" data-action="minus" data-id="<?php echo $addon['id']; ?>"></a>
						</div>
						<div class="saasappoint-addons-multipleqty-counter-item saasappoint-addons-multipleqty-counter-item-center">
							<input class="saasappoint-addons-multipleqty-counter__value saasappoint-addons-multipleqty-js-counter-value saasappoint-addons-multipleqty-unit-<?php echo $addon['id']; ?>" type="text" data-id="<?php echo $addon['id']; ?>" value="0" disabled="disabled" tabindex="-1" min="0" max="10" required />
						</div>
						<div class="saasappoint-addons-multipleqty-counter-item">
							<a class="saasappoint-addons-multipleqty-counter-plus saasappoint-addons-multipleqty-js-counter-btn fa fa-plus" id="saasappoint-addons-multipleqty-plus-js-counter-btn-<?php echo $addon['id']; ?>" aria-hidden="true" data-action="plus" data-id="<?php echo $addon['id']; ?>"></a>
						</div>
					</div>
				</div>
			</div>
			<?php 
		} 
		?>
	</div>
	<?php 
}

/* get addons by service id ajax */
else if(isset($_POST['get_single_qty_addons_by_service_id'])){
	$obj_frontend->service_id = $_POST['id'];
	$all_addons = $obj_frontend->get_single_qty_addons_by_service_id();
	while($addon = mysqli_fetch_array($all_addons)){ 
		?>
		<li>
			<input type="checkbox" id="saasappoint-addons-singleqty-unit-<?php echo $addon['id']; ?>" value="<?php echo $addon['id']; ?>" class="saasappoint-addons-singleqty-unit-selection" />
			<label for="saasappoint-addons-singleqty-unit-<?php echo $addon['id']; ?>"><img src="<?php echo SITE_URL; ?>includes/images/<?php if($addon['image'] != "" && file_exists("../images/".$addon['image'])){ echo $addon['image']; }else{ echo "no-image.jpg"; } ?>" /> <b><?php echo $obj_settings->get_option('saasappoint_currency_symbol').$addon['rate']; ?></b><br /><?php echo ucwords($addon['title']); ?></label>
		</li>
		<?php 
	}
}

/* get all frequently discount */
else if(isset($_POST['get_all_frequently_discount'])){
	$all_frequently_discount = $obj_frontend->get_all_frequently_discount(); 
	if(mysqli_num_rows($all_frequently_discount)>0){ 
		?>
		<div class="row">
			<?php 
			while($fd_discount = mysqli_fetch_array($all_frequently_discount)){ 
				?>
				<div class="col-md-3 saasappoint-sm-box">
					<div class="saasappoint-styled-radio saasappoint-styled-radio-second">
						<input type="radio" id="saasappoint-frequently-discount-<?php echo $fd_discount['id']; ?>" name="saasappoint-frequently-discount" class="saasappoint-frequently-discount-change" value="<?php echo $fd_discount['id']; ?>" />
						<label for="saasappoint-frequently-discount-<?php echo $fd_discount['id']; ?>" <?php if($fd_discount['fd_description'] != ""){ echo ' data-toggle="tooltip" data-placement="bottom" title="'.$fd_discount['fd_description'].'"'; } ?>><?php echo $fd_discount['fd_label']; ?></label>
					</div>
				</div>
				<?php 
			} 
		?>
		</div>
		<?php 
	}else{
		$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = "";
		$_SESSION['saasappoint_mb_cart_freqdiscount'] = 0;
		$_SESSION['saasappoint_mb_cart_freqdiscount_label'] = "";
		$_SESSION['saasappoint_mb_cart_freqdiscount_key'] = "";
	}
}

/* on change update frequently discount */
else if(isset($_POST['update_frequently_discount'])){
	$obj_frontend->frequently_discount_id = $_POST['id'];
	$fd_discount = $obj_frontend->readone_frequently_discount(); 
	
	if(is_array($fd_discount)){
		if($_SESSION['saasappoint_mb_cart_subtotal']>0){
			$new_subtotal = $_SESSION['saasappoint_mb_cart_subtotal'];
			
			if($fd_discount['fd_type'] == "percentage"){
				$percentage_value = ($new_subtotal*$fd_discount["fd_value"]/100);
				$new_nettotal = ($new_subtotal-$percentage_value);
				if($new_nettotal>0){
					$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
				}else{
					$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
				}
				$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = $_POST['id'];
				$_SESSION['saasappoint_mb_cart_freqdiscount'] = $percentage_value;
			}else{
				$new_nettotal = ($new_subtotal-$fd_discount["fd_value"]);
				if($new_nettotal>0){
					$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
				}else{
					$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
				}
				$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = $_POST["id"];
				$_SESSION['saasappoint_mb_cart_freqdiscount'] = $fd_discount["fd_value"];
			}
			$_SESSION['saasappoint_mb_cart_couponid'] = "";
			$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
			$_SESSION['saasappoint_mb_cart_freqdiscount_label'] = $fd_discount["fd_label"];
			$_SESSION['saasappoint_mb_cart_freqdiscount_key'] = $fd_discount["fd_key"];
		}else{
			$_SESSION['saasappoint_mb_cart_couponid'] = "";
			$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
			$_SESSION['saasappoint_mb_cart_freqdiscount_id'] = "";
			$_SESSION['saasappoint_mb_cart_freqdiscount'] = 0;
			$_SESSION['saasappoint_mb_cart_freqdiscount_label'] = "";
			$_SESSION['saasappoint_mb_cart_freqdiscount_key'] = "";
		}
	}
}

/* Check and apply coupon ajax */
else if(isset($_POST['apply_coupon'])){
	$obj_frontend->coupon_id = $_POST['id'];
	$coupon_detail = $obj_frontend->apply_coupon(); 
	if(is_array($coupon_detail)){
		if($_SESSION['saasappoint_mb_cart_subtotal']>0){
			if($_SESSION['saasappoint_mb_cart_freqdiscount']>0){
				$new_subtotal = ($_SESSION['saasappoint_mb_cart_subtotal']-$_SESSION['saasappoint_mb_cart_freqdiscount']);
			}else{
				$new_subtotal = $_SESSION['saasappoint_mb_cart_subtotal'];
			}
			
			if($coupon_detail['coupon_type'] == "percentage"){
				$percentage_value = ($new_subtotal*$coupon_detail["coupon_value"]/100);
				$new_nettotal = ($new_subtotal-$percentage_value);
				if($new_nettotal>0){
					$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
				}else{
					$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
				}
				$_SESSION['saasappoint_mb_cart_couponid'] = $_POST['id'];
				$_SESSION['saasappoint_mb_cart_coupondiscount'] = $percentage_value;
			}else{
				$new_nettotal = ($new_subtotal-$coupon_detail["coupon_value"]);
				if($new_nettotal>0){
					$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
				}else{
					$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
				}
				$_SESSION['saasappoint_mb_cart_couponid'] = $_POST["id"];
				$_SESSION['saasappoint_mb_cart_coupondiscount'] = $coupon_detail["coupon_value"];
			}
			echo "available";
		}else{
			$_SESSION['saasappoint_mb_cart_couponid'] = "";
			$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
		}
	}
}

/* remove applied coupon ajax */
else if(isset($_POST['remove_applied_coupon'])){
	$obj_frontend->coupon_id = $_POST['id'];
	$coupon_detail = $obj_frontend->apply_coupon(); 
	if($_SESSION['saasappoint_mb_cart_subtotal']>0){
		if($_SESSION['saasappoint_mb_cart_freqdiscount']>0){
			$new_subtotal = ($_SESSION['saasappoint_mb_cart_subtotal']-$_SESSION['saasappoint_mb_cart_freqdiscount']);
		}else{
			$new_subtotal = $_SESSION['saasappoint_mb_cart_subtotal'];
		}
		
		if($coupon_detail['coupon_type'] == "percentage"){
			$percentage_value = ($new_subtotal*$coupon_detail["coupon_value"]/100);
			$new_nettotal = ($new_subtotal+$percentage_value);
			if($new_nettotal>0){
				$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
			}else{
				$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
			}
		}else{
			$new_nettotal = ($new_subtotal+$coupon_detail["coupon_value"]);
			if($new_nettotal>0){
				$_SESSION['saasappoint_mb_cart_nettotal'] = $new_nettotal;
			}else{
				$_SESSION['saasappoint_mb_cart_nettotal'] = 0;
			}
		}
		$_SESSION['saasappoint_mb_cart_couponid'] = "";
		$_SESSION['saasappoint_mb_cart_coupondiscount'] = 0;
	}
}

/* add feedback ajax */
else if(isset($_POST['add_feedback'])){
	$obj_frontend->feedback_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$obj_frontend->feedback_email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_frontend->feedback_rating = $_POST['rating'];
	$obj_frontend->feedback_review = filter_var($_POST['review'], FILTER_SANITIZE_STRING);
	$obj_frontend->feedback_review_datetime = date("Y-m-d H:i:s");
	$added = $obj_frontend->add_feedback(); 
	if($added){
		echo "added";
	}
}

/* Get available slots ajax */
else if(isset($_POST['get_slots'])){ 
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);

	$isEndTime = false;
	$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_SESSION['saasappoint_mb_cart_service_id']);
	
	$saasappoint_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('saasappoint_hide_already_booked_slots_from_frontend_calendar');
	$saasappoint_minimum_advance_booking_time = $obj_settings->get_option('saasappoint_minimum_advance_booking_time');
	$saasappoint_maximum_advance_booking_time = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');

	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$saasappoint_maximum_advance_booking_time.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$saasappoint_minimum_advance_booking_time." minutes", $current_datetime));  
	?>
	<div class="pt-1 pb-1 pl-4 pr-4">
		<div class="row">
			<div class="col-md-12 saasappoint-sm-box mb-1 text-center">
				<a href="javascript:void(0);" class="saasappoint_back_to_calendar"><label><b><i class="fa fa-caret-up fa-3x"></i></b></label></a>
				<a href="javascript:void(0);" class="saasappoint_reset_slot_selection pull-right" data-day="<?php echo $selected_date; ?>"><label><b><i class="fa fa-refresh"></i> Reset</b></label></a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 saasappoint-sm-box mb-3 text-center">
				<label><b><i class="fa fa-calendar"></i> <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?></b></label>
			</div>
		</div>
		<div class="row">
			<?php 
			/** maximum date check **/		
			if(strtotime($selected_date)>strtotime($maximum_date)){ 
				?>
				<div class="col-md-12 saasappoint-sm-box">
					<label><b>[ You cannot book appointment on <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?>. Our maximum advance booking period is <?php 
						if($saasappoint_maximum_advance_booking_time == "1"){ echo "1 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "3"){ echo "3 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "6"){ echo "6 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "9"){ echo "9 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "12"){ echo "1 Year"; }
						else if($saasappoint_maximum_advance_booking_time == "18"){ echo "1.5 Year"; }
						else if($saasappoint_maximum_advance_booking_time == "24"){ echo "2 Year"; } 
					?>. So you can book appointment till <?php echo $maximum_date; ?>. ]</b></label>
				</div>
				<?php 
			}
			/** time slots **/
			else if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
				$i = 1;
				$j = 0;
				foreach($available_slots['slots'] as $slot){
					if(strtotime($selected_date." ".$slot)<strtotime($minimum_date)){
						continue;
					}else{
						$booked_slot_exist = false;
						foreach($available_slots['booked'] as $bslot){
							if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot)){
								$booked_slot_exist = true;
								continue;
							} 
						}
						if($booked_slot_exist && $saasappoint_hide_already_booked_slots_from_frontend_calendar == "Y"){
							continue;
						}else if($booked_slot_exist && $saasappoint_hide_already_booked_slots_from_frontend_calendar == "N"){ 
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
							<div class="col-md-3 saasappoint-sm-box">
								<div class="saasappoint-styled-radio saasappoint-styled-radio-second saasappoint-styled-radio-disable">
									<input type="radio" id="saasappoint-booked-time-slot-<?php echo $i; ?>" name="saasappoint-booked-time-slots" disabled>
									<label for="saasappoint-booked-time-slot-<?php echo $i; ?>" disabled><?php echo date($saasappoint_time_format,strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}else{ 
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
							<div class="col-md-3 saasappoint-sm-box">
								<div class="saasappoint-styled-radio saasappoint-styled-radio-second">
									<input type="radio" class="saasappoint_time_slots_selection" id="saasappoint-time-slot-<?php echo $i; ?>" name="saasappoint-time-slots" value="<?php echo $slot; ?>">
									<label for="saasappoint-time-slot-<?php echo $i; ?>"><?php echo date($saasappoint_time_format,strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}
						$i++;
					}
				}
				if($j == 0){ 
					?>
					<div class="col-md-12 saasappoint-sm-box">
						<label><b>[ None of slots available on <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?>. ]</b></label>
					</div>
					<?php 
				}
			}else{ 
				?>
				<div class="col-md-12 saasappoint-sm-box">
					<label><b>[ None of slots available on <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?>. ]</b></label>
				</div>
				<?php 
			} 
			?>
		</div>
	</div>
	<?php 
}

/* Endtime available slots ajax */
else if(isset($_POST['get_endtime_slots'])){ 
	$saasappoint_settings_timezone = $obj_settings->get_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 

	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);

	$isEndTime = true;
	$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_SESSION['saasappoint_mb_cart_service_id']);
	
	$saasappoint_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('saasappoint_hide_already_booked_slots_from_frontend_calendar');
	$saasappoint_minimum_advance_booking_time = $obj_settings->get_option('saasappoint_minimum_advance_booking_time');
	$saasappoint_maximum_advance_booking_time = $obj_settings->get_option('saasappoint_maximum_advance_booking_time');

	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$saasappoint_maximum_advance_booking_time.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$saasappoint_minimum_advance_booking_time." minutes", $current_datetime));  
	
	/** check for maximum end time slot limit **/
	$saasappoint_maximum_endtimeslot_limit = $obj_settings->get_option('saasappoint_maximum_endtimeslot_limit');
	$selected_slot_check = strtotime($selected_date." ".$_POST["selected_slot"]);
	$maximum_endslot_limit = date("Y-m-d H:i:s", strtotime("+".$saasappoint_maximum_endtimeslot_limit." minutes", $selected_slot_check)); 	
	?>
	<div class="pt-1 pb-1 pl-4 pr-4">
		<div class="row">
			<div class="col-md-12 saasappoint-sm-box mb-1 text-center">
				<a href="javascript:void(0);" class="saasappoint_back_to_calendar"><label><b><i class="fa fa-caret-up fa-3x"></i></b></label></a>
				<a href="javascript:void(0);" class="saasappoint_reset_slot_selection pull-right" data-day="<?php echo $selected_date; ?>"><label><b><i class="fa fa-refresh"></i> Reset</b></label></a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 saasappoint-sm-box mb-3 text-center">
				<label><b><i class="fa fa-calendar"></i> <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?></b></label>
				<br/>
				<label><b>From: <i class="fa fa-clock-o"></i> <?php echo date($saasappoint_time_format, strtotime($selected_date." ".$_POST["selected_slot"])); ?></b></label>
			</div>
		</div>
		<div class="row">
			<?php 
			/** maximum date check **/		
			if(strtotime($selected_date)>strtotime($maximum_date)){ 
				?>
				<div class="col-md-12 saasappoint-sm-box">
					<label><b>[ You cannot book appointment on <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?>. Our maximum advance booking period is <?php 
						if($saasappoint_maximum_advance_booking_time == "1"){ echo "1 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "3"){ echo "3 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "6"){ echo "6 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "9"){ echo "9 Month"; }
						else if($saasappoint_maximum_advance_booking_time == "12"){ echo "1 Year"; }
						else if($saasappoint_maximum_advance_booking_time == "18"){ echo "1.5 Year"; }
						else if($saasappoint_maximum_advance_booking_time == "24"){ echo "2 Year"; } 
					?>. So you can book appointment till <?php echo $maximum_date; ?>. ]</b></label>
				</div>
				<?php 
			}
			/** time slots **/
			else if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
				$i = 1;
				$j = 0;
				foreach($available_slots['slots'] as $slot){
					if(strtotime($selected_date." ".$slot)<strtotime($minimum_date)){
						continue;
					}else{
						if(strtotime($selected_date." ".$slot) <= strtotime($selected_date." ".$_POST["selected_slot"])){
							continue;
						}elseif(strtotime($selected_date." ".$slot) > strtotime($maximum_endslot_limit)){
							continue;
						}else{
							$booked_slot_exist = false;
							foreach($available_slots['booked'] as $bslot){
								if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot)){
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
										if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
											$blockoff_exist = true;
											continue;
										} 
									}
								} 
								if($blockoff_exist){
									break;
								} 
								?>
								<div class="col-md-3 saasappoint-sm-box">
									<div class="saasappoint-styled-radio saasappoint-styled-radio-second">
										<input type="radio" class="saasappoint_endtime_slots_selection" id="saasappoint-time-slot-<?php echo $i; ?>" name="saasappoint-time-slots" value="<?php echo $slot; ?>">
										<label for="saasappoint-time-slot-<?php echo $i; ?>"><?php echo date($saasappoint_time_format,strtotime($selected_date." ".$slot)); ?></label>
									</div>
								</div>
								<?php 
								$j++;
							}
						}
						$i++;
					}
				}
				if($j == 0){ 
					if(is_numeric($_SESSION['saasappoint_mb_cart_service_id']) && $_SESSION['saasappoint_mb_cart_service_id'] != "0"){
						$time_interval=$obj_slots->get_service_time_interval($_SESSION['saasappoint_mb_cart_service_id'],$time_interval);
					}
					$sdate_stime = strtotime($selected_date." ".$_POST["selected_slot"]);
					$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
					$sdate_estime = date("H:i:s", strtotime($sdate_etime));
					?>
					<div class="col-md-3 saasappoint-sm-box">
						<div class="saasappoint-styled-radio saasappoint-styled-radio-second">
							<input type="radio" class="saasappoint_endtime_slots_selection" id="saasappoint-time-slot-<?php echo $i; ?>" name="saasappoint-time-slots" value="<?php echo $sdate_estime; ?>">
							<label for="saasappoint-time-slot-<?php echo $i; ?>"><?php echo date($saasappoint_time_format,strtotime($sdate_etime)); ?></label>
						</div>
					</div>
					<?php 
				}
			}else{ 
				?>
				<div class="col-md-12 saasappoint-sm-box">
					<label><b>[ None of slots available on <?php echo date($saasappoint_date_format, strtotime($selected_date)); ?>. ]</b></label>
				</div>
				<?php 
			} 
			?>
		</div>
	</div>
	<?php 
}

/* Add selected slot to session ajax */
else if(isset($_POST['add_selected_slot'])){ 
	$selected_startdatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_startslot']));
	$selected_enddatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_endslot']));
	$_SESSION['saasappoint_mb_cart_datetime'] = $selected_startdatetime;
	$_SESSION['saasappoint_mb_cart_end_datetime'] = $selected_enddatetime;
	
	$saasappoint_cart_date = date($saasappoint_date_format, strtotime($_SESSION['saasappoint_mb_cart_datetime'])); 
	$saasappoint_cart_starttime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_mb_cart_datetime'])); 
	$saasappoint_cart_endtime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_mb_cart_end_datetime'])); 
	echo '<span class="text-center"><b><i class="fa fa-calendar text-success"></i> '.$saasappoint_cart_date." ".$saasappoint_cart_starttime." to ".$saasappoint_cart_endtime.'</b></span>';
}

/* Add selected slot to session ajax */
else if(isset($_POST['add_selected_slot_withendslot'])){ 
	if(is_numeric($_SESSION['saasappoint_mb_cart_service_id']) && $_SESSION['saasappoint_mb_cart_service_id'] != "0"){
		$time_interval=$obj_slots->get_service_time_interval($_SESSION['saasappoint_mb_cart_service_id'],$time_interval);
	}
	$selected_startdatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_startslot']));
	$selected_enddatetime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", strtotime($selected_startdatetime)));
	$_SESSION['saasappoint_mb_cart_datetime'] = $selected_startdatetime;
	$_SESSION['saasappoint_mb_cart_end_datetime'] = $selected_enddatetime;
	
	$saasappoint_cart_date = date($saasappoint_date_format, strtotime($_SESSION['saasappoint_mb_cart_datetime'])); 
	$saasappoint_cart_starttime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_mb_cart_datetime'])); 
	$saasappoint_cart_endtime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_mb_cart_end_datetime'])); 
	echo '<span class="text-center"><b><i class="fa fa-calendar text-success"></i> '.$saasappoint_cart_date." ".$saasappoint_cart_starttime.'</b></span>';
}

/* get customer detail ajax */
else if(isset($_POST['get_customer_detail'])){ 
	$obj_frontend->customer_id = $_POST['id'];
	
	/* Function to check login details */
	$login_detail = $obj_frontend->readone_customer();
	
	$array = array();
	$array['status'] = "failed";
	if(is_array($login_detail)){
		$array['id'] = $login_detail['id'];
		$array['email'] = $login_detail['email'];
		$array['password'] = $login_detail['password'];
		$array['firstname'] = ucwords($login_detail['firstname']);
		$array['lastname'] = ucwords($login_detail['lastname']);
		$array['phone'] = $login_detail['phone'];
		$array['address'] = $login_detail['address'];
		$array['city'] = $login_detail['city'];
		$array['state'] = $login_detail['state'];
		$array['zip'] = $login_detail['zip'];
		$array['country'] = $login_detail['country'];
		$array['status'] = "success";
	}
	echo json_encode($array);
}