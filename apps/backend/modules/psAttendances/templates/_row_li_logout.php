<?php
$student_id = $list_student->getStudentId ();

$logout_relative_id = null;
$logout_at = null;
$logout_member_id = null;

$disable = 'disabled';

if ($list_student->getId () > 0) {

	$login_relative_id = ($list_student->getlogoutRelativeId ()) ? $list_student->getlogoutRelativeId () : '';

	$logout_member_id = ($list_student->getlogoutMemberId ()) ? $list_student->getlogoutMemberId () : '';

	$disable = '';
}

$logout_at = ($list_student->getLogoutAt () != '') ? date ( 'H:i', strtotime ( $list_student->getLogoutAt () ) ) : date ( 'H:i', strtotime ( "now" ) );
// foreach ($ps_off_school as $off_school){
// if ($off_school->getStudentId() == $student_id){
// $absent = 1;
// }
// }

?>

<li
	class="col-md-3 col-sm-3 col-xs-6 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding-top: 5px;"><label class="select" style="width: 100%"> <select
		class="form-control" style="width: 100%;"
		name="student_logtime[<?php echo $student_id; ?>][relative_logout]"
		id="select_relative_<?php echo $student_id; ?>">
    		<?php //if (count($list_relative) == 0) : ?>
    		<option selected value=""><?php echo __('-Select logout relative-') ?></option>
    		<?php //endif; ?>	
    		<?php foreach ($list_relative  as $relative) : ?>
    		<?php if ( $relative->getRelativeId() == $login_relative_id): ?>
    		<option selected value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
    		<?php else : ?>
    		<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
    		<?php endif; ?>													
    		<?php endforeach;?>
    </select>
</label></li>

<li
	class="col-md-3 col-sm-3 col-xs-6 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding-top: 5px;"><label class="select" style="width: 100%"> <select
		class="form-control" style="width: 100%"
		name="student_logtime[<?php echo $student_id; ?>][member_logout]"
		id="select_<?php echo $student_id; ?>_member_logout">
        <?php //if (count($list_member) == 0) : ?>
        <option selected value=""><?php echo __('-Select logout member-') ?></option>
        <?php //endif; ?>	
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
	class="col-md-2 col-sm-2 col-xs-6 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding: 5px;">
	<div class="input-group" style="width: 100%">
		<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>
		<input id="logout_at_<?php echo $list_student->getStudentId() ?>"
			name="student_logtime[<?php echo $list_student->getStudentId() ?>][logout_at]"
			class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
			maxlength="5" value="<?php echo $logout_at ?>">
	</div>

</li>

<li
	class="col-md-4 col-sm-4 col-xs-6 <?php if ($list_student->getId() && $list_student->getLogoutAt() =='') echo 'bg-color-orange';?>"
	style="padding: 5px;"><input type="text" class="form-control"
	style="width: 100%" name="note"
	id="note_<?php echo $list_student->getStudentId() ?>"
	placeholder="<?php echo __('Enter note')?>"
	value="<?php echo $list_student->getNote() ?>"></li>
<?php foreach ($list_relative  as $relatives) : ?>
<input type="checkbox"
	id="relative_class_<?php echo $list_student->getStudentId() ?>"
	class="relative_class_<?php echo $list_student->getStudentId() ?> hidden"
	checked <?php //echo $disable?>
	name="student_logtime[<?php echo $list_student->getStudentId() ?>][relative_class][]"
	value="<?php echo $relatives->getRelativeId();?>" />
<?php endforeach;?>