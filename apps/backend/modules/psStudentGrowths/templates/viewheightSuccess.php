<?php use_helper('I18N', 'Date') ?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
.modal-body {
	padding-top: 0 !important
}
</style>
<?php

if ($growths == 0) {
	$index = 'Normal';
} elseif ($growths == - 1) {
	$index = 'Lever1';
} elseif ($growths == - 2) {
	$index = 'Lever2';
} else {
	$index = 'Tall';
}
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('List student height '.$index) ?></h4>
</div>
<div class="modal-body">

	<div class="row">

		<div class="tab-content">
			<h5><?php echo $class_name->getSchoolName().' , '.$class_name->getWpName(). ' , '.__('Class') .' : '.$class_name->getMcName() ?></h5>
			<p><?php echo __('Examination').' : '.$examination->getName().' , '.__('Date').' : '.$examination->getInputDateAt(); ?></p>
			<br>
			<div id="home" class="tab-pane fade in active">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<th class="text-center"><?php echo __('Name') ?></th>
							<th class="text-center"><?php echo __('Brithday') ?></th>
							<th class="text-center"><?php echo __('Sex') ?></th>
							<th class="text-center"><?php echo __('Time examination') ?></th>
							<th class="text-center"><?php echo __('Height') ?></th>
							<th class="text-center"><?php echo __('Index height') ?></th>
						</thead>
						<tbody>
              <?php foreach ($growths_height as $ps_student_growths): ?>
                <tr>
								<td class="text-center"> <?php echo $ps_student_growths->getStudentName() ?> </td>
								<td class="text-center"> 
                  	<?php echo $ps_student_growths->getBirthday() ?><br />
									<code><?php echo PreSchool::getAge($ps_student_growths->getBirthday(),false)?></code>
								</td>
								<td class="text-center"> 
                  	<?php echo get_partial('psStudentGrowths/sex', array('type' => 'list', 'ps_student_growths' => $ps_student_growths)) ?>
                  </td>
								<td class="text-center"><code><?php echo PreSchool::getMonthYear1($ps_student_growths->getBirthday(),$examination->getInputDateAt())?></code>
								</td>
								<td class="text-center"> <?php echo $ps_student_growths->getHeight() ?> </td>
								<td class="text-center"> 
					<?php echo get_partial('psStudentGrowths/index_height', array('type' => 'list', 'ps_student_growths' => $ps_student_growths)) ?>
				  </td>

							</tr>
              <?php endforeach ?>
            </tbody>
            <?php //echo $growths; ?>
          </table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">
	<a class="btn btn-default"
		href="<?php echo url_for(@ps_student_growths).'/'.$examination->getId().'/'.$class_name->getId().'/'.$growths.'/'; ?>export_height"><i
		class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>
