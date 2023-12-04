<script type="text/javascript">
$(document).ready(function() {
	    
	    $('#ps_constant_option_ps_customer_id').change(function() {
	    	
	    	$.ajax({
		        url: '<?php echo url_for('@ps_constant_constant_list?cid=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'cid=' + $(this).val(),
		        success: function(data) {
		            $('#ps_constant_option_ps_constant_id').html(data);		            		            
		        }
		    });		 	
	    });
});
</script>
