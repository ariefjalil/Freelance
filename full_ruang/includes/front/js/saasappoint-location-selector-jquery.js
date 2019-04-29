/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/

/** Prevent enter key stroke on form inputs **/
$(document).on("keydown", '.saasappoint input', function (e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
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
					swal("Available! Our service available at your location.", "", "success");
					window.location.href = siteurl;
				}else{
					swal("Opps! We are not available at your location.", "", "error");
				}
			}
		});
	}else{
		swal("Please enter valid zipcode.", "", "error");
	}
});