<th class="sf_admin_text sf_admin_list_th_view_img" style="width: 4%">
  <?php echo __('View img', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_student_name"
	style="width: 16%">
  <?php echo __('Student name', array(), 'messages') ?>
</th>
<th
	class="sf_admin_text sf_admin_list_th_td_attendance text-center smart-form has-tickbox"
	style="width: 5%;">
  <?php echo __('Td attendance', array(), 'messages') ?>
  <?php
		if ($filter_value ['ps_class_id'] > 0 && $filter_value ['tracked_at'] != '') {
			$count = Doctrine::getTable ( 'PsLogtimes' )->getLoginCount ( $filter_value ['ps_class_id'], $filter_value ['tracked_at'] );
			echo '<p><span class="btn btn-xs btn-success btn-circle">' . $count . '/' . $nbResults . '</span></p>';
		}
		?>
	<!--  
	<label class="checkbox-inline">
	<input type="checkbox" class="checkbox style-0" id="sf_admin_list_th_td_attendance"/>
		<span></span>
	</label>-->
</th>
<th class="sf_admin_text sf_admin_list_th_td_login_infomation"
	style="width: 20%">
  <?php echo __('Td login infomation', array(), 'messages') ?>   
</th>
<th class="sf_admin_text sf_admin_list_th_td_logout_infomation"
	style="width: 20%">
  <?php echo __('Td logout infomation', array(), 'messages') ?>
  <label class="btn bg-color-orange" rel="tooltip" data-placement="top"
	data-original-title="Chưa điểm danh về"></label>
</th>
<th class="sf_admin_text sf_admin_list_th_td_service" style="width: 12%">
  <?php echo __('User service', array(), 'messages') ?>
</th>
<th class="sf_admin_text sf_admin_list_th_td_note">
  <?php echo __('Note', array(), 'messages') ?>
</th>