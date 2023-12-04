<table id="dt_basic_receivable"
	class="display table table-striped table-bordered table-hover"
	width="100%">
	<thead>
		<tr>
			<th><?php echo __('Title');?></th>
			<th><?php echo __('Amount')?></th>
			<th><?php echo __('Description');?></th>
			<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose')?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($receivable_for_fee_report as $obj): ?>
	<tr>
			<td>
		<?php echo $obj->getTitle()?>
		<p>
					<span class="text-muted"><i><?php echo $obj->getDescription()?></i></span>
				</p>
			</td>

			<td>
				<div class="form-group" style="margin-bottom: 0px;">
					<input type="number" class="form-control" min="-9999999"
						max="9999999"
						name="receivable_month[<?php echo $obj->getId();?>][amount]"
						id="receivable_month_my_class_<?php echo $obj->getId();?>_amount"
						value="<?php echo $obj->getAmount()?>" />
				</div>
			</td>
			<td>
				<div class="form-group" style="margin-bottom: 0px;">
					<input type="text" class="form-control" maxlength="255"
						name="receivable_month[<?php echo $obj->getId();?>][note]"
						id="receivable_month_my_class_<?php echo $obj->getId();?>_note"
						value="<?php echo $obj->getDescription()?>" />
				</div>
			</td>
			<td class="text-center"><label class="checkbox-inline"> <input
					class="select checkbox chk_ids" type="checkbox"
					name="receivable_month[<?php echo $obj->getId();?>][ids]"
					id="receivable_month_my_class_<?php echo $obj->getId();?>_select"
					value="<?php echo $obj->getId();?>"><span></span>
			</label></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>