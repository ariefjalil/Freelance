/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
/** Initialization on ready state JS **/
$(document).ready(function () {
	var ajaxurl = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_profile_phone, #saasappoint_add_business_admin_phone, #saasappoint_add_business_admin_companyphone, #saasappoint_company_phone").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
	
	/** Validation patterns **/
	$.validator.addMethod("pattern_name", function(value, element) {
		return this.optional(element) || /^[a-zA-Z '.']+$/.test(value);
	}, "Please enter only alphabets");
	$.validator.addMethod("pattern_price", function(value, element) {
		return this.optional(element) || /^[0-9]\d*(\.\d{1,2})?$/.test(value);
	}, "Please enter only numerics");
	$.validator.addMethod("pattern_phone", function(value, element) {
		return this.optional(element) || /\d+(?:[ -]*\d+)*$/.test(value);
	}, "Please enter valid phone number [without country code]");
	$.validator.addMethod("pattern_zip", function(value, element) {
		return this.optional(element) || /^[a-zA-Z 0-9\-]*$/.test(value);
	}, "Please enter valid zip");
	
	
	/** Validate add new business form **/
	$('#saasappoint_add_new_business_form').validate({
		rules: {
			saasappoint_add_business_admin_firstname:{ required: true, maxlength: 50},
			saasappoint_add_business_admin_lastname: { required:true, maxlength: 50 },
			saasappoint_add_business_admin_email:{ required: true, email: true, remote: { 
				url:ajaxurl+"saasappoint_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#saasappoint_add_business_admin_email").val(); },
					check_email_exist: 1
				}
			} },
			saasappoint_add_business_admin_password:{ required: true, minlength: 8, maxlength: 20 },
			saasappoint_add_business_admin_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_add_business_admin_address: { required:true },
			saasappoint_add_business_admin_city: { required:true },
			saasappoint_add_business_admin_state: { required:true},
			saasappoint_add_business_admin_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_add_business_admin_country: { required:true},
			saasappoint_add_business_admin_businesstype: { required:true },
			saasappoint_add_business_admin_companyname: { required:true, maxlength: 50},
			saasappoint_add_business_admin_companyemail:{ required: true, email: true },
			saasappoint_add_business_admin_companyphone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_add_business_admin_companyaddress: { required:true },
			saasappoint_add_business_admin_companycity: { required:true },
			saasappoint_add_business_admin_companystate: { required:true },
			saasappoint_add_business_admin_companyzip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_add_business_admin_companycountry: { required:true },
			saasappoint_add_business_plans_radio: { required:true }
		},
		messages: {
			saasappoint_add_business_admin_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_add_business_admin_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_add_business_admin_email:{ required: "Please enter email", email: "Please enter valid email", remote: "Email already exist" },
			saasappoint_add_business_admin_password: { required: "Please enter password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_add_business_admin_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_add_business_admin_address: { required: "Please enter address" },
			saasappoint_add_business_admin_city: { required: "Please enter city" },
			saasappoint_add_business_admin_state: { required: "Please enter state" },
			saasappoint_add_business_admin_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_add_business_admin_country: { required: "Please enter country" },
			saasappoint_add_business_admin_businesstype: { required: "Please select business type" },
			saasappoint_add_business_admin_companyname: { required: "Please enter company name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_add_business_admin_companyemail:{ required: "Please enter company email", email: "Please enter valid email" },
			saasappoint_add_business_admin_companyphone: { required: "Please enter  company phone", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_add_business_admin_companyaddress: { required: "Please enter company address" },
			saasappoint_add_business_admin_companycity: { required: "Please enter company city" },
			saasappoint_add_business_admin_companystate: { required: "Please enter company state" },
			saasappoint_add_business_admin_companyzip: { required: "Please enter company zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_add_business_admin_companycountry: { required: "Please enter company country" },
			saasappoint_add_business_plans_radio: { required: "Please select subscription plan" },
		}
	});
	
	/** Check for setup instruction modal **/
	if(generalObj.setup_instruction_modal_status == "Y"){
		$("#saasappoint-setup-instruction-modal").modal("show");
	}
	
	/** DataTable JS **/
	$("#saasappoint_businesses_list_table").DataTable({
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		"order": [ 0, 'desc' ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false },
			{ bSortable: false },
			{ bSortable: false },
			{ bSortable: false },
			{ bSortable: false },
			{ bSortable: false }
		] 
	});
	$("#saasappoint_subscription_history_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true }
		] 
	});
	$("#saasappoint_sms_subscription_history_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true }
		] 
	});
	$("#saasappoint_support_ticket_list_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		] 
	});
	$("#saasappoint_splan_list_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		] 
	});
	
	$("#saasappoint_smsplan_list_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		] 
	});
	
	$("#saasappoint_btype_list_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		] 
	});
	
	/** Validate add subscription plan form **/
	$('#saasappoint_add_splan_form').validate({
		rules: {
			saasappoint_splanname:{ required: true, maxlength: 100 },
			saasappoint_splanrate: { required:true, pattern_price:true },
			saasappoint_splanperiod: { required:true, number:true }
		},
		messages: {
			saasappoint_splanname:{ required: "Please enter plan name", maxlength: "Please enter maximum 100 characters" },
			saasappoint_splanrate: { required: "Please enter plan rate" },
			saasappoint_splanperiod: { required: "Please enter plan period", number: "Please enter only numeric" }
		}
	});
	
	/** Validate add sms plan form **/
	$('#saasappoint_add_smsplan_form').validate({
		rules: {
			saasappoint_smsplanname:{ required: true, maxlength: 100 },
			saasappoint_smsplanrate: { required:true, pattern_price:true },
			saasappoint_smsplancredit: { required:true, number:true }
		},
		messages: {
			saasappoint_smsplanname:{ required: "Please enter plan name", maxlength: "Please enter maximum 100 characters" },
			saasappoint_smsplanrate: { required: "Please enter plan rate" },
			saasappoint_smsplancredit: { required: "Please enter SMS credit", number: "Please enter only numeric" }
		}
	});
	
	/** Validate add subscription plan form **/
	$('#saasappoint_add_btype_form').validate({
		rules: {
			saasappoint_btypename:{ required: true, maxlength: 100 }
		},
		messages: {
			saasappoint_btypename:{ required: "Please enter business type", maxlength: "Please enter maximum 100 characters" }
		}
	});
});

$(document).ajaxComplete(function(){
	var site_url = generalObj.site_url;
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_twilio_sender_number, #saasappoint_plivo_sender_number").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
});

/** Add business type js */
$(document).on('click', '#saasappoint_add_btype_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($("#saasappoint_add_btype_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var business_type = $("#saasappoint_btypename").val();
		var status = $("input[name='saasappoint_btypestatus']:checked"). val();
		
		$.ajax({
			type: 'post',
			data: {
				'business_type': business_type,
				'status': status,
				'add_business_type': 1
			},
			url: ajaxurl + "saasappoint_business_types_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", 'Business type added successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Add subscription plan js */
$(document).on('click', '#saasappoint_add_splan_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($("#saasappoint_add_splan_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var plan_name = $("#saasappoint_splanname").val();
		var plan_rate = $("#saasappoint_splanrate").val();
		var plan_period = $("#saasappoint_splanperiod").val();
		var renewal_type = $("#saasappoint_splantype").val();
		var status = $("input[name='saasappoint_splanstatus']:checked"). val();
		
		$.ajax({
			type: 'post',
			data: {
				'plan_name': plan_name,
				'plan_rate': plan_rate,
				'plan_period': plan_period,
				'renewal_type': renewal_type,
				'status': status,
				'add_subscription_plan': 1
			},
			url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", 'Subscription plan added successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** update subscription plan js */
$(document).on('click', '#saasappoint_update_splan_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	
	/** Validate update subscription plan form **/
	$("#saasappoint_update_splan_form").validate({
		rules: {
			saasappoint_update_splanname:{ required: true, maxlength: 100 },
			saasappoint_update_splanrate: { required:true, pattern_price:true },
			saasappoint_update_splanperiod: { required:true, number:true }
		},
		messages: {
			saasappoint_update_splanname:{ required: "Please enter plan name", maxlength: "Please enter maximum 100 characters" },
			saasappoint_update_splanrate: { required: "Please enter plan rate" },
			saasappoint_update_splanperiod: { required: "Please enter plan period", number: "Please enter only numeric" }
		}
	});
	
	if($("#saasappoint_update_splan_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var id = $(this).data("id");
		var plan_name = $("#saasappoint_update_splanname").val();
		var plan_rate = $("#saasappoint_update_splanrate").val();
		var plan_period = $("#saasappoint_update_splanperiod").val();
		var renewal_type = $("#saasappoint_update_splantype").val();
		
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'plan_name': plan_name,
				'plan_rate': plan_rate,
				'plan_period': plan_period,
				'renewal_type': renewal_type,
				'update_subscription_plan': 1
			},
			url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", 'Subscription plan updated successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** update business type js */
$(document).on('click', '#saasappoint_update_btype_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	
	/** Validate update subscription plan form **/
	$("#saasappoint_update_btype_form").validate({
		rules: {
			saasappoint_update_btypename:{ required: true, maxlength: 100 }
		},
		messages: {
			saasappoint_update_btypename:{ required: "Please enter business type", maxlength: "Please enter maximum 100 characters" }
		}
	});
	
	if($("#saasappoint_update_btype_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var id = $(this).data("id");
		var business_type = $("#saasappoint_update_btypename").val();
		
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'business_type': business_type,
				'update_business_type': 1
			},
			url: ajaxurl + "saasappoint_business_types_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", 'Business type updated successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update subscription plan modal detail JS **/
$(document).on('click', '.saasappoint-update-splanmodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_splan_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
		success: function (res) {
			$(".saasappoint-update-splan-modal-body").html(res);
			$("#saasappoint-update-splan-modal").modal("show");
			$("#saasappoint_update_splan_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Update business type modal detail JS **/
$(document).on('click', '.saasappoint-update-btypemodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_btype_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_business_types_ajax.php",
		success: function (res) {
			$(".saasappoint-update-btype-modal-body").html(res);
			$("#saasappoint-update-btype-modal").modal("show");
			$("#saasappoint_update_btype_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Tab view js */
$(document).on('click', '.saasappoint_tab_view_nav_link', function(){
	var tabNo = $(this).data('tabno');
	$('.custom-nav-item').removeClass('active');
	$(".custom-nav-item:eq("+tabNo+")").addClass("active");
});

/** Change business status JS **/
$(document).on('change', '.saasappoint_change_business_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var status_check = $(this).prop('checked');
	var status_text = 'Deactivated';
	var status = 'N';
	if(status_check){
		status_text = 'Activated';
		status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': status,
			'change_business_status': 1
		},
		url: ajaxurl + "saasappoint_businesses_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				swal(status_text+"!", 'Business status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change business type status JS **/
$(document).on('change', '.saasappoint_change_btype_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var status_check = $(this).prop('checked');
	var status_text = 'Deactivated';
	var status = 'N';
	if(status_check){
		status_text = 'Activated';
		status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': status,
			'change_business_type_status': 1
		},
		url: ajaxurl + "saasappoint_business_types_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				swal(status_text+"!", 'Business type status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change subscription plan status JS **/
$(document).on('change', '.saasappoint_change_splan_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var status_check = $(this).prop('checked');
	var status_text = 'Deactivated';
	var status = 'N';
	if(status_check){
		status_text = 'Activated';
		status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': status,
			'change_splan_status': 1
		},
		url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				swal(status_text+"!", 'Subscription plan status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change Password JS **/
$(document).on('click', '.saasappoint_change_password_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	/** Validate change password form **/
	$('#saasappoint_change_password_form').validate({
		rules: {
			saasappoint_old_password:{ required: true, minlength: 8, maxlength: 20 },
			saasappoint_new_password: { required:true, minlength: 8, maxlength: 20 },
			saasappoint_rtype_password: { required:true, equalTo: "#saasappoint_new_password", minlength: 8, maxlength: 20 }
		},
		messages: {
			saasappoint_old_password:{ required: "Please enter old password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_new_password: { required: "Please enter new password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_rtype_password: { required: "Please enter retype new password", equalTo: "New password and Retype new password mismatch", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" }
		}
	});
	if($("#saasappoint_change_password_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var id = $(this).data('id');
		var old_password = $("#saasappoint_old_password").val();
		var new_password = $("#saasappoint_rtype_password").val();
		$.ajax({
			type: 'post',
			data: {
				'superadmin_id': id,
				'old_password': old_password,
				'new_password': new_password,
				'change_superadmin_password': 1
			},
			url: ajaxurl + "saasappoint_superadmin_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="changed"){
					$("#saasappoint_old_password").val("");
					$("#saasappoint_new_password").val("");
					$("#saasappoint_rtype_password").val("");
					$("#saasappoint-change-password-modal").modal("hide");
					swal("Changed!", 'Your password changed successfully', "success");
				}else if(res=="wrong"){
					swal("Opps!", "Incorrect old password.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update Profile JS **/
$(document).on('click', '.saasappoint_update_profile_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var firstname = $("#saasappoint_profile_firstname").val();
	var lastname = $("#saasappoint_profile_lastname").val();
	var phone = $("#saasappoint_profile_phone").intlTelInput("getNumber");
	var address = $("#saasappoint_profile_address").val();
	var city = $("#saasappoint_profile_city").val();
	var state = $("#saasappoint_profile_state").val();
	var zip = $("#saasappoint_profile_zip").val();
	var country = $("#saasappoint_profile_country").val();
	var id = $("#saasappoint-profile-admin-id-hidden").val();
	
	/** Validate update Profile form **/
	$('#saasappoint_profile_form').validate({
		rules: {
			saasappoint_profile_firstname:{ required: true, maxlength: 50 },
			saasappoint_profile_lastname: { required:true, maxlength: 50 },
			saasappoint_profile_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_profile_address: { required:true },
			saasappoint_profile_city: { required:true },
			saasappoint_profile_state: { required:true },
			saasappoint_profile_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_profile_country: { required:true }
		},
		messages: {
			saasappoint_profile_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_profile_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_profile_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_profile_address: { required: "Please enter address" },
			saasappoint_profile_city: { required: "Please enter city" },
			saasappoint_profile_state: { required: "Please enter state" },
			saasappoint_profile_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_profile_country: { required: "Please enter country" }
		}
	});
	
	if($("#saasappoint_profile_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'firstname': firstname,
				'lastname': lastname,
				'phone': phone,
				'address': address,
				'city': city,
				'state': state,
				'zip': zip,
				'country': country,
				'id': id,
				'update_profile': 1
			},
			url: ajaxurl + "saasappoint_superadmin_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", 'Your profile updated successfully', "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update company settings JS **/
$(document).on('click', '#update_company_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_company_name = $("#saasappoint_company_name").val();
	var saasappoint_company_email = $("#saasappoint_company_email").val();
	var saasappoint_company_phone = $("#saasappoint_company_phone").intlTelInput("getNumber");
	var saasappoint_timezone = $("#saasappoint_timezone").val();
	var saasappoint_date_format = $("#saasappoint_date_format").val();
	var saasappoint_time_format = $("#saasappoint_time_format").val();
	var saasappoint_currency = $("#saasappoint_currency").val();
	var saasappoint_currency_symbol = $("#saasappoint_currency option:selected").data("symbol");
	
	/** Validate company settings form **/
	$('#saasappoint_company_settings_form').validate({
		rules: {
			saasappoint_company_name:{ required: true },
			saasappoint_company_email:{ required: true, email: true },
			saasappoint_company_phone:{ required: true, minlength: 10, maxlength: 15, pattern_phone:true }
		},
		messages: {
			saasappoint_company_name:{ required: "Please enter company name" },
			saasappoint_company_email:{ required: "Please enter company email", email: "Please enter valid email" },
			saasappoint_company_phone:{ required: "Please enter company phone", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" }
		}
	});
	
	if($("#saasappoint_company_settings_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_company_name': saasappoint_company_name,
				'saasappoint_company_email': saasappoint_company_email,
				'saasappoint_company_phone': saasappoint_company_phone,
				'saasappoint_currency': saasappoint_currency,
				'saasappoint_currency_symbol': saasappoint_currency_symbol,
				'saasappoint_timezone': saasappoint_timezone,
				'saasappoint_date_format': saasappoint_date_format,
				'saasappoint_time_format': saasappoint_time_format,
				'update_company_settings': 1
			},
			url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Company settings updated successfully', "success");
			}
		});
	}
});

/** Update Email settings JS **/
$(document).on('click', '#update_email_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_email_sender_name = $("#saasappoint_email_sender_name").val();
	var saasappoint_email_sender_email = $("#saasappoint_email_sender_email").val();
	var saasappoint_email_smtp_hostname = $("#saasappoint_email_smtp_hostname").val();
	var saasappoint_email_smtp_username = $("#saasappoint_email_smtp_username").val();
	var saasappoint_email_smtp_password = $("#saasappoint_email_smtp_password").val();
	var saasappoint_email_smtp_port = $("#saasappoint_email_smtp_port").val();
	var saasappoint_email_encryption_type = $("#saasappoint_email_encryption_type").val();
	var saasappoint_email_smtp_authentication = $("#saasappoint_email_smtp_authentication").val();
	
	/** Validate Email settings form **/
	$('#saasappoint_email_settings_form').validate({
		rules: {
			saasappoint_email_sender_name:{ required: true },
			saasappoint_email_sender_email:{ required: true, email: true }
		},
		messages: {
			saasappoint_email_sender_name:{ required: "Please enter sender name" },
			saasappoint_email_sender_email:{ required: "Please enter sender email", email: "Please enter valid email" }
		}
	});
	
	if($("#saasappoint_email_settings_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_email_sender_name': saasappoint_email_sender_name,
				'saasappoint_email_sender_email': saasappoint_email_sender_email,
				'saasappoint_email_smtp_hostname': saasappoint_email_smtp_hostname,
				'saasappoint_email_smtp_username': saasappoint_email_smtp_username,
				'saasappoint_email_smtp_password': saasappoint_email_smtp_password,
				'saasappoint_email_smtp_port': saasappoint_email_smtp_port,
				'saasappoint_email_encryption_type': saasappoint_email_encryption_type,
				'saasappoint_email_smtp_authentication': saasappoint_email_smtp_authentication,
				'update_email_settings': 1
			},
			url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Email settings updated successfully', "success");
			}
		});
	}
});

/** support ticket discussion JS **/
(function () {
    var saasappoint_support_ticket_object;
    saasappoint_support_ticket_object = function (arg) {
        this.text = arg.text, this.saasappoint_support_ticket_reply_side = arg.saasappoint_support_ticket_reply_side;
        this.draw = function (_this) {
            return function () {
                var $message;
                $message = $($('.saasappoint_support_ticket_reply_template').clone().html());
                $message.addClass(_this.saasappoint_support_ticket_reply_side).find('.saasappoint_support_ticket_reply_wrapper_content').html(_this.text);
				
                $('.saasappoint_support_ticket_reply_list').append($message);
                return setTimeout(function () {
                    return $message.addClass('saasappoint_show_support_ticket_reply');
                }, 0);
            };
        }(this);
        return this;
    };
    $(function () {
        var saasappoint_get_support_ticket_reply, saasappoint_support_ticket_reply_side, saasappoint_send_support_ticket_reply;
        saasappoint_support_ticket_reply_side = 'saasappoint_show_support_ticket_on_right';
        saasappoint_get_support_ticket_reply = function () {
            var $saasappoint_support_ticket_reply_input;
            $saasappoint_support_ticket_reply_input = $('.saasappoint_support_ticket_reply_input');
            return $saasappoint_support_ticket_reply_input.val();
        };
        saasappoint_send_support_ticket_reply = function (text, ticket_id) {
            var $saasappoint_support_ticket_reply_list, message;
            if (text.trim() === '') {
                return;
            }
			
			/** Add ticket discussion reply JS start */
			var ajaxurl = generalObj.ajax_url;
			$.ajax({
				type: 'post',
				data: {
					'reply': text,
					'ticket_id': ticket_id,
					'add_ticket_discussion_reply': 1
				},
				url: ajaxurl + "saasappoint_superadmin_support_ticket_discussions_ajax.php",
				success: function (res) { 
				}
			});
			/** Add ticket discussion reply JS end */
			
            $('.saasappoint_support_ticket_reply_input').val('');
            $saasappoint_support_ticket_reply_list = $('.saasappoint_support_ticket_reply_list');
            saasappoint_support_ticket_reply_side = 'saasappoint_show_support_ticket_on_right';
            message = new saasappoint_support_ticket_object({
                text: text,
                saasappoint_support_ticket_reply_side: saasappoint_support_ticket_reply_side
            });
            message.draw();
			$(".saasappoint_remove_empty_discussion_li").remove();
            return $saasappoint_support_ticket_reply_list.animate({ scrollTop: $saasappoint_support_ticket_reply_list.prop('scrollHeight') }, 300);		
        };
        $('.saasappoint_support_ticket_send_reply_btndiv').click(function (e) {
			var ticket_id = $(this).data("id");
            return saasappoint_send_support_ticket_reply(saasappoint_get_support_ticket_reply(), ticket_id);
        });
        $('.saasappoint_support_ticket_reply_input').keyup(function (e) {
            if (e.which === 13) {
                var ticket_id = $(this).data("id");
				return saasappoint_send_support_ticket_reply(saasappoint_get_support_ticket_reply(), ticket_id);
            }
        });
    });
}.call(this));

/** Update support ticket JS **/
$(document).on('click', '#saasappoint_update_support_ticket_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	/** Validate update support ticket form **/
	$('#saasappoint_update_support_ticket_form').validate({
		rules: {
			saasappoint_update_tickettitle:{ required: true },
			saasappoint_update_ticketdescription:{ required: true }
		},
		messages: {
			saasappoint_update_tickettitle:{ required: "Please enter ticket title" },
			saasappoint_update_ticketdescription:{ required: "Please enter ticket description" }
		}
	});
	if($('#saasappoint_update_support_ticket_form').valid()){
		var id = $(this).data("id");
		var title = $("#saasappoint_update_tickettitle").val();
		var description = $("#saasappoint_update_ticketdescription").val();
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'ticket_title': title,
				'description': description,
				'update_support_ticket': 1
			},
			url: ajaxurl + "saasappoint_superadmin_support_tickets_ajax.php",
			success: function (res) {
				$("#saasappoint-update-ticket-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("updated!", "Support ticket updated successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update support ticket modal detail JS **/
$(document).on('click', '.saasappoint-update-supportticketmodal', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_supportticket_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_superadmin_support_tickets_ajax.php",
		success: function (res) {
			$(".saasappoint-update-ticket-modal-body").html(res);
			$("#saasappoint-update-ticket-modal").modal("show");
			$("#saasappoint_update_support_ticket_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Mark as read all support ticket reply modal detail JS **/
$(document).on('click', '.markasread_all_support_ticket_reply', function(){
	var site_url = generalObj.site_url;
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'markasread_all_support_ticket_reply': 1
		},
		url: ajaxurl + "saasappoint_superadmin_support_tickets_ajax.php",
		success: function (res) {
			window.location.href = site_url+'backend/s-ticket-discussion.php?tid='+id;
		}
	});
});

/** Delete support ticket JS **/
$(document).on('click', '.saasappoint_delete_support_ticket_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this support ticket",
	  type: "error",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'delete_support_ticket': 1
			},
			url: ajaxurl + "saasappoint_superadmin_support_tickets_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Support ticket deleted successfully.", "success");
					location.reload();
				}else if(res=="replyexist"){
					swal("Opps!", "You cannot delete this support ticket. You have discussion on this support ticket", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Mark support ticket as completed JS **/
$(document).on('click', '.saasappoint_markascomplete_support_ticket_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to mark this support ticket as complete",
	  type: "success",
	  showCancelButton: true,
	  confirmButtonClass: "btn-success",
	  confirmButtonText: "Yes, Mark as completed!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'markascomplete_support_ticket': 1
			},
			url: ajaxurl + "saasappoint_superadmin_support_tickets_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Marked as completed!", "Support ticket marked as completed successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});
/** Logout JS **/
$(document).on('click','#saasappoint_logout_btn',function(){
	var ajax_url = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	$.ajax({
		type: 'post',
		data: {
			'logout_process': 1
		},
		url: ajax_url + "saasappoint_login_ajax.php",
		success: function (res) {
			window.location = site_url+"backend";
		}
	});
});
/** Admin auto login JS **/
$(document).on('click','#saasappoint_admin_autologin',function(){
	var ajax_url = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'autologin_process': 1
		},
		url: ajax_url + "saasappoint_autologin_ajax.php",
		success: function (res) {
			if(res.trim() == "admin"){
				window.location.replace(site_url+"backend/appointments.php");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Delete subscription plan JS **/
$(document).on('click', '.saasappoint-delete-splan-btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this subscription plan",
	  type: "error",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'delete_subscription_plan': 1
			},
			url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Subscription plan deleted successfully.", "success");
					location.reload();
				}else if(res=="exist"){
					swal("Opps!", "You cannot delete this subscription plan. There is already subscription on this plan.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Delete business type JS **/
$(document).on('click', '.saasappoint-delete-btype-btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this business type",
	  type: "error",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'delete_business_type': 1
			},
			url: ajaxurl + "saasappoint_business_types_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Business type deleted successfully.", "success");
					location.reload();
				}else if(res=="exist"){
					swal("Opps!", "You cannot delete this business type. You have already running businesses on this plan.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});


/** Change Profile email JS **/
$(document).on('click', '#saasappoint_change_profile_email_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	/** Validate Change Email form **/
	$('#saasappoint_change_profile_email_form').validate({
		rules: {
			saasappoint_change_profile_email:{ required: true, email: true }
		},
		messages: {
			saasappoint_change_profile_email:{ required: "Please enter email", email: "Please enter valid email" }
		}
	});
	if($("#saasappoint_change_profile_email_form").valid()){
		var email = $("#saasappoint_change_profile_email").val();
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'change_email': 1
			},
			url: ajaxurl + "saasappoint_superadmin_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Changed!", 'Your email changed successfully', "success");
					location.reload();
				}else if(res=="exist"){
					swal("Exist!", "Email already exist. Please try to update with not registered email.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Prevent enter key stroke on form inputs **/
$(document).on("keydown", '.saasappoint form input', function (e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
});


/** Register as Admin JS **/
$(document).on('click', '#saasappoint_add_new_business_btn', function(e){
	e.preventDefault();
	var site_url = generalObj.site_url;
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_add_new_business_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var selected_plan = $(".saasappoint_add_business_plans_radio:checked").val();
		var firstname = $("#saasappoint_add_business_admin_firstname").val();
		var lastname = $("#saasappoint_add_business_admin_lastname").val();
		var email = $("#saasappoint_add_business_admin_email").val();
		var password = $("#saasappoint_add_business_admin_password").val();
		var phone = $("#saasappoint_add_business_admin_phone").intlTelInput("getNumber");
		var business_type_id = $("#saasappoint_add_business_admin_businesstype").val();
		var address = $("#saasappoint_add_business_admin_address").val();
		var city = $("#saasappoint_add_business_admin_city").val();
		var state = $("#saasappoint_add_business_admin_state").val();
		var zip = $("#saasappoint_add_business_admin_zip").val();
		var country = $("#saasappoint_add_business_admin_country").val();
		
		var companyname = $("#saasappoint_add_business_admin_companyname").val();
		var companyemail = $("#saasappoint_add_business_admin_companyemail").val();
		var companyphone = $("#saasappoint_add_business_admin_companyphone").intlTelInput("getNumber");
		var companyaddress = $("#saasappoint_add_business_admin_companyaddress").val();
		var companycity = $("#saasappoint_add_business_admin_companycity").val();
		var companystate = $("#saasappoint_add_business_admin_companystate").val();
		var companyzip = $("#saasappoint_add_business_admin_companyzip").val();
		var companycountry = $("#saasappoint_add_business_admin_companycountry").val();
		
		if(selected_plan !== undefined){
			$.ajax({
				type: 'post',
				data: {
					'id': selected_plan,
					'check_selected_plan': 1
				},
				url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
				success: function (res) {
					if(res == "notexist"){
						swal("Opps!", "Your selected subscription plan does not exist. Please try again.", "error");
					}else{
						
						$.ajax({
							type: 'post',
							data: {
								'plan_id': selected_plan,
								'firstname': firstname,
								'lastname': lastname,
								'email': email,
								'password': password,
								'phone': phone,
								'business_type_id': business_type_id,
								'address': address,
								'city': city,
								'state': state,
								'zip': zip,
								'country': country,
								'companyname': companyname,
								'companyemail': companyemail,
								'companyphone': companyphone,
								'companyaddress': companyaddress,
								'companycity': companycity,
								'companystate': companystate,
								'companyzip': companyzip,
								'companycountry': companycountry,
								'add_business_admin': 1
							},
							url: ajaxurl + "saasappoint_add_new_business_ajax.php",
							success: function (response) {
								$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
								if(response=="added"){
									swal("Added!", 'New business added successfully.', "success");
									window.location.replace(site_url+"backend");
								}else{
									swal("Opps!", "Something went wrong. Please try again.", "error");
								}
							}
						});
					}
				}
			});
		}else{
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Opps!", "Please select subscription plan.", "error");
		}
	}
});

/** Update Payment settings JS **/
$(document).on('click', '#update_payment_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var payment = $(this).data("payment");
	
	/** Paypal payment settings **/
	if(payment == "1"){
		var saasappoint_paypal_payment_status = 'N';
		var saasappoint_paypal_guest_payment = 'N';
		
		var saasappoint_paypal_payment_status_check = $("#saasappoint_paypal_payment_status").prop('checked');
		var saasappoint_paypal_guest_payment_check = $("#saasappoint_paypal_guest_payment").prop('checked');
		
		if(saasappoint_paypal_payment_status_check){
			saasappoint_paypal_payment_status = 'Y';
		}
		if(saasappoint_paypal_guest_payment_check){
			saasappoint_paypal_guest_payment = 'Y';
		}
		
		var saasappoint_paypal_api_username = $("#saasappoint_paypal_api_username").val();
		var saasappoint_paypal_api_password = $("#saasappoint_paypal_api_password").val();
		var saasappoint_paypal_signature = $("#saasappoint_paypal_signature").val();
		
		/** Validate Paypal payment form **/
		$("#saasappoint_paypal_payment_settings_form").validate();
		if(saasappoint_paypal_payment_status == "Y"){
			$("#saasappoint_paypal_api_username").rules("add", {
				required: true,
				messages: { required: "Please enter API username" }
			});
			$("#saasappoint_paypal_api_password").rules("add", {
				required: true,
				messages: { required: "Please enter API password" }
			});
			$("#saasappoint_paypal_signature").rules("add", {
				required: true, 
				messages: { required: "Please enter signature" }
			});
		}else{
			$("#saasappoint_paypal_api_username").rules("add", {
				required: false,
				messages: { required: "Please enter API username" }
			});
			$("#saasappoint_paypal_api_password").rules("add", {
				required: false,
				messages: { required: "Please enter API password" }
			});
			$("#saasappoint_paypal_signature").rules("add", {
				required: false, 
				messages: { required: "Please enter signature" }
			});
		}
		if($("#saasappoint_paypal_payment_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_paypal_payment_status': saasappoint_paypal_payment_status,
					'saasappoint_paypal_guest_payment': saasappoint_paypal_guest_payment,
					'saasappoint_paypal_api_username': saasappoint_paypal_api_username,
					'saasappoint_paypal_api_password': saasappoint_paypal_api_password,
					'saasappoint_paypal_signature': saasappoint_paypal_signature,
					'update_paypal_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-payment-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Paypal payment settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** Stripe payment settings **/
	else if(payment == "2"){
		var saasappoint_stripe_payment_status = 'N';
		var saasappoint_stripe_payment_status_check = $("#saasappoint_stripe_payment_status").prop('checked');
		if(saasappoint_stripe_payment_status_check){
			saasappoint_stripe_payment_status = 'Y';
		}
		var saasappoint_stripe_secret_key = $("#saasappoint_stripe_secret_key").val();
		var saasappoint_stripe_publishable_key = $("#saasappoint_stripe_publishable_key").val();
		
		/** Validate payment form **/
		$("#saasappoint_stripe_payment_settings_form").validate();
		if(saasappoint_stripe_payment_status == "Y"){
			$("#saasappoint_stripe_secret_key").rules("add", {
				required: true,
				messages: { required: "Please enter secret key" }
			});
			$("#saasappoint_stripe_publishable_key").rules("add", {
				required: true,
				messages: { required: "Please enter publishable key" }
			});
		}else{
			$("#saasappoint_stripe_secret_key").rules("add", {
				required: false,
				messages: { required: "Please enter secret key" }
			});
			$("#saasappoint_stripe_publishable_key").rules("add", {
				required: false,
				messages: { required: "Please enter publishable key" }
			});
		}
		if($("#saasappoint_stripe_payment_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_stripe_payment_status': saasappoint_stripe_payment_status,
					'saasappoint_stripe_secret_key': saasappoint_stripe_secret_key,
					'saasappoint_stripe_publishable_key': saasappoint_stripe_publishable_key,
					'update_stripe_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-payment-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Stripe payment settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** Authorize.net payment settings **/
	else if(payment == "3"){
		var saasappoint_authorizenet_payment_status = 'N';
		var saasappoint_authorizenet_payment_status_check = $("#saasappoint_authorizenet_payment_status").prop('checked');
		if(saasappoint_authorizenet_payment_status_check){
			saasappoint_authorizenet_payment_status = 'Y';
		}
		var saasappoint_authorizenet_api_login_id = $("#saasappoint_authorizenet_api_login_id").val();
		var saasappoint_authorizenet_transaction_key = $("#saasappoint_authorizenet_transaction_key").val();
		
		/** Validate payment form **/
		$('#saasappoint_authorizenet_payment_settings_form').validate();
		if(saasappoint_authorizenet_payment_status == "Y"){
			$("#saasappoint_authorizenet_api_login_id").rules("add", {
				required: true,
				messages: { required: "Please enter API login ID" }
			});
			$("#saasappoint_authorizenet_transaction_key").rules("add", {
				required: true,
				messages: { required: "Please enter transaction key" }
			});
		}else{
			$("#saasappoint_authorizenet_api_login_id").rules("add", {
				required: false,
				messages: { required: "Please enter API login ID" }
			});
			$("#saasappoint_authorizenet_transaction_key").rules("add", {
				required: false,
				messages: { required: "Please enter transaction key" }
			});
		}
		if($("#saasappoint_authorizenet_payment_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_authorizenet_payment_status': saasappoint_authorizenet_payment_status,
					'saasappoint_authorizenet_api_login_id': saasappoint_authorizenet_api_login_id,
					'saasappoint_authorizenet_transaction_key': saasappoint_authorizenet_transaction_key,
					'update_authorizenet_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-payment-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Authorize.net payment settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** 2Checkout payment settings **/
	else if(payment == "4"){
		var saasappoint_twocheckout_payment_status = 'N';
		var saasappoint_twocheckout_payment_status_check = $("#saasappoint_twocheckout_payment_status").prop('checked');
		if(saasappoint_twocheckout_payment_status_check){
			saasappoint_twocheckout_payment_status = 'Y';
		}
		var saasappoint_twocheckout_publishable_key = $("#saasappoint_twocheckout_publishable_key").val();
		var saasappoint_twocheckout_private_key = $("#saasappoint_twocheckout_private_key").val();
		var saasappoint_twocheckout_seller_id = $("#saasappoint_twocheckout_seller_id").val();
		
		/** Validate payment form **/
		$('#saasappoint_twocheckout_payment_settings_form').validate();
		if(saasappoint_twocheckout_payment_status == "Y"){
			$("#saasappoint_twocheckout_publishable_key").rules("add", {
				required: true,
				messages: { required: "Please enter publishable key" }
			});
			$("#saasappoint_twocheckout_private_key").rules("add", {
				required: true,
				messages: { required: "Please enter private key" }
			});
			$("#saasappoint_twocheckout_seller_id").rules("add", {
				required: true,
				messages: { required: "Please enter seller ID" }
			});
		}else{
			$("#saasappoint_twocheckout_publishable_key").rules("add", {
				required: false,
				messages: { required: "Please enter publishable key" }
			});
			$("#saasappoint_twocheckout_private_key").rules("add", {
				required: false,
				messages: { required: "Please enter private key" }
			});
			$("#saasappoint_twocheckout_seller_id").rules("add", {
				required: false,
				messages: { required: "Please enter seller ID" }
			});
		}
		if($("#saasappoint_twocheckout_payment_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_twocheckout_payment_status': saasappoint_twocheckout_payment_status,
					'saasappoint_twocheckout_publishable_key': saasappoint_twocheckout_publishable_key,
					'saasappoint_twocheckout_private_key': saasappoint_twocheckout_private_key,
					'saasappoint_twocheckout_seller_id': saasappoint_twocheckout_seller_id,
					'update_twocheckout_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-payment-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", '2Checkout payment settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
});

/** Payment Collapsible JS **/
$(document).on("click", ".saasappoint_payment_settings_sadmin", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	
	$(".saasappoint_payment_settings_sadmin").each(function(){
		if($(this).attr("id") != "saasappoint_payment_settings_sadmin_"+id){
			$(this).removeClass("saasappoint-boxshadow_active");
		}
	});
	if(!$("#saasappoint_payment_settings_sadmin_"+id).hasClass("saasappoint-boxshadow_active")){
		$(this).addClass("saasappoint-boxshadow_active");
	}
	$.ajax({
		type: 'post',
		data: {
			'get_payment_settings': id
		},
		url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
		success: function (res) {
			$("#update_payment_settings_btn").attr("data-payment", id)
			$(".saasappoint-payment-setting-form-modal-content").html(res);
			$("#saasappoint-payment-setting-form-modal").modal("show");
		}
	});
});

/** Add SMS plan js */
$(document).on('click', '#saasappoint_add_smsplan_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($("#saasappoint_add_smsplan_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var plan_name = $("#saasappoint_smsplanname").val();
		var plan_rate = $("#saasappoint_smsplanrate").val();
		var credit = $("#saasappoint_smsplancredit").val();
		var status = $("input[name='saasappoint_smsplanstatus']:checked"). val();
		
		$.ajax({
			type: 'post',
			data: {
				'plan_name': plan_name,
				'plan_rate': plan_rate,
				'credit': credit,
				'status': status,
				'add_sms_plan': 1
			},
			url: ajaxurl + "saasappoint_superadmin_sms_plans.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", 'SMS plan added successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** update SMS plan js */
$(document).on('click', '#saasappoint_update_smsplan_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	
	/** Validate update subscription plan form **/
	$("#saasappoint_update_smsplan_form").validate({
		rules: {
			saasappoint_update_smsplanname:{ required: true, maxlength: 100 },
			saasappoint_update_smsplanrate: { required:true, pattern_price:true },
			saasappoint_update_smscredit: { required:true, number:true }
		},
		messages: {
			saasappoint_update_smsplanname:{ required: "Please enter plan name", maxlength: "Please enter maximum 100 characters" },
			saasappoint_update_smsplanrate: { required: "Please enter plan rate" },
			saasappoint_update_smscredit: { required: "Please enter SMS credit", number: "Please enter only numeric" }
		}
	});
	
	if($("#saasappoint_update_smsplan_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var id = $(this).data("id");
		var plan_name = $("#saasappoint_update_smsplanname").val();
		var plan_rate = $("#saasappoint_update_smsplanrate").val();
		var credit = $("#saasappoint_update_smscredit").val();
		
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'plan_name': plan_name,
				'plan_rate': plan_rate,
				'credit': credit,
				'update_sms_plan': 1
			},
			url: ajaxurl + "saasappoint_superadmin_sms_plans.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", 'SMS plan updated successfully', "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update SMS plan modal detail JS **/
$(document).on('click', '.saasappoint-update-smsplanmodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_smsplan_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_superadmin_sms_plans.php",
		success: function (res) {
			$(".saasappoint-update-smsplan-modal-body").html(res);
			$("#saasappoint-update-smsplan-modal").modal("show");
			$("#saasappoint_update_smsplan_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Delete SMS plan JS **/
$(document).on('click', '.saasappoint-delete-smsplan-btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this SMS plan",
	  type: "error",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'delete_sms_plan': 1
			},
			url: ajaxurl + "saasappoint_superadmin_sms_plans.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "SMS plan deleted successfully.", "success");
					location.reload();
				}else if(res=="exist"){
					swal("Opps!", "You cannot delete this SMS plan. There is already subscription on this plan.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
}); 

/** SMS Settings JS **/
$(document).on("click", ".saasappoint_sms_settings_sadmin", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	
	$(".saasappoint_sms_settings_sadmin").each(function(){
		if($(this).attr("id") != "saasappoint_collapsible_"+id){
			$(this).removeClass("saasappoint-boxshadow_active");
		}
	});
	if(!$("#saasappoint_sms_settings_sadmin_"+id).hasClass("saasappoint-boxshadow_active")){
		$(this).addClass("saasappoint-boxshadow_active");
	}
	$.ajax({
		type: 'post',
		data: {
			'get_sms_settings': id
		},
		url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
		success: function (res) {
			$("#update_sms_settings_btn").attr("data-sms", id)
			$(".saasappoint-sms-setting-form-modal-content").html(res);
			$("#saasappoint-sms-setting-form-modal").modal("show");
		}
	});
});

/** Update SMS settings JS **/
$(document).on('click', '#update_sms_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var sms = $(this).data("sms");
	
	/** Twilio SMS settings **/
	if(sms == "1"){
		var saasappoint_twilio_sms_status = 'N';
		var saasappoint_twilio_sms_status_check = $("#saasappoint_twilio_sms_status").prop('checked');
		if(saasappoint_twilio_sms_status_check){
			saasappoint_twilio_sms_status = 'Y';
		}
		
		var saasappoint_twilio_account_SID = $("#saasappoint_twilio_account_SID").val();
		var saasappoint_twilio_auth_token = $("#saasappoint_twilio_auth_token").val();
		var saasappoint_twilio_sender_number = $("#saasappoint_twilio_sender_number").intlTelInput("getNumber");
		
		/** Validate SMS form **/
		$("#saasappoint_twilio_sms_settings_form").validate();
		if(saasappoint_twilio_sms_status == "Y"){
			$("#saasappoint_twilio_account_SID").rules("add", {
				required: true,
				messages: { required: "Please enter account SID" }
			});
			$("#saasappoint_twilio_auth_token").rules("add", {
				required: true,
				messages: { required: "Please enter auth token" }
			});
			$("#saasappoint_twilio_sender_number").rules("add", {
				required: true, minlength: 10, maxlength: 15, pattern_phone:true, 
				messages: { required: "Please enter sender number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" }
			});
		}else{
			$("#saasappoint_twilio_account_SID").rules("add", {
				required: false,
				messages: { required: "Please enter account SID" }
			});
			$("#saasappoint_twilio_auth_token").rules("add", {
				required: false,
				messages: { required: "Please enter auth token" }
			});
			$("#saasappoint_twilio_sender_number").rules("add", {
				required: false, minlength: 10, maxlength: 15, pattern_phone:true, 
				messages: { required: "Please enter sender number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" }
			});
		}
		if($("#saasappoint_twilio_sms_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_twilio_sms_status': saasappoint_twilio_sms_status,
					'saasappoint_twilio_account_SID': saasappoint_twilio_account_SID,
					'saasappoint_twilio_auth_token': saasappoint_twilio_auth_token,
					'saasappoint_twilio_sender_number': saasappoint_twilio_sender_number,
					'update_twilio_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-sms-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Twilio SMS settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** Plivo SMS settings **/
	else if(sms == "2"){
		var saasappoint_plivo_sms_status = 'N';
		var saasappoint_plivo_sms_status_check = $("#saasappoint_plivo_sms_status").prop('checked');
		if(saasappoint_plivo_sms_status_check){
			saasappoint_plivo_sms_status = 'Y';
		}
		
		var saasappoint_plivo_account_SID = $("#saasappoint_plivo_account_SID").val();
		var saasappoint_plivo_auth_token = $("#saasappoint_plivo_auth_token").val();
		var saasappoint_plivo_sender_number = $("#saasappoint_plivo_sender_number").intlTelInput("getNumber");
		
		/** Validate SMS form **/
		$("#saasappoint_plivo_sms_settings_form").validate();
		if(saasappoint_plivo_sms_status == "Y"){
			$("#saasappoint_plivo_account_SID").rules("add", {
				required: true,
				messages: { required: "Please enter account SID" }
			});
			$("#saasappoint_plivo_auth_token").rules("add", {
				required: true,
				messages: { required: "Please enter auth token" }
			});
			$("#saasappoint_plivo_sender_number").rules("add", {
				required: true, minlength: 10, maxlength: 15, pattern_phone:true, 
				messages: { required: "Please enter sender number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" }
			});
		}else{
			$("#saasappoint_plivo_account_SID").rules("add", {
				required: false,
				messages: { required: "Please enter account SID" }
			});
			$("#saasappoint_plivo_auth_token").rules("add", {
				required: false,
				messages: { required: "Please enter auth token" }
			});
			$("#saasappoint_plivo_sender_number").rules("add", {
				required: false, minlength: 10, maxlength: 15, pattern_phone:true, 
				messages: { required: "Please enter sender number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" }
			});
		}
		if($("#saasappoint_plivo_sms_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_plivo_sms_status': saasappoint_plivo_sms_status,
					'saasappoint_plivo_account_SID': saasappoint_plivo_account_SID,
					'saasappoint_plivo_auth_token': saasappoint_plivo_auth_token,
					'saasappoint_plivo_sender_number': saasappoint_plivo_sender_number,
					'update_plivo_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-sms-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Plivo SMS settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** Nexmo SMS settings **/
	else if(sms == "3"){
		var saasappoint_nexmo_sms_status = 'N';
		var saasappoint_nexmo_sms_status_check = $("#saasappoint_nexmo_sms_status").prop('checked');
		if(saasappoint_nexmo_sms_status_check){
			saasappoint_nexmo_sms_status = 'Y';
		}
		
		var saasappoint_nexmo_api_key = $("#saasappoint_nexmo_api_key").val();
		var saasappoint_nexmo_api_secret = $("#saasappoint_nexmo_api_secret").val();
		var saasappoint_nexmo_from = $("#saasappoint_nexmo_from").val();
		
		/** Validate SMS form **/
		$("#saasappoint_nexmo_sms_settings_form").validate();
		if(saasappoint_nexmo_sms_status == "Y"){
			$("#saasappoint_nexmo_api_key").rules("add", {
				required: true,
				messages: { required: "Please enter API key" }
			});
			$("#saasappoint_nexmo_api_secret").rules("add", {
				required: true,
				messages: { required: "Please enter API secret" }
			});
			$("#saasappoint_nexmo_from").rules("add", {
				required: true, 
				messages: { required: "Please enter nexmo from" }
			});
		}else{
			$("#saasappoint_nexmo_api_key").rules("add", {
				required: false,
				messages: { required: "Please enter API key" }
			});
			$("#saasappoint_nexmo_api_secret").rules("add", {
				required: false,
				messages: { required: "Please enter API secret" }
			});
			$("#saasappoint_nexmo_from").rules("add", {
				required: false, 
				messages: { required: "Please enter nexmo from" }
			});
		}
		if($("#saasappoint_nexmo_sms_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_nexmo_sms_status': saasappoint_nexmo_sms_status,
					'saasappoint_nexmo_api_key': saasappoint_nexmo_api_key,
					'saasappoint_nexmo_api_secret': saasappoint_nexmo_api_secret,
					'saasappoint_nexmo_from': saasappoint_nexmo_from,
					'update_nexmo_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-sms-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'Nexmo SMS settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
	/** TextLocal SMS settings **/
	else if(sms == "4"){
		var saasappoint_textlocal_sms_status = 'N';
		var saasappoint_textlocal_sms_status_check = $("#saasappoint_textlocal_sms_status").prop('checked');
		if(saasappoint_textlocal_sms_status_check){
			saasappoint_textlocal_sms_status = 'Y';
		}
		
		var saasappoint_textlocal_api_key = $("#saasappoint_textlocal_api_key").val();
		var saasappoint_textlocal_sender = $("#saasappoint_textlocal_sender").val();
		var saasappoint_textlocal_country = $("#saasappoint_textlocal_country").val();
		
		/** Validate SMS form **/
		$("#saasappoint_textlocal_sms_settings_form").validate();
		if(saasappoint_textlocal_sms_status == "Y"){
			$("#saasappoint_textlocal_api_key").rules("add", {
				required: true,
				messages: { required: "Please enter API key" }
			});
			$("#saasappoint_textlocal_sender").rules("add", {
				required: true,
				messages: { required: "Please enter textlocal sender" }
			});
			$("#saasappoint_textlocal_country").rules("add", {
				required: true, 
				messages: { required: "Please select country" }
			});
		}else{
			$("#saasappoint_textlocal_api_key").rules("add", {
				required: false,
				messages: { required: "Please enter API key" }
			});
			$("#saasappoint_textlocal_sender").rules("add", {
				required: false,
				messages: { required: "Please enter textlocal sender" }
			});
			$("#saasappoint_textlocal_country").rules("add", {
				required: false, 
				messages: { required: "Please select country" }
			});
		}
		if($("#saasappoint_textlocal_sms_settings_form").valid()){
			$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'saasappoint_textlocal_sms_status': saasappoint_textlocal_sms_status,
					'saasappoint_textlocal_api_key': saasappoint_textlocal_api_key,
					'saasappoint_textlocal_sender': saasappoint_textlocal_sender,
					'saasappoint_textlocal_country': saasappoint_textlocal_country,
					'update_textlocal_settings': 1
				},
				url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
				success: function (res) {
					$("#saasappoint-sms-setting-form-modal").modal("hide");
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Updated!", 'TextLocal SMS settings updated successfully', "success");
					location.reload();
				}
			});
		}
	}
});


/** Change reminder buffer time JS **/
$(document).on('change', '#saasappoint_reminder_buffer_time', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var rbtime = $(this).val();
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_reminder_buffer_time': rbtime,
			'change_reminder_buffer_time': 1
		},
		url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Changed!", 'Appointment reminder buffer time changed successfully', "success");
		}
	});
});

function saasappoint_second_read_uploaded_file_url(input) {
    if (input.files && input.files[0]) {
		if((input.files[0].size/1000) > 1000){
			swal("Opps!", "Maximum file upload size 1 MB.", "error");
		}else if(input.files[0].type =="image/jpeg" || input.files[0].type =="image/jpg" || input.files[0].type =="image/png"){
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#saasappoint_seo_og_tag_image-hidden').val(e.target.result);
				$('#saasappoint_seo_og_tag_image-preview').css('background-image', 'url('+e.target.result +')');
				$('#saasappoint_seo_og_tag_image-preview').hide();
				$('#saasappoint_seo_og_tag_image-preview').fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}else{
			swal("Opps!", "Please select a valid image file (jpeg, jpg and png are allowed).", "error");
		}
    }
}
$(document).on('change', "#saasappoint_seo_og_tag_image", function() {
    saasappoint_second_read_uploaded_file_url(this);
});

/** Update SEO settings JS **/
$(document).on('click', '#update_seo_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var uploaded_file = $("#saasappoint_seo_og_tag_image-hidden").val();
	var saasappoint_seo_ga_code = $("#saasappoint_seo_ga_code").val();
	var saasappoint_seo_meta_tag = $("#saasappoint_seo_meta_tag").val();
	var saasappoint_seo_meta_description = $("#saasappoint_seo_meta_description").val();
	var saasappoint_seo_og_meta_tag = $("#saasappoint_seo_og_meta_tag").val();
	var saasappoint_seo_og_tag_type = $("#saasappoint_seo_og_tag_type").val();
	var saasappoint_seo_og_tag_url = $("#saasappoint_seo_og_tag_url").val();
	
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_seo_ga_code': saasappoint_seo_ga_code,
			'saasappoint_seo_meta_tag': saasappoint_seo_meta_tag,
			'saasappoint_seo_meta_description': saasappoint_seo_meta_description,
			'saasappoint_seo_og_meta_tag': saasappoint_seo_og_meta_tag,
			'saasappoint_seo_og_tag_type': saasappoint_seo_og_tag_type,
			'saasappoint_seo_og_tag_url': saasappoint_seo_og_tag_url,
			'uploaded_file': uploaded_file,
			'update_seo_settings': 1
		},
		url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Updated!", 'SEO settings updated successfully', "success");
		}
	});
});

/** Change pay at venue payment status JS **/
$(document).on('change', '#saasappoint_pay_at_venue_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var status_text = "Disabled";
	var saasappoint_pay_at_venue_status = "N";
	if($("#saasappoint_pay_at_venue_status").prop('checked')){
		status_text = "Enabled";
		saasappoint_pay_at_venue_status = "Y";
	} 
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_pay_at_venue_status': saasappoint_pay_at_venue_status,
			'change_pay_at_venue_status': 1
		},
		url: ajaxurl + "saasappoint_superadmin_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal(status_text+"!", 'Pay at Venue payment status successfully', "success");
		}
	});
});



/* Save Points */
/** Change business status JS **/
$(document).on('click', '.saasappoint_save_points', function(){
	var ajaxurl = generalObj.ajax_url;
	var bid = $(this).data('bid');
	var points = $('.bussiness_points'+bid).val();

	if(points=='' || isNaN(points)){
		swal("Opps!", "Please check points value.", "error");
		return false
	}
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	
	$.ajax({
		type: 'post',
		data: {
			'bid': bid,
			'points': points,
			'bussiness_points': 1
		},
		url: ajaxurl + "saasappoint_businesses_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="save"){
				swal("Success!", 'Points saved successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change business point status JS **/
$(document).on('change', '.saasappoint_change_point_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var status_check = $(this).prop('checked');
	var status_text = 'Disabled';
	var status = 'N';
	if(status_check){
		status_text = 'Enabled';
		status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': status,
			'change_business_point_status': 1
		},
		url: ajaxurl + "saasappoint_businesses_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				swal(status_text+"!", 'Point visibility status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Delete Business JS **/
$(document).on('click', '.saasappoint-delete-business-btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this Business [All data related to this business will be lost. We cannot undo once it is done]",
	  type: "error",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'delete_business': 1
			},
			url: ajaxurl + "saasappoint_businesses_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Business deleted successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
}); 