<?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'username';
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
<th class="sf_admin_text sf_admin_list_th_username"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>
  <?php if ('username' == $sort[0]): ?>
    <?php echo link_to(__('Username', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=username&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>	
  <?php else: ?>
    <?php echo link_to(__('Username', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=username&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'first_name';
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
<th class="sf_admin_text sf_admin_list_th_first_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>
  <?php if ('first_name' == $sort[0]): ?>
    <?php echo link_to(__('First name', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=first_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('First name', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=first_name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'last_name';
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
<th class="sf_admin_text sf_admin_list_th_last_name"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>
  <?php if ('last_name' == $sort[0]): ?>
    <?php echo link_to(__('Last name', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=last_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Last name', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=last_name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'user_type';
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
<th class="sf_admin_text sf_admin_list_th_user_type"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>
  <?php if ('user_type' == $sort[0]): ?>
    <?php echo link_to(__('User type', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=user_type&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>	
  <?php else: ?>
    <?php echo link_to(__('User type', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=user_type&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'field_user_activated';
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
<th class="sf_admin_text sf_admin_list_th_field_user_activated"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>
	style="width: 65px;">
  <?php echo __('Is activated', array(), 'messages') ?>
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
    <?php echo link_to(__('Updated at', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>

<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<?php

$data_hide = '';
$name_field = 'last_login';
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
<th class="sf_admin_date sf_admin_list_th_last_login"
	<?php echo $data_hide; ?> <?php echo $style_order; ?>>
  <?php if ('last_login' == $sort[0]): ?>
    <?php echo link_to(__('Last login', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=last_login&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Last login', array(), 'messages'), '@sf_guard_user', array('query_string' => 'sort=last_login&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>

<?php include_slot('sf_admin.current_header') ?>
