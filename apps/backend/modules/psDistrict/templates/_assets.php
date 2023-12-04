<script type="text/javascript">
$(document).ready(function() {
    
    // Load provinces for filter by country_code
    $('#ps_district_filters_country_code').change(function() {
    	$.ajax({
	        url: '<?php echo url_for('@ps_province_country?cid=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'cid=' + $(this).val(),
	        success: function(data) {
	            $('#ps_district_filters_ps_province_id').html(data);            		            
	        }
	    });
    });

    // Load provinces for form by country_code
    $('#ps_district_country_code').change(function() {
    	
    	$("#ps_district_ps_province_id").select2('data', null);

    	$.ajax({
	        url: '<?php echo url_for('@ps_province_country?cid=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'cid': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items  
          		};
        	},
	    }).done(function(msg) {
			jQuery("#ps_district_ps_province_id").html(msg);	            	
	    });

    });
});
</script>