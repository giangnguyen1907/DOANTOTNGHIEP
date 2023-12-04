
<script type="text/javascript">
$(document).ready(function() {
	$('.control_filter_receivable_month').change(function() {

		var obj_id = $(this).attr('data-value');
		var amount = $('#control_filter_receivable_month_my_class_'+obj_id+'_amount2').val();
		
		if ($('#control_filter_receivable_month_my_class_'+obj_id+'_amount2').val() > 0) {
			//alert(amount);
			$("#control_filter_receivable_month_my_class").attr('readonly', true);
		} else {
			$("#control_filter_receivable_month_my_class").attr('readonly', false);
		}
		$('#control_filter_receivable_month_my_class_'+obj_id+'_amount').val($( '#control_filter_receivable_month_my_class_'+obj_id+'_amount2 option:selected' ).val());		
	});	
});
</script>

<span class="label label-warning"><?php echo __('List receivables')?></span>
<div class="custom-scroll table-responsive"
	style="max-height: 500px; overflow-y: scroll;">
	<table id="dt_basic_receivable"
		class="display table table-striped table-bordered table-hover"
		width="100%">
		<thead>
			<tr>
				<th style="width: 35%;"><?php echo __('Name');?></th>
				<th style="width: 25%;"><?php echo __('Amount')?></th>
				<th><?php echo __('Note');?></th>
				<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose')?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $receivables as $obj ) :
			?>
		<tr>
				<td>
				<?php echo $obj->getTitle()?>
				<p>
						<span class="text-muted"><i><?php echo $obj->getDescription()?></i></span>
					</p>
				</td>
				<td>
				<?php

$receivable_detail = Doctrine::getTable ( 'ReceivableDetail' )->getAllReceivableDetailDate ( $obj->getId (), $date_at );
			if (count ( $receivable_detail ) > 0) {
				?>
				<input type="number"
					name="control_filter[receivable][<?php echo $obj->getId();?>][amount]"
					class="form-control"
					id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_amount"
					value="<?php echo PreNumber::number_clean($obj->getAmount());?>"
					placeholder="<?php echo __('Enter the relative payment')?>" /> <select
					class="form-control control_filter_receivable_month"
					name="control_filter[receivable][<?php echo $obj->getId();?>][amount2]"
					id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_amount2"
					data-value="<?php echo $obj->getId();?>">
						<option
							value="<?php echo PreNumber::number_clean($obj->getAmount());?>"><?php echo __('Or')?></option>
                	<?php foreach ($receivable_detail as $re_detail){?>
                	<option
							value="<?php echo PreNumber::number_clean($re_detail->getAmount())?>"><?php echo PreNumber::number_format($re_detail->getAmount())?></option>
                	<?php }?>
                </select>
				<?php }else{?>
				<div class="form-group" style="margin-bottom: 0px;">
						<input type="number" class="form-control" min="-9999999"
							max="9999999"
							name="control_filter[receivable][<?php echo $obj->getId();?>][amount]"
							id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_amount"
							value="<?php echo PreNumber::number_clean($obj->getAmount());?>" />
					</div>
				<?php }?>
			</td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<input type="text" class="form-control" maxlength="255"
							name="control_filter[receivable][<?php echo $obj->getId();?>][note]"
							id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_note"
							value="" />
					</div>
				</td>
				<td class="text-center"><label class="checkbox-inline"> <input
						class="select checkbox chk_ids" type="checkbox"
						
						name="control_filter[receivable][<?php echo $obj->getId();?>][ids]"
						id="control_filter_receivable_month_my_class_<?php echo $obj->getId();?>_select"
						value="<?php echo $obj->getId();?>"><span></span>
				</label></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<!--
<p class="label label-warning"><?php echo __('List service')?></p>
<div class="custom-scroll table-responsive" style="max-height:500px; overflow-y: scroll;">
	<table id="dt_basic_receivable" class="display table table-striped table-bordered table-hover" width="100%">
		<thead>
		<tr>
			<th style="width: 35%;"><?php echo __('Name');?></th>
			<th style="width: 25%;"><?php echo __('Amount')?></th>
			<th><?php echo __('Discount fixed');?></th>
			<th><?php echo __('Discount');?></th>
			<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose')?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $list_service as $service ) :
			?>
		<tr>
			<td>
				<?php echo $service->getTitle()?>
			</td>				
			<td>
				<div class="form-group" style="margin-bottom: 0px;">
			       <input type="number" readonly class="form-control" min="-9999999" max="9999999" name="control_form[service][<?php echo $service->getId();?>][amount]" id="control_form_service_month_my_class_<?php echo $service->getId();?>_amount" value="<?php echo PreNumber::number_clean($service->getAmount());?>"/>				
				</div>
			</td>
			<td>
				<div class="form-group" style="margin-bottom: 0px;">
			       <input type="number" class="form-control"  name="control_form[service][<?php echo $service->getId();?>][fixed]" id="control_form_service_month_my_class_<?php echo $service->getId();?>_discount_fixed" value=""/>				
				</div>
			</td>
			<td>
				<div class="form-group" style="margin-bottom: 0px;">
			       <input type="number" class="form-control"  name="control_form[service][<?php echo $service->getId();?>][discount]" id="control_form_service_month_my_class_<?php echo $service->getId();?>_discount" value=""/>				
				</div>
			</td>
			<td class="text-center">
				<label class="checkbox-inline">
					<input class="select checkbox chk_ids" type="checkbox" <?php if (in_array($service->getId(), $arr_receivable)) echo 'checked="checked"';?> name="control_form[service][<?php echo $service->getId();?>][idss]" id="control_form_service_month_my_class_<?php echo $service->getId();?>_select" value="<?php echo $service->getId();?>"><span></span>
				</label>
			</td>
		</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
-->