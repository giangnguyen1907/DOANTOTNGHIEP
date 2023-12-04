<div class="widget-body">
	<div class="dt-toolbar" style="padding-bottom: 10px;">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
			action="<?php echo url_for('@ps_student_service_course_comment_view_comment') ?>"
			method="post">
			<div class="pull-left">
	 	<?php echo $formFilter->renderHiddenFields(true) ?>
		<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_service_id']->render() ?>
		 <?php echo $formFilter['ps_service_id']->renderError() ?>
		 </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_service_course_id']->render() ?>
		 <?php echo $formFilter['ps_service_course_id']->renderError() ?>
		 </label>
				</div>
				<div class="form-group">
					<label>
		    	<?php echo $helper->linkToFilterSearch() ?>
				</label>
				</div>
			</div>
		</form>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive custom-scroll"
			style="overflow-y: scroll; max-height: 300px;">
			<table id="dt" class="table table-striped table-bordered table-hover"
				width="100%">
				<thead>
					<tr>
						<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
						<th class="text-center"><?php echo __('Student', array(), 'messages') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($filter_list_student as $student): ?>
					<tr>
						<td>
							<?php

if ($student->getImage () != "")
						$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
					echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
					?>
						</td>
						<td><a
							<?php if($student->getStudentId() == $student_id) echo "style='font-weight:bold;'" ?>
							href=" <?php echo url_for('@ps_student_service_course_comment_view_comment?student='.$student->getStudentId().'&schid='.$formFilter['ps_service_course_id']->getValue()) ?>"> 
								<?php echo $student->getFullName() ?>
							</a></td>
					</tr>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
	</div>
</div>