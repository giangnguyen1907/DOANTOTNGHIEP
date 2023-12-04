<script>

	// msg
	var msg_invalid_value_between = '<?php echo __('Value must be between %s and %s')?>',
		msg_invalid_value_from= '<?php echo __('Value must from% s to% s')?>';
		
	$(document).ready(function() {
		    $('#service_split_split_value').keyup(function() {
		    	var split_value = parseInt($('#service_split_split_value').val());
		    	var service_id = parseInt($('#service_split_service_id').val());
		    	if(isNaN(split_value)){
		    		$('#service_split_value_price').val('');
		    		$('#service_split_value_price').change();
		    	}
		    	$.ajax({
		            url: '<?php echo url_for('@ps_subject_splits_split_value?split_value=') ?>'+split_value+'&sid='+service_id,
		            type: "POST",
		            data: 'f=<?php echo md5(time().time().time().time())?>&split_value=' + split_value + '&sid='+service_id,
		            processResults: function (data, page) {
		                return {
		                  results: data.items  
		                };
		            },
		  		}).done(function(msg) {
		  			$('#service_split_value_price').val(msg);
		  			$('#service_split_value_price').change();
		  		});
		    });
		    
	  });	         
			
</script>