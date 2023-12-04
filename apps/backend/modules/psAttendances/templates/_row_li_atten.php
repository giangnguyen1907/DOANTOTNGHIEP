<?php
$student_id = $list_student->getStudentId ();

// $disable = ($list_student->getId() > 0) ? '' : 'disabled';
$disable = ($check_logtime) ? 'disabled' : '';

$disable = 'disabled';
$login_relative_id = null;
$login_at = null;
$login_member_id = null;

if ($list_student->getId () > 0) {

	$login_relative_id = ($list_student->getloginRelativeId ()) ? $list_student->getloginRelativeId () : '';

	$login_member_id = ($list_student->getloginMemberId ()) ? $list_student->getloginMemberId () : '';

	$disable = '';
}

$login_at = ($list_student->getLoginAt () != '') ? date ( 'H:i', strtotime ( $list_student->getLoginAt () ) ) : date ( 'H:i', strtotime ( "now" ) );
foreach ( $ps_off_school as $off_school ) {
	if ($off_school->getStudentId () == $student_id) {
		$absent = 1;
	}
}

if ($list_student->getId () && $list_student->getLoginMemberId () >= 1 && $list_student->getLoginMemberId () < 2) {
	$label_load = 'bg-color-green';
} elseif ($list_student->getId () && $list_student->getLoginMemberId () >= 0 && $list_student->getLoginMemberId () < 1) {
	$label_load = 'bg-color-red';
} elseif ($list_student->getId () && $list_student->getLoginMemberId () > 1) {
	$label_load = 'bg-color-orange';
}
?>

<?php
if ($list_student->getId ()) {
	if ($list_student->getLogValue () == 1) {
		$bg_color = 'background-color:#356e35!important';
	} elseif ($list_student->getLogValue () == 2) {
		$bg_color = 'background-color:#b09b5b!important';
	} else {
		$bg_color = 'background-color: cadetblue';
	}
}
?>

<div class="row">
	<div class="col-md-12">
		<li class="col-md-6 col-sm-6 col-xs-6" style="padding-top:5px;<?php echo $bg_color; ?>">
			<label class="select" style="width: 100%"> <select
				class="form-control" <?php echo $disable?> style="width: 100%;"
				name="student_logtime[<?php echo $student_id; ?>][relative_login]"
				id="select_<?php echo $student_id; ?>_relative_login">
	    		<?php //if (count($list_relative) == 0 || $absent == 1) : ?>
	    		<option selected value=""><?php echo __('-Select login relative-') ?></option>
	    		<?php //endif; ?>	
	    		<?php foreach ($list_relative  as $relative) : ?>
	    		<?php if ( $relative->getRelativeId() == $login_relative_id): ?>
	    		<option selected
						value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
	    		<?php else : ?>
	    		<option value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
	    		<?php endif; ?>													
	    		<?php endforeach;?>
	    </select>
		</label>
		</li>

		<li class="col-md-6 col-sm-6 col-xs-6" style="padding-top:5px;<?php echo $bg_color; ?>">
			<label class="select" <?php echo $disable?> style="width: 100%"> <select
				class="form-control" <?php echo $disable?> style="width: 100%"
				name="student_logtime[<?php echo $student_id; ?>][member_login]"
				id="select_<?php echo $student_id; ?>_member_login">
	        <?php //if (count($list_member) == 0) : ?>
	        <option value=""><?php echo __('-Select login member-') ?></option>
	        <?php //endif; ?>	
	        <?php foreach ( $list_member as $member) :?>
	        <?php if ( $member->getId() == $login_member_id): ?>
	        <option selected value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
	        <?php else : ?>
	        <option value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
	        <?php endif; ?>								
	        <?php endforeach; ?>
	    </select>
		</label>
		</li>
	</div>
	<div class="col-md-12">
		<li class="col-md-6 col-sm-6 col-xs-6" style="padding-bottom:5px;<?php echo $bg_color; ?>">
			<div class="input-group" style="width: 100%">
				<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>
				<input <?php echo $disable?>
					id="login_at_<?php echo $list_student->getStudentId() ?>"
					name="student_logtime[<?php echo $list_student->getStudentId() ?>][login_at]"
					class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
					maxlength="5" value="<?php echo $login_at ?>">
			</div>
		</li>
		<li class="col-md-6 col-sm-6 col-xs-6" style="padding-bottom:5px;<?php echo $bg_color; ?>">
			<input
			name="student_logtime[<?php echo $list_student->getStudentId() ?>][note]"
			type="text" <?php echo $disable?> class="form-control"
			style="width: 100%"
			id="note_<?php echo $list_student->getStudentId() ?>"
			placeholder="<?php echo __('Enter note')?>"
			value="<?php echo $list_student->getNote() ?>">
		</li>
	<?php foreach ($list_relative  as $relatives) : ?>
		<input type="checkbox"
			id="relative_class_<?php echo $list_student->getStudentId() ?>"
			class="relative_class_<?php echo $list_student->getStudentId() ?> hidden"
			<?php //echo $disable?>
			name="student_logtime[<?php echo $list_student->getStudentId() ?>][relative_class][]"
			value="<?php echo $relatives->getRelativeId();?>" />
	<?php endforeach;?>
	</div>
</div>