<?php include_partial('global/include/_box_modal')?>
<script type="text/javascript">
$(document).ready(function(){
	$('#myTab a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		localStorage.setItem('activeTab', $(e.target).attr('href'));
	});
	var activeTab = localStorage.getItem('activeTab');
	if(activeTab){
		$('#myTab a[href="' + activeTab + '"]').tab('show');
	}
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	/*
	var hash = window.location.hash;
    if (hash == '')
    hash = '#pstab_1';	
	$('#myTab a[href="' + hash + '"]').tab('show');
	*/		
<?php if (myUser::credentialPsCustomers('PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL')):?>
	// Load district for filter by ps_province_id
    $('#ps_work_places_filters_ps_province_id').change(function() {    	

    	$("#ps_work_places_filters_ps_district_id").attr('disabled', 'disabled');		
    	resetOptions('ps_work_places_filters_ps_ward_id');
    	resetOptions('ps_work_places_filters_ps_customer_id');
    	
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
	    	$('#ps_work_places_filters_ps_district_id').select2('val','');
			$("#ps_work_places_filters_ps_district_id").html(msg);
			$("#ps_work_places_filters_ps_district_id").attr('disabled', null);	            	
	    });
    });

    $('#ps_work_places_filters_ps_district_id').change(function() {

    	$("#ps_work_places_filters_ps_ward_id").attr('disabled', 'disabled');
    	resetOptions('ps_work_places_filters_ps_customer_id');
    	
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
        	$('#ps_work_places_filters_ps_ward_id').select2('val','');
            $("#ps_work_places_filters_ps_ward_id").html(msg);
            $("#ps_work_places_filters_ps_ward_id").attr('disabled', null);               
        });

      });

    $('#ps_work_places_filters_ps_ward_id').change(function() {      

    	$("#ps_work_places_filters_ps_customer_id").attr('disabled', 'disabled');
    	
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
        	$('#ps_work_places_filters_ps_customer_id').select2('val','');
            $("#ps_work_places_filters_ps_customer_id").html(msg);
            $("#ps_work_places_filters_ps_customer_id").attr('disabled', null);               
        });
      });
	
	//------- END: filters -----------------------------------
	
	// Load district by province
    $('#ps_work_places_ps_province_id').change(function() {

		resetOptions('ps_work_places_ps_ward_id');
    	$("#ps_work_places_ps_ward_id").attr('disabled', 'disabled');

    	resetOptions('ps_work_places_ps_customer_id');
    	$("#ps_work_places_ps_customer_id").attr('disabled', 'disabled');
      	
		if ($(this).val() > 0) {
			$("#ps_work_places_ps_district_id").attr('disabled', 'disabled');
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
    			  $('#ps_work_places_ps_district_id').select2('val','');
    			  $("#ps_work_places_ps_district_id").html(msg);
    			  $("#ps_work_places_ps_district_id").attr('disabled', null);               
    		  });		
		} else {
			$('#ps_work_places_ps_district_id').select2('val','');
			$("#ps_work_places_ps_district_id").html(null);
			$("#ps_work_places_ps_district_id").attr('disabled', 'disabled');

		}
    });

 	// Load ward by district
    $('#ps_work_places_ps_district_id').change(function() {

    	resetOptions('ps_work_places_ps_ward_id');

    	resetOptions('ps_work_places_ps_customer_id');
    	$("#ps_work_places_ps_customer_id").attr('disabled', 'disabled');

    	if ($(this).val() > 0) {
    		$("#ps_work_places_ps_ward_id").attr('disabled', 'disabled');
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
    			  $('#ps_work_places_ps_ward_id').select2('val','');    			  
    			  $("#ps_work_places_ps_ward_id").html(msg);        
    			  $("#ps_work_places_ps_ward_id").attr('disabled', null);       
    		  });
    	  } else {
    		  $("#ps_work_places_ps_district_id").attr('disabled', 'disabled');
    		  $("#ps_work_places_ps_district_id").html(null);
    	}
    });

 	// Load customer by ward
    $('#ps_work_places_ps_ward_id').change(function() {
    	$("#ps_work_places_ps_customer_id").attr('disabled', 'disabled');
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
    	  $('#ps_work_places_ps_customer_id').select2('val','');
    	  $("#ps_work_places_ps_customer_id").html(msg);
    	  $("#ps_work_places_ps_customer_id").attr('disabled', null);               
      });
    });

    <?php endif;?>
        
});
</script>