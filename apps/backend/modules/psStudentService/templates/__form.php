<?php use_helper('I18N', 'Date', 'Number')?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php 
$school_code = $ps_student->getPsCustomer()->getSchoolCode();
$enable_roll = PreSchool::loadPsRoll();
?>
<div class="sf_admin_form">
  
  	<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
		<tr>
			<th style="width: 100px;" class="text-center"><?php echo __('Icon');?></th>
			<th style="width: 200px;"><?php echo __('Title');?></th>
			<th class="center-text" style="width: 200px;"><?php echo __('Enable roll');?></th>
			<th class="center-text" style="width: 120px;"><?php echo __('Service amount');?></th>
			<th class="center-text" style="width: 100px;"><?php echo __('Service detail at');?></th>
			<th class="center-text" style="width: 100px;"><?php echo __('By number');?></th>
			<th class="center-text"><?php echo __('Discount');?></th>
			<th class="center-text" style="width: 100px;"><?php echo __('Select');?></th>
		</tr>
		<?php foreach($list_service as $service): ?>
				
		<tr>
			<td>			
			<?php
			if ($service->getFileName() != '') {
			    echo image_tag ( '/sys_icon/' . $service->getFileName(), array('style' => 'max-width:45px;text-align:center;'));
            }
            ?>
			</td>
			<td>
			<?php echo $service->getTitle();?><br/>
			<small><i><?php echo __('School year').': '.$service->getSchoolYear();?></i></small>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?>
			</td>			
			<td><?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?></td>			
			<?php $servicedetails = $service->getServiceDetailByDate(time());?>
			
			<td class="text-right" ><?php echo PreNumber::format_number($servicedetails['amount']);?></td>
			<td><code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code></td>	
			<td><?php echo $servicedetails['by_number'];?></td>	
			<td class="text-center"  style="width: 200px;"><?php echo $form['discount']->render(array('name' => 'form_student_service['.$service->getId().'][discount]')) ?></td>		
			<td class="text-center"  style="width: 100px;">
			 <label class="checkbox-inline">
			<?php echo $form['select']->render(array('name' => 'form_student_service['.$service->getId().'][select]')) ?><span></span></label></td>	
		</tr>
		<?php endforeach;?>
	</table>
	


</div>
