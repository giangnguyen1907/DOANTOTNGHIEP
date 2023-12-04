<?php
$status_student = PreSchool::loadStatusStudentClass ();

$status = $student->getStatus ();
$class = '';
if ($status == PreSchool::SC_STATUS_OFFICIAL)
	$class = 'label-success';
elseif ($status == PreSchool::SC_STATUS_TEST)
	$class = 'label-primary';
elseif ($status == PreSchool::SC_STATUS_PAUSE)
	$class = 'label-warning';
elseif ($status == PreSchool::SC_STATUS_STOP_STUDYING)
	$class = 'label-danger';
elseif ($status == PreSchool::SC_STATUS_GRADUATION)
	$class = 'label-primary';

if (isset ( $status_student [$status] ))
	echo '<span class="label ' . $class . '">' . __ ( $status_student [$status] ) . '</span>';
?>