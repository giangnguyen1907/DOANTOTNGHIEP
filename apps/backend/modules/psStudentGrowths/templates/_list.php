
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
<th class="sf_admin_text sf_admin_list_th_student_name" style="white-space: nowrap;">
  <?php echo __('Student name', array(), 'messages') ?>
  <div><?php echo __('Student name', array(), 'messages') ?></div>
</th>

<th class="sf_admin_text sf_admin_list_th_student_name  text-center">
  <?php echo __('Birthday', array(), 'messages') ?>
  <div><?php echo __('Birthday', array(), 'messages') ?></div>
</th>
<th class="sf_admin_text sf_admin_list_th_student_name  text-center">
  <?php echo __('Age examination', array(), 'messages') ?>
  <div><?php echo __('Age examination', array(), 'messages') ?>
</th>
<th class="sf_admin_date sf_admin_list_th_input_date_at text-center">
  <?php echo __('Examination', array(), 'messages') ?>
  <div><?php echo __('Examination', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_height  text-center">
  <?php echo __('Height', array(), 'messages') ?>
  <div><?php echo __('Height', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_weight  text-center">
  <?php echo __('Weight', array(), 'messages') ?>
  <div><?php echo __('Weight', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_tooth  text-center">
  <?php echo __('Tooth', array(), 'messages') ?>
  <div><?php echo __('Tooth', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_throat  text-center">
  <?php echo __('Throat', array(), 'messages') ?>
  <div><?php echo __('Throat', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_eye  text-center">
  <?php echo __('Eye', array(), 'messages') ?>
  <div><?php echo __('Eye', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_heart  text-center">
  <?php echo __('Heart', array(), 'messages') ?>
  <div><?php echo __('Heart', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_lung  text-center">
  <?php echo __('Lung', array(), 'messages') ?>
  <div><?php echo __('Lung', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_index_skin  text-center">
  <?php echo __('Skin', array(), 'messages') ?>
  <div><?php echo __('Skin', array(), 'messages') ?>
</th>

<th class="sf_admin_date sf_admin_list_th_updated_at text-center">
	<?php echo __('Updated by', array(), 'messages') ?>
  <div><?php echo __('Updated by', array(), 'messages') ?>
</th>
<th class="sf_admin_date sf_admin_list_th_send_notication text-center">
	<?php echo __('Notication', array(), 'messages') ?>
  <div><?php echo __('Notication', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			<th id="sf_admin_list_th_actions" class="text-center" style="width: 85px;">
			<?php echo __('Actions', array(), 'sf_admin') ?>
			<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;">
					<div><label
					class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></div></th>
			</tr>
			
			<tr class="hidden-lg hidden-md">
          <?php include_partial('psStudentGrowths/list_th_tabular', array('sort' => $sort)) ?>
          
          <th id="sf_admin_list_th_actions" class="text-center"
					style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;"><label
					class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>

			</tr>
		</thead>
		
		<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_student_growths): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psStudentGrowths/list_td_tabular', array('ps_student_growths' => $ps_student_growths)) ?>
						            <?php include_partial('psStudentGrowths/list_td_actions', array('ps_student_growths' => $ps_student_growths, 'helper' => $helper)) ?>
			                        <?php include_partial('psStudentGrowths/list_td_batch_actions', array('ps_student_growths' => $ps_student_growths, 'helper' => $helper)) ?>
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