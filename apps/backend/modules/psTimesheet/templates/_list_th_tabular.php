<?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'member_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_member_id"
	<?php echo $style_order; ?>>
  <?php if ('member_id' == $sort[0]): ?>
    <?php echo link_to(__('Member', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=member_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Member', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=member_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'time';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_time text-center"
	<?php echo $style_order; ?>>
  <?php echo __('Time', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'is_io';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_boolean sf_admin_list_th_is_io text-center"
	<?php echo $style_order; ?>>
  <?php if ('is_io' == $sort[0]): ?>
    <?php echo link_to(__('Is io', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=is_io&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is io', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=is_io&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'time_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_time_at text-center"
	<?php echo $style_order; ?>>
  <?php if ('time_at' == $sort[0]): ?>
    <?php echo link_to(__('Time at', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=time_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Time at', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=time_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'timesheet_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_timesheet_at text-center"
	<?php echo $style_order; ?>>
  <?php if ('timesheet_at' == $sort[0]): ?>
    <?php echo link_to(__('Timesheet at', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=timesheet_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Timesheet at', array(), 'messages'), '@ps_timesheet', array('query_string' => 'sort=timesheet_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>