<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<?php //echo time() .'___'. strtotime(date('Y-m-d H:i:s'))?>
<section id="widget-grid">
<?php include_partial('psAttendances/flashes')?>
	<div class="row">
		<article
			class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget" id="wid-id-2"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-editbutton="false" data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false" data-widget-collapsed="false"
				data-widget-sortable="false">
				<header>
					<span class="widget-icon"><i class="fa fa-calendar-check-o"></i></span>
					<h2><?php echo __('Trackbook', array(), 'messages') ?>
					<span><strong><?php echo $student->getFirstName().' '.$student->getLastName()?></strong>(<code><?php echo $student->getStudentCode()?></code>)</span>
						<span>
				       		<?php
													if (false !== strtotime ( $student->getBirthday () ))
														echo '(' . format_date ( $student->getBirthday (), "dd-MM-yyyy" ) . ')&nbsp;<code>' . PreSchool::getAge ( $student->getBirthday (), false ) . '</code>';
													else
														echo '&nbsp;';
													?>		
							- <strong>
							<?php if ($class):?>
								<?php echo $class->getName();?>
							<?php endif?>
							</strong>
						</span>
					</h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">					
							<?php
							$path_file = '';
							if ($student->getImage () != '') {
								$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getPsCustomer ()
									->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
								echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
							}
							?>
			       			
				       		<div>
									<label><strong><?php echo __('Registered service')?></strong>:</label>
					       		<?php foreach ($list_registered_service as $service) :?>
					       			<?php echo $service->getTitle();?>;
					       		<?php endforeach;?>
				       		</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="div_form_filter form-inline">
			                	<?php echo $form_filter->renderFormTag('trackbook') ?>
			                		<div class="form-group ">
			                			<?php echo $form_filter['ps_month']->renderError()?>
			                			<?php echo $form_filter['ps_month']->render()?>
			                		</div>
									<div class="form-group ">
			                			<?php echo $form_filter['ps_year']->renderError()?>
			                			<?php echo $form_filter['ps_year']->render()?>
			                		</div>
									<button type="submit" rel="tooltip" data-placement="bottom"
										data-original-title="<?php echo __('Select')?>"
										class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
										<i class="fa-fw fa fa-search"></i>
									</button>
									</form>
								</div>
							</div>
						</div>

						<div class="dt-toolbar">	                	
	                	<?php
							echo '<strong>' . __ ( 'Usage statistics' ) . '</strong>' . ' :';
							// Thống kê số lần sử dụng dịch vụ $year $month .-01 => strto
							$studentServiceDiary = $student->getStudentServiceDiaryTotal ( strtotime ( $year . '-' . $month . '-01' ) );
							foreach ( $studentServiceDiary as $key => $obj ) :
							?>	                	
	                	<?php echo $obj->getTitle().": <strong>".$obj->getByNumber()."</strong> ";?>	                	
	                	<?php endforeach;?>	                
	                </div>

						<form id="frm_batch"
							action="<?php echo url_for('@ps_attendances_trackbook_save') ?>"
							method="post">					
						<?php if($check_student_class):?>
					 	<div
								class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>						
							<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_students')?>"><i
										class="fa-fw fa fa-list-ul" aria-hidden="true"
										title="<?php echo __('Back to list')?>"></i> <?php echo __('Back to list')?></a>
							<?php endif; ?>
							</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>						
							<button type="submit"
										class="btn btn-default btn-success btn-sm btn-psadmin pull-right">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
											title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
							<?php endif; ?>
							</div>
							</div>
					 	<?php
						
							if ($count_late_fee > 0) {
								include_partial ( 'psAttendances/list_logtimes', array (
										'month' => $month,
										'year' => $year,
										'class_id' => $class_id,
										'student' => $student,
										'list_relative' => $list_relative,
										'list_member' => $list_member,
										'student_logtime' => $student_logtime,
										'ps_work_places' => $ps_work_places ) );
							} else {
								include_partial ( 'psAttendances/list_logtimes_2', array (
										'month' => $month,
										'year' => $year,
										'class_id' => $class_id,
										'student' => $student,
										'list_relative' => $list_relative,
										'list_member' => $list_member,
										'student_logtime' => $student_logtime,
										'ps_work_places' => $ps_work_places ) );
							}
							/**/
							?>
						<div
								class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>						
							<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_students')?>"><i
										class="fa-fw fa fa-list-ul" aria-hidden="true"
										title="<?php echo __('Back to list')?>"></i> <?php echo __('Back to list')?></a>
							<?php endif; ?>
							</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>						
							<button type="submit"
										class="btn btn-default btn-success btn-sm btn-psadmin pull-right">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
											title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
							<?php endif; ?>
							</div>
							</div>
						<?php else:?>
	                	<div class="dt-toolbar no-margin no-border">
								<div class="col-xs-12 col-sm-12">
									<div class="alert alert-info fade in">
		                		<?php echo __('At this point students are not classified')?>
		                		</div>
								</div>
							</div>
							<div
								class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>						
							<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_students')?>"><i
										class="fa-fw fa fa-list-ul" aria-hidden="true"
										title="<?php echo __('Back to list')?>"></i> <?php echo __('Back to list')?></a>
							<?php endif; ?>
							</div>
							</div>
						<?php endif;?>
					</form>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>