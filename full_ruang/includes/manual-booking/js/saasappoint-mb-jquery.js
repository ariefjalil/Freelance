/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
	
	var site_url = generalObj.site_url;
	/** JS to add intltel input to phone number **/
	$("#saasappoint_user_phone, #saasappoint_guest_phone").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
	
	/** JS to load calendar **/
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'offline': "Y",
			'get_calendar_on_load': 1
		},
		url: ajax_url + "saasappoint_calendar_ajax.php",
		success: function (res) {
			$(".saasappoint-inline-calendar-container").html(res);
		}
	});
	
	/** JS to get frequently discount **/
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'get_all_frequently_discount': 1
		},
		url: ajax_url + "saasappoint_mb_front_ajax.php",
		success: function (res) {
			if(res != ""){
				$("#saasappoint_frequently_discount_content").html(res);
				$(".show_hide_frequently_discount").show();
			}else{
				$("#saasappoint_frequently_discount_content").html("");
				$(".show_hide_frequently_discount").hide();
			}
		}
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

});

$(document).bind("ready ajaxComplete", function(){
	var ajaxurl = generalObj.ajax_url;
    $('[data-toggle="tooltip"]').tooltip();
	
	/** validate user detail form **/
	$("#saasappoint_user_detail_form").validate({
		rules: {
			saasappoint_user_email:{ required: true, email:true, remote: { 
				url:ajaxurl+"saasappoint_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#saasappoint_user_email").val(); },
					check_front_email_exist: 1
				}
			} },
			saasappoint_user_password: { required:true, minlength: 8, maxlength: 20 },
			saasappoint_user_firstname:{ required: true, maxlength: 50 },
			saasappoint_user_lastname: { required:true, maxlength: 50 },
			saasappoint_user_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_user_address: { required:true },
			saasappoint_user_city: { required:true },
			saasappoint_user_state: { required:true },
			saasappoint_user_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_user_country: { required:true }
		},
		messages: {
			saasappoint_user_email:{ required: "Please enter email", email: "Please enter valid email", remote: "Email already exist" },
			saasappoint_user_password: { required: "Please enter password", minlength: "Please enter minimum 8 characters", maxlength: "Please enter maximum 20 characters" },
			saasappoint_user_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_user_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_user_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_user_address: { required: "Please enter address" },
			saasappoint_user_city: { required: "Please enter city" },
			saasappoint_user_state: { required: "Please enter state" },
			saasappoint_user_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_user_country: { required: "Please enter country" }
		}
	});
	
	/** validate guest user detail form **/
	$("#saasappoint_guestuser_detail_form").validate({
		rules: {
			saasappoint_guest_email:{ required: true, email:true },
			saasappoint_guest_firstname:{ required: true, maxlength: 50 },
			saasappoint_guest_lastname: { required:true, maxlength: 50},
			saasappoint_guest_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_guest_address: { required:true },
			saasappoint_guest_city: { required:true },
			saasappoint_guest_state: { required:true },
			saasappoint_guest_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_guest_country: { required:true }
		},
		messages: {
			saasappoint_guest_email:{ required: "Please enter email", email: "Please enter valid email" },
			saasappoint_guest_firstname:{ required: "Please enter first name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_guest_lastname: { required: "Please enter last name", maxlength: "Please enter maximum 50 characters" },
			saasappoint_guest_phone: { required: "Please enter phone number", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_guest_address: { required: "Please enter address" },
			saasappoint_guest_city: { required: "Please enter city" },
			saasappoint_guest_state: { required: "Please enter state" },
			saasappoint_guest_zip: { required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_guest_country: { required: "Please enter country" }
		}
	});
});

/** JS to add multiple qty addons into cart **/
$(document).on("click", ".saasappoint-frequently-discount-change", function(){
	var id = $(this).val();
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_frequently_discount': 1
		},
		url: ajax_url + "saasappoint_mb_front_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
				}
			});
		}
	});
});

/** JS to add multiple qty addons into cart **/
$(document).on("click", ".saasappoint-addons-multipleqty-js-counter-btn", function(){
	var id = $(this).data("id");
	var ajax_url = generalObj.ajax_url;
	if($(this).data('action') == "plus") {
		var qty = Number($('.saasappoint-addons-multipleqty-unit-'+id).val()) + 1;
	}else{
		if($('.saasappoint-addons-multipleqty-unit-'+id).val()>0){
			var qty = Number($('.saasappoint-addons-multipleqty-unit-'+id).val()) - 1;
		}else{
			var qty = 0;
		}
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': qty,
			'add_to_cart_item': 1
		},
		url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
		success: function (res) {
			$(".saasappoint_remove_applied_coupon").trigger("click");
			$(".saasappoint-frequently-discount-change:checked").trigger("click");
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					if(qty>0){
						$('.saasappoint-addons-multipleqty-unit-'+id).val(qty);
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-box-icon').addClass("saasappoint-selected-addon");
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter').addClass("saasappoint-selected-addon");
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter-item-center').addClass("saasappoint-selected-addon");
					}else{
						$('.saasappoint-addons-multipleqty-unit-'+id).val(qty);
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-box-icon').removeClass("saasappoint-selected-addon");
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter').removeClass("saasappoint-selected-addon");
						$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter-item-center').removeClass("saasappoint-selected-addon");
					}
				}
			});
		}
	});
});

/** JS to add single qty addons into cart **/
$(document).on("click", ".saasappoint-addons-singleqty-unit-selection", function(){
	var id = $(this).val();
	var check = $(this).prop("checked");
	var ajax_url = generalObj.ajax_url;
	if(check){
		var qty = 1;
	}else{
		var qty = 0;
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': qty,
			'add_to_cart_item': 1
		},
		url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					if(qty==0){
						$('#saasappoint-addons-singleqty-unit-'+id).prop("checked", false);
					}
				}
			});
		}
	});
});

/** JS to remove item from cart **/
$(document).on("click", ".saasappoint_remove_addon_from_cart", function(){
	var id = $(this).data("id");
	var ajax_url = generalObj.ajax_url;
	var qty = 0;
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': qty,
			'add_to_cart_item': 1
		},
		url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					$('#saasappoint-addons-singleqty-unit-'+id).prop("checked", false);
					$('.saasappoint-addons-multipleqty-unit-'+id).val(qty);
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-box-icon').removeClass("saasappoint-selected-addon");
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter').removeClass("saasappoint-selected-addon");
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter-item-center').removeClass("saasappoint-selected-addon");
				}
			});
		}
	});
});

/** Show hide customer detail box according selection JS **/
$(document).on("change", ".saasappoint-user-selection", function(){
	if($(this).attr("id") == "saasappoint-new-user"){
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-guest-user-box").slideUp(1000);
		$("#saasappoint-existing-user-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideDown(2000);
	}else if($(this).attr("id") == "saasappoint-guest-user"){
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-existing-user-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideUp(1000);
		$("#saasappoint-guest-user-box").slideDown(2000);
	}else{
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-guest-user-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideUp(1000);
		$("#saasappoint-existing-user-box").slideDown(2000);
	}
});

/** JS to show services according category selection **/
$(document).on("click", ".saasappoint-categories-radio-change", function(){
	$("#saasappoint_refresh_cart").html("<label>No items in cart</label>");
	$("#saasappoint_services_html_content").html("");
	$(".saasappoint_show_hide_services").hide();
	$(".saasappoint_show_hide_addons").hide();
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_services_by_cat_id': 1
		},
		url: ajax_url + "saasappoint_mb_front_ajax.php",
		success: function (res) {
			$("#saasappoint_services_html_content").html(res);
			$(".saasappoint_show_hide_services").show();
			
			/** Auto Trigger Service Check **/
			var single_service_status = generalObj.single_service_status;
			if(single_service_status == "Y"){
				var countservices = 0;
				$('.saasappoint-services-radio-change').each(function(){		
					countservices++;
				});
				
				if(countservices==1){
					$('.saasappoint-services-radio-change').trigger('click');	
					$('.saasappoint_show_hide_services').hide();	
				}
			}
		}
	});
});

/** JS to show addons according services selection **/
$(document).on("click", ".saasappoint-services-radio-change", function(){
	$("#saasappoint_refresh_cart").html("<label>No items in cart</label>");
	$("#saasappoint_multipleqty_addon_html_content").html("");
	$("#saasappoint_singleqty_addon_html_content").html("");
	$(".saasappoint_show_hide_addons").hide();
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_multiple_qty_addons_by_service_id': 1
		},
		url: ajax_url + "saasappoint_mb_front_ajax.php",
		success: function (res) {
			$("#saasappoint_multipleqty_addon_html_content").html(res);
			$(".saasappoint_reset_slot_selection").trigger("click");
			$(".saasappoint_show_hide_addons").show();
		}
	});
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_single_qty_addons_by_service_id': 1
		},
		url: ajax_url + "saasappoint_mb_front_ajax.php",
		success: function (res) {
			$("#saasappoint_singleqty_addon_html_content").html(res);
			$(".saasappoint_show_hide_addons").show();
		}
	});
});

/** JS to get customer detail on change **/
$(document).on("click", "#saasappoint_existing_customer_selection", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	if($.isNumeric(id) && id>0){
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'get_customer_detail': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				var detail = $.parseJSON(res);
				if(detail['status'] == "success"){
					$(".saasappoint_loggedin_name").html(detail['firstname']+" "+detail['lastname']);
					$("#saasappoint_user_customer_id").val(detail['id']);
					$("#saasappoint_user_email").val(detail['email']);
					$("#saasappoint_user_password").val(detail['password']);
					$("#saasappoint_user_firstname").val(detail['firstname']);
					$("#saasappoint_user_lastname").val(detail['lastname']);
					$("#saasappoint_user_zip").val(detail['zip']);
					$("#saasappoint_user_phone").intlTelInput("setNumber", detail['phone']);
					$("#saasappoint_user_address").val(detail['address']);
					$("#saasappoint_user_city").val(detail['city']);
					$("#saasappoint_user_state").val(detail['state']);
					$("#saasappoint_user_country").val(detail['country']);
					
					$("#saasappoint-existing-user-box").hide();
					$(".saasappoint-users-selection-div").hide();
					$(".saasappoint_hide_after_login").hide();
					$(".saasappoint-logout-div").show();
					$("#saasappoint-new-user-box").show();
					
					$(".saasappoint_remove_applied_coupon").trigger("click");
				}else{
					swal("Opps! Your entered email not registered. Please book an appointment as new customer.", "", "error");
				}
			}
		});
	}
});

/** JS to make logout on frontend **/
$(document).on("click", "#saasappoint_change_customer_btn", function(){
	$("#saasappoint_existing_customer_selection").val($("#saasappoint_existing_customer_selection option:first").val());
	$(".saasappoint_loggedin_name").html("");
	$("#saasappoint_user_customer_id").val("");
	$("#saasappoint_user_email").val("");
	$("#saasappoint_user_password").val("");
	$("#saasappoint_user_firstname").val("");
	$("#saasappoint_user_lastname").val("");
	$("#saasappoint_user_zip").val("");
	$("#saasappoint_user_phone").intlTelInput("setNumber", "");
	$("#saasappoint_user_address").val("");
	$("#saasappoint_user_city").val("");
	$("#saasappoint_user_state").val("");
	$("#saasappoint_user_country").val("");
	$("#saasappoint_login_email").val("");
	$("#saasappoint_login_password").val("");
	
	$("#saasappoint-existing-user-box").show();
	$(".saasappoint-users-selection-div").show();
	$(".saasappoint_hide_after_login").show();
	$(".saasappoint-logout-div").hide();
	$("#saasappoint-new-user-box").hide();
});

/** JS to trigger counter on click of multiple qty box **/
$(document).on("click", ".saasappoint_make_multipleqty_addon_selected", function(){
	var id = $(this).data("id");
	if($(".saasappoint-addons-multipleqty-unit-"+id).val()==0){
		$("#saasappoint-addons-multipleqty-plus-js-counter-btn-"+id).trigger("click");
	} else if($(".saasappoint-addons-multipleqty-unit-"+id).val()==1){
		$("#saasappoint-addons-multipleqty-minus-js-counter-btn-"+id).trigger("click");
	} else {
		/** do nothing **/
	}
});

/** validate date **/
function saasappoint_isValidDate(dateString) {
  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  if(!dateString.match(regEx)) return false;  /** Invalid format **/
  var d = new Date(dateString);
  if(Number.isNaN(d.getTime())) return false; /** Invalid date **/
  return d.toISOString().slice(0,10) === dateString;
}

/** JS to book an appointment **/
$(document).on("click", "#saasappoint_book_appointment_btn", function(){
	var ajax_url = generalObj.ajax_url;
	if($(".saasappoint-categories-radio-change:checked").val() === undefined || $(".saasappoint-services-radio-change:checked").val() === undefined){
		swal("Please add item in your cart.", "", "error");
	}else{
		var check_for_addon = 0;
		$(".saasappoint-addons-multipleqty-js-counter-value").each(function(){
			if($(this).val()>0){
				check_for_addon = 1;
			}
		});
		if($(".saasappoint-addons-singleqty-unit-selection:checked").val() !== undefined){
			check_for_addon = 1;
		}
		if(check_for_addon == 0){
			swal("Please add item in your cart.", "", "error");
		}else{
			if($("#saasappoint_fdate").val() == ""){
				swal("Please select appointment slot.", "", "error");
			}else{
				if($("#saasappoint_fstime").val() == ""){
					swal("Please select appointment slot.", "", "error");
				}else if($("#saasappoint_fetime").val() == "" && generalObj.endslot_status == "Y"){
					swal("Please select appointment slot.", "", "error");
				}else{
					var user_selection = $(".saasappoint-user-selection:checked").val();
					if(user_selection == "ec"){
						var customer_selection = $("#saasappoint_existing_customer_selection").val();
						if($.isNumeric(customer_selection) && customer_selection>0){
							if($("#saasappoint_user_detail_form").valid()){
								/** book existing customer appointment **/
								var customer_id = $("#saasappoint_user_customer_id").val();
								var email = $("#saasappoint_user_email").val();
								var password = $("#saasappoint_user_password").val();
								var firstname = $("#saasappoint_user_firstname").val();
								var lastname = $("#saasappoint_user_lastname").val();
								var zip = $("#saasappoint_user_zip").val();
								var phone = $("#saasappoint_user_phone").intlTelInput("getNumber");
								var address = $("#saasappoint_user_address").val();
								var city = $("#saasappoint_user_city").val();
								var state = $("#saasappoint_user_state").val();
								var country = $("#saasappoint_user_country").val();
								var payment_method = "pay-at-venue";
								saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, customer_id);
							}
						}else{
							swal("Please select customer to book an appointment.", "", "error");
						}
					} else if(user_selection == "nc"){
						if($("#saasappoint_user_detail_form").valid()){
							/** book new customer appointment **/
							var customer_id = "";
							var email = $("#saasappoint_user_email").val();
							var password = $("#saasappoint_user_password").val();
							var firstname = $("#saasappoint_user_firstname").val();
							var lastname = $("#saasappoint_user_lastname").val();
							var zip = $("#saasappoint_user_zip").val();
							var phone = $("#saasappoint_user_phone").intlTelInput("getNumber");
							var address = $("#saasappoint_user_address").val();
							var city = $("#saasappoint_user_city").val();
							var state = $("#saasappoint_user_state").val();
							var country = $("#saasappoint_user_country").val();
							var payment_method = "pay-at-venue";
							saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, customer_id);
						}
					} else if(user_selection == "gc"){
						if($("#saasappoint_guestuser_detail_form").valid()){
							/** book guest customer appointment **/
							var customer_id = "";
							var email = $("#saasappoint_guest_email").val();
							var password = "";
							var firstname = $("#saasappoint_guest_firstname").val();
							var lastname = $("#saasappoint_guest_lastname").val();
							var zip = $("#saasappoint_guest_zip").val();
							var phone = $("#saasappoint_guest_phone").intlTelInput("getNumber");
							var address = $("#saasappoint_guest_address").val();
							var city = $("#saasappoint_guest_city").val();
							var state = $("#saasappoint_guest_state").val();
							var country = $("#saasappoint_guest_country").val();
							var payment_method = "pay-at-venue";
							saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, customer_id);
						}
					}
				}
			}
		}
	}
});
function saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, customer_id){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'customer_id': customer_id,
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'payment_method': payment_method,
			'type': user_selection,
			'pay_at_venue_appointment': 1
		},
		url: ajax_url + "saasappoint_mb_front_checkout_ajax.php",
		success: function (res) {
			if(res == "BOOKED"){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Booked!","Appointment booked successfullly.", "success");
				location.reload();
			}
		}
	});
}

/** Get available slots JS **/
$(document).on("click", ".saasappoint_date_selection", function(){
	var selected_date = $(this).data("day");
	if (selected_date.length>0) {
		$(".saasappoint_available_slots_block").html("");
		var ajax_url = generalObj.ajax_url;
		$(".saasappoint_date_selection").removeClass("active_selected_date");
		$(this).addClass("active_selected_date");
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'get_slots': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				$("#saasappoint_time_slots_selection_date").val(selected_date);
				$(".saasappoint-inline-calendar-container-main").slideUp(1000);
				$(".saasappoint_available_slots_block").html(res);
				$(".saasappoint_available_slots_block").slideDown(1000);
			}
		});
	}
});

/** Reset available slots JS **/
$(document).on("click", ".saasappoint_reset_slot_selection", function(){
	var selected_date = $(this).data("day");
	if (selected_date.length>0) {
		$(".saasappoint_reset_slot_selection i").addClass("fa-spin");
		var ajax_url = generalObj.ajax_url;
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'get_slots': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				$(".saasappoint_available_slots_block").show();
				$(".saasappoint_available_slots_block").html(res);
			}
		});
	}
});

/** JS to get end time slots **/
$(document).on("click", ".saasappoint_time_slots_selection", function(){
	var ajax_url = generalObj.ajax_url;
	var check_endslot_status = generalObj.endslot_status;
	var selected_slot = $(this).val();
	var selected_date = $("#saasappoint_time_slots_selection_date").val();
	if(selected_slot != "" && selected_date != "" && check_endslot_status == "Y"){
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_slot': selected_slot,
				'get_endtime_slots': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				$("#saasappoint_time_slots_selection_starttime").val(selected_slot);
				$(".saasappoint_available_slots_block").html(res);
			}
		});
	}else{
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_startslot': selected_slot,
				'add_selected_slot_withendslot': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				$(".saasappoint_selected_slot_detail").html(res);
				$(".saasappoint_selected_slot_detail").show();
				$(".saasappoint_back_to_calendar").trigger("click");
				$("#saasappoint_fdate").val(selected_date);
				$("#saasappoint_fstime").val(selected_slot);
				$("#saasappoint_fetime").val(selected_endslot);
				$("#saasappoint_time_slots_selection_date").val(selected_date);
				$("#saasappoint_time_slots_selection_starttime").val(selected_startslot);
				$("#saasappoint_time_slots_selection_endtime").val(selected_endslot);
				$.ajax({
					type: 'post',
					data: {
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
					success: function (response) {
						$("#saasappoint_refresh_cart").html(response);
					}
				});
			}
		});
	}
});

/** JS to add slots **/
$(document).on("click", ".saasappoint_endtime_slots_selection", function(){
	var ajax_url = generalObj.ajax_url;
	var selected_endslot = $(this).val();
	var selected_startslot = $("#saasappoint_time_slots_selection_starttime").val();
	var selected_date = $("#saasappoint_time_slots_selection_date").val();
	if(selected_endslot != "" && selected_startslot != "" && selected_date != ""){
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_startslot': selected_startslot,
				'selected_endslot': selected_endslot,
				'add_selected_slot': 1
			},
			url: ajax_url + "saasappoint_mb_front_ajax.php",
			success: function (res) {
				$(".saasappoint_selected_slot_detail").html(res);
				$(".saasappoint_selected_slot_detail").show();
				$(".saasappoint_back_to_calendar").trigger("click");
				$("#saasappoint_fdate").val(selected_date);
				$("#saasappoint_fstime").val(selected_startslot);
				$("#saasappoint_fetime").val(selected_endslot);
				$("#saasappoint_time_slots_selection_date").val(selected_date);
				$("#saasappoint_time_slots_selection_starttime").val(selected_startslot);
				$("#saasappoint_time_slots_selection_endtime").val(selected_endslot);
				$.ajax({
					type: 'post',
					data: {
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "saasappoint_mb_front_cart_ajax.php",
					success: function (response) {
						$("#saasappoint_refresh_cart").html(response);
					}
				});
			}
		});
	}
});

/** Get available slots JS **/
$(document).on("click", ".saasappoint_back_to_calendar", function(){
	$(".saasappoint-inline-calendar-container-main").slideDown(1000);
	$(".saasappoint_available_slots_block").slideUp(1000);
});


$(document).on("click", ".saasappoint_cal_prev_month, .saasappoint_cal_next_month", function(){
	var ajax_url = generalObj.ajax_url;
	var selected_month = $(this).data("month");
	$.ajax({
		type: 'post',
		data: {
			'offline': "Y",
			'selected_month': selected_month,
			'get_calendar_on_next_prev': 1
		},
		url: ajax_url + "saasappoint_calendar_ajax.php",
		success: function (res) {
			$(".saasappoint-inline-calendar-container").html(res);
		}
	});
});