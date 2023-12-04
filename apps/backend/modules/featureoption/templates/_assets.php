<script type="text/javascript">
$(document).ready(function() {

	$('#feature_option_filters_ps_customer_id').change(function() {      

		if ($(this).val() <= 0) {
			return;
		}

		resetOptions('feature_option_filters_servicegroup_id');
		$('#feature_option_filters_servicegroup_id').select2('val','');
		$("#feature_option_filters_servicegroup_id").attr('disabled', 'disabled');

		resetOptions('feature_option_filters_feature_id');
		$('#feature_option_filters_feature_id').select2('val','');
		$("#feature_option_filters_feature_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_feature_by_customer?cid=') ?>' + $(this).val(),
            type: "POST",
            data: {'cid': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_option_filters_feature_id').select2('val','');
            $("#feature_option_filters_feature_id").html(msg);
            $("#feature_option_filters_feature_id").attr('disabled', null);
        });

		$.ajax({
			url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
            type: "POST",
            data: {'cid': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_option_filters_servicegroup_id').select2('val','');
            $("#feature_option_filters_servicegroup_id").html(msg);
            $("#feature_option_filters_servicegroup_id").attr('disabled', null);
        });
        
    });

	$('#feature_option_ps_customer_id').change(function() {      

		if ($(this).val() <= 0) {
			return;
		}

		resetOptions('feature_option_servicegroup_id');
		$('#feature_option_servicegroup_id').select2('val','');
		$("#feature_option_servicegroup_id").attr('disabled', 'disabled');

		resetOptions('feature_option_feature_id');
		$('#feature_option_feature_id').select2('val','');
		$("#feature_option_feature_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_feature_by_customer?cid=') ?>' + $(this).val(),
            type: "POST",
            data: {'cid': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_option_feature_id').select2('val','');
            $("#feature_option_feature_id").html(msg);
            $("#feature_option_feature_id").attr('disabled', null);
        });
        
		$.ajax({
			url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
            type: "POST",
            data: {'cid': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_option_servicegroup_id').select2('val','');
            $("#feature_option_servicegroup_id").html(msg);
            $("#feature_option_servicegroup_id").attr('disabled', null);
        });
        
    });
	
	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL')):?>
	// Load district for filter by ps_province_id
    $('#feature_option_filters_ps_province_id').change(function() {    	
		
		$("#feature_option_filters_ps_district_id").attr('disabled', 'disabled');
		
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
	    	$("#feature_option_filters_ps_district_id").attr('disabled', null);
			$('#feature_option_filters_ps_district_id').select2('val','');
			$("#feature_option_filters_ps_district_id").html(msg);	            	
	    });
    });

    $('#feature_option_filters_ps_district_id').change(function() {
        
		$("#feature_option_filters_ps_ward_id").attr('disabled', 'disabled');
		
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
        	$("#feature_option_filters_ps_ward_id").attr('disabled', null);
			$('#feature_option_filters_ps_ward_id').select2('val','');
            $("#feature_option_filters_ps_ward_id").html(msg);               
        });

      });

    $('#feature_option_filters_ps_ward_id').change(function() {      
        
		$("#feature_option_filters_ps_customer_id").attr('disabled', 'disabled');
		
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
        	$("#feature_option_filters_ps_customer_id").attr('disabled', null);
			$('#feature_option_filters_ps_customer_id').select2('val','');
            $("#feature_option_filters_ps_customer_id").html(msg);               
        });
      });
	<?php endif;?>
	//------- END: filters -----------------------------------
	
	// Load district by province
    $('#feature_option_ps_province_id').change(function() {
		$("#feature_option_ps_district_id").attr('disabled', 'disabled');
		
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
			 $("#feature_option_ps_district_id").attr('disabled', null);
			 $("#feature_option_ps_district_id").html(msg);               
		  });
		
		} else {
			
			$("#feature_option_ps_district_id").attr('disabled', 'disabled');
			$("#feature_option_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#feature_option_ps_district_id').change(function() {
      $("#feature_option_ps_ward_id").attr('disabled', 'disabled');
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
			 $("#feature_option_ps_ward_id").attr('disabled', null);
			 $("#feature_option_ps_ward_id").html(msg);               
		  });
	  } else {
		  $("#feature_option_ps_ward_id").attr('disabled', 'disabled');
		  $("#feature_option_ps_ward_id").html(null);
	  }

    });

 	// Load customer by ward
    $('#feature_option_ps_ward_id').change(function() {
		$("#feature_option_ps_customer_id").attr('disabled', 'disabled');
		
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
        $("#feature_option_ps_customer_id").attr('disabled', null);
		$("#feature_option_ps_customer_id").html(msg);		 
      });
    });    
});
</script>