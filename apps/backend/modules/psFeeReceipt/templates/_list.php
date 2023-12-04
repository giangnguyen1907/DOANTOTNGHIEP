
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

				<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center">
				  <?php echo __('Student', array(), 'messages') ?>
				  <div><?php echo __('Student', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_receipt_no text-center">
				  <?php echo __('Receipt no', array(), 'messages') ?>
				  <div><?php echo __('Receipt no', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_receivable_amount text-center">
				  <?php echo __('Receivable amount', array(), 'messages') ?>
				  <div><?php echo __('Receivable amount', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_collected_amount text-center">
				  <?php echo __('Collected amount', array(), 'messages') ?>
				  <div><?php echo __('Collected amount', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_balance_amount text-center">
				  <?php echo __('Balance amount', array(), 'messages') ?>
				  <div><?php echo __('Balance amount', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_receipt_date text-center">
				  <?php echo __('Receipt date', array(), 'messages') ?>
				  <div><?php echo __('Receipt date', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_payment_status text-center">
				  <?php echo __('Payment status', array(), 'messages') ?>
				  <div><?php echo __('Payment status', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_payment_date text-center">
				  <?php echo __('Payment date', array(), 'messages') ?>
				  <div><?php echo __('Payment date', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_boolean sf_admin_list_th_is_public text-center">
				  <?php echo __('Is public', array(), 'messages') ?>
				  <div><?php echo __('Is public', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_boolean sf_admin_list_th_number_push_notication text-center">
				  <?php echo __('Number push notication', array(), 'messages') ?>
				  <div><?php echo __('Number push notication', array(), 'messages') ?></div>
				</th>
				
				<th class="sf_admin_text sf_admin_list_th_updated_by text-center">
				  <?php echo __('Updated by', array(), 'messages') ?>
				  <div><?php echo __('Updated by', array(), 'messages') ?></div>
				</th>
				
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?>
	          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;">
	          <?php echo __('Actions', array(), 'sf_admin') ?>
	          <div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>
	
	          <th data-hide="phone,tablet" id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
	    			<div><label class="checkbox-inline">
	    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
	    				<span></span>
	    			</label></div>					
			  </th>
			
			</tr>
        <tr class="hidden-lg hidden-md">
          <?php include_partial('psFeeReceipt/list_th_tabular', array('sort' => $sort)) ?>
          
          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

          <th data-hide="phone,tablet" id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label>						
		  </th>

        </tr>
      </thead>
      
      <tbody>
        <?php foreach ($pager->getResults() as $i => $ps_fee_receipt): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psFeeReceipt/list_td_tabular', array('ps_fee_receipt' => $ps_fee_receipt)) ?>
						            <?php include_partial('psFeeReceipt/list_td_actions', array('ps_fee_receipt' => $ps_fee_receipt, 'helper' => $helper)) ?>
			                        <?php include_partial('psFeeReceipt/list_td_batch_actions', array('ps_fee_receipt' => $ps_fee_receipt, 'helper' => $helper)) ?>
                </tr>
        <?php endforeach; ?>
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