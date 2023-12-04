<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_boolean sf_admin_list_th_sex text-center">
    <?php echo __('Sex', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'is_month';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_is_month text-center"
	<?php echo $style_order; ?>>
  <?php if ('is_month' == $sort[0]): ?>
    <?php echo link_to(__('Is month', array(), 'messages'), '@ps_student_bmi', array('query_string' => 'sort=is_month&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is month', array(), 'messages'), '@ps_student_bmi', array('query_string' => 'sort=is_month&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_min_height text-center">
    <?php echo __('Min height1', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_min_height text-center">
    <?php echo __('Min height', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_medium_height text-center">
    <?php echo __('Medium height', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_max_height text-center"
	<?php echo $style_order; ?>>
  <?php echo __('Max height', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_min_height text-center">
    <?php echo __('Max height1', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_min_height text-center">
    <?php echo __('Min weight1', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_min_weight text-center">
  <?php echo __('Min weight', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_medium_weight text-center">
    <?php echo __('Medium weight', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_max_weight text-center">
  <?php echo __('Max weight', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_min_height text-center">
    <?php echo __('Max weight1', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_updated_by text-center">
  <?php echo __('Updated by', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>