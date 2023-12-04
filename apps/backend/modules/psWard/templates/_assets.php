<script type="text/javascript">
$(document).ready(function() {
    
    // Load group District for filter by country_code
    $('#ps_ward_filters_country_code').change(function() {
    	
    	$.ajax({
	        url: '<?php echo url_for('@ps_group_district?cid=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'cid': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items  
          		};
        	},
	    }).done(function(msg) {
			   jQuery("#ps_ward_filters_ps_district_id").html(msg);	            	
	    });
    });

    // Load group District by country_code
    $('#ps_ward_country_code').change(function() {
      
      $.ajax({
          url: '<?php echo url_for('@ps_group_district?cid=') ?>' + $(this).val(),
          type: "POST",
          data: {'cid': $(this).val()},
          processResults: function (data, page) {
              return {
                results: data.items  
              };
          },
      }).done(function(msg) {
         jQuery("#ps_ward_ps_district_id").html(msg);               
      });

    });
});
</script>