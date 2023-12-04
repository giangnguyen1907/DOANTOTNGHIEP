<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_student_name text-center">
  <?php echo __('Student name', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_title text-center">
  <?php echo __('Title', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receipt_date text-center">
	<?php echo __('Receipt date', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receivable text-center">
	<?php echo __('Receivable amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_collected_amount text-center">
	<?php echo __('Collected amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_balance_amount text-center">
    <?php echo __('Balance amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_is_import text-center">
    <?php echo __('Is import', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_relative_id text-center">
    <?php echo __('Relative', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_note">
    <?php echo __('Note', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_updated_by text-center">
  <?php echo __('Updated by', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>