<th class="sf_admin_text sf_admin_list_th_student_name">
  <?php echo __('Student', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_birthday">
  <?php echo __('Birthday', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_receivable_at text-right">
  <?php if ('receivable_at' == $sort[0]): ?>
    <?php echo link_to(__('Receivable at', array(), 'messages'), '@ps_fee_reports', array('query_string' => 'sort=receivable_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Receivable at', array(), 'messages'), '@ps_fee_reports', array('query_string' => 'sort=receivable_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>

<th class="sf_admin_text sf_admin_list_th_expected text-right">
  <?php echo __('Fee expected', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_updated_at text-center">
  <?php echo __('Updated at', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receipt_no">
  <?php echo __('Receipt no', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_collected_amount text-right">
  <?php echo __('Collected amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_balance_amount text-right">
  <?php echo __('Balance amount', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_payment_status text-right">
  <?php echo __('Payment status', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_payment_date text-right">
  <?php echo __('Payment date', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_is_public text-right">
  <?php echo __('Publish', array(), 'messages') ?>
</th>

