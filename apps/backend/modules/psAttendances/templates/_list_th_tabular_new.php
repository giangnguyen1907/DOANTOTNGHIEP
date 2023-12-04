<tr style="background-image: none;">
	<th rowspan="2" scope="col"
		class="sf_admin_text sf_admin_list_th_view_img text-center"
		style="width: 50px;">
	  <?php echo __('View img', array(), 'messages') ?>
	</th>
	<th rowspan="2" scope="col"
		class="sf_admin_foreignkey sf_admin_list_th_student_id text-center"
		style="width: 120px;">
	  <?php echo __('Student', array(), 'messages') ?>
	</th>

	<th rowspan="2" scope="col"
		class="sf_admin_foreignkey sf_admin_list_th_login_at text-center"
		style="width: 55px;">
	  <?php echo __('Go school', array(), 'messages') ?>
	  <?php
			if ($ps_class_id > 0 && $tracked_at != '') {
				$count = Doctrine::getTable ( 'PsLogtimes' )->getLoginCount ( $ps_class_id, $tracked_at );
				echo '<p><span class="label label-success">' . $count . '/' . $nbResults . '</span></p>';
			}
			?>
	</th>

	<th colspan="2" scope="col" class="text-center"><?php echo __('Absent', array(), 'messages');?></th>

	<th rowspan="2" scope="col"
		class="sf_admin_foreignkey sf_admin_list_th_login_at text-center"
		style="width: 30% px !important;"><?php echo __('Attendance login at', array(), 'messages') ?></th>

	<th rowspan="2" scope="col"
		class="sf_admin_text sf_admin_list_th_service text-center"
		style="width: 200px;"><?php echo __('Service', array(), 'messages') ?></th>

	<th rowspan="2" scope="col" class="text-center" style="width: 100px;"><?php echo __('Updated by', array(), 'messages') ?></th>

	<th rowspan="2" scope="col"
		class="sf_admin_text sf_admin_list_th_action text-center"
		style="width: 65px;"><?php echo __('Action', array(), 'messages') ?></th>
</tr>

<tr style="background-image: none;">
	<th scope="col"
		class="sf_admin_text sf_admin_list_th_action text-center"
		style="width: 55px;">
	  <?php echo __('Permission', array(), 'messages') ?>
	  <?php
			if ($ps_class_id > 0 && $tracked_at != '') {

				$count3 = Doctrine::getTable ( 'PsLogtimes' )->getLoginCountPermission ( $ps_class_id, $tracked_at );

				echo '<p><span class="label label-warning">' . $count3 . '</span></p>';
			}
			?>
	</th>
	<th scope="col"
		class="sf_admin_text sf_admin_list_th_action text-center"
		style="width: 55px; border-right: 1px solid #ddd !important">	
	  <?php echo __('Not Permission', array(), 'messages') ?>
	  <?php
			if ($ps_class_id > 0 && $tracked_at != '') {
				$count2 = Doctrine::getTable ( 'PsLogtimes' )->getLoginCountNotPermission ( $ps_class_id, $tracked_at );
				echo '<p><span class="label label-danger">' . $count2.'</span></p>';
		}
	  ?>
	</th>
</tr>