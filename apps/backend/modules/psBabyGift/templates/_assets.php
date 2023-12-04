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

function openLoadImages(field, add_url) {
	window.KCFinder = {
			callBack: function(url) {
				field.value = url;		   		    
				window.KCFinder = null;			        	        
			},
			relative_urls: false,
			remove_script_host: false,
			convert_urls: true
	};
	window.open(add_url, 'kcfinder_textbox','inline=1, resizable=1, scrollbars=0, width=800, height=600');
}

function remove_file_img (_name_file) {
	$('.' + _name_file).val('');
}

$(document).ready(function() {

	$('#ps_baby_gift_date_at').datepicker({
    		dateFormat : 'dd-mm-yy',
    		prevText : '<i class="fa fa-chevron-left"></i>',
    		nextText : '<i class="fa fa-chevron-right"></i>',
    		changeMonth : true,
    		changeYear : true,
    	})
    	
	.on('change', function(e) {
		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
	});


	// END: filters

	$('#ps_category_review_filters_ps_customer_id').change(function() {

		resetOptions('ps_category_review_filters_ps_workplace_id');
	    $('#ps_category_review_filters_ps_workplace_id').select2('val','');
	    $("#ps_category_review_filters_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_category_review_filters_ps_workplace_id').show();
	    		$('#ps_category_review_filters_ps_workplace_id').html(data);
	    		$("#ps_category_review_filters_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });

    //xử lí trong form
    $('#ps_category_review_ps_customer_id').change(function() {

		resetOptions('ps_category_review_ps_workplace_id');
	    $('#ps_category_review_ps_workplace_id').select2('val','');
	    $("#ps_category_review_ps_workplace_id").attr('disabled', 'disabled');
    	
	    $.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#ps_category_review_ps_workplace_id').show();
	    		$('#ps_category_review_ps_workplace_id').html(data);
	    		$("#ps_category_review_ps_workplace_id").attr('disabled', null);
	    	}
	    });		

    });
    
});
</script>
<?php include_partial('global/include/_box_modal')?>