<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function() {
// 	alert($('#receivable_temp_filters_school_year_id').html());
	<?php $ps_school_year = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault () ->fetchOne(); ?>;
	<?php $ps_school_year_id = $ps_school_year ? $ps_school_year ->getId() : ''; ?>;
    $('#receivable_temp_filters_ps_customer_id').change(function () {
    
    	resetOptions('receivable_temp_filters_ps_workplace_id');
    	$('#receivable_temp_filters_ps_workplace_id').select2('val','');

    	resetOptions('receivable_temp_filters_ps_myclass_id');
    	$('#receivable_temp_filters_ps_myclass_id').select2('val','');
    	
    	if($(this).val() <= 0 ) {
    		return;
    	}
    
    	$("#receivable_temp_filters_ps_workplace_id").attr('disabled', 'disabled');

    	$("#receivable_temp_filters_ps_myclass_id").attr('disabled', 'disabled');
    
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
    	    	$('#receivable_temp_filters_ps_workplace_id').select2('val','');
    			$("#receivable_temp_filters_ps_workplace_id").html(msg);
    			$("#receivable_temp_filters_ps_workplace_id").attr('disabled', null);

    			$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#receivable_temp_filters_ps_customer_id').val() + '&w_id=' + $('#receivable_temp_filters_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id ?>,
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#receivable_temp_filters_ps_myclass_id').select2('val','');
					$("#receivable_temp_filters_ps_myclass_id").html(msg);
					$("#receivable_temp_filters_ps_myclass_id").attr('disabled', null);
			    });

	    });
	    
    });

    $('#receivable_temp_filters_ps_workplace_id').change(function () {

    	resetOptions('receivable_temp_filters_ps_myclass_id');
    	$('#receivable_temp_filters_ps_myclass_id').select2('val','');

    	$("#receivable_temp_filters_ps_myclass_id").attr('disabled', 'disabled');
    	
    	$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#receivable_temp_filters_ps_customer_id').val() + '&w_id=' + $('#receivable_temp_filters_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id ?>,
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#receivable_temp_filters_ps_myclass_id').select2('val','');
			$("#receivable_temp_filters_ps_myclass_id").html(msg);
			$("#receivable_temp_filters_ps_myclass_id").attr('disabled', null);
	    });
    
    });

	//form
	$('#receivable_temp_ps_customer_id').change(function () {
    
    	resetOptions('receivable_temp_ps_workplace_id');
    	$('#receivable_temp_ps_workplace_id').select2('val','');

    	resetOptions('receivable_temp_ps_myclass_id');
    	$('#receivable_temp_ps_myclass_id').select2('val','');
    	
    	if($(this).val() <= 0 ) {
    		return;
    	}
    
    	$("#receivable_temp_ps_workplace_id").attr('disabled', 'disabled');

    	$("#receivable_temp_ps_myclass_id").attr('disabled', 'disabled');
    
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
    	    	$('#receivable_temp_ps_workplace_id').select2('val','');
    			$("#receivable_temp_ps_workplace_id").html(msg);
    			$("#receivable_temp_ps_workplace_id").attr('disabled', null);

    			$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#receivable_temp_ps_customer_id').val() + '&w_id=' + $('#receivable_temp_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id ?>,
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#receivable_temp_ps_myclass_id').select2('val','');
					$("#receivable_temp_ps_myclass_id").html(msg);
					$("#receivable_temp_ps_myclass_id").attr('disabled', null);
			    });

	    });
	    
    });
    
	$('#receivable_temp_ps_workplace_id').change(function () {

    	resetOptions('receivable_temp_ps_myclass_id');
    	$('#receivable_temp_ps_myclass_id').select2('val','');

    	$("#receivable_temp_ps_myclass_id").attr('disabled', 'disabled');
    	
    	$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#receivable_temp_ps_customer_id').val() + '&w_id=' + $('#receivable_temp_ps_workplace_id').val() + '&y_id=' + <?php echo $ps_school_year_id ?>,
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#receivable_temp_ps_myclass_id').select2('val','');
			$("#receivable_temp_ps_myclass_id").html(msg);
			$("#receivable_temp_ps_myclass_id").attr('disabled', null);
	    });
    
    });

	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
    
    $('#receivable_temp_receivable_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate: new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
	.on('change', function(e) {
		$('#ps-form').formValidation('revalidateField', $(this).attr('name'));
	});
	//end-form
    
});
</script>