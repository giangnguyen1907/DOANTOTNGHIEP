<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_reduce_yourself->getReduceCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_reduce_yourself->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_level">
  <?php echo $ps_reduce_yourself->getLevel() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_status">
  <!-- <?php echo $ps_reduce_yourself->getStatus() ?> -->
  <?php echo get_partial('psReduceYourself/list_field_status_custom', array('value' => $ps_reduce_yourself->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_discount">
  <?php echo $ps_reduce_yourself->getStart() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_discount">
  <?php echo $ps_reduce_yourself->getStop() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_type">
  <?php echo PreSchool::$ps_giamtru[$ps_reduce_yourself->getIsType()]; //echo get_partial('psReduceYourself/list_field_boolean', array('value' => $ps_reduce_yourself->getIsType())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_reduce_yourself->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_reduce_yourself->getUpdatedAt()) ? format_date($ps_reduce_yourself->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
