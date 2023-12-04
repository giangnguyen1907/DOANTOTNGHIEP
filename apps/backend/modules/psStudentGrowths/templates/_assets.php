<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal');?>

<style>
.guithongbao{margin-top: 10px;}
.push_notication .list-inline{margin-left: 0px;padding: 2px 8px;}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('.push_notication').click(function() {

		var growths_id = $(this).attr('data-value');
		var student_id = $(this).attr('value');

		//alert(growths_id + student_id);
		
		$('#ic-loading-' + growths_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_student_growths_send_notication') ?>',
	        type: 'POST',
	        data: 'growths_id=' + growths_id + '&student_id=' + student_id,
	        success: function(data) {
	        	$('#ic-loading-' + growths_id).hide();
	        	$('#box-' + growths_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
	});
});
</script>
<script type="text/javascript">

$('#remoteModal').on('hide.bs.modal', function(e) {
	$(this).removeData('bs.modal');
});

$(document).ready(function() {
	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_student_code a ,.sf_admin_list_td_first_name a, .sf_admin_list_td_last_name a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});
	// filter
	$('#ps_student_growths_filters_ps_customer_id').change(function() {

		resetOptions('ps_student_growths_filters_ps_workplace_id');
		$('#ps_student_growths_filters_ps_workplace_id').select2('val','');
		$("#ps_student_growths_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('ps_student_growths_filters_ps_class_id');
		$('#ps_student_growths_filters_ps_class_id').select2('val','');
		
		resetOptions('ps_student_growths_filters_examination_id');
		$('#ps_student_growths_filters_examination_id').select2('val','');
		
		
		if ($(this).val() > 0) {
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

		    	$('#ps_student_growths_filters_ps_workplace_id').select2('val','');

				$("#ps_student_growths_filters_ps_workplace_id").html(msg);

				$("#ps_student_growths_filters_ps_workplace_id").attr('disabled', null);

		    });
		}		
	});
	 
	$('#ps_student_growths_filters_ps_workplace_id').change(function() {
		
		resetOptions('ps_student_growths_filters_examination_id');
		$('#ps_student_growths_filters_examination_id').select2('val','');
		if ($('#ps_student_growths_filters_ps_customer_id').val() <= 0) {
			return;
		}
		
		$("#ps_student_growths_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_student_growths_filters_ps_customer_id').val() + '&w_id=' + $('#ps_student_growths_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_student_growths_filters_school_year_id').val() + '&o_id=' + $('#ps_student_growths_filters_ps_obj_group_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_student_growths_filters_ps_class_id').select2('val','');
			$("#ps_student_growths_filters_ps_class_id").html(msg);
			$("#ps_student_growths_filters_ps_class_id").attr('disabled', null);
	    });
		
		$("#ps_student_growths_filters_examination_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_student_growths_examination') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_student_growths_filters_ps_customer_id').val() + '&w_id=' + $('#ps_student_growths_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_student_growths_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_student_growths_filters_examination_id').select2('val','');
			$("#ps_student_growths_filters_examination_id").html(msg);
			$("#ps_student_growths_filters_examination_id").attr('disabled', null);
	    });
	    
	});

	$('#ps_student_growths_filters_school_year_id').change(function() {
		
		if ($('#ps_student_growths_filters_school_year_id').val() <= 0) {
			return;
		}

		$("#ps_student_growths_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_student_growths_filters_ps_customer_id').val() + '&w_id=' + $('#ps_student_growths_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_student_growths_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_student_growths_filters_ps_class_id').select2('val','');
			$("#ps_student_growths_filters_ps_class_id").html(msg);
			$("#ps_student_growths_filters_ps_class_id").attr('disabled', null);
	    });
	
		
		$("#ps_student_growths_filters_examination_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_student_growths_examination') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_student_growths_filters_ps_customer_id').val() + '&w_id=' + $('#ps_student_growths_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_student_growths_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_student_growths_filters_examination_id').select2('val','');
			$("#ps_student_growths_filters_examination_id").html(msg);
			$("#ps_student_growths_filters_examination_id").attr('disabled', null);
	    });

	});

	$('#ps_student_growths_filters_ps_obj_group_id').change(function() {

		$("#ps_student_growths_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_student_growths_filters_ps_customer_id').val() + '&w_id=' + $('#ps_student_growths_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_student_growths_filters_school_year_id').val() + '&o_id=' + $('#ps_student_growths_filters_ps_obj_group_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_student_growths_filters_ps_class_id').select2('val','');
			$("#ps_student_growths_filters_ps_class_id").html(msg);
			$("#ps_student_growths_filters_ps_class_id").attr('disabled', null);
	    });
	});


});
</script>
<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal_errors');?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal');?>