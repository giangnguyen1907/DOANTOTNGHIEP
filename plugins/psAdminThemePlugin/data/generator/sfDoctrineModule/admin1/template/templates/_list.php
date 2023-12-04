
  [?php if ($pager->getNbResults()): ?]
  	<div class="custom-scroll table-responsive">
    <table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
      <thead>
        <tr>
          [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort)) ?]
          
<?php if ($this->configuration->getValue('list.object_actions')): ?>
          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;">[?php echo __('Actions', array(), 'sf_admin') ?]</th>
<?php endif; ?>

<?php if ($this->configuration->getValue('list.batch_actions')): ?>
          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label>						
		  </th>
<?php endif; ?>

        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
          <div class="text-results">
          [?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?]
          </div>            
          </th>
        </tr>
      </tfoot>
      <tbody>
        [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          <tr class="sf_admin_row [?php echo $odd ?]">
			
			            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
			<?php if ($this->configuration->getValue('list.object_actions')): ?>
			            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
			<?php endif; ?>
      <?php if ($this->configuration->getValue('list.batch_actions')): ?>
                  [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
      <?php endif; ?>
          </tr>
        [?php endforeach; ?]
      </tbody>
    </table>
    </div>
  [?php endif; ?]