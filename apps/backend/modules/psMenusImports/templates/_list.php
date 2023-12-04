
  <?php if ($pager->getNbResults()): ?>
  	<div class="clear" style="clear: both;"></div>
<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
  	<table id="dt_basic" class="table table-bordered table-striped"	width="100%">
  		<thead>
			<tr class="header hidden-sm hidden-xs">
			  
			  <?php include_partial('psMenusImports/list_th_tabular2', array('sort' => $sort)) ?>
				
	          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?>
				<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

			<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
				class="text-center" style="width: 31px;"><div><label
				class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label></div></th>
			</tr>
			
			<tr class="hidden-lg hidden-md">
          <?php include_partial('psMenusImports/list_th_tabular', array('sort' => $sort)) ?>
          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

			<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
				class="text-center" style="width: 31px;"><label
				class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label></th>

		</tr>
		</thead>
  		<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_menus_imports): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psMenusImports/list_td_tabular', array('ps_menus_imports' => $ps_menus_imports)) ?>
						            <?php include_partial('psMenusImports/list_td_actions', array('ps_menus_imports' => $ps_menus_imports, 'helper' => $helper)) ?>
			                        <?php include_partial('psMenusImports/list_td_batch_actions', array('ps_menus_imports' => $ps_menus_imports, 'helper' => $helper)) ?>
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