
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo link_to($receivable->getTitle(), 'receivable_edit', $receivable) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_list_price">
  <?php echo get_partial('receivable/list_price', array('type' => 'list', 'receivable' => $receivable)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_iorder"><input type="number"
	min="0" id="iorder_<?php echo $receivable->getId();?>"
	name="iorder[<?php echo $receivable->getId();?>]"
	class="form-control sf_admin_batch_number"
	value="<?php echo $receivable->getIorder() ?>"
	style="width: 80px; text-align: center;"
	onkeypress="javascript:return keyNumber(event);"
	onchange="javascript:setCheck(this,'<?php echo $receivable->getId();?>');" />

</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('receivable/list_field_boolean', array('value' => $receivable->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_customer_title">
  <?php echo $receivable->getCustomerTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_work_places">
  <?php echo $receivable->getWorkPlaces() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
<?php echo $receivable->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($receivable->getUpdatedAt()) ? format_date($receivable->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
