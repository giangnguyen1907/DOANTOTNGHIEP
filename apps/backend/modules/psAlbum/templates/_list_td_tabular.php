<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_album->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_content">
  <?php echo html_entity_decode($ps_album->getContent()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_class_name">
  <?php echo $ps_album->getClassName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_total_like">
  <?php echo $ps_album->getTotalLike() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_total_comment">
  <?php echo $ps_album->getTotalComment() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_status">
  <?php echo get_partial('psAlbum/list_field_boolean_type', array('value' => $ps_album->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_album->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_album->getUpdatedAt()) ? format_date($ps_album->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
