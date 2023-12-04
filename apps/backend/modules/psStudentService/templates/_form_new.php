<?php use_helper('I18N', 'Date', 'Number')?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php
//$school_code = $ps_student->getPsCustomer ()->getSchoolCode ();
$enable_roll = PreSchool::loadPsRoll ();
?>
<div class="sf_admin_form">
	<div class="custom-scroll table-responsive"
		style="height: 300px; overflow-y: scroll;">
		<table id="dt_basic"
			class="table table-striped table-bordered table-hover" width="100%">
			<tr>
				<th style="width: 50px" class="text-center"><?php echo __('Icon');?></th>
				<th style="width: auto;"><?php echo __('Service');?></th>
				<th class="center-text" style="width: 10%"><?php echo __('Enable roll');?></th>
				<th class="center-text" style="width: 7%"><?php echo __('Service amount');?></th>
				<th class="center-text" style="width: 7%"><?php echo __('Service detail at');?></th>
				<th class="center-text" style="width: 7%"><?php echo __('By number');?></th>
				<th class="center-text" style="width: 10%"><?php echo __('Tần xuất thu');?></th>
				<th class="center-text" style="width: 15%"><?php echo __('Note');?></th>
				<th class="center-text" style="width: 60px;"><?php echo __('Select');?></th>
			</tr>
			<?php foreach($list_service as $service): ?>
					
			<tr>
				<td>			
				<?php
				if ($service->getFileName () != '') {
					echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
							'style' => 'max-width:45px;text-align:center;' ) );
				}
				?>
				</td>
				<td>
				<?php echo $service->getTitle();?><br /> <small
					style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
				</td>
				<td><?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?></td>			
				<?php $servicedetails = $service->getServiceDetailByDate(time());?>
				
				<td class="text-right"><?php echo PreNumber::number_format($servicedetails['amount']);?></td>
				<td class="center-text"><code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code></td>
				<td class="center-text"><?php echo $servicedetails['by_number'];?></td>
				
				<td class="text-center">
				<?php echo $form['regularity_id']->render(array('class'=>'form-control','name' => 'form_student_service['.$service->getId().'][regularity_id]')) ?>
				</td>
				<td class="text-center">
				<?php echo $form['note']->render(array('name' => 'form_student_service['.$service->getId().'][note]')) ?>
				</td>
				<td class="text-center"><label class="checkbox-inline">
				 <?php echo $form['select']->render(array('name' => 'form_student_service['.$service->getId().'][select]')) ?><span></span>
				</label></td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>
