<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_ADD'))): ?>
<div
	class="widget-body-toolbar col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right"
	style="padding-top: 10px;">
	<?php echo $helper->linkToNew(array(  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
</div>
<?php endif; ?>
<?php if ($pager->getNbResults()): ?>
<div class="table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding">
		<thead>
			<tr>
	          <?php include_partial('psAppPermission/list_th_tabular', array('sort' => $sort)) ?>          
	          <th id="sf_admin_list_th_actions"><?php echo __('Functional')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="3">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_app_permission): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
        <?php
		$class_tr = '';
		if (! $ps_app_permission->getPsAppRoot ())
			$class_tr = ' danger';
		?>
        
        <tr class="sf_admin_row <?php echo $odd . $class_tr;?>">
		<?php include_partial('psAppPermission/list_td_tabular', array('ps_app_permission' => $ps_app_permission)) ?>
		<?php include_partial('psAppPermission/list_td_app_permission', array('ps_app_permission' => $ps_app_permission, 'helper' => $helper)) ?>
		</tr>
        <?php endforeach;?>
      </tbody>
	</table>
</div>
<?php endif; ?>