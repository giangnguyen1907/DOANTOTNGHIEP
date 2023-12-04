<div class="jarviswidget jarviswidget-sortable" id="wid-id-0"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	role="widget">
	<header role="heading">
		<span class="widget-icon"> <i class="fa fa-clock-o faa-burst animated"></i></span>
		<h2><?php echo __('Statistics attendance day');?> <div
				class="label label-success"><?php echo PsDateTime::psTimetoDate(null, "d-m-Y");?></div>
		</h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div class="table-responsive no-margin custom-scroll"
				style="height: 300px; overflow-y: scroll;">
				<table class="table table-hover">
					<thead>
						<th><?php echo __('Class name') ?></th>
						<th class="text-center"><?php echo __('Total student') ?></th>
						<th class="text-center"><?php echo __('Login student count') ?> </th>
						<th class="text-center"><?php echo __('Not login student count') ?></th>
					</thead>
					<tbody>
						<?php
						foreach ( $workplaces as $workplace ) :
							$total_active_student_workplace = 0;
							$total_number_login = 0;
							$list_my_class = Doctrine::getTable ( 'MyClass' )->getClassByPsWorkplace ( $workplace->getId (),null );
						?>
						<tr>
							<td colspan="4"><strong><a><?php echo $workplace->getTitle()?></a></strong></td>
						</tr>								
							<?php foreach($list_my_class as $my_class): ?>
							<tr>
							<td><?php echo $my_class->getName() ?></td>
							<td class="text-center">
							<strong>
							<?php
							//echo $total_active_student = $my_class->getNumberStudentActivitie ();
							
							echo $total_active_student = $my_class->getNumberStudentActivitie ();
							
							$total_active_student_workplace = $total_active_student_workplace + $total_active_student;
							?>
							</strong>
							</td>
							<td class="text-center">
								    <?php
									$number_login = Doctrine::getTable ( 'PsLogtimes' )->getLoginCount ( $my_class->getId () );
									$percentage_login = ($total_active_student != 0) ? ($number_login * 100) / $total_active_student : 0;

									$total_number_login = $total_number_login + $number_login;

									?>
									<?php include_partial('psCpanel/box_number_attendance_login', array('number_login' => $number_login, 'percentage_login' => $percentage_login)) ?>											
							</td>
							<td class="text-center">
    							<?php
    							      $absent = $total_active_student - $number_login;
    							      $percentage_absent = ($total_active_student != 0) ? ($absent * 100) / $total_active_student : 0;
    							?>
								<?php include_partial('psCpanel/box_number_attendance_absent', array('absent' => $absent, 'percentage_absent' => $percentage_absent)) ?>
							</td>
						</tr>									
									<?php endforeach ?>
									<tr>
							<td><strong><?php echo __('Total')?></strong></td>
							<td class="text-center"><span class="btn btn-default no-border">
											<?php echo $total_active_student_workplace;?>
										</span></td>
							<td class="text-center">
									    <?php
									$total_percentage_login = ($total_active_student_workplace != 0) ? ($total_number_login * 100) / $total_active_student_workplace : 0;
									include_partial ( 'psCpanel/box_number_attendance_login', array (
											'number_login' => $total_number_login,
											'percentage_login' => $total_percentage_login ) );
									?>
									    </td>
							<td class="text-center">
									    	<?php
									$total_absent = $total_active_student_workplace - $total_number_login;
									$total_percentage_absent = ($total_active_student_workplace != 0) ? ($total_absent * 100) / $total_active_student_workplace : 0;
									?>
											<?php include_partial('psCpanel/box_number_attendance_absent', array('absent' => $total_absent, 'percentage_absent' => $total_percentage_absent)) ?>
									    </td>
						</tr>
								<?php endforeach ?>
							</tbody>
				</table>
			</div>
		</div>
	</div>
</div>