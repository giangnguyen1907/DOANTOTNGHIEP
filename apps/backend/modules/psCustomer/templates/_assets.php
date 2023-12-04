<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>

<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>

<script type="text/javascript">
var msg_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var msg_name_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: xls, xlsx. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var PsMaxSizeFile = '<?php echo $app_upload_max_size;?>';

$(document).ready(function() {

	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_school_code a, .sf_admin_list_td_school_name a").on("contextmenu",function(){
	    return false;
	});

	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL')):?>
	
	// Load district for filter by province
    $('#ps_customer_filters_ps_province_id').change(function() {

    	$("#ps_customer_filters_ps_district_id").attr('disabled', 'disabled');		
    	resetOptions('ps_customer_filters_ps_ward_id');
    	    	    	    	
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
	    	$('#ps_customer_filters_ps_district_id').select2('val','');
			$("#ps_customer_filters_ps_district_id").html(msg);
			$("#ps_customer_filters_ps_district_id").attr('disabled', null);				            	
	    });
    });
	
	$('#ps_customer_filters_ps_district_id').change(function() {

		$("#ps_customer_filters_ps_ward_id").attr('disabled', 'disabled');
        $.ajax({
            url: '<?php echo url_for('@ps_ward_by_district') ?>?did=' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
			$('#ps_customer_filters_ps_ward_id').select2('val','');
			$("#ps_customer_filters_ps_ward_id").html(msg);
			$("#ps_customer_filters_ps_ward_id").attr('disabled', null);               
        });
    });
	<?php endif;?>
    // END filters
    
	// Load district by province
    $('#ps_customer_ps_province_id').change(function() {

    	resetOptions('ps_customer_ps_ward_id');
    	
		if ($(this).val() > 0) {
			$("#ps_customer_ps_district_id").attr('disabled', 'disabled');
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
    			  $('#ps_customer_ps_district_id').select2('val','');     			 
    			  $("#ps_customer_ps_district_id").html(msg);
    			  $("#ps_customer_ps_district_id").attr('disabled', null);               
    		  });
		
		} else {			
			$("#ps_customer_ps_district_id").attr('disabled', 'disabled');
			$("#ps_customer_ps_district_id").html(null);
		}
    });

 	// Load ward by district
    $('#ps_customer_ps_district_id').change(function() {      
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
			 $("#ps_customer_ps_ward_id").html(msg);               
		  });	
	  } else {
		  $('#ps_customer_ps_ward_id').select2('val','');     			 
		  $("#ps_customer_ps_ward_id").html(msg);
		  $("#ps_customer_ps_ward_id").attr('disabled', null);
	  }
    });   

});
</script>