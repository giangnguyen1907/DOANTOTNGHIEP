<?php

$type = (isset($filter_value['is_status'])) ? $filter_value['is_status']->getValue() : '';

// so thong bao chua doc
$num_no_read = Doctrine::getTable('PsCmsNotifications')->getNoReadNotification(myUser::getUserId());
?>
<script type="text/javascript">

$(document).on("ready", function(){
	$('#inbox-sent').click(function() {		
		$("#ps_cms_notifications_filters_is_status").val('sent');
		$( "#ps-filter" ).submit();
	});

	$('#inbox-received').click(function() {		
		$("#ps_cms_notifications_filters_is_status").val('received');
		$( "#ps-filter" ).submit();
	});
});
</script>

<ul class="inbox-menu-lg">
	<li <?php if($type == 'new'): ?> class="active" <?php endif;?>>
		<a href="<?php echo url_for('ps_cms_notifications_new') ?>"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo __('New')?></a>
	</li>
	<li id="inbox-received" <?php if($type == 'received'): ?> class="active" <?php endif;?>>
		<a class="row-id">
		<i href="#" class="fa fa-inbox" aria-hidden="true"></i> <?php echo __('Inbox')?> <span class="badge bg-color-red inbox-badge animated"> <?php echo $num_no_read ?> </span>
		</a>
	</li>
	<li id="inbox-sent" <?php if($type == 'sent'): ?> class="active" <?php endif;?>>
		<a href="#" class="row-id"><i class="fa fa-send-o" aria-hidden="true"></i> <?php echo __('Sent')?></a>
	</li>
	<li id="inbox-sent" <?php if($type == 'trash'): ?> class="active" <?php endif;?>>
		<a href="#" class="row-id"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo __('Trash')?></a>
	</li>
</ul>
