<?php include_partial('global/include/_box_modal_messages');?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}
}
</style>
<script>
	// msg
	var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
	var msg_select_ps_workplace_id	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id 		= '<?php echo __('Please select class to to filter the data.')?>';
	var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';
	var msg_select_date 			= '<?php echo __('Please enter dates to filter the data.')?>';

	var msg_select_student 			= '<?php echo __('Please select students from the list of parties to enter data.')?>';
	var msg_select_login_relative 	= '<?php echo __('Please select relatives to enter data.')?>';
	var msg_select_teacher_received = '<?php echo __('Please select a teacher to pick up your child.')?>';
	
</script>

<script>
$(document).ready(function() {

	// $('#select-all').click(function(event) {
	//   if (this.checked) {
	// 	$(':checkbox').prop('checked', true);
	//   } else {
	// 	$(':checkbox').prop('checked', false);
	//   }
	// });
	
	// luu diem danh den
	$('.btn-attendance').click(function() {
	
		var student_id = $(this).attr('data-value');
		
		var service_code = $('#service_code_' + student_id).val();

		var log_code = $('#select_' + student_id + '_log_code').val();
		
		var relative = $('#select_' + student_id+'_relative_login').val();

		var member = $('#select_'+ student_id +'_member_login').val();
		
		var login_at = $('#login_at_' + student_id).val();

		var note = $('#note_' + student_id).val();

		var class_id = $('#ps_logtimes_filters_ps_class_id').val();

		var ps_workplace_id = $('#ps_logtimes_filters_ps_workplace_id').val();

		var customer_id = $('#ps_logtimes_filters_ps_customer_id').val();
		
		var date_at = $('#ps_logtimes_filters_date_time').val();

		var status = $("input[id='radiobox-"+student_id+"']:checked").val();

		var config = $("#config_choose_attendances_relative").val();

		var favorite = [];
            $.each($("input[name='student_logtime["+student_id+"][student_service][]']:checked"), function(){            
                favorite.push($(this).val());
            });

    	var str_service = favorite.join(",");
        
		if(config == 1 && status == 1){
    		if (student_id <= 0 || login_at == '' || relative <= 0 || member <= 0) {
    			alert('<?php echo __("Unknow relative")?>');
    			return false;
    		}
		}

		var url = 'student_id=' + student_id + '&relative=' + relative + '&member=' + member + '&login_at=' + login_at + '&note=' + note + '&service=' + str_service + '&date_at=' + date_at + '&class_id=' + class_id + '&ps_workplace_id=' + ps_workplace_id + '&status=' + status + '&log_code=' + log_code + '&service_code=' + service_code + '&config=' + config + '&customer_id=' +customer_id;

		// $('#ic-loading-' + student_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_attendance_save_login') ?>',
	        type: 'POST',
	        data: 'student_id=' + student_id + '&relative=' + relative + '&member=' + member + '&login_at=' + login_at + '&note=' + note + '&service=' + str_service + '&date_at=' + date_at + '&class_id=' + class_id + '&ps_workplace_id=' + ps_workplace_id + '&status=' + status + '&log_code=' +log_code + '&service_code=' + service_code + '&config=' + config + '&customer_id=' + customer_id,
	        success: function(data) {
	        	// $('#ic-loading-' + student_id).hide();
	        	// $('#box-' + student_id).html(data);
	        	window.location.reload();
	        	// alert(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	            $('#ic-loading-' + student_id).hide();
	        },
		});
	    
  	});

	
	// luu diem danh ve
	$('.btn-attendance-logout').click(function() {
		
		var student_id = $(this).attr('data-value');
		
		var relative = $('#select_relative_' + student_id).val();

		var member = $('#select_'+ student_id +'_member_logout').val();
		
		var logout_at = $('#logout_at_' + student_id).val();

		var note = $('#note_' + student_id).val();

		var class_id = $('#ps_logtimes_filters_ps_class_id').val();

		var ps_workplace_id = $('#ps_logtimes_filters_ps_workplace_id').val();
		
		var date_at = $('#ps_logtimes_filters_date_time').val();

		var config = $("#config_choose_attendances_relative").val();
		
        //alert("Data : " + class_id);

        if(config == 1){
			if (student_id <= 0 || logout_at == '' || relative <= 0) {
				alert('<?php echo __("Unknow relative")?>');
				return false;
			}
        }
        
		$('#ic-loading-' + student_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_attendance_save_logout') ?>',
	        type: 'POST',
	        data: 'student_id=' + student_id + '&relative=' + relative + '&member=' + member + '&logout_at=' + logout_at + '&note=' + note + '&date_at=' + date_at + '&class_id=' + class_id + '&ps_workplace_id=' + ps_workplace_id,
	        success: function(data) {
	        	$('#ic-loading-' + student_id).hide();
	        	$('#logout-' + student_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
  	});


	
	// BEGIN: filters
	$('#ps_logtimes_filters_ps_customer_id').change(function() {
	
		resetOptions('ps_logtimes_filters_ps_workplace_id');
		$('#ps_logtimes_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_logtimes_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#ps_logtimes_filters_ps_workplace_id').select2('val','');

				$("#ps_logtimes_filters_ps_workplace_id").html(msg);

				$("#ps_logtimes_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
					$("#ps_logtimes_filters_ps_class_id").html(msg);
					$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#ps_logtimes_filters_ps_workplace_id').change(function() {
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($('#ps_logtimes_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
			$("#ps_logtimes_filters_ps_class_id").html(msg);
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#ps_logtimes_filters_school_year_id').change(function() {
		
		resetOptions('ps_logtimes_filters_ps_class_id');
		$('#ps_logtimes_filters_ps_class_id').select2('val','');
		
		if ($('#ps_logtimes_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#ps_logtimes_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_logtimes_filters_ps_customer_id').val() + '&w_id=' + $('#ps_logtimes_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_logtimes_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_logtimes_filters_ps_class_id').select2('val','');
			$("#ps_logtimes_filters_ps_class_id").html(msg);
			$("#ps_logtimes_filters_ps_class_id").attr('disabled', null);
	    });
	});
// 	$('.time_picker').timepicker({
// 		timeFormat : 'HH:mm',
// 		showMeridian : false,
// 		defaultTime : null
// 	});
	// END: filters
	

//BEGIN: filters_synthetic
$('#delay_filter_ps_customer_id').change(function() {

	resetOptions('delay_filter_ps_workplace_id');
	
	$('#delay_filter_ps_workplace_id').select2('val','');
	
	if ($(this).val() > 0) {

		$("#delay_filter_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#delay_filter_ps_workplace_id').select2('val','');

			$('#delay_filter_ps_workplace_id').html(msg);

			$('#delay_filter_ps_workplace_id').attr('disabled', null);

	    });
	}		
});

$('#delay_filter_date_at').datepicker({
	dateFormat : 'dd-mm-yy',
	maxDate : new Date(),
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	changeMonth : true,
	changeYear : true,
})

// .on('change', function(e) {
// 	$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
// });
	
	$('#ps_logtimes_filters_tracked_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		maxDate : new Date(),
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})
    	
    	.on('change', function(e) {
    		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    	});
	
	// filter statistic
	
	$('#logtimes_filter_ps_school_year_id').change(function() {

		resetOptions('logtimes_filter_year_month');
		$('#logtimes_filter_year_month').select2('val','');
		if ($(this).val() > 0) {
				
		$("#logtimes_filter_year_month").attr('disabled', 'disabled');
		$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'ym_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
		    	$('#logtimes_filter_year_month').select2('val','');
				$("#logtimes_filter_year_month").html(msg);
				$("#logtimes_filter_year_month").attr('disabled', null);
		    });
		}
	});
	
	$('#logtimes_filter_ps_customer_id').change(function() {

		resetOptions('logtimes_filter_ps_workplace_id');
		$('#logtimes_filter_ps_workplace_id').select2('val','');
		$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('logtimes_filter_ps_department_id');
		$('#logtimes_filter_ps_department_id').select2('val','');
		$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#logtimes_filter_ps_workplace_id').select2('val','');

			$("#logtimes_filter_ps_workplace_id").html(msg);

			$("#logtimes_filter_ps_workplace_id").attr('disabled', null);

			$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');

	    });

		$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_department_workplace') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#logtimes_filter_ps_department_id').select2('val','');
			$("#logtimes_filter_ps_department_id").html(msg);
			$("#logtimes_filter_ps_department_id").attr('disabled', null);
		});
	}		
});
 
	$('#logtimes_filter_ps_workplace_id').change(function() {
	    
    	resetOptions('logtimes_filter_ps_department_id');
    	$('#logtimes_filter_ps_department_id').select2('val','');
    	
    	if ($(this).val() > 0) {
    
    		$("#logtimes_filter_ps_department_id").attr('disabled', 'disabled');
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_department_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#logtimes_filter_ps_department_id').select2('val','');
    			$("#logtimes_filter_ps_department_id").html(msg);
    			$("#logtimes_filter_ps_department_id").attr('disabled', null);
    		});
    		
    	}		
    });

});


// diem danh co nhieu trang thai
function setLogtime2(student_id,ele) {

	 if (ele.value!=0) {
		
		$('#select_'+ student_id +'_relative_login').attr('disabled', false);
		$('#select_'+ student_id +'_relative_logout').attr('disabled', false);
		$('#select_'+ student_id +'_relative_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_relative_logout').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_login').attr('disabled', false);
		$('#select_'+ student_id +'_member_logout').attr('disabled', false);
		$('#select_'+ student_id +'_member_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_member_logout').prop("selectedIndex", 0);
		$('.input-sm_'+ student_id +'_login').attr('disabled', false);
		$('.input-sm_'+ student_id +'_logout').attr('disabled', false);
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);		
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', true);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);

		$('#note_'+ student_id).attr('disabled', false);
		$('#service_code_'+ student_id).attr('disabled', false);

// 		$('#btn-attendance_'+ student_id).attr('disabled', false);
		
	} else {
		

		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', false);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');		

		$('#select_'+ student_id +'_relative_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_relative_logout').attr('disabled', 'disabled');	
		$('#select_'+ student_id +'_member_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_member_logout').attr('disabled', 'disabled');
		$('.input-sm_'+ student_id +'_login').attr('disabled', 'disabled');		
		$('.input-sm_'+ student_id +'_logout').attr('disabled', 'disabled');	

		$('#note_'+ student_id).attr('disabled', 'disabled');
		$('#service_code_'+ student_id).attr('disabled', 'disabled');


// 		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');
		
	}

	 $('#sf_admin_list_th_td_attendance').click(function() {
			
		var boxes = document.getElementsByTagName('input');
	
		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];			
			if (box.type == 'checkbox' && box.item_name == 'attendance[]')
				box.checked = $(this).is(":checked");
		}
	
		return true;
	});	
}// end function  setLogtime


// diem danh co 1 trang thai
function setLogtime(student_id,ele) {
	
	 if (ele.checked) {

		$('#select_'+ student_id +'_relative_login').attr('disabled', false);
		$('#select_'+ student_id +'_relative_logout').attr('disabled', false);
		$('#select_'+ student_id +'_relative_login').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_relative_logout').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_login').attr('disabled', false);
		$('#select_'+ student_id +'_member_logout').attr('disabled', false);
		$('#select_'+ student_id +'_member_login').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_logout').prop("selectedIndex", 0);
		$('.input-sm_'+ student_id +'_login').attr('disabled', false);
		$('.input-sm_'+ student_id +'_logout').attr('disabled', false);
		$('#note_'+ student_id).attr('disabled', false);
		$('.relative_class_'+ student_id).prop('checked',true);
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);		
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', true);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);

		$('#btn-attendance_'+ student_id).attr('disabled', false);
		
	} else {
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', false);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');		

		$('#select_'+ student_id +'_relative_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_relative_logout').attr('disabled', 'disabled');	
		$('#select_'+ student_id +'_member_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_member_logout').attr('disabled', 'disabled');
		$('.input-sm_'+ student_id +'_login').attr('disabled', 'disabled');		
		$('.input-sm_'+ student_id +'_logout').attr('disabled', 'disabled');
		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');	
		$('#note_'+ student_id).attr('disabled', 'disabled');	
		$('.relative_class_'+ student_id).prop('checked',false);

		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');
	}

	 $('#sf_admin_list_th_td_attendance').click(function() {
			
		var boxes = document.getElementsByTagName('input');
	
		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];			
			if (box.type == 'checkbox' && box.item_name == 'attendance[]')
				box.checked = $(this).is(":checked");
		}
	
		return true;
	});	
}// end function  setLogtime

//diem danh so diem danh
function setLogtime3(student_id,ele) {
	
	 if (ele.checked) {

		$('#select_'+ student_id +'_relative_login').attr('disabled', false);
		$('#select_'+ student_id +'_relative_logout').attr('disabled', false);
		$('#select_'+ student_id +'_relative_login').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_relative_logout').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_login').attr('disabled', false);
		$('#select_'+ student_id +'_member_logout').attr('disabled', false);
		$('#select_'+ student_id +'_member_login').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_logout').prop("selectedIndex", 0);
		$('.input-sm_'+ student_id +'_login').attr('disabled', false);
		$('.input-sm_'+ student_id +'_logout').attr('disabled', false);
		$('#note_'+ student_id).attr('disabled', false);
		$('.relative_class_'+ student_id).prop('checked',true);

		$('.radiobox-'+ student_id).attr('disabled', false);
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);		
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', true);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);

		$('#btn-attendance_'+ student_id).attr('disabled', false);
		
	} else {
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', false);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');		

		$('#select_'+ student_id +'_relative_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_relative_logout').attr('disabled', 'disabled');	
		$('#select_'+ student_id +'_member_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_member_logout').attr('disabled', 'disabled');
		$('.input-sm_'+ student_id +'_login').attr('disabled', 'disabled');		
		$('.input-sm_'+ student_id +'_logout').attr('disabled', 'disabled');
		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');	
		$('#note_'+ student_id).attr('disabled', 'disabled');
		$('.radiobox-'+ student_id).attr('disabled', 'disabled');
		$('.relative_class_'+ student_id).prop('checked',false);

		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');
	}

	 $('#sf_admin_list_th_td_attendance').click(function() {
			
		var boxes = document.getElementsByTagName('input');
	
		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];			
			if (box.type == 'checkbox' && box.item_name == 'attendance[]')
				box.checked = $(this).is(":checked");
		}
	
		return true;
	});	
}// end function  setLogtime

</script>

<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#ps-filter').formValidation({
    	framework : 'bootstrap',
    	addOns : {
			i18n : {}
		},
		err : {
			container: '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
    	fields : {
			"ps_logtimes_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "ps_logtimes_filters[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_school_year,
                        		  en_US: msg_select_school_year
                        }
                    }
                }
            },
            
            "ps_logtimes_filters[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "ps_logtimes_filters[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    },
                }
            },

		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps-filter').formValidation('setLocale', PS_CULTURE);

});
</script>