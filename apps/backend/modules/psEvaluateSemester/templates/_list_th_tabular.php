<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center">
  <?php echo __('Student code', array(), 'messages') ?>
</th>

<th
	class="sf_admin_foreignkey sf_admin_list_th_student_name text-center">
  <?php echo __('Student', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_title text-center">
  <?php echo __('Title', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_url_file text-center">
  <?php echo __('View file', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_is_public text-center">
  <?php echo __('Is public', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_updated_at text-center">
  <?php echo __('Updated at', array(), 'messages') ?>
</th>

<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>