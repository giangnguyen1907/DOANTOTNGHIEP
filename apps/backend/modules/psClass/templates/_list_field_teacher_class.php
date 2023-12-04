<?php
$ps_teacher_class = $my_class->getTeachers ();
?>

<div class="custom-scroll table-responsive" style="<?php if (count($ps_teacher_class) > 10) {?> height:200px; <?php };?>overflow-y: scroll;">
<?php
foreach ( $ps_teacher_class as $ps_teacher ) {

	echo '<code>' . $ps_teacher->getMemberCode () . '</code>-';
	if ($ps_teacher->getPrimaryTeacher () == PreSchool::ACTIVE)
		echo $ps_teacher->getFullName () . ' <i class="font-xs">(' . __ ( 'HTeacher' ) . ')<br></i>';
	else
		echo $ps_teacher->getFullName () . '<br>';
}
?>
</div>