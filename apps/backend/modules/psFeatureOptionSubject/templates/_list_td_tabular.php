<td class="sf_admin_text sf_admin_list_td_feature_option">
  <?php echo $feature_option_subject->getFeatureOption() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_tpl_custom/type">
  <?php echo get_partial('psFeatureOptionSubject/tpl_custom/type', array('type' => 'list', 'feature_option_subject' => $feature_option_subject)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_order_by text-center">
  <?php include_partial('psFeatureOptionSubject/order_by',array('feature_option_subject' => $feature_option_subject)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $feature_option_subject->getUpdatedBy() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($feature_option_subject->getUpdatedAt()) ? format_date($feature_option_subject->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
