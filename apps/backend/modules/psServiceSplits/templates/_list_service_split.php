<?php
// Sét nhãn hiển thị bởi is_type_fee
if ($service->getIsTypeFee () == 1)
	$count_value_text = __ ( 'Number of unused words' ); // Quy đổi theo số ngày nghỉ
else
	$count_value_text = __ ( 'Count value' );
?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
				<th class="text-center w-20" style="width: 20%;"><?php echo ($service->getIsTypeFee() == 1) ? '<code>'.$count_value_text.'</code>' : $count_value_text;?></th>
				<th class="text-center w-20" style="width: 10%;"><?php echo __('Count ceil')?></th>
				<th class="text-center w-25" style="width: 25%;"><?php echo __('Split value')?></th>
				<th class="text-right w-25" style="width: 25%;"><?php echo __('Service amount', array(), 'messages') ?></th>
				<th data-hide="phone,tablet" id="sf_admin_list_th_actions"
					class="text-center w-10" style="width: 20%;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($ps_service_splits as $name => $ps_service_split): ?>
		<tr
				<?php if ($service_split->getId() == $ps_service_split->getId()) :?>
				class="warning" <?php endif;?>>
				<td class="text-center"><?php echo $ps_service_split->getCountValue();?></td>
				<td class="text-center"><?php echo $ps_service_split->getCountCeil();?></td>
				<td class="text-center">
			<?php echo PreNumber::number_clean($ps_service_split->getSplitValue())?></td>
				<td class="text-right">
			<?php
			if ($service_detail) {
				$amount = $service_detail->getAmount ();
				$percent = $ps_service_split->getSplitValue ();
				$percent_price = ($amount * $service_detail->getByNumber () * $percent) / 100;
				include_partial ( 'global/field_custom/_list_field_price', array (
						'value' => $percent_price ) );
			} else {
				echo '<code>' . __ ( 'Undefined' ) . '</code>';
			}
			?>
			</td>
				<td class="text-center">
					<div class="btn-group">
			    
			    <?php echo $helper->linkToEdit($ps_service_split, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
			    
			    <?php echo $helper->linkToDelete($ps_service_split, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
			    
			</div>
				</td>
			</tr>
		<?php endforeach; ?>	
		</tbody>
	</table>
</div>