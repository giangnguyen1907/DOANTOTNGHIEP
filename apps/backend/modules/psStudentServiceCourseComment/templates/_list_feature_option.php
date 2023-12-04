<?php foreach ($list_options as $option) :?>

<?php if ($option->getType() == 1): ?>
    <?php if ($option->getMode() == 1): ?>
<label class="radio radio-inline"> <input class="radiobox"
	onclick="javascript:toggleText_radio('<?php echo $student_service_course_comment->getStudentId(); ?>',this.value);"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId();?>]"
	type="radio" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentServiceCourseCommentId()): ?>
	checked="checked" <?php endif;?>> <span><?php echo __($option->getName());?></span></label>
<?php endif;?>
    <?php if ($option->getMode() == 2): ?>
<label class="checkbox"> <input class="checkbox"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId();?>][<?php echo $option->getId(); ?>]"
	type="checkbox" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentServiceCourseCommentId()): ?>
	checked="checked" <?php endif;?>><span><?php echo __($option->getName());?></span></label>
<?php endif;?>
<?php endif;?>
<?php if ($option->getType() == 2): ?>
    <?php if ($option->getMode() == 1): ?>
<label class="radio radio-inline"><input class="radiobox"
	onclick="javascript:toggleText_radio('<?php echo $student_service_course_comment->getStudentId(); ?>',this);"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId();?>]"
	type="radio" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentServiceCourseCommentId()): ?>
	checked="checked" <?php endif;?>> <span><?php echo __($option->getName());?></span></label>
<?php if (!$option->getStudentServiceCourseCommentId()): ?>
<input
	class="form-control branch_<?php echo $student_service_course_comment->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>"
	id="textbox_radio_<?php echo $student_service_course_comment->getStudentId(); ?>"
	type="text" disabled="disabled"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
	maxlength="255" data-fv-field="feature_option[textbox]" value="">
<?php else: ?>
<input
	class="form-control branch_<?php echo $student_service_course_comment->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>"
	id="textbox_radio_<?php echo $student_service_course_comment->getStudentId(); ?>"
	type="text"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
	maxlength="255" value="<?php echo$option->getNote()?>">
<?php endif;?>
    <?php endif;?>
    <?php if ($option->getMode() == 2): ?>
<label class="checkbox"> <input class="checkbox"
	onclick="javascript:toggleText_radio('<?php echo $student_service_course_comment->getStudentId(); ?>',this);"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId();?>][<?php echo $option->getId(); ?>]"
	type="checkbox" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentServiceCourseCommentId()): ?>
	checked="checked" <?php endif;?>> <span><?php echo __($option->getName());?></span></label>
<?php if (!$option->getStudentServiceCourseCommentId()): ?>
<input
	class="form-control branch_<?php echo $student_service_course_comment->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>"
	id="textbox_radio_<?php echo $student_service_course_comment->getStudentId(); ?>"
	type="text" disabled="disabled"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
	maxlength="255" data-fv-field="feature_option[textbox]" value="">
<?php else: ?>
<input
	class="form-control branch_<?php echo $student_service_course_comment->getStudentId(); ?>"
	id="textbox_radio_<?php echo $student_service_course_comment->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>" maxlength="255" type="text"
	name="feature_option[<?php echo $student_service_course_comment->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
	value="<?php echo $option->getNote()?>">
<?php endif;?>
    <?php endif;?>
<?php endif;?>
<?php endforeach; ?>
