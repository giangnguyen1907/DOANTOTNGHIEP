
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psCustomer/list_th_tabular', array('sort' => $sort)) ?>          
          <th data-hide="phone" id="sf_admin_list_th_actions"
					class="text-center" style="width: 87px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="9">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_customer): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psCustomer/list_td_tabular', array('ps_customer' => $ps_customer)) ?>
          <?php include_partial('psCustomer/list_td_actions', array('ps_customer' => $ps_customer, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
</div>
<?php endif; ?>