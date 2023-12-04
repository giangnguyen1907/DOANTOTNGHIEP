<?php use_helper('I18N', 'Number')?>
<script>
var URL_CHECKSCHEDULES = '<?php echo url_for('@ps_course_schedules_checkschedules')?>';

var msg_ps_customer_id_invalid = '<?php echo __('Please select School to filter the data.', array(), 'messages') ?>';

var url_ps_course_schedules_week = '<?php echo url_for('@ps_course_schedules_week');?>';

$(document).ready(function() {

	<?php if (myUser::credentialPsCustomers('PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL')):?>
	
	// Load ps_workplace
	$('#ps_service_course_schedules_ps_customer_id').change(function() {
		
		$("#ps_service_course_schedules_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('ps_service_course_schedules_ps_service_course_id');
		$('#ps_service_course_schedules_ps_service_courses_id').select2('val','');

		if ($('#ps_service_course_schedules_ps_customer_id').val() <= 0) {
			return;
		}
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        async: false,
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_service_course_schedules_ps_workplace_id').select2('val','');
				$('#ps_service_course_schedules_ps_workplace_id').html(data);
				$("#ps_service_course_schedules_ps_workplace_id").attr('disabled', null);
			}
		});
		
		$.ajax({
	        url: '<?php echo url_for('@ps_courses_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
		    async: false,
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_service_course_schedules_ps_service_course_id').select2('val','');
				$('#ps_service_course_schedules_ps_service_course_id').html(data);
				$("#ps_service_course_schedules_ps_service_course_id").attr('disabled', null);
			}
		});
	});
	
    <?php endif;?>

	// Load classroom of workplace
	$('#ps_service_course_schedules_ps_workplace_id').change(function() {
		resetOptions('ps_service_course_schedules_ps_class_room_id');
		$('#ps_service_course_schedules_ps_class_room_id').select2('val','');

		if ($('#ps_service_course_schedules_ps_workplace_id').val() <= 0) {
			return;
		}
		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&wp_id=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_service_course_schedules_ps_class_room_id').select2('val','');
				$('#ps_service_course_schedules_ps_class_room_id').html(data);
				$("#ps_service_course_schedules_ps_class_room_id").attr('disabled',null);
	        }
		});
	});
	
	 $('#course_schedules_filter_ps_year').change(function() {

	    	$("#course_schedules_filter_ps_week").attr('disabled', 'disabled');
	    	resetOptions('course_schedules_filter_ps_week');
	    	
	    	$.ajax({
	            url: '<?php echo url_for('@ps_menus_weeks_year') ?>',
	            type: "POST",
	            data: {'ps_year': $(this).val()},
	            processResults: function (data, page) {
	                return {
	                  results: data.items  
	                };
	            },
	  		}).done(function(msg) {
	  			 $('#course_schedules_filter_ps_week').select2('val','');
	 			 $("#course_schedules_filter_ps_week").html(msg);
	  			 $("#course_schedules_filter_ps_week").attr('disabled', null);

	  			$('#course_schedules_filter_ps_week').val(1);
				$('#course_schedules_filter_ps_week').change();
	  		});
	    	
	    });

    $('#course_schedules_filter_ps_customer_id, #course_schedules_filter_ps_workplace_id, #course_schedules_filter_ps_week, #course_schedules_filter_ps_class_room_id, #course_schedules_filter_ps_service_id, #course_schedules_filter_ps_service_course_id, #course_schedules_filter_ps_member_id').change(function() {

    	$("#ic-loading").show();
    	$("#tbl-menu").html('');
    	
    	$.ajax({
	          url: '<?php echo url_for('@ps_course_schedules_week');?>',
	          type: "POST",
	          data: $("#psnew-filter").serialize(),
	          processResults: function (data, page) {
	              return {
	                results: data.items  
	              };
	          },
			}).done(function(msg) {
				 $("#ic-loading").hide();
				 $("#tbl-menu").html(msg);				 	
			});
    	
    });
    $('#course_schedules_filter_ps_customer_id').change(function() {

        	resetOptions('course_schedules_filter_ps_workplace_id');
    		$('#course_schedules_filter_ps_workplace_id').select2('val','');
    		$('#course_schedules_filter_ps_workplace_id').change();
		
        	resetOptions('course_schedules_filter_ps_service_id');
    		$('#course_schedules_filter_ps_service_id').select2('val','');
    
    		resetOptions('course_schedules_filter_ps_member_id');
    		$('#course_schedules_filter_ps_member_id').select2('val','');
    		
    		if ($('#course_schedules_filter_ps_customer_id').val() <= 0) {
    			return;
    		}
    		
    		$("#course_schedules_filter_ps_service_id").attr('disabled', 'disabled');
			
    		$.ajax({
    	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
    	        type: 'POST',
    	        async: false,
    	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
    	        success: function(data) {
    	        	$('#course_schedules_filter_ps_workplace_id').select2('val','');
    				$('#course_schedules_filter_ps_workplace_id').html(data);
    				$("#course_schedules_filter_ps_workplace_id").attr('disabled', null);
    			}
    		});
    		
    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_by_ps_customer') ?>',
    	        type: "POST",
    	        async: false,
    	        data: {'psc_id': $(this).val()},
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#course_schedules_filter_ps_service_id').select2('val','');
    			$("#course_schedules_filter_ps_service_id").html(msg);
    			$("#course_schedules_filter_ps_service_id").attr('disabled', null);
    	    });
    	    
    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_member') ?>',
    	        type: "POST",
    	    	async: false,
    	        data: {'psc_id': $(this).val()},
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#course_schedules_filter_ps_member_id').select2('val','');
    			$("#course_schedules_filter_ps_member_id").html(msg);
    			$("#course_schedules_filter_ps_member_id").attr('disabled', null);
    	    });	    
    		
    	});

    $('#course_schedules_filter_ps_workplace_id').change(function() {

    	
		resetOptions('course_schedules_filter_ps_class_room_id');
		$('#course_schedules_filter_ps_class_room_id').select2('val','');

		if ($('#course_schedules_filter_ps_workplace_id').val() <= 0) {			
			return;
		}
		
		$.ajax({
	        url: '<?php echo url_for('@ps_class_room_by_ps_workplace?wp_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&wp_id=' + $(this).val(),
	        success: function(data) {
	        	$('#course_schedules_filter_ps_class_room_id').select2('val','');
				$('#course_schedules_filter_ps_class_room_id').html(data);
				$("#course_schedules_filter_ps_class_room_id").attr('disabled',null);
	        }
		});
	});

    $('#course_schedules_filter_ps_service_id').change(function() {
    
        	resetOptions('course_schedules_filter_ps_service_course_id');
    		$('#course_schedules_filter_ps_service_course_id').select2('val','');
    		$("#course_schedules_filter_ps_service_course_id").attr('disabled','disabled');
    	
    		if ($('#course_schedules_filter_ps_service_id').val() <= 0) {        			
    			return;
    		}
    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_course_by_ps_service?sid=') ?>' + $(this).val(),
    	        type: 'POST',
    	        data: 'f=<?php echo md5(time().time().time().time())?>sid=' + $(this).val(),
    	        success: function(data) {
    	        	$('#course_schedules_filter_ps_service_course_id').select2('val','');
    				$('#course_schedules_filter_ps_service_course_id').html(data);
    				$("#course_schedules_filter_ps_service_course_id").attr('disabled',null);
    	        }
    		});
    });
	
	$('#btn-copy-menu').click(function() {

			var count_list_course_schedules = parseInt($('#count_list_course_schedules').val());;
			
			var ps_customer_id = parseInt($('#course_schedules_filter_ps_customer_id').val());

			var ps_class_room_id = $('#course_schedules_filter_ps_class_room_id').val();
			var ps_service_id = $('#course_schedules_filter_ps_service_id').val();
			var ps_member_id = parseInt($('#course_schedules_filter_ps_member_id').val());
			var ps_service_course_id = parseInt($('#course_schedules_filter_ps_service_course_id').val());

			if (isNaN(ps_customer_id)){

				$("#errors").html("<?php echo __('You have not selected a school')?>");

			    $('#warningModal').modal({show: true,backdrop:'static'});
				
				return false;
			}
			
			else if (isNaN(ps_service_course_id)){

				$("#errors").html("<?php echo __('You have not selected a course')?>");

			    $('#warningModal').modal({show: true,backdrop:'static'});
				
				return false;
			}
			

			if (count_list_course_schedules <= 0 ){
				$("#errors").html("<?php echo __('The current week has no data')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});				
				return false;
			}
			
			var current_week = parseInt($('#course_schedules_filter_ps_week').val());
			var year = parseInt($('#course_schedules_filter_ps_year').val());
			var ps_class_room_id = $('#course_schedules_filter_ps_class_room_id').val();
			var ps_service_id = $('#course_schedules_filter_ps_service_id').val();
			var ps_member_id = $('#course_schedules_filter_ps_member_id').val();
			var ps_service_course_id = $('#course_schedules_filter_ps_service_course_id').val();
			
			<?php $ps_week = PsDateTime::getIndexWeekOfYear(date('Y-m-d'));?>
			
			$('#week_source').val(current_week);
			$('#week_source').change();
			$('#form_ps_customer_id').val(ps_customer_id);
			$('#form_ps_customer_id').change();	
			$('#form_ps_week_source').val(current_week);
			$('#form_ps_week_source').change();
			$('#form_ps_year_source').val(year);
			$('#form_ps_year_source').change();
			$('#form_ps_week_destination').val(<?php echo $ps_week ?> + 1);
 			$('#form_ps_week_destination').change();
 			$('#form_ps_class_room_id').val(ps_class_room_id);
			$('#form_ps_class_room_id').change();
			$('#form_ps_service_id').val(ps_service_id);
			$('#form_ps_service_id').change();
			$('#form_ps_member_id').val(ps_member_id);
			$('#form_ps_member_id').change();
			$('#form_ps_service_course_id').val(ps_service_course_id);
			$('#form_ps_service_course_id').change();
		
			
		});


	    $('#form_ps_year_destination').change(function() {

	    	$("#form_ps_week_destination").attr('disabled', 'disabled');
	    	resetOptions('form_ps_week_destination');
	    	
	    	$.ajax({
	            url: '<?php echo url_for('@ps_menus_weeks_year') ?>',
	            type: "POST",
	            data: {'ps_year': $(this).val()},
	            processResults: function (data, page) {
	                return {
	                  results: data.items  
	                };
	            },
	  		}).done(function(msg) {
	  			 $('#form_ps_week_destination').select2('val','');
	 			 $("#form_ps_week_destination").html(msg);
	  			 $("#form_ps_week_destination").attr('disabled', null);

	  			$('#form_ps_week_destination').val(1);
				$('#form_ps_week_destination').change();
	  		});
	    });

		$('#btn-prev').click(function() {

			// tuan hien tai
			var current_week = parseInt($('#course_schedules_filter_ps_week').val());

			// tong so tuan cua nam hien tai
			var total_week = parseInt($('#course_schedules_filter_ps_number_week').val());

			if( current_week == 1) {
				$("#errors").html("<?php echo __('This is the first week of the year. Please select the previous year.')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});
			    return false;
			    
			} else {

				$('#course_schedules_filter_ps_week').val(current_week - 1);
				
				$("#ic-loading").show();
		    	$("#tbl-menu").html('');

				$.ajax({
			          url: '<?php echo url_for('@ps_course_schedules_week');?>',
			          type: "POST",
			          data: $("#psnew-filter").serialize(),
			          processResults: function (data, page) {
			              return {
			                results: data.items  
			              };
			          },
				}).done(function(msg) {
					$('#course_schedules_filter_ps_week').change();
					$("#ic-loading").hide();
					$("#tbl-menu").html(msg);				 	
				});
			}			
	    });
	
		$('#btn-next').click(function() {

			// tuan hien tai
			var current_week = parseInt($('#course_schedules_filter_ps_week').val());

			// tong so tuan cua nam hien tai
			var total_week = parseInt($('#course_schedules_filter_ps_number_week').val());

			if( current_week == total_week) {
				
				$("#errors").html("<?php echo __('This is the last week of the year. Please select the next year.')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});
			    return false;			    
			} else {
				
				$('#course_schedules_filter_ps_week').val(current_week + 1);
				$("#ic-loading").show();
		    	$("#tbl-menu").html('');

				$.ajax({
			          url: '<?php echo url_for('@ps_course_schedules_week');?>',
			          type: "POST",
			          data: $("#psnew-filter").serialize(),
			          processResults: function (data, page) {
			              return {
			                results: data.items  
			              };
			          },
				}).done(function(msg) {
					$('#course_schedules_filter_ps_week').change();
					$("#ic-loading").hide();
					$("#tbl-menu").html(msg);
				});
			}						
		});
	});
</script>


