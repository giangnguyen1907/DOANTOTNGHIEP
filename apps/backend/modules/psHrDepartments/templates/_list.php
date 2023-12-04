<?php $_status = PreSchool::loadHrStatus();?>
<?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psHrDepartments/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions" class="text-center"
					style="width: 87px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="11">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
      	<?php
			$list_td_tabular = $sf_user->hasCredential ( 'PS_SYSTEM_USER_EDIT' ) ? 'list_td_tabular_link_user' : 'list_td_tabular';
			foreach ( $pager->getResults () as $i => $ps_member ) :
				$odd = fmod ( ++ $i, 2 ) ? 'odd' : 'even'?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psHrDepartments/'.$list_td_tabular, array('ps_member' => $ps_member, '_status' => $_status)) ?>
          <?php include_partial('psHrDepartments/list_td_actions', array('ps_member' => $ps_member, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
</div>
<?php endif; ?>