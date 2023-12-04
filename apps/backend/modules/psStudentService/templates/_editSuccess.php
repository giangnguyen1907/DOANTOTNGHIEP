<?php use_helper('I18N', 'Date')?>
<?php
$enable_roll = PreSchool::loadPsRoll ();
$service = $ps_student_service->getService ();
$servicedetails = $service->getServiceDetailByDate ( time () );
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h5 class="modal-title">
	<?php echo __('Update service of student: %%student%%', array('%%student%%' => $ps_student->getFirstName().' '.$ps_student->getLastName()), 'messages')?>
		<small>
		(<?php if (false !== strtotime($ps_student->getBirthday())) echo format_date($ps_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($ps_student->getBirthday(),false).'</code>';?>) - <?php echo __('Class')?>: <?php echo ($student_class) ? $student_class->getName() : '';?>, <?php echo ($student_class) ? __('School year').' '.$student_class->getPsSchoolYear() : ''?>
		</small>
	</h5>
</div>
<?php echo form_tag_for($form, '@ps_student_service', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n'))?>
    <?php echo $form->renderHiddenFields(true)?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors()?>
    <?php endif; ?>
<div class="modal-body" style="overflow: hidden;">
	<div class="alert alert-info fade in">
		<i class="fa-fw fa fa-info"></i> <span class="lable"><?php echo __('Service')?>:</span>
		<code><?php echo $service->getTitle();?></code>
		(<small style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getPsSchoolYear()->getTitle();?></i>, <?php echo ($service->getPsWorkPlaces()->getTitle() != '') ? $service->getPsWorkPlaces()->getTitle() : __('Whole School');?></small>)
		- <span><?php echo __('Enable roll');?>: </span>
		<code><?php echo ($service->getEnableSchedule() == PreSchool::ACTIVE) ? __('Fixed schedule note').', ' : ''?> <?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?></code>
		<?php echo __('Service amount');?>: <code><?php echo PreNumber::number_format($servicedetails['amount']);?></code>
		<?php echo __('Service detail at');?>: <code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code>
	</div>
	<?php include_partial('psStudentService/form', array('ps_student_service' => $ps_student_service,'ps_student' => $ps_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>
</form>