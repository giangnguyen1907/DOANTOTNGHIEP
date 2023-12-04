$(document).ready(function() {	
	
	if ($("#sf_guard_group_ps_province_id").val() <= 0) {		
		$("#sf_guard_group_ps_district_id").attr('disabled', 'disabled');		
	};
	
	if ($("#sf_guard_group_ps_district_id").val() <= 0) {		
		$("#sf_guard_group_ps_ward_id").attr('disabled', 'disabled');		
	} else {
		$("#sf_guard_group_ps_ward_id").attr('disabled', null);
	}
	
	$("#sf_guard_group_ps_district_id").on('change', function(e) {
		
		if ($("#sf_guard_group_ps_district_id").val() <= 0) {		
			
			$("#sf_guard_group_ps_ward_id").attr('disabled', 'disabled');
			
		} else {
			
			$("#sf_guard_group_ps_ward_id").attr('disabled', null);
		}
  	});
	
	if ($("#sf_guard_group_ps_ward_id").val() <= 0) {		
		$("#sf_guard_group_ps_customer_id").attr('disabled', 'disabled');		
	};
	
	$("#sf_guard_group_ps_ward_id").on('change', function(e) {
		if ($("#sf_guard_group_ps_ward_id").val() <= 0) {		
			$("#sf_guard_group_ps_customer_id").attr('disabled', 'disabled');		
		} else {
			$("#sf_guard_group_ps_customer_id").attr('disabled', null);
		}
  	});
	
	if ($("#sf_guard_group_ps_customer_id").val() <= 0) {		
		$("#sf_guard_group_users_list").attr('disabled', 'disabled');		
	};
	
	$('#ps-form')
		.formValidation({
			framework: 'bootstrap',
			excluded: [':disabled'],
	          addOns: {
	               i18n: {}
	        },
	        errorElement: "div",
	        errorClass: "help-block with-errors",
	        message: {vi_VN: 'This value is not valid'},
	        icon: {
	        	
	        },		  
			fields: {}
    });
	
    $('#ps-form').formValidation('setLocale', PS_CULTURE);    
});