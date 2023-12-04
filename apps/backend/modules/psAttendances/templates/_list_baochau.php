<?php
$check_logtime = null;
// echo 'ABC_'.$filter_value['tracked_at'];
// Danh sach giao vien duoc phan cong trong lop
$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] )->execute ();
$ps_off_school = Doctrine::getTable ( 'PsOffSchool' )->getStudentOffSchoolByDate ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] );
$teacher_class_id = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () )->fetchOne ();

// Lấy tấy cả các trạng ký hiệu điểm danh
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
		$array_dichvu[$symbol->getServiceId().'_'.$symbol->getTitle()] = $symbol->getNote();
	}
}

?>
  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>        
		<?php include_partial('psAttendances/list_th_tabular_baochau', array('sort' => $sort,'ps_class_id' => $filter_value['ps_class_id'],'tracked_at' => $filter_value['tracked_at'], 'nbResults' => $pager->getNbResults())) ?>
	  </thead>

		<tfoot>
			<tr>
				<th colspan="7">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>

      <?php foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
	  <tr class="sf_admin_row <?php echo $odd ?>">
		<?php include_partial('psAttendances/list_td_tabular_baochau', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id,'array_diemdanh'=>$array_diemdanh,'array_dichvu'=>$array_dichvu)) ?>
	  </tr>
      <?php endforeach; ?>
      </tbody>
	</table>
</div>
<script type="text/javascript">

// Điểm danh theo mã
function setLogtimeBySymbol(student_id,ele) {

	var sd = '<?=$sudung?>';
	var ksd = '<?=$kosudung?>';
	txt_sd = sd.slice( 0, -1 );
	txt_ksd = ksd.slice( 0, -1 );
	let arr_sd = txt_sd.split(',');
	let arr_ksd = txt_ksd.split(',');

	//alert(ele.value);
	if(arr_sd.includes(ele.value)){

		$('#select_'+ student_id +'_relative_login').attr('disabled', false);
		$('#select_'+ student_id +'_relative_logout').attr('disabled', false);
		$('#select_'+ student_id +'_relative_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_relative_logout').prop("selectedIndex", 0);
		$('#select_'+ student_id +'_member_login').attr('disabled', false);
		$('#select_'+ student_id +'_member_logout').attr('disabled', false);
		$('#select_'+ student_id +'_member_login').prop("selectedIndex", 0);;
		$('#select_'+ student_id +'_member_logout').prop("selectedIndex", 0);
		$('.input-sm_'+ student_id +'_login').attr('disabled', false);
		$('.input-sm_'+ student_id +'_logout').attr('disabled', false);
		
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);		
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', true);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', false);

		$('#note_'+ student_id).attr('disabled', false);
		$('#service_code_'+ student_id).attr('disabled', false);
		$('#btn-attendance_'+ student_id).attr('disabled', false);

	}else{
		$('#block_student_service_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').prop('checked', false);
		$('.ss_id_'+ student_id + ' input[type="checkbox"]').attr('disabled', 'disabled');		

		$('#select_'+ student_id +'_relative_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_relative_logout').attr('disabled', 'disabled');	
		$('#select_'+ student_id +'_member_login').attr('disabled', 'disabled');
		$('#select_'+ student_id +'_member_logout').attr('disabled', 'disabled');
		$('.input-sm_'+ student_id +'_login').attr('disabled', 'disabled');		
		$('.input-sm_'+ student_id +'_logout').attr('disabled', 'disabled');	

		$('#note_'+ student_id).attr('disabled', 'disabled');
		$('#service_code_'+ student_id).attr('disabled', 'disabled');
		$('#btn-attendance_'+ student_id).attr('disabled', 'disabled');
	}

	$('#sf_admin_list_th_td_attendance').click(function() {
			
		var boxes = document.getElementsByTagName('input');
	
		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];			
			if (box.type == 'checkbox' && box.item_name == 'attendance[]')
				box.checked = $(this).is(":checked");
		}
	
		return true;
	});	

}// end function  setLogtimeBySymbol


</script>

<?php endif; ?>