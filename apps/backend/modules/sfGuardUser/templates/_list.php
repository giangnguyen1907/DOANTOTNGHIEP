<?php if ($pager->getNbResults()): ?>
<?php
	$number_colspan = 8;
	$list_th_tabular = 'list_th_tabular';
	$list_td_tabular = 'list_td_tabular';

	if (myUser::isAdministrator ()) :
			$number_colspan = 9;
			$list_th_tabular = 'list_th_tabular_app_device';
			$list_td_tabular = 'list_td_tabular_app_device_full';
	 elseif (myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) :
			$number_colspan = 9;
			$list_th_tabular = 'list_th_tabular_app_device';
			$list_td_tabular = 'list_td_tabular_app_device';
	endif;
?>
<div class="custom-scroll table-responsive">
<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
	<thead>
		<tr style="background: transparent;">
		  <th colspan="<?php echo $number_colspan;?>"><div class="text-results"><?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?></div></th>
		  </tr>
		<tr>
          <?php include_partial('sfGuardUser/'.$list_th_tabular, array('sort' => $sort)) ?>          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="<?php echo $number_colspan;?>">
			<div class="text-results"><?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?></div>
			</th>
		</tr>
	</tfoot>
	<tbody>
        <?php foreach ($pager->getResults() as $i => $sf_guard_user): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('sfGuardUser/'.$list_td_tabular, array('sf_guard_user' => $sf_guard_user)) ?>
          <?php include_partial('sfGuardUser/list_td_actions', array('sf_guard_user' => $sf_guard_user, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
</table>
</div>
<?php endif; ?>