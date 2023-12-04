<?php slot('sf_admin.current_header') ?>

<?php $name_field = 'meal_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_meal_title" <?php echo $style_order; ?>>
  <?php echo __('Meal title', array(), 'messages') ?>
  <div><?php echo __('Meal title', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_date sf_admin_list_th_date_at" <?php echo $style_order; ?>>
  <?php echo __('Date at', array(), 'messages') ?>
  <div>
  <?php if ('date_at' == $sort[0]): ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_menus_imports', array('query_string' => 'sort=date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_menus_imports', array('query_string' => 'sort=date_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'object_group_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_object_group_title" <?php echo $style_order; ?>>
  <?php echo __('Object group title', array(), 'messages') ?>
  <div><?php echo __('Object group title', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'description';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_description" <?php echo $style_order; ?>>
  <?php echo __('Description', array(), 'messages') ?>
  <div><?php if ('description' == $sort[0]): ?>
    <?php echo link_to(__('Description', array(), 'messages'), '@ps_menus_imports', array('query_string' => 'sort=description&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Description', array(), 'messages'), '@ps_menus_imports', array('query_string' => 'sort=description&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'updated_by';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_updated_by" <?php echo $style_order; ?>>
  <?php echo __('Updated by', array(), 'messages') ?>
  <div><?php echo __('Updated by', array(), 'messages') ?></div>
</th>

<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>