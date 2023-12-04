<?php
$student_id = $ps_logtimes->getStudentId ();

// $logtime = Doctrine::getTable('PsLogtimes')->getLogtimeByTrackedAt($student_id, $filter_value['tracked_at']);

$disable = ($check_logtime) ? 'disabled' : '';

$logout_relative_id = null;
$logout_at = null;
$logout_member_id = null;

$disable = 'disabled';

if ($ps_logtimes->getId () > 0) {

	$logout_relative_id = ($ps_logtimes->getlogoutRelativeId ()) ? $ps_logtimes->getlogoutRelativeId () : '';

	$logout_member_id = ($ps_logtimes->getlogoutMemberId ()) ? $ps_logtimes->getlogoutMemberId () : '';

	$logout_at = ($ps_logtimes->getLogoutAt ()) ? date ( 'H:i', strtotime ( $ps_logtimes->getLogoutAt () ) ) : date ( 'H:i', strtotime ( "now" ) );

	$disable = '';
}
?>
<input type="hidden"
	name="student_logtime[<?php echo $student_id; ?>][student_id]"
	value="<?php echo $student_id ?>">
<label class="select" style="width: 100%"> <select class="form-control"
	<?php echo $disable?> style="width: 100%"
	name="student_logtime[<?php echo $student_id; ?>][relative_logout]"
	id="select_<?php echo $student_id; ?>_relative_logout">
		<?php //if (count($list_relative) == 0 || $ps_logtimes->getId() <= 0) : ?>
		<option selected value=""><?php echo __('-Select logout relative-') ?></option>
		<?php //endif; ?>	
		<?php foreach ($list_relative as $relative) : ?>
		<?php if ( $relative->getRelativeId() == $logout_relative_id): ?>
		<option selected value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
		<?php else : ?>
		<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
		<?php endif; ?>													
		<?php endforeach;?>
</select><i></i>
</label>

<label class="select" style="width: 100%"> <select class="form-control"
	<?php echo $disable?> style="width: 100%"
	name="student_logtime[<?php echo $student_id; ?>][member_logout]"
	id="select_<?php echo $student_id; ?>_member_logout">
		<option selected value=""><?php echo __('-Select logout member-') ?></option>
<?php //if (count($list_member) == 0 || $ps_logtimes->getId() <= 0) : ?>
<option selected value="<?php echo myUser::getUserId() ?>"><?php echo myUser::getUser()->getFirstName() ." ". myUser::getUser()->getLastName() ?></option>
<?php //endif; ?>
	
<?php foreach ( $list_member as $member) :?>
<?php if ( $member->getId() == $logout_member_id): ?>
<option selected value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php else : ?>
<option value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
<?php endif; ?>								
<?php endforeach; ?>
</select>
</label>

<div class="input-group" style="width: 100%">
	<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>
	<input <?php echo $disable?>
		name="student_logtime[<?php echo $ps_logtimes->getStudentId() ?>][logout_at]"
		class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
		maxlength="5" value="<?php echo $logout_at ?>">
</div>
