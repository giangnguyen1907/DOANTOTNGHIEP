<?php

if ($check_current_date) {
	$number_colspan = 9;
} else {
	$number_colspan = 9;
}
?>
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psFeatureBranchTimes/list_th_tabular', array('sort' => $sort)) ?>          
          <th id="sf_admin_list_th_actions" class="text-center"
					style="width: 80px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="<?php echo $number_colspan ?>">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $feature_branch_times): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psFeatureBranchTimes/list_td_tabular', array('feature_branch_times' => $feature_branch_times)) ?>
						            <?php include_partial('psFeatureBranchTimes/list_td_actions', array('feature_branch_times' => $feature_branch_times, 'helper' => $helper)) ?>
			                </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
</div>
<?php endif; ?>