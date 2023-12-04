
<?php if(myUser::credentialPsCustomers('PS_CMS_ALBUMS_EDIT')): ?>
	
	<?php if(myUser::credentialPsCustomers('PS_CMS_ALBUMS_LOCK')): ?>
	
    	<?php $state = PreSchool::loadPsUserActivated(); ?>
<div id="field-user-<?php echo $album_id ?>">
	<i><?php echo __('Status'). ': ' ?></i>
	<div class="btn-group" rel="tooltip" data-placement="top"
		data-original-title="<?php echo __($state[$value])?>">
		<a class="btn btn-default btn-xs" href="javascript:void(0);"><?php echo get_partial('global/field_custom/_list_field_status_media', array('value' => $value));?></a>
		<a class="btn btn-default btn-xs dropdown-toggle"
			data-toggle="dropdown" href="javascript:void(0);"
			aria-expanded="false"><span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a href="javascript:void(0);" class="btn-item-activated"
				item="<?php echo $album_id ?>"
				data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
			<li><a href="javascript:void(0);" class="btn-item-deactivated"
				item="<?php echo $album_id ?>"
				data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
			<li><a href="javascript:void(0);" class="btn-item-lock"
				item="<?php echo $album_id ?>"
				data-check="<?php echo PreSchool::LOCK ?>"><?php echo __('Lock') ?></a></li>
		</ul>
	</div>
</div>

<?php elseif($value != PreSchool::LOCK):?>
	
    	<?php $state = PreSchool::loadPsUserActivated(); ?>
<div id="field-user-<?php echo $album_id ?>">
	<i><?php echo __('Status'). ': ' ?></i>
	<div class="btn-group" rel="tooltip" data-placement="top"
		data-original-title="<?php echo __($state[$value])?>">
		<a class="btn btn-default btn-xs" href="javascript:void(0);"><?php echo get_partial('global/field_custom/_list_field_status_media', array('value' => $value));?></a>
		<a class="btn btn-default btn-xs dropdown-toggle"
			data-toggle="dropdown" href="javascript:void(0);"
			aria-expanded="false"><span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a href="javascript:void(0);" class="btn-item-activated"
				item="<?php echo $album_id ?>"
				data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
			<li><a href="javascript:void(0);" class="btn-item-deactivated"
				item="<?php echo $album_id ?>"
				data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
		</ul>
	</div>
</div>
<?php
	else :
		echo get_partial ( 'global/field_custom/_list_field_status_media', array (
				'value' => $value ) );
	endif;
	?>
	
<?php else: ?>
<?php
	echo get_partial ( 'global/field_custom/_list_field_status_media', array (
			'value' => $value ) );
endif;
?>

<script>
$(document).ready(function(){

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
});
</script>