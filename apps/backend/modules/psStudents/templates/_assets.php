<?php use_helper('I18N', 'Number')?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
<style>
#ps-filter .has-error {
	/* To make the feedback icon visible */
	z-index: 9999;
	color: #b94a48;
}

.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
tr.info th{color: #333;line-height: 35px!important;}
</style>

<script type="text/javascript">
	var msg_file_invalid = '<?php echo __('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array('%value%' => $app_upload_max_size))?>';
	var PsMaxSizeFile 	 = '<?php echo $app_upload_max_size;?>';

$(document).ready(function() {
	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_student_code a ,.sf_admin_list_td_first_name a, .sf_admin_list_td_last_name a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});
	
	$('.btn-delete-relative').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-relative').attr('action', '<?php echo url_for('@ps_relative_student')?>/' + item_id);
	});
	
	$('.btn-delete-service').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-service').attr('action', '<?php echo url_for('@ps_student_service')?>/' + item_id);
	});
	
	$('.btn-delete-class').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-class').attr('action', '<?php echo url_for('@ps_student_class')?>/' + item_id);
	});

	$('.btn-restore-service').click(function() {
		var item_id = $(this).attr('data-item');
		$('#restore_id').val(item_id);
		
		var text = $('#row-' + item_id ).html();
		$('#show-text').html(text);
		
		$('#ps-form-restore-service').attr('action', '<?php echo url_for('@ps_student_service_restore')?>');
	});
	
	// BEGIN: filters
	$('#student_filters_ps_customer_id').change(function() {

		resetOptions('student_filters_ps_workplace_id');
		$('#student_filters_ps_workplace_id').select2('val','');
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		if ($(this).val() > 0) {

			$("#student_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#student_filters_ps_workplace_id').select2('val','');

				$("#student_filters_ps_workplace_id").html(msg);

				$("#student_filters_ps_workplace_id").attr('disabled', null);

				$("#student_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params2') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#student_filters_ps_class_id').select2('val','');
					$("#student_filters_ps_class_id").html(msg);
					$("#student_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#student_filters_ps_workplace_id').change(function() {
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		
		if ($('#student_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params2') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filters_ps_class_id').select2('val','');
			$("#student_filters_ps_class_id").html(msg);
			$("#student_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#student_filters_school_year_id').change(function() {
		
		resetOptions('student_filters_ps_class_id');
		$('#student_filters_ps_class_id').select2('val','');
		
		if ($('#student_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params2') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filters_ps_customer_id').val() + '&w_id=' + $('#student_filters_ps_workplace_id').val() + '&y_id=' + $('#student_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filters_ps_class_id').select2('val','');
			$("#student_filters_ps_class_id").html(msg);
			$("#student_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters

	$('#student_ps_customer_id').change(function() {

		resetOptions('student_ps_workplace_id');
	    $('#student_ps_workplace_id').select2('val','');
	    $("#student_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#student_ps_workplace_id').show();
	    		$('#student_ps_workplace_id').html(data);
	    		$("#student_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

	$('#btn-export-class').click(function() {

		if ($('#student_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('You can select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_export_by_class') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	$('#btn-export-relavtive-class').click(function() {

		if ($('#student_filters_ps_class_id').val() <= 0) {
			alert('<?php echo __('You can select class')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_export_by_class_with_relatives') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

    

	$('#btn-export-workplace').click(function() {

		if ($('#student_filters_ps_workplace_id').val() <=0) {
			alert('<?php echo __('You can select workplace')?>');
			return false;
		}

		//Get action hien tai
		$action = $('#ps-filter').attr('action');
		
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_export_by_workplace') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	$('#btn-export-relavtive-workplace').click(function() {

		if ($('#student_filters_ps_workplace_id').val() <=0) {
			alert('<?php echo __('You can select workplace')?>');
			return false;
		}

		//Get action hien tai.
		$action = $('#ps-filter').attr('action');
		
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_export_by_workplace_with_relatives') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	$('#btn-export-student-statistic-workplace').click(function() {

		if ($('#student_filters_ps_workplace_id').val() <=0) {
			alert('<?php echo __('You can select workplace')?>');
			return false;
		}

		//Get action hien tai.
		$action = $('#ps-filter').attr('action');
		
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_statistic_by_workplace_export') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	$('#btn-export-student-statistic-customer').click(function() {

		if ($('#student_filters_ps_customer_id').val() <=0) {
			alert('<?php echo __('You can select customer')?>');
			return false;
		}

		//Get action hien tai.
		$action = $('#ps-filter').attr('action');
		
		$('#ps-filter').attr('action', '<?php echo url_for('@ps_students_statistic_by_customer_export') ?>');
		$('#ps-filter').submit();
		
		//Tra lai action ban dau
		$('#ps-filter').attr('action', $action);
		return true;
    });

	$('#student_start_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
	});
    
});
</script>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('psStudents/box_modal_confirm_remover_relative');?>
<?php include_partial('psStudents/box_modal_confirm_remover_service');?>
<?php include_partial('psStudents/box_modal_confirm_remover_class');?>
<?php include_partial('psStudents/box_modal_confirm_restore_service');?>