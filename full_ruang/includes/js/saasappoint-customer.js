/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
/** Initialization on ready state JS **/
$(document).ready(function () {
	var ajaxurl = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_profile_phone").intlTelInput({
      separateDialCode: true,
      utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js",
    });
	/** Calendar JS **/
    var curdate = generalObj.current_date;
	$('#saasappoint-appointments-calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,listMonth'
		},
		defaultDate: curdate,
		editable: false,
		refetch: false,
		firstDay: 1,
		eventLimit: 6,
		eventTextColor: "#FFF",
		events: ajaxurl + 'saasappoint_my_appointments_ajax.php',
		eventRender: function (event, element) {
			element.attr('href', 'javascript:void(0);');
			element.find('.fc-title').hide();
			element.find('.fc-time').hide();
			element.find('.fc-title').before(
				$("<div class='saasappoint-fc-title'>"+event.event_icon+" <span>"+event.event_status+"</span></div><hr class='saasappoint-hr' />")
			);
			element.find('.fc-title').after(
				$("<div class='saasappoint-fc-title'>" + event.cat_name + "</div><div class='saasappoint-fc-title'>" + event.title + "</div><div class='saasappoint-fc-title'>" + event.customer_email + "</div><div class='saasappoint-fc-title'>" + event.customer_phone + "</div><hr class='saasappoint-hr' /><div class='saasappoint-fc-title'>" + event.rating + "</div>")
			);
            element.css('padding', "5px");
			element.click(function () {
				$.ajax({
					type: 'post',
					data: {
						'order_id': event.id,
						'get_appointment_detail': 1
					},
					url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
					success: function (res) {
						$('.saasappoint_appointment_detail_modal_body').html(res);
						$('#saasappoint_appointment_detail_modal').modal('show');
						$('.saasappoint_appointment_detail_link').trigger('click');
					}
				});
			});
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
	
	/** DataTable JS **/
	$("#saasappoint_support_ticket_list_table").DataTable({
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
	$('#saasappoint_refund_request_list_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true }
		]
    } ); 
	$('#saasappoint_customer_referrals_list_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true }
		]
    } ); 
});

/** image upload js */
function saasappoint_read_uploaded_file_url(input) {
    if (input.files && input.files[0]) {
		if((input.files[0].size/1000) > 1000){
			swal("Opps!", "Maximum file upload size 1 MB.", "error");
		}else if(input.files[0].type =="image/jpeg" || input.files[0].type =="image/jpg" || input.files[0].type =="image/png"){
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#saasappoint-image-upload-file-hidden').val(e.target.result);
				$('#saasappoint-image-upload-file-preview').css('background-image', 'url('+e.target.result +')');
				$('#saasappoint-image-upload-file-preview').hide();
				$('#saasappoint-image-upload-file-preview').fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}else{
			swal("Opps!", "Please select a valid image file (jpeg, jpg and png are allowed).", "error");
		}
    }
}
$(document).on('change', "#saasappoint-image-upload-file", function() {
    saasappoint_read_uploaded_file_url(this);
});

/** Tab view js */
$(document).on('click', '.saasappoint_tab_view_nav_link', function(){
	var tabNo = $(this).data('tabno');
	$('.custom-nav-item').removeClass('active');
	$(".custom-nav-item:eq("+tabNo+")").addClass("active");
});

/** Appointment detail tab content **/
$(document).on('click', '.saasappoint_appointment_detail_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'appointment_detail_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_appointment_detail').html(res);
			$('#saasappoint_appointment_detail').show();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_customer_detail').hide();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').hide();
			$('#saasappoint_feedback_appointment').hide();
		}
	});
});

/** Payment detail tab content **/
$(document).on('click', '.saasappoint_payment_detail_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'payment_detail_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_payment_detail').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').show();
			$('#saasappoint_customer_detail').hide();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').hide();
			$('#saasappoint_feedback_appointment').hide();
		}
	});
});

/** Customer detail tab content **/
$(document).on('click', '.saasappoint_customer_detail_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'customer_detail_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_customer_detail').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_customer_detail').show();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').hide();
			$('#saasappoint_feedback_appointment').hide();
		}
	});
});

/** Reschedule Appointment detail tab content **/
$(document).on('click', '.saasappoint_reschedule_appointment_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'saasappoint_reschedule_appointment_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_reschedule_appointment').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_customer_detail').hide();
			$('#saasappoint_reschedule_appointment').show();
			$('#saasappoint_reject_appointment').hide();
			$('#saasappoint_feedback_appointment').hide();
		}
	});
});

/** Reject Appointment detail tab content **/
$(document).on('click', '.saasappoint_reject_appointment_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'saasappoint_reject_appointment_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_reject_appointment').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_customer_detail').hide();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').show();
			$('#saasappoint_feedback_appointment').hide();
		}
	});
});

/** On date change get slots **/
$(document).on('change', '#saasappoint_appt_rs_date', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var datetime = $(this).data('datetime');
	var oid = $(this).data('oid');
	var selected_date = $(this).val();
	var service_id = $("#saasappoint_appt_rs_sid").val();
	$.ajax({
		type: 'post',
		data: {
			'order_id': oid,
			'booking_datetime': datetime,
			'selected_date': selected_date,
			'service_id': service_id,
			'saasappoint_slots_on_date_change': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('.saasappoint_appt_rs_timeslot').html(res);
			$('.saasappoint_appt_rs_endtimeslot').html("");
			$('.saasappoint_appt_rs_timeslot option:first').trigger("change");
		}
	});
});

/** Reschedule Appointment JS **/
$(document).on('click', '.saasappoint_appt_rs_now_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var date = $("#saasappoint_appt_rs_date").val();
	var slot = $(".saasappoint_appt_rs_timeslot").val();
	var endslot = $(".saasappoint_appt_rs_endtimeslot").val();
	var reason = $("#saasappoint_appt_rs_reason").val();
	if(date != "" && slot != "" && slot !== null && endslot != "" && endslot !== null){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'order_id': id,
				'date': date,
				'slot': slot,
				'endslot': endslot,
				'reason': reason,
				'reschedule_appointment_detail': 1
			},
			url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					$('.saasappoint_confirm_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_pending_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_reschedule_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_reject_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_complete_appointment_link').parent().removeClass('saasappoint-hide');
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					$('.saasappoint_appointment_detail_link').trigger('click');
					swal("Rescheduled!", 'Appointment rescheduled successfully', "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Reject Appointment JS **/
$(document).on('click', '.saasappoint_appt_reject_now_btn', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var reason = $("#saasappoint_appt_reject_reason").val();
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'reason': reason,
			'reject_customerappointment_detail': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				$('.saasappoint_confirm_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_pending_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_reschedule_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_reject_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_complete_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_feedback_appointment_link').parent().removeClass('saasappoint-hide');
				$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
				$('.saasappoint_appointment_detail_link').trigger('click');
				swal("Cancelled!", 'Appointment cancelled successfully', "success");
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
				'customer_id': id,
				'old_password': old_password,
				'new_password': new_password,
				'change_customer_password': 1
			},
			url: ajaxurl + "saasappoint_customer_ajax.php",
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
	var uploaded_file = $("#saasappoint-image-upload-file-hidden").val();
	var firstname = $("#saasappoint_profile_firstname").val();
	var lastname = $("#saasappoint_profile_lastname").val();
	var phone = $("#saasappoint_profile_phone").val();
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
			saasappoint_profile_state: { required:true},
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
				'uploaded_file': uploaded_file,
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
			url: ajaxurl + "saasappoint_customer_ajax.php",
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
				url: ajaxurl + "saasappoint_customer_support_ticket_discussions_ajax.php",
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

/** Generate support ticket JS **/
$(document).on('click', '#saasappoint_generate_support_ticket_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	/** Validate generate support ticket form **/
	$('#saasappoint_generate_support_ticket_form').validate({
		rules: {
			saasappoint_tickettitle:{ required: true },
			saasappoint_ticketdescription:{ required: true }
		},
		messages: {
			saasappoint_tickettitle:{ required: "Please enter ticket title" },
			saasappoint_ticketdescription:{ required: "Please enter ticket description" }
		}
	});
	if($('#saasappoint_generate_support_ticket_form').valid()){
		var business_id = $("#saasappoint_ticket_business").val();
		var title = $("#saasappoint_tickettitle").val();
		var description = $("#saasappoint_ticketdescription").val();
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'business_id': business_id,
				'ticket_title': title,
				'description': description,
				'generate_support_ticket': 1
			},
			url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
			success: function (res) {
				$("#saasappoint-generate-ticket-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", "Support ticket generated successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

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
			url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
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
		url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
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
		url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
		success: function (res) {
			window.location.href = site_url+'backend/c-ticket-discussion.php?tid='+id;
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
			url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
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
			url: ajaxurl + "saasappoint_customer_support_tickets_ajax.php",
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
			url: ajaxurl + "saasappoint_customer_ajax.php",
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

/** Rating & Review Appointment detail tab content **/
$(document).on('click', '.saasappoint_feedback_appointment_link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'saasappoint_feedback_appointment_tab': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_feedback_appointment').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_customer_detail').hide();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').hide();
			$('#saasappoint_feedback_appointment').show();
		}
	});
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

/** JS to submit feedback **/
$(document).on("click", ".saasappoint_submit_feedback_btn", function(){
	var ajax_url = generalObj.ajax_url;
	
	/** validate feedback form **/
	$('#saasappoint_feedback_form').validate({
		rules: {
			saasappoint_fb_name:{ required: true },
			saasappoint_fb_email: { required:true, email:true },
			saasappoint_fb_review: { required:true }
		},
		messages: {
			saasappoint_fb_name:{ required: "Please enter name" },
			saasappoint_fb_email: { required: "Please enter email", email: "Please enter valid email" },
			saasappoint_fb_review: { required: "Please enter review" }
		}
	});
	
	if($('#saasappoint_feedback_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var order_id = $(this).data("id");
		var review = $("#saasappoint_fb_review").val();
		var rating = $("#saasappoint_fb_rating").val();
		
		$.ajax({
			type: 'post',
			data: {
				'order_id': order_id,
				'review': review,
				'rating': rating,
				'add_feedback': 1
			},
			url: ajax_url + "saasappoint_my_appointment_detail_ajax.php",
			success: function (res) {
				if(res=="added"){
					swal("Submitted! Your review submitted successfully.", "", "success");
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					$('.saasappoint_feedback_appointment_link').trigger('click');
				}else{
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Opps! Something went wrong. Please try again.", "", "error");
				}
				
			}
		});
	}
});

/** Change endslot on startslot selection JS **/
$(document).on('change', '.saasappoint_appt_rs_timeslot', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var oid = $('#saasappoint_appt_rs_date').data('oid');
	var service_id = $("#saasappoint_appt_rs_sid").val();
	var datetime = $("#saasappoint_appt_rs_date").data('datetime');
	var selected_date = $("#saasappoint_appt_rs_date").val();
	var selected_startslot = $(this).val();
	
	$.ajax({
		type: 'post',
		data: {
			'order_id': oid,
			'booking_datetime': datetime,
			'selected_date': selected_date,
			'service_id': service_id,
			'selected_startslot': selected_startslot,
			'get_endtimeslots': 1
		},
		url: ajaxurl + "saasappoint_my_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if ($('.saasappoint_appt_rs_endtimeslot').hasClass("saasappoint_appt_rs_endtimeslot_input")) {
				$(".saasappoint_appt_rs_endtimeslot").val(res);
			}else{
				$(".saasappoint_appt_rs_endtimeslot").html(res);
			}
		}
	});
});