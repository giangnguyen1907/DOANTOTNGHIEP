<?php use_helper('I18N', 'Number') ?>
<?php if (myUser::credentialPsCustomers('PS_SYSTEM_ROOMS_FILTER_SCHOOL')):?>
<script type="text/javascript">

$(document).ready(function() {

	// Load district for filter by ps_province_id
    $('#ps_class_rooms_filters_ps_province_id').change(function() {    	
		
		$("#ps_class_rooms_filters_ps_district_id").attr('disabled', 'disabled');
		$('#ps_class_rooms_filters_ps_district_id').select2('val','');
		$('#ps_class_rooms_filters_ps_ward_id').select2('val','');
		
		resetOptions('ps_class_rooms_filters_ps_ward_id');
		$('#ps_class_rooms_filters_ps_ward_id').select2('val','');
		
		resetOptions('ps_class_rooms_filters_ps_customer_id');
		$('#ps_class_rooms_filters_ps_customer_id').select2('val','');
				
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
	    	$('#ps_class_rooms_filters_ps_district_id').select2('val','');
			$("#ps_class_rooms_filters_ps_district_id").html(msg);
			$("#ps_class_rooms_filters_ps_district_id").attr('disabled', null);
	    });
    });

    $('#ps_class_rooms_filters_ps_district_id').change(function() {
        $("#ps_class_rooms_filters_ps_ward_id").attr('disabled', 'disabled');
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
        	$('#ps_class_rooms_filters_ps_ward_id').select2('val','');
            $("#ps_class_rooms_filters_ps_ward_id").html(msg);
			$("#ps_class_rooms_filters_ps_ward_id").attr('disabled', null);			
        });

      });

    $('#ps_class_rooms_filters_ps_ward_id').change(function() {      
        $("#ps_class_rooms_filters_ps_customer_id").attr('disabled', 'disabled');
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
        	$('#ps_class_rooms_filters_ps_customer_id').select2('val','');
            $("#ps_class_rooms_filters_ps_customer_id").html(msg);
			$("#ps_class_rooms_filters_ps_customer_id").attr('disabled', null);			
        });
    });
    
	// Form filters - Lay co so dao tao theo nha truong
	$('#ps_class_rooms_filters_ps_customer_id').change(function() {
		
		$("#ps_class_rooms_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_class_rooms_filters_ps_workplace_id').html(data);
				$("#ps_class_rooms_filters_ps_workplace_id").attr('disabled', null);
				
	        }
		});		 	
	});
	// END: filters
	
	// Load district by province
    $('#ps_class_rooms_ps_province_id').change(function() {
		$("#ps_class_rooms_ps_district_id").attr('disabled', 'disabled');
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
			 $("#ps_class_rooms_ps_district_id").html(msg);               
			 $("#ps_class_rooms_ps_district_id").attr('disabled', null);
		  });
		
		} else {
			
			$("#ps_class_rooms_ps_district_id").attr('disabled', 'disabled');
			$("#ps_class_rooms_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#ps_class_rooms_ps_district_id').change(function() {

        $("#ps_class_rooms_ps_ward_id").attr('disabled', 'disabled');
        $('#ps_class_rooms_ps_ward_id').select2('val','');
      
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
			 $('#ps_class_rooms_ps_ward_id').select2('val','');
		     $("#ps_class_rooms_ps_ward_id").html(msg);               
			 $("#ps_class_rooms_ps_ward_id").attr('disabled', null);
		  });
	  } else {
		  $("#ps_class_rooms_ps_district_id").attr('disabled', 'disabled');
		  $("#ps_class_rooms_ps_district_id").html(null);
	  }
    });

 	// Load customer by ward
    $('#ps_class_rooms_ps_ward_id').change(function() {      
      $("#ps_class_rooms_ps_customer_id").attr('disabled', 'disabled');
	  $.ajax({
          url: '<?php echo url_for('@ps_customer_by_ps_ward?wid=') ?>' + $(this).val(),
          type: "POST",
          data: {'wid': $(this).val()},
          processResults: function (data, page) {
              return {
                results: data.items  
              };
          },
      }).done(function(msg) {
         $("#ps_class_rooms_ps_customer_id").html(msg); 
		 $("#ps_class_rooms_ps_customer_id").attr('disabled', null);		 
      });
    });
	
	// Lay co so dao tao theo nha truong
	$('#ps_class_rooms_ps_customer_id').change(function() {
		
		$("#ps_class_rooms_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_class_rooms_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_class_rooms_ps_workplace_id').html(data);
				$("#ps_class_rooms_ps_workplace_id").attr('disabled', null);
	        }
		});		 	
	});

		    
});
</script>
<?php endif;?>