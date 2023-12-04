<script type="text/javascript">
$(document).ready(function() {
	
	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_GROUP_USER_FILTER_SCHOOL')):?>
	
	// Load district for filter by ps_province_id
    $('#sf_guard_group_filters_ps_province_id').change(function() {    	
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
	    	$('#sf_guard_group_filters_ps_district_id').select2('val','');
			$("#sf_guard_group_filters_ps_district_id").html(msg);	            	
	    });
    });

    $('#sf_guard_group_filters_ps_district_id').change(function() {
        
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
        	$('#sf_guard_group_filters_ps_ward_id').select2('val','');
            $("#sf_guard_group_filters_ps_ward_id").html(msg);               
        });

      });

    $('#sf_guard_group_filters_ps_ward_id').change(function() {      
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
        	$('#sf_guard_group_filters_ps_customer_id').select2('val','');
            $("#sf_guard_group_filters_ps_customer_id").html(msg);               
        });
      });
    
	<?php endif;?>
	//------- END: filters -----------------------------------
	
	// Load district by province
    $('#sf_guard_group_ps_province_id').change(function() {
      
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
			 $("#sf_guard_group_ps_district_id").attr('disabled', null);
			 $("#sf_guard_group_ps_district_id").html(msg);               
		  });
		
		} else {
			
			$("#sf_guard_group_ps_district_id").attr('disabled', 'disabled');
			$("#sf_guard_group_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#sf_guard_group_ps_district_id').change(function() {
      
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
			 $("#sf_guard_group_ps_ward_id").attr('disabled', null);
			 $("#sf_guard_group_ps_ward_id").html(msg);               
		  });
	  } else {
		  $("#sf_guard_group_ps_district_id").attr('disabled', 'disabled');
		  $("#sf_guard_group_ps_district_id").html(null);
	  }

    });

 	// Load customer by ward
    $('#sf_guard_group_ps_ward_id').change(function() {      
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
         $("#sf_guard_group_ps_customer_id").html(msg);               
      });
    });

    $('#sf_guard_group_ps_customer_id').change(function() {
    	if ($(this).val() > 0) {    	
	    	$.ajax({
		        url: '<?php echo url_for('@sf_guard_group_ps_users?cid=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'cid=' + $(this).val(),
		        success: function(data) {
		            $("#sf_guard_group_users_list").attr('disabled', null);
		            $('#sf_guard_group_users_list').html(data);		            		            
		        }
		    });
	    } else {
	    	$('#sf_guard_group_users_list').html(null);
	    	$('#sf_guard_group_users_list').select2('val','');
	    	$("#sf_guard_group_users_list").attr('disabled', 'disabled');
	    }	 	
    });
    
});
</script>