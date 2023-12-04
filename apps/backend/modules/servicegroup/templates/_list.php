
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('servicegroup/list_th_tabular', array('sort' => $sort)) ?>
          
          <th id="sf_admin_list_th_actions" class="text-center"
					style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

				<th id="sf_admin_list_batch_actions" class="text-center"
					style="width: 31px;"><label class="checkbox-inline"> <input
						id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="8" rowspan="1">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $service_group): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('servicegroup/list_td_tabular', array('service_group' => $service_group)) ?>
						            <?php include_partial('servicegroup/list_td_actions', array('service_group' => $service_group, 'helper' => $helper)) ?>
			                        <?php include_partial('servicegroup/list_td_batch_actions', array('service_group' => $service_group, 'helper' => $helper)) ?>
                </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
</div>
<?php endif; ?>