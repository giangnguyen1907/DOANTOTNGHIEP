<?php use_helper('I18N', 'Date')?>
<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#ps_albums_filters_ps_customer_id').change(function() {

		resetOptions('ps_albums_filters_ps_class_id');
		$('#ps_albums_filters_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#ps_albums_filters_ps_class_id').select2('val','');

				$("#ps_albums_filters_ps_class_id").html(msg);

				$("#ps_albums_filters_ps_class_id").attr('disabled', null);
		    });
		}		
	});
	
});
</script>
