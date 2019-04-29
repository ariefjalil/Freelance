<?php 
session_start();

/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_templates.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;
$obj_settings->business_id = $_SESSION['business_id'];

$obj_templates = new saasappoint_templates();
$obj_templates->conn = $conn;
$obj_templates->business_id = $_SESSION['business_id'];

/** Update Email template Ajax **/
if(isset($_POST["update_email_template"])){
	$obj_templates->template = $_POST['template'];
	$obj_templates->subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
	$obj_templates->email_content = base64_encode($_POST['email_content']);
	$obj_templates->template_for = $_POST['template_for'];
	$obj_templates->email_status = $_POST['email_status'];
	$updated = $obj_templates->update_email_template();
	if($updated){
		echo "updated";
	}
}

/** Update Email template Ajax **/
else if(isset($_POST["update_sms_template"])){
	$obj_templates->template = $_POST['template'];
	$obj_templates->sms_content = base64_encode($_POST['sms_content']);
	$obj_templates->template_for = $_POST['template_for'];
	$obj_templates->sms_status = $_POST['sms_status'];
	$updated = $obj_templates->update_sms_template();
	if($updated){
		echo "updated";
	}
}

/** Get Email template Ajax **/
else if(isset($_POST['get_email_template'])){ 
	$template = $_POST["template"];
	$template_for = $_POST["template_for"]; 
	$obj_templates->template = $template;
	$obj_templates->template_for = $template_for;
	$template_data = $obj_templates->readone_template(); 
	if(mysqli_num_rows($template_data)>0){
		$tdetail = mysqli_fetch_array($template_data);
		$status = $tdetail["email_status"];
		$subject = $tdetail["subject"];
		$content = $tdetail["email_content"]; 
		?>
		<form name="saasappoint_email_templates_settings_form" id="saasappoint_email_templates_settings_form" method="post">
			<input type="hidden" id="saasappoint_emailtemplate_template" value="<?php echo $template; ?>" />
			<input type="hidden" id="saasappoint_emailtemplate_template_for" value="<?php echo $template_for; ?>" />
			<div class="form-group row">
				<div class="col-md-12">
					<label class="col-md-4 saasappoint-va-top pt-1"><?php echo ucwords($template_for); ?> Email Status</label>
					<label class="saasappoint-toggle-switch">
						<input type="checkbox" id="saasappoint_email_template_status" class="saasappoint-toggle-switch-input" <?php if($status == "Y"){ echo "checked"; } ?> />
						<span class="saasappoint-toggle-switch-slider"></span>
					</label>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label class="control-label">Email Subject</label>
					<input name="saasappoint_email_template_subject" id="saasappoint_email_template_subject" class="form-control" type="text" value="<?php echo $subject; ?>" placeholder="Enter subject" />
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label class="control-label">Email Template</label>
					<textarea name="saasappoint_email_template_content" class="saasappoint_text_editor_container" id="saasappoint_email_template_content" autocomplete="off"><?php if($content != ""){ echo base64_decode($content); } ?></textarea>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-12">
					<center><h4 class="control-label">Dynamic Tags</h4></center>
					<center><small><b>[ Copy tags and add in your template to get dynamic value ]</b></small></center>
					<hr />
					<ul class="list-inline ml-3 text-white">
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{category}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{service}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{addons}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{booking_date}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{booking_time}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{payment_method}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{payment_date}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{transaction_id}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{sub_total}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{coupon_discount}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{frequently_discount}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{tax}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{net_total}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_email}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_phone}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_address}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{admin_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company	_email}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_phone}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_address}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_logo}}}</li>
					</ul>
				</div>
			</div>
		</form>
		<?php 
	}else{
		echo "<center class='m-5'><h4>Opps! Template is not available.<br />Contact your superadmin to unlock this template.</h4></center>";
	} 
}

/** Get SMS template Ajax **/
else if(isset($_POST['get_sms_template'])){ 
	$template = $_POST["template"];
	$template_for = $_POST["template_for"]; 
	$obj_templates->template = $template;
	$obj_templates->template_for = $template_for;
	$template_data = $obj_templates->readone_template(); 
	if(mysqli_num_rows($template_data)>0){
		$tdetail = mysqli_fetch_array($template_data);
		$status = $tdetail["sms_status"];
		$content = $tdetail["sms_content"];
		?>
		<form name="saasappoint_sms_templates_settings_form" id="saasappoint_sms_templates_settings_form" method="post">
			<input type="hidden" id="saasappoint_smstemplate_template" value="<?php echo $template; ?>" />
			<input type="hidden" id="saasappoint_smstemplate_template_for" value="<?php echo $template_for; ?>" />
			<div class="form-group row">
				<div class="col-md-12">
					<label class="col-md-4 saasappoint-va-top pt-1"><?php echo ucwords($template_for); ?> SMS Status</label>
					<label class="saasappoint-toggle-switch">
						<input type="checkbox" id="saasappoint_sms_template_status" class="saasappoint-toggle-switch-input" <?php if($status == "Y"){ echo "checked"; } ?> />
						<span class="saasappoint-toggle-switch-slider"></span>
					</label>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12 pl-4">
					<label class="control-label">SMS Template</label>
					<textarea class="form-control" name="saasappoint_sms_template_content" id="saasappoint_sms_template_content" rows="5" placeholder="Write something..."><?php echo base64_decode($content); ?></textarea>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-12">
					<center><h4 class="control-label">Dynamic Tags</h4></center>
					<center><small><b>[ Copy tags and add in your template to get dynamic value ]</b></small></center>
					<hr />
					<ul class="list-inline ml-3 text-white">
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{category}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{service}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{addons}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{booking_date}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{booking_time}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{payment_method}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{payment_date}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{transaction_id}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{sub_total}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{coupon_discount}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{frequently_discount}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{tax}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{net_total}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_email}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_phone}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{customer_address}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{admin_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_name}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_email}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_phone}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_address}}}</li>
						<li class="list-inline-item badge bg-dark p-2 mb-2">{{{company_logo}}}</li>
					</ul>
				</div>
			</div>
		</form>
		<?php 
	}else{
		echo "<center class='m-5'><h4>Opps! Template is not available.<br />Contact your superadmin to unlock this template.</h4></center>";
	} 
}