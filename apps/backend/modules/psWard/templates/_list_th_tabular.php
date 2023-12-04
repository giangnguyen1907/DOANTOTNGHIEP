<?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 's_code';

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

<th class="sf_admin_text sf_admin_list_th_s_code"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('s_code' == $sort[0]): ?>
    <?php echo link_to(__('S code', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=s_code&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('S code', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=s_code&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'name';

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

<th class="sf_admin_text sf_admin_list_th_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('name' == $sort[0]): ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'district_name';

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

<th class="sf_admin_text sf_admin_list_th_district_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('District name', array(), 'messages') ?>
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

<th class="sf_admin_text sf_admin_list_th_iorder"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php if ('iorder' == $sort[0]): ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=iorder&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=iorder&sort_type=asc')) ?>
  <?php endif; ?>
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
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
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

<th class="sf_admin_text sf_admin_list_th_updated_by"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>


  <?php echo __('Updated by', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$data_hide = '';
$name_field = 'updated_at';

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
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_ward', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>