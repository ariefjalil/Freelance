/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
$(document).ready(function(){
	var ajaxurl = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_register_admin_phone, #saasappoint_register_admin_companyphone, #saasappoint_register_customer_phone").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
	
	/** Validation patterns **/
	$.validator.addMethod("pattern_name", function(value, element) {
		return this.optional(element) || /^[a-zA-Z '.']+$/.test(value);
	}, "Please enter only alphabets");
	$.validator.addMethod("pattern_phone", function(value, element) {
		return this.optional(element) || /\d+(?:[ -]*\d+)*$/.test(value);
	}, "Please enter valid phone number [without country code]");
	$.validator.addMethod("pattern_zip", function(value, element) {
		return this.optional(element) || /^[a-zA-Z 0-9\-]*$/.test(value);
	}, "Please enter valid zip");
	
	/** Validate register as admin form **/
	$('#saasappoint_admin_register_form').validate({
		rules: {
			saasappoint_register_admin_firstname:{ required: true, maxlength: 50 },
			saasappoint_register_admin_lastname: { required:true, maxlength: 50},
			saasappoint_register_admin_email:{ required: true, email: true, remote: { 
				url:ajaxurl+"saasappoint_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#saasappoint_register_admin_email").val(); },
					check_email_exist: 1
				}
			} },
			saasappoint_register_admin_password:{ required: true, minlength: 8, maxlength: 20 },
			saasappoint_register_admin_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_register_admin_address: { required:true },
			saasappoint_register_admin_city: { required:true },
			saasappoint_register_admin_state: { required:true },
			saasappoint_register_admin_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_register_admin_country: { required:true },
			saasappoint_register_admin_businesstype: { required:true },
			saasappoint_register_admin_companyname: { required:true, maxlength: 50 },
			saasappoint_register_admin_companyemail:{ required: true, email: true },
			saasappoint_register_admin_companyphone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_register_admin_companyaddress: { required:true },
			saasappoint_register_admin_companycity: { required:true },
			saasappoint_register_admin_companystate: { required:true},
			saasappoint_register_admin_companyzip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_register_admin_companycountry: { required:true }
		},
		messages: {
			saasappoint_register_admin_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_register_admin_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_register_admin_email:{ required: "Please enter email", email: "Please enter valid email", remote: "Email already exist" },
			saasappoint_register_admin_password: { required: "Please enter password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_register_admin_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_register_admin_address: { required: "Please enter address" },
			saasappoint_register_admin_city: { required: "Please enter city" },
			saasappoint_register_admin_state: { required: "Please enter state" },
			saasappoint_register_admin_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_register_admin_country: { required: "Please enter country" },
			saasappoint_register_admin_businesstype: { required: "Please select business type" },
			saasappoint_register_admin_companyname: { required: "Please enter company name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_register_admin_companyemail:{ required: "Please enter company email", email: "Please enter valid email" },
			saasappoint_register_admin_companyphone: { required: "Please enter  company phone", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_register_admin_companyaddress: { required: "Please enter company address" },
			saasappoint_register_admin_companycity: { required: "Please enter company city" },
			saasappoint_register_admin_companystate: { required: "Please enter company state" },
			saasappoint_register_admin_companyzip: { required: "Please enter company zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_register_admin_companycountry: { required: "Please enter company country" },
		}
	});
	
	/** Validate register as customer form **/
	$('#saasappoint_customer_register_form').validate({
		rules: {
			saasappoint_register_customer_firstname:{ required: true, maxlength: 50 },
			saasappoint_register_customer_lastname: { required:true, maxlength: 50 },
			saasappoint_register_customer_email:{ required: true, email: true, remote: { 
				url:ajaxurl+"saasappoint_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#saasappoint_register_customer_email").val(); },
					check_email_exist: 1
				}
			} },
			saasappoint_register_customer_password:{ required: true, minlength: 8, maxlength: 20 },
			saasappoint_register_customer_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_register_customer_address: { required:true },
			saasappoint_register_customer_city: { required:true },
			saasappoint_register_customer_state: { required:true },
			saasappoint_register_customer_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_register_customer_country: { required:true}
		},
		messages: {
			saasappoint_register_customer_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_register_customer_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_register_customer_email:{ required: "Please enter email", email: "Please enter valid email", remote: "Email already exist" },
			saasappoint_register_customer_password: { required: "Please enter password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_register_customer_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_register_customer_address: { required: "Please enter address" },
			saasappoint_register_customer_city: { required: "Please enter city" },
			saasappoint_register_customer_state: { required: "Please enter state" },
			saasappoint_register_customer_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_register_customer_country: { required: "Please enter country" }
		}
	});
	
	/** two checkout configuration **/
	var twocheckout_status = generalObj.twocheckout_status;
	if(twocheckout_status == 'Y'){
		$(function(){ TCO.loadPubKey('sandbox'); });
	}
});

/** stripe check **/
var stripe_status = generalObj.stripe_status;
if(stripe_status == "Y"){
	var stripe_pkey = generalObj.stripe_pkey;
	if(stripe_pkey != ""){
		/* Create a Stripe client. */
		var saasappoint_stripe = Stripe(stripe_pkey);

		/* Create an instance of Elements. */
		var saasappoint_stripe_elements = saasappoint_stripe.elements();

		/* Custom styling can be passed to options when creating an Element. */
		var saasappoint_stripe_plan_style = {
			base: {
				color: '#32325d',
				lineHeight: '18px',
				fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
				fontSmoothing: 'antialiased',
				fontSize: '16px',
				'::placeholder': {
					color: '#aab7c4'
				}
			},
			invalid: {
				color: '#fa755a',
				iconColor: '#fa755a'
			}
		};

		/* Create an instance of the card Element. */
		var saasappoint_stripe_plan_card = saasappoint_stripe_elements.create('card', {style: saasappoint_stripe_plan_style});

		/* Add an instance of the card Element. */
		saasappoint_stripe_plan_card.mount('#saasappoint_stripe_plan_card_errors');
	}
}

/** Register as Admin JS **/
$(document).on('click', '#saasappoint_admin_register_btn', function(e){
	e.preventDefault();
	$("#saasappoint_accept_admin_tandc_error").hide();
	var site_url = generalObj.site_url;
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_admin_register_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var check_tandc = $("#saasappoint_accept_admin_tandc").prop("checked");
		var selected_plan = $(".saasappoint_register_plans_radio:checked").val();
		var firstname = $("#saasappoint_register_admin_firstname").val();
		var lastname = $("#saasappoint_register_admin_lastname").val();
		var email = $("#saasappoint_register_admin_email").val();
		var password = $("#saasappoint_register_admin_password").val();
		var phone = $("#saasappoint_register_admin_phone").intlTelInput("getNumber");
		var business_type_id = $("#saasappoint_register_admin_businesstype").val();
		var address = $("#saasappoint_register_admin_address").val();
		var city = $("#saasappoint_register_admin_city").val();
		var state = $("#saasappoint_register_admin_state").val();
		var zip = $("#saasappoint_register_admin_zip").val();
		var country = $("#saasappoint_register_admin_country").val();
		
		var companyname = $("#saasappoint_register_admin_companyname").val();
		var companyemail = $("#saasappoint_register_admin_companyemail").val();
		var companyphone = $("#saasappoint_register_admin_companyphone").intlTelInput("getNumber");
		var companyaddress = $("#saasappoint_register_admin_companyaddress").val();
		var companycity = $("#saasappoint_register_admin_companycity").val();
		var companystate = $("#saasappoint_register_admin_companystate").val();
		var companyzip = $("#saasappoint_register_admin_companyzip").val();
		var companycountry = $("#saasappoint_register_admin_companycountry").val();
		
		var payment_method = $(".saasappoint_payment_method_radio:checked").val();
		
		if(check_tandc){
			if(selected_plan !== undefined){
				$.ajax({
					type: 'post',
					data: {
						'id': selected_plan,
						'check_selected_plan': 1
					},
					url: ajaxurl + "saasappoint_subscription_plans_ajax.php",
					success: function (res) {
						if(res == "freeplan"){
							saasappoint_register_freeplan(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url);
						}else if(res == "notexist"){
							swal("Opps!", "Your selected subscription plan does not exist. Please try again.", "error");
						}else{
							if(payment_method == "stripe"){
								if(stripe_pkey != "" && stripe_status == "Y"){
									saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
										if (result.error) {
											/* Inform the user if there was an error. */
											$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
											$("#saasappoint_stripe_plan_card_errors").html(result.error.message);
										} else {
											/* Send the token via ajax */
											var token = result.token.id;
											saasappoint_register_stripe(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, token);
											
										}
									});
								}else{
									swal("Opps!", "Please contact super admin to set payment account's credentials.", "error");
								}
							}else if(payment_method == "paypal"){
								saasappoint_register_paypal(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url);
							}else if(payment_method == "authorize.net"){
								var cardnumber = $("#saasappoint-cardnumber").val();
								var cardcvv = $("#saasappoint-cardcvv").val();
								var cardexmonth = $("#saasappoint-cardexmonth").val();
								var cardexyear = $("#saasappoint-cardexyear").val();
								var cardholdername = $("#saasappoint-cardholdername").val();
								
								var cdetail_valid = $.payment.validateCardNumber(cardnumber);
								if (!cdetail_valid) {
									$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
									swal('Opps! Your card number is not valid.', "", "error");
									return false;
								}else{
									var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
									if (!ymdetail_valid) {
										$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
										swal('Opps! Your card expiry is not valid.', "", "error");
										return false;
									}else{
										var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
										if (!cvvdetail_valid) {
											$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
											swal('Opps! Your cvv is not valid.', "", "error");
											return false;
										}else{
											if(cardholdername == ""){
												$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
												swal('Please enter card holder name.', "", "error");
												return false;
											}
										}
									}
								}
								cardnumber = cardnumber.replace(/\s+/g, '');
									
								saasappoint_register_authorizenet(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);
							}else if(payment_method == "2checkout"){
								
								var cardnumber = $("#saasappoint-cardnumber").val();
								var cardcvv = $("#saasappoint-cardcvv").val();
								var cardexmonth = $("#saasappoint-cardexmonth").val();
								var cardexyear = $("#saasappoint-cardexyear").val();
								var cardholdername = $("#saasappoint-cardholdername").val();
								
								var cdetail_valid = $.payment.validateCardNumber(cardnumber);
								if (!cdetail_valid) {
									$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
									swal('Opps! Your card number is not valid.', "", "error");
									return false;
								}else{
									var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
									if (!ymdetail_valid) {
										$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
										swal('Opps! Your card expiry is not valid.', "", "error");
										return false;
									}else{
										var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
										if (!cvvdetail_valid) {
											$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
											swal('Opps! Your cvv is not valid.', "", "error");
											return false;
										}else{
											if(cardholdername == ""){
												$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
												swal('Please enter card holder name.', "", "error");
												return false;
											}
										}
									}
								}
								cardnumber = cardnumber.replace(/\s+/g, '');
								
								var twocheckout_sid = generalObj.twocheckout_sid;
								var twocheckout_pkey = generalObj.twocheckout_pkey;
								/*  Called when token created successfully. */
								function successCallback(data) {
									/* Set the token as the value for the token input */
									var token = data.response.token.token;
									saasappoint_register_twocheckout(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, token);
								};

								/*  Called when token creation fails. */
								function errorCallback(data) {
									if (data.errorCode === 200) {
										$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
										tokenRequest();
									} else {
										$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
										swal(data.errorMsg, "", "error");
									}
								};

								function tokenRequest() {
									/* Setup token request arguments */
									var args = {
										sellerId: twocheckout_sid,
										publishableKey: twocheckout_pkey,
										ccNo: $("#saasappoint-cardnumber").val(),
										cvv: $("#saasappoint-cardcvv").val(),
										expMonth: $("#saasappoint-cardexmonth").val(),
										expYear: $("#saasappoint-cardexyear").val()
									};
									/* Make the token request */
									TCO.requestToken(successCallback, errorCallback, args);
								};

								tokenRequest();
							}else{
								var payment_method = "pay manually";
								saasappoint_register_pay_manually(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url);
							}
						}
					}
				});
			}else{
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$("#saasappoint_register_plans_radio_error").show();
			}
		}else{
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$("#saasappoint_accept_admin_tandc_error").show();
		}
	}
});

$(document).on("change", "#saasappoint_accept_admin_tandc", function(){
	$("#saasappoint_accept_admin_tandc_error").hide();
});
$(document).on("click", ".saasappoint_register_plans_radio", function(){
	$("#saasappoint_register_plans_radio_error").hide();
});

/** Show hide card payemnt box JS **/
$(document).on("change", ".saasappoint_payment_method_radio", function(){
	if($(this).val() == "stripe" || $(this).val() == "authorize.net" || $(this).val() == "2checkout"){
		$(".saasappoint-card-payment-div").fadeIn();
	}else{
		$(".saasappoint-card-payment-div").fadeOut();
	}
});

/** Prevent enter key stroke on form inputs **/
$(document).on("keydown", '.saasappoint form input', function (e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
});

/** free plan registration function **/
function saasappoint_register_freeplan(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url){
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
			'register_admin_freeplan': 1
		},
		url: ajaxurl + "saasappoint_register_ajax.php",
		success: function (response) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="registered"){
				swal("Registered!", 'You are registered successfully. Please login.', "success");
				window.location.replace(site_url+"backend");
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** free plan registration function **/
function saasappoint_register_pay_manually(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url){
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
			'register_admin_pay_manually': 1
		},
		url: ajaxurl + "saasappoint_register_ajax.php",
		success: function (response) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="registered"){
				swal("Registered!", 'You are registered successfully. Please login.', "success");
				window.location.replace(site_url+"backend");
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** paypal registration function **/
function saasappoint_register_paypal(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url){
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
			'register_admin_paypal': 1
		},
		url: ajaxurl + "saasappoint_register_paypal_ajax.php",
		success: function (response) {
			var response_detail = $.parseJSON(response);
			if(response_detail.status=='success'){
				window.location.href = response_detail.value; 
			}
			if(response_detail.status=='error'){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal(response_detail.value, "", "error");
			}
		}
	});
}

/** authorize.net registration function **/
function saasappoint_register_authorizenet(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername){
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
			'cardnumber': cardnumber,
			'cardcvv': cardcvv,
			'cardexmonth': cardexmonth,
			'cardexyear': cardexyear,
			'cardholdername': cardholdername,
			'register_admin_authorizenet': 1
		},
		url: ajaxurl + "saasappoint_register_ajax.php",
		success: function (response) {
			if(response=="registered"){
				swal("Registered!", 'You are registered successfully. Please login.', "success");
				window.location.replace(site_url+"backend");
			} else {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal(response, "", "error");
			}
		}
	});
}

/** stripe registration function **/
function saasappoint_register_stripe(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, token){
	$.ajax({
		type: 'post',
		data: {
			'token': token,
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
			'register_admin_stripe': 1
		},
		url: ajaxurl + "saasappoint_register_ajax.php",
		success: function (response) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="registered"){
				swal("Registered!", 'You are registered successfully. Please login.', "success");
				window.location.replace(site_url+"backend");
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** twocheckout registration function **/
function saasappoint_register_twocheckout(selected_plan, firstname, lastname, email, password, phone, business_type_id, address, city, state, zip, country, companyname, companyemail, companyphone, companyaddress, companycity, companystate, companyzip, companycountry, ajaxurl, site_url, token){
	$.ajax({
		type: 'post',
		data: {
			'token': token,
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
			'register_admin_twocheckout': 1
		},
		url: ajaxurl + "saasappoint_register_ajax.php",
		success: function (response) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="registered"){
				swal("Registered!", 'You are registered successfully. Please login.', "success");
				window.location.replace(site_url+"backend");
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** Register as Customer JS **/
$(document).on('click', '#saasappoint_customer_register_btn', function(e){
	e.preventDefault();
	var site_url = generalObj.site_url;
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_customer_register_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var firstname = $("#saasappoint_register_customer_firstname").val();
		var lastname = $("#saasappoint_register_customer_lastname").val();
		var email = $("#saasappoint_register_customer_email").val();
		var password = $("#saasappoint_register_customer_password").val();
		var phone = $("#saasappoint_register_customer_phone").intlTelInput("getNumber");
		var address = $("#saasappoint_register_customer_address").val();
		var city = $("#saasappoint_register_customer_city").val();
		var state = $("#saasappoint_register_customer_state").val();
		var zip = $("#saasappoint_register_customer_zip").val();
		var country = $("#saasappoint_register_customer_country").val();
		
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'password': password,
				'firstname': firstname,
				'lastname': lastname,
				'phone': phone,
				'address': address,
				'city': city,
				'state': state,
				'zip': zip,
				'country': country,
				'customer_register': 1
			},
			url: ajaxurl + "saasappoint_register_as_customer_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="registered"){
					swal("Registered!", 'You are registered successfully.', "success");
					window.location.replace(site_url+"backend/refer.php");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});