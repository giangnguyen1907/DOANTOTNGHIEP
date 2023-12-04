<p class="label label-primary"><?php echo __('Receivables added of month')?>:</p>
<div class="custom-scroll table-responsive"
	style="height: 200px; overflow-y: scroll;">
	<table id="dt_basic_receivable" class="table table-bordered"
		width="100%">
		<thead>
			<tr>
				<th><?php echo __('Name');?></th>
				<th><?php echo __('Amount')?></th>
				<th><?php echo __('Note');?></th>
			</tr>
		</thead>
		<tbody>
		
		<?php foreach($receivable_for_fee_report as $obj):?>		
			<?php foreach($receivables as $receivable): ?>
				<?php if (isset($receivable['ids']) && $receivable['ids'] > 0 && $receivable['ids'] == $obj->getId()):?>
				<tr>
						<td><input class="select checkbox chk_ids" type="hidden"
							name="control_filter[receivable][<?php echo $obj->getId();?>][ids]"
							id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_select"
							value="<?php echo $obj->getId();?>">
					<?php echo $obj->getTitle()?>
					<?php if (trim($obj->getDescription()) != ''):?>
					<p>
								<span class="text-muted"><i><?php echo $obj->getDescription()?></i></span>
							</p>
					<?php endif;?>
					</td>
						<td>
							<div class="form-group" style="margin-bottom: 0px;">
								<input type="number"
									style="background: transparent; border: none;" readonly
									class="form-control" min="-9999999" max="9999999"
									name="control_filter[receivable][<?php echo $obj->getId();?>][amount]"
									id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_amount"
									value="<?php echo $receivable['amount']?>" />
							</div>
						</td>
						<td>
							<div class="form-group" style="margin-bottom: 0px;">
								<input type="text" style="background: transparent; border: none;"
									readonly class="form-control" maxlength="255"
									name="control_filter[receivable][<?php echo $obj->getId();?>][note]"
									id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_note"
									value="<?php echo $receivable['note'];?>" />
							</div>
						</td>
					</tr>
				<?php endif;?>
			<?php endforeach;?>
		<?php endforeach;?>
		
		</tbody>
	</table>
</div>