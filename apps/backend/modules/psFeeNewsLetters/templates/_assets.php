<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function(){
	
	$('.push_notication').click(function() {
		var fee_id = $(this).attr('value');
		var is_public = $(this).attr('data-value');

		if(is_public == 0){

			alert("<?php echo __('You can public notication') ?>");
			
		}else{
			$('#ic-loading-' + fee_id).show();		
			$.ajax({
		        url: '<?php echo url_for('@ps_fee_news_letters_send_notication') ?>',
		        type: 'POST',
		        data: 'fee_id=' + fee_id,
		        success: function(data) {
		        	$('#ic-loading-' + fee_id).hide();
		        	$('#box-' + fee_id).html(data);
		        },
		        error: function (request, error) {
		            alert(" Can't do because: " + error);
		            $('#ic-loading-' + fee_id).hide();
		        },
			});
		}
	});	

	$('.fee_news_letters_status').click(function() {
		
		var fee_id = $(this).attr('value');
		$('#status-loading-' + fee_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_fee_news_letters_updated_status') ?>',
	        type: 'POST',
	        data: 'fee_id=' + fee_id,
	        success: function(data) {
	        	$('#status-loading-' + fee_id).hide();
	        	$('#fee_news_letters_status-' + fee_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	            $('#status-loading-' + fee_id).hide();
	        },
		});
	});	
});
</script>
