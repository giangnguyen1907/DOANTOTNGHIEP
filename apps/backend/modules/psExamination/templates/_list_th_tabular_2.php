<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_foreignkey sf_admin_list_th_ps_customer_id"
	<?php echo $style_order; ?>>
  <?php if ('ps_customer_id' == $sort[0]): ?>
    <?php echo link_to(__('Ps customer', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=ps_customer_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Ps customer', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=ps_customer_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_foreignkey sf_admin_list_th_ps_workplace_id"
	<?php echo $style_order; ?>>
  <?php if ('ps_workplace_id' == $sort[0]): ?>
    <?php echo link_to(__('Ps workplace', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=ps_workplace_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Ps workplace', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=ps_workplace_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_name"
	<?php echo $style_order; ?>>
  <?php if ('name' == $sort[0]): ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_date sf_admin_list_th_input_date_at"
	<?php echo $style_order; ?>>
  <?php if ('input_date_at' == $sort[0]): ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=input_date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=input_date_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_note"
	<?php echo $style_order; ?>>
  <?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>


<th class="sf_admin_foreignkey sf_admin_list_th_user_updated_id"
	<?php echo $style_order; ?>>
  <?php if ('user_updated_id' == $sort[0]): ?>
    <?php echo link_to(__('User updated', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=user_updated_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Update', array(), 'messages'), '@ps_examination', array('query_string' => 'sort=user_updated_id&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>