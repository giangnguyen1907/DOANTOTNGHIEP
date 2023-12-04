<?php use_helper('I18N', 'Number') ?>
<style>
.select2-container .select2-choice img.img-flag {
	height: 20px !important;
	width: 20px !important;
}
</style>
<script>
var URL_CHECKEMENU = '<?php echo url_for('@ps_menus_checkmenu')?>';

var msg_ps_customer_id_invalid = '<?php echo __('Please select School to filter the data.', array(), 'messages') ?>';

var url_ps_menus_week = '<?php echo url_for('@ps_menus_week');?>';

$(document).ready(function() {

	// Lay co so dao tao theo nha truong
	$('#ps_menus_filters_ps_customer_id').change(function() {
		
		$("#ps_menus_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_menus_filters_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_menus_filters_ps_workplace_id').html(data);
				$("#ps_menus_filters_ps_workplace_id").attr('disabled', null);
	        }
		});
	});

	$('#ps_menus_ps_workplace_id').change(function() {

		resetOptions('ps_menus_ps_meal_id');
		$("#ps_menus_ps_meal_id").attr('disabled', 'disabled');
	   	
	   	var cid = $('#ps_menus_ps_customer_id').val();

		$.ajax({
			url: '<?php echo url_for('@ps_meals_by_ps_customer?cid=') ?>' + cid + '&wp_id=' + $('#ps_menus_ps_workplace_id').val(),
	        type: "POST",
	        data: 'cid=' + cid  + '&wp_id=' + $('#ps_menus_ps_workplace_id').val(),
	        processResults: function (data, page) {
              return {
                results: data.items  
              };
	          },
		}).done(function(msg) {
			 $("#ps_menus_ps_meal_id").html(msg); 
			 $("#ps_menus_ps_meal_id").attr('disabled', null);		 
		});

	});
	
	<?php if (myUser::credentialPsCustomers('PS_NUTRITION_MENUS_FILTER_SCHOOL')):?>

	
    $('#ps_menus_ps_customer_id').change(function() {      

    	resetOptions('ps_menus_ps_workplace_id');
    	
    	$("#ps_menus_ps_workplace_id").attr('disabled', 'disabled');

    	$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_menus_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_menus_ps_workplace_id').html(data);
				$("#ps_menus_ps_workplace_id").attr('disabled', null);
	        }
		});

		$("#ps_menus_ps_meal_id").attr('disabled', 'disabled');
        
        resetOptions('ps_menus_ps_meal_id');

        $("#ps_menus_ps_food_id").attr('disabled', 'disabled');
        
        resetOptions('ps_menus_ps_food_id');

        var cid = $(this).val();

		$.ajax({
			url: '<?php echo url_for('@ps_meals_by_ps_customer?cid=') ?>' + cid,
			type: "POST",
			data: {'cid': cid},
			processResults: function (data, page) {
				return {
				  results: data.items  
				};
			},
		}).done(function(msg) {
			$("#ps_menus_ps_meal_id").html(msg); 
		   	$("#ps_menus_ps_meal_id").attr('disabled', null);

			$.ajax({
	          url: '<?php echo url_for('@ps_foods_by_ps_customer?cid=') ?>' + cid,
	          type: "POST",
	          data: {'cid': cid},
	          processResults: function (data, page) {
	              return {
	                results: data.items  
	              };
	          },
			}).done(function(msg) {
				 $("#ps_menus_ps_food_id").html(msg); 
				 $("#ps_menus_ps_food_id").attr('disabled', null);		 
			});		   	
		});				
    });
    <?php endif;?>

    $('#menus_filter_ps_year').change(function() {

    	$("#menus_filter_ps_week").attr('disabled', 'disabled');
    	resetOptions('menus_filter_ps_week');
    	
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
  			 $('#menus_filter_ps_week').select2('val','');
 			 $("#menus_filter_ps_week").html(msg);
  			 $("#menus_filter_ps_week").attr('disabled', null);

  			$('#menus_filter_ps_week').val(1);
			$('#menus_filter_ps_week').change();
  		});
    });

    $('#menus_filter_ps_customer_id').change(function() {

		resetOptions('menus_filter_ps_workplace_id');
    	
    	$("#menus_filter_ps_workplace_id").attr('disabled', 'disabled');

    	$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#menus_filter_ps_workplace_id").val(null).trigger("change");
	        	$('#menus_filter_ps_workplace_id').html(data);
				$("#menus_filter_ps_workplace_id").attr('disabled', null);
	        }
		});
    });

    $('#menus_filter_ps_customer_id, #menus_filter_ps_workplace_id ,#menus_filter_ps_week, #menus_filter_ps_object_group_id').change(function() {

    	$("#ic-loading").show();
    	$("#tbl-menu").html('');
    	
    	$.ajax({
	          url: '<?php echo url_for('@ps_menus_week');?>',
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

	$('#btn-copy-menu').click(function() {

			var count_list_menus = parseInt($('#count_list_menus').val());;
			
			var ps_customer_id = parseInt($('#menus_filter_ps_customer_id').val());

			var ps_workplace_id = parseInt($('#menus_filter_ps_workplace_id').val());

			if (isNaN(ps_customer_id)){

				$("#errors").html("<?php echo __('You have not selected a school')?>");

			    $('#warningModal').modal({show: true,backdrop:'static'});
				
				return false;
			}

			if (count_list_menus <= 0 ){
				$("#errors").html("<?php echo __('The current week has no data')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});				
				return false;
			}
			
			var current_week = parseInt($('#menus_filter_ps_week').val());
			var year = parseInt($('#menus_filter_ps_year').val());
			<?php $ps_week = PsDateTime::getIndexWeekOfYear(date('Y-m-d'));?>
			var ps_object_group_id = parseInt($('#menus_filter_ps_object_group_id').val());
			
			$('#week_source').val(current_week);
			$('#week_source').change();
			$('#form_ps_customer_id').val(ps_customer_id);
			$('#form_ps_customer_id').change();

			$('#form_ps_workplace_id').val(ps_workplace_id);
			$('#form_ps_workplace_id').change();

			$('#form_ps_object_group_id').val(ps_object_group_id);
			$('#form_ps_object_group_id').change();
			
			$('#form_ps_week_source').val(current_week);
			$('#form_ps_week_source').change();
			
			$('#form_ps_year_source').val(year);
			$('#form_ps_year_source').change();
			
			$('#form_ps_week_destination').val(<?php echo $ps_week ?> + 1);
 			$('#form_ps_week_destination').change();
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
			var current_week = parseInt($('#menus_filter_ps_week').val());

			// tong so tuan cua nam hien tai
			var total_week = parseInt($('#menus_filter_ps_number_week').val());

			if( current_week == 1) {
				$("#errors").html("<?php echo __('This is the first week of the year. Please select the previous year.')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});
			    return false;
			    
			} else {

				$('#menus_filter_ps_week').val(current_week - 1);
				
				$("#ic-loading").show();
		    	$("#tbl-menu").html('');

				$.ajax({
			          url: '<?php echo url_for('@ps_menus_week');?>',
			          type: "POST",
			          data: $("#psnew-filter").serialize(),
			          processResults: function (data, page) {
			              return {
			                results: data.items  
			              };
			          },
				}).done(function(msg) {
					$('#menus_filter_ps_week').change();
					$("#ic-loading").hide();
					$("#tbl-menu").html(msg);				 	
				});
			}			
	    });
	
		$('#btn-next').click(function() {

			// tuan hien tai
			var current_week = parseInt($('#menus_filter_ps_week').val());

			// tong so tuan cua nam hien tai
			var total_week = parseInt($('#menus_filter_ps_number_week').val());

			if( current_week == total_week) {
				
				$("#errors").html("<?php echo __('This is the last week of the year. Please select the next year.')?>");
			    $('#warningModal').modal({show: true,backdrop:'static'});
			    return false;			    
			} else {
				
				$('#menus_filter_ps_week').val(current_week + 1);
				$("#ic-loading").show();
		    	$("#tbl-menu").html('');

				$.ajax({
			          url: '<?php echo url_for('@ps_menus_week');?>',
			          type: "POST",
			          data: $("#psnew-filter").serialize(),
			          processResults: function (data, page) {
			              return {
			                results: data.items  
			              };
			          },
				}).done(function(msg) {
					$('#menus_filter_ps_week').change();
					$("#ic-loading").hide();
					$("#tbl-menu").html(msg);
				});
			}						
		});
	});
</script>


