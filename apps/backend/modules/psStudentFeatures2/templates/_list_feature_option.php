<style>
textarea.form-control {
	height: 100px !important;
	float: left;
}

label.checkbox {
	float: left;
	padding-bottom: 10px;
}
</style>

<?php foreach ($list_options as $option) :?>

<?php if ($option->getType() == 1): ?>
    <?php if ($option->getMode() == 1): ?>
<label class="radio radio-inline"> <input class="radiobox"
	onclick="javascript:toggleText_radio('<?php echo $student_feature->getStudentId(); ?>',this.value);"
	name="feature_option[<?php echo $student_feature->getStudentId();?>]"
	type="radio" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentFeatureId()): ?> checked="checked"
	<?php endif;?>> <span><?php echo __($option->getName());?></span></label>
<?php endif;?>
    <?php if ($option->getMode() == 2): ?>
<label class="checkbox col-md-3"> <input class="checkbox"
	name="feature_option[<?php echo $student_feature->getStudentId();?>][<?php echo $option->getId(); ?>]"
	type="checkbox" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentFeatureId()): ?> checked="checked"
	<?php endif;?>><span><?php echo __($option->getName());?></span></label>
<?php endif;?>
<?php endif;?>
<?php if ($option->getType() == 2): ?>
    <?php if ($option->getMode() == 1): ?>
<label class="radio radio-inline"> <input class="radiobox"
	onclick="javascript:toggleText_radio('<?php echo $student_feature->getStudentId(); ?>',this);"
	name="feature_option[<?php echo $student_feature->getStudentId();?>]"
	type="radio" value="<?php echo $option->getId(); ?>"
	<?php if ($option->getStudentFeatureId()): ?> checked="checked"
	<?php endif;?>> <span><?php echo __($option->getName());?></span></label>

<?php if (!$option->getStudentFeatureId()): ?>

<textarea maxlength="5000"
	class="form-control branch_<?php echo $student_feature->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>"
	id="textbox_radio_<?php echo $student_feature->getStudentId(); ?>"
	disabled="disabled"
	name="feature_option[<?php echo $student_feature->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
	data-fv-field="feature_option[textbox]" value=""></textarea>

<?php else: ?>

<textarea maxlength="5000"
	class="form-control branch_<?php echo $student_feature->getStudentId(); ?>"
	title="<?php echo __('Enter comment')?>"
	id="textbox_radio_<?php echo $student_feature->getStudentId(); ?>"
	name="feature_option[<?php echo $student_feature->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"><?php echo $option->getNote()?></textarea>

<?php endif;?>
    <?php endif;?>
    <?php if ($option->getMode() == 2): ?>
<div class="col-md-3 row">
	<label class="checkbox"> <input class="checkbox"
		onclick="javascript:toggleText_radio('<?php echo $student_feature->getStudentId(); ?>',this);"
		name="feature_option[<?php echo $student_feature->getStudentId();?>][<?php echo $option->getId(); ?>]"
		type="checkbox" value="<?php echo $option->getId(); ?>"
		<?php if ($option->getStudentFeatureId()): ?> checked="checked"
		<?php endif;?>> <span><?php echo __($option->getName());?></span></label>
        	<?php if (!$option->getStudentFeatureId()): ?>
        	
        	<textarea maxlength="5000"
		class="form-control branch_<?php echo $student_feature->getStudentId(); ?>"
		title="<?php echo __('Enter comment')?>"
		id="textbox_radio_<?php echo $student_feature->getStudentId(); ?>"
		disabled="disabled"
		name="feature_option[<?php echo $student_feature->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"
		data-fv-field="feature_option[textbox]"></textarea>
        	
        	<?php else: ?>
        	
        	<textarea maxlength="5000"
		class="form-control branch_<?php echo $student_feature->getStudentId(); ?>"
		id="textbox_radio_<?php echo $student_feature->getStudentId(); ?>"
		title="<?php echo __('Enter comment')?>"
		name="feature_option[<?php echo $student_feature->getStudentId(); ?>][<?php echo $option->getId() ?>][textbox]"><?php echo $option->getNote()?></textarea>
    	 <?php endif;?>
    </div>
<?php endif;?>
<?php endif;?>
<?php endforeach; ?>
