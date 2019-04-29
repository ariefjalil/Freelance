<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

$langfiles = preg_grep('~^saasappoint-'.$_SESSION['business_id'].'.*\.(php)$~', scandir(dirname(dirname(dirname(__FILE__)))."/includes/languages/"));
$langfiles = array_values($langfiles); 
$sizeof_langfiles = sizeof($langfiles);

if(isset($_COOKIE["saasappoint_language"]) && $sizeof_langfiles>0){
	if(file_exists(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-".$_COOKIE["saasappoint_language"].".php")){
		include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-".$_COOKIE["saasappoint_language"].".php"); 
	}else if(file_exists(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-en.php")){
		include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-".$_SESSION['business_id']."-en.php"); 
	}else{
		include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-default-en.php"); 
	}
}else{
	include(dirname(dirname(dirname(__FILE__)))."/includes/languages/saasappoint-default-en.php"); 
}

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_frontend = new saasappoint_frontend();
$obj_frontend->conn = $conn;
$obj_frontend->business_id = $_SESSION['business_id'];

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}

/* add to cart item ajax */
if(isset($_POST['add_to_cart_item'])){
	$id = $_POST['id'];
	$qty = $_POST['qty'];
	
	$saasappoint_tax_status = $obj_settings->get_option('saasappoint_tax_status');
	$saasappoint_tax_type = $obj_settings->get_option('saasappoint_tax_type');
	$saasappoint_tax_value = $obj_settings->get_option('saasappoint_tax_value');
	
	if($_POST['qty']>0){
		/** Add and update item into cart **/
		$obj_frontend->addon_id = $id;
		$addon_rate = $obj_frontend->get_addon_rate();
		$rate = ($addon_rate*$qty);
		$item_arr = array();
		$item_arr['id'] = $id;
		$item_arr['qty'] = $qty;
		$item_arr['rate'] = $rate;
		
		$cart_item_key = $obj_frontend->saasappoint_check_existing_cart_item($_SESSION['saasappoint_cart_items'], $id);
		if(is_numeric($cart_item_key)){
			$_SESSION['saasappoint_cart_items'][$cart_item_key] = $item_arr;
			$_SESSION['saasappoint_cart_items'] = array_values($_SESSION['saasappoint_cart_items']);
		}else{
			array_push($_SESSION['saasappoint_cart_items'], $item_arr);
			$_SESSION['saasappoint_cart_items'] = array_values($_SESSION['saasappoint_cart_items']);
		}
		
		$subtotal = 0;
		foreach($_SESSION['saasappoint_cart_items'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		}
				
		$saasappoint_referral_discount_type = $obj_settings->get_option('saasappoint_referral_discount_type');
		$saasappoint_referral_discount_value = $obj_settings->get_option('saasappoint_referral_discount_value');
		$obj_frontend->saasappoint_cart_item_calculation($subtotal, $saasappoint_tax_status, $saasappoint_tax_type, $saasappoint_tax_value, $saasappoint_referral_discount_type, $saasappoint_referral_discount_value);
	}else{
		/** remove item from cart **/	
		$subtotal = 0;
		foreach($_SESSION['saasappoint_cart_items'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		} 
		$cart_item_key = $obj_frontend->saasappoint_check_existing_cart_item($_SESSION['saasappoint_cart_items'], $id);
		if(is_numeric($cart_item_key)){
			$subtotal = $subtotal-$_SESSION['saasappoint_cart_items'][$cart_item_key]['rate'];
			$saasappoint_referral_discount_type = $obj_settings->get_option('saasappoint_referral_discount_type');
			$saasappoint_referral_discount_value = $obj_settings->get_option('saasappoint_referral_discount_value');
			$obj_frontend->saasappoint_cart_item_calculation($subtotal, $saasappoint_tax_status, $saasappoint_tax_type, $saasappoint_tax_value, $saasappoint_referral_discount_type, $saasappoint_referral_discount_value);
			unset($_SESSION['saasappoint_cart_items'][$cart_item_key]);
			$_SESSION['saasappoint_cart_items'] = array_values($_SESSION['saasappoint_cart_items']);
		}
	}
}

/* refresh cart sidebar ajax */
else if(isset($_POST['refresh_cart_sidebar'])){ 
	if(sizeof($_SESSION['saasappoint_cart_items'])>0){
		$saasappoint_currency_symbol = $obj_settings->get_option('saasappoint_currency_symbol'); 
		?>
		<ul class="saasappoint_cart_items_list">
			<li class="saasappoint_cart_items_list_li">
				<i class="fa fa-bookmark" aria-hidden="true"></i>
				<p>
					<?php 
					$obj_frontend->category_id = $_SESSION['saasappoint_cart_category_id'];
					$category_name = $obj_frontend->readone_category_name(); 
					echo ucwords($category_name); 
					?>
				</p>
			</li>
			<li class="saasappoint_cart_items_list_li">
				<i class="fa fa-paint-brush" aria-hidden="true"></i>
				<p>
					<?php 
					$obj_frontend->service_id = $_SESSION['saasappoint_cart_service_id'];
					$service_name = $obj_frontend->readone_service_name(); 
					echo ucwords($service_name); 
					?>
				</p>
			</li>
			<?php 
			if($_SESSION['saasappoint_cart_datetime'] != "" && $_SESSION['saasappoint_cart_end_datetime'] != "" ){
				$saasappoint_cart_date = date($saasappoint_date_format, strtotime($_SESSION['saasappoint_cart_datetime'])); 
				$saasappoint_cart_starttime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_cart_datetime'])); 
				$saasappoint_cart_endtime = date($saasappoint_time_format, strtotime($_SESSION['saasappoint_cart_end_datetime'])); 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<p><?php echo $saasappoint_cart_date." ".$saasappoint_cart_starttime." to ".$saasappoint_cart_endtime; ?></p>
				</li>
			<?php 
			} 
			?>
			<?php 
			if($_SESSION['saasappoint_cart_freqdiscount_key'] != ""){ 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-refresh" aria-hidden="true"></i>
					<p><?php echo $_SESSION['saasappoint_cart_freqdiscount_label']; ?></p>
				</li>
			<?php 
			} 
			?>
			<li class="saasappoint_cart_items_list_li">
				<i class="fa fa-puzzle-piece" aria-hidden="true"></i>
				<p><?php echo $langArr['addons']; ?>:</p>
			</li>
			<li class="saasappoint_cart_items_list_li">
				<div class="saasappoint_cart_addons_list">
					<?php 
					foreach($_SESSION['saasappoint_cart_items'] as $val){ 
						$obj_frontend->addon_id = $val['id'];
						$addon_name = $obj_frontend->readone_addon_name(); 
						?>
						<label class="col-md-12">
							<a class="saasappoint_remove_addon_from_cart" href="javascript:void(0)" data-id="<?php echo $val['id']; ?>"><i class="fa fa-trash saasappoint_remove_addon_icon" aria-hidden="true"></i></a> &nbsp; 
							<?php echo ucwords($addon_name)." - ".$val['qty']; ?>
							<span class="pull-right"><?php echo $saasappoint_currency_symbol.$val['rate']; ?></span>
						</label>
						<?php 
					} 
					?>
				</div>
			</li>
			<hr />
			<li class="saasappoint_cart_items_list_li">
				<i class="fa fa-money" aria-hidden="true"></i> 
				<p>
					<?php echo $langArr['sub_total']; ?>
					<span class="pull-right"><?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_cart_subtotal']; ?></span>
				</p>
			</li>
			<?php 
			if($_SESSION['saasappoint_cart_freqdiscount']>0){ 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-percent" aria-hidden="true"></i> 
					<p>
						<?php echo $langArr['frequently_discount']; ?>
						<span class="pull-right">-<?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_cart_freqdiscount']; ?></span>
					</p>
				</li>
			<?php 
			} 
			if($_SESSION['saasappoint_cart_coupondiscount']>0){ 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-ticket" aria-hidden="true"></i> 
					<p>
						<?php echo $langArr['coupon_discount']; ?>
						<span class="pull-right">-<?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_cart_coupondiscount']; ?></span>
					</p>
				</li>
			<?php 
			} 
			if($_SESSION['saasappoint_referral_discount_amount']>0){ 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-gift" aria-hidden="true"></i> 
					<p>
						<?php echo $langArr['referral_coupon_discount']; ?>
						<span class="pull-right">-<?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_referral_discount_amount']; ?></span>
					</p>
				</li>
			<?php 
			} 
			if($_SESSION['saasappoint_cart_tax']>0){ 
				?>
				<li class="saasappoint_cart_items_list_li">
					<i class="fa fa-tags" aria-hidden="true"></i> 
					<p>
						<?php echo $langArr['tax']; ?>
						<span class="pull-right">+<?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_cart_tax']; ?></span>
					</p>
				</li>
				<?php 
			} 
			?>
		</ul>
		<h4><?php echo $langArr['net_total']; ?><span><?php echo $saasappoint_currency_symbol.$_SESSION['saasappoint_cart_nettotal']; ?></span></h4>
		<?php 
	}else{ 
		?>
		<label><?php echo $langArr['no_items_in_cart']; ?></label>
		<?php 
	}
}