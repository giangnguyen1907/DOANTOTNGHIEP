<?php
$ktime = '01-' . $filters ['ps_year_month']->getValue ();
$ktime = strtotime ( $ktime );
?>
<?php if ($pager->getNbResults()): ?>
<div class="clear" style="clear: both;"></div>
<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-bordered table-striped"
		width="100%">
		<thead>
			<tr class="header hidden-sm hidden-xs">
			
				<?php slot('sf_admin.current_header') ?>

<?php $name_field = 'student_name';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_student_name" >
  <?php echo __('Student', array(), 'messages') ?>
  <div><?php echo __('Student', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'td_birthday';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_td_birthday" >
   <?php echo __('Birthday', array(), 'messages') ?>
   <div><?php echo __('Birthday', array(), 'messages') ?></div>
</th>

<th class="sf_admin_text" >
   <?php echo __('Current class name', array(), 'messages') ?>
   <div><?php echo __('Current class name', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'receivable_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_receivable_at" >
   <?php echo __('Receivable at', array(), 'messages') ?>
   <div><?php echo __('Receivable at', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'updated_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_date sf_admin_list_th_updated_at" >
  <?php echo __('Updated by', array(), 'messages') ?>
  <div><?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
     <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'receipt_no';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_receipt_no" >
  <?php echo __('Receipt no', array(), 'messages') ?>
  <div><?php if ('receipt_no' == $sort[0]): ?>
     <?php echo link_to(__('Receipt no', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=receipt_no&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Receipt no', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=receipt_no&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'td_expected';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_td_expected" >
   <?php echo __('Fee expected', array(), 'messages') ?>
   <div><?php echo __('Fee expected', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'late_payment_amount';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_late_payment_amount" >
  <?php echo __('Late payment amount', array(), 'messages') ?>
  <div><?php if ('late_payment_amount' == $sort[0]): ?>
     <?php echo link_to(__('Late payment amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=late_payment_amount&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Late payment amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=late_payment_amount&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'collected_amount';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_collected_amount" >
   <?php echo __('Collected amount', array(), 'messages') ?>
   <div><?php if ('collected_amount' == $sort[0]): ?>
    <?php echo link_to(__('Collected amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=collected_amount&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Collected amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=collected_amount&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'balance_amount';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_balance_amount" >
   <?php echo __('Balance amount', array(), 'messages') ?>
   <div><?php if ('balance_amount' == $sort[0]): ?>
    <?php echo link_to(__('Balance amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=balance_amount&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Balance amount', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=balance_amount&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'payment_status';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_boolean sf_admin_list_th_payment_status" >
   <?php echo __('Payment status', array(), 'messages') ?>
   <div><?php if ('payment_status' == $sort[0]): ?>
    <?php echo link_to(__('Payment status', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=payment_status&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Payment status', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=payment_status&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'payment_date';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_payment_date" >
   <?php echo __('Payment date', array(), 'messages') ?>
   <div><?php if ('payment_date' == $sort[0]): ?>
    <?php echo link_to(__('Payment date', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=payment_date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Payment date', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=payment_date&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'is_public';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_boolean sf_admin_list_th_is_public" >
   <?php echo __('Publish receipt', array(), 'messages') ?>
   <div><?php if ('is_public' == $sort[0]): ?>
    <?php echo link_to(__('Publish receipt', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=is_public&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Publish receipt', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=is_public&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'number_push_notication';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_number_push_notication" >
   <?php echo __('Number push notication', array(), 'messages') ?>
   <div><?php if ('number_push_notication' == $sort[0]): ?>
    <?php echo link_to(__('Number push notication', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=number_push_notication&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
  <?php else: ?>
    <?php echo link_to(__('Number push notication', array(), 'messages'), '@ps_receipts', array('query_string' => 'sort=number_push_notication&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
				
				<th id="sf_admin_list_th_actions" class="text-center"
					style="width: 140px;"> <div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;">
					 <div><label
					class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></div></th>
				
			</tr>
			<tr class="hidden-lg hidden-md">
				<?php include_partial('psReceipts/list_th_tabular', array('sort' => $sort)) ?>
				
				<th id="sf_admin_list_th_actions" class="text-center"
					style="width: 140px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;"><label
					class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>
			
			</tr>
			
		</thead>
		
		<tbody>
        <?php foreach ($pager->getResults() as $i => $receipt): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">			
			 <?php include_partial('psReceipts/list_td_tabular', array('receipt' => $receipt)) ?>
			 <?php include_partial('psReceipts/list_td_actions', array('receipt' => $receipt, 'helper' => $helper, 'ktime' => $ktime)) ?>
			 <?php include_partial('psReceipts/list_td_batch_actions', array('receipt' => $receipt, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach;?>
      </tbody>
	</table>
</div>
</section>
<div class="clear" style="clear: both;"></div>
<style>
.border_top_bottom{border: 1px solid #ccc;border-left: none;border-right: none; }
.border_top_bottom span{font-weight: 700;}
</style>
	<div class="col-xs-12 border_top_bottom">
		<span class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
        </span>
	</div>
<?php endif; ?>