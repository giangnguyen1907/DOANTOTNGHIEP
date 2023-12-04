<?php use_helper('I18N', 'Number') ?>
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

	$('#sf_guard_user_department_type').change(function() {
	      
		if ($(this).val() == '<?php echo PreSchool::MANAGER_TYPE_PROVINCIAL?>') {

			$('#sf_guard_user_ps_district_id').select2('val','');			
			$("#sf_guard_user_ps_district_id").attr('disabled', 'disabled');
					
		} else {
			
			$("#sf_guard_user_ps_district_id").attr('disabled', null);
			//$("#sf_guard_user_ps_district_id").html(null);
		}
    });

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
    
});
</script>

