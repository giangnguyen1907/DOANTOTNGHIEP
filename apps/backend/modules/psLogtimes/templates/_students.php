
<div class="widget-body">
	<div class="dt-toolbar" style="padding-bottom: 10px;">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter-form" class="form-inline pull-left"
			action="<?php echo url_for('ps_logtimes_collection', array('action' => 'new')) ?>"
			method="post">
			<div class="pull-left">
		<?php echo $formFilter->renderHiddenFields(true) ?>
	 	  <div class="form-group">
					<label>
		 <?php echo $formFilter['ps_customer_id']->render() ?>
		 <?php echo $formFilter['ps_customer_id']->renderError() ?>
		 </label>
				</div>

				<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_workplace_id']->render() ?>
		 <?php echo $formFilter['ps_workplace_id']->renderError() ?>
		 </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_school_year_id']->render() ?>
		 <?php echo $formFilter['ps_school_year_id']->renderError() ?>
		 </label>
				</div>

				<div class="form-group">
					<label>
		 <?php echo $formFilter['class_id']->render() ?>
		 <?php echo $formFilter['class_id']->renderError() ?>
		 </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['tracked_at']->render() ?>
		 <?php echo $formFilter['tracked_at']->renderError() ?>
		 </label>
				</div>
				<div class="form-group" style="display: none">
		 <?php //echo $formFilter['student_id']->render() ?>
		 </div>
				<div class="form-group">
					<label>
						<button type="submit"
							class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
							<i class="fa fa-search"></i>
						</button>
					</label>
				</div>
			</div>
		</form>
	</div>
	<div class="table-responsive">
		<table id="dt" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
					<th><?php echo __('Student name', array(), 'messages') ?></th>
					<th style="width: 80px;" class="text-center"><?php echo __('Status', array(), 'messages') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php

$tracked_at = $formFilter->getDefault ( 'tracked_at' );
			foreach ( $filter_list_student as $student ) {

				$link = ($student->getPsLogtimeId () > 0) ? url_for ( '@ps_logtimes_edit?id=' . $student->getPsLogtimeId () ) : url_for ( '@ps_logtimes_new?student_id=' . $student->getStudentId () . '&tracked_at=' . strtotime ( $tracked_at ) );

				?>
				<tr
					<?php if ($student->getStudentId() == $sf_request->getParameter('student_id') || $student->getStudentId() == $form->getObject()->getStudentId()) echo 'class="highlight"';?>>
					<td>			
        			<?php
				$path_file = '';
				if ($student->getImage () != '') {
					$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
					echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
				}
				?>
        			</td>
					<td><a href="<?php echo $link;?>"
						row-id="<?php echo $student->getStudentId()?>" class="row-id"
						rel="popover-hover" data-html="true"
						data-original-title="<?php echo $student->getFullName(); ?>"
						data-content="<img  style='max-width: 45px; text-align: center;'
						src='<?php echo $path_file;?>' /> <?php echo $student->getStudentCode(); ?> " >
					<?php echo $student->getFullName() ?>
					</a></td>

					<td class="row-id text-center">					
						<?php if($student->getPsLogtimeId()):?>
						<span class="label label-success" style="font-weight: normal;"><?php echo __('Marked') ?></span>
						<?php else:?>
						<span class="label label-warning" style="font-weight: normal;"><?php echo __('Unmarked') ?></span>
						<?php endif;?>
					</td>
				</tr>
					<?php } ?>
			</tbody>
		</table>
	</div>

</div>
<script type="text/javascript">
$(document).ready(function() {

	/*
	$('.row-id').click(function() {
		var student_id = $(this).attr('row-id');

		alert(student_id);
		
		$("#student_filter_student_id").val(student_id);
		$("#ps-filter-form").submit();		
		return true;		
	});	
	*/
	
	// BEGIN: filters
	$('#student_filter_ps_customer_id').change(function() {
		
		$("#student_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('student_filter_ps_workplace_id');
		$('#student_filter_ps_workplace_id').select2('val','');
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#student_filter_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_filter_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#student_filter_ps_workplace_id').select2('val','');

				$("#student_filter_ps_workplace_id").html(msg);

				$("#student_filter_ps_workplace_id").attr('disabled', null);

				$("#student_filter_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#student_filter_class_id').select2('val','');
					$("#student_filter_class_id").html(msg);
					$("#student_filter_class_id").attr('disabled', null);
			    });
		    });
		}		
	});
	 
	$('#student_filter_ps_workplace_id').change(function() {
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filter_class_id').select2('val','');
			$("#student_filter_class_id").html(msg);
			$("#student_filter_class_id").attr('disabled', null);
	    });
	});

	$('#student_filter_ps_school_year_id').change(function() {
		
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filter_class_id').select2('val','');
			$("#student_filter_class_id").html(msg);
			$("#student_filter_class_id").attr('disabled', null);
	    });
	});
	$('.time_picker').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false,
		defaultTime : null
	});
	// END: filters
	

});
$('#student_filter_tracked_at').datepicker({
    dateFormat : 'dd-mm-yy',
	  changeMonth: true,
	  changeYear: true,
	  maxDate: new Date(),
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>',
  });
</script>

