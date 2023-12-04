<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>
<?php
// Su dung bien global
// sfConfig::set('enableRollText', PreSchool::loadPsRoll());
?>
<style>
<!--
#ui-datepicker-div {
	z-index: 100 !important;;
}
-->
</style>

<?php if (myUser::credentialPsCustomers('PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL')):?>
<script type="text/javascript">

$(document).ready(function() {

	$(".widget-body-toolbar a").on("contextmenu",function(){
	       return false;
	});

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('.btn-delete-student').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#student_service_id').val(item_id);
	});

// filter
		//Filter bao cao
    	$('#student_service_filter_ps_customer_id').change(function() {

    		resetOptions('student_service_filter_ps_workplace_id');
    		$('#student_service_filter_ps_workplace_id').select2('val','');
    			
    		resetOptions('student_service_filter_ps_service_id');
    		$('#student_service_filter_ps_service_id').select2('val','');
    
    		if ($('#student_service_filter_ps_customer_id').val() <= 0) {
    			return;
    		}
    		$("#student_service_filter_ps_service_id").attr('disabled', 'disabled');

    		$("#student_service_filter_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#student_service_filter_ps_workplace_id').select2('val','');

				$("#student_service_filter_ps_workplace_id").html(msg);

				$("#student_service_filter_ps_workplace_id").attr('disabled', null);
				  
        		$.ajax({
        	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
        	        type: "POST",
        	        data: 'c_id='+ $('#student_service_filter_ps_customer_id').val() + '&y_id=' + $('#student_service_filter_school_year_id').val() + '&w_id=' + $("#student_service_filter_ps_workplace_id").val(),
        	        processResults: function (data, page) {
                  		return {
                    		results: data.items
                  		};
                	},
        	    }).done(function(msg) {
        	    	$('#student_service_filter_ps_service_id').select2('val','');
        			$("#student_service_filter_ps_service_id").html(msg);
        			$("#student_service_filter_ps_service_id").attr('disabled', null);
        	    });
        	  
		    });
		    
        });

    	$('#student_service_filter_ps_workplace_id').change(function() {

    		resetOptions('student_service_filter_ps_service_id');
    		$('#student_service_filter_ps_service_id').select2('val','');
    
    		if ($('#student_service_filter_ps_workplace_id').val() <= 0) {
    			return;
    		}
    		$("#student_service_filter_ps_service_id").attr('disabled', 'disabled');

    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id='+ $('#student_service_filter_ps_customer_id').val() + '&y_id=' + $('#student_service_filter_school_year_id').val() + '&w_id=' + $("#student_service_filter_ps_workplace_id").val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#student_service_filter_ps_service_id').select2('val','');
    			$("#student_service_filter_ps_service_id").html(msg);
    			$("#student_service_filter_ps_service_id").attr('disabled', null);
    	    });
    	  
	    });

    	$('#student_service_filter_school_year_id').change(function() {

    		resetOptions('student_service_filter_ps_service_id');
    		$('#student_service_filter_ps_service_id').select2('val','');
    
    		if ($('#student_service_filter_ps_workplace_id').val() <= 0) {
    			return;
    		}
    		$("#student_service_filter_ps_service_id").attr('disabled', 'disabled');

    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id='+ $('#student_service_filter_ps_customer_id').val() + '&y_id=' + $('#student_service_filter_school_year_id').val() + '&w_id=' + $("#student_service_filter_ps_workplace_id").val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#student_service_filter_ps_service_id').select2('val','');
    			$("#student_service_filter_ps_service_id").html(msg);
    			$("#student_service_filter_ps_service_id").attr('disabled', null);
    	    });
    	  
	    });
	    
//     	$('#student_service_filter_ps_service_id').change(function() {
			
//     		resetOptions('student_service_filter_course');
//     		$('#student_service_filter_course').select2('val','');
    
//     		if ($('#student_service_filter_ps_service_id').val() <= 0) {
//     			return;
//     		}
//     		$("#student_service_filter_course").attr('disabled', 'disabled');
//     		$.ajax({
//    	        url: '<?php //echo url_for('@ps_service_course_by_ps_service') ?>',
//     	        type: "POST",
//     	        data: {'sid': $(this).val()},
//     	        processResults: function (data, page) {
//               		return {
//                 		results: data.items
//               		};
//             	},
//     	    }).done(function(msg) {
//     	    	$('#student_service_filter_course').select2('val','');
//     			$("#student_service_filter_course").html(msg);
//     			$("#student_service_filter_course").attr('disabled', null);
//     	    });
    		
//         });
		//End filter bao cao
		        
		 $('#ps_service_courses_filters_ps_customer_id').change(function() {

			 	resetOptions('ps_service_courses_filters_ps_workplace_id');
				$('#ps_service_courses_filters_ps_workplace_id').select2('val','');
				
				resetOptions('ps_service_courses_filters_ps_service_id');
				$('#ps_service_courses_filters_ps_service_id').select2('val','');

				resetOptions('ps_service_courses_filters_ps_member_id');
				$('#ps_service_courses_filters_ps_member_id').select2('val','');
				
				if ($('#ps_service_courses_filters_ps_customer_id').val() <= 0) {
					return;
				}
				$("#ps_service_courses_filters_ps_service_id").attr('disabled', 'disabled');
				$("#ps_service_courses_filters_ps_workplace_id").attr('disabled', 'disabled');
				
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
				    
			    	$('#ps_service_courses_filters_ps_workplace_id').select2('val','');

					$("#ps_service_courses_filters_ps_workplace_id").html(msg);

					$("#ps_service_courses_filters_ps_workplace_id").attr('disabled', null);
			    
    				$.ajax({
    			        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
    			        type: "POST",
    			        data: 'c_id='+ $('#ps_service_courses_filters_ps_customer_id').val() + '&y_id=' + $('#ps_service_courses_filters_school_year_id').val() + '&w_id=' + $("#ps_service_courses_filters_ps_workplace_id").val(),
    			        processResults: function (data, page) {
    		          		return {
    		            		results: data.items
    		          		};
    		        	},
    			    }).done(function(msg) {
    			    	$('#ps_service_courses_filters_ps_service_id').select2('val','');
    					$("#ps_service_courses_filters_ps_service_id").html(msg);
    					$("#ps_service_courses_filters_ps_service_id").attr('disabled', null);
    			    });

			    });
			    
				$.ajax({
			        url: '<?php echo url_for('@ps_service_courses_member') ?>',
			        type: "POST",
			        data: {'psc_id': $(this).val()},
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_service_courses_filters_ps_member_id').select2('val','');
					$("#ps_service_courses_filters_ps_member_id").html(msg);
					$("#ps_service_courses_filters_ps_member_id").attr('disabled', null);
			    });
		    });

		 $('#ps_service_courses_filters_school_year_id').change(function() {

	    		resetOptions('ps_service_courses_filters_ps_service_id');
	    		$('#ps_service_courses_filters_ps_service_id').select2('val','');
	    
	    		if ($('#ps_service_courses_filters_school_year_id').val() <= 0) {
	    			return;
	    		}
	    		$("#ps_service_courses_filters_ps_service_id").attr('disabled', 'disabled');

	    		$.ajax({
	    	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
	    	        type: "POST",
	    	        data: 'c_id='+ $('#ps_service_courses_filters_ps_customer_id').val() + '&y_id=' + $('#ps_service_courses_filters_school_year_id').val() + '&w_id=' + $("#ps_service_courses_filters_ps_workplace_id").val(),
	    	        processResults: function (data, page) {
	              		return {
	                		results: data.items
	              		};
	            	},
	    	    }).done(function(msg) {
	    	    	$('#ps_service_courses_filters_ps_service_id').select2('val','');
	    			$("#ps_service_courses_filters_ps_service_id").html(msg);
	    			$("#ps_service_courses_filters_ps_service_id").attr('disabled', null);
	    	    });
	    	  
		    });
		
		 $('#ps_service_courses_filters_ps_workplace_id').change(function() {

	    		resetOptions('ps_service_courses_filters_ps_service_id');
	    		$('#ps_service_courses_filters_ps_service_id').select2('val','');
	    
	    		if ($('#ps_service_courses_filters_ps_workplace_id').val() <= 0) {
	    			return;
	    		}
	    		$("#ps_service_courses_filters_ps_service_id").attr('disabled', 'disabled');

	    		$.ajax({
	    	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
	    	        type: "POST",
	    	        data: 'c_id='+ $('#ps_service_courses_filters_ps_customer_id').val() + '&y_id=' + $('#ps_service_courses_filters_school_year_id') + '&w_id=' + $("#ps_service_courses_filters_ps_workplace_id").val(),
	    	        processResults: function (data, page) {
	              		return {
	                		results: data.items
	              		};
	            	},
	    	    }).done(function(msg) {
	    	    	$('#ps_service_courses_filters_ps_service_id').select2('val','');
	    			$("#ps_service_courses_filters_ps_service_id").html(msg);
	    			$("#ps_service_courses_filters_ps_service_id").attr('disabled', null);
	    	    });
	    	  

		    });
// end filter
		 $('#ps_service_courses_ps_customer_id').change(function() {
				
				resetOptions('ps_service_courses_ps_service_id');
				$('#ps_service_courses_ps_service_id').select2('val','');
				resetOptions('ps_service_courses_ps_member_id');
				$('#ps_service_courses_ps_member_id').select2('val','');
				
				if ($('#ps_service_courses_ps_customer_id').val() <= 0) {
					return;
				}
				$("#ps_service_courses_ps_service_id").attr('disabled', 'disabled');
				$.ajax({
			        url: '<?php echo url_for('@ps_service_courses_by_ps_customer') ?>',
			        type: "POST",
			        data: {'psc_id': $(this).val()},
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_service_courses_ps_service_id').select2('val','');
					$("#ps_service_courses_ps_service_id").html(msg);
					$("#ps_service_courses_ps_service_id").attr('disabled', null);
			    });
				$.ajax({
			        url: '<?php echo url_for('@ps_service_courses_member') ?>',
			        type: "POST",
			        data: {'psc_id': $(this).val()},
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#ps_service_courses_ps_member_id').select2('val','');
					$("#ps_service_courses_ps_member_id").html(msg);
					$("#ps_service_courses_ps_member_id").attr('disabled', null);
			    });
		    });
 
});


</script>
<?php endif;?>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('psServiceCourses/box_modal_confirm_remover_student');?>