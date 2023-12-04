<script type="text/javascript">
$(document).ready(function() {
	// BEGIN: filters
	$('#student_feature_filters_ps_customer_id').change(function() {

		if ($(this).val() <= 0) {
			return;
		}
		
		resetOptions('student_feature_filters_feature_branch_id');
		$('#student_feature_filters_feature_branch_id').select2('val','');

		resetOptions('student_feature_filters_ps_workplace_id');
		$('#student_feature_filters_ps_workplace_id').select2('val','');

		resetOptions('student_feature_filters_ps_class_id');
		$('#student_feature_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#student_feature_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#student_feature_filters_ps_workplace_id').select2('val','');

				$("#student_feature_filters_ps_workplace_id").html(msg);

				$("#student_feature_filters_ps_workplace_id").attr('disabled', null);

				$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_feature_filters_ps_customer_id').val() + '&w_id=' + $('#student_feature_filters_ps_workplace_id').val() + '&y_id=' + $('#student_feature_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#student_feature_filters_ps_class_id').select2('val','');
					$("#student_feature_filters_ps_class_id").html(msg);
					$("#student_feature_filters_ps_class_id").attr('disabled', null);

					$.ajax({
						url: '<?php echo url_for('@ps_feature_branch_by_ps_customer_id?cid=') ?>' + $(this).val(),
						type: "POST",
						data: {'cid': $(this).val()},
						processResults: function (data, page) {
							return {
							  results: data.items  
							};
						},
					}).done(function(msg) {
						$('#student_feature_filters_feature_branch_id').select2('val','');
						$("#student_feature_filters_feature_branch_id").html(msg);               
					});
					
			    });

				/*
				$.ajax({
					url: '<?php echo url_for('@ps_feature_branch_by_ps_customer_id?cid=') ?>' + $(this).val(),
					type: "POST",
					data: {'cid': $(this).val()},
					processResults: function (data, page) {
						return {
						  results: data.items  
						};
					},
				}).done(function(msg) {
					$('#student_feature_filters_feature_branch_id').select2('val','');
					$("#student_feature_filters_feature_branch_id").html(msg);               
				});
				*/
		    });	
		}		
	});
	 
	$('#student_feature_filters_ps_workplace_id').change(function() {
		
		if ($('#student_feature_filters_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
			return;
		}
		
		resetOptions('student_feature_filters_ps_class_id');
		$('#student_feature_filters_ps_class_id').select2('val','');

		$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_feature_filters_ps_customer_id').val() + '&w_id=' + $('#student_feature_filters_ps_workplace_id').val() + '&y_id=' + $('#student_feature_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_feature_filters_ps_class_id').select2('val','');
			$("#student_feature_filters_ps_class_id").html(msg);
			$("#student_feature_filters_ps_class_id").attr('disabled', null);
	    });
	});

	$('#student_feature_filters_school_year_id').change(function() {
		
		resetOptions('student_feature_filters_ps_class_id');
		$('#student_feature_filters_ps_class_id').select2('val','');
		
		if ($('#student_feature_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_feature_filters_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_feature_filters_ps_customer_id').val() + '&w_id=' + $('#student_feature_filters_ps_workplace_id').val() + '&y_id=' + $('#student_feature_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_feature_filters_ps_class_id').select2('val','');
			$("#student_feature_filters_ps_class_id").html(msg);
			$("#student_feature_filters_ps_class_id").attr('disabled', null);
	    });
	});

	// END: filters


	
/*
$('#student_feature_filters_ps_customer_id').change(function() {      
    $.ajax({
        url: '<?php // echo url_for('@ps_feature_branch_by_ps_customer_id?cid=') ?>' + $(this).val(),
        type: "POST",
        data: {'cid': $(this).val()},
        processResults: function (data, page) {
            return {
              results: data.items  
            };
        },
    }).done(function(msg) {
    	$('#student_feature_filters_feature_branch_id').select2('val','');
        $("#student_feature_filters_feature_branch_id").html(msg);               
    });
});

*/


    // get my class filter by school year
    
});
function toggleText_radio(bid,id,value){
	$('.branch_' + bid ).attr('disabled', true);	
	if(value > 0)
		$('#textbox_radio_' + bid ).attr('disabled', false);	
}
</script>