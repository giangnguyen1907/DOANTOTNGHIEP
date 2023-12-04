<?php
$student_id = $ps_logtimes->getStudentId ();

// $logtime = Doctrine::getTable('PsLogtimes')->getLogtimeByTrackedAt($student_id, $filter_value['tracked_at']);

$disable = ($check_logtime) ? 'disabled' : '';

/*
 * if ($logtime) {
 * $login_relative_id = ($logtime->getLoginRelativeId()) ? $logtime->getLoginRelativeId() : '';
 * $login_member_id = ($logtime->getLoginMemberId()) ? $logtime->getLoginMemberId() : '';
 * $login_at = ($logtime->getLoginAt()) ? date('H:i', strtotime($logtime->getLoginAt())) : '';
 * $disable = '';
 * }
 * else $login_at = date('H:i');
 */

if ($ps_logtimes->getId () > 0) {

	$login_relative_id = ($ps_logtimes->getLoginRelativeId ()) ? $ps_logtimes->getLoginRelativeId () : '';

	$login_member_id = ($ps_logtimes->getLoginMemberId ()) ? $ps_logtimes->getLoginMemberId () : '';

	$login_at = ($ps_logtimes->getLoginAt ()) ? date ( 'H:i', strtotime ( $ps_logtimes->getLoginAt () ) ) : '';

	$disable = '';
} else {
	$login_at = date ( 'H:i' );
	$disable = 'disabled';
}
?>
<div>
	<input type="hidden"
		name="student_logtime[<?php echo $student_id; ?>][student_id]"
		value="<?php echo $student_id ?>"> <label class="select"> <select
		class="form-control" <?php echo $disable?>
		name="student_logtime[<?php echo $student_id; ?>][relative_login]"
		id="select_<?php echo $student_id; ?>_relative_login"
		style="width: 200px;">
<?php if (count($list_relative) == 0) : ?>
<option selected value=""><?php echo __('-Select login relative-') ?></option>
<?php endif; ?>	
<?php foreach ($list_relative as $relative) : ?>
<?php if ( $relative->getRelativeId() == $login_relative_id): ?>
<option selected value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
<?php else : ?>
<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
<?php endif; ?>													
<?php endforeach;?>
</select>
	</label>

</div>
<div>
	<label class="select"> <select class="form-control"
		<?php echo $disable?>
		name="student_logtime[<?php echo $student_id; ?>][member_login]"
		id="select_<?php echo $student_id; ?>_member_login"
		style="width: 200px;">
<?php if (count($list_member) == 0) : ?>
<option selected value=""><?php echo __('-Select login member-') ?></option>
<?php endif; ?>	
<?php foreach ( $list_member as $member) :?>
<?php if ( $member->getId() == $login_member_id): ?>
<option selected value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php else : ?>
<option value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php endif; ?>								
<?php endforeach; ?>
</select> <i></i>
	</label>
</div>

<div class="input-group ">
	<span class="input-group-addon"><i class="icon-append fa fa-calendar"></i></span>
	<input <?php echo $disable?>
		name="student_logtime[<?php echo $ps_logtimes->getStudentId() ?>][login_at]"
		class="timepicker form-control input-sm_<?php echo $student_id;?>_logout"
		value="<?php echo $login_at ?>" style="width: 100px;">
</div>
