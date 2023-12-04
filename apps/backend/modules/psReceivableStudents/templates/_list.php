
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
    <?php if($filter_value['student_id'] == '' ){ ?>
    <table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psReceivableStudents/list_th_tabular', array('sort' => $sort,'filter_value' => $filter_value)) ?>
        </tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="6">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $receivable_student): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
            <?php include_partial('psReceivableStudents/list_td_tabular', array('receivable_student' => $receivable_student,'filter_value' => $filter_value)) ?>
        	</tr>
        <?php endforeach; ?>
      </tbody>
	</table>
    <?php }else{ ?>
    <table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psReceivableStudents/list_th_tabular2', array('sort' => $sort)) ?>
        </tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="6">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $receivable_student): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psReceivableStudents/list_td_tabular2', array('receivable_student' => $receivable_student)) ?>
			                </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
    <?php }?>
    </div>
<?php endif; ?>