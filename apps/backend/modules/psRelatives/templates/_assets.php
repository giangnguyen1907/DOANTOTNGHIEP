<?php use_helper('I18N', 'Number') ?>
<?php //include_partial('global/include/_box_modal_messages');?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>

<script type="text/javascript">
var msg_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var PsMaxSizeFile = '<?php echo $app_upload_max_size;?>';

var URL_CHECKEMAIL = '<?php echo url_for('@relative_checkemail')?>';

var URL_CHECK_IDENTITYCARD = '<?php echo url_for('@relative_checkidentitycard')?>';

var msg_mobile_invalid 	= '<?php echo __('Mobile is not a valid')?>';

var msg_identity_card_invalid 	= '<?php echo __('The identity card can only consist of alphabetical, number.')?>';

var msg_identity_card_lenght 	= '<?php echo __('The identity card must be more than %s and less than %s characters long.')?>';

var msg_identity_card_exist 	= '<?php echo __('The identity card already exits.')?>';

var msg_email_exist 	= '<?php echo __('Email address already exist.')?>';

var msg_email_invalid 	= '<?php echo __('Invalid Email.')?>';

<?php if (myUser::credentialPsCustomers('PS_STUDENT_RELATIVE_FILTER_SCHOOL')):?>

$(document).ready(function() {
	
	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_first_name a").on("contextmenu",function(){
	    return false;
	});

	//BEGIN: filters
	// Load district for filter by ps_province_id
    $('#relative_filters_ps_province_id').change(function() {
		$("#relative_filters_ps_district_id").attr('disabled', 'disabled');
		$('#relative_filters_ps_district_id').select2('val','');
		$('#relative_filters_ps_ward_id').select2('val','');
		
		//$('#relative_filters_ps_customer_id').empty();
		$("#relative_filters_ps_customer_id option").remove();
		$('#relative_filters_ps_customer_id').select2('val','');
				
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
	    	$('#relative_filters_ps_district_id').select2('val','');
			$("#relative_filters_ps_district_id").html(msg);
			$("#relative_filters_ps_district_id").attr('disabled', null);
	    });
    });
	
	$('#relative_filters_ps_district_id').change(function() {
        $("#relative_filters_ps_ward_id").attr('disabled', 'disabled');
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
        	$('#relative_filters_ps_ward_id').select2('val','');
            $("#relative_filters_ps_ward_id").html(msg);
			$("#relative_filters_ps_ward_id").attr('disabled', null);
        });

      });

    $('#relative_filters_ps_ward_id').change(function() {
        $("#relative_filters_ps_customer_id").attr('disabled', 'disabled');
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
        	$('#relative_filters_ps_customer_id').select2('val','');
            $("#relative_filters_ps_customer_id").html(msg);
			$("#relative_filters_ps_customer_id").attr('disabled', null);
        });
    });

	$('#relative_filters_ps_customer_id, #relative_filters_school_year_id').change(function() {
		resetOptions('relative_filters_ps_class_id');
		$('#relative_filters_ps_class_id').select2('val','');
		
		if (($('#relative_filters_ps_customer_id').val() <= 0) && ($('#relative_filters_school_year_id').val() <= 0)) {
			return;
		}

		$("#relative_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_group_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#relative_filters_ps_customer_id').val() + '&w_id=' + $('#relative_filters_ps_workplace_id').val() + '&y_id=' + $('#relative_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#relative_filters_ps_class_id').select2('val','');
			$("#relative_filters_ps_class_id").html(msg);
			$("#relative_filters_ps_class_id").attr('disabled', null);
	    });
	});
		
	// END: filters
	
	$('#relative_ps_customer_id').change(function() {

		$("#relative_ps_workplace_id").attr('disabled', 'disabled');
		
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

	    	$('#relative_ps_workplace_id').select2('val','');

			$("#relative_ps_workplace_id").html(msg);

			$("#relative_ps_workplace_id").attr('disabled', null);

	    });
	});
	
	// Load district by province
//     $('#relative_ps_province_id').change(function() {
// 		$("#relative_ps_district_id").attr('disabled', 'disabled');
// 		if ($(this).val() > 0) {
		  
// 		  $.ajax({
//			  url: '<?php //echo url_for('@ps_districts_by_province?pid=') ?>//' + $(this).val(),
// 			  type: "POST",
// 			  data: {'pid': $(this).val()},
// 			  processResults: function (data, page) {
// 				  return {
// 					results: data.items
// 				  };
// 			  },
// 		  }).done(function(msg) {
// 			 $("#relative_ps_district_id").html(msg);
// 			 $("#relative_ps_district_id").attr('disabled', null);
// 		  });
		
// 		} else {
			
// 			$("#relative_ps_district_id").attr('disabled', 'disabled');
// 			$("#relative_ps_district_id").html(null);

// 		}
//     });

 	// Load ward by district
//     $('#relative_ps_district_id').change(function() {
//       $("#relative_ps_ward_id").attr('disabled', 'disabled');
// 	  if ($(this).val() > 0) {
// 		  $.ajax({
//			  url: '<?php //echo url_for('@ps_ward_by_district?did=') ?>//' + $(this).val(),
// 			  type: "POST",
// 			  data: {'did': $(this).val()},
// 			  processResults: function (data, page) {
// 				  return {
// 					results: data.items
// 				  };
// 			  },
// 		  }).done(function(msg) {
// 			 $("#relative_ps_ward_id").html(msg);
// 			 $("#relative_ps_ward_id").attr('disabled', null);
// 		  });
// 	  } else {
// 		  $("#relative_ps_district_id").attr('disabled', 'disabled');
// 		  $("#relative_ps_district_id").html(null);
// 	  }
//     });

 	// Load customer by ward
//     $('#relative_ps_ward_id').change(function() {
//       $("#relative_ps_customer_id").attr('disabled', 'disabled');
// 	  $.ajax({
//          url: '<?php //echo url_for('@ps_customer_by_ps_ward?wid=') ?>//' + $(this).val(),
//           type: "POST",
//           data: {'wid': $(this).val()},
//           processResults: function (data, page) {
//               return {
//                 results: data.items
//               };
//           },
//       }).done(function(msg) {
//          $("#relative_ps_customer_id").html(msg);
// 		 $("#relative_ps_customer_id").attr('disabled', null);
//       });
//     });
	
	// Lay co so dao tao theo nha truong
// 	$('#relative_ps_customer_id').change(function() {
		
// 		$("#relative_ps_workplace_id").attr('disabled', 'disabled');
		
// 		$.ajax({
//	        url: '<?php //echo url_for('@ps_work_places_by_customer?psc_id=') ?>//' + $(this).val(),
// 	        type: 'POST',
//	        data: 'f=<?php //echo md5(time().time().time().time())?>//&psc_id=' + $(this).val(),
// 	        success: function(data) {
// 	        	$("#relative_ps_workplace_id").val(null).trigger("change");
// 	        	$('#relative_ps_workplace_id').html(data);
// 				$("#relative_ps_workplace_id").attr('disabled', null);
// 	        }
// 		});
// 	});
    
});
<?php endif;?>
</script>
<script type="text/javascript">
$(document).ready(function() {
    //Statistic
 	
    $('#member_statistic_filter_ps_customer_id').change(function() {
    
    	resetOptions('member_statistic_filter_ps_workplace_id');
    	$('#member_statistic_filter_ps_workplace_id').select2('val','');
    
    	if ($('#member_statistic_filter_ps_customer_id').val() <= 0) {
    		return;
    	}
    
    	$("#member_statistic_filter_ps_workplace_id").attr('disabled', 'disabled');
    	
    	$.ajax({
            url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
            type: 'POST',
            data: 'psc_id=' + $(this).val(),
            success: function(data) {
    	        
            	$('#member_statistic_filter_ps_workplace_id').select2('val','');
            	$('#member_statistic_filter_ps_workplace_id').html(data);
    			$("#member_statistic_filter_ps_workplace_id").attr('disabled', null);
    
    			resetOptions('member_statistic_filter_ps_class_id');
    			$('#member_statistic_filter_ps_class_id').select2('val','');
    			
    			if ($('#member_statistic_filter_ps_workplace_id').val() <= 0) {
    				return;
    			}
    
    			$("#member_statistic_filter_ps_class_id").attr('disabled', 'disabled');
    			
    			$.ajax({
    				url: '<?php echo url_for('@ps_class_by_params') ?>',
    		        type: "POST",
    		        data: 'c_id=' + $('#member_statistic_filter_ps_customer_id').val() + '&w_id=' + $('#member_statistic_filter_ps_workplace_id').val() + '&y_id=' + $('#member_statistic_filter_school_year_id').val(),
    		        processResults: function (data, page) {
    	          		return {
    	            		results: data.items
    	          		};
    	        	},
    		    }).done(function(data) {
    		    		$('#member_statistic_filter_ps_class_id').select2('val','');
    		        	$('#member_statistic_filter_ps_class_id').html(data);
    					$("#member_statistic_filter_ps_class_id").attr('disabled', null);
    			});
            }
    	});
    });
    
    $('#member_statistic_filter_ps_workplace_id').change(function() {
    
    	resetOptions('member_statistic_filter_ps_class_id');
    	$('#member_statistic_filter_ps_class_id').select2('val','');
    
    	if ($('#member_statistic_filter_ps_workplace_id').val() <= 0) {
    		return;
    	}
    	
    	$("#member_statistic_filter_ps_class_id").attr('disabled', 'disabled');
    	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#member_statistic_filter_ps_customer_id').val() + '&w_id=' + $('#member_statistic_filter_ps_workplace_id').val() + '&y_id=' + $('#member_statistic_filter_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(data) {
        		$('#member_statistic_filter_ps_class_id').select2('val','');
            	$('#member_statistic_filter_ps_class_id').html(data);
    			$("#member_statistic_filter_ps_class_id").attr('disabled', null);
    	});
    });
});
// END Statictis
</script>
<script>
// khong cho nhap chu
function keyPhone(e)
{
var keyword=null;
    if(window.event)
    {
    keyword=window.event.keyCode;
    }else
    {
        keyword=e.which; //NON IE;
    }
    
    if(keyword<48 || keyword>57)
    {
        if(keyword==48 || keyword==127)
        {
            return ;
        }
        return false;
    }
}
// chi gioi han nhap 10 so
function change (el) {
	var max_len = 10;
	if (el.value.length > max_len) {
	el.value = el.value.substr(0, max_len);
	}
	document.getElementById('char_cnt').innerHTML = el.value.length;
	document.getElementById('chars_left').innerHTML = max_len - el.value.length;
	return true;
	}
</script>
<?php include_partial('global/include/_box_modal') ?>