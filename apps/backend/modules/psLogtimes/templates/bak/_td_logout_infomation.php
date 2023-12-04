
<?php
$student_id = $ps_logtimes->getStudentId ();
$ps_customer_id = $ps_logtimes->getPsCustomerId ();
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $student_id, $ps_customer_id );

// $list_member = Doctrine::getTable('PsTeacherClass')->getTeachersByClassId($ps_logtimes->getClassId());

$logtime = Doctrine::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $filter_value ['tracked_at'] );

$logout_at = '--:--';
$logout_relative_id = $logout_member_id = null;
$disable = ($check_logtime) ? 'disabled' : '';
if ($logtime) {
	$logout_relative_id = ($logtime->getLogoutRelativeId ()) ? $logtime->getLogoutRelativeId () : '';
	$logout_member_id = ($logtime->getLogoutMemberId ()) ? $logtime->getLogoutMemberId () : '';
	$logout_at = ($logtime->getLogoutAt ()) ? date ( 'H:i', strtotime ( $logtime->getLogoutAt () ) ) : date ( 'H:i' );
	$disable = '';
}

?>

<div>
	<label class="select"> <select class="form-control"
		<?php echo $disable?>
		name="student_logtime[<?php echo $student_id; ?>][relative_logout]"
		id="select_<?php echo $student_id; ?>_relative_logout"
		style="width: 200px;">
<?php if (count($list_relative) == 0) : ?>
<option selected value=""><?php echo __('-Select logout relative-') ?></option>
<?php endif; ?>		
<?php foreach ($list_relative as $relative) : ?>
<?php if ( $relative->getRelativeId() == $logout_relative_id): ?>
<option selected value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
<?php else : ?>
<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
<?php endif; ?>													
<?php endforeach;?>
</select> <i></i>
	</label>
</div>
<div>
	<label class="select"> <select class="form-control"
		<?php echo $disable?>
		name="student_logtime[<?php echo $student_id; ?>][member_logout]"
		id="select_<?php echo $student_id; ?>_member_logout"
		style="width: 200px;">
<?php if (count($list_member) == 0) : ?>
<option selected value=""><?php echo __('-Select logout member-') ?></option>
<?php endif; ?>	
<?php foreach ( $list_member as $member) :?>
<?php if ( $member->getId() == $logout_member_id): ?>
<option selected value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php else : ?>
<option value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php endif; ?>								
<?php endforeach; ?>
</select> <i></i>
	</label>
</div>

<input <?php echo $disable?>
	name="student_logtime[<?php echo $ps_logtimes->getStudentId() ?>][logout_at]"
	class="timepicker form-control input-sm_<?php echo $student_id;?>_logout"
	value="<?php echo $logout_at ?>" style="width: 100px;">
