<?php use_helper('I18N', 'Date') ?>
<style>
<!--
#sf_fieldset_class_infomation { /*float: left;width: 50%;*/
	
}

#sf_fieldset_class_services, #sf_fieldset_class_members {
	/*float: left;width: 25%;*/
	
}
-->
</style>

<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>

<script type="text/javascript">
$(document).ready(function() {

	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_name a").on("contextmenu",function(){
	       return false;
	});	
	
	// Load district for filter by ps_province_id
	$('#my_class_filters_ps_customer_id').change(function() {
		
		$("#my_class_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#my_class_filters_ps_workplace_id').select2('val','');
				$('#my_class_filters_ps_workplace_id').html(data);
				$("#my_class_filters_ps_workplace_id").attr('disabled', null);
				
	        }
		});
	});
	// END: filters
	
	// Load district by province
    $('#my_class_ps_province_id').change(function() {
		
		$("#my_class_ps_district_id").attr('disabled', 'disabled');
		
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
			 $("#my_class_ps_district_id").html(msg);
			 $("#my_class_ps_district_id").attr('disabled', null);
		  });
		
		} else {
			
			$("#my_class_ps_district_id").attr('disabled', 'disabled');
			$("#my_class_ps_district_id").html(null);

		}
    });

 	// Load ward by district
    $('#my_class_ps_district_id').change(function() {
      
	  $("#my_class_ps_ward_id").attr('disabled', 'disabled');
	  
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
			 $("#my_class_ps_ward_id").html(msg);
			 $("#my_class_ps_ward_id").attr('disabled', null);
		  });
	  } else {
		  $("#my_class_ps_district_id").attr('disabled', 'disabled');
		  $("#my_class_ps_district_id").html(null);
	  }
    });

 	// Load customer by ward
    $('#my_class_ps_ward_id').change(function() {
      
	  $("#my_class_ps_customer_id").attr('disabled', 'disabled');
	  
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
         $("#my_class_ps_customer_id").html(msg);
		 $("#my_class_ps_customer_id").attr('disabled', null);
      });
    });
	
	// Load ps_workplace
	$('#my_class_ps_customer_id').change(function() {
		
		$("#my_class_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#my_class_ps_workplace_id').select2('val','');
				$('#my_class_ps_workplace_id').html(data);
				$("#my_class_ps_workplace_id").attr('disabled', null);
			}
		});
	});
	
	// Load classroom of workplace
	$('#my_class_ps_workplace_id').change(function() {

		$("#my_class_ps_class_room_id").attr('disabled', 'disabled');

		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&wp_id=' + $(this).val(),
	        success: function(data) {
	        	$('#my_class_ps_class_room_id').select2('val','');
				$('#my_class_ps_class_room_id').html(data);
				$("#my_class_ps_class_room_id").attr('disabled',null);
	        }
		});
	});
	
	$('.btn-delete-item ').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete').attr('action', '<?php echo url_for('@ps_teacher_class')?>/' + item_id);
	});

	$('.btn-delete-class').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-class').attr('action', '<?php echo url_for('@ps_student_class')?>/' + item_id);
	});
	
});
</script>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('psClass/box_modal_confirm_remover_teacher') ?>
