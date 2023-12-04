<?php use_helper('I18N', 'Date') ?>
<?php if ($formFilter->hasGlobalErrors()): ?>
	<?php echo $formFilter->renderGlobalErrors()?>
	<?php endif;?>
	
	<?php echo $formFilter->renderHiddenFields() ?>
<div class="custom-scroll table-responsive">
	<table id="tbl-List-User" class="table table-bordered table-striped">
		<input type="hidden" id="count_list_course_schedules"
			value="<?php echo count($list_course_schedules)?>">
		<thead>
			<tr>
				<th style="width: <?php echo $width_th?>%;">&nbsp;</th>
			<?php foreach ($week_list as $date => $monday):?>
			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
						<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b></th>
			<?php endforeach;?>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-center"><?php echo __('Morning')?></td>
			<?php foreach ($week_list as $date => $monday):?>
			<td>
					<ul class="media-list" style="list-style-type: none;">
					<?php foreach ($list_course_schedules as $course_schedules):?>
					<?php
					$session = null;
					$end_at = $course_schedules->getEndTimeAt () ? date ( 'H:i', strtotime ( $course_schedules->getEndTimeAt () ) ) : null;
					if ((strtotime ( $end_at ) < strtotime ( '12:00' )) && (strtotime ( $end_at ) >= strtotime ( '6:00' )))
						$session = true; // sang
					?>
					<?php if (date('Y-m-d', strtotime($course_schedules->getDateAt())) == $date &&  ($session))  :?>
					<li
							class="media <?php if ($edit_ps_service_course_schedule_id == $course_schedules->getId()) echo 'bg-color-orange txt-color-white';?>"
							rel="popover-hover" data-placement="top"
							data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
							data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>'; echo "";?>">
							<div class="media-body">
								<ul class="media-list" style="list-style-type: none;">
									<li>
									<?php if(myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT' )) :?>
			                        	<a rel="popover-hover" data-placement="top"
										data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
										data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>';?>"
										href="<?php echo url_for('@ps_service_course_schedules_edit?id='.$course_schedules->getId())?>"><?php echo $course_schedules->getCoursesTitle()?></a>
			                        <?php else :?>
									<?php echo $course_schedules->getCoursesTitle()?>
									<?php endif?>
								</li>
									<li>
									<?php echo __('CR:').$course_schedules->getClassRoomTitle()?>
								</li>
									<li>
								<?php echo date('H:i', strtotime($course_schedules->getStartTimeAt())).'-'.$end_at?>
								</li>
									<li>
								<?php echo __('Teacher:')?><?php echo $course_schedules->getTeacher();?>
								</li>
									<li><a target=_blank
										href="<?php echo url_for('@ps_student_service_course_comment?psscs_id='.$course_schedules->getId()) ?>"
										class="btn btn-xs btn-default"> <i
											class="fa fa-fw fa-comments txt-color-blue"></i>
									</a></li>
								</ul>
							</div>
						</li>
					<?php endif;?>
					<?php endforeach;?>
				</ul>
				</td>
			<?php endforeach;?>
			</tr>
			<tr>
				<td class="text-center"><?php echo __('Afternoon')?></td>
			<?php foreach ($week_list as $date => $monday):?>
			<td>
					<ul class="media-list" style="list-style-type: none;">
					<?php foreach ($list_course_schedules as $course_schedules):?>
					<?php
					$session = null;
					$end_at = $course_schedules->getEndTimeAt () ? date ( 'H:i', strtotime ( $course_schedules->getEndTimeAt () ) ) : null;
					if ((strtotime ( $end_at ) <= strtotime ( '18:00' )) && (strtotime ( $end_at ) >= strtotime ( '12:00' )))
						$session = true; // sang
					?>
					<?php if (date('Y-m-d', strtotime($course_schedules->getDateAt())) == $date &&  ($session))  :?>
					<li
							class="media <?php if ($edit_ps_service_course_schedule_id == $course_schedules->getId()) echo 'bg-color-orange txt-color-white';?>"
							rel="popover-hover" data-placement="top"
							data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
							data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>'; echo "";?>">
							<div class="media-body">
								<ul class="media-list" style="list-style-type: none;">
									<li>
									<?php if(myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT' )) :?>
			                        	<a rel="popover-hover" data-placement="top"
										data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
										data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>';?>"
										href="<?php echo url_for('@ps_service_course_schedules_edit?id='.$course_schedules->getId())?>"><?php echo $course_schedules->getCoursesTitle()?></a>
			                        <?php else :?>
									<?php echo $course_schedules->getCoursesTitle()?>
									<?php endif?>
								</li>
									<li>
									<?php echo __('CR:').$course_schedules->getClassRoomTitle()?>
								</li>
									<li>
									<?php echo date('H:i', strtotime($course_schedules->getStartTimeAt())).'-'.date('H:i', strtotime($course_schedules->getEndTimeAt()))?>
								</li>
									<li>
									<?php echo __('Teacher:')?><?php echo $course_schedules->getTeacher();?>
								</li>
									<li><a target=_blank
										href="<?php echo url_for('@ps_student_service_course_comment?psscs_id='.$course_schedules->getId()) ?>"
										class="btn btn-xs btn-default"> <i
											class="fa fa-fw fa-comments txt-color-blue"></i>
									</a></li>
								</ul>
							</div>
						</li>
					<?php endif;?>
					<?php endforeach;?>
				</ul>
				</td>
			<?php endforeach;?>
			</tr>
			<tr>
				<td class="text-center"><?php echo __('Evening')?></td>
			<?php foreach ($week_list as $date => $monday):?>
			<td>
					<ul class="media-list" style="list-style-type: none;">
					<?php foreach ($list_course_schedules as $course_schedules):?>
					<?php
					$session = null;
					$end_at = $course_schedules->getEndTimeAt () ? date ( 'H:i', strtotime ( $course_schedules->getEndTimeAt () ) ) : null;
					if ((strtotime ( $end_at ) <= strtotime ( '22:00' )) && (strtotime ( $end_at ) > strtotime ( '18:00' )))
						$session = true; // Sang
					?>
					<?php if (date('Y-m-d', strtotime($course_schedules->getDateAt())) == $date &&  ($session))  :?>
						<li
							class="media <?php if ($edit_ps_service_course_schedule_id == $course_schedules->getId()) echo 'bg-color-orange txt-color-white';?>">
							<div class="media-body">
								<ul class="media-list" style="list-style-type: none;">
									<li>
									<?php if(myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT' )) :?>
			                        	<a rel="popover-hover" data-placement="top"
										data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
										data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>'; echo "";?>"
										href="<?php echo url_for('@ps_service_course_schedules_edit?id='.$course_schedules->getId())?>"><?php echo $course_schedules->getCoursesTitle()?></a>
			                        <?php else :?>
										<a href="#" rel="popover-hover" data-placement="top"
										data-original-title="<?php echo __('Subject').': '.$course_schedules->getServiceTitle()?>"
										data-content="<?php echo __('Courses').': '.$course_schedules->getCoursesTitle().'</br>';?>"><?php echo $course_schedules->getCoursesTitle();?></a>
									<?php endif?>
									</li>
									<li>
										<?php echo __('CR:').$course_schedules->getClassRoomTitle()?>
									</li>
									<li>
										<?php echo date('H:i', strtotime($course_schedules->getStartTimeAt())).'-'.date('H:i', strtotime($course_schedules->getEndTimeAt()))?>
									</li>
									<li>
										<?php echo __('Teacher:')?><?php echo $course_schedules->getTeacher();?>
									</li>
									<li><a target=_blank
										href="<?php echo url_for('@ps_student_service_course_comment?psscs_id='.$course_schedules->getId()) ?>"
										class="btn btn-xs btn-default"> <i
											class="fa fa-fw fa-comments txt-color-blue"></i>
									</a></li>
								</ul>
							</div>
						</li>
					<?php endif;?>
					<?php endforeach;?>
				</ul>
				</td>
			<?php endforeach;?>							
		</tr>
		</tbody>
	</table>
</div>