<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/field_custom/_ps_assets')?>
<?php use_helper('I18N', 'Number')?>
<script type="text/javascript">

$(document).ready(function() {
	
	$('#ps_evaluate_index_student_filters_school_year_id').change(function() {

		resetOptions('ps_evaluate_index_student_filters_ps_class_id');
		$('#ps_evaluate_index_student_filters_ps_class_id').select2('val','');

		resetOptions('ps_evaluate_index_student_filters_ps_month');
		$('#ps_evaluate_index_student_filters_ps_month').select2('val','');
		
		if($(this).val() <= 0){
			return;
		}

		$("#ps_evaluate_index_student_filters_ps_month").attr('disabled', 'disabled');

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
		    	$('#ps_evaluate_index_student_filters_ps_month').select2('val','');
				$("#ps_evaluate_index_student_filters_ps_month").html(msg);
				$("#ps_evaluate_index_student_filters_ps_month").attr('disabled', null);
		    });
	    
		$("#ps_evaluate_index_student_filters_ps_workplace_id").trigger('change');
	});
	
	//customer
	$('#ps_evaluate_index_student_filters_ps_customer_id').change(function() {

		resetOptions('ps_evaluate_index_student_filters_ps_workplace_id');
		$('#ps_evaluate_index_student_filters_ps_workplace_id').select2('val','');

		resetOptions('ps_evaluate_index_student_filters_ps_class_id');
		$('#ps_evaluate_index_student_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_evaluate_index_student_filters_ps_workplace_id").attr('disabled', 'disabled');
			//$("#ps_evaluate_index_student_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_evaluate_index_student_filters_ps_workplace_id').select2('val','');

				$("#ps_evaluate_index_student_filters_ps_workplace_id").html(msg);

				$("#ps_evaluate_index_student_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_evaluate_index_student_filters_ps_workplace_id").trigger('change');

		    });
		}
	
	});
	//end-customer

	//workplace
	$('#ps_evaluate_index_student_filters_ps_workplace_id').change(function() {

		resetOptions('ps_evaluate_index_student_filters_ps_class_id');
		$('#ps_evaluate_index_student_filters_ps_class_id').select2('val','');

// 		resetOptions('ps_evaluate_index_student_filters_evaluate_subject_id');
// 		$('#ps_evaluate_index_student_filters_evaluate_subject_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

// 		$("#ps_evaluate_index_student_filters_evaluate_subject_id").attr('disabled', 'disabled');
		
		$("#ps_evaluate_index_student_filters_ps_class_id").attr('disabled', 'disabled');

			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_evaluate_index_student_filters_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_index_student_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_index_student_filters_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_evaluate_index_student_filters_ps_class_id').select2('val','');
				$("#ps_evaluate_index_student_filters_ps_class_id").html(msg);
				$("#ps_evaluate_index_student_filters_ps_class_id").attr('disabled', null);
		    });

		
// 		$.ajax({
//            url: '<?php //echo url_for('@ps_evaluate_subject_by_params') ?>',
//             type: "POST",
//             data: 'c_id = ' + $('#ps_evaluate_index_student_filters_ps_customer_id').val() + '&w_id=' + $("#ps_evaluate_index_student_filters_ps_workplace_id").val() ,
//             processResults: function (data, page) {
//                 return {
//                   results: data.items  
//                 };
//             },
//         }).done(function(msg) {
//         	$('#ps_evaluate_index_student_filters_evaluate_subject_id').select2('val','');
//             $("#ps_evaluate_index_student_filters_evaluate_subject_id").html(msg);
//             $("#ps_evaluate_index_student_filters_evaluate_subject_id").attr('disabled', null);
//         });
				
	});
	//end-workplace
	//END: filter

	//Statistic
	$('#evaluate_filter_ps_school_year_id').change(function() {

		resetOptions('evaluate_filter_year_month');
		$('#evaluate_filter_year_month').select2('val','');
		if ($(this).val() > 0) {
				
		$("#evaluate_filter_year_month").attr('disabled', 'disabled');
		$("#ps_evaluate_filters_ps_class_id").attr('disabled', 'disabled');

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
		    	$('#evaluate_filter_year_month').select2('val','');
				$("#evaluate_filter_year_month").html(msg);
				$("#evaluate_filter_year_month").attr('disabled', null);
		    });
		}
	});

	$('#evaluate_filter_ps_customer_id').change(function() {

		resetOptions('evaluate_filter_ps_workplace_id');
		$('#evaluate_filter_ps_workplace_id').select2('val','');
		$("#evaluate_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('evaluate_filter_class_id');
		$('#evaluate_filter_class_id').select2('val','');
		$("#evaluate_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#evaluate_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#evaluate_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#evaluate_filter_ps_workplace_id').select2('val','');

			$("#evaluate_filter_ps_workplace_id").html(msg);

			$("#evaluate_filter_ps_workplace_id").attr('disabled', null);

			$("#evaluate_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
    });
     
    $('#evaluate_filter_ps_workplace_id').change(function() {
    	
    	$("#evaluate_filter_class_id").attr('disabled', 'disabled');
    	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#evaluate_filter_ps_customer_id').val() + '&w_id=' + $('#evaluate_filter_ps_workplace_id').val() + '&y_id=' + $('#evaluate_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#evaluate_filter_class_id').select2('val','');
    		$("#evaluate_filter_class_id").html(msg);
    		$("#evaluate_filter_class_id").attr('disabled', null);
        });
    });
    
    $('#evaluate_filter_ps_school_year_id').change(function() {
    	
    	resetOptions('evaluate_filter_class_id');
    	$('#evaluate_filter_class_id').select2('val','');
    	
    	if ($('#evaluate_filter_ps_customer_id').val() <= 0) {
    		return;
    	}
    
    	$("#evaluate_filter_class_id").attr('disabled', 'disabled');
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#evaluate_filter_ps_customer_id').val() + '&w_id=' + $('#evaluate_filter_ps_workplace_id').val() + '&y_id=' + $('#evaluate_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#evaluate_filter_class_id').select2('val','');
    		$("#evaluate_filter_class_id").html(msg);
    		$("#evaluate_filter_class_id").attr('disabled', null);
        });
    });
	//END Statistic
	
	//Form
	<?php $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();?>
	
	$('#ps_evaluate_index_student_ps_customer_id').change(function() {

		resetOptions('ps_evaluate_index_student_ps_workplace_id');
		$('#ps_evaluate_index_student_ps_workplace_id').select2('val','');

		if ($(this).val() > 0) {

			$("#ps_evaluate_index_student_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_evaluate_index_student_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_evaluate_index_student_ps_workplace_id').select2('val','');

				$("#ps_evaluate_index_student_ps_workplace_id").html(msg);

				$("#ps_evaluate_index_student_ps_workplace_id").attr('disabled', null);

				$("#ps_evaluate_index_student_ps_workplace_id").trigger('change');

		    });
		}
	
	});
	
	$('#ps_evaluate_index_student_ps_workplace_id').change(function() {

		resetOptions('ps_evaluate_index_student_ps_class_id');
		$('#ps_evaluate_index_student_ps_class_id').select2('val','');

		resetOptions('ps_evaluate_index_student_evaluate_subject_id');
		$('#ps_evaluate_index_student_evaluate_subject_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_evaluate_index_student_evaluate_subject_id").attr('disabled', 'disabled');
		
		$("#ps_evaluate_index_student_ps_class_id").attr('disabled', 'disabled');

			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_evaluate_index_student_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_index_student_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id; ?>,
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_evaluate_index_student_ps_class_id').select2('val','');
				$("#ps_evaluate_index_student_ps_class_id").html(msg);
				$("#ps_evaluate_index_student_ps_class_id").attr('disabled', null);
		    });

		
		$.ajax({
            url: '<?php echo url_for('@ps_evaluate_subject_by_params') ?>',
            type: "POST",
            data: 'c_id = ' + $('#ps_evaluate_index_student_ps_customer_id').val() + '&w_id=' + $("#ps_evaluate_index_student_ps_workplace_id").val() ,
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#ps_evaluate_index_student_evaluate_subject_id').select2('val','');
            $("#ps_evaluate_index_student_evaluate_subject_id").html(msg);
            $("#ps_evaluate_index_student_evaluate_subject_id").attr('disabled', null);
        });
				
	});
	//end-workplace
	//END: Form

	//Save data
	$('.btn-save-evaluate').click(function(){
		var date = <?php echo date('d')?> +'-' + $(this).attr('data-date');
		var criteria = $(this).attr('data-criteria-id');
		var is_publish = ($('#is_publish').is(':checked')) ? 1 : 0;
		var is_awaiting = ($('#is_awaiting').is(':checked')) ? 1 : 0;

		
		//String: Danh sach hoc sinh theo tung nhom tieu chi
		var student_list = $(this).attr('data-student-list');
		//Mang chi so tung cell de xac dinh vi tri hoc sinh theo tung chi so
		var cell = ($(this).attr('data-cell')).split(',');
		
		var i;
		var data = [];
		for( i = 0; i < cell.length; i ++) {
			var symbol_id  = $('#criteria-' + criteria + '-cell-' + cell[i]).val();
			if(symbol_id > 0){
				data[i] = symbol_id;
			} else {
				data[i] = 0;
			}
			
		}
		var symbol_list = data.join(",");
		
		$.ajax({
	        url: '<?php echo url_for('@ps_evaluate_index_student_save') ?>',
	        type: 'POST',
	        data: 'student_arr=' + student_list + '&symbol_arr=' + symbol_list + '&criteria_id=' + criteria + '&date=' + date + '&is_publish=' + is_publish + '&is_awaiting=' + is_awaiting,
	        success: function(data) {
// 		        alert(data);
		        location.reload();
	        },
	        error: function (request, error) {
	            alert(" <?php echo __ ('Can\'t do because: ') ?>" + error);
	        },
		});
		
	});

	//Save data
	$('.btn-save-state').click(function(){
		var date = <?php echo date('d')?> +'-' + $(this).attr('data-date');
		var is_publish = ($('#is_publish').is(':checked')) ? 1 : 0;
		var is_awaiting = ($('#is_awaiting').is(':checked')) ? 1 : 0;
		var class_id  = $(this).attr('data-class');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_evaluate_index_student_save_state_by_class') ?>',
	        type: 'POST',
	        data: 'class_id=' + class_id + '&date=' + date + '&is_publish=' + is_publish + '&is_awaiting=' + is_awaiting,
	        success: function(data) {
		        alert("<?php echo __('Success')?>");
	        },
	        error: function (request, error) {
	            alert(" <?php echo __ ('Can\'t do because: ') ?>" + error);
	        },
		});
		
	});
	//End save data
	
	
	$('#is_awaiting').change(function(){
		if($('#is_awaiting').is(':checked')){
			$('#is_publish').prop('checked', true);
		}
	});
});
</script>
<script>
//msg
var msg_select_ps_customer_id	= '<?php echo __('Please select School to filter the data.')?>';
var msg_select_ps_workplace_id	= '<?php echo __('Please select workplace to filter the data.')?>';
var msg_select_ps_class_id 		= '<?php echo __('Please select class to to filter the data.')?>';
var msg_select_school_year 		= '<?php echo __('Please select school year to to filter the data.')?>';
var msg_select_ps_month 		= '<?php echo __('Please select month to to filter the data.')?>';

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
			"ps_evaluate_index_student_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "ps_evaluate_index_student_filters[school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_school_year,
                        		  en_US: msg_select_school_year
                        }
                    }
                }
            },

            "ps_evaluate_index_student_filters[ps_month]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_month,
                        		  en_US: msg_select_ps_month
                        }
                    }
                }
            },
            
            "ps_evaluate_index_student_filters[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "ps_evaluate_index_student_filters[ps_class_id]": {
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