<?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'file_name';

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

<th class="sf_admin_text sf_admin_list_th_file_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('Icon', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'title';

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

<th class="sf_admin_text sf_admin_list_th_title"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'iorder';

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

<th class="sf_admin_text sf_admin_list_th_group_name"
	<?php echo $data_hide; ?>>
  <?php echo __('Group name', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'iorder';
if ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'iorder' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone"';
 elseif ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone,tablet"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_iorder"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('iorder' == $sort[0]): ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=iorder&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=iorder&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'service_detail';

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
<th class="sf_admin_text sf_admin_list_th_number_course"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('number_course' == $sort[0]): ?>
    <?php echo link_to(__('Number course', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=number_course&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Number course', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=number_course&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'title';

if ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'iorder' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone"';
 elseif ($name_field == 'updated_at' || $name_field == 'updated_by' || $name_field == 'order' || $name_field == 'note' || $name_field == 'description') :
	$data_hide = 'data-hide="phone,tablet"';
endif;

$style_order = '';
if ($name_field == 'number_course' || $name_field == 'number_course') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th
	class="sf_admin_text sf_admin_list_th_list_field_number_option_subject"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('List field number option subject', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'service_detail';

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

<th class="sf_admin_text sf_admin_list_th_service_detail text-center"
	<?php echo $data_hide; ?>>
  <?php echo __('Service detail', array(), 'messages') ?>
  <div class="col-md-12 pull-left border-top">
		<div class="col-md-4 pull-left text-right border-right">
		 	<?php echo __('Service amount');?>
		</div>
		<div class="col-md-4 pull-left text-center">
		 	<?php echo __('Service detail at');?>
		</div>
		<div class="col-md-4 pull-left text-center border-left">
			<?php echo __('Service split');?>
		</div>
	</div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'is_activated';

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

<th class="sf_admin_boolean sf_admin_list_th_is_activated"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'updated_by';

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



<th class="sf_admin_date sf_admin_list_th_updated_at"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_subjects', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>