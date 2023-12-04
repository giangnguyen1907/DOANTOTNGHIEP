  <?php if ($pager->getNbResults()): ?>
<table id=""
	class="table table-striped table-bordered table-hover no-footer no-padding"
	width="100%">
	<thead>
		<tr>
          <?php include_partial('psStudentFeatures/list_th_tabular', array('sort' => $sort)) ?>
        
        </tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="4">
				<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
			</th>
		</tr>
	</tfoot>
	<tbody>
        <?php foreach ($pager->getResults() as $i => $student_feature): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			 <?php include_partial('psStudentFeatures/list_td_tabular', array('student_feature' => $student_feature, 'filter_value' => $filter_value)) ?>
			
            </tr>
        <?php endforeach; ?>
      </tbody>
</table>
<?php endif; ?>