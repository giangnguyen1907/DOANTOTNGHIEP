<script type="text/javascript">
$(document).ready(function(){
	
	$(".btn-album-item-activated, .btn-album-item-deactivated, .btn-album-item-lock").click(function() {
		
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
  	
});
</script>
<?php
$status = $a->getIsActivated ();
// $state = PreSchool::loadPsActivity();
if ($status == PreSchool::ACTIVE) {
	$att = 'label label-success';
	$state = __ ( 'Publish' );
} elseif ($status == PreSchool::NOT_ACTIVE) {
	$att = 'label label-danger';
	$state = __ ( 'Not publish' );
} else {
	$att = 'label label-warning';
	$state = __ ( 'Lock' );
}
?>
<div id="ic-loading-<?php echo $a->getId();?>" style="display: none;">
	<i class="fa fa-spinner fa-2x fa-spin text-success"
		style="padding: 3px;"></i><?php echo __('Loading...')?>
</div>
<?php if(myUser::credentialPsCustomers('PS_CMS_ALBUMS_LOCK')): // Neu co quyen khoa albums ?>
<div class="btn-group">
	<a class="btn btn-default" href="javascript:void(0);"
		style="padding: 3px 9px;"><?php echo $state ?></a> <a
		class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"
		style="padding: 3px 9px;"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-album-item-activated"
			id="item-activated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-album-item-deactivated"
			id="item-deactivated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-album-item-lock"
			id="item-lock-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::LOCK ?>"><?php echo __('Lock') ?></a></li>
	</ul>
</div>
<?php elseif($status != PreSchool::LOCK): ?>
<div class="btn-group">
	<a class="btn btn-default" href="javascript:void(0);"
		style="padding: 3px 9px;"><?php echo $state ?></a> <a
		class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"
		style="padding: 3px 9px;"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-album-item-activated"
			id="item-activated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-album-item-deactivated"
			id="item-deactivated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
	</ul>
</div>
<?php

else :
	echo get_partial ( 'global/field_custom/_list_field_status_media', array (
			'value' => $status ) );
endif;
?>
