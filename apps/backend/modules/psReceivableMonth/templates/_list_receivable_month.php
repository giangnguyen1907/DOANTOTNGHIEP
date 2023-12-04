<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_flashes');?>
<div class="alert alert-info no-margin fade in">
	<i class="fa-fw fa fa-info"></i><strong><?php echo __('Receivables added of month')?>:</strong>&nbsp;									
	<?php if (count($list_receivable_temp_receivable_at) <= 0) echo '<code>'.__('No data').'</code>';?>
	<?php foreach($list_receivable_temp_receivable_at as $obj): ?>
	<?php echo $obj->getTitle()?>(<strong class="txt-color-red"><?php include_partial('global/list_field_price', array('value' => $obj->getAmount())) ?></strong>)
	<button id="item-<?php echo $obj->getId()?>"
		data-item="<?php echo $obj->getId()?>"
		data-item-text="<?php echo $obj->getTitle()?>" data-toggle="modal"
		data-target="#confirmDeleteRT" data-backdrop="static" rel="tooltip"
		data-placement="bottom"
		data-original-title="<?php echo __('Delete this receivable')?>"
		type="button"
		class="btn btn-xs btn-default btn-delete-receivable-temp"
		onclick="javascript:setItem('<?php echo $obj->getId()?>');">
		<i class="fa-fw fa fa-times txt-color-red"></i>
	</button>
	<?php endforeach;?>
</div>