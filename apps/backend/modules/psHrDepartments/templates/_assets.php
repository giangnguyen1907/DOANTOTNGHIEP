<script type="text/javascript">
$(document).ready(function() {

	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_school_code a, .sf_admin_list_td_school_name a").on("contextmenu",function(){
	    return false;
	});

	// Load district for filter by province
    $('#ps_member_filters_ps_province_id').change(function() {

    	$("#ps_member_filters_ps_district_id").attr('disabled', 'disabled');		
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
	    	$('#ps_member_filters_ps_district_id').select2('val','');
			$("#ps_member_filters_ps_district_id").html(msg);
			$("#ps_member_filters_ps_district_id").attr('disabled', null);				            	
	    });
    });

 	// Load district for filter by province
    $('#ps_member_ps_province_id').change(function() {

    	$("#ps_member_ps_district_id").attr('disabled', 'disabled');		
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
	    	$('#ps_member_ps_district_id').select2('val','');
			$("#ps_member_ps_district_id").html(msg);
			$("#ps_member_ps_district_id").attr('disabled', null);				            	
	    });
    });
    
});
</script>