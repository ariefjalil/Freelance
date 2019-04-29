/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
$(document).ready(function(){
	var site_url = generalObj.site_url;
	var ajax_url = generalObj.ajax_url;
    $('[data-toggle="tooltip"]').tooltip();
	
	/** Show Location selector Modal **/
	if(generalObj.location_selector == "Y"){
		$("#saasappoint-location-selector-modal").modal("show");
	}
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_user_phone, #saasappoint_guest_phone").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
	
	/** JS to get frequently discount **/
	$.ajax({
		type: 'post',
		data: {
			'get_all_frequently_discount': 1
		},
		url: ajax_url + "saasappoint_front_ajax.php",
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
	
	/** JS to load calendar **/
	$.ajax({
		type: 'post',
		data: {
			'online': "Y",
			'get_calendar_on_load': 1
		},
		url: ajax_url + "saasappoint_calendar_ajax.php",
		success: function (res) {
			$(".saasappoint-inline-calendar-container").html(res);
		}
	});
	
	/** feedbacks list slider JS **/
	var feedback_index = 1;
	$(".saasappoint_list_of_feedbacks:eq(0)").show();
	if($(".saasappoint_list_of_feedbacks").length>1){
		setInterval(function(){ 
			var feedback_i;
			var feedback_x = $(".saasappoint_list_of_feedbacks").length;
			for (feedback_i = 0; feedback_i < feedback_x; feedback_i++) {
				$(".saasappoint_list_of_feedbacks:eq("+(feedback_i)+")").hide();
			}
			feedback_index++;
			if (feedback_index > feedback_x) {
				feedback_index = 1;
			}
			$(".saasappoint_list_of_feedbacks:eq("+(feedback_index-1)+")").fadeIn();
		}, 10000);
	}
	
	/** Validation patterns **/
	$.validator.addMethod("pattern_name", function(value, element) {
		return this.optional(element) || /^[a-zA-Z '.']+$/.test(value);
	}, langObj.please_enter_only_alphabets);
	$.validator.addMethod("pattern_price", function(value, element) {
		return this.optional(element) || /^[0-9]\d*(\.\d{1,2})?$/.test(value);
	}, langObj.please_enter_only_numerics);
	
	$.validator.addMethod("pattern_phone", function(value, element) {
		return this.optional(element) || /\d+(?:[ -]*\d+)*$/.test(value);
	}, langObj.please_enter_valid_phone_number_without_country_code);
	$.validator.addMethod("pattern_zip", function(value, element) {
		return this.optional(element) || /^[a-zA-Z 0-9\-]*$/.test(value);
	}, langObj.please_enter_valid_zip);
	
	/** validate feedback form **/
	$('#saasappoint_feedback_form').validate({
		rules: {
			saasappoint_fb_name:{ required: true },
			saasappoint_fb_email: { required:true, email:true },
			saasappoint_fb_review: { required:true }
		},
		messages: {
			saasappoint_fb_name:{ required: langObj.please_enter_name },
			saasappoint_fb_email: { required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			saasappoint_fb_review: { required: langObj.please_enter_review }
		}
	});
	
	/** validate login form **/
	$('#saasappoint_login_form').validate({
		rules: {
			saasappoint_login_email: { required:true, email:true },
			saasappoint_login_password: { required:true, minlength: 8, maxlength: 20 }
		},
		messages: {
			saasappoint_login_email: { required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			saasappoint_login_password: { required: langObj.please_enter_password, minlength: langObj.please_enter_minimum_8_characters, maxlength: langObj.please_enter_maximum_20_characters },
		}
	});
	
	/** two checkout configuration **/
	var twocheckout_status = generalObj.twocheckout_status;
	if(twocheckout_status == 'Y'){
		$(function(){ TCO.loadPubKey('sandbox'); });
	}
		
	/** Trigger Category On Page Load **/
	var single_category_status = generalObj.single_category_status;
	if(single_category_status == "Y"){
		var countcats = 0;
		$('.saasappoint-categories-radio-change').each(function(){		
			countcats++;
		});
		if(countcats==1){
			$('.saasappoint-categories-radio-change').trigger('click');	
			$('.saasappoint-company-services-blocks').hide();		
		}
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
			saasappoint_user_email:{ required: langObj.please_enter_email, email: langObj.please_enter_valid_email, remote: langObj.email_already_exist },
			saasappoint_user_password: { required: langObj.please_enter_password, minlength: langObj.please_enter_minimum_8_characters, maxlength: langObj.please_enter_maximum_20_characters },
			saasappoint_user_firstname:{ required: langObj.please_enter_first_name, maxlength: langObj.please_enter_maximum_50_characters },
			saasappoint_user_lastname: { required: langObj.please_enter_last_name, maxlength: langObj.please_enter_maximum_50_characters },
			saasappoint_user_phone: { required: langObj.please_enter_phone_number, minlength: langObj.please_enter_minimum_10_digits, maxlength: langObj.please_enter_maximum_15_digits },
			saasappoint_user_address: { required: langObj.please_enter_address },
			saasappoint_user_city: { required: langObj.please_enter_city },
			saasappoint_user_state: { required: langObj.please_enter_state },
			saasappoint_user_zip: { required: langObj.please_enter_state, minlength: langObj.please_enter_minimum_5_characters, maxlength: langObj.please_enter_maximum_10_characters },
			saasappoint_user_country: { required: langObj.please_enter_country }
		}
	});
	
	/** validate guest user detail form **/
	$("#saasappoint_guestuser_detail_form").validate({
		rules: {
			saasappoint_guest_email:{ required: true, email:true },
			saasappoint_guest_firstname:{ required: true, maxlength: 50 },
			saasappoint_guest_lastname: { required:true, maxlength: 50 },
			saasappoint_guest_phone: { required:true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_guest_address: { required:true },
			saasappoint_guest_city: { required:true },
			saasappoint_guest_state: { required:true },
			saasappoint_guest_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_guest_country: { required:true }
		},
		messages: {
			saasappoint_guest_email:{ required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			saasappoint_guest_firstname:{ required: langObj.please_enter_first_name, maxlength: langObj.please_enter_maximum_50_characters },
			saasappoint_guest_lastname: { required: langObj.please_enter_last_name, maxlength: langObj.please_enter_maximum_50_characters },
			saasappoint_guest_phone: { required: langObj.please_enter_phone_number, minlength: langObj.please_enter_minimum_10_digits, maxlength: langObj.please_enter_maximum_15_digits },
			saasappoint_guest_address: { required: langObj.please_enter_address },
			saasappoint_guest_city: { required: langObj.please_enter_city },
			saasappoint_guest_state: { required: langObj.please_enter_state },
			saasappoint_guest_zip: { required: langObj.please_enter_state, minlength: langObj.please_enter_minimum_5_characters, maxlength: langObj.please_enter_maximum_10_characters },
			saasappoint_guest_country: { required: langObj.please_enter_country }
		}
	});
	
	/** validate forget password form **/
	$('#saasappoint_forgot_password_form').validate({
        rules: {
            saasappoint_forgot_password_email: {required: true, email: true}
        },
        messages: {
            saasappoint_forgot_password_email: { required: langObj.please_enter_email_address, email: langObj.please_enter_valid_email_address }
        }
    });
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
					$('#saasappoint-forgot-password-success').html(langObj.reset_password_link_sent_successfully_at_your_registered_email_address);
					$('#saasappoint-forgot-password-success').show();
				}else if(res.trim() == "tryagain"){
					$('#saasappoint-forgot-password-success').hide();
					$('#saasappoint-forgot-password-error').html(langObj.oops_error_occurred_please_try_again);
					$('#saasappoint-forgot-password-error').show();
				}else{
					$('#saasappoint-forgot-password-success').hide();
					$('#saasappoint-forgot-password-error').html(langObj.invalid_email_address);
					$('#saasappoint-forgot-password-error').show();
				}
			}
		});
	}
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
		url: ajax_url + "saasappoint_front_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					if($(".saasappoint_cart_items_list li").length>0){
						$(".saasappoint-frequently-discount-change").prop('checked', false);
					}
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
		url: ajax_url + "saasappoint_front_cart_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_front_cart_ajax.php",
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
					if($(".saasappoint_cart_items_list li").length>0){
						$(".saasappoint-frequently-discount-change").prop('checked', false);
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
		url: ajax_url + "saasappoint_front_cart_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					if(qty==0){
						$('#saasappoint-addons-singleqty-unit-'+id).prop("checked", false);
					}
					if($(".saasappoint_cart_items_list li").length>0){
						$(".saasappoint-frequently-discount-change").prop('checked', false);
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
		url: ajax_url + "saasappoint_front_cart_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "saasappoint_front_cart_ajax.php",
				success: function (response) {
					$("#saasappoint_refresh_cart").html(response);
					$('#saasappoint-addons-singleqty-unit-'+id).prop("checked", false);
					$('.saasappoint-addons-multipleqty-unit-'+id).val(qty);
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-box-icon').removeClass("saasappoint-selected-addon");
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter').removeClass("saasappoint-selected-addon");
					$('.saasappoint-addons-multipleqty-box-'+id+' .saasappoint-addons-multipleqty-counter-item-center').removeClass("saasappoint-selected-addon");
					if($(".saasappoint_cart_items_list li").length>0){
						$(".saasappoint-frequently-discount-change").prop('checked', false);
					}
				}
			});
		}
	});
});

/** Show hide card payemnt box JS **/
$(document).on("change", ".saasappoint-payment-method-check", function(){
	if($(this).val() == "stripe" || $(this).val() == "2checkout" || $(this).val() == "authorize.net"){
		$(".saasappoint-card-detail-box").slideDown(2000);
	}else{
		$(".saasappoint-card-detail-box").slideUp(1000);
	}
});

/** Show hide customer detail box according selection JS **/
$(document).on("change", ".saasappoint-user-selection", function(){
	if($(this).attr("id") == "saasappoint-new-user"){
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-guest-user-box").slideUp(1000);
		$("#saasappoint-existing-user-box").slideUp(1000);
		$("#saasappoint-user-forget-password-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideDown(2000);
	}else if($(this).attr("id") == "saasappoint-guest-user"){
		$("#saasappoint_apply_referral_code_btn").trigger("click");
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-existing-user-box").slideUp(1000);
		$("#saasappoint-user-forget-password-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideUp(1000);
		$("#saasappoint-guest-user-box").slideDown(2000);
	}else if($(this).attr("id") == "saasappoint-user-forget-password"){
		$("#saasappoint_apply_referral_code_btn").trigger("click");
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-guest-user-box").slideUp(1000);
		$("#saasappoint-existing-user-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideUp(2000);
		$("#saasappoint-user-forget-password-box").slideDown(1000);
	}else{
		$("#saasappoint-guest-user-box").removeClass("saasappoint_show");
		$("#saasappoint-guest-user-box").slideUp(1000);
		$("#saasappoint-new-user-box").slideUp(1000);
		$("#saasappoint-user-forget-password-box").slideUp(1000);
		$("#saasappoint-existing-user-box").slideDown(2000);
	}
});

/** JS to mark rating stars **/
function saasappoint_add_star_rating(ths,sno){
	for (var i=1;i<=5;i++){
		var cur=document.getElementById("saasappoint-sidebar-feedback-star"+i)
		cur.className="fa fa-star-o saasappoint-sidebar-feedback-star"
	}

	for (var i=1;i<=sno;i++){
		var cur=document.getElementById("saasappoint-sidebar-feedback-star"+i)
		if(cur.className=="fa fa-star-o saasappoint-sidebar-feedback-star")
		{
			cur.className="fa fa-star saasappoint-sidebar-feedback-star saasappoint-sidebar-feedback-star-checked"
		}
	}
	$("#saasappoint_fb_rating").val(sno);
}

/** JS to show services according category selection **/
$(document).on("click", ".saasappoint-categories-radio-change", function(){
	$("#saasappoint_refresh_cart").html("<label>"+langObj.no_items_in_cart+"</label>");
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
		url: ajax_url + "saasappoint_front_ajax.php",
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
	$("#saasappoint_refresh_cart").html("<label>"+langObj.no_items_in_cart+"</label>");
	$("#saasappoint_multipleqty_addon_html_content").html("");
	$("#saasappoint_singleqty_addon_html_content").html("");
	$(".saasappoint_show_hide_addons").hide();
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_multi_and_single_qty_addons_content': 1
		},
		url: ajax_url + "saasappoint_front_ajax.php",
		success: function (res) {
			$("#saasappoint_multi_and_single_qty_addons_content").html(res);
			$(".saasappoint_reset_slot_selection").trigger("click");
			$(".saasappoint_show_hide_addons").show();
		}
	});
});

/** JS to show available coupons **/
$(document).on("click", ".saasappoint-coupon-radio", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	var coupon_code = $(this).data("promo");
	$(".saasappoint-available-coupons-list").removeClass("saasappoint-coupon-radio-checked");
	$("#saasappoint-coupon-radio-"+id).parent().addClass("saasappoint-coupon-radio-checked");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'apply_coupon': 1
		},
		url: ajax_url + "saasappoint_front_ajax.php",
		success: function (res) {
			if(res=="available"){
				$("#saasappoint-available-coupons-modal").modal("hide");
				$(".saasappoint_remove_applied_coupon").attr('data-id', id);
				$(".saasappoint_applied_coupon_badge").html('<i class="fa fa-ticket"></i> '+coupon_code);
				$(".saasappoint_remove_applied_coupon").show();
				$(".saasappoint_applied_coupon_div").show();
				swal(langObj.applied_promo_applied_successfully, "", "success");
				$.ajax({
					type: 'post',
					data: {
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "saasappoint_front_cart_ajax.php",
					success: function (response) {
						$("#saasappoint_refresh_cart").html(response);
						if($(".saasappoint_cart_items_list li").length>0){
							$(".saasappoint-frequently-discount-change").prop('checked', false);
						}
					}
				});
			}else{
				swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
			}
		}
	});
});

/** JS to revert coupon **/
$(document).on("click", ".saasappoint_remove_applied_coupon", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	if(id!=""){
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'remove_applied_coupon': 1
			},
			url: ajax_url + "saasappoint_front_ajax.php",
			success: function (res) {
				$(".saasappoint_remove_applied_coupon").attr('data-id', "");
				$(".saasappoint_applied_coupon_badge").html('');
				$(".saasappoint-available-coupons-list").removeClass("saasappoint-coupon-radio-checked");
				$(".saasappoint-coupon-radio").prop("checked", false);
				$(".saasappoint_remove_applied_coupon").hide();
				$(".saasappoint_applied_coupon_div").hide();

				$.ajax({
					type: 'post',
					data: {
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "saasappoint_front_cart_ajax.php",
					success: function (response) {
						$("#saasappoint_refresh_cart").html(response);
						if($(".saasappoint_cart_items_list li").length>0){
							$(".saasappoint-frequently-discount-change").prop('checked', false);
						}
					}
				});
			}
		});
	}
});

/** JS to show available coupons in modal **/
$(document).on("click", "#saasappoint-available-coupons-open-modal", function(){
	var ajax_url = generalObj.ajax_url;
	if($("#saasappoint-guest-user").prop("checked") || $("#saasappoint-user-forget-password").prop("checked")){
		swal(langObj.opps_please_book_your_appointment_as_new_or_existing_customer, "", "error");
	}else{
		$.ajax({
			type: 'post',
			data: {
				'get_available_coupons': 1
			},
			url: ajax_url + "saasappoint_front_ajax.php",
			success: function (res) {
				$(".saasappoint_avail_promo_modal_body").html(res);
				$("#saasappoint-available-coupons-modal").modal("show");
			}
		});
	}
});

/** JS to submit feedback **/
$(document).on("click", "#saasappoint_submit_feedback_btn", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	if($('#saasappoint_feedback_form').valid()){
		var name = $("#saasappoint_fb_name").val();
		var email = $("#saasappoint_fb_email").val();
		var review = $("#saasappoint_fb_review").val();
		var rating = $("#saasappoint_fb_rating").val();
		
		$.ajax({
			type: 'post',
			data: {
				'name': name,
				'email': email,
				'review': review,
				'rating': rating,
				'add_feedback': 1
			},
			url: ajax_url + "saasappoint_front_ajax.php",
			success: function (res) {
				if(res=="added"){
					swal(langObj.submitted_your_review_submitted_successfully, "", "success");
				}else{
					swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
				}
				
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
			url: ajax_url + "saasappoint_front_ajax.php",
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
			url: ajax_url + "saasappoint_front_ajax.php",
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
					url: ajax_url + "saasappoint_front_cart_ajax.php",
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
			url: ajax_url + "saasappoint_front_ajax.php",
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
					url: ajax_url + "saasappoint_front_cart_ajax.php",
					success: function (response) {
						$("#saasappoint_refresh_cart").html(response);
					}
				});
			}
		});
	}
});

/** JS to make login on frontend **/
$(document).on("click", "#saasappoint_login_btn", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	var email = $("#saasappoint_login_email").val();
	var password = $("#saasappoint_login_password").val();
	if($("#saasappoint_login_form").valid()){
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'password': password,
				'front_login': 1
			},
			url: ajax_url + "saasappoint_front_ajax.php",
			success: function (res) {
				var detail = $.parseJSON(res);
				if(detail['status'] == "success"){
					$(".saasappoint_loggedin_name").html(detail['firstname']+" "+detail['lastname']);
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
					$("#saasappoint_apply_referral_code_btn").trigger("click");
				}else{
					swal(langObj.opps_your_entered_email_not_registered_please_book_an_appointment_as_new_customer, "", "error");
				}
			}
		});
	}
});

/** JS to make logout on frontend **/
$(document).on("click", "#saasappoint_logout_btn", function(){
	var ajax_url = generalObj.ajax_url;	
	$.ajax({
		type: 'post',
		data: {
			'front_logout': 1
		},
		url: ajax_url + "saasappoint_front_ajax.php",
		success: function (res) {
			
			$(".saasappoint_loggedin_name").html("");
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
			
			$(".saasappoint_remove_applied_coupon").trigger("click");
			$("#saasappoint_apply_referral_code_btn").trigger("click");
		}
	});
});

/** JS to apply referral code on frontend **/
$(document).on("click", "#saasappoint_apply_referral_code_btn", function(){
	var ajax_url = generalObj.ajax_url;
	var referral_code = $("#saasappoint_referral_code").val().toUpperCase();
	var email = $("#saasappoint_user_email").val();
	
	if(referral_code.length==15){
		if(email != "" && ($(".saasappoint-user-selection:checked").val() == "ec" || $(".saasappoint-user-selection:checked").val() == "nc")){
			$.ajax({
				type: 'post',
				data: {
					'email': email,
					'referral_code': referral_code,
					'apply_referral_code': 1
				},
				url: ajax_url + "saasappoint_front_ajax.php",
				success: function (res) {
					if(res == "applied"){
						$(".saasappoint_referral_code_div").hide();
						$(".saasappoint_referral_code_applied_div").show();
						$(".saasappoint_referral_code_applied_text").html(referral_code);
						swal(langObj.referral_code_applied_successfully, "", "success");
					}else if(res == "owncode"){
						$(".saasappoint_referral_code_div").show();
						$(".saasappoint_referral_code_applied_div").hide();
						$(".saasappoint_referral_code_applied_text").html("");
						swal(langObj.you_cannot_use_your_own_referral_code, "", "error");
					}else if(res == "onfirstbookingonly"){
						$(".saasappoint_referral_code_div").show();
						$(".saasappoint_referral_code_applied_div").hide();
						$(".saasappoint_referral_code_applied_text").html("");
						swal(langObj.you_can_apply_referral_code_only_on_first_booking, "", "error");
					}else if(res == "notexist"){
						$(".saasappoint_referral_code_div").show();
						$(".saasappoint_referral_code_applied_div").hide();
						$(".saasappoint_referral_code_applied_text").html("");
						swal(langObj.opps_youve_entered_incorrect_referral_code, "", "error");
					}else{
						$(".saasappoint_referral_code_div").show();
						$(".saasappoint_referral_code_applied_div").hide();
						$(".saasappoint_referral_code_applied_text").html("");
						swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
					}
				}
			});
		}else{
			$.ajax({
				type: 'post',
				data: {
					'remove_referral_code': 1
				},
				url: ajax_url + "saasappoint_front_ajax.php",
				success: function (res) {
					$(".saasappoint_referral_code_div").show();
					$(".saasappoint_referral_code_applied_div").hide();
					$(".saasappoint_referral_code_applied_text").html("");
					swal(langObj.please_register_or_login_to_use_referral_code_feature, "", "error");
				}
			});
		}
	}else if(referral_code.length>1){
		swal(langObj.please_enter_15_characters_long_referral_code, "", "error");
	}
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
	var ty_page = generalObj.ty_link;
	
	/** Check for location **/
	var location_zipcode = "";
	if($(".saasappoint-user-selection:checked").val() == "ec" || $(".saasappoint-user-selection:checked").val() == "nc"){
		location_zipcode = $("#saasappoint_user_zip").val();
	}else if($(".saasappoint-user-selection:checked").val() == "gc"){
		location_zipcode = $("#saasappoint_guest_zip").val();
	}
	if(location_zipcode != ""){
		$.ajax({
			type: 'post',
			data: {
				'zipcode': location_zipcode,
				'check_zip_location': 1
			},
			url: ajax_url + "saasappoint_location_selector_ajax.php",
			success: function (res) {
				if(res!="available"){
					swal(langObj.opps_we_are_not_available_at_your_location, "", "error");
					$("#saasappoint-location-selector-modal").modal("show");
				}else{
					/*** Booking code START ***/
					if($(".saasappoint-categories-radio-change:checked").val() === undefined || $(".saasappoint-services-radio-change:checked").val() === undefined){
						swal(langObj.please_add_item_in_your_cart, "", "error");
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
							swal(langObj.please_add_item_in_your_cart, "", "error");
						}else{
							if($("#saasappoint_fdate").val() == ""){
								swal(langObj.please_select_appointment_slot, "", "error");
							}else{
								if($("#saasappoint_fstime").val() == ""){
									swal(langObj.please_select_appointment_slot, "", "error");
								}else if($("#saasappoint_fetime").val() == "" && generalObj.endslot_status == "Y"){
									swal(langObj.please_select_appointment_slot, "", "error");
								}else{
									var user_selection = $(".saasappoint-user-selection:checked").val();
									if(user_selection == "ec"){
										if($("#saasappoint_login_btn").is(":visible")){
											$("#saasappoint_login_btn").trigger("click");
											swal(langObj.please_login_to_book_an_appointment, "", "error");
										}else{
											if($("#saasappoint_user_detail_form").valid()){
												if($(".saasappoint-tc-control-input").prop("checked")){
													
													/** book existing customer appointment **/
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
													var payment_method = $(".saasappoint-payment-method-check:checked").val();
						
													if(payment_method == "paypal"){
														saasappoint_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
													}else if(payment_method == "stripe"){
														var stripe_pkey = generalObj.stripe_pkey;
														if(stripe_pkey != ""){
															saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
																if (result.error) {
																	/* Inform the user if there was an error. */
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	$("#saasappoint_stripe_plan_card_errors").html(result.error.message);
																} else {
																	/* Send the token via ajax */
																	var token = result.token.id;
																	saasappoint_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
																}
															});
														}else{
															swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
														}
													}else if(payment_method == "authorize.net"){
														var cardnumber = $("#saasappoint-cardnumber").val();
														var cardcvv = $("#saasappoint-cardcvv").val();
														var cardexmonth = $("#saasappoint-cardexmonth").val();
														var cardexyear = $("#saasappoint-cardexyear").val();
														var cardholdername = $("#saasappoint-cardholdername").val();
														
														var cdetail_valid = $.payment.validateCardNumber(cardnumber);
														if (!cdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_number_is_not_valid, "", "error");
															return false;
														}else{
															var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
															if (!ymdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
																return false;
															}else{
																var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
																if (!cvvdetail_valid) {
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																	return false;
																}else{
																	if(cardholdername == ""){
																		$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																		swal(langObj.please_enter_card_holder_name, "", "error");
																		return false;
																	}
																}
															}
														}
														cardnumber = cardnumber.replace(/\s+/g, '');
														
														saasappoint_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);
													
													}else if(payment_method == "2checkout"){
														
														var cardnumber = $("#saasappoint-cardnumber").val();
														var cardcvv = $("#saasappoint-cardcvv").val();
														var cardexmonth = $("#saasappoint-cardexmonth").val();
														var cardexyear = $("#saasappoint-cardexyear").val();
														var cardholdername = $("#saasappoint-cardholdername").val();
														
														var cdetail_valid = $.payment.validateCardNumber(cardnumber);
														if (!cdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_number_is_not_valid, "", "error");
															return false;
														}else{
															var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
															if (!ymdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
																return false;
															}else{
																var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
																if (!cvvdetail_valid) {
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																	return false;
																}else{
																	if(cardholdername == ""){
																		$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																		swal(langObj.please_enter_card_holder_name, "", "error");
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
															saasappoint_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
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
														payment_method = "pay-at-venue";
														saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
													}
												}else{
													swal(langObj.please_accept_our_terms_conditions, "", "error");
												}
											}
										}
									} else if(user_selection == "nc"){
										if($("#saasappoint_user_detail_form").valid()){
											if($(".saasappoint-tc-control-input").prop("checked")){
												/** book new customer appointment **/
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
												var payment_method = $(".saasappoint-payment-method-check:checked").val();
												
												if(payment_method == "paypal"){
													saasappoint_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
												}else if(payment_method == "stripe"){
													var stripe_pkey = generalObj.stripe_pkey;
													if(stripe_pkey != ""){
														saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
															if (result.error) {
																/* Inform the user if there was an error. */
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																$("#saasappoint_stripe_plan_card_errors").html(result.error.message);
															} else {
																/* Send the token via ajax */
																var token = result.token.id;
																saasappoint_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
															}
														});
													}else{
														swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
													}
												}else if(payment_method == "authorize.net"){
													var cardnumber = $("#saasappoint-cardnumber").val();
													var cardcvv = $("#saasappoint-cardcvv").val();
													var cardexmonth = $("#saasappoint-cardexmonth").val();
													var cardexyear = $("#saasappoint-cardexyear").val();
													var cardholdername = $("#saasappoint-cardholdername").val();
													
													var cdetail_valid = $.payment.validateCardNumber(cardnumber);
													if (!cdetail_valid) {
														$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
														swal(langObj.opps_your_card_number_is_not_valid, "", "error");
														return false;
													}else{
														var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
														if (!ymdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
															return false;
														}else{
															var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
															if (!cvvdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																return false;
															}else{
																if(cardholdername == ""){
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.please_enter_card_holder_name, "", "error");
																	return false;
																}
															}
														}
													}
													cardnumber = cardnumber.replace(/\s+/g, '');
													
													saasappoint_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);
												}else if(payment_method == "2checkout"){
													
													var cardnumber = $("#saasappoint-cardnumber").val();
													var cardcvv = $("#saasappoint-cardcvv").val();
													var cardexmonth = $("#saasappoint-cardexmonth").val();
													var cardexyear = $("#saasappoint-cardexyear").val();
													var cardholdername = $("#saasappoint-cardholdername").val();
													
													var cdetail_valid = $.payment.validateCardNumber(cardnumber);
													if (!cdetail_valid) {
														$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
														swal(langObj.opps_your_card_number_is_not_valid, "", "error");
														return false;
													}else{
														var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
														if (!ymdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
															return false;
														}else{
															var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
															if (!cvvdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																return false;
															}else{
																if(cardholdername == ""){
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.please_enter_card_holder_name, "", "error");
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
														saasappoint_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
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
													payment_method = "pay-at-venue";
													saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
												}
											}else{
												swal(langObj.please_accept_our_terms_conditions, "", "error");
											}
										}
									} else if(user_selection == "gc"){
										if($("#saasappoint_guestuser_detail_form").valid()){
											if($(".saasappoint-tc-control-input").prop("checked")){
												/** book guest customer appointment **/
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
												var payment_method = $(".saasappoint-payment-method-check:checked").val();

												if(payment_method == "paypal"){
													saasappoint_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
												}else if(payment_method == "stripe"){
													var stripe_pkey = generalObj.stripe_pkey;
													if(stripe_pkey != ""){
														saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
															if (result.error) {
																/* Inform the user if there was an error. */
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																$("#saasappoint_stripe_plan_card_errors").html(result.error.message);
															} else {
																/* Send the token via ajax */
																var token = result.token.id;
																saasappoint_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
															}
														});
													}else{
														swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
													}
												}else if(payment_method == "authorize.net"){
													var cardnumber = $("#saasappoint-cardnumber").val();
													var cardcvv = $("#saasappoint-cardcvv").val();
													var cardexmonth = $("#saasappoint-cardexmonth").val();
													var cardexyear = $("#saasappoint-cardexyear").val();
													var cardholdername = $("#saasappoint-cardholdername").val();
													
													var cdetail_valid = $.payment.validateCardNumber(cardnumber);
													if (!cdetail_valid) {
														$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
														swal(langObj.opps_your_card_number_is_not_valid, "", "error");
														return false;
													}else{
														var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
														if (!ymdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
															return false;
														}else{
															var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
															if (!cvvdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																return false;
															}else{
																if(cardholdername == ""){
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.please_enter_card_holder_name, "", "error");
																	return false;
																}
															}
														}
													}
													cardnumber = cardnumber.replace(/\s+/g, '');
													
													saasappoint_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);

												}else if(payment_method == "2checkout"){
													
													var cardnumber = $("#saasappoint-cardnumber").val();
													var cardcvv = $("#saasappoint-cardcvv").val();
													var cardexmonth = $("#saasappoint-cardexmonth").val();
													var cardexyear = $("#saasappoint-cardexyear").val();
													var cardholdername = $("#saasappoint-cardholdername").val();
													
													var cdetail_valid = $.payment.validateCardNumber(cardnumber);
													if (!cdetail_valid) {
														$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
														swal(langObj.opps_your_card_number_is_not_valid, "", "error");
														return false;
													}else{
														var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
														if (!ymdetail_valid) {
															$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
															swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
															return false;
														}else{
															var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
															if (!cvvdetail_valid) {
																$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																swal(langObj.opps_your_cvv_is_not_valid, "", "error");
																return false;
															}else{
																if(cardholdername == ""){
																	$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
																	swal(langObj.please_enter_card_holder_name, "", "error");
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
														saasappoint_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token);
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
													payment_method = "pay-at-venue";
													saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page);
												}
											}else{
												swal(langObj.please_accept_our_terms_conditions, "", "error");
											}
										}
									}
								}
							}
						}
					}
					/*** Booking code END ***/
				}
			}
		});
	}else{
		swal(langObj.opps_please_check_for_services_available_at_your_location_or_not, "", "error");
		$("#saasappoint-location-selector-modal").modal("show");
		return false;
	}
});
function saasappoint_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
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
		url: ajax_url + "saasappoint_front_checkout_ajax.php",
		success: function (res) {
			if(res == "BOOKED"){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				window.location.href = ty_page;
			}
		}
	});
}
function saasappoint_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
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
			'paypal_appointment': 1
		},
		url: ajax_url + "saasappoint_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
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
function saasappoint_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
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
			'cardnumber': cardnumber,
			'cardcvv': cardcvv,
			'cardexmonth': cardexmonth,
			'cardexyear': cardexyear,
			'cardholdername': cardholdername,
			'authorizenet_appointment': 1
		},
		url: ajax_url + "saasappoint_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "saasappoint_front_appt_process_ajax.php",
					success:function(response){
						if(response == 'BOOKED'){
							window.location=ty_page; 
						}
					}
				});
			}
		}
	});
}
function saasappoint_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
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
			'token': token,
			'2checkout_appointment': 1
		},
		url: ajax_url + "saasappoint_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "saasappoint_front_appt_process_ajax.php",
					success:function(response){
						if(response == 'BOOKED'){
							window.location=ty_page; 
						}
					}
				});
			}
		}
	});
}
function saasappoint_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
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
			'token': token,
			'stripe_appointment': 1
		},
		url: ajax_url + "saasappoint_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "saasappoint_front_appt_process_ajax.php",
					success:function(response){
						if(response == 'BOOKED'){
							window.location=ty_page; 
						}
					}
				});
			}
		}
	});
}

/** swal to apply referral discount coupon **/
$(document).on("click", "#saasappoint_apply_referral_coupon", function(){
	var ajax_url = generalObj.ajax_url;
	if($(".saasappoint-user-selection:checked").val() == "ec"){
		if($("#saasappoint_login_btn").is(":visible")){
			$("#saasappoint_login_btn").trigger("click");
			swal(langObj.please_login_to_apply_referral_discount_coupon, "", "error");
		}else{
			swal({
				title: langObj.enter_your_referral_discount_coupon_code,
				text: "",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: false,
				animation: "slide-from-bottom",
				confirmButtonText: langObj.apply,
				cancelButtonText: langObj.cancel,
				inputPlaceholder: langObj.enter_discount_coupon
			}, function (ref_discount_coupon) {
				if(ref_discount_coupon){
					if(ref_discount_coupon != ""){
						ref_discount_coupon = ref_discount_coupon.toUpperCase();
						$.ajax({
							type: 'post',
							data: {
								'ref_discount_coupon': ref_discount_coupon,
								'apply_referral_discount': 1
							},
							url: ajax_url + "saasappoint_front_ajax.php",
							success: function (res) {
								if(res == "notexist"){
									$(".saasappoint_applied_referral_coupon_code").html("");
									$(".saasappoint_applied_referral_coupon_div_text").hide();
									$(".saasappoint_apply_referral_coupon_div").show();
									swal(langObj.please_enter_valid_referral_discount_coupon, "", "error");
								}else if(res == "used"){
									$(".saasappoint_applied_referral_coupon_code").html("");
									$(".saasappoint_applied_referral_coupon_div_text").hide();
									$(".saasappoint_apply_referral_coupon_div").show();
									swal(langObj.referral_discount_coupon_already_used, "", "error");
								}else if(res == "applied"){
									$(".saasappoint_applied_referral_coupon_code").html(ref_discount_coupon);
									$(".saasappoint_applied_referral_coupon_div_text").show();
									$(".saasappoint_apply_referral_coupon_div").hide();
									swal(langObj.applied_referral_discount_coupon_applied_successfully, "", "success");
									$.ajax({
										type: 'post',
										data: {
											'refresh_cart_sidebar': 1
										},
										url: ajax_url + "saasappoint_front_cart_ajax.php",
										success: function (response) {
											$("#saasappoint_refresh_cart").html(response);
											if($(".saasappoint_cart_items_list li").length>0){
												$(".saasappoint-frequently-discount-change").prop('checked', false);
											}
										}
									});
								}else {
									$(".saasappoint_applied_referral_coupon_code").html("");
									$(".saasappoint_applied_referral_coupon_div_text").hide();
									$(".saasappoint_apply_referral_coupon_div").show();
									swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
								}
							}
						});
					}else{
						swal(langObj.please_enter_referral_discount_coupon_code, "", "error");
					}
				}
			});
		}
	}else{
		swal(langObj.please_login_to_apply_referral_discount_coupon, "", "error");
	}
});

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
			url: ajax_url + "saasappoint_front_ajax.php",
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
			url: ajax_url + "saasappoint_front_ajax.php",
			success: function (res) {
				$(".saasappoint_available_slots_block").show();
				$(".saasappoint_available_slots_block").html(res);
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
			'online': "Y",
			'selected_month': selected_month,
			'get_calendar_on_next_prev': 1
		},
		url: ajax_url + "saasappoint_calendar_ajax.php",
		success: function (res) {
			$(".saasappoint-inline-calendar-container").html(res);
		}
	});
});

/** Check location JS **/
$(document).on('click', '#saasappoint_location_check_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var siteurl = generalObj.site_url;
	var zipcode = $("#saasappoint_ls_input_keyword").val();
	var zip_pattern = /^[a-zA-Z 0-9\-]*$/;
	
	if(zipcode.match(zip_pattern) && zipcode != ""){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'zipcode': zipcode,
				'check_zip_location': 1
			},
			url: ajaxurl + "saasappoint_location_selector_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="available"){
					$("#saasappoint_user_zip").val(zipcode);
					$("#saasappoint_guest_zip").val(zipcode);
					swal(langObj.available_our_service_available_at_your_location, "", "success");
					$("#saasappoint-location-selector-modal").modal("hide");
				}else{
					swal(langObj.opps_we_are_not_available_at_your_location, "", "error");
				}
			}
		});
	}else{
		swal(langObj.please_enter_valid_zipcode, "", "error");
	}
});

/** Set selected language JS **/
$(document).on('change', '#saasappoint_langauges', function(){
	var ajaxurl = generalObj.ajax_url;
	var lang = $(this).val();
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'lang': lang,
			'set_selected_language': 1
		},
		url: ajaxurl + "saasappoint_front_ajax.php",
		success: function (res) {
			location.reload();
		}
	});
});