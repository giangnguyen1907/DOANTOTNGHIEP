
<th class="sf_admin_text sf_admin_list_th_id">ID</th>

<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_title">
  <?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_service', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_service', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>

<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_list_enable_roll">
  <?php echo __('List enable roll', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_group_name">
  <?php echo __('Group name', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_iorder">
  <?php if ('iorder' == $sort[0]): ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_service', array('query_string' => 'sort=iorder&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_service', array('query_string' => 'sort=iorder&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_service_detail text-center">
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
<?php include_slot('sf_admin.current_header') ?>

<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_is_default">
  <?php echo __('Is default', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>

<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_boolean sf_admin_list_th_is_activated">

  <?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_service', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_service', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>



<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_date sf_admin_list_th_updated_at">
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_service', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_service', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>