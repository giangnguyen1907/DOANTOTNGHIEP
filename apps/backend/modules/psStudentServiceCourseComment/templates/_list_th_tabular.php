<?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'view_img';

if ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'iorder' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone"';
 elseif ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone,tablet"';
endif;

$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_view_img"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('View img', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'student_name';

if ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'iorder' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone"';
 elseif ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone,tablet"';
endif;

$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_student_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('Student name', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'list_subject_option';

if ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'iorder' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone"';
 elseif ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone,tablet"';
endif;

$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_list_subject_option"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('List subject option', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>