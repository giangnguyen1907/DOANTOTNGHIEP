<th class="sf_admin_text sf_admin_list_th_view_img text-center"
	style="width: 50px;">
  <?php echo __('View img', array(), 'messages') ?>
</th>
<th class="sf_admin_foreignkey sf_admin_list_th_student_id text-center"
	style="width: 120px;">
  <?php echo __('Student', array(), 'messages') ?>
</th>

<th class="sf_admin_foreignkey sf_admin_list_th_login_at text-center"
	style="width: 55px;">
  <?php echo __('Go school', array(), 'messages') ?>
  <?php
		if ($ps_class_id > 0 && $tracked_at != '') {
			$count = Doctrine::getTable ( 'PsLogtimes' )->getLoginCount ( $ps_class_id, $tracked_at );
			echo '<p><span class="btn btn-xs btn-success btn-circle">' . $count . '/' . $nbResults . '</span></p>';
		}
		?>
</th>

<!--  
<th class="sf_admin_text sf_admin_list_th_action text-center" style="width: 55px;">
  <?php echo __('Not Permission', array(), 'messages') ?>
  <?php
		if ($ps_class_id > 0 && $tracked_at != '') {
			$count2 = Doctrine::getTable ( 'PsLogtimes' )->getLoginCountNotPermission ( $ps_class_id, $tracked_at );
			echo '<p><span class="btn btn-xs btn-success btn-circle">' . $count2 . '/' . $nbResults . '</span></p>';
		}
		?>
</th>

<th class="sf_admin_text sf_admin_list_th_action text-center" style="width: 55px;">
  <?php echo __('Permission', array(), 'messages') ?>
  <?php
		if ($ps_class_id > 0 && $tracked_at != '') {

			$count3 = Doctrine::getTable ( 'PsLogtimes' )->getLoginCountPermission ( $ps_class_id, $tracked_at );

			echo '<p><span class="btn btn-xs btn-success btn-circle">' . $count3 . '/' . $nbResults . '</span></p>';
		}
		?>
</th>

-->

<th class="sf_admin_foreignkey sf_admin_list_th_login_at text-center"
	style="width: 30% px !important;">
  <?php echo __('Attendance login at', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_service text-center"
	style="width: 200px;">
  <?php echo __('Service', array(), 'messages') ?>
</th>

<th class="text-center" style="width: 100px;">
  <?php echo __('Created by', array(), 'messages') ?>
</th>

<th class="text-center" style="width: 100px;">
  <?php echo __('Updated by', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_action text-center"
	style="width: 85px;">
  <?php echo __('Action', array(), 'messages') ?>
</th>
