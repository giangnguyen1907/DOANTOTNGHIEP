<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.select2-container .select2-choice img.img-flag {
	height: 20px !important;
	width: 20px !important;
}
.description-food{white-space: pre-line;}
</style>
<script type="text/javascript">
	$('#remoteModal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
</script>
<script>
var msg_file_invalid = '<?php echo __('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array('%value%' => $app_upload_max_size))?>';
var PsMaxSizeFile 	 = '<?php echo $app_upload_max_size;?>';

function openLoadImages(field, add_url) {
	window.KCFinder = {
			callBack: function(url) {
				field.value = url;		   		    
				window.KCFinder = null;			        	        
			},
			relative_urls: false,
			remove_script_host: false,
			convert_urls: true
	};
	window.open(add_url, 'kcfinder_textbox','inline=1, resizable=1, scrollbars=0, width=800, height=600');
}

function remove_file_img (_name_file) {
	$('.' + _name_file).val('');
}


$(document).ready(function() {

	// Lay co so dao tao theo nha truong
	$('#ps_menus_imports_filters_ps_customer_id').change(function() {
		
		$("#ps_menus_imports_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_menus_imports_filters_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_menus_imports_filters_ps_workplace_id').html(data);
				$("#ps_menus_imports_filters_ps_workplace_id").attr('disabled', null);
	        }
		});
	});

	$('#ps_menus_imports_ps_workplace_id').change(function() {

		resetOptions('ps_menus_imports_ps_meal_id');
		$("#ps_menus_imports_ps_meal_id").attr('disabled', 'disabled');
	   	
	   	var cid = $('#ps_menus_imports_ps_customer_id').val();

		$.ajax({
			url: '<?php echo url_for('@ps_meals_by_ps_customer?cid=') ?>' + cid + '&wp_id=' + $('#ps_menus_imports_ps_workplace_id').val(),
	        type: "POST",
	        data: 'cid=' + cid  + '&wp_id=' + $('#ps_menus_imports_ps_workplace_id').val(),
	        processResults: function (data, page) {
              return {
                results: data.items  
              };
	          },
		}).done(function(msg) {
			 $("#ps_menus_imports_ps_meal_id").html(msg); 
			 $("#ps_menus_imports_ps_meal_id").attr('disabled', null);		 
		});

	});
	
	<?php if (myUser::credentialPsCustomers('PS_NUTRITION_MENUS_FILTER_SCHOOL')):?>

	
    $('#ps_menus_imports_ps_customer_id').change(function() {      

    	resetOptions('ps_menus_imports_ps_workplace_id');
    	
    	$("#ps_menus_imports_ps_workplace_id").attr('disabled', 'disabled');

    	$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_menus_imports_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_menus_imports_ps_workplace_id').html(data);
				$("#ps_menus_imports_ps_workplace_id").attr('disabled', null);
	        }
		});

		$("#ps_menus_imports_ps_meal_id").attr('disabled', 'disabled');
        
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
			$("#ps_menus_imports_ps_meal_id").html(msg); 
		   	$("#ps_menus_imports_ps_meal_id").attr('disabled', null);
		});				
    });
    <?php endif;?>

});
</script>


