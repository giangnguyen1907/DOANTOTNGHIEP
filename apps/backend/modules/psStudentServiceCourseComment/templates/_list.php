
  <?php if ($pager->getNbResults()): ?>

<table id="dt_basic"
	class="table table-striped table-bordered table-hover no-footer no-padding"
	width="100%">
	<thead>
		<tr>
          <?php include_partial('psStudentServiceCourseComment/list_th_tabular', array('sort' => $sort)) ?>
          <th><?php echo __('Comment') ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="4">
				<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
			</th>
		</tr>
	</tfoot>
	<tbody>
        <?php foreach ($pager->getResults() as $i => $student_service_course_comment): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			     <?php include_partial('psStudentServiceCourseComment/list_td_tabular', array('student_service_course_comment' => $student_service_course_comment, 'filter_value' => $filter_value)) ?>
              <?php include_partial('psStudentServiceCourseComment/list_td_actions', array('student_service_course_comment' => $student_service_course_comment, 'helper' => $helper,'ps_customer_id' => $filter_value['ps_customer_id'], 'ps_service_course_id' => $filter_value['ps_service_course_id'], 'ps_service_course_schedule_id' => $filter_value['ps_service_course_schedule_id'])) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
</table>
<?php endif; ?>