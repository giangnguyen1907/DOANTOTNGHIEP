<?php
$check_logtime = null;

// Danh sach giao vien duoc phan cong trong lop
$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] )
	->execute ();

if ($check_current_date) {
	$number_colspan = 9;
} else {
	$number_colspan = 7;
}
?>
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psLogtimes/list_th_tabular', array('sort' => $sort, 'filter_value' => $filter_value, 'nbResults' => $pager->getNbResults())) ?>
          <?php if ($check_current_date):?>
            <th id="sf_admin_list_th_actions" class="text-center"
					style="width: 65px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
				<th id="sf_admin_list_batch_actions" class="text-center"
					style="width: 31px;"><label class="checkbox-inline"> <input
						id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>
		    <?php endif;?>
        </tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="<?php echo $number_colspan;?>">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php
			if ($check_current_date) :
				foreach ( $pager->getResults () as $i => $ps_logtimes ) :
					$odd = fmod ( ++ $i, 2 ) ? 'odd' : 'even'?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psLogtimes/list_td_tabular', array('list_member' => $list_member,'ps_logtimes' => $ps_logtimes, 'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'ps_constant_option' => $ps_constant_option)) ?>
		  <?php include_partial('psLogtimes/list_td_actions', array('ps_logtimes' => $ps_logtimes, 'helper' => $helper, 'filter_value' => $filter_value)) ?>
		  <?php include_partial('psLogtimes/list_td_batch_actions', array('ps_logtimes' => $ps_logtimes, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
		<?php

else :
				foreach ( $pager->getResults () as $i => $ps_logtimes ) :
					$odd = fmod ( ++ $i, 2 ) ? 'odd' : 'even'?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psLogtimes/list_td_tabular', array('list_member' => $list_member,'ps_logtimes' => $ps_logtimes, 'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'ps_constant_option' => $ps_constant_option)) ?>
		  </tr>
        <?php endforeach; ?>		
		<?php endif;?>
      </tbody>
	</table>
</div>
<?php endif; ?>