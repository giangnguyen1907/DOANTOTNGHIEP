<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center">
  <?php echo __('Student', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receipt_no text-center">
  <?php echo __('Receipt no', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receivable_amount text-center">
  <?php echo __('Receivable amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_collected_amount text-center">
  <?php echo __('Collected amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_balance_amount text-center">
  <?php echo __('Balance amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receipt_date text-center">
  <?php echo __('Receipt date', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_payment_status text-center">
  <?php echo __('Payment status', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_payment_date text-center">
  <?php echo __('Payment date', array(), 'messages') ?>
</th>

<th class="sf_admin_boolean sf_admin_list_th_is_public text-center">
  <?php echo __('Is public', array(), 'messages') ?>
</th>

<th
	class="sf_admin_boolean sf_admin_list_th_number_push_notication text-center">
  <?php echo __('Number push notication', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_updated_by text-center">
  <?php echo __('Updated by', array(), 'messages') ?>
</th>

<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>