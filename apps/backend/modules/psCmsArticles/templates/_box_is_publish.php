<script type="text/javascript">
$(document).ready(function(){
	
	$(".btn-articles-item-activated, .btn-articles-item-deactivated, .btn-articles-item-lock").click(function() {
		
		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		$('#ic-loading-' + alb_id).show();
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_cms_articles_update_publish') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#ic-loading-' + alb_id).hide();
	        	$('#status-' + alb_id).html(data);
	        }
		});
		
	});

});
</script>
<?php
$state = PreSchool::loadCmsArticlesLock ();
$value = $ps_cms_articles->getIsPublish ();
$articles_id = $ps_cms_articles->getId ();
?>
<div id="ic-loading-<?php echo $articles_id;?>" style="display: none;">
	<i class="fa fa-spinner fa-2x fa-spin text-success"
		style="padding: 3px;"></i><?php echo __('Loading...')?>
</div>
<?php if(myUser::credentialPsCustomers('PS_CMS_ARTICLES_LOCK')): // Neu co quyen khoa tin tuc ?>
<div class="btn-group" rel="tooltip" data-placement="top"
	data-original-title="<?php echo __($state[$value])?>">
	<a class="btn btn-default" href="javascript:void(0);"><?php echo get_partial('global/field_custom/_list_field_status_media', array('value' => $value));?></a>
	<a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-articles-item-activated"
			data-value="<?php echo $articles_id ?>"
			data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
		<li><a href="javascript:void(0);"
			class="btn-articles-item-deactivated"
			data-value="<?php echo $articles_id ?>"
			data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-articles-item-lock"
			data-value="<?php echo $articles_id ?>"
			data-check="<?php echo PreSchool::LOCK ?>"><?php echo __('Lock') ?></a></li>
	</ul>
</div>
<?php elseif($value != PreSchool::LOCK): ?>
<div class="btn-group" rel="tooltip" data-placement="top"
	data-original-title="<?php echo __($state[$value])?>">
	<a class="btn btn-default" href="javascript:void(0);"><?php echo get_partial('global/field_custom/_list_field_status_media', array('value' => $value));?></a>
	<a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-articles-item-activated"
			data-value="<?php echo $articles_id ?>"
			data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Publish') ?></a></li>
		<li><a href="javascript:void(0);"
			class="btn-articles-item-deactivated"
			data-value="<?php echo $articles_id ?>"
			data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Not publish') ?></a></li>
	</ul>
</div>
<?php

else :
	echo get_partial ( 'global/field_custom/_list_field_status_media', array (
			'value' => $value ) );
	?>
	
<?php endif;?>

