<?php
$student_id = $ps_logtimes->getStudentId ();
$disable = ($check_logtime) ? 'disabled' : '';
$logtime = Doctrine::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $filter_value ['tracked_at'] );
if ($logtime) {
	$value = ($logtime->getNote ()) ? $logtime->getNote () : '';
	$disable = '';
} else
	$value = '';
?>

<input class="form-control input-sm_<?php echo $student_id;?>_logout"
	<?php echo $disable?> type="text"
	name="student_logtime[<?php echo $ps_logtimes->getStudentId() ?>][note]"
	value="<?php echo $value ?>">


