<?php use_helper('I18N', 'Number')?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
<style>
.input-group.sf_admin_form_row, .input-group.sf_admin_form_row label {
	width: 100%
}

#ps_cms_articles_description_ifr {
	min-height: 300px !important
}

</style>
<script>

var msg_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var msg_name_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: xls, xlsx. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var PsMaxSizeFile = '<?php echo $app_upload_max_size;?>';

$(document).ready(function(){
	$('#ps_cms_articles_filters_ps_customer_id').change(function() {

		resetOptions('ps_cms_articles_filters_ps_workplace_id');
		
		$('#ps_cms_articles_filters_ps_workplace_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#ps_cms_articles_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_cms_articles_filters_ps_workplace_id').select2('val','');

				$("#ps_cms_articles_filters_ps_workplace_id").html(msg);

				$("#ps_cms_articles_filters_ps_workplace_id").attr('disabled', null);
		    });
		}		
	});
		
	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_title a, a[data-target=#remoteModal]").on("contextmenu",function(){
	       return false;
	});
	
	//is cached
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});	

	$('#ps_cms_articles_ps_customer_id').change(function() {

		resetOptions('ps_cms_articles_ps_workplace_id');
		
	    $('#ps_cms_articles_ps_workplace_id').select2('val','');
	    
	    $("#ps_cms_articles_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_cms_articles_ps_workplace_id').show();
	    		$('#ps_cms_articles_ps_workplace_id').html(data);
	    		$("#ps_cms_articles_ps_workplace_id").attr('disabled', null);
	    	}
	    });	
	});

	$('#ps_cms_articles_ps_workplace_id').change(function() {

		resetOptions('ps_cms_articles_ps_class_ids');
	    $('#ps_cms_articles_ps_class_ids').select2('val','');
	    //$("#ps_cms_articles_ps_class_ids").attr('disabled', 'disabled');
		if ($('#ps_cms_articles_ps_workplace_id').val() > 0) {
			$.ajax({
				url: '<?php echo url_for('@ps_class_object_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_cms_articles_ps_customer_id').val() + '&w_id=' + $('#ps_cms_articles_ps_workplace_id').val() + '&y_id=' + $('#ps_cms_articles_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#ps_cms_articles_ps_class_ids').select2('val','');
				$("#ps_cms_articles_ps_class_ids").html(msg);
				$("#ps_cms_articles_ps_class_ids").attr('disabled', null);
		    });
		}else{
			return;
		}
	});

	$('#ps_cms_articles_filters_ps_workplace_id').change(function() {
		
		if ($('#ps_cms_articles_filters_ps_customer_id').val() <= 0) {
			return;
		}
		
		$("#ps_cms_articles_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_cms_articles_filters_ps_customer_id').val() + '&w_id=' + $('#ps_cms_articles_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_cms_articles_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_cms_articles_filters_ps_class_id').select2('val','');
			$("#ps_cms_articles_filters_ps_class_id").html(msg);
			$("#ps_cms_articles_filters_ps_class_id").attr('disabled', null);
	    });
		
	});
});
</script>
<?php include_partial('global/include/_box_modal');?>
