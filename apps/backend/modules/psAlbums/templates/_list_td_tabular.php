<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_albums->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_number">
  <?php //echo $ps_albums->getNumberImg()?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_albums->getNote() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_created_id">
  <?php echo $ps_albums->getCreatorBy() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psAlbums/list_field_boolean', array('value' => $ps_albums->getIsActivated())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php $create_at = date('d-m-Y', strtotime($ps_albums->getCreatedAt())) ?>
  <?php echo $create_at ?>
</td>
