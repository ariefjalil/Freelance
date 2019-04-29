/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/
var saasappoint_stripe;
var saasappoint_stripe_plan_card;
/** Initialization on ready state JS **/
$(document).ready(function () {
	var ajaxurl = generalObj.ajax_url;
	var site_url = generalObj.site_url;
	
	/** JS to add intltel input to phone number **/
	$("#saasappoint_profile_phone, #saasappoint_company_phone").intlTelInput({
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
		editable: true,
		eventDrop: function(event, delta, revertFunc) {
			var selected_date = event.start.format().substring(0, 10);
			var selected_datetime = event.start.format().substring(0, 10) + " " + event.start.format().substring(11, 19);
			var d =  new Date(selected_date);
			var tdate = new Date();
			tdate.setDate(tdate.getDate() - 1);
			var curr_date = d.getDate();
			var curr_month = d.getMonth()+1;
			var curr_year = d.getFullYear();
			if(d > tdate){			
				swal({
					title: "Reschedule Reason:",
					text: "<textarea class='form-control fullwidth' id='rs_appointment_reason' placeholder='Enter reschedule reason'></textarea>",
					html: true,
					showCancelButton: true,
					closeOnConfirm: false
				}, function (isRescheduled) {
					if (isRescheduled) {
						$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
						var rs_reason = $("#rs_appointment_reason").val();
						$.ajax({
							type: 'post',
							data: {
								'selected_datetime': selected_datetime,
								'order_id': event.id,
								'reason': rs_reason,
								'update_dragged_appointment': 1
							},
							url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
							success: function (res) {
								$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
								if(res=="updated"){
									$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
									swal("Rescheduled!", 'Appointment rescheduled successfully', "success");
								}else{
									swal("Opps!", "Something went wrong. Please try again.", "error");
								}
							}
						});
					}else{
						revertFunc();
					}
				});
			} else {
				swal("Opps!", "You can not book on previous date.", "error");
				revertFunc();
			}
		},
		refetch: false,
		firstDay: 1,
		eventLimit: 6,
		eventTextColor: "#FFF",
		events: ajaxurl + 'saasappoint_appointments_ajax.php',
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
					url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
					success: function (res) {
						$('.saasappoint_delete_appt_btn').attr('data-id', event.id);
						$('.saasappoint_appointment_detail_modal_body').html(res);
						$('#saasappoint_appointment_detail_modal').modal('show');
						$('.saasappoint_appointment_detail_link').trigger('click');
					}
				});
			});
		},
		/*  calendar day click show manual booking  */
		dayClick: function (date, jsEvent, view) {
			var selected_datetime = new Date(date);
			var selected_date = selected_datetime.getDate();
			var selected_month = selected_datetime.getMonth() + 1;
			var selected_year = selected_datetime.getFullYear();
			var selected_date_with_format = selected_year+"-"+selected_month+"-"+selected_date;
			
			var current_datetime = new Date();
			var current_date = current_datetime.getDate();
			var current_month = current_datetime.getMonth() + 1;
			var current_year = current_datetime.getFullYear();
			var current_date_with_format = current_year + "-" + current_month + "-" + current_date;
			
			if (new Date(selected_date_with_format).getTime() < new Date(current_date_with_format).getTime()) {
				swal("Opps!", "You cannot book on past date.", "error");
			}else{
				var new_smonth = selected_month;
				var new_sdate = selected_date;
				if(new_smonth<10){ new_smonth = "0"+new_smonth; };
				var new_date_format = selected_year + "-" + new_smonth + "-" + new_sdate;
				
				$(".saasappoint_cal_next_month").data('month',selected_year + "-" + new_smonth + "-01");
				$(".saasappoint_cal_next_month").trigger('click');
							
				var datetrigger = setTimeout(function(){
					$(".saasappoint_date_selection").each(function(){
						if($(this).data('day')==new_date_format){
							$(".saasappoint_date_selection[data-day='"+new_date_format+"']").trigger("click");
							clearInterval(datetrigger);
						}
					});
				}, 1000);
				
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
				
				$('#saasappoint_manual_booking_modal').modal("show");
			}
		}
	});
	
	/** Check for setup instruction modal **/
	if(generalObj.setup_instruction_modal_status == "Y"){
		$("#saasappoint-setup-instruction-modal").modal("show");
	}
	
	/** datatables JS **/
	$("#saasappoint_blockoff_list_table").DataTable({
		"order": [ 0, 'desc' ],
		"stripeClasses": [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false },
			{ bSortable: false }
		] 
	});
	
	$('#saasappoint_coupons_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_coupon_ajax.php?refresh_coupon"
        },
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
	$('#saasappoint_rc_payment_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_rc_payments_ajax.php?refresh_rc_payments"
        }
    } ); 
	$('#saasappoint_gc_payment_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_gc_payments_ajax.php?refresh_gc_payments"
        }
    } ); 
	$('#saasappoint_registered_customers_detail').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_registered_customer_ajax.php?refresh_rc_detail"
        },
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
	$('#saasappoint_guest_customers_detail').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_guest_customer_ajax.php?refresh_gc_detail"
        },
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
	$('#saasappoint_feedback_list_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_feedback_ajax.php?refresh_feedbacks"
        },
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
	$('#saasappoint_categories_list_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_category_ajax.php?refresh_categories"
        },
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false },
			{ bSortable: false }
		]
    } ); 
	$('#saasappoint_support_ticket_list_table').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		aoColumns: [
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
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
			{ bSortable: true },
			{ bSortable: false }
		]
    } ); 
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
	
	/** Manage categories & services local session **/
	var saasappoint_pagename = window.location.pathname;
	var site_url = generalObj.site_url;
	
	if(saasappoint_pagename.indexOf("backend/category") != -1){
		localStorage['category_id'] = "";
		localStorage['service_id'] = "";
	}
	if(saasappoint_pagename.indexOf("backend/services") != -1){
		localStorage['service_id'] = "";
	}
	if(saasappoint_pagename.indexOf("backend/services") != -1){
		localStorage['service_id'] = "";
	}
	
	if(saasappoint_pagename.indexOf("backend/services") != -1 && (localStorage['category_id'] === undefined || localStorage['category_id'] == "")){
		window.location = site_url+"backend/category.php";
	}
	if(saasappoint_pagename.indexOf("backend/addons") != -1 && (localStorage['service_id'] === undefined || localStorage['service_id'] == "")){
		window.location = site_url+"backend/services.php";
	}
	
	/** Check categories local session **/
	if(localStorage['category_id'] !== undefined && localStorage['category_id'] != ""){
		$('#saasappoint_services_list_table').DataTable( {
			stripeClasses: [ 'saasappoint_datatable_strip', "" ],
			processing: true,
			serverSide: true,
			ajax: {
				dataSrc: "data",
				type: "POST",
				processData: true,
				url: ajaxurl + "saasappoint_services_ajax.php?refresh_services&catid="+localStorage['category_id']
			},
			aoColumns: [
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: false },
				{ bSortable: false }
			]
		} ); 
	}
	
	/** Check services local session **/
	if(localStorage['service_id'] !== undefined && localStorage['service_id'] != ""){
		$('#saasappoint_addons_list_table').DataTable( {
			stripeClasses: [ 'saasappoint_datatable_strip', "" ],
			processing: true,
			serverSide: true,
			ajax: {
				dataSrc: "data",
				type: "POST",
				processData: true,
				url: ajaxurl + "saasappoint_addons_ajax.php?refresh_addons&service_id="+localStorage['service_id']
			},
			aoColumns: [
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: true },
				{ bSortable: false },
				{ bSortable: false },
				{ bSortable: false }
			]
		} ); 
	}
	var saasappoint_pageurl = window.location.pathname;
	
	if(saasappoint_pageurl.indexOf("backend/location-selector") != -1 || saasappoint_pageurl.indexOf("backend/refund") != -1 || saasappoint_pageurl.indexOf("backend/email-sms-templates") != -1 || saasappoint_pageurl.indexOf("backend/settings") != -1){
		$('.saasappoint_text_editor_container').summernote({
			height: 200,
			tabsize: 2,
			placeholder: '<p>Write something...</p>'
		});
	}
});

/** Validation JS **/
$(document).ajaxComplete( function(){
	var saasappoint_pageurl = window.location.pathname;
	
	if(saasappoint_pageurl.indexOf("backend/location-selector") != -1 || saasappoint_pageurl.indexOf("backend/refund") != -1 || saasappoint_pageurl.indexOf("backend/email-sms-templates") != -1 || saasappoint_pageurl.indexOf("backend/settings") != -1){
		$('.saasappoint_text_editor_container').summernote({
			height: 200,
			tabsize: 2,
			placeholder: '<p>Write something...</p>'
		});
	}
});
$(document).bind('ready ajaxComplete',function(){
	
	/** Validate add coupon form **/
	$('#saasappoint_add_coupon_form').validate({
		rules: {
			saasappoint_couponcode:{ required: true },
			saasappoint_coupontype: { required:true },
			saasappoint_couponvalue: { required:true, pattern_price:true },
			saasappoint_couponexpiry: { required:true }
		},
		messages: {
			saasappoint_couponcode:{ required: "Please enter coupon code" },
			saasappoint_coupontype: { required: "Please select coupon type" },
			saasappoint_couponvalue: { required: "Please enter coupon value" },
			saasappoint_couponexpiry: { required: "Please enter coupon expiry" }
		}
	});
	/** Validate update coupon form **/
	$('#saasappoint_update_coupon_form').validate({
		rules: {
			saasappoint_update_couponcode:{ required: true },
			saasappoint_update_coupontype: { required:true },
			saasappoint_update_couponvalue: { required:true, pattern_price:true },
			saasappoint_update_couponexpiry: { required:true }
		},
		messages: {
			saasappoint_update_couponcode:{ required: "Please enter coupon code" },
			saasappoint_update_coupontype: { required: "Please select coupon type" },
			saasappoint_update_couponvalue: { required: "Please enter coupon value" },
			saasappoint_update_couponexpiry: { required: "Please enter coupon expiry" }
		}
	});
	/** Validate update frequently discount form **/
	$('#saasappoint_update_fd_form').validate({
		rules: {
			saasappoint_fdlabel:{ required: true },
			saasappoint_fdtype: { required:true },
			saasappoint_fdvalue: { required:true, pattern_price:true },
			saasappoint_fddescription: { required:true }
		},
		messages: {
			saasappoint_fdlabel:{ required: "Please enter frequently discount label" },
			saasappoint_fdtype: { required: "Please select frequently discount type" },
			saasappoint_fdvalue: { required: "Please enter frequently discount value" },
			saasappoint_fddescription: { required: "Please enter frequently discount description" }
		}
	});
	/** Validate add category form **/
	$('#saasappoint_add_category_form').validate({
		rules: {
			saasappoint_categoryname:{ required: true }
		},
		messages: {
			saasappoint_categoryname:{ required: "Please enter category name" }
		}
	});
	/** Validate update category form **/
	$('#saasappoint_update_category_form').validate({
		rules: {
			saasappoint_update_categoryname:{ required: true }
		},
		messages: {
			saasappoint_update_categoryname:{ required: "Please enter category name" }
		}
	});
	/** Validate add service form **/
	$('#saasappoint_add_service_form').validate({
		rules: {
			saasappoint_servicetitle:{ required: true },
			saasappoint_serviceduration:{ required: true, number: true, min: 1, max: 1440 },
			saasappoint_servicepbefore:{ required: true, number: true, min: 0, max: 1440 },
			saasappoint_servicepafter:{ required: true, number: true, min: 0, max: 1440 },
			saasappoint_servicedescription:{ required: true }
		},
		messages: {
			saasappoint_servicetitle:{ required: "Please enter service title" },
			saasappoint_serviceduration:{ required: "Please enter service duration", number: "Enter only numerics", min: "Please enter a value greater than or equal to 1.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_servicepbefore:{ required: "Please enter service padding before", number: "Enter only numerics", min: "Please enter a value greater than or equal to 0.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_servicepafter:{ required: "Please enter service padding after", number: "Enter only numerics", min: "Please enter a value greater than or equal to 0.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_servicedescription:{ required: "Please enter service description" }
		}
	});
	/** Validate update service form **/
	$('#saasappoint_update_service_form').validate({
		rules: {
			saasappoint_update_servicetitle:{ required: true },
			saasappoint_update_serviceduration:{ required: true, number: true, min: 1, max: 1440 },
			saasappoint_update_servicepbefore:{ required: true, number: true, min: 0, max: 1440 },
			saasappoint_update_servicepafter:{ required: true, number: true, min: 0, max: 1440 },
			saasappoint_update_servicedescription:{ required: true }
		},
		messages: {
			saasappoint_update_servicetitle:{ required: "Please enter service title" },
			saasappoint_update_serviceduration:{ required: "Please enter service duration", number: "Enter only numerics", min: "Please enter a value greater than or equal to 1.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_update_servicepbefore:{ required: "Please enter service padding before", number: "Enter only numerics", min: "Please enter a value greater than or equal to 0.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_update_servicepafter:{ required: "Please enter service padding after", number: "Enter only numerics", min: "Please enter a value greater than or equal to 0.", max: "Please enter a value less than or equal to 1440." },
			saasappoint_update_servicedescription:{ required: "Please enter service description" }
		}
	});
	/** Validate add addon form **/
	$('#saasappoint_add_addon_form').validate({
		rules: {
			saasappoint_addonname:{ required: true },
			saasappoint_addonrate:{ required: true, pattern_price:true },
			saasappoint_addondescription:{ required: true }
		},
		messages: {
			saasappoint_addonname:{ required: "Please enter addon name" },
			saasappoint_addonrate:{ required: "Please enter addon rate" },
			saasappoint_addondescription:{ required: "Please enter addon description" }
		}
	});
	/** Validate update addon form **/
	$('#saasappoint_update_addon_form').validate({
		rules: {
			saasappoint_update_addonname:{ required: true },
			saasappoint_update_addonrate:{ required: true, pattern_price:true },
			saasappoint_update_addondescription:{ required: true }
		},
		messages: {
			saasappoint_update_addonname:{ required: "Please enter addon name" },
			saasappoint_update_addonrate:{ required: "Please enter addon rate" },
			saasappoint_update_addondescription:{ required: "Please enter addon description" }
		}
	});
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
/** image upload js */
function saasappoint_update_read_uploaded_file_url(input) {
    if (input.files && input.files[0]) {
		if((input.files[0].size/1000) > 1000){
			swal("Opps!", "Maximum file upload size 1 MB.", "error");
		}else if(input.files[0].type =="image/jpeg" || input.files[0].type =="image/jpg" || input.files[0].type =="image/png"){
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#saasappoint-update-image-upload-file-hidden').val(e.target.result);
				$('#saasappoint-update-image-upload-file-preview').css('background-image', 'url('+e.target.result +')');
				$('#saasappoint-update-image-upload-file-preview').hide();
				$('#saasappoint-update-image-upload-file-preview').fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}else{
			swal("Opps!", "Please select a valid image file (jpeg, jpg and png are allowed).", "error");
		}
    }
}
$(document).on('change', "#saasappoint-update-image-upload-file", function() {
    saasappoint_update_read_uploaded_file_url(this);
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

function saasappoint_third_read_uploaded_file_url(input) {
    if (input.files && input.files[0]) {
		if((input.files[0].size/1000) > 1000){
			swal("Opps!", "Maximum file upload size 1 MB.", "error");
		}else if(input.files[0].type =="image/jpeg" || input.files[0].type =="image/jpg" || input.files[0].type =="image/png"){
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#saasappoint_bookingform_bg_image-hidden').val(e.target.result);
				$('#saasappoint_bookingform_bg_image-preview').css('background-image', 'url('+e.target.result +')');
				$('#saasappoint_bookingform_bg_image-preview').hide();
				$('#saasappoint_bookingform_bg_image-preview').fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}else{
			swal("Opps!", "Please select a valid image file (jpeg, jpg and png are allowed).", "error");
		}
    }
}
$(document).on('change', "#saasappoint_bookingform_bg_image", function() {
    saasappoint_third_read_uploaded_file_url(this);
});
/** Tab view js */
$(document).on('click', '.saasappoint_tab_view_nav_link', function(){
	var tabNo = $(this).data('tabno');
	$('.custom-nav-item').removeClass('active');
	$(".custom-nav-item:eq("+tabNo+")").addClass("active");
});

/** Add coupon JS **/
$(document).on('click', '.add_coupon_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var coupon_code = $('#saasappoint_couponcode').val();
	var coupon_type = $('#saasappoint_coupontype').val();
	var coupon_value = $('#saasappoint_couponvalue').val();
	var coupon_expiry = $('#saasappoint_couponexpiry').val();
	var coupon_status = $('.saasappoint_couponstatus:checked').val();
	if($('#saasappoint_add_coupon_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'coupon_code': coupon_code,
				'coupon_type': coupon_type,
				'coupon_value': coupon_value,
				'coupon_expiry': coupon_expiry,
				'status': coupon_status,
				'add_coupon': 1
			},
			url: ajaxurl + "saasappoint_coupon_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$('#saasappoint_add_coupon_modal').modal('hide');
				if(res=="added"){
					swal("Added!", 'Coupon added successfully', "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
				$('#saasappoint_couponcode').val('');
				$('#saasappoint_coupontype').val('');
				$('#saasappoint_couponvalue').val('');
				$('#saasappoint_couponexpiry').val('');
				$('#saasappoint_coupons_table').DataTable().ajax.reload();
			}
		});
	}
});

/** Change coupon status JS **/
$(document).on('change', '.saasappoint_change_coupon_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var coupon_status_check = $(this).prop('checked');
	var coupon_status_text = 'Disabled';
	var coupon_status = 'N';
	if(coupon_status_check){
		coupon_status_text = 'Enabled';
		coupon_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': coupon_status,
			'change_coupon_status': 1
		},
		url: ajaxurl + "saasappoint_coupon_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(coupon_status_text+"!", 'Coupon status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change feedback status JS **/
$(document).on('change', '.saasappoint_change_feedback_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var feedback_status_check = $(this).prop('checked');
	var feedback_status_text = 'Disabled';
	var feedback_status = 'N';
	if(feedback_status_check){
		feedback_status_text = 'Enabled';
		feedback_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': feedback_status,
			'change_feedback_status': 1
		},
		url: ajaxurl + "saasappoint_feedback_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				swal(feedback_status_text+"!", 'Feedback status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Delete coupon JS **/
$(document).on('click', '.saasappoint-delete-coupon-sweetalert', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this coupon",
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
				'delete_coupon': 1
			},
			url: ajaxurl + "saasappoint_coupon_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Coupon deleted successfully.", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
				$('#saasappoint_coupons_table').DataTable().ajax.reload();
			}
		});
	});
});

/** Update coupon modal JS **/
$(document).on('click', '.saasappoint-update-couponmodal', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_coupon_modal': 1
		},
		url: ajaxurl + "saasappoint_coupon_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('.saasappoint_update_coupon_modal_body').html(res);
			$('#saasappoint_update_coupon_modal').modal('show');
		}
	});
});

/** Update coupon JS **/
$(document).on('click', '.saasappoint_update_coupon_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var coupon_code = $('#saasappoint_update_couponcode').val();
	var coupon_type = $('#saasappoint_update_coupontype').val();
	var coupon_value = $('#saasappoint_update_couponvalue').val();
	var coupon_expiry = $('#saasappoint_update_couponexpiry').val();
	if($('#saasappoint_update_coupon_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'coupon_code': coupon_code,
				'coupon_type': coupon_type,
				'coupon_value': coupon_value,
				'coupon_expiry': coupon_expiry,
				'id': id,
				'update_coupon': 1
			},
			url: ajaxurl + "saasappoint_coupon_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$('#saasappoint_update_coupon_modal').modal('hide');
				if(res=="updated"){
					swal("Updated!", "Coupon updated successfully", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
				$('#saasappoint_coupons_table').DataTable().ajax.reload();
			}
		});
	}
});

/** Update Frequently Discount modal JS **/
$(document).on('click', '.saasappoint-update-fdmodal', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_fd_modal': 1
		},
		url: ajaxurl + "saasappoint_frequently_discount_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('.saasappoint_update_fd_modal_body').html(res);
			$('#saasappoint_update_fd_modal').modal('show');
		}
	});
});

/** Update Frequently Discount JS **/
$(document).on('click', '.saasappoint_update_fd_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var fd_label = $('#saasappoint_fdlabel').val();
	var fd_type = $('#saasappoint_fdtype').val();
	var fd_value = $('#saasappoint_fdvalue').val();
	var fd_description = $('#saasappoint_fddescription').val();
	if($('#saasappoint_update_fd_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'fd_label': fd_label,
				'fd_type': fd_type,
				'fd_value': fd_value,
				'fd_description': fd_description,
				'id': id,
				'update_frequently_discount': 1
			},
			url: ajaxurl + "saasappoint_frequently_discount_ajax.php",
			success: function (res) {
				$('#saasappoint_update_fd_modal').modal('hide');
				if(res=="updated"){
					swal("Updated!", "Frequently Discount updated successfully", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
				$.ajax({
					type: 'post',
					data: {
						'refresh_frequently_discount': 1
					},
					url: ajaxurl + "saasappoint_frequently_discount_ajax.php",
					success: function (res) {
						$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
						$('.saasappoint_frequently_discount_tbody').html(res);
					}
				});
			}
		});
	}
});

/** Change Frequently Discount status JS **/
$(document).on('change', '.saasappoint_change_fd_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var fd_status_check = $(this).prop('checked');
	var fd_status_text = 'Disabled';
	var fd_status = 'N';
	if(fd_status_check){
		fd_status_text = 'Enabled';
		fd_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'fd_status': fd_status,
			'change_fd_status': 1
		},
		url: ajaxurl + "saasappoint_frequently_discount_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(fd_status_text+"!", 'Frequently Discount status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change Schedule status JS **/
$(document).on('change', '.saasappoint_change_schedule_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var schedule_offday_check = $(this).prop('checked');
	var schedule_offday_text = 'Marked as Offday';
	var schedule_offday = 'Y';
	if(schedule_offday_check){
		schedule_offday_text = 'Marked as Working day';
		schedule_offday = 'N';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'offday': schedule_offday,
			'change_schedule_offday': 1
		},
		url: ajaxurl + "saasappoint_schedule_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(schedule_offday_text+"!", schedule_offday_text+' successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change Schedule start time JS **/
$(document).on('changed.bs.select', 'select.saasappoint_starttime_dropdown', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var db_starttime = $(".saasappoint_starttime_dropdown_hidden_"+id).val();
	var starttime = $(this).val();
	var position = $('option:selected', this).data('position');
	var endtime_position = $('#saasappoint_endtime_dropdown_'+id+' option:selected').data('position');
	if(endtime_position>position){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'starttime': starttime,
				'update_schedule_starttime': 1
			},
			url: ajaxurl + "saasappoint_schedule_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					$(".saasappoint_starttime_dropdown_hidden_"+id).val(starttime);
					swal("Updated!", 'Schedule start time updated successfully', "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}else{
		$("#saasappoint_starttime_dropdown_"+id).val(db_starttime);
		$("#saasappoint_starttime_dropdown_"+id).selectpicker("refresh");
		swal("Opps!", "Please select start time less than end time.", "error");
	}
});

/** Change Schedule end time JS **/
$(document).on('changed.bs.select', 'select.saasappoint_endtime_dropdown', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var endtime = $(this).val();
	var db_endtime = $(".saasappoint_endtime_dropdown_hidden_"+id).val();
	var position = $('option:selected', this).data('position');
	var starttime_position = $('#saasappoint_starttime_dropdown_'+id+' option:selected').data('position');
	if(starttime_position<position){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'endtime': endtime,
				'update_schedule_endtime': 1
			},
			url: ajaxurl + "saasappoint_schedule_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					$(".saasappoint_endtime_dropdown_hidden_"+id).val(starttime);
					swal("Updated!", 'Schedule end time updated successfully', "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}else{
		$("#saasappoint_endtime_dropdown_"+id).val(db_endtime);
		$("#saasappoint_endtime_dropdown_"+id).selectpicker("refresh");
		swal("Opps!", "Please select end time greater than start time.", "error");
	}
});

/** Registered Customer appointments modal JS **/
$(document).on('click', '.saasappoint_customer_appointments_btn', function(){
	$('#saasappoint_customer_appointments_listing').DataTable().destroy();
	$('#saasappoint_customer_appointments_listing tbody').empty();
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var ctype = $(this).data('ctype');
	$('#saasappoint_customer_appointment_modal').modal('show');
	$('#saasappoint_customer_appointments_listing').DataTable( {
		stripeClasses: [ 'saasappoint_datatable_strip', "" ],
		processing: true,
        serverSide: true,
        ajax: {
			dataSrc: "data",
            type: "POST",
			processData: true,
			url: ajaxurl + "saasappoint_customer_appointments_ajax.php?refresh_appt_detail&c_id="+id+"&ctype="+ctype
        }
    } );
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('#saasappoint_customer_detail').html(res);
			$('#saasappoint_appointment_detail').hide();
			$('#saasappoint_payment_detail').hide();
			$('#saasappoint_feedback_appointment').hide();
			$('#saasappoint_customer_detail').show();
			$('#saasappoint_reschedule_appointment').hide();
			$('#saasappoint_reject_appointment').hide();
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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

/** Delete Appointment JS **/
$(document).on('click', '.saasappoint_delete_appt_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this appointment",
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
				'order_id': id,
				'delete_appointment': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					swal("Deleted!", "Appointment deleted successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Confirm Appointment JS **/
$(document).on('click', '.saasappoint_confirm_appointment_link', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to confirm this appointment",
	  type: "success",
	  showCancelButton: true,
	  confirmButtonClass: "btn-success",
	  confirmButtonText: "Yes, confirm it!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'order_id': id,
				'confirm_appointment': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="confirmed"){
					$('.saasappoint_confirm_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_pending_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_reschedule_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_reject_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_complete_appointment_link').parent().removeClass('saasappoint-hide');
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					$('.saasappoint_appointment_detail_link').trigger('click');
					swal("Confirmed!", "Appointment confirmed successfully.", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Mark as Pending Appointment JS **/
$(document).on('click', '.saasappoint_pending_appointment_link', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to mark this appointment as pending",
	  type: "success",
	  showCancelButton: true,
	  confirmButtonClass: "btn-warning",
	  confirmButtonText: "Yes, Mark as pending!",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'order_id': id,
				'mark_as_pending_appointment': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="pending"){
					$('.saasappoint_confirm_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_pending_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_reschedule_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_reject_appointment_link').parent().removeClass('saasappoint-hide');
					$('.saasappoint_complete_appointment_link').parent().removeClass('saasappoint-hide');
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					$('.saasappoint_appointment_detail_link').trigger('click');
					swal("Marked as pending!", "Appointment marked as pending successfully.", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Mark as Complete Appointment JS **/
$(document).on('click', '.saasappoint_complete_appointment_link', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to mark this appointment as complete",
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
				'order_id': id,
				'mark_as_completed_appointment': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="completed"){
					$('.saasappoint_confirm_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_pending_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_reschedule_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_reject_appointment_link').parent().addClass('saasappoint-hide');
					$('.saasappoint_complete_appointment_link').parent().addClass('saasappoint-hide');
					$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
					$('.saasappoint_appointment_detail_link').trigger('click');
					swal("Marked as completed!", "Appointment marked as completed successfully.", "success");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Mark as Refunded request JS **/
$(document).on('click', '.saasappoint_markasrefunded_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "Refund process has been transferred",
	  type: "success",
	  showCancelButton: true,
	  confirmButtonClass: "btn-success",
	  confirmButtonText: "Yes, Refunded!",
	  cancelButtonText: "Not now",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'markasrefunded_appointment': 1
			},
			url: ajaxurl + "saasappoint_refund_request_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="changed"){
					swal("Refunded!", "Refund request processed successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Mark as Cancel refund request JS **/
$(document).on('click', '.saasappoint_cancel_refundrequest_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "You want to cancel refund request",
	  type: "success",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, Cancel it!",
	  cancelButtonText: "Not now",
	  closeOnConfirm: false
	},
	function(){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'cancel_refundrequest': 1
			},
			url: ajaxurl + "saasappoint_refund_request_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="changed"){
					swal("Refunded!", "Refund request cancelled successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** On date change get slots **/
$(document).on('change', '#saasappoint_appt_rs_date', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var datetime = $(this).data('datetime');
	var selected_date = $(this).val();
	var service_id = $("#saasappoint_appt_rs_sid").val();
	$.ajax({
		type: 'post',
		data: {
			'booking_datetime': datetime,
			'selected_date': selected_date,
			'service_id': service_id,
			'saasappoint_slots_on_date_change': 1
		},
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
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
			'reject_appointment_detail': 1
		},
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				$('.saasappoint_confirm_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_pending_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_reschedule_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_reject_appointment_link').parent().addClass('saasappoint-hide');
				$('.saasappoint_complete_appointment_link').parent().addClass('saasappoint-hide');
				$('#saasappoint-appointments-calendar').fullCalendar('refetchEvents');
				$('.saasappoint_appointment_detail_link').trigger('click');
				swal("Rejected!", 'Appointment rejected successfully', "success");
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
				'admin_id': id,
				'old_password': old_password,
				'new_password': new_password,
				'change_admin_password': 1
			},
			url: ajaxurl + "saasappoint_admin_ajax.php",
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

/** Display appointment notification detail JS **/
$(document).on('click', '.saasappoint-notification-dropdown-link', function(){
	if($('#saasappoint-notification-dropdown-content').css('display') === 'none'){
		/* hide refund dropdown start */
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-refundrequest-dd").removeClass("show");
		$('#saasappoint-refund-dropdown-content').html("");
		$('#saasappoint-refund-dropdown-content').slideUp();
		/* hide refund dropdown end */
		
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-notification-dd").addClass("show");
		var ajaxurl = generalObj.ajax_url;
		$.ajax({
			type: 'post',
			data: {
				'get_notification_appointment_detail': 1
			},
			url: ajaxurl + "saasappoint_notification_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$('#saasappoint-notification-dropdown-content').html(res);
				$('#saasappoint-notification-dropdown-content').slideDown();
			}
		});
	}else{
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-notification-dd").removeClass("show");
		$('#saasappoint-notification-dropdown-content').html("");
		$('#saasappoint-notification-dropdown-content').slideUp();
	}
});

/** Display refund request detail JS **/
$(document).on('click', '.saasappoint-refund-dropdown-link', function(){
	if($('#saasappoint-refund-dropdown-content').css('display') === 'none'){
		/* hide notification dropdown start */
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-notification-dd").removeClass("show");
		$('#saasappoint-notification-dropdown-content').html("");
		$('#saasappoint-notification-dropdown-content').slideUp();
		/* hide notification dropdown start */
		
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-refundrequest-dd").addClass("show");
		var ajaxurl = generalObj.ajax_url;
		$.ajax({
			type: 'post',
			data: {
				'get_refund_request_detail': 1
			},
			url: ajaxurl + "saasappoint_refund_request_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$('#saasappoint-refund-dropdown-content').html(res);
				$('#saasappoint-refund-dropdown-content').slideDown();
			}
		});
	}else{
		$("#saasappoint-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown.saasappoint-refundrequest-dd").removeClass("show");
		$('#saasappoint-refund-dropdown-content').html("");
		$('#saasappoint-refund-dropdown-content').slideUp();
	}
});

/** Display appointment notification detail modal JS **/
$(document).on('click', '.saasappoint-notification-appointment-modal-link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$('.saasappoint-notification-dropdown-link').trigger("click");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'mark_appointment_as_read': 1
		},
		url: ajaxurl + "saasappoint_notification_ajax.php"
	});
	$.ajax({
		type: 'post',
		data: {
			'order_id': id,
			'get_appointment_detail': 1
		},
		url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$('.saasappoint_delete_appt_btn').attr('data-id', id);
			$('.saasappoint_appointment_detail_modal_body').html(res);
			$('#saasappoint_appointment_detail_modal').modal('show');
			$('.saasappoint_appointment_detail_link').trigger('click');
		}
	});
});

/** Mark as read refund request from notification JS **/
$(document).on('click', '.saasappoint-notification-refundrequest-modal-link', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'mark_refund_request_as_read': 1
		},
		url: ajaxurl + "saasappoint_refund_request_ajax.php",
		success: function (res) {
			$.ajax({
				type: 'post',
				data: {
					'get_refund_request_detail': 1
				},
				url: ajaxurl + "saasappoint_refund_request_ajax.php",
				success: function (res) {
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					$('#saasappoint-refund-dropdown-content').html(res);
				}
			});
		}
	});
});

/** Export All Categories JS **/
$(document).on('click', '.saasappoint_export_services_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var categories = $("#saasappoint_export_categories").val();
	var services = $("#saasappoint_export_services").val();
	var addons = $("#saasappoint_export_addons").val();
	if(categories == "" && services == "" && addons == ""){
		swal("Opps!", "Please select atleast any of one option to export.", "error");
	}else if(categories != "" && services != "" && addons == ""){
		swal("Opps!", "Please select addon.", "error");
	}else if(categories != "" && services == "" && addons != ""){
		swal("Opps!", "Please select service.", "error");
	}else if(categories == "" && services != "" && addons != ""){
		swal("Opps!", "Please select category.", "error");
	}else{
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'categories': categories,
				'services': services,
				'addons': addons,
				'export_services': 1
			},
			url: ajaxurl + "saasappoint_export_services_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				window.location.href = res;
			}
		});
	}
});

/** Get services on Categories JS **/
$(document).on('change', '#saasappoint_export_categories', function(){
	var ajaxurl = generalObj.ajax_url;
	var categories = $(this).val();
	if(categories != ""){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'categories': categories,
				'get_services_and_addons': 1
			},
			url: ajaxurl + "saasappoint_export_services_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				var detail = $.parseJSON(res);
				$("#saasappoint_export_services").html(detail['service_options']);
				$("#saasappoint_export_addons").html(detail['addon_options']);
				$("#saasappoint_export_services").selectpicker("refresh");
				$("#saasappoint_export_addons").selectpicker("refresh");
			}
		});
	}else{
		swal("Opps!", "Please select category.", "error");
	}
});

/** Get addons on services JS **/
$(document).on('change', '#saasappoint_export_services', function(){
	var ajaxurl = generalObj.ajax_url;
	var services = $(this).val();
	if(services != ""){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'services': services,
				'get_addons': 1
			},
			url: ajaxurl + "saasappoint_export_services_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				var detail = $.parseJSON(res);
				$("#saasappoint_export_addons").html(detail['addon_options']);
				$("#saasappoint_export_addons").selectpicker("refresh");
			}
		});
	}else{
		swal("Opps!", "Please select service.", "error");
	}
});

/** Export Appointments JS **/
$(document).on('click', '.saasappoint_export_appt_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var from_date = $("#saasappoint_export_appt_from").val();
	var to_date = $("#saasappoint_export_appt_to").val();
	var appt_type = $("#saasappoint_export_appt_type").val();
	if(from_date == ""){
		swal("Opps!", "Please select From date.", "error");
	}else if(to_date == ""){
		swal("Opps!", "Please select To date.", "error");
	}else{
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'from_date': from_date,
				'to_date': to_date,
				'appt_type': appt_type,
				'export_appointments': 1
			},
			url: ajaxurl + "saasappoint_export_appointments_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				window.location.href = res;
			}
		});
	}
});

/** Export Payments JS **/
$(document).on('click', '.saasappoint_export_payment_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var from_date = $("#saasappoint_export_payment_from").val();
	var to_date = $("#saasappoint_export_payment_to").val();
	var payment_type = $("#saasappoint_export_payment_type").val();
	if(from_date == ""){
		swal("Opps!", "Please select From date.", "error");
	}else if(to_date == ""){
		swal("Opps!", "Please select To date.", "error");
	}else{
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'from_date': from_date,
				'to_date': to_date,
				'payment_type': payment_type,
				'export_payments': 1
			},
			url: ajaxurl + "saasappoint_export_payments_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				window.location.href = res;
			}
		});
	}
});

/** Export Customers JS **/
$(document).on('click', '.saasappoint_export_customers_btn', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var customer_type = $("#saasappoint_export_customers_type").val();
	$.ajax({
		type: 'post',
		data: {
			'customer_type': customer_type,
			'export_customers': 1
		},
		url: ajaxurl + "saasappoint_export_customers_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			window.location.href = res;
		}
	});
});

/** Update Profile JS **/
$(document).on('click', '.saasappoint_update_profile_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var uploaded_file = $("#saasappoint-image-upload-file-hidden").val();
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
			url: ajaxurl + "saasappoint_admin_ajax.php",
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
			url: ajaxurl + "saasappoint_admin_ajax.php",
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

/** Update company settings JS **/
$(document).on('click', '#update_company_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var uploaded_file = $("#saasappoint-image-upload-file-hidden").val();
	var saasappoint_company_name = $("#saasappoint_company_name").val();
	var saasappoint_company_email = $("#saasappoint_company_email").val();
	var saasappoint_company_phone = $("#saasappoint_company_phone").intlTelInput("getNumber");
	var saasappoint_company_address = $("#saasappoint_company_address").val();
	var saasappoint_company_city = $("#saasappoint_company_city").val();
	var saasappoint_company_state = $("#saasappoint_company_state").val();
	var saasappoint_company_zip = $("#saasappoint_company_zip").val();
	var saasappoint_company_country = $("#saasappoint_company_country").val();
	
	/** Validate company settings form **/
	$('#saasappoint_company_settings_form').validate({
		rules: {
			saasappoint_company_name:{ required: true },
			saasappoint_company_email:{ required: true, email: true },
			saasappoint_company_phone:{ required: true, minlength: 10, maxlength: 15, pattern_phone:true },
			saasappoint_company_address:{ required: true },
			saasappoint_company_city:{ required: true },
			saasappoint_company_state:{ required: true },
			saasappoint_company_zip:{ required: true, pattern_zip:true, minlength: 5, maxlength: 10 },
			saasappoint_company_country:{ required: true }
		},
		messages: {
			saasappoint_company_name:{ required: "Please enter company name" },
			saasappoint_company_email:{ required: "Please enter company email", email: "Please enter valid email" },
			saasappoint_company_phone:{ required: "Please enter company phone", minlength: "Please enter minimum 10 digits", maxlength: "Please enter maximum 15 digits" },
			saasappoint_company_address:{ required: "Please enter company address" },
			saasappoint_company_city:{ required: "Please enter city" },
			saasappoint_company_state:{ required: "Please enter state" },
			saasappoint_company_zip:{ required: "Please enter zip", minlength: "Please enter minimum 5 characters", maxlength: "Please enter maximum 10 characters" },
			saasappoint_company_country:{ required: "Please enter country" }
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
				'saasappoint_company_address': saasappoint_company_address,
				'saasappoint_company_city': saasappoint_company_city,
				'saasappoint_company_state': saasappoint_company_state,
				'saasappoint_company_zip': saasappoint_company_zip,
				'saasappoint_company_country': saasappoint_company_country,
				'uploaded_file': uploaded_file,
				'update_company_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Company settings updated successfully', "success");
			}
		});
	}
});

/** Update Appearance settings JS **/
$(document).on('click', '#update_appearance_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_timeslot_interval = $("#saasappoint_timeslot_interval").val();
	var saasappoint_endtimeslot_selection_status = $("#saasappoint_endtimeslot_selection_status").val();
	var saasappoint_maximum_endtimeslot_limit = $("#saasappoint_maximum_endtimeslot_limit").val();
	var saasappoint_currency = $("#saasappoint_currency").val();
	var saasappoint_currency_symbol = $("#saasappoint_currency option:selected").data("symbol");
	var saasappoint_auto_confirm_appointment = $("#saasappoint_auto_confirm_appointment").val();
	var saasappoint_tax_status = $("#saasappoint_tax_status").val();
	var saasappoint_tax_type = $("#saasappoint_tax_type").val();
	var saasappoint_tax_value = $("#saasappoint_tax_value").val();
	var saasappoint_minimum_booking_amount = $("#saasappoint_minimum_booking_amount").val();
	var saasappoint_minimum_advance_booking_time = $("#saasappoint_minimum_advance_booking_time").val();
	var saasappoint_maximum_advance_booking_time = $("#saasappoint_maximum_advance_booking_time").val();
	var saasappoint_cancellation_buffer_time = $("#saasappoint_cancellation_buffer_time").val();
	var saasappoint_reschedule_buffer_time = $("#saasappoint_reschedule_buffer_time").val();
	var saasappoint_date_format = $("#saasappoint_date_format").val();
	var saasappoint_time_format = $("#saasappoint_time_format").val();
	var saasappoint_timezone = $("#saasappoint_timezone").val();
	var saasappoint_show_frontend_rightside_feedback_list = $("#saasappoint_show_frontend_rightside_feedback_list").val();
	var saasappoint_show_frontend_rightside_feedback_form = $("#saasappoint_show_frontend_rightside_feedback_form").val();
	var saasappoint_show_guest_user_checkout = $("#saasappoint_show_guest_user_checkout").val();
	var saasappoint_show_existing_new_user_checkout = $("#saasappoint_show_existing_new_user_checkout").val();
	var saasappoint_hide_already_booked_slots_from_frontend_calendar = $("#saasappoint_hide_already_booked_slots_from_frontend_calendar").val();
	var saasappoint_thankyou_page_url = $("#saasappoint_thankyou_page_url").val();
	var saasappoint_terms_and_condition_link = $("#saasappoint_terms_and_condition_link").val();
	
	/** Check End Time Slot Limit **/
	if(saasappoint_endtimeslot_selection_status == "Y"){
		if(parseInt(saasappoint_maximum_endtimeslot_limit)<parseInt(saasappoint_timeslot_interval)){
			swal("Opps!", 'Maximum End Time Slot limit should be greater than equal to Time Slot Interval', "error");
			return false;
		}
	}
	
	/** Validate Appearance settings form **/
	$('#saasappoint_appearance_settings_form').validate();
	$("#saasappoint_thankyou_page_url").rules("add",
	{
		required: true, url: true,
		messages: { required: "Please enter thankyou page URL", url: "Please enter proper URL" }
	});
	$("#saasappoint_terms_and_condition_link").rules("add",
	{
		required: true, url: true,
		messages: { required: "Please enter terms & condition link", url: "Please enter proper URL" }
	});
	if(saasappoint_tax_status == "Y"){
		$("#saasappoint_tax_value").rules("add",
        {
            required: true, pattern_price: true,
            messages: { required: "Please enter tax value" }
        });
	}else{
		$("#saasappoint_tax_value").rules("add",
        {
            required: false, pattern_price: true,
            messages: { required: "Please enter tax value" }
        });
	}
	
	$("#saasappoint_minimum_booking_amount").rules("add",
	{
		required: false, pattern_price: true,
		messages: { required: "Please minimum booking amount" }
	});
	if($("#saasappoint_appearance_settings_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_timeslot_interval': saasappoint_timeslot_interval,
				'saasappoint_endtimeslot_selection_status': saasappoint_endtimeslot_selection_status,
				'saasappoint_maximum_endtimeslot_limit': saasappoint_maximum_endtimeslot_limit,
				'saasappoint_currency': saasappoint_currency,
				'saasappoint_currency_symbol': saasappoint_currency_symbol,
				'saasappoint_auto_confirm_appointment': saasappoint_auto_confirm_appointment,
				'saasappoint_tax_status': saasappoint_tax_status,
				'saasappoint_tax_type': saasappoint_tax_type,
				'saasappoint_tax_value': saasappoint_tax_value,
				'saasappoint_minimum_advance_booking_time': saasappoint_minimum_advance_booking_time,
				'saasappoint_maximum_advance_booking_time': saasappoint_maximum_advance_booking_time,
				'saasappoint_cancellation_buffer_time': saasappoint_cancellation_buffer_time,
				'saasappoint_reschedule_buffer_time': saasappoint_reschedule_buffer_time,
				'saasappoint_date_format': saasappoint_date_format,
				'saasappoint_time_format': saasappoint_time_format,
				'saasappoint_timezone': saasappoint_timezone,
				'saasappoint_show_frontend_rightside_feedback_list': saasappoint_show_frontend_rightside_feedback_list,
				'saasappoint_show_frontend_rightside_feedback_form': saasappoint_show_frontend_rightside_feedback_form,
				'saasappoint_show_guest_user_checkout': saasappoint_show_guest_user_checkout,
				'saasappoint_show_existing_new_user_checkout': saasappoint_show_existing_new_user_checkout,
				'saasappoint_hide_already_booked_slots_from_frontend_calendar': saasappoint_hide_already_booked_slots_from_frontend_calendar,
				'saasappoint_thankyou_page_url': saasappoint_thankyou_page_url,
				'saasappoint_terms_and_condition_link': saasappoint_terms_and_condition_link,
				'saasappoint_minimum_booking_amount': saasappoint_minimum_booking_amount,
				'update_appearance_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Appearance settings updated successfully', "success");
			}
		});
	}
});

/** Update Referral settings JS **/
$(document).on('click', '#update_referral_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_referral_discount_status = 'N';
	var saasappoint_referral_discount_status_check = $("#saasappoint_referral_discount_status").prop('checked');
	if(saasappoint_referral_discount_status_check){
		saasappoint_referral_discount_status = 'Y';
	}
	var saasappoint_referral_discount_type = $("#saasappoint_referral_discount_type").val();
	var saasappoint_referral_discount_value = $("#saasappoint_referral_discount_value").val();
		
	/** Validate Appearance settings form **/
	$('#saasappoint_referral_settings_form').validate();
	$("#saasappoint_referral_discount_type").rules("add",
	{
		required: true,
		messages: { required: "Please select referral dicount type" }
	});
	$("#saasappoint_referral_discount_value").rules("add",
	{
		required: true, pattern_price: true,
		messages: { required: "Please enter referral discount value" }
	});
	
	if($("#saasappoint_referral_settings_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_referral_discount_status': saasappoint_referral_discount_status,
				'saasappoint_referral_discount_type': saasappoint_referral_discount_type,
				'saasappoint_referral_discount_value': saasappoint_referral_discount_value,
				'update_referral_discount_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Referral discount settings updated successfully', "success");
			}
		});
	}
});

/** Update Refund settings JS **/
$(document).on('click', '#update_refund_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_refund_status = 'N';
	var saasappoint_refund_status_check = $("#saasappoint_refund_status").prop('checked');
	if(saasappoint_refund_status_check){
		saasappoint_refund_status = 'Y';
	}
	var saasappoint_refund_type = $("#saasappoint_refund_type option:selected").val();
	var saasappoint_refund_value = $("#saasappoint_refund_value").val();
	var saasappoint_refund_request_buffer_time = $("#saasappoint_refund_request_buffer_time option:selected").val();
	var saasappoint_refund_summary = $("#saasappoint_refund_summary").summernote('code');
	
	/** Validate Refund settings form **/
	$('#saasappoint_refund_settings_form').validate();
	if(saasappoint_refund_status == "Y"){
		$("#saasappoint_refund_value").rules("add",
        {
            required: true, pattern_price: true,
            messages: { required: "Please enter refund value" }
        });
		$("#saasappoint_refund_type").rules("add",
        {
            required: true,
            messages: { required: "Please select refund type" }
        });
		$("#saasappoint_refund_request_buffer_time").rules("add",
        {
            required: true,
            messages: { required: "Please select refund request buffer time" }
        });
	}else{
		$("#saasappoint_refund_value").rules("add",
        {
            required: false, pattern_price: true,
            messages: { required: "Please enter refund value" }
        });
		$("#saasappoint_refund_type").rules("add",
        {
            required: false,
            messages: { required: "Please select refund type" }
        });
		$("#saasappoint_refund_request_buffer_time").rules("add",
        {
            required: false,
            messages: { required: "Please select refund request buffer time" }
        });
	}
	
	if($("#saasappoint_refund_settings_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_refund_status': saasappoint_refund_status,
				'saasappoint_refund_type': saasappoint_refund_type,
				'saasappoint_refund_value': saasappoint_refund_value,
				'saasappoint_refund_request_buffer_time': saasappoint_refund_request_buffer_time,
				'saasappoint_refund_summary': saasappoint_refund_summary,
				'update_refund_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Refund settings updated successfully', "success");
			}
		});
	}
});

/** Update Email settings JS **/
$(document).on('click', '#update_email_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_admin_email_notification_status = 'N';
	var saasappoint_customer_email_notification_status = 'N';
	var saasappoint_admin_email_notification_status_check = $("#saasappoint_admin_email_notification_status").prop('checked');
	var saasappoint_customer_email_notification_status_check = $("#saasappoint_customer_email_notification_status").prop('checked');
	if(saasappoint_admin_email_notification_status_check){
		saasappoint_admin_email_notification_status = 'Y';
	}
	if(saasappoint_customer_email_notification_status_check){
		saasappoint_customer_email_notification_status = 'Y';
	}
	var saasappoint_email_sender_name = $("#saasappoint_email_sender_name").val();
	var saasappoint_email_sender_email = $("#saasappoint_email_sender_email").val();
	
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
				'saasappoint_admin_email_notification_status': saasappoint_admin_email_notification_status,
				'saasappoint_customer_email_notification_status': saasappoint_customer_email_notification_status,
				'saasappoint_email_sender_name': saasappoint_email_sender_name,
				'saasappoint_email_sender_email': saasappoint_email_sender_email,
				'update_email_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Email settings updated successfully', "success");
			}
		});
	}
});

/** Update SMS settings JS **/
$(document).on('click', '#update_sms_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_admin_sms_notification_status = 'N';
	var saasappoint_customer_sms_notification_status = 'N';
	var saasappoint_admin_sms_notification_status_check = $("#saasappoint_admin_sms_notification_status").prop('checked');
	var saasappoint_customer_sms_notification_status_check = $("#saasappoint_customer_sms_notification_status").prop('checked');
	if(saasappoint_admin_sms_notification_status_check){
		saasappoint_admin_sms_notification_status = 'Y';
	}
	if(saasappoint_customer_sms_notification_status_check){
		saasappoint_customer_sms_notification_status = 'Y';
	}

	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_admin_sms_notification_status': saasappoint_admin_sms_notification_status,
			'saasappoint_customer_sms_notification_status': saasappoint_customer_sms_notification_status,
			'update_sms_settings': 1
		},
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Updated!", 'SMS settings updated successfully', "success");
		}
	});
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
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Updated!", 'SEO settings updated successfully', "success");
		}
	});
});

/** Update Location selector settings JS **/
$(document).on('click', '#save_location_selector_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_location_selector_container = $("#saasappoint_location_selector_container").summernote('code');
	var saasappoint_location_selector = $("#saasappoint_location_selector").val();
	saasappoint_location_selector = saasappoint_location_selector.replace(/\s/g, '');
	
	var saasappoint_location_selector_status = "N";
	var saasappoint_location_selector_status_check = $("#saasappoint_location_selector_status").prop('checked');
	if(saasappoint_location_selector_status_check){
		saasappoint_location_selector_status = 'Y';
	}
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_location_selector_status': saasappoint_location_selector_status,
			'saasappoint_location_selector': saasappoint_location_selector,
			'saasappoint_location_selector_container': saasappoint_location_selector_container,
			'save_location_selector_settings': 1
		},
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Updated!", 'Location selector setting updated successfully', "success");
		}
	});
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
		$('#saasappoint_paypal_payment_settings_form').validate();
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
				url: ajaxurl + "saasappoint_settings_ajax.php",
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
		$('#saasappoint_stripe_payment_settings_form').validate();
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
				url: ajaxurl + "saasappoint_settings_ajax.php",
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
				url: ajaxurl + "saasappoint_settings_ajax.php",
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
				url: ajaxurl + "saasappoint_settings_ajax.php",
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
$(document).on("click", ".saasappoint_payment_settings_admin", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	
	$(".saasappoint_payment_settings_admin").each(function(){
		if($(this).attr("id") != "saasappoint_payment_settings_admin_"+id){
			$(this).removeClass("saasappoint-boxshadow_active");
		}
	});
	if(!$("#saasappoint_payment_settings_admin_"+id).hasClass("saasappoint-boxshadow_active")){
		$(this).addClass("saasappoint-boxshadow_active");
	}
	$.ajax({
		type: 'post',
		data: {
			'get_payment_settings': id
		},
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$("#update_payment_settings_btn").attr("data-payment", id)
			$(".saasappoint-payment-setting-form-modal-content").html(res);
			$("#saasappoint-payment-setting-form-modal").modal("show");
		}
	});
});

/** Set local session of category ID JS **/
$(document).on("click", ".saasappoint_set_catid", function(){
	var id = $(this).data('id');
	localStorage['category_id'] = id;
});

/** Set local session of service ID JS **/
$(document).on("click", ".saasappoint_set_serviceid", function(){
	var id = $(this).data('id');
	localStorage['service_id'] = id;
});

/** Change Categories status JS **/
$(document).on('change', '.saasappoint_change_category_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var category_status_check = $(this).prop('checked');
	var category_status_text = 'Disabled';
	var category_status = 'N';
	if(category_status_check){
		category_status_text = 'Enabled';
		category_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'category_status': category_status,
			'change_category_status': 1
		},
		url: ajaxurl + "saasappoint_category_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(category_status_text+"!", 'Category status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Delete Categories JS **/
$(document).on('click', '.saasappoint_delete_category_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this category",
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
				'delete_category': 1
			},
			url: ajaxurl + "saasappoint_category_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Category deleted successfully.", "success");
					$('#saasappoint_categories_list_table').DataTable().ajax.reload();
				}else if(res=="appointments exist"){
					swal("Opps!", "You cannot delete this category. You have appointment with this category.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Add Categories JS **/
$(document).on('click', '#saasappoint_add_category_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_add_category_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var cat_name = $("#saasappoint_categoryname").val();
		var cat_status = $("input[name='saasappoint_categorystatus']:checked").val();
		$.ajax({
			type: 'post',
			data: {
				'cat_name': cat_name,
				'status': cat_status,
				'add_category': 1
			},
			url: ajaxurl + "saasappoint_category_ajax.php",
			success: function (res) {
				$("#saasappoint-add-category-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", "Category added successfully.", "success");
					$('#saasappoint_categories_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update Categories modal detail JS **/
$(document).on('click', '.saasappoint-update-categorymodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_category_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_category_ajax.php",
		success: function (res) {
			$(".saasappoint-update-category-modal-body").html(res);
			$("#saasappoint-update-category-modal").modal("show");
			$("#saasappoint_update_category_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Update Categories JS **/
$(document).on('click', '#saasappoint_update_category_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	var cat_name = $("#saasappoint_update_categoryname").val();
	if($("#saasappoint_update_category_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'cat_name': cat_name,
				'update_category': 1
			},
			url: ajaxurl + "saasappoint_category_ajax.php",
			success: function (res) {
				$("#saasappoint-update-category-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", "Category updated successfully.", "success");
					$('#saasappoint_categories_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Add Services JS **/
$(document).on('click', '#saasappoint_add_service_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_add_service_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var cat_id = localStorage['category_id'];
		var title = $("#saasappoint_servicetitle").val();
		var duration = $("#saasappoint_serviceduration").val();
		var padding_before = $("#saasappoint_servicepbefore").val();
		var padding_after = $("#saasappoint_servicepafter").val();
		var ser_image = $("#saasappoint-image-upload-file-hidden").val();
		var description = $("#saasappoint_servicedescription").val();
		var ser_status = $("input[name='saasappoint_servicestatus']:checked").val();
		$.ajax({
			type: 'post',
			data: {
				'cat_id': cat_id,
				'title': title,
				'duration': duration,
				'padding_before': padding_before,
				'padding_after': padding_after,
				'uploaded_file': ser_image,
				'description': description,
				'status': ser_status,
				'add_service': 1
			},
			url: ajaxurl + "saasappoint_services_ajax.php",
			success: function (res) {
				$("#saasappoint-add-service-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", "Service added successfully.", "success");
					$('#saasappoint_services_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update Services JS **/
$(document).on('click', '#saasappoint_update_service_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_update_service_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var id = $(this).data("id");
		var duration = $("#saasappoint_update_serviceduration").val();
		var padding_before = $("#saasappoint_update_servicepbefore").val();
		var padding_after = $("#saasappoint_update_servicepafter").val();
		var title = $("#saasappoint_update_servicetitle").val();
		var ser_image = $("#saasappoint-update-image-upload-file-hidden").val();
		var description = $("#saasappoint_update_servicedescription").val();
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'duration': duration,
				'padding_before': padding_before,
				'padding_after': padding_after,
				'title': title,
				'uploaded_file': ser_image,
				'description': description,
				'update_service': 1
			},
			url: ajaxurl + "saasappoint_services_ajax.php",
			success: function (res) {
				$("#saasappoint-update-service-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", "Service updated successfully.", "success");
					$('#saasappoint_services_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update Services modal detail JS **/
$(document).on('click', '.saasappoint-update-servicemodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_service_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_services_ajax.php",
		success: function (res) {
			$(".saasappoint-update-service-modal-body").html(res);
			$("#saasappoint-update-service-modal").modal("show");
			$("#saasappoint_update_service_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** View Services modal detail JS **/
$(document).on('click', '.saasappoint-view-servicemodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'view_service_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_services_ajax.php",
		success: function (res) {
			$(".saasappoint-view-service-modal-body").html(res);
			$("#saasappoint-view-service-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** View addons modal detail JS **/
$(document).on('click', '.saasappoint-view-addonmodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'view_addon_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_addons_ajax.php",
		success: function (res) {
			$(".saasappoint-view-addon-modal-body").html(res);
			$("#saasappoint-view-addon-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Delete Services JS **/
$(document).on('click', '.saasappoint_delete_service_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this service",
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
				'delete_service': 1
			},
			url: ajaxurl + "saasappoint_services_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Service deleted successfully.", "success");
					$('#saasappoint_services_list_table').DataTable().ajax.reload();
				}else if(res=="appointments exist"){
					swal("Opps!", "You cannot delete this service. You have appointment with this service.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Delete Addons JS **/
$(document).on('click', '.saasappoint_delete_addon_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var service_id = localStorage['service_id'];
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this addon",
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
				'service_id': service_id,
				'delete_addon': 1
			},
			url: ajaxurl + "saasappoint_addons_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Addon deleted successfully.", "success");
					$('#saasappoint_addons_list_table').DataTable().ajax.reload();
				}else if(res=="appointments exist"){
					swal("Opps!", "You cannot delete this addon. You have appointment with this addon.", "error");
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Change Service status JS **/
$(document).on('change', '.saasappoint_change_service_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var service_status_check = $(this).prop('checked');
	var service_status_text = 'Disabled';
	var service_status = 'N';
	if(service_status_check){
		service_status_text = 'Enabled';
		service_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': service_status,
			'change_service_status': 1
		},
		url: ajaxurl + "saasappoint_services_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(service_status_text+"!", 'Service status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change Addon status JS **/
$(document).on('change', '.saasappoint_change_addon_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var addon_status_check = $(this).prop('checked');
	var addon_status_text = 'Disabled';
	var addon_status = 'N';
	if(addon_status_check){
		addon_status_text = 'Enabled';
		addon_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'status': addon_status,
			'change_addon_status': 1
		},
		url: ajaxurl + "saasappoint_addons_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(addon_status_text+"!", 'Addon status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change Addon multiple qty status JS **/
$(document).on('change', '.saasappoint_change_addon_multiple_qty_status', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	var addon_status_check = $(this).prop('checked');
	var addon_status_text = 'Disabled';
	var addon_status = 'N';
	if(addon_status_check){
		addon_status_text = 'Enabled';
		addon_status = 'Y';
	}
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'multiple_qty': addon_status,
			'change_addon_multiple_qty_status': 1
		},
		url: ajaxurl + "saasappoint_addons_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(addon_status_text+"!", 'Addon\'s multiple qty status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Add Addons JS **/
$(document).on('click', '#saasappoint_add_addon_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_add_addon_form').valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var service_id = localStorage['service_id'];
		var title = $("#saasappoint_addonname").val();
		var addon_image = $("#saasappoint-image-upload-file-hidden").val();
		var rate = $("#saasappoint_addonrate").val();
		var description = $("#saasappoint_addondescription").val();
		var addon_multipleqty_status = $("input[name='saasappoint_addonmultipleqty']:checked").val();
		var addon_status = $("input[name='saasappoint_addonstatus']:checked").val();
		$.ajax({
			type: 'post',
			data: {
				'service_id': service_id,
				'title': title,
				'uploaded_file': addon_image,
				'description': description,
				'rate': rate,
				'multiple_qty': addon_multipleqty_status,
				'status': addon_status,
				'add_addon': 1
			},
			url: ajaxurl + "saasappoint_addons_ajax.php",
			success: function (res) {
				$("#saasappoint-add-addon-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="added"){
					swal("Added!", "Addon added successfully.", "success");
					$('#saasappoint_addons_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Update Addons modal detail JS **/
$(document).on('click', '.saasappoint-update-addonmodal', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_addon_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_addons_ajax.php",
		success: function (res) {
			$(".saasappoint-update-addon-modal-body").html(res);
			$("#saasappoint-update-addon-modal").modal("show");
			$("#saasappoint_update_addon_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Update Addons JS **/
$(document).on('click', '#saasappoint_update_addon_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	if($('#saasappoint_update_addon_form').valid()){
		var id = $(this).data("id");
		var title = $("#saasappoint_update_addonname").val();
		var addon_image = $("#saasappoint-update-image-upload-file-hidden").val();
		var rate = $("#saasappoint_update_addonrate").val();
		var description = $("#saasappoint_update_addondescription").val();
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'title': title,
				'uploaded_file': addon_image,
				'rate': rate,
				'description': description,
				'update_addon': 1
			},
			url: ajaxurl + "saasappoint_addons_ajax.php",
			success: function (res) {
				$("#saasappoint-update-addon-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", "Addon updated successfully.", "success");
					$('#saasappoint_addons_list_table').DataTable().ajax.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Upgrade Subscription Plan Modal Detail JS **/
$(document).on('click', '.saasappoint-subscription-pricing-table-button', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$("#saasappoint_upgrade_plan_btn").attr("data-id", id);
		
	/** two checkout configuration **/
	var twocheckout_status = generalObj.twocheckout_status;
	if(twocheckout_status == 'Y'){
		$(function(){ TCO.loadPubKey('sandbox'); });
	}
	
	/** Stripe check **/ 
	var stripe_status = generalObj.stripe_status;
	var stripe_pkey = generalObj.stripe_pkey;
	if(stripe_status == "Y" && stripe_pkey != ""){
		/* Create a Stripe client. */
		saasappoint_stripe = Stripe(stripe_pkey);

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
		saasappoint_stripe_plan_card = saasappoint_stripe_elements.create('card', {style: saasappoint_stripe_plan_style});

		/* Add an instance of the card Element. */
		saasappoint_stripe_plan_card.mount('#saasappoint_stripe_plan_card_errors');
	}
	
	$("#saasappoint-upgrade-plan-modal").modal("show");
});

/** Upgrade subscription JS **/
$(document).on('click', '#saasappoint_upgrade_plan_btn', function(e){
	e.preventDefault();
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	var payment_method = $("input[name='saasappoint_payment_method_radio']:checked").val();
	if(id != ""){
		if(payment_method == "stripe"){
			saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
				if (result.error) {
					/* Inform the user if there was an error. */
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Opps!", result.error.message, "error");
				} else {
					/* Send the token via ajax */
					var token = result.token.id;
					saasappoint_upgradespaln_stripe(id, ajaxurl, token);
				}
			});
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
				saasappoint_upgradespaln_twocheckout(id, ajaxurl, token);
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
			
			saasappoint_upgradespaln_authorizenet(id, ajaxurl, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);
		}else if(payment_method == "pay manually"){
			saasappoint_upgradespaln_pay_manually(id, ajaxurl);
		}else if(payment_method == "paypal"){
			saasappoint_upgradespaln_paypal(id, ajaxurl);
		}
	}else{
		$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		swal("Opps!", "Please contact super admin to configure subscription plans.", "error");
	}
});

/** paymanually upgradespaln function **/
function saasappoint_upgradespaln_pay_manually(id, ajaxurl){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'upgrade_subscription_pay_manually': 1
		},
		url: ajaxurl + "saasappoint_subscriptions_ajax.php",
		success: function (res) {
			$("#saasappoint-upgrade-plan-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "Subscription plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

/** paypal upgradespaln function **/
function saasappoint_upgradespaln_paypal(id, ajaxurl){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'upgrade_subscription_paypal': 1
		},
		url: ajaxurl + "saasappoint_paypal_subscriptions_ajax.php",
		success: function (response) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			var response_detail = $.parseJSON(response);
			if(response_detail.status=='success'){
				window.location.href = response_detail.value; 
			}
			if(response_detail.status=='error'){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Opps!", response_detail.value, "error");
			}
		}
	});
}

/** authorize.net upgradespaln function **/
function saasappoint_upgradespaln_authorizenet(id, ajaxurl, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'cardnumber': cardnumber,
			'cardcvv': cardcvv,
			'cardexmonth': cardexmonth,
			'cardexyear': cardexyear,
			'cardholdername': cardholdername,
			'upgrade_subscription_authorizenet': 1
		},
		url: ajaxurl + "saasappoint_subscriptions_ajax.php",
		success: function (response) {
			$("#saasappoint-upgrade-plan-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="upgraded"){
				swal("Upgraded!", "Subscription plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** stripe buy sms credit function **/
function saasappoint_upgradespaln_stripe(id, ajaxurl, token){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'token': token,
			'upgrade_subscription_stripe': 1
		},
		url: ajaxurl + "saasappoint_subscriptions_ajax.php",
		success: function (res) {
			$("#saasappoint-upgrade-plan-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "Subscription plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

/** twocheckout upgradespaln function **/
function saasappoint_upgradespaln_twocheckout(id, ajaxurl, token){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'token': token,
			'upgrade_subscription_twocheckout': 1
		},
		url: ajaxurl + "saasappoint_subscriptions_ajax.php",
		success: function (res) {
			$("#saasappoint-upgrade-plan-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "Subscription plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

/** Upgrade SMS Plan Modal Detail JS **/
$(document).on('click', '#saasappoint-buy-sms-credit-btn', function(){
	var ajaxurl = generalObj.ajax_url;
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'get_sms_plan_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_sms_plans_ajax.php",
		success: function (res) {
			$(".saasappoint-buy-sms-credit-modal-body").html(res);
			$("#saasappoint-buy-sms-credit-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			
			/** two checkout configuration **/
			var twocheckout_status = generalObj.twocheckout_status;
			if(twocheckout_status == 'Y'){
				$(function(){ TCO.loadPubKey('sandbox'); });
			}
			
			/** Stripe check **/ 
			var stripe_status = generalObj.stripe_status;
			var stripe_pkey = generalObj.stripe_pkey;
			if(stripe_status == "Y" && stripe_pkey != ""){
				/* Create a Stripe client. */
				saasappoint_stripe = Stripe(stripe_pkey);

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
				saasappoint_stripe_plan_card = saasappoint_stripe_elements.create('card', {style: saasappoint_stripe_plan_style});

				/* Add an instance of the card Element. */
				saasappoint_stripe_plan_card.mount('#saasappoint_stripe_plan_card_errors');
			}
		}
	});
});

/** Upgrade SMS Plan JS **/
$(document).on('click', '#saasappoint_buy_sms_plan_credit_btn', function(e){
	e.preventDefault();
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $("input[name='saasappoint_sms_plans_group']:checked").val();
	var payment_method = $("input[name='saasappoint_payment_method_radio']:checked").val();
	if(id !== undefined){
		if(payment_method == "stripe"){
			saasappoint_stripe.createToken(saasappoint_stripe_plan_card).then(function(result) {
				if (result.error) {
					/* Inform the user if there was an error. */
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Opps!", result.error.message, "error");
				} else {
					/* Send the token via ajax */
					var token = result.token.id;
					saasappoint_buy_sms_credit_stripe(id, ajaxurl, token);
				}
			});
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
				saasappoint_buy_sms_credit_twocheckout(id, ajaxurl, token);
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
			
			saasappoint_buy_sms_credit_authorizenet(id, ajaxurl, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername);
		}else if(payment_method == "pay manually"){
			saasappoint_buy_sms_credit_pay_manually(id, ajaxurl);
		}else if(payment_method == "paypal"){
			saasappoint_buy_sms_credit_paypal(id, ajaxurl);
		}
	}else{
		$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		swal("Opps!", "Please contact super admin to set SMS plans.", "error");
	}
});

/** paymanually plan buy sms credit function **/
function saasappoint_buy_sms_credit_pay_manually(id, ajaxurl){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'upgrade_sms_plan_pay_manually': 1
		},
		url: ajaxurl + "saasappoint_sms_plans_ajax.php",
		success: function (res) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "SMS plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

/** paypal plan buy sms credit function **/
function saasappoint_buy_sms_credit_paypal(id, ajaxurl){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'upgrade_sms_plan_paypal': 1
		},
		url: ajaxurl + "saasappoint_paypal_sms_plans_ajax.php",
		success: function (response) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			var response_detail = $.parseJSON(response);
			if(response_detail.status=='success'){
				window.location.href = response_detail.value; 
			}
			if(response_detail.status=='error'){
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Opps!", response_detail.value, "error");
			}
		}
	});
}

/** authorize.net buy sms credit function **/
function saasappoint_buy_sms_credit_authorizenet(id, ajaxurl, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'cardnumber': cardnumber,
			'cardcvv': cardcvv,
			'cardexmonth': cardexmonth,
			'cardexyear': cardexyear,
			'cardholdername': cardholdername,
			'upgrade_sms_plan_authorizenet': 1
		},
		url: ajaxurl + "saasappoint_sms_plans_ajax.php",
		success: function (response) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(response=="upgraded"){
				swal("Upgraded!", "SMS plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+response, "error");
			}
		}
	});
}

/** stripe buy sms credit function **/
function saasappoint_buy_sms_credit_stripe(id, ajaxurl, token){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'token': token,
			'upgrade_sms_plan_stripe': 1
		},
		url: ajaxurl + "saasappoint_sms_plans_ajax.php",
		success: function (res) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "SMS plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

/** twocheckout buy sms credit function **/
function saasappoint_buy_sms_credit_twocheckout(id, ajaxurl, token){
	$.ajax({
		type: 'post',
		data: {
			'plan_id': id,
			'token': token,
			'upgrade_sms_plan_twocheckout': 1
		},
		url: ajaxurl + "saasappoint_sms_plans_ajax.php",
		success: function (res) {
			$("#saasappoint-buy-sms-credit-modal").modal("hide");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="upgraded"){
				swal("Upgraded!", "SMS plan upgraded successfully.", "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again. "+res, "error");
			}
		}
	});
}

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
				url: ajaxurl + "saasappoint_support_ticket_discussions_ajax.php",
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
		var title = $("#saasappoint_tickettitle").val();
		var description = $("#saasappoint_ticketdescription").val();
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'ticket_title': title,
				'description': description,
				'generate_support_ticket': 1
			},
			url: ajaxurl + "saasappoint_support_tickets_ajax.php",
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
			url: ajaxurl + "saasappoint_support_tickets_ajax.php",
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
		url: ajaxurl + "saasappoint_support_tickets_ajax.php",
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
		url: ajaxurl + "saasappoint_support_tickets_ajax.php",
		success: function (res) {
			window.location.href = site_url+'backend/ticket-discussion.php?tid='+id;
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
			url: ajaxurl + "saasappoint_support_tickets_ajax.php",
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
			url: ajaxurl + "saasappoint_support_tickets_ajax.php",
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

/** Prevent enter key stroke on form inputs **/
$(document).on("keydown", '.saasappoint form input', function (e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
});

/** Show & Hide block off custom div on change of block off type **/
$(document).on("change", ".saasappoint_blockoff_type", function(){
	var btype = $(this).val();
	if(btype == "custom"){
		$(".saasappoint_hide_blockoff_custom_box").slideDown();
	}else{
		$(".saasappoint_hide_blockoff_custom_box").slideUp();
	}
});

/** Add Block Off JS **/
$(document).on("click", "#saasappoint_add_blockoff_btn", function(){
	var ajaxurl = generalObj.ajax_url;
	
	/** Validate add block off form **/
	$('#saasappoint_add_blockoff_form').validate({
		rules: {
			saasappoint_blockofftitle:{ required: true },
			saasappoint_blockoff_fromdate:{ required: true, date: true },
			saasappoint_blockoff_todate:{ required: true, date: true },
			saasappoint_blockoff_type:{ required: true },
			saasappoint_blockoff_fromtime:{ required: true },
			saasappoint_blockoff_totime:{ required: true },
			saasappoint_blockoff_status:{ required: true }
		},
		messages: {
			saasappoint_blockofftitle:{ required: "Please enter block off title" },
			saasappoint_blockoff_fromdate:{ required: "Please select from date", date: "please select proper date" },
			saasappoint_blockoff_todate:{ required: "Please select to date", date: "please select proper date" },
			saasappoint_blockoff_type:{ required: "Please select block off type" },
			saasappoint_blockoff_fromtime:{ required: "Please select from time" },
			saasappoint_blockoff_totime:{ required: "Please select to time" },
			saasappoint_blockoff_status:{ required: "Please select status" }
		}
	});
	
	var saasappoint_blockoff_fromdate = $("#saasappoint_blockoff_fromdate").val();
	var saasappoint_blockoff_todate = $("#saasappoint_blockoff_todate").val();
	
	if(new Date(saasappoint_blockoff_fromdate) > new Date(saasappoint_blockoff_todate)){
		swal("Opps!", "Please select From Date less than To Date.", "error");
		return;
	}
	
	var saasappoint_blockoff_fromtime = $("#saasappoint_blockoff_fromtime").val();
	var saasappoint_blockoff_totime = $("#saasappoint_blockoff_totime").val();
	var fromtime = new Date("May 18, 2019 "+saasappoint_blockoff_fromtime);
	var totime = new Date("May 18, 2019 "+saasappoint_blockoff_totime);
	
	if(fromtime.getTime() > totime.getTime()){
		swal("Opps!", "Please select From Time less than To Time.", "error");
		return;
	}
		
	if($("#saasappoint_add_blockoff_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var saasappoint_blockofftitle = $("#saasappoint_blockofftitle").val();
		var saasappoint_blockoff_type = $(".saasappoint_blockoff_type:checked").val();
		var saasappoint_blockoff_status = $(".saasappoint_blockoff_status:checked").val();
		$.ajax({
			type: 'post',
			data: {
				'title': saasappoint_blockofftitle,
				'from_date': saasappoint_blockoff_fromdate,
				'to_date': saasappoint_blockoff_todate,
				'blockoff_type': saasappoint_blockoff_type,
				'from_time': saasappoint_blockoff_fromtime,
				'to_time': saasappoint_blockoff_totime,
				'status': saasappoint_blockoff_status,
				'add_blockoff': 1
			},
			url: ajaxurl + "saasappoint_block_off_ajax.php",
			success: function (res) {
				if(res=="added"){
					swal("Added!", "Block off added successfully.", "success");
					location.reload();
				}else{
					$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});


/** Update Block Off modal detail JS **/
$(document).on('click', '.saasappoint-update-blockoffmodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_blockoff_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_block_off_ajax.php",
		success: function (res) {
			$(".saasappoint-update-blockoff-modal-body").html(res);
			$("#saasappoint-update-blockoff-modal").modal("show");
			$("#saasappoint_update_blockoff_btn").attr("data-id",id);
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Update block off JS **/
$(document).on('click', '#saasappoint_update_blockoff_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	
	/** Validate update block off form **/
	$('#saasappoint_update_blockoff_form').validate({
		rules: {
			saasappoint_update_blockofftitle:{ required: true },
			saasappoint_update_blockoff_fromdate:{ required: true, date: true },
			saasappoint_update_blockoff_todate:{ required: true, date: true },
			saasappoint_update_blockoff_type:{ required: true },
			saasappoint_update_blockoff_fromtime:{ required: true },
			saasappoint_update_blockoff_totime:{ required: true }
		},
		messages: {
			saasappoint_update_blockofftitle:{ required: "Please enter block off title" },
			saasappoint_update_blockoff_fromdate:{ required: "Please select from date", date: "please select proper date" },
			saasappoint_update_blockoff_todate:{ required: "Please select to date", date: "please select proper date" },
			saasappoint_update_blockoff_type:{ required: "Please select block off type" },
			saasappoint_update_blockoff_fromtime:{ required: "Please select from time" },
			saasappoint_update_blockoff_totime:{ required: "Please select to time" }
		}
	});
	
	var saasappoint_update_blockoff_fromdate = $("#saasappoint_update_blockoff_fromdate").val();
	var saasappoint_update_blockoff_todate = $("#saasappoint_update_blockoff_todate").val();
	
	if(new Date(saasappoint_update_blockoff_fromdate) > new Date(saasappoint_update_blockoff_todate)){
		swal("Opps!", "Please select From Date less than To Date.", "error");
		return;
	}
	
	var saasappoint_update_blockoff_fromtime = $("#saasappoint_update_blockoff_fromtime").val();
	var saasappoint_update_blockoff_totime = $("#saasappoint_update_blockoff_totime").val();
	var fromtime = new Date("May 18, 2019 "+saasappoint_update_blockoff_fromtime);
	var totime = new Date("May 18, 2019 "+saasappoint_update_blockoff_totime);
	
	if(fromtime.getTime() > totime.getTime()){
		swal("Opps!", "Please select From Time less than To Time.", "error");
		return;
	}
	
	if($("#saasappoint_update_blockoff_form").valid()){
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		var saasappoint_update_blockofftitle = $("#saasappoint_update_blockofftitle").val();
		var saasappoint_update_blockoff_type = $(".saasappoint_update_blockoff_type:checked").val();
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'title': saasappoint_update_blockofftitle,
				'from_date': saasappoint_update_blockoff_fromdate,
				'to_date': saasappoint_update_blockoff_todate,
				'blockoff_type': saasappoint_update_blockoff_type,
				'from_time': saasappoint_update_blockoff_fromtime,
				'to_time': saasappoint_update_blockoff_totime,
				'update_blockoff': 1
			},
			url: ajaxurl + "saasappoint_block_off_ajax.php",
			success: function (res) {
				$("#saasappoint-update-blockoff-modal").modal("hide");
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="updated"){
					swal("Updated!", "Block off updated successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	}
});

/** Delete blockoff JS **/
$(document).on('click', '.saasappoint_delete_blockoff_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data('id');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this block off",
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
				'delete_blockoff': 1
			},
			url: ajaxurl + "saasappoint_block_off_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				if(res=="deleted"){
					swal("Deleted!", "Block off deleted successfully.", "success");
					location.reload();
				}else{
					swal("Opps!", "Something went wrong. Please try again.", "error");
				}
			}
		});
	});
});

/** Change Block off status JS **/
$(document).on('change', '.saasappoint_change_blockoff_status', function(){
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
			'change_blockoff_status': 1
		},
		url: ajaxurl + "saasappoint_block_off_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="changed"){
				swal(status_text+"!", 'Block off status changed successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Show hide card payemnt box JS **/
$(document).on("change", ".saasappoint_payment_method_radio", function(){
	if($(this).val() == "stripe" || $(this).val() == "authorize.net" || $(this).val() == "2checkout"){
		$(".saasappoint-card-payment-div").fadeIn();
	}else{
		$(".saasappoint-card-payment-div").fadeOut();
	}
});

/** Customer Email Collapsible JS **/
$(document).on("click", ".saasappoint_emailtemplate_settings_customer", function(){
	$(".saasappoint_emailtemplate_settings_admin").removeClass("saasappoint-boxshadow_active");
	if(!$(".saasappoint_emailtemplate_settings_customer").hasClass("saasappoint-boxshadow_active")){
		$(".saasappoint_emailtemplate_settings_customer").addClass("saasappoint-boxshadow_active");
	}
	$(".saasappoint_admin_email_templates").slideUp(1000);
	$(".saasappoint_customer_email_templates").slideDown(2000);
});

/** Admin Email Collapsible JS **/
$(document).on("click", ".saasappoint_emailtemplate_settings_admin", function(){
	$(".saasappoint_emailtemplate_settings_customer").removeClass("saasappoint-boxshadow_active");
	if(!$(".saasappoint_emailtemplate_settings_admin").hasClass("saasappoint-boxshadow_active")){
		$(".saasappoint_emailtemplate_settings_admin").addClass("saasappoint-boxshadow_active");
	}
	$(".saasappoint_customer_email_templates").slideUp(1000);
	$(".saasappoint_admin_email_templates").slideDown(2000);
});

/** Customer SMS Collapsible JS **/
$(document).on("click", ".saasappoint_smstemplate_settings_customer", function(){
	$(".saasappoint_smstemplate_settings_admin").removeClass("saasappoint-boxshadow_active");
	if(!$(".saasappoint_smstemplate_settings_customer").hasClass("saasappoint-boxshadow_active")){
		$(".saasappoint_smstemplate_settings_customer").addClass("saasappoint-boxshadow_active");
	}
	$(".saasappoint_admin_sms_templates").slideUp(1000);
	$(".saasappoint_customer_sms_templates").slideDown(2000);
});

/** Admin SMS Collapsible JS **/
$(document).on("click", ".saasappoint_smstemplate_settings_admin", function(){
	$(".saasappoint_smstemplate_settings_customer").removeClass("saasappoint-boxshadow_active");
	if(!$(".saasappoint_smstemplate_settings_admin").hasClass("saasappoint-boxshadow_active")){
		$(".saasappoint_smstemplate_settings_admin").addClass("saasappoint-boxshadow_active");
	}
	$(".saasappoint_customer_sms_templates").slideUp(1000);
	$(".saasappoint_admin_sms_templates").slideDown(2000);
});

/** Customize Email Template JS **/
$(document).on("click", ".saasappoint_email_template_modal_btn", function(){
	var ajaxurl = generalObj.ajax_url;
	var template = $(this).data("template");
	var template_for = $(this).data("template_for");
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'template': template,
			'template_for': template_for,
			'get_email_template': 1
		},
		url: ajaxurl + "saasappoint_email_sms_templates_ajax.php",
		success: function (res) {
			$(".saasappoint-emailtemplate-setting-form-modal-content").html(res);
			$("#saasappoint-emailtemplate-setting-form-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Customize SMS Template JS **/
$(document).on("click", ".saasappoint_sms_template_modal_btn", function(){
	var ajaxurl = generalObj.ajax_url;
	var template = $(this).data("template");
	var template_for = $(this).data("template_for");
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'template': template,
			'template_for': template_for,
			'get_sms_template': 1
		},
		url: ajaxurl + "saasappoint_email_sms_templates_ajax.php",
		success: function (res) {
			$(".saasappoint-smstemplate-setting-form-modal-content").html(res);
			$("#saasappoint-smstemplate-setting-form-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Update Email Template JS **/
$(document).on("click", "#update_emailtemplate_settings_btn", function(){
	var ajaxurl = generalObj.ajax_url;
	var template = $("#saasappoint_emailtemplate_template").val();
	var template_for = $("#saasappoint_emailtemplate_template_for").val();
	var subject = $("#saasappoint_email_template_subject").val();
	var email_content = $("#saasappoint_email_template_content").summernote('code');
	var email_status = "N";
	if($("#saasappoint_email_template_status").prop('checked')){
		email_status = "Y";
	} 
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'template': template,
			'template_for': template_for,
			'email_status': email_status,
			'subject': subject,
			'email_content': email_content,
			'update_email_template': 1
		},
		url: ajaxurl + "saasappoint_email_sms_templates_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				$("#saasappoint-emailtemplate-setting-form-modal").modal("hide");
				swal("Customized!", 'Email template customized successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Update SMS Template JS **/
$(document).on("click", "#update_smstemplate_settings_btn", function(){
	var ajaxurl = generalObj.ajax_url;
	var template = $("#saasappoint_smstemplate_template").val();
	var template_for = $("#saasappoint_smstemplate_template_for").val();
	var sms_content = $("#saasappoint_sms_template_content").val();
	var sms_status = "N";
	if($("#saasappoint_sms_template_status").prop('checked')){
		sms_status = "Y";
	} 
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'template': template,
			'template_for': template_for,
			'sms_status': sms_status,
			'sms_content': sms_content,
			'update_sms_template': 1
		},
		url: ajaxurl + "saasappoint_email_sms_templates_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res=="updated"){
				$("#saasappoint-smstemplate-setting-form-modal").modal("hide");
				swal("Customized!", 'SMS template customized successfully', "success");
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Change endslot on startslot selection JS **/
$(document).on('change', '.saasappoint_appt_rs_timeslot', function(){
	var ajaxurl = generalObj.ajax_url;
	var datetime = $("#saasappoint_appt_rs_date").data('datetime');
	var service_id = $("#saasappoint_appt_rs_sid").val();
	var selected_date = $("#saasappoint_appt_rs_date").val();
	var selected_startslot = $(this).val();
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	if(generalObj.endslot_status == "Y"){
		$.ajax({
			type: 'post',
			data: {
				'booking_datetime': datetime,
				'service_id': service_id,
				'selected_date': selected_date,
				'selected_startslot': selected_startslot,
				'get_endtimeslots': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$(".saasappoint_appt_rs_endtimeslot").html(res);
			}
		});
	}else{
		$.ajax({
			type: 'post',
			data: {
				'booking_datetime': datetime,
				'service_id': service_id,
				'selected_date': selected_date,
				'selected_startslot': selected_startslot,
				'get_without_endtimeslots': 1
			},
			url: ajaxurl + "saasappoint_appointment_detail_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$(".saasappoint_appt_rs_endtimeslot").val(res);
			}
		});
	}
});

/** Schedule Services modal detail JS **/
$(document).on('click', '.saasappoint-schedule-servicemodal', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $(this).data("id");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'schedule_service_modal_detail': 1
		},
		url: ajaxurl + "saasappoint_services_ajax.php",
		success: function (res) {
			$(".saasappoint-schedule-service-modal-body").html(res);
			$("#saasappoint-schedule-service-modal").modal("show");
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		}
	});
});

/** Schedule Services modal detail JS **/
$(document).on('click', '#saasappoint_update_service_schedule_btn', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var id = $("#saasappoint_schedule_service_id").val();
	var schedule_status = $("input[name='saasappoint_service_schedule_status\\[\\]']").map(function(){ if($(this).prop("checked")){ return "N";}else{ return "Y";} }).get();
	var starttime = $("select[name='saasappoint_service_starttime_dropdown\\[\\]']").map(function(){return $(this).val();}).get();
	var endtime = $("select[name='saasappoint_service_endtime_dropdown\\[\\]']").map(function(){return $(this).val();}).get();
	var no_of_booking = $("input[name='saasappoint_service_booking\\[\\]']").map(function(){return $(this).val();}).get();
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'schedule_status': schedule_status,
			'starttime': starttime,
			'endtime': endtime,
			'no_of_booking': no_of_booking,
			'save_service_schedule': 1
		},
		url: ajaxurl + "saasappoint_services_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$("#saasappoint-schedule-service-modal").modal("hide");
			swal("Updated!", 'Service schedule updated successfully', "success");
		}
	});
});

/** Update Booking Form settings JS **/
$(document).on('click', '#update_bookingform_settings_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var uploaded_file = $("#saasappoint_bookingform_bg_image-hidden").val();
	var saasappoint_single_category_autotrigger_status = $("#saasappoint_single_category_autotrigger_status").val();
	var saasappoint_single_service_autotrigger_status = $("#saasappoint_single_service_autotrigger_status").val();
	var saasappoint_bookingform_bg = $("#saasappoint_bookingform_bg").val();
	var saasappoint_bookingform_bg_type = $("#saasappoint_bookingform_bg_type").val();
	var saasappoint_bookingform_bg_color = $("#saasappoint_bookingform_bg_color").val();
	if(saasappoint_bookingform_bg_type == "image" && uploaded_file == "" && saasappoint_bookingform_bg != "default"){
		swal("Opps!", 'Please upload background image', "error");
	}else{
		$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
		$.ajax({
			type: 'post',
			data: {
				'saasappoint_single_category_autotrigger_status': saasappoint_single_category_autotrigger_status,
				'saasappoint_single_service_autotrigger_status': saasappoint_single_service_autotrigger_status,
				'saasappoint_bookingform_bg': saasappoint_bookingform_bg,
				'saasappoint_bookingform_bg_type': saasappoint_bookingform_bg_type,
				'saasappoint_bookingform_bg_color': saasappoint_bookingform_bg_color,
				'uploaded_file': uploaded_file,
				'update_bookingform_settings': 1
			},
			url: ajaxurl + "saasappoint_settings_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				swal("Updated!", 'Booking Form settings updated successfully', "success");
			}
		});
	}
});

/** Update Welcome Message settings JS **/
$(document).on('click', '#update_welcome_message_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var saasappoint_welcome_message_container = $("#saasappoint_welcome_message_container").summernote('code');
	var saasappoint_welcome_message_status = $("#saasappoint_welcome_message_status").val();
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'saasappoint_welcome_message_container': saasappoint_welcome_message_container,
			'saasappoint_welcome_message_status': saasappoint_welcome_message_status,
			'update_welcome_message_settings': 1
		},
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal("Updated!", 'Welcome Message updated successfully', "success");
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
		url: ajaxurl + "saasappoint_settings_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			swal(status_text+"!", 'Pay at Venue payment status successfully', "success");
		}
	});
});

/** Change pay at venue payment status JS **/
$(document).on('change', '#saasappoint_langauges', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var lang = $(this).val();
	if(lang != "none"){
		$.ajax({
			type: 'post',
			data: {
				'lang': lang,
				'get_selected_lang_labels': 1
			},
			url: ajaxurl + "saasappoint_languages_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$(".saasappoint_languages_container").html(res);
			}
		});
	}else{
		$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
		$(".saasappoint_languages_container").html("");
	}
});

/** Save language translation JS **/
$(document).on('click', '#save_language_translation', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var lang = $('#saasappoint_langauges').val();
	var lang_array = {};
    
	$(".selected_language_inputs").each(function(){
		lang_array[$(this).attr('id')] =  $(this).val();
	});
	$.ajax({
		type: 'post',
		data: {
			'lang': lang,
			'lang_array': lang_array,
			'save_selected_lang_labels': 1
		},
		url: ajaxurl + "saasappoint_languages_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			if(res != "notexist"){
				swal("Translated!", 'Language translation saved successfully', "success");
				location.reload();
			}else{
				swal("Opps!", "Something went wrong. Please try again.", "error");
			}
		}
	});
});

/** Delete coupon JS **/
$(document).on('click', '.saasappoint_remove_language', function(){
	var ajaxurl = generalObj.ajax_url;
	var lang = $(this).data('lang');
	swal({
	  title: "Are you sure?",
	  text: "you want to delete this language",
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
				'lang': lang,
				'delete_lang': 1
			},
			url: ajaxurl + "saasappoint_languages_ajax.php",
			success: function (res) {
				$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
				$(".saasappoint_remove_language_"+lang).remove();
				swal("Deleted!", "Language deleted successfully.", "success");
			}
		});
	});
});

$(document).on('click', '.saasappoint_edit_language', function(){
	$(".saasappoint_main_loader").removeClass("saasappoint_hide_loader");
	var ajaxurl = generalObj.ajax_url;
	var lang = $(this).data('lang');
	$.ajax({
		type: 'post',
		data: {
			'lang': lang,
			'get_selected_lang_labels': 1
		},
		url: ajaxurl + "saasappoint_languages_ajax.php",
		success: function (res) {
			$(".saasappoint_main_loader").addClass("saasappoint_hide_loader");
			$(".saasappoint_languages_container").html(res);
		}
	});
});