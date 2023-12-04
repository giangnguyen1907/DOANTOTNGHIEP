<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function() {


	$('#ps_receivable_temporary_filters_school_year_id').change(function () {
	    
        if($(this).val() <= 0 ) {
            return;
        }
    
        $("#ps_receivable_temporary_filters_ps_month").attr('disabled', 'disabled');
    
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
            $('#ps_receivable_temporary_filters_ps_month').select2('val','');
            $("#ps_receivable_temporary_filters_ps_month").html(msg);
            $("#ps_receivable_temporary_filters_ps_month").attr('disabled', null);
        });
    
    });

	$('#ps_receivable_temporary_filters_ps_customer_id').change(function() {
		
		if ($(this).val() > 0) {

			$("#ps_receivable_temporary_filters_ps_workplace_id").attr('disabled', 'disabled');
			
			$("#ps_receivable_temporary_filters_receivable_id").attr('disabled', 'disabled');
	    	
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

		    	$('#ps_receivable_temporary_filters_ps_workplace_id').select2('val','');

				$("#ps_receivable_temporary_filters_ps_workplace_id").html(msg);

				$("#ps_receivable_temporary_filters_ps_workplace_id").attr('disabled', null);

		    });

	    	$.ajax({
		  	      url: '<?php echo url_for('@ps_receivable_student_by_customer') ?>',
		  	      type: "POST",
		  	      data: 'y_id=' + $('#ps_receivable_temporary_filters_school_year_id').val() + '&psc_id=' + $('#ps_receivable_temporary_filters_ps_customer_id').val(),
		  	      processResults: function (data, page) {
		  	          return {
		  	            results: data.items  
		  	          };
		  	      },
		  	      }).done(function(msg) {
		  	    	  $('#ps_receivable_temporary_filters_receivable_id').select2('val','');
		  	    	  $("#ps_receivable_temporary_filters_receivable_id").html(msg);
		  	    	  $("#ps_receivable_temporary_filters_receivable_id").attr('disabled', null);
		  	      });
			}		
		});

		$('#ps_receivable_temporary_filters_ps_workplace_id').change(function() {
		
		if ($(this).val() > 0) {

			$("#ps_receivable_temporary_filters_receivable_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
		  	      url: '<?php echo url_for('@ps_receivable_student_by_customer') ?>',
		  	      type: "POST",
		  	      data: 'y_id=' + $('#ps_receivable_temporary_filters_school_year_id').val() + '&psc_id=' + $('#ps_receivable_temporary_filters_ps_customer_id').val() + '&wp_id=' + $('#ps_receivable_temporary_filters_ps_workplace_id').val(),
		  	      processResults: function (data, page) {
		  	          return {
		  	            results: data.items  
		  	          };
		  	      },
		  	      }).done(function(msg) {
		  	    	  $('#ps_receivable_temporary_filters_receivable_id').select2('val','');
		  	    	  $("#ps_receivable_temporary_filters_receivable_id").html(msg);
		  	    	  $("#ps_receivable_temporary_filters_receivable_id").attr('disabled', null);
		  	      });
			}		
		});
	

    //form
    $('#ps_receivable_temporary_school_year_id, #ps_receivable_temporary_ps_customer_id').change(function() {

    	resetOptions('ps_receivable_temporary_receivable_id');
    	$('#ps_receivable_temporary_receivable_id').select2('val','');

    	$("#ps_receivable_temporary_receivable_id").attr('disabled', 'disabled');
    	
    	$.ajax({
	  	      url: '<?php echo url_for('@ps_receivable_student_by_customer') ?>',
	  	      type: "POST",
	  	      data: 'y_id=' + $('#ps_receivable_temporary_school_year_id').val() + '&psc_id=' + $('#ps_receivable_temporary_ps_customer_id').val(),
	  	      processResults: function (data, page) {
	  	          return {
	  	            results: data.items  
	  	          };
	  	      },
	  	      }).done(function(msg) {
	  	    	  $('#ps_receivable_temporary_receivable_id').select2('val','');
	  	    	  $("#ps_receivable_temporary_receivable_id").html(msg);
	  	    	  $("#ps_receivable_temporary_receivable_id").attr('disabled', null);
	  	      });
    });

    $('#ps_receivable_temporary_school_year_id').change(function() {

		resetOptions('ps_receivable_temporary_receivable_at');

		if ($(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_start_end_year') ?>',
	        type: "POST",
	        data: {'y_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
		    }).done(function(msg) {
				 $('#ps_receivable_temporary_receivable_at').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
				 
		    });
	});

    $.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
    
    $('#ps_receivable_temporary_receivable_at').datepicker({
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