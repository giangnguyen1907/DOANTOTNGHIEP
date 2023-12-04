<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php  echo $form['ps_service_course_id']->render()?>
<div class="form-group"></div>

<div class="txt-color-green" id="loading" style="display: none;">
	<i class="fa fa-refresh fa-spin fa-1x fa-fw" style="padding: 3px;"></i> <?php echo __('Loading...')?>
	</div>
<div id="list_student_main">
		<?php include_partial('psStudentService/list_student_main', array('ps_service_courses' => $ps_service_courses, 'form' => $form, 'list_student' => $list_student, 'pager' => $pager)) ?>	
	</div>
