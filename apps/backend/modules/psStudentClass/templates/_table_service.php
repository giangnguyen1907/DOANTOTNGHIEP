<?php use_helper('I18N', 'Date') ?>
<table id="dt_basic"
	class="table table-striped table-bordered table-hover" width="100%">
	<tr>
		<th style="width: 100px;" class="text-center"><?php echo __('Icon');?></th>
		<th style="width: 200px;"><?php echo __('Service name');?></th>
		<th class="center-text" style="width: 200px;"><?php echo __('Enable roll');?></th>
		<th class="center-text" style="width: 200px;"><?php echo __('Service amount');?></th>
		<th class="center-text" style="width: 220px;"><?php echo __('Service detail at');?></th>
		<th class="center-text" style="width: 100px;"><?php echo __('By number');?></th>
		<th class="center-text" style="width: 100px;"><?php echo __('Select');?></th>
	</tr>
		<?php foreach($list_service as $service): ?>
		<tr>
		<td class="center-text">			
			<?php
			if ($service->getFileName () != '') {
				echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
						'style' => 'max-width:30px;text-align:center;' ) );
			}
			?>
			</td>
		<td><?php echo $service->getTitle();?></td>
		<td><?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?></td>			
			<?php $servicedetails = $service->getServiceDetailByDate(time());?>
			
			<td><?php echo $servicedetails['amount'] ? $servicedetails['amount'] : 'aaa';?></td>
		<td><code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code></td>
		<td><?php echo $servicedetails['by_number'];?></td>
		<td class="text-center" style="width: 100px;"><input class="select"
			type="checkbox"
			name="form_student_service[<?php echo $service->getId() ?>][select]"
			id="form_student_service_1_select"></td>
	</tr>
		<?php endforeach;?>
</table>