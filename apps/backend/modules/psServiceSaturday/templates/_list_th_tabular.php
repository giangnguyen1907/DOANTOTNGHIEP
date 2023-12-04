<?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'student_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id"
	<?php echo $style_order; ?>>
  <?php if ('student_id' == $sort[0]): ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=student_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=student_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'service_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_service_id"
	<?php echo $style_order; ?>>
  <?php if ('service_id' == $sort[0]): ?>
    <?php echo link_to(__('Service', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Service', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'service_date';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_service_date"
	<?php echo $style_order; ?>>
  <?php if ('service_date' == $sort[0]): ?>
    <?php echo link_to(__('Service date', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Service date', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_date&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'relative_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_relative_id"
	<?php echo $style_order; ?>>
  <?php if ('relative_id' == $sort[0]): ?>
    <?php echo link_to(__('Relative', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=relative_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Relative', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=relative_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'input_date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_input_date_at"
	<?php echo $style_order; ?>>
  <?php if ('input_date_at' == $sort[0]): ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=input_date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=input_date_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'note';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_note"
	<?php echo $style_order; ?>>
  <?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'updated_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_updated_at"
	<?php echo $style_order; ?>>
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>