<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_category_review->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_category_review->getNote() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_status">
  <?php echo get_partial('psCategoryReview/list_field_boolean_type', array('value' => $ps_category_review->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_category_review->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_category_review->getUpdatedAt()) ? format_date($ps_category_review->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
