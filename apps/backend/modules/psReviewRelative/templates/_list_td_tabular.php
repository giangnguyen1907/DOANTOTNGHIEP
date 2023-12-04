<td class="sf_admin_text sf_admin_list_td_cate_review_name">
  <?php echo $ps_review_relative->getCateReviewName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_review_relative->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_status">
  <?php echo get_partial('psReviewRelative/list_field_boolean_type', array('value' => $ps_review_relative->getStatus())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_review_relative->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($ps_review_relative->getUpdatedAt()) ? format_date($ps_review_relative->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
