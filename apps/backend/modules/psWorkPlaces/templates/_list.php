
  <?php if ($pager->getNbResults()): ?>

<table id="dt_basic"
	class="table table-striped table-bordered table-hover no-footer no-padding"
	width="100%">
	<thead>
		<tr>
          <?php include_partial('psWorkPlaces/list_th_tabular', array('sort' => $sort)) ?>
          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

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
			<th colspan="7">
				<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
			</th>
		</tr>
	</tfoot>
	<tbody>
      	<?php
			$list_td_tabular = $sf_user->hasCredential ( 'PS_SYSTEM_WORK_PLACES_DETAIL' ) ? 'list_td_tabular_detail' : 'list_td_tabular';
			?>
        <?php foreach ($pager->getResults() as $i => $ps_work_places): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psWorkPlaces/'.$list_td_tabular, array('ps_work_places' 		=> $ps_work_places)) ?>
          <?php include_partial('psWorkPlaces/list_td_actions', array('ps_work_places' 			=> $ps_work_places, 'helper' => $helper)) ?>
          <?php include_partial('psWorkPlaces/list_td_batch_actions', array('ps_work_places' 	=> $ps_work_places, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
</table>
<?php endif; ?>