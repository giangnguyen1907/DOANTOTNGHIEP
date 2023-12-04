<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php
$school_code = $ps_student->getPsCustomer ()
	->getSchoolCode ();
$enable_roll = PreSchool::loadPsRoll ();
?>
<div class="sf_admin_form" id="list_service">  
  	<?php include_partial('psStudentClass/table_service', array('school_code' => $school_code, 'list_service' => $list_service, 'configuration' => $configuration, 'helper' => $helper))?>
</div>
