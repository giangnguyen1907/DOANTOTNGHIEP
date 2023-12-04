<?php
$type = $type ? $type : $filter_value ['type'];

// so thong bao chua doc
$num_no_read = Doctrine::getTable ( 'PsCmsNotifications' )->getNoReadNotification ( myUser::getUserId () );
?>
<ul class="inbox-menu-lg">
	<li <?php if($type == 'new'): ?> class="active" <?php endif;?>><a
		href="<?php echo url_for('ps_cms_notifications/new') ?>"><i
			class="fa fa-plus" aria-hidden="true"></i> <?php echo __('New')?></a></li>
	<li <?php if($type == 'received'): ?> class="active" <?php endif;?>><a
		href="<?php echo url_for('@ps_cms_notifications?type=received') ?>"
		class="row-id"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo __('Inbox')?> <span
			class="badge bg-color-red inbox-badge animated"> <?php echo $num_no_read ?> </span></a></li>
	<li <?php if($type == 'sent'): ?> class="active" <?php endif;?>><a
		href="<?php echo url_for('@ps_cms_notifications?type=sent') ?>"
		class="row-id"><i class="fa fa-send-o" aria-hidden="true"></i> <?php echo __('Sent')?></a></li>
	<li <?php if($type == 'drafts'): ?> class="active" <?php endif;?>><a
		href="<?php echo url_for('@ps_cms_notifications?type=drafts') ?>"
		class="row-id"><i class="fa fa-edit " aria-hidden="true"></i> <?php echo __('Draft')?></a></li>
	<li <?php if($type == 'trash'): ?> class="active" <?php endif;?>><a
		href="<?php echo url_for('@ps_cms_notifications?type=trash') ?>"
		class="row-id"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo __('Trash')?></a></li>
</ul>
