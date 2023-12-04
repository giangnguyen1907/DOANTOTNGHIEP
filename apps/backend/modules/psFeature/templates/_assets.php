<script type="text/javascript">
$(document).ready(function() {
	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_FILTER_SCHOOL')):?>
	// Load district for filter by ps_province_id
    $('#feature_filters_ps_province_id').change(function() {    	
		$.ajax({
	        url: '<?php echo url_for('@ps_districts_by_province?pid=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'pid': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items  
          		};
        	},
	    }).done(function(msg) {
	    	$('#feature_filters_ps_district_id').select2('val','');
			$("#feature_filters_ps_district_id").html(msg);	            	
	    });
    });

    $('#feature_filters_ps_district_id').change(function() {
        
        $.ajax({
            url: '<?php echo url_for('@ps_ward_by_district?did=') ?>' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_filters_ps_ward_id').select2('val','');
            $("#feature_filters_ps_ward_id").html(msg);               
        });

      });

    $('#feature_filters_ps_ward_id').change(function() {      
        $.ajax({
            url: '<?php echo url_for('@ps_customer_by_ps_ward?wid=') ?>' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_filters_ps_customer_id').select2('val','');
            $("#feature_filters_ps_customer_id").html(msg);               
        });
      });
	<?php endif;?>
	//------- END: filters -----------------------------------
	
	// Load district by province
    $('#feature_ps_province_id').change(function() {
      
		if ($(this).val() > 0) {
		  
		  $.ajax({
			  url: '<?php echo url_for('@ps_districts_by_province?pid=') ?>' + $(this).val(),
			  type: "POST",
			  data: {'pid': $(this).val()},
			  processResults: function (data, page) {
				  return {
					results: data.items  
				  };
			  },
		  }).done(function(msg) {
			 $("#feature_ps_district_id").attr('disabled', null);
			 $("#feature_ps_district_id").html(msg);               
		  });
		
		} else {
			
			$("#feature_ps_district_id").attr('disabled', 'disabled');
			$("#feature_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#feature_ps_district_id').change(function() {
      
	  if ($(this).val() > 0) {
		  $.ajax({
			  url: '<?php echo url_for('@ps_ward_by_district?did=') ?>' + $(this).val(),
			  type: "POST",
			  data: {'did': $(this).val()},
			  processResults: function (data, page) {
				  return {
					results: data.items  
				  };
			  },
		  }).done(function(msg) {
			 $("#feature_ps_ward_id").attr('disabled', null);
			 $("#feature_ps_ward_id").html(msg);               
		  });
	  } else {
		  $("#feature_ps_district_id").attr('disabled', 'disabled');
		  $("#feature_ps_district_id").html(null);
	  }

    });

 	// Load customer by ward
    $('#feature_ps_ward_id').change(function() {      
      $.ajax({
          url: '<?php echo url_for('@ps_customer_by_ps_ward?wid=') ?>' + $(this).val(),
          type: "POST",
          data: {'did': $(this).val()},
          processResults: function (data, page) {
              return {
                results: data.items  
              };
          },
      }).done(function(msg) {
         $("#feature_ps_customer_id").html(msg);               
      });
    });    
});
</script>