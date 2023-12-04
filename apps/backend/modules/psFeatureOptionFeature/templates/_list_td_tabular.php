<td class="sf_admin_text sf_admin_list_td_feature_option">
  <?php echo $feature_option_feature->getFeatureOption() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_tpl_custom/type">
  <?php echo get_partial('psFeatureOptionFeature/tpl_custom/type', array('type' => 'list', 'feature_option_feature' => $feature_option_feature)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_order_by text-center">
  <?php include_partial('psFeatureOptionFeature/order_by',array('feature_option_feature' => $feature_option_feature)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_activated text-center">
	<div id="status-loading-<?php echo $feature_option_feature->getId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div>
  	
  	<span class="onoffswitch feature_option_feature_status" id="feature_option_feature_status_<?php echo $feature_option_feature->getId() ?>" value="<?php echo $feature_option_feature->getId() ?>">
  		<?php echo get_partial('psFeatureOptionFeature/list_field_boolean', array('value' => $feature_option_feature->getIsActivated())) ?>
  	</span>
  	
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $feature_option_feature->getUpdatedBy() ?><br/>
  <?php echo false !== strtotime($feature_option_feature->getUpdatedAt()) ? format_date($feature_option_feature->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>

