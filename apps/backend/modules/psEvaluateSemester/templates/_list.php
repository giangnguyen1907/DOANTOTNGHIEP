
  <?php if ($pager->getNbResults()): ?>
  	<div class="clear" style="clear: both;"></div>
<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-bordered table-striped"
		width="100%">
		<thead>
<tr class="header hidden-sm hidden-xs">
<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center">
  <?php echo __('Student code', array(), 'messages') ?>
  <div><?php echo __('Student code', array(), 'messages') ?></div>
</th>

<th	class="sf_admin_foreignkey sf_admin_list_th_student_name text-center">
  <?php echo __('Student', array(), 'messages') ?>
  <div><?php echo __('Student', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_title text-center">
  <?php echo __('Title', array(), 'messages') ?>
  <div><?php echo __('Title', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_url_file text-center">
  <?php echo __('View file', array(), 'messages') ?>
  <div><?php echo __('View file', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_is_public text-center">
  <?php echo __('Is public', array(), 'messages') ?>
  <div><?php echo __('Is public', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_updated_at text-center">
  <?php echo __('Updated at', array(), 'messages') ?>
  <div><?php echo __('Updated at', array(), 'messages') ?></div>
</th>

<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			<th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;">
			<?php echo __('Actions', array(), 'sf_admin') ?>
			<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<div><label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label></div>
		  </th>
			</tr>
        <tr class="hidden-lg hidden-md">
          <?php include_partial('psEvaluateSemester/list_th_tabular', array('sort' => $sort)) ?>
          
          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label>						
		  </th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($pager->getResults() as $i => $ps_evaluate_semester): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psEvaluateSemester/list_td_tabular', array('ps_evaluate_semester' => $ps_evaluate_semester)) ?>
						            <?php include_partial('psEvaluateSemester/list_td_actions', array('ps_evaluate_semester' => $ps_evaluate_semester, 'helper' => $helper)) ?>
			                        <?php include_partial('psEvaluateSemester/list_td_batch_actions', array('ps_evaluate_semester' => $ps_evaluate_semester, 'helper' => $helper)) ?>
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