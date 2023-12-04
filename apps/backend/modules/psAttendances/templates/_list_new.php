<?php
$check_logtime = null;
// echo 'ABC_'.$filter_value['tracked_at'];
// Danh sach giao vien duoc phan cong trong lop
$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] )->execute ();
$ps_off_school = Doctrine::getTable ( 'PsOffSchool' )->getStudentOffSchoolByDate ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] );
$teacher_class_id = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () )->fetchOne ();

$ps_symbol = Doctrine_Query::create()->from('psSymbol')->addWhere('ps_customer_id =?',$filter_value ['ps_customer_id'])->addWhere('ps_workplace_id =?',$filter_value ['ps_workplace_id'])->execute();

$array_diemdanh = array();
$array_dichvu = array();
$sudung = $kosudung = '';
foreach($ps_symbol as $symbol){
	// Nhóm điểm danh đi học
	if($symbol->getServiceId() == ''){
		$array_diemdanh [$symbol->getTitle()] = $symbol->getNote();

		if($symbol->getIsType() == 1){
			$sudung .= $symbol->getTitle().',';
		}else{
			$kosudung .=$symbol->getTitle().',';
		}

	}else{
		$array_dichvu[$symbol->getServiceId().'_'.$symbol->getTitle()] = $symbol->getTitle().': '.$symbol->getNote();
	}
}

?>
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>        
		<?php include_partial('psAttendances/list_th_tabular_new', array('sort' => $sort,'ps_class_id' => $filter_value['ps_class_id'],'tracked_at' => $filter_value['tracked_at'], 'nbResults' => $pager->getNbResults())) ?>
	  </thead>

		<tfoot>
			<tr>
				<th colspan="9">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>

			<tr class="hidden">
				<td>
				</td>
				<td></td>
				<td><label class="radio no-margin no-padding "> <input
						name="student_logtime[][log_value]" class="radiobox style-0"
						type="radio"> <span></span>
				</label></td>
				<td><label class="radio no-margin no-padding "> <input
						name="student_logtime[][log_value]" class="radiobox style-0"
						type="radio"> <span></span>
				</label></td>
				<td><label class="radio no-margin no-padding "> <input
						name="student_logtime[][log_value]" class="radiobox style-0"
						type="radio"> <span></span>
				</label></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
        	
      <?php foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
	  <tr class="sf_admin_row <?php echo $odd ?>">
		<?php include_partial('psAttendances/list_td_tabular_new', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id,'array_diemdanh'=>$array_diemdanh,'array_dichvu'=>$array_dichvu)) ?>
	  </tr>
      <?php endforeach; ?>
      </tbody>
	</table>
</div>

<?php endif; ?>