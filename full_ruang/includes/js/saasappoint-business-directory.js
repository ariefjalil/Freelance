/*
* SaasAppoint
* Online Multi Business Appointment Scheduling & Reservation Booking Calendar
*/

/** Initialization on ready state JS **/
$(document).ready( function(){
	var ajaxurl = generalObj.ajax_url;
	var keyword = $("#saasappoint_pagination_search_keyword").val();
	var rowcount = $("#saasappoint_pagination_rowcount").val();
	$.ajax({
		url: ajaxurl+"saasappoint_business_directory_ajax.php",
		type: "POST",
		data:  {"rowcount":rowcount,"keyword": ""},
		success: function(data){ $("#saasappoint_get_all_business_list").html(data); }
	});
});

/** Click event for pagination with search**/
$(document).on("click", "#saasappoint_reset_business_btn", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var keyword = $("#saasappoint_pagination_search_keyword").val();
	var rowcount = $("#saasappoint_pagination_rowcount").val();
	$.ajax({
		url: ajaxurl+"saasappoint_business_directory_ajax.php",
		type: "POST",
		data:  {"rowcount":rowcount,"keyword": ""},
		success: function(data){ $("#saasappoint_get_all_business_list").html(data);$("#saasappoint_pagination_search_keyword").val(""); }
	});
});

/** Click event for pagination with search**/
$(document).on("click", "#saasappoint_search_business_btn", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var keyword = $("#saasappoint_pagination_search_keyword").val();
	var rowcount = $("#saasappoint_pagination_rowcount").val();
	if(keyword != ""){
		$("#saasappoint_get_all_business_list").html('<div class="saasappoint-bsearch-result-heading "> <h3>Please wait while processing...</h3> </div>');
		$.ajax({
			url: ajaxurl+"saasappoint_business_directory_ajax.php",
			type: "POST",
			data:  {"rowcount":rowcount,"keyword":keyword},
			success: function(data){ $("#saasappoint_get_all_business_list").html(data); }
		});
	}
});

/** Click event for pagination **/
$(document).on("click", ".saasappoint_perpage_pagination_link", function(e){
	e.preventDefault();
	var ajaxurl = generalObj.ajax_url;
	var keyword = $("#saasappoint_pagination_search_keyword").val();
	var rowcount = $("#saasappoint_pagination_rowcount").val();
	var pageid = $(this).data("id");
	if(pageid>0){
		$("#saasappoint_get_all_business_list").html('<div class="saasappoint-bsearch-result-heading "> <h3>Please wait while processing...</h3> </div>');
		$.ajax({
			url: ajaxurl+"saasappoint_business_directory_ajax.php",
			type: "POST",
			data:  {"rowcount":rowcount,"keyword":keyword,"pageid":pageid},
			success: function(data){ $("#saasappoint_get_all_business_list").html(data); }
		});
	}
});

$(document).on("keypress", '#saasappoint_pagination_search_keyword', function(e) {
	var key = e.which;
	if(key == 13){
		$('#saasappoint_search_business_btn').trigger('click');
	}
});   