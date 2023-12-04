<?php
$student_id = $ps_logtimes->getStudentId ();
if ($ps_logtimes->getId ()) {
	$value = ($ps_logtimes->getNote ()) ? $ps_logtimes->getNote () : '';
	$disable = '';
} else {
	$value = '';
	$disable = 'disabled';
}
?>

<input class="form-control input-sm_<?php echo $student_id;?>_logout"
	<?php echo $disable?> type="text" maxlength="255"
	name="student_logtime[<?php echo $ps_logtimes->getStudentId() ?>][note]"
	value="<?php echo $value ?>">