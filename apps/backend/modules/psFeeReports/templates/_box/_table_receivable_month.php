<p class="label label-primary"><?php echo __('Add receivables to fee report')?>:</p>
<div class="custom-scroll table-responsive"
	style="height: 350px; overflow-y: scroll;">
	<table id="dt_basic_receivable"
		class="display table table-striped table-bordered table-hover"
		width="100%">
		<thead>
			<tr>
				<th style="max-width: 150px;"><?php echo __('Name');?></th>
				<th><?php echo __('Amount')?></th>
				<th><?php echo __('Note');?></th>
				<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose')?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		
		$arr_receivable = array ();
		
		if (count($receivables)) {
			foreach ( $receivables as $_receivable ) {
				
				array_push ( $arr_receivable, $_receivable ['ids'] );
			}
		}
		
		foreach ( $receivable_for_fee_report as $obj ) :

			$check = '';

			$note = '';

			if (in_array ( $obj->getId (), $arr_receivable )) {

				$check = 'checked="checked"';

				$note = $receivables [$obj->getId ()] ['note'];
			}
			?>
		<tr>
				<td>
				<?php echo $obj->getTitle()?>
				<p>
						<span class="text-muted"><i><?php echo $obj->getDescription()?></i></span>
					</p>
				</td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;" rel="tooltip"
						data-placement="top"
						data-original-title="<?php echo __('Allow to edit values and accept negative values');?>">
						<input type="number" class="form-control" min="-9999999"
							max="9999999"
							name="control_filter[receivable][<?php echo $obj->getId();?>][amount]"
							id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_amount"
							value="<?php echo $obj->getAmount()?>" />
					</div>
				</td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<input type="text" class="form-control" maxlength="255"
							name="control_filter[receivable][<?php echo $obj->getId();?>][note]"
							id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_note"
							value="<?php echo $note;?>" />
					</div>
				</td>
				<td class="text-center"><label class="checkbox-inline"> <input
						class="select checkbox chk_ids" type="checkbox"
						<?php if (in_array($obj->getId(), $arr_receivable)) echo 'checked="checked"';?>
						name="control_filter[receivable][<?php echo $obj->getId();?>][ids]"
						id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_select"
						value="<?php echo $obj->getId();?>"><span></span>
				</label></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>