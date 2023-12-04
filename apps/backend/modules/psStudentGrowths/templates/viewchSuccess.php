<?php use_helper('I18N', 'Date') ?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
@media ( min-width : 992px) .modal-lg {
	min-width
	:
	 
	900
	px
	;
	
	    
	width
	:
	 
	1200
	px
	;
	
	
}

.modal-lg {
	min-width: 900px;
	width: 1200px;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Hsdakham') ?></h4>
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
							<th class="text-center"><?php echo __('Weight') ?></th>
							<th class="text-center"><?php echo __('Index weight') ?></th>
						</thead>

						<tbody>
              <?php foreach ($growths_ch as $ps_student_growths): ?>
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
								<td class="text-center"> <?php echo $ps_student_growths->getWeight() ?> </td>
								<td class="text-center">
					<?php echo get_partial('psStudentGrowths/index_weight', array('type' => 'list', 'ps_student_growths' => $ps_student_growths)) ?>
				  </td>
							</tr>
              <?php endforeach ?>
            </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">
	<a class="btn btn-default"
		href="<?php echo url_for(@ps_student_growths).'/'.$class_name->getId().'/'.$examination->getId().'/'; ?>export_examined"><i
		class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>
