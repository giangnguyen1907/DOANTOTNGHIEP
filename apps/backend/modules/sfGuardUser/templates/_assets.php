<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>
<script type="text/javascript">

var router_check = '<?php echo url_for('@ps_user_check_username') ?>?';

$(document).ready(function() {

    //Load ajax User state
	$(".btn-item-activated, .btn-item-deactivated, .btn-item-lock").click(function() {
		var id =  $(this).attr('item');
		var state = $(this).attr('data-check');
		
		$.ajax({
	        url: '<?php echo url_for('@sf_guard_user_activated') ?>',
	        type: 'POST',
	        data: 'id=' + id + '&state=' + state,
	        success: function(data) {
	        	$('#field-user-' + id).html(data);
	        	return;
	        }
		});
	});

	//Load ajax choose customer show workplace
	$('#sf_guard_user_filters_ps_customer_id').change(function() {

		resetOptions('sf_guard_user_filters_ps_workplace_id');
		$('#sf_guard_user_filters_ps_workplace_id').select2('val','');

		if ($(this).val() > 0) {

			$("#sf_guard_user_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#sf_guard_user_filters_ps_workplace_id').select2('val','');

				$("#sf_guard_user_filters_ps_workplace_id").html(msg);

				$("#sf_guard_user_filters_ps_workplace_id").attr('disabled', null);

		    });
		}
	
	});
	
	// Load group District for filter by country_code
    $('#sf_guard_user_filters_ps_province_id').change(function() {    	
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
	    	$("#sf_guard_user_filters_ps_district_id").html(msg);	            	
	    });
    });

    $('#sf_guard_user_filters_ps_district_id').change(function() {
        var ps_ward_by_district = ps_ward_by_district + '?did=' + $(this).val();
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
        	 //$('#sf_guard_user_filters_ps_ward_id').select2('val','');
           $("#sf_guard_user_filters_ps_ward_id").html(msg);               
        });
    });

    $('#sf_guard_user_filters_ps_ward_id').change(function() {        
		$.ajax({
            url: '<?php echo url_for('@ps_customer_by_ps_ward') ?>?wid=' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	//$('#sf_guard_user_filters_ps_customer_id').select2('val','');
          $("#sf_guard_user_filters_ps_customer_id").html(msg);               
        });	
    });	
	
	//------- END: filters -----------------------------------
	
	if ($("#sf_guard_user_ps_province_id").val() <= 0) {		
		$("#sf_guard_user_ps_district_id").attr('disabled', 'disabled');		
	};

	<?php if (!isset($sf_guard_user) || (isset($sf_guard_user) && ($sf_guard_user->getId() <= 0))) :?>

		if ($("#sf_guard_user_ps_province_id").val() <= 0) {		
			$("#sf_guard_user_ps_district_id").attr('disabled', 'disabled');		
		};
		
		if ($("#sf_guard_user_ps_district_id").val() <= 0) {		
			$("#sf_guard_user_ps_ward_id").attr('disabled', 'disabled');		
		} else {
			$("#sf_guard_user_ps_ward_id").attr('disabled', null);
		}
		
		$("#sf_guard_user_ps_district_id").on('change', function(e) {

			if ($("#sf_guard_user_ps_district_id").val() <= 0) {		
				
				$("#sf_guard_user_ps_ward_id").attr('disabled', 'disabled');
				
			} else {
				
				$("#sf_guard_user_ps_ward_id").attr('disabled', null);
			}
	  	});
		
		if ($("#sf_guard_user_ps_ward_id").val() <= 0) {		
			$("#sf_guard_user_ps_customer_id").attr('disabled', 'disabled');		
		};
		
		$("#sf_guard_user_ps_ward_id").on('change', function(e) {
			if ($("#sf_guard_user_ps_ward_id").val() <= 0) {		
				$("#sf_guard_user_ps_customer_id").attr('disabled', 'disabled');		
			} else {
				$("#sf_guard_user_ps_customer_id").attr('disabled', null);
			}
	  	});
		
		if ($("#sf_guard_user_ps_customer_id").val() <= 0) {		
			$("#sf_guard_user_member_id").attr('disabled', 'disabled');
		};
		
	<?php endif;?>
	
	// Load district by province
    $('#sf_guard_user_ps_province_id').change(function() {
      
		if ($(this).val() > 0) {

			resetOptions('sf_guard_user_ps_district_id');			
			$('#sf_guard_user_ps_district_id').select2('val','');			
			$("#sf_guard_user_ps_district_id").attr('disabled', 'disabled');
			
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
			 $("#sf_guard_user_ps_district_id").attr('disabled', null);
			 $("#sf_guard_user_ps_district_id").html(msg);               
		  });
		
		} else {
			
			$("#sf_guard_user_ps_district_id").attr('disabled', 'disabled');
			$("#sf_guard_user_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#sf_guard_user_ps_district_id').change(function() {
      
	  if ($(this).val() > 0) {
		  resetOptions('sf_guard_user_ps_ward_id');			
		  $('#sf_guard_user_ps_ward_id').select2('val','');			
		  $("#sf_guard_user_ps_ward_id").attr('disabled', 'disabled');
				  
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
			 $("#sf_guard_user_ps_ward_id").html(msg);
			 $("#sf_guard_user_ps_ward_id").attr('disabled', null);               
		  });	
	  } else {
		  $("#sf_guard_user_ps_ward_id").attr('disabled', 'disabled');
		  $("#sf_guard_user_ps_ward_id").html(null);
	  }

    });

 	// Load customer by ward
    $('#sf_guard_user_ps_ward_id').change(function() {      
    	resetOptions('sf_guard_user_ps_customer_id');			
		  $('#sf_guard_user_ps_customer_id').select2('val','');			
		  $("#sf_guard_user_ps_customer_id").attr('disabled', 'disabled');
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
         $("#sf_guard_user_ps_customer_id").html(msg);
         $("#sf_guard_user_ps_customer_id").attr('disabled', null);               
      });
    });

    $("#sf_guard_user_ps_customer_id").on('change', function(e) {
		if ($("#sf_guard_user_ps_customer_id").val() <= 0) {		
			$("#sf_guard_user_member_id").attr('disabled', 'disabled');		
		} else {

			$("#sf_guard_user_member_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_user_by_customer?cid=') ?>' + $("#sf_guard_user_ps_customer_id").val() + '&type=' + $("#sf_guard_user_user_type").val(),
		          type: "POST",
		          data: {},
		          processResults: function (data, page) {
		              return {
		                results: data.items  
		              };
		          },
		      }).done(function(msg) {
				  $('#sf_guard_group_users_list').select2('val','');
		    	  $("#sf_guard_user_member_id").html(null);
		    	  $("#sf_guard_user_member_id").html(msg);
		    	  $("#sf_guard_user_member_id").attr('disabled', null);               
		      });
		}
  	});

    $('#sf_guard_user_member_id').change(function() {    	
		if ($("#sf_guard_user_ps_customer_id").val() > 0) {	    	
			$.ajax({
		        url: '<?php echo url_for('@ps_group_user_by_customer?cid=') ?>' + $("#sf_guard_user_ps_customer_id").val(),
		        type: 'POST',
		        data: 'cid=' + $("#sf_guard_user_ps_customer_id").val(),
		        success: function(data) {
		            $("#sf_guard_user_groups_list").attr('disabled', null);
					
		            $('#sf_guard_user_groups_list').html(data);		            		            
		        }
		    });			
	    } else {
	    	$('#sf_guard_user_groups_list').html(null);
	    	$('#sf_guard_user_groups_list').select2('val','');
	    	$("#sf_guard_user_groups_list").attr('disabled', 'disabled');
	    } 	
    });
	
	if ($("#sf_guard_user_user_type").val() != '<?php echo PreSchool::USER_TYPE_TEACHER?>') {		
		$("#sf_fieldset_groups_infomation, #sf_fieldset_features_infomation").attr('style', 'display:none');
	};    
});
</script>
