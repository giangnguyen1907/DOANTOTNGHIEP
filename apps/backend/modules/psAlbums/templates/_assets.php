<?php use_helper('I18N', 'Date')?>
<?php include_partial('global/field_custom/_ps_assets') ?>
<style>
img.hover-shadow {
	display: inline-block;
	width: 100%;
	/*height: 200px;*/
	padding: 5px;
	border: 1px solid #b9aeae;
	background-position: center center;
	background-size: cover;
}

.thumb:hover {
	opacity: 0.5;
}

.ps_albums_intro {
	margin: 20px 0px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	
	$('.input_textarea').keyup(function(){
	    $( "#remainingInput_note" ).html( this.value.length + '/' + $(this).attr('maxLength') );
	});

	$('.btn-album-item-activated').click(function() {
		
		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		//alert(alb_state);
		
		$('#ic-loading-' + alb_id).show();
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#ic-loading-' + alb_id).hide();
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});

	//Change status state
	$('.btn-album-item-deactivated').click(function() {

		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		$('#ic-loading-' + alb_id).show();
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#ic-loading-' + alb_id).hide();
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});

	//Change status state
	$('.btn-album-item-lock').click(function() {

		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		$('#ic-loading-' + alb_id).show();
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#ic-loading-' + alb_id).hide();
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});
  	
	$(".btn-item-activated, .btn-item-deactivated, .btn-item-lock").click(function() {
		
		var album_id =  $(this).attr('item');
		var status = $(this).attr('data-check');
		
		$('#ic-loading-' + album_id).show();		

		$.ajax({
	        url: '<?php echo url_for('@ps_albums_update_status') ?>',
	        type: 'POST',
	        data: 'album_id=' + album_id + '&status=' + status,
	        success: function(data) {
	        	$('#ic-loading-' + album_id).hide();
	        	$('#field-user-' + album_id).html(data);
	        	return;
	        }
		});
		
	});
	
	//BEGIN: filter
	$('#ps_albums_filters_ps_customer_id').change(function() {

		resetOptions('ps_albums_filters_ps_workplace_id');
		$('#ps_albums_filters_ps_workplace_id').select2('val','');
		resetOptions('ps_albums_filters_ps_class_id');
		$('#ps_albums_filters_ps_class_id').select2('val','');
		if ($(this).val() > 0) {

			$("#ps_albums_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#ps_albums_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#ps_albums_filters_ps_workplace_id').select2('val','');

				$("#ps_albums_filters_ps_workplace_id").html(msg);

				$("#ps_albums_filters_ps_workplace_id").attr('disabled', null);

				$("#ps_albums_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#ps_albums_filters_ps_customer_id').val() + '&w_id=' + $('#ps_albums_filters_ps_workplace_id').val() +'&y_id=' + $('#ps_albums_filters_school_year_id').val(),
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
		    });
		}		
	});
	 
	$('#ps_albums_filters_ps_workplace_id').change(function() {
		resetOptions('ps_albums_filters_ps_class_id');
		$('#ps_albums_filters_ps_class_id').select2('val','');
		
		if ($('#ps_albums_filters_ps_workplace_id').val() <= 0) {
			return;
		}

		$("#ps_albums_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_albums_filters_ps_customer_id').val() + '&w_id=' + $('#ps_albums_filters_ps_workplace_id').val() +'&y_id=' + $('#ps_albums_filters_school_year_id').val(),
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
	});

	$('#ps_albums_filters_school_year_id').change(function() {
		resetOptions('ps_albums_filters_ps_class_id');
		$('#ps_albums_filters_ps_class_id').select2('val','');
		
		if ($('#ps_albums_filters_school_year_id').val() <= 0) {
			return;
		}

		$("#ps_albums_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_albums_filters_ps_customer_id').val() + '&w_id=' + $('#ps_albums_filters_ps_workplace_id').val() +'&y_id=' + $('#ps_albums_filters_school_year_id').val(),
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
	});
	//END: filter

	//BEGIN: Form New
	$('#ps_albums_school_year_id').change(function() {
		resetOptions('ps_albums_ps_class_id');
		$('#ps_albums_ps_class_id').select2('val','');
		
		if ($('#ps_albums_school_year_id').val() <= 0) {
			return;
		}

		$("#ps_albums_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#ps_albums_ps_customer_id').val() + '&w_id=' + $('#ps_albums_ps_workplace_id').val() +'&y_id=' + $('#ps_albums_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_albums_ps_class_id').select2('val','');
			$("#ps_albums_ps_class_id").html(msg);
			$("#ps_albums_ps_class_id").attr('disabled', null);
	    });
	});
	
	$('#ps_albums_ps_workplace_id').change(function() {

		resetOptions('ps_albums_ps_class_id');
		$('#ps_albums_ps_class_id').select2('val','');
		
		if ($(this).val() > 0) {
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#ps_albums_ps_customer_id').val() + '&w_id=' + $('#ps_albums_ps_workplace_id').val() +'&y_id=' + $('#ps_albums_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#ps_albums_ps_class_id').select2('val','');

				$("#ps_albums_ps_class_id").html(msg);

				$("#ps_albums_ps_class_id").attr('disabled', null);
		    });
		}		
	});
	//END:Form New
});
</script>
<?php include_partial('global/include/_box_modal');?>