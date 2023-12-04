<?php
$student_id = $list_student->getStudentId ();

$logout_relative_id = null;
$logout_at = null;
$logout_member_id = null;

$disable = 'disabled';

if ($list_student->getId () > 0) {

	$logout_relative_id = ($list_student->getlogoutRelativeId ()) ? $list_student->getlogoutRelativeId () : '';

	$logout_member_id = ($list_student->getlogoutMemberId ()) ? $list_student->getlogoutMemberId () : '';

	$logout_at = ($list_student->getLogoutAt ()) ? date ( 'H:i', strtotime ( $list_student->getLogoutAt () ) ) : date ( 'H:i', strtotime ( "now" ) );

	$disable = '';
}
?>
<li
	class="col-md-3 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding-top: 5px;"><label class="select" style="width: 100%"> <select
		class="form-control" style="width: 100%;" <?php echo $disable?>
		name="student_logtime[<?php echo $list_student->getStudentId(); ?>][relative_logout]"
		id="select_<?php echo $list_student->getStudentId(); ?>">
    		<?php if ($attendances_relative == 0) : ?>
    		<option selected value=""><?php echo __('-Select logout relative-') ?></option>
    		<?php endif; ?>	
    		<?php foreach ($list_relative  as $relative) : ?>
    		<?php if ( $relative->getRelativeId() == $logout_relative_id): ?>
    		<option selected value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
    		<?php else : ?>
    		<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
    		<?php endif; ?>													
    		<?php endforeach;?>
    </select>
</label></li>

<li
	class="col-md-3 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding-top: 5px; display: none;"><label class="select"
	style="width: 100%"> <select class="form-control" <?php echo $disable?>
		style="width: 100%"
		name="student_logtime[<?php echo $list_student->getStudentId(); ?>][member_logout]"
		id="select_<?php echo $list_student->getStudentId(); ?>_member_logout">
        <?php if (count($list_member) == 0 || $list_student->getId() <= 0) : ?>
        <option selected value=""><?php echo __('-Select logout member-') ?></option>
        <?php endif; ?>	
        <?php foreach ( $list_member as $member) :?>
        <?php if ( $member->getId() == $logout_member_id): ?>
        <option selected value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
        <?php else : ?>
        <option value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
        <?php endif; ?>								
        <?php endforeach; ?>
    </select>
</label></li>

<li
	class="col-md-2 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding: 5px;">
	<div class="input-group" style="width: 100%">
		<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>
		<input <?php echo $disable?>
			id="logout_at_<?php echo $list_student->getStudentId() ?>"
			name="student_logtime[<?php echo $list_student->getStudentId() ?>][logout_at]"
			class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
			maxlength="5" value="<?php echo $logout_at ?>">
	</div>

</li>

<li
	class="col-md-4 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding: 5px;"><input type="text" class="form-control"
	style="width: 100%" name="note"
	id="note_<?php echo $list_student->getStudentId() ?>"
	placeholder="<?php echo __('Enter note')?>"
	value="<?php echo $list_student->getNote() ?>"></li>