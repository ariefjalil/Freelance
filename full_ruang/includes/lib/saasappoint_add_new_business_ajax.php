<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_admins.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscriptions.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_subscription_plans.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_businesses.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_schedule.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frequently_discount.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_templates.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$obj_admins = new saasappoint_admins();
$obj_admins->conn = $conn;

$obj_subscription_plans = new saasappoint_subscription_plans();
$obj_subscription_plans->conn = $conn;

$obj_subscriptions = new saasappoint_subscriptions();
$obj_subscriptions->conn = $conn;

$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;

$obj_schedule = new saasappoint_schedule();
$obj_schedule->conn = $conn;

$obj_frequently_discount = new saasappoint_frequently_discount();
$obj_frequently_discount->conn = $conn;

$obj_templates = new saasappoint_templates();
$obj_templates->conn = $conn;

/** Register admin Ajax **/
if(isset($_POST['add_business_admin'])){
	$obj_subscription_plans->id = $_POST['plan_id'];
	$plan_detail = $obj_subscription_plans->readone_subscription_plan();
	
	if($plan_detail['renewal_type'] == "monthly"){
		$date_year_month = "months";
	}else{
		$date_year_month = "years";
	}
	
	$saasappoint_settings_timezone = $obj_settings->get_superadmin_option("saasappoint_timezone");
	$saasappoint_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($saasappoint_server_timezone,$saasappoint_settings_timezone); 
	
	$subscribed_date = date("Y-m-d H:i:s", $currDateTime_withTZ);
	$exipred_date = date("Y-m-d H:i:s", strtotime("+".$plan_detail['plan_period']." ".$date_year_month, strtotime($subscribed_date)));
	
	$sms_credit = $obj_settings->get_option('saasappoint_sms_credit');
	$updated_sms_credit = $sms_credit+$plan_detail['sms_credit'];
			
	/** add business **/
	$obj_businesses->business_type_id = $_POST['business_type_id'];
	$obj_businesses->registered_on = $subscribed_date;
	$obj_businesses->status = "Y";
	$business_id = $obj_businesses->add_business();
	
	if(is_numeric($business_id)){
		/** add admin **/
		$obj_admins->business_id = $business_id;
		$obj_admins->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
		$obj_admins->password = md5($_POST['password']);
		$obj_admins->firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$obj_admins->lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$obj_admins->phone = $_POST['phone'];
		$obj_admins->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$obj_admins->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$obj_admins->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$obj_admins->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$obj_admins->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
		$obj_admins->image = "";
		$obj_admins->status = "Y";
		$admin_id = $obj_admins->add_admin();
		
		if(is_numeric($admin_id)){
			/** add subscription **/
			$obj_subscriptions->business_id = $business_id;
			$obj_subscriptions->admin_id = $admin_id;
			$obj_subscriptions->plan_id = $_POST['plan_id'];
			$obj_subscriptions->transaction_id = "";
			$obj_subscriptions->subscribed_on = $subscribed_date;
			$obj_subscriptions->expired_on = $exipred_date;
			$obj_subscriptions->joined_on = $subscribed_date;
			$obj_subscriptions->renewal = $plan_detail['renewal_type'];
			$obj_subscriptions->payment_method = "pay manually";
			$added = $obj_subscriptions->add_subscription();
			$obj_subscriptions->add_subscription_history();
		
			if($added){
				
				/** add default frequently discount **/
				$obj_frequently_discount->business_id = $business_id;
				$obj_frequently_discount->add_default_frequently_discount();
					
				/** add default schedule **/
				$obj_schedule->business_id = $business_id;
				$obj_schedule->add_default_schedule();
				
				/** add default settings **/
				$obj_settings->business_id = $business_id;
				$companyname = filter_var($_POST['companyname'], FILTER_SANITIZE_STRING);
				$companyemail = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['companyemail'])));
				$companyphone = $_POST['companyphone'];
				$companyaddress = filter_var($_POST['companyaddress'], FILTER_SANITIZE_STRING);
				$companycity = filter_var($_POST['companycity'], FILTER_SANITIZE_STRING);
				$companystate = filter_var($_POST['companystate'], FILTER_SANITIZE_STRING);
				$companyzip = filter_var($_POST['companyzip'], FILTER_SANITIZE_STRING);
				$companycountry = filter_var($_POST['companycountry'], FILTER_SANITIZE_STRING);
				
				$settings_added = $obj_settings->add_default_settings(SITE_URL, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry);
				
				if($settings_added){
					/** add default templates **/
					$obj_templates->business_id = $business_id;
					$obj_templates->add_default_email_sms_templates();
					
					/** update SMS credit **/
					$obj_settings->business_id = $business_id;
					$obj_settings->update_option("saasappoint_sms_credit", $updated_sms_credit);
					
					/** Add language file **/
					$fileContents = file_get_contents(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-default-en.php');
					$fh = fopen(dirname(dirname(dirname(__FILE__))).'/includes/languages/saasappoint-'.$business_id.'-en.php', 'w+');
					fputs($fh, $fileContents);
					fclose($fh);
					echo "added";
				}
			}
		}
	}
}