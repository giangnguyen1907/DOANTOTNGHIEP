<?php use_helper('I18N', 'Date') ?>
<?php $app_upload_max_size = sfConfig::get('app_upload_max_size');?>
<script type="text/javascript">
var msg_file_invalid 	= '<?php echo __( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array ('%value%' => $app_upload_max_size))?>';

var PsMaxSizeFile = '<? echo $app_upload_max_size;?>';

$(document).ready(function() {

	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_CAMERA_FILTER_SCHOOL')):?>
	
	$('#ps_camera_filters_ps_customer_id').change(function() {
		
		$("#ps_camera_filters_ps_workplace_id").attr('disabled', 'disabled');
				
		resetOptions('ps_camera_filters_ps_class_room_id');

		$("#ps_camera_filters_ps_class_room_id").attr('disabled','disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_camera_filters_ps_workplace_id').select2('val','');
				$('#ps_camera_filters_ps_workplace_id').html(data);
				$("#ps_camera_filters_ps_workplace_id").attr('disabled', null);
	        }
		});
		
		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'psc_id=' + $('#ps_camera_filters_ps_customer_id').val() + '&wp_id=' + $('#ps_camera_filters_ps_workplace_id').val(),
	        success: function(data) {
	        	$('#ps_camera_filters_ps_class_room_id').select2('val','');
				$('#ps_camera_filters_ps_class_room_id').html(data);
				$("#ps_camera_filters_ps_class_room_id").attr('disabled',null);				
	        }
		});
	});

	<?php endif;?>
	
	// Load classroom of workplace
	$('#ps_camera_filters_ps_workplace_id').change(function() {
		
		$("#ps_camera_filters_ps_class_room_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&wp_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_camera_filters_ps_class_room_id').select2('val','');
				$('#ps_camera_filters_ps_class_room_id').html(data);
				$("#ps_camera_filters_ps_class_room_id").attr('disabled',null);				
	        }
		});		 	
	});
	// END: filters
	
	// Load district by province
    $('#ps_camera_ps_province_id').change(function() {

		$("#ps_camera_ps_district_id").attr('disabled', 'disabled');

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
			 $("#ps_camera_ps_district_id").html(msg);               
			 $("#ps_camera_ps_district_id").attr('disabled', null);
		  });		
		} else {
			$("#ps_camera_ps_district_id").attr('disabled', 'disabled');
			$("#ps_camera_ps_district_id").html(null);
		}
    });

 	// Load ward by district
    $('#ps_camera_ps_district_id').change(function() {

      $("#ps_camera_ps_ward_id").attr('disabled', 'disabled');
      
      resetOptions('ps_camera_ps_customer_id');
      
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
			 $('#ps_camera_ps_ward_id').select2('val','');
		     $("#ps_camera_ps_ward_id").html(msg);               
			 $("#ps_camera_ps_ward_id").attr('disabled', null);
		  });
	  } else {
		  $("#ps_camera_ps_district_id").attr('disabled', 'disabled');
		  $("#ps_camera_ps_district_id").html(null);
	  }
    });

 	// Load customer by ward
    $('#ps_camera_ps_ward_id').change(function() {

    	$("#ps_camera_ps_customer_id").attr('disabled', 'disabled');

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
         $("#ps_camera_ps_customer_id").html(msg); 
		 $("#ps_camera_ps_customer_id").attr('disabled', null);
      });
    });
    	
	// Load ps_workplace
	$('#ps_camera_ps_customer_id').change(function() {
		
		$("#ps_camera_ps_workplace_id").attr('disabled', 'disabled');

		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_camera_ps_workplace_id').select2('val','');
				$('#ps_camera_ps_workplace_id').html(data);
				$("#ps_camera_ps_workplace_id").attr('disabled', null);				
	        }
		});		 	
	});
	
	// Load classroom of workplace
	$('#ps_camera_ps_workplace_id').change(function() {
		
		$("#ps_camera_ps_class_room_id").attr('disabled', 'disabled');

		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&wp_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_camera_ps_class_room_id').select2('val','');
				$('#ps_camera_ps_class_room_id').html(data);
				$("#ps_camera_ps_class_room_id").attr('disabled',null);				
	        }
		});		 	
	});
	
});
</script>
