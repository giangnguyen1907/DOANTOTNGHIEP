<style>
.list-inline {
    margin-left: 0;
}
</style>

<div class="widget-body">
	<div class="dt-toolbar" style="padding-bottom: 10px;">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
			action="<?php echo url_for('ps_student_growths_collection', array('action' => 'new')) ?>"
			method="post">
			<div class="pull-left">
	 	<?php echo $formFilter->renderHiddenFields(true) ?>
		<div class="form-group">
					<label>		 
		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
		 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
		 </label>
				</div>
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
		 <?php echo $formFilter['class_id']->render() ?>
		 <?php echo $formFilter['class_id']->renderError() ?>
		  </label>
				</div>

				<div class="form-group">
					<label>
			 <?php echo $formFilter['examination_id']->render() ?>
			 <?php echo $formFilter['examination_id']->renderError() ?>
			 </label>
				</div>
				<div class="form-group">
					<label><?php echo $helper->linkToFilterSearch() ?></label>
				</div>
			</div>
		</form>
	</div>
	<div class="custom-scroll table-responsive">
		<table id="dt" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><?php echo __('Student', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Birthday') ?></th>
					<th class="text-center"><?php echo __('Examination') ?></th>
					<th class="text-center"><?php echo __('Gender') ?></th>
					<th class="text-center"><?php echo __('Height') ?></th>
					<th class="text-center"><?php echo __('Weight') ?></th>
					<th class="text-center"><?php echo __('Send notication') ?></th>
					<th class="text-center"><?php echo __('Action') ?></th>
				</tr>
			</thead>

			<tbody>
			<?php

			$examination_id = $formFilter->getDefault ( 'examination_id' );
			if ($examination_id > 0) {
				$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
				$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
			} else {
				$tracked_at = date ( 'Y-m-d' );
			}

			$tracked_at = strtotime ( $tracked_at );

			foreach ( $filter_list_student as $student ) {
				?>
			
					<tr
					<?php if ($ps_student_growths && $ps_student_growths->getStudent()->getId() == $student->getStudentId()) echo 'class="highlight";' ?>>						
					<?php if($student->getPsStudentGrowthsId()) :?>
					<td><span class="pull-left">
						<?php
					if ($student->getImage () != '') {
						$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
						echo '<img style="max-width: 45px; text-align: center; padding-right:3px;" src="' . $path_file . '">';
					}
					?>
						</span> <a
						href="<?php echo url_for('@ps_student_growths_edit?id='.$student->getPsStudentGrowthsId()) ; ?>"><?php echo $student->getFullName() ?></a>
						<p>
							<code><?php echo $student->getStudentCode(); ?></code>
						</p></td>	
					<?php else:?>
					<td style="vertical-align: top;"><span class="pull-left">
						<?php
					if ($student->getImage () != '') {
						$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
						echo '<img style="max-width: 45px; text-align: center; padding-right:3px;" src="' . $path_file . '">';
					}
					?>
						</span> <a
						href="<?php echo url_for('@ps_student_growths_new?student_id='.$student->getStudentId()).'&date='.$examination_id;?>"><?php echo $student->getFullName() ?></a>
						<p>
							<code><?php echo $student->getStudentCode(); ?></code>
						</p></td>	
					<?php endif;?>
					
					<td class="text-center">
					<?php
				if (false !== strtotime ( $student->getBirthday () ))
					echo '<div class="date">' . format_date ( $student->getBirthday (), "dd-MM-yyyy" ) . '</div><div><code>' . PreSchool::getAge ( $student->getBirthday (), false ) . '</code></div>';
				else
					echo '&nbsp;';
				?>
					</td>
					<td class="text-center">
						<?php echo $student->getExName();?><br /> <code><?php echo $student->getInputDateAt();?></code>
					</td>
					<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
					<td class="text-center"><?php echo $student->getHeight() ?><br/>
					<?php include_partial('psStudentGrowths/index_height', array('value' => $student->getIndexHeight()))?>
					</td>
					<td class="text-center"><?php echo $student->getWeight() ?><br/>
					<?php include_partial('psStudentGrowths/index_weight', array('value' => $student->getIndexWeight()))?>
					</td>
					<td class="text-center">
					
					<?php if ($sf_user->hasCredential(array('PS_MEDICAL_GROWTH_PUSH')) && $student->getPsStudentGrowthsId() > 0): ?>
					  	<div id="ic-loading-<?php echo $student->getPsStudentGrowthsId();?>"
							style="display: none;">
							<i class="fa fa-spinner fa-2x fa-spin text-success"
								style="padding: 3px;"></i><?php echo __('Loading...')?>
					    </div> 
					    <a class="btn btn-labeled btn-success push_notication" id="push_notication-<?php echo $student->getPsStudentGrowthsId() ?>"
						href="javascript:;" value="<?php echo $student->getStudentId() ?>" data-value="<?php echo $student->getPsStudentGrowthsId() ?>"> 
							<span class="btn-label list-inline" id="box-<?php echo $student->getPsStudentGrowthsId() ?>">
					    		<?php echo get_partial('psStudentGrowths/load_number_notication', array('value' => $student->getNumberPushNotication()))?>
					    	</span> 
					    	<span class="btn-control"> <i class="fa fa-bell"></i></span>
						</a>
					<?php endif;?>
					
					</td>
					<td class="text-center">
					
					<?php if($student->getPsStudentGrowthsId()) :?>
					<a class="btn btn-xs btn-default btn-edit-td-action"
						href="<?php echo url_for('@ps_student_growths_edit?id='.$student->getPsStudentGrowthsId()) ; ?>"><i
							class="fa-fw fa fa-pencil txt-color-blue"></i></a>
					<?php else:?>
					<a class="btn btn-xs btn-default btn-edit-td-action"
						href="<?php echo url_for('@ps_student_growths_new?student_id='.$student->getStudentId()).'&date='.$examination_id;?>"><i
							class="fa-fw fa fa-plus txt-color-blue"></i></a>
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

	// BEGIN: filters
	$('#student_filter_ps_customer_id').change(function() {

		resetOptions('student_filter_ps_workplace_id');
		$('#student_filter_ps_workplace_id').select2('val','');
		
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');

		resetOptions('student_filter_examination_id');
		$('#student_filter_examination_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#student_filter_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_filter_class_id").attr('disabled', 'disabled');
			$("#student_filter_examination_id").attr('disabled', 'disabled');
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

		    });
		}		
	});
	 
	$('#student_filter_ps_workplace_id').change(function() {
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
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

		$("#student_filter_examination_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_student_growths_examination') ?>',
		    type: "POST",
		    data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
		    processResults: function (data, page) {
	          	return {
	            	results: data.items
	          	};
	        },
		}).done(function(msg) {
		    $('#student_filter_examination_id').select2('val','');
			$("#student_filter_examination_id").html(msg);
			$("#student_filter_examination_id").attr('disabled', null);
		});
		    
	    
	});

	$('#student_filter_ps_school_year_id').change(function() {
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
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

		$("#student_filter_examination_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_student_growths_examination') ?>',
		    type: "POST",
		    data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_ps_school_year_id').val(),
		    processResults: function (data, page) {
	          	return {
	            	results: data.items
	          	};
	        },
		}).done(function(msg) {
		    $('#student_filter_examination_id').select2('val','');
			$("#student_filter_examination_id").html(msg);
			$("#student_filter_examination_id").attr('disabled', null);
		});
		
	});

	// END: filters
	


$('#student_filter_tracked_at').datepicker({
    dateFormat : 'dd-mm-yy',
	  changeMonth: true,
	  changeYear: true,
	  maxDate: new Date(),
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>',
  });
});

</script>
