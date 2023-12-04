<?php include_partial('global/field_custom/_ps_assets') ?>
<?php include_partial('global/include/_box_modal');?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
		
	// filter
	$('#receivable_student_filters_school_year_id').change(function () {
	    
        if($(this).val() <= 0 ) {
            return;
        }
    
        $("#receivable_student_filters_ps_month").attr('disabled', 'disabled');
        $("#receivable_student_filters_ps_class_id").attr('disabled', 'disabled');
    
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
            $('#receivable_student_filters_ps_month').select2('val','');
            $("#receivable_student_filters_ps_month").html(msg);
            $("#receivable_student_filters_ps_month").attr('disabled', null);
        });

        $.ajax({
        	url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#receivable_student_filters_ps_customer_id').val() + '&w_id=' + $('#receivable_student_filters_ps_workplace_id').val() + '&y_id=' + $('#receivable_student_filters_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#receivable_student_filters_ps_class_id').select2('val','');
        	$("#receivable_student_filters_ps_class_id").html(msg);
        	$("#receivable_student_filters_ps_class_id").attr('disabled', null);
        });
    
    });

	$('#receivable_student_filters_ps_customer_id').change(function() {
		
		if ($(this).val() > 0) {

			resetOptions('receivable_student_filters_ps_workplace_id');
			$('#receivable_student_filters_ps_workplace_id').select2('val','');
			resetOptions('receivable_student_filters_ps_class_id');
			$('#receivable_student_filters_ps_class_id').select2('val','');
			resetOptions('receivable_student_filters_receivable_id');
			$('#receivable_student_filters_receivable_id').select2('val','');
			resetOptions('receivable_student_filters_student_id');
			$('#receivable_student_filters_student_id').select2('val','');
			
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

		    	$('#receivable_student_filters_ps_workplace_id').select2('val','');

				$("#receivable_student_filters_ps_workplace_id").html(msg);

				$("#receivable_student_filters_ps_workplace_id").attr('disabled', null);

		    });

	    	$.ajax({
		  	      url: '<?php echo url_for('@ps_receivable_student_by_customer') ?>',
		  	      type: "POST",
		  	      data: 'y_id=' + $('#receivable_student_filters_school_year_id').val() + '&psc_id=' + $('#receivable_student_filters_ps_customer_id').val(),
		  	      processResults: function (data, page) {
		  	          return {
		  	            results: data.items  
		  	          };
		  	      },
      	      }).done(function(msg) {
      	    	  $('#receivable_student_filters_receivable_id').select2('val','');
      	    	  $("#receivable_student_filters_receivable_id").html(msg);
      	    	  $("#receivable_student_filters_receivable_id").attr('disabled', null);
      	      });
    	      
	    	$.ajax({
            	url: '<?php echo url_for('@ps_class_by_params') ?>',
                type: "POST",
                data: 'c_id=' + $('#receivable_student_filters_ps_customer_id').val() + '&w_id=' + $('#receivable_student_filters_ps_workplace_id').val() + '&y_id=' + $('#receivable_student_filters_school_year_id').val(),
                processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
            }).done(function(msg) {
            	$('#receivable_student_filters_ps_class_id').select2('val','');
            	$("#receivable_student_filters_ps_class_id").html(msg);
            	$("#receivable_student_filters_ps_class_id").attr('disabled', null);
            });
  	      
			}		
		});

		$('#receivable_student_filters_ps_workplace_id').change(function() {
		
		if ($(this).val() > 0) {

			$("#receivable_student_filters_receivable_id").attr('disabled', 'disabled');
			$("#receivable_student_filters_ps_class_id").attr('disabled', 'disabled');
	    	$.ajax({
		  	      url: '<?php echo url_for('@ps_receivable_student_by_customer') ?>',
		  	      type: "POST",
		  	      data: 'y_id=' + $('#receivable_student_filters_school_year_id').val() + '&psc_id=' + $('#receivable_student_filters_ps_customer_id').val() + '&wp_id=' + $('#receivable_student_filters_ps_workplace_id').val(),
		  	      processResults: function (data, page) {
		  	          return {
		  	            results: data.items  
		  	          };
		  	      },
		  	      }).done(function(msg) {
		  	    	  $('#receivable_student_filters_receivable_id').select2('val','');
		  	    	  $("#receivable_student_filters_receivable_id").html(msg);
		  	    	  $("#receivable_student_filters_receivable_id").attr('disabled', null);
		  	      });
	  	      
	    	$.ajax({
            	url: '<?php echo url_for('@ps_class_by_params') ?>',
                type: "POST",
                data: 'c_id=' + $('#receivable_student_filters_ps_customer_id').val() + '&w_id=' + $('#receivable_student_filters_ps_workplace_id').val() + '&y_id=' + $('#receivable_student_filters_school_year_id').val(),
                processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
            }).done(function(msg) {
            	$('#receivable_student_filters_ps_class_id').select2('val','');
            	$("#receivable_student_filters_ps_class_id").html(msg);
            	$("#receivable_student_filters_ps_class_id").attr('disabled', null);
            });
		}		
	});

    $('#receivable_student_filters_ps_class_id').change(function() {
    	if ($(this).val() > 0) {

    		$("#receivable_student_filters_student_id").attr('disabled', 'disabled');
            $.ajax({
            	url: '<?php echo url_for('@ps_students_by_class_id') ?>',
                type: "POST",
                data: 'c_id=' + $(this).val(),
                processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
            }).done(function(msg) {
            	$('#receivable_student_filters_student_id').select2('val','');
            	$("#receivable_student_filters_student_id").html(msg);
            	$("#receivable_student_filters_student_id").attr('disabled', null);
            });
        }
    });

 // filter statistic
	$('#receivable_filter_ps_customer_id').change(function() {

		resetOptions('receivable_filter_ps_workplace_id');
		$('#receivable_filter_ps_workplace_id').select2('val','');
		$("#receivable_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('receivable_filter_class_id');
		$('#receivable_filter_class_id').select2('val','');
		$("#receivable_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#receivable_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#receivable_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#receivable_filter_ps_workplace_id').select2('val','');

			$("#receivable_filter_ps_workplace_id").html(msg);

			$("#receivable_filter_ps_workplace_id").attr('disabled', null);

			$("#receivable_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#receivable_filter_ps_workplace_id').change(function() {
	
	$("#receivable_filter_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#receivable_filter_ps_customer_id').val() + '&w_id=' + $('#receivable_filter_ps_workplace_id').val() + '&y_id=' + $('#receivable_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#receivable_filter_class_id').select2('val','');
		$("#receivable_filter_class_id").html(msg);
		$("#receivable_filter_class_id").attr('disabled', null);
    });
});

$('#receivable_filter_ps_school_year_id').change(function() {
	
	resetOptions('receivable_filter_class_id');
	$('#receivable_filter_class_id').select2('val','');
	
	if ($('#receivable_filter_ps_customer_id').val() <= 0) {
		return;
	}

	$("#receivable_filter_class_id").attr('disabled', 'disabled');
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#receivable_filter_ps_customer_id').val() + '&w_id=' + $('#receivable_filter_ps_workplace_id').val() + '&y_id=' + $('#receivable_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#receivable_filter_class_id').select2('val','');
		$("#receivable_filter_class_id").html(msg);
		$("#receivable_filter_class_id").attr('disabled', null);
    });
});
    
    
});
</script>