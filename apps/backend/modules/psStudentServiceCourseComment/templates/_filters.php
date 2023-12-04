<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">	
  <?php if ($form->hasGlobalErrors()): ?>
    <?php echo $form->renderGlobalErrors() ?>
  <?php endif; ?>

	<form id="ps-filter-form" class="form-inline pull-right"
		action="<?php echo url_for('ps_student_service_course_comment_collection', array('action' => 'filter')) ?>"
		method="post">
  	<?php echo $form->renderHiddenFields() ?>
  	<div class="pull-left">
    <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>          
          <?php

include_partial ( 'psStudentServiceCourseComment/filters_field', array (
							'name' => $name,
							'attributes' => $field->getConfig ( 'attributes', array () ),
							'label' => $field->getConfig ( 'label' ),
							'help' => $field->getConfig ( 'help' ),
							'form' => $form,
							'field' => $field,
							'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_filter_field_' . $name ) )?>

    <?php endforeach; ?>
    	
    	<div class="form-group">
				<label> <a data-toggle="modal" id="load-calendar"
					data-target="#remoteModal" data-backdrop="static"
					class="btn btn-sm btn-default btn-filter-reset btn-psadmin view_calendar"
					href="<?php echo url_for(@ps_student_service_course_comment).'/'?>detail"><i
						class="fa-fw fa fa-calendar txt-color-blue" title="Chi tiết"></i></a>
				</label>
			</div>

			<div class="form-group">
				<label>
	    	<?php echo $helper->linkToFilterSearch() ?>
	    	<?php echo $helper->linkToFilterReset() ?>
	      </label>
			</div>

		</div>

	</form>
</div>
<script>
$(document).ready(function() {
	// lay gia tri tren filter
	$('.view_calendar').click(function() {
		
		var ps_customer_id      	= $('#student_service_course_comment_filters_ps_customer_id').val();

		var ps_workplace_id 		= $('#student_service_course_comment_filters_ps_workplace_id').val();

		var ps_service_id 			= $('#student_service_course_comment_filters_ps_service_id').val();

		var ps_service_course_id 	= $('#student_service_course_comment_filters_ps_service_course_id').val();
		
		var date_at 				= $('#student_service_course_comment_filters_tracked_at').val();

// 		alert(ps_customer_id + ps_workplace_id + ps_service_id + ps_service_course_id + date_at)
		
		var url = '<?php echo url_for(@ps_student_service_course_comment).'/'?>detail?' + 'customer=' + ps_customer_id + '&workplace=' + ps_workplace_id + '&service=' + ps_service_id + '&course_id=' + ps_service_course_id + '&date_at=' + date_at;;
		
		$('#load-calendar').attr('href', url);
		
  	});
});
</script>