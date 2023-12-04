<?php
$check_logtime = null;
// echo 'ABC_'.$filter_value['tracked_at'];
// Danh sach giao vien duoc phan cong trong lop
$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] )
	->execute ();
$ps_off_school = Doctrine::getTable ( 'PsOffSchool' )->getStudentOffSchoolByDate ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] );
$teacher_class_id = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () )
	->fetchOne ();

?>

  <?php if ($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
          <?php include_partial('psAttendances/list_th_tabular', array('sort' => $sort,'ps_class_id' => $filter_value['ps_class_id'],'tracked_at' => $filter_value['tracked_at'], 'nbResults' => $pager->getNbResults())) ?>          
        </tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="8">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>

			<!-- Chỗ này cần xem lại cho kỹ 
        <?php 
// if(myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_SHOW') && ! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_ADD')){
					                                                                                                                              // if($filter_value['ps_class_id'] == $teacher_class_id->getId()){ ?>
                <?php //foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                  <tr class="sf_admin_row <?php //echo $odd ?>">
        			<?php //include_partial('psAttendances/list_td_tabular', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id)) ?>
                  </tr>
                <?php //endforeach; ?>
            <?php //}else{ ?>
            	<?php //foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                  <tr class="sf_admin_row <?php //echo $odd ?>">
        			<?php //include_partial('psAttendances/list_td_tabular_no', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id)) ?>
                  </tr>
                <?php //endforeach; ?>
            <?php //} }else{?>
            
                <?php //foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                  <tr class="sf_admin_row <?php //echo $odd ?>">
        			<?php //include_partial('psAttendances/list_td_tabular', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id)) ?>
                  </tr>
                <?php //endforeach; ?>
        <?php //}?>
        -->
        
        <?php foreach ($pager->getResults() as $i => $ps_logtimes): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			<?php include_partial('psAttendances/list_td_tabular', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'check_logtime' => $check_logtime,'ps_off_school' => $ps_off_school,'ps_class_id' => $filter_value['ps_class_id'], 'tracked_at' => $filter_value['tracked_at'],'teacher_class_id' => $teacher_class_id)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
</div>
<?php endif; ?>