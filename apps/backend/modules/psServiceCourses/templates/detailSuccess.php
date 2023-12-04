<?php use_helper('I18N', 'Date')?>
<style type="text/css">
.control-label {
	font-weight: bold;
}

.mt-1 {
	margin-top: 2rem;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">
		<strong><?php echo __('Service courses information: %%name%%', array('%%name%%' => $service_courses_detail->getTitle())) ?></strong>
	</h4>
</div>

<div class="modal-body">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<ul class="nav nav-tabs tabs-pull-right">
			<li class="active"><a data-toggle="tab"
				href="#service_courses_information"><?php echo __('Service courses information') ?></a></li>
			<li><a data-toggle="tab" href="#service_courses_student"><?php echo __('Service courses student') ?></a></li>
		</ul>

		<div class="tab-content mt-1">

			<div id="service_courses_information" class="tab-pane fade in active">
				<div class="row">

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Service courses name') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $service_courses_detail->getTitle()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Service courses teacher') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $service_courses_detail->getTeacherName()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Service courses title') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $service_courses_detail->getServiceTitle()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Start at') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo format_date($service_courses_detail->getStartAt(), 'dd-MM-yyyy')?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('End at') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo format_date($service_courses_detail->getEndAt(), 'dd-MM-yyyy')?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Is activated') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $service_courses_detail->getIsActivated() ? '<i class="fa fa-check txt-color-green"></i> '.__('Activated') : '<i class="fa fa-times txt-color-red"></i> '.__('Not activated')?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Note') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $service_courses_detail->getNote() ?>
								</p>
						</div>
					</div>
				</div>
			</div>

			<div id="service_courses_student" class="tab-pane fade">
				<div class="row">
					<?php
					$school_code = $service_courses_detail->getPsService ()
						->getPsCustomer ()
						->getSchoolCode ();
					?>

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- 						<div class="widget-body-toolbar">
							<h4><?php echo __('Service sources student') ?></h4>
					    </div>     -->
						<table id="dt_basic"
							class="table table-striped table-bordered table-hover"
							width="100%">
							<tr class="info">
								<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
								<th><?php echo __('Student code');?></th>
								<th><?php echo __('Full name');?></th>
								<th class="text-center"><?php echo __('Birthday');?></th>
								<th class="text-center"><?php echo __('Sex');?></th>

							</tr>
							<?php foreach($list_student as $student): ?>
							<tr>
								<td>			
								<?php
								if ($student->getImage () != '') {
									$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $school_code . '/' . $student->getYearData () . '/' . $student->getImage ();
									echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
								}
								?>
								</td>
								<td><?php echo $student->getStudentCode();?></td>
								<td><?php echo $student->getFullName();?></td>
								<td class="text-center">
									<div class="date"><?php echo (false !== strtotime($student->getBirthday())) ? format_date($student->getBirthday(),"dd-MM-yyyy").'<div><code>'.PreSchool::getAge($student->getBirthday(),false).'</code>' : '';?>
									
								
								</td>
								<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
							</tr>
							<?php endforeach;?>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
	<?php
	if ($sf_user->hasCredential ( 'PS_STUDENT_SERVICE_COURSES_EDIT' )) {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_service_courses_edit', $service_courses_detail, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
	}
	?>
</div>


