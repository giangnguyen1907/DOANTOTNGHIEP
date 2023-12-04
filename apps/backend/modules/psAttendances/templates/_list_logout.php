<?php
$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] )
	->execute ();
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
          <?php include_partial('psAttendances/list_th_tabular_logout') ?>
        </tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="5">
					<div class="text-results">
          		<?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php if(myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_SHOW') && ! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_ADD') && ($filter_value['ps_class_id'] != $teacher_class_id->getId())){?>
            <?php foreach ($pager->getResults() as $i => $ps_logtimes):  ?>
              <tr class="sf_admin_row">
                <?php include_partial('psAttendances/list_td_tabular_logout_no', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'tracked_at' => $filter_value['tracked_at'])) ?>
               </tr>
            <?php endforeach; ?>
		<?php }else{ ?>
    		<?php foreach ($pager->getResults() as $i => $ps_logtimes):  ?>
              <tr class="sf_admin_row">
                <?php include_partial('psAttendances/list_td_tabular_logout', array('ps_attendances' => $ps_logtimes,'list_member' => $list_member, 'tracked_at' => $filter_value['tracked_at'])) ?>
               </tr>
            <?php endforeach; ?>
		<?php }?>
        
      </tbody>
	</table>
</div>
<?php endif; ?>