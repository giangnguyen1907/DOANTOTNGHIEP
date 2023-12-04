<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_foreignkey sf_admin_list_th_ps_class_id text-center"
	style="width: 100px;">
  <?php echo __('Ps class', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_relative_id text-center"
	style="width: 170px;">
  <?php echo __('Relative', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center"
	style="width: 170px;">
  <?php echo __('Student', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_description text-center">
  <?php echo __('Reason', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_is_activated text-center"
	style="width: 140px;">
  <?php echo __('Status', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_date_at text-center"
	style="width: 110px;">
  <?php echo __('Date at', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_date text-center"
	style="width: 150px;">
  <?php echo __('Date', array(), 'messages') ?>
</th>

<th class="sf_admin_date sf_admin_list_th_created_at text-center"
	style="width: 80px;">
  <?php echo __('Created at', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>