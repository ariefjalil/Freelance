<?php 
session_start();
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_login.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_database = new saasappoint_database();
$conn = $obj_database->connect();
$obj_login = new saasappoint_login();
$obj_login->conn = $conn;
$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

/* Login process ajax */
if(isset($_POST['login_process'])){
	$obj_login->email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_login->password = $_POST['password'];
	$obj_login->remember_me = $_POST['remember_me'];
	
	/* Function to check login details */
	$obj_login->login_process();
}

/* Logout process ajax */
else if(isset($_POST['logout_process'])){
	session_destroy();
}

/* Reset password process ajax */
else if(isset($_POST['reset_password'])){
	$obj_login->email = trim(strip_tags(mysqli_real_escape_string($conn, $_SESSION["saasappoint_rp_cemail"])));
	$obj_login->password = md5($_POST["password"]);
	$reset = $obj_login->reset_password();
	if($reset){
		echo 'reset';
	}
}

/* Forgot password ajax */
else if(isset($_POST['forgot_password'])){
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_login->email = $email;
	$existing_email_check = $obj_login->existing_email_check();
	if(mysqli_num_rows($existing_email_check)>0){
		$val = mysqli_fetch_array($existing_email_check);
		$userID = $email;
		$currentTime = date('Y-m-d H:i:s');
		$userName = ucwords($val['firstname'].' '.$val['lastname']);
		$ency_code = base64_encode(base64_encode($userID) . '#####' . strtotime("+120 minutes", strtotime($currentTime)));
		$companyname = $obj_settings->get_superadmin_option("saasappoint_company_name");
		
		/* Body section of the mail */
		$body = '<html>
					<head>
						<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						<title>Welcome to '.$companyname.'</title>
					</head>
					<body>
						<div style="margin: 0;padding: 0;font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;font-size: 100%;line-height: 1.6;box-sizing: border-box;">
							<div style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
								<table style="border: 1px solid #c2c2c2;width: 100%;float: left;margin: 30px 0px;-webkit-border-radius: 5px;-moz-border-radius: 5px;-o-border-radius: 5px;border-radius: 5px;">
									<tbody>
										<tr>
											<td>
												<div style="padding: 25px 30px;background: #fff;float: left;width: 90%;display: block;">
													<div style="border-bottom: 1px solid #e6e6e6;float: left;width: 100%;display: block;">
														<h3 style="color: #606060;font-size: 20px;margin: 15px 0px 0px;font-weight: 400;">Hi '.$userName.',</h3><br />
														<p style="color: #606060;font-size: 15px;margin: 10px 0px 15px;">Forgot your password - <a href="'. SITE_URL .'backend/reset-password.php?code=' . $ency_code . '" >Reset it here</a></p>
													</div>
													<div style="padding: 15px 0px;float: left;width: 100%;">
														<h5 style="color: #606060;font-size: 13px;margin: 10px 0px px;">Regards,</h5>
														<h6 style="color: #606060;font-size: 14px;font-weight: 600;margin: 10px 0px 15px;">'.$companyname.'</h6>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</body>
				</html>';
				
		$saasappoint_email_sender_name = $obj_settings->get_superadmin_option("saasappoint_email_sender_name");
		$saasappoint_email_sender_email = $obj_settings->get_superadmin_option("saasappoint_email_sender_email");
		$saasappoint_email_smtp_hostname = $obj_settings->get_superadmin_option("saasappoint_email_smtp_hostname");
		$saasappoint_email_smtp_username = $obj_settings->get_superadmin_option("saasappoint_email_smtp_username");
		$saasappoint_email_smtp_password = $obj_settings->get_superadmin_option("saasappoint_email_smtp_password");
		$saasappoint_email_smtp_port = $obj_settings->get_superadmin_option("saasappoint_email_smtp_port");
		$saasappoint_email_encryption_type = $obj_settings->get_superadmin_option("saasappoint_email_encryption_type");
		$saasappoint_email_smtp_authentication = $obj_settings->get_superadmin_option("saasappoint_email_smtp_authentication");
				
		/* Send Mail code start here */
		try {
			include(dirname(dirname(dirname(__FILE__)))."/classes/class.phpmailer.php");
			$mail = new saasappoint_phpmailer();
			if($saasappoint_email_smtp_hostname != '' && $saasappoint_email_sender_name != '' && $saasappoint_email_sender_email != '' && $saasappoint_email_smtp_username != '' && $saasappoint_email_smtp_password != '' && $saasappoint_email_smtp_port != ''){
				$mail->IsSMTP();
				$mail->Host = $saasappoint_email_smtp_hostname;
				$mail->SMTPAuth = $saasappoint_email_smtp_authentication;
				$mail->Username = $saasappoint_email_smtp_username;
				$mail->Password = $saasappoint_email_smtp_password;
				$mail->SMTPSecure = $saasappoint_email_encryption_type;
				$mail->Port = $saasappoint_email_smtp_port;
			}else{
				$mail->IsMail();
			}
			$mail->IsHTML(true);
			$mail->SMTPDebug  = 0;
			$mail->From = $saasappoint_email_sender_email;
			$mail->FromName = $saasappoint_email_sender_name;
			$mail->Sender = $saasappoint_email_sender_email;
			$mail->Subject = 'Reset Password';
			$mail->Body = $body;
			$mail->AddAddress($email, $userName);
			$response = $mail->Send();
			if($response){
				echo 'mailsent';
			}else{
				@ob_clean();
				ob_start();
				echo 'tryagain';
			}
		} catch (phpmailerException $e) {
			echo 'tryagain';
		}
	}
}