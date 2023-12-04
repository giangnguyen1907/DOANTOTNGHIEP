<style>
@media ( min-width : 992px) 
.modal-lg {
	min-width:800px;
	width:1000px;}
.modal-lg {
	min-width: 800px;
	width: 1000px;
}
.radio-inline{margin-top: 10px!important}
</style>
<script type="text/javascript">

$("#errors").html("<?php echo __('You have not selected class !')?>");

function moveClass_Click() {
	
	if (!CheckStudent()) {
		
		$("#errors").html("<?php echo __('You have not selected any student !')?>");

	    $('#warningModal').modal({show: true,backdrop:'static'});

	    setTimeout(function() {
	        $('#remoteModal123,#remoteModal12').modal('hide');
	    }, 0);
	    
	    $('#btn-student-move-class').addClass("disabled");
		$('#btn-student-move-class').attr('disabled', 'disabled');

	    $('#btn-stop-studying').addClass("disabled");
		$('#btn-stop-studying').attr('disabled', 'disabled');
		
	    return false;
	}else{
		
		$('#btn-student-move-class').attr('disabled', false);
		$('#btn-student-move-class').removeClass("disabled");
		
		$('#btn-stop-studying').attr('disabled', false);
		$('#btn-stop-studying').removeClass("disabled");
		
		$('#form_student_myclass_id').click(function() {
			if ($(this).val() > 0) {
				$('#btn-student-move-class').attr('disabled', false);
				$('#btn-student-move-class').removeClass("disabled");
			}else{
				$('#btn-student-move-class').addClass("disabled");
				$('#btn-student-move-class').attr('disabled', 'disabled');
			}
		});

		$('#form_student_class_from_id').val(<?php echo $class_from_id; ?>);
		
	}
	
}

function CheckStudent() {
	
	var boxes = document.getElementsByTagName('input');

	for (i = 0; i < boxes.length; i++ ) {
		box = boxes[i];
		if ( box.type == 'checkbox' && box.className == 'select checkbox') {						
			if (box.checked == true)
	  		 return true;	
	  	}
	}

	return false;		   
}

function setSelect(student_id,ele) {    	

   	if (ele.checked) {
   		
   		$('.myclass_mode_'+ student_id).attr('disabled', false);
   		
   	} else {
   		
   		$('.myclass_mode_'+ student_id).attr('disabled', 'disabled');
   	}
}


$(document).ready(function() {

	$('#btn-stop-studying').click(function() {

		if($('#form_student_start_at').val() == ''){
			alert('<?php echo __("Start at required") ?>');
		}else{
    		
    		$('#ps-form-value').attr('action', '<?php echo url_for('@student_stop_studying') ?>');
    		$('#ps-form-value').submit();
    
    		return true;
		}
	});

	$('#move_class_type').change(function() {
		if($(this).val() == "<?php echo PreSchool::SC_STATUS_STOP_STUDYING ?>"){
			alert('<?php echo __("Note: Type class is stop studying")?>');
		}
	});
	
	$('#btn-student-move-class').click(function() {

		if($('#move_class_myclass_id').val() == ''){
			alert('<?php echo __("You have not selected class !") ?>');

		    $('#btn-student-move-class').addClass("disabled");
			$('#btn-student-move-class').attr('disabled', 'disabled');
				
		}else{
    		
    		$('#ps-form-value').attr('action', '<?php echo url_for('@ps_students_move_class_save') ?>');
    		$('#ps-form-value').submit();
    
    		return true;
		}
	});

	$('#form_student_start_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

	$('#form_student_stop_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

	$('#move_class_start_at').datepicker({
			dateFormat : 'dd-mm-yy',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#move_class_stop_at')
						.datepicker('option',
								'minDate',
								selectedDate);
			}
		}).on(
		'changeDate',
		function(e) {
			// Revalidate the date field
			$('#ps_off_school_form').formValidation(
					'revalidateField',
					'move_class[start_at]');
		});

	$('#move_class_stop_at')
		.datepicker(
				{
					dateFormat : 'dd-mm-yy',
					minDate : new Date(),
					prevText : '<i class="fa fa-chevron-left"></i>',
					nextText : '<i class="fa fa-chevron-right"></i>',
					onSelect : function(selectedDate) {
						$('#move_class_start_at')
								.datepicker('option',
										'maxDate',
										selectedDate);
					}
				}).on(
				'changeDate',
				function(e) {
					// Revalidate the date field
					$('#ps_off_school_form').formValidation(
							'revalidateField',
							'move_class[stop_at]');
				});

	$('.select_all').on('change', function() {
		
		   if($(".select_all:checked").val() == 0){
			   $('.checkbox').prop('checked',true);
		   }else{
			   $('.checkbox').prop('checked',false);
			}
		});
});


</script>

<div class="widget-body">
	<div class="dt-toolbar">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors()?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
			action="<?php echo url_for('ps_class_collection', array('action' => 'move')) ?>"
			method="post">
			<div class="pull-left">	
	 	 <?php echo $formFilter->renderHiddenFields(true)?>
	 	 	<div class="form-group">
					<label>
				 <?php echo $formFilter['school_year_id']->render()?>
				 <?php echo $formFilter['school_year_id']->renderError()?>
				 </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_customer_id']->render()?>
		 <?php echo $formFilter['ps_customer_id']->renderError()?>
		 </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['ps_workplace_id']->render()?>
		 <?php echo $formFilter['ps_workplace_id']->renderError()?>
		  </label>
				</div>
				<div class="form-group">
					<label>
		 <?php echo $formFilter['class_id']->render()?>
		 <?php echo $formFilter['class_id']->renderError()?>
		 </label>
				</div>
				<div class="form-group">
					<label> <input type="hidden" id="not_in_class" value="not_in_class">
					</label>
				</div>
				<div class="form-group">
					<label>
						<button type="submit" rel="tooltip" data-placement="bottom"
							data-original-title="<?php echo __('Search')?>"
							class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin"
							onclick="return search_Click();">
							<i class="fa fa-search"></i>
						</button>
					</label>
				</div>
			</div>
		</form>
	</div>
	<div class="box-body" style="clear: both">

		<form id="ps-form-value" action="<?php echo url_for('ps_class_collection', array('action' => 'moveClass')) ?>"
			method="post">
			<div class="table-responsive custom-scroll"
				style="max-height: 500px; overflow-y: scroll;">
				<table id="dt" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
							<th><?php echo count($filter_list_student).' '.__('Student', array(), 'messages') ?></th>
							<th class="text-center"><?php echo __('Birthday') ?></th>
							<th class="text-center"><?php echo __('Sex') ?></th>
							<th class="text-center">							
								<?php echo __('Updated by') ?>	
							</th>
							<th class="text-center">							
								<label class="checkbox-inline"> 
									<input class="select_all checkbox" type="checkbox" value="0"><span></span>
								</label>							
							</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach ( $filter_list_student as $student ) {
					?>
					<tr>
						<td>			
			        	<?php
						if ($student->getImage () != '') {
							$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
							echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
						}
						?>
        				</td>
						<td>
							<?php echo $student->getFullName() ?>
							<p><code><?php echo $student->getStudentCode();?></code></p>
						</td>
						<td class="text-center"><?php echo date('d-m-Y', strtotime($student->getBirthday())) ?></td>
						<td class="text-center">
							<?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?>
						</td>
						<td class="text-center">
							<?php 
							echo $student->getUpdatedBy () . '<br/>';
							echo false !== strtotime ( $student->getUpdatedAt () ) ? date ( 'd/m/Y', strtotime ( $student->getUpdatedAt () ) ) : '&nbsp;';
							?>
						</td>
						
						<td class="text-center">
							<label class="checkbox-inline">
								<input class="select checkbox" type="checkbox" value="<?php echo $student->getStudentId();?>" name="student_ids[]">
								<span></span>
							</label>
						</td>
						
						</tr>
					<?php }?>					
					</tbody>
				</table>
			</div>
			<input type="hidden" name="class_from_id" id="form_student_class_from_id"> 
			<input type="hidden" name="class_to_id" id="form_student_class_to_id" value="<?php echo $class_to_id; ?>" />
			<div class="form-actions">
				
				<?php if($class_from_id > 0){ ?>
				<a data-backdrop="static" data-toggle="modal" onclick="return moveClass_Click();"
					data-target="#remoteModal12"
					class="btn btn-default btn-success btn-stop-studying12"> <i
					class="fa-fw fa fa-codepen" data-class=""></i>
				<?php echo __('Pause')?></a>
				<?php }?>
				
				<a data-backdrop="static" data-toggle="modal"  onclick="return moveClass_Click();"
					data-target="#remoteModal123"
					class="btn btn-default btn-success btn-move-class12"> <i class="fa-fw fa fa-random" data-class=""></i>
					<?php echo __('Move class')?>
				</a>

			</div>

		<!-- Modal chuyển lớp -->
		
		<div class="modal fade" id="remoteModal123" role="dialog">
			<div class="modal-dialog modal-lg">

				
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel"><?php echo __('Students class move') ?></h4>
					</div>
					<div class="modal-body">
					<div class="sf_admin_form widget-body">
			<?php if ($form->hasGlobalErrors()): ?>
		    <?php echo $form->renderGlobalErrors()?>
		    <?php endif; ?>
			<fieldset>
	    	 	<?php echo $form->renderHiddenFields(true) ?>
	    	 	
	    	 	<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('School year', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['ps_school_year_id']->render() ?>
                             <?php echo $form['ps_school_year_id']->renderError() ?>
                        </div>
					</div>
				</div>
				
				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('To class', array(), 'messages') ?>
                        <span class="required"> *</span>
						</label>
						<div class="col-md-8">
                             <?php echo $form['myclass_id']->render() ?>
                             <?php echo $form['myclass_id']->renderError() ?>
                        </div>
					</div>
				</div>

				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Statistic class', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['statistic_class_id']->render() ?>
                             <?php echo $form['statistic_class_id']->renderError() ?>
                        </div>
					</div>
				</div>
				
				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Start at', array(), 'messages') ?>
                        <span class="required"> *</span>
						</label>
						<div class="col-md-8">
                             <?php echo $form['start_at']->render() ?>
                             <?php echo $form['start_at']->renderError() ?>
                        </div>
					</div>
				</div>
				
				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Stop at', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['stop_at']->render() ?>
                             <?php echo $form['stop_at']->renderError() ?>
                        </div>
					</div>
				</div>

				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Status studying', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['type']->render() ?>
                             <?php echo $form['type']->renderError() ?>
                        </div>
					</div>
				</div>
				
				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Saturday school', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['myclass_mode']->render() ?>
                             <?php echo $form['myclass_mode']->renderError() ?>
                        </div>
					</div>
				</div>
				<!-- <div style="clear: both;"></div> -->
				<div class='col-md-6'>
					<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
						<label class="col-md-4 control-label">
                        <?php echo __('Class activated', array(), 'messages') ?>
						</label>
						<div class="col-md-8">
                             <?php echo $form['is_activated']->render() ?>
                             <?php echo $form['is_activated']->renderError() ?>
                        </div>
					</div>
				</div>

			</fieldset>
	</div>
	<div id="sf_admin_footer" class="no-border no-padding"></div>
	
					</div>
					<div class="modal-footer">
						<a class="btn btn-default btn-success" href="javascript:;"
							id="btn-student-move-class"><i class="fa-fw fa fa-floppy-o"></i>&nbsp;<?php echo __('Save')?></a>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<i class="fa-fw fa fa-ban"></i><?php echo __('Close');?></button>
					</div>
				</div>
			</div>
		</div>
 
		
		<!-- Modal dừng học -->
		<div class="modal fade" id="remoteModal12" role="dialog">
			<div class="modal-dialog">

				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel"><?php echo __('Update type') ?></h4>
					</div>
					<div class="modal-body">

<div class="row">

	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
			<div class="widget-body">

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

						<div
							class="form-group sf_admin_form_row sf_admin_foreignkey sf_admin_form_field_start_at ">
							<label class="col-md-12 control-label"
								for="student_class_start_at"><?php echo __('Start at')?><span
								class="required"> *</span></label>
							<div class="col-md-12">
								<div data-dateformat="dd-mm-yyyy" placeholder="dd-mm-yyyy"
									title="<?php echo __('Start at')?>"
									data-original-title="<?php echo __('Start date')?>"
									rel="tooltip" class="icon-addon">
									<input data-dateformat="dd-mm-yyyy" required="required"
										placeholder="dd-mm-yyyy" title="Start at"
										data-original-title="<?php echo __('Start date')?>"
										rel="tooltip" type="text" name="form_student_start_at"
										class="form-control" id="form_student_start_at"> <label
										for="dateselect_filter"
										class="icon-append fa fa-calendar padding-left-5"
										rel="tooltip" title="" data-original-title=""></label>
								</div>
							</div>
						</div>

					</div>

					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

						<div
							class="form-group sf_admin_form_row sf_admin_date sf_admin_form_field_stop_at ">
							<label class="col-md-12 control-label"
								for="student_class_stop_at"><?php echo __('Stop at')?></label>
							<div class="col-md-12">
								<div data-dateformat="dd-mm-yyyy" placeholder="dd-mm-yyyy"
									title="<?php echo __('Stop at')?>"
									data-original-title="<?php echo __('Stop date')?>"
									rel="tooltip" class="icon-addon">
									<input data-dateformat="dd-mm-yyyy"
										placeholder="dd-mm-yyyy" title="Stop at"
										data-original-title="<?php echo __('Stop date')?>"
										rel="tooltip" type="text" name="form_student_stop_at"
										class="form-control" id="form_student_stop_at"> <label
										for="dateselect_filter"
										class="icon-append fa fa-calendar padding-left-5"
										rel="tooltip" title="" data-original-title=""></label>
								</div>
							</div>
						</div>

					</div>

				</div>
				<br>
				<div class="row">
				
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="form-group sf_admin_form_row sf_admin_foreignkey sf_admin_form_field_type ">
						<label class="col-md-5 control-label"
							for="student_class_is_activated"><?php echo __('Class activated')?></label>

						<div class="col-md-7">
							<label class="radio radio-inline"
								for="form_student_myclass_mode_1" style="margin-top: 0">
								<input class="radiobox" name="form_student_myclass_mode"
								type="radio" value="<?php echo PreSchool::ACTIVE ;?>"
								id="form_student_myclass_mode_1"><span><?php echo __("Yes")?></span>
							</label> 
							<label class="radio radio-inline"
								for="form_student_myclass_mode_0"> <input
								class="radiobox" name="form_student_myclass_mode"
								type="radio" value="<?php echo PreSchool::NOT_ACTIVE ;?>"
								id="form_student_myclass_mode_0" checked="checked"><span><?php echo __("No")?></span>
							</label>
						</div>

					</div>
				</div>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="modal-footer">
						<a class="btn btn-default btn-success" href="javascript:;"
							id="btn-stop-studying"><i class="fa-fw fa fa-floppy-o"></i>&nbsp;<?php echo __('Save')?></a>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<i class="fa-fw fa fa-ban"></i><?php echo __('Close');?></button>
					</div>
				</div>
			</div>
		</div>
		
		</form>
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// BEGIN: filters
	$('#student_filter_ps_customer_id').change(function() {
		resetOptions('student_filter_ps_workplace_id');
		$('#student_filter_ps_workplace_id').select2('val','');

		resetOptions('student_filter2_ps_workplace_id');
		$('#student_filter2_ps_workplace_id').select2('val','');
		
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');

		$('student_filter_ps_workplace_id, #student_filter2_ps_workplace_id').change();
		
		if ($(this).val() > 0 ) {

			$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").attr('disabled', 'disabled');
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

		    	$('#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id').select2('val','');

				$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").html(msg);

				$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").attr('disabled', null);

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val() + '&not_class=' + $('#not_in_class').val(),
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
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val() + '&not_class=' + $('#not_in_class').val(),
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

	$('#student_filter_school_year_id').change(function() {
		
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val() + '&not_class=' + $('#not_in_class').val(),
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

	// END: filters

	$('#move_class_ps_school_year_id').change(function() {
		resetOptions('move_class_myclass_id');
		$('#move_class_myclass_id').select2('val','');
		
		if (($('#move_class_ps_customer_id').val() <= 0) && ($('#move_class_ps_school_year_id').val() <= 0)) {
			return;
		}

		$("#move_class_myclass_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_group_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#move_class_ps_customer_id').val() + '&y_id=' + $('#move_class_ps_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#move_class_myclass_id').select2('val','');
			$("#move_class_myclass_id").html(msg);
			$("#move_class_myclass_id").attr('disabled', null);
	    });
	});
	
});
</script>


