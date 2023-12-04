
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

<th class="sf_admin_foreignkey sf_admin_list_th_ps_class_id text-center"
	style="width: 100px;">
  <?php echo __('Ps class', array(), 'messages') ?>
  <div><?php echo __('Ps class', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_relative_id text-center"
	style="width: 170px;">
  <?php echo __('Relative', array(), 'messages') ?>
  <div><?php echo __('Relative', array(), 'messages') ?></div>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center"
	style="width: 170px;">
  <?php echo __('Student', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_description text-center">
  <?php echo __('Reason', array(), 'messages') ?>
  <div><?php echo __('Reason', array(), 'messages') ?></div>
</th>

<th class="sf_admin_text sf_admin_list_th_is_activated text-center"
	style="width: 140px;">
  <?php echo __('Stutus', array(), 'messages') ?>
  <div><?php echo __('Status', array(), 'messages') ?></div>
</th>

<th class="sf_admin_text sf_admin_list_th_date_at text-center"
	style="width: 110px;">
  <?php echo __('Date at', array(), 'messages') ?>
  <div><?php echo __('Date at', array(), 'messages') ?></div>
</th>

<th class="sf_admin_text sf_admin_list_th_date text-center"
	style="width: 150px;">
  <?php echo __('Date', array(), 'messages') ?>
  <div><?php echo __('Date', array(), 'messages') ?></div>
</th>

<th class="sf_admin_date sf_admin_list_th_created_at text-center"	style="width: 80px;">
  <?php echo __('Created at', array(), 'messages') ?>
  <div><?php echo __('Created at', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>


          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?>
          <div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<div><label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label></div>						
		  </th>

			</tr>
        <tr class="hidden-lg hidden-md">
          <?php include_partial('psOffSchool/list_th_tabular', array('sort' => $sort)) ?>
          
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
        <?php foreach ($pager->getResults() as $i => $ps_off_school): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psOffSchool/list_td_tabular', array('ps_off_school' => $ps_off_school)) ?>
						            <?php include_partial('psOffSchool/list_td_actions', array('ps_off_school' => $ps_off_school, 'helper' => $helper)) ?>
			                        <?php include_partial('psOffSchool/list_td_batch_actions', array('ps_off_school' => $ps_off_school, 'helper' => $helper)) ?>
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