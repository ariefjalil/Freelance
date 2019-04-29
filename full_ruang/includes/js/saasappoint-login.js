/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
$(document).ready(function(){
	/** validate login form **/
	 $('#saasappoint_login_form').validate({
        rules: {
            saasappoint_login_email: {required: true, email: true},
            saasappoint_login_password: {required: true, minlength:8, maxlength:20}
        },
        messages: {
            saasappoint_login_email: {required: "Please enter email address", email: "Please enter valid email address"},
            saasappoint_login_password: {required: "Please enter password", minlength: "Please enter minimum 8 character", maxlength: "Please enter maximum 20 character"}
        }
    });
	
	/** validate reset password form **/
	$('#saasappoint_reset_password_form').validate({
		rules: {
			saasappoint_reset_new_password: { required:true, minlength: 8, maxlength: 20 },
			saasappoint_reset_retype_new_password: { required:true, equalTo: "#saasappoint_reset_new_password", minlength: 8, maxlength: 20 }
		},
		messages: {
			saasappoint_reset_new_password: { required: "Please enter new password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_reset_retype_new_password: { required: "Please enter retype new password", equalTo: "New password and Retype new password mismatch", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" }
		}
	});
	
	/** validate forget password form **/
	$('#saasappoint_forgot_password_form').validate({
        rules: {
            saasappoint_forgot_password_email: {required: true, email: true}
        },
        messages: {
            saasappoint_forgot_password_email: {required: "Please enter email address", email: "Please enter valid email address"}
        }
    });
});

/** Login process JS **/
$(document).on('click','#saasappoint_login_btn',function(e){
	e.preventDefault();
	$('#saasappoint-login-error').hide();
	var email = $('#saasappoint_login_email').val();
	var password = $('#saasappoint_login_password').val();
	var remember_me = $('#saasappoint_login_remember_me').prop('checked');
	if(remember_me){
		remember_me = 'Y';
	}else{
		remember_me = 'N';
	}
	var site_url = generalObj.site_url;
	var ajax_url = generalObj.ajax_url;
	if ($('#saasappoint_login_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'password': password,
				'remember_me': remember_me,
				'login_process': 1
			},
			url: ajax_url + "saasappoint_login_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res.trim() == "customer"){
                   window.location.replace(site_url+"backend/my-appointments.php");
				}else if(res.trim() == "admin"){
					window.location.replace(site_url+"backend/appointments.php");
				}else if(res.trim() == "superadmin"){
					window.location.replace(site_url+"backend/businesses.php");
				}else{
					$('#saasappoint-login-error').show();
				}
			}
		});
	}
});

/** Reset password JS **/
$(document).on('click','#saasappoint_reset_password_btn',function(e){
	e.preventDefault();
	$('#saasappoint-login-error').hide();
	var password = $('#saasappoint_reset_retype_new_password').val();
	var ajax_url = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	if ($('#saasappoint_reset_password_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'password': password,
				'reset_password': 1
			},
			url: ajax_url + "saasappoint_login_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res.trim() == "reset"){
					swal("Reset!", "Your password reset successfully. Try login to enjoy services", "success");
					window.location.replace(site_url+"backend");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Forget password JS **/
$(document).on('click','#saasappoint_forgot_password_btn',function(e){
	e.preventDefault();
	$('#saasappoint-forgot-password-success').hide();
	$('#saasappoint-forgot-password-error').hide();
	var email = $('#saasappoint_forgot_password_email').val();
	var site_url = generalObj.site_url;
	var ajax_url = generalObj.ajax_url;
	if ($('#saasappoint_forgot_password_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'forgot_password': 1
			},
			url: ajax_url + "saasappoint_login_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res.trim() == "mailsent"){
					$('#saasappoint-forgot-password-error').hide();
					$('#saasappoint-forgot-password-success').html("Reset password link sent successfully at your registered email address");
					$('#saasappoint-forgot-password-success').show();
				}else if(res.trim() == "tryagain"){
					$('#saasappoint-forgot-password-success').hide();
					$('#saasappoint-forgot-password-error').html("Oops! Error occurred please try again");
					$('#saasappoint-forgot-password-error').show();
				}else{
					$('#saasappoint-forgot-password-success').hide();
					$('#saasappoint-forgot-password-error').html("Invalid email address");
					$('#saasappoint-forgot-password-error').show();
				}
			}
		});
	}
});