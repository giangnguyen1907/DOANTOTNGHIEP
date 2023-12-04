<td class="sf_admin_text sf_admin_list_td_is_update_by">
  <?php echo $ps_cms_notifications->getCreatedBy() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title" style="width: 300px;">
  <a data-backdrop="static" data-toggle="modal" data-target="#remoteModal"  href="<?php echo url_for('@ps_cms_notifications_detail?id='.$ps_cms_notifications->getId())?>">
  <?php if ($ps_cms_notifications->getIsRead() == 0 && $filter_value['type'] == 'received') : ?>
  <strong><?php echo $ps_cms_notifications->getTitle() ?></strong>
  <?php else :?>
  <?php echo $ps_cms_notifications->getTitle()  ?>
  <?php endif;?>
  </a>
</td>
<td class="sf_admin_date sf_admin_list_td_date_at">
  <?php echo false !== strtotime($ps_cms_notifications->getDateAt()) ? format_date($ps_cms_notifications->getDateAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_received" style="width: 300px;">
  <?php echo get_partial('psCmsNotifications/list_received', array('type' => 'list', 'ps_cms_notifications' => $ps_cms_notifications)) ?>
</td>
