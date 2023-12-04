<?php use_helper('I18N', 'Date') ?>
<?php include_partial('global/include/_box_modal_messages');?>
<?php
$today = date ( 'd-m' );
?>
<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psCmsNotification/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Birthday Notification %%date_at%%', array('%%date_at%%' => $year_month), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="dt_basic_filter"
										class="sf_admin_filter dataTables_filter">
										<form id="psnew-filter" class="form-inline pull-left"
											action="<?php echo url_for('ps_cms_notifications_ps_cms_notification_collection', array('action' => 'birthdayNotify')) ?>"
											method="post">
											<div class="pull-left">
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
												<div class="form-group ">
													<label> 
                            				 <?php echo $formFilter['ps_workplace_id']->render()?>
                            				 <?php echo $formFilter['ps_workplace_id']->renderError()?>
                            				 </label>
												</div>
												<div class="form-group">
													<label> 
                            				 <?php echo $formFilter['ps_class_id']->render()?>
                            				 <?php echo $formFilter['ps_class_id']->renderError()?>
                            				 </label>
												</div>

												<div class="form-group ">
													<label> 
                            					<?php echo $formFilter['year_month']->render()?>
                            				    <?php echo $formFilter['year_month']->renderError()?>
                                				 <?php //echo $formFilter['track_at']->render()?>
                                				 <?php //echo $formFilter['track_at']->renderError()?>
                            				 </label>
												</div>
												<div class="form-group ">
													<label> 
                            				 <?php echo $helper->linkToFilterSearchBirthdaynotify() ?>
                            				 </label>
												</div>
												<div class="form-group ">
													<label> 
                            				 <?php echo $helper->linkToFilterResetBirthdaynotify() ?>
                            				 </label>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!-- 					<div class="row"> -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        		<?php $total = count($relatives_list) + count($students_list) + count($teachers_list) ?>
        		<?php if($total <= 0): ?>
        		<?php include_partial('global/include/_no_result') ?>  
        		<?php endif; ?>
        		
        			
        		<?php if(count($students_list) > 0): ?>
        		<div class="margin-bottom-1">&nbsp;</div>
							<div class="well padding-10">
								<header>
									<p>
										<span class="widget-icon"><i class="fa fa-birthday-cake"
											aria-hidden="true"></i></span> <strong><?php echo __('Students list', array(), 'messages') ?></strong>
									</p>
								</header>

								<div class="custom-scroll table-responsive"
									style="max-height: 200px; overflow-y: scroll;">
									<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('Image') ?></th>
												<th class="text-center"><?php echo __('Student code') ?></th>
												<th class="text-center"><?php echo __('First name') ?></th>
												<th class="text-center"><?php echo __('Last name') ?></th>
												<th class="text-center"><?php echo __('Birthday') ?></th>
												<th class="text-center"><?php echo __('Sex') ?></th>
												<th class="text-center"><?php echo __('Status') ?></th>
												<th class="text-center"><?php echo __('Class') ?></th>

											</tr>
										</thead>
										<tbody>
            							<?php foreach ($students_list as $student):?>
            							
            							<tr>
												<td class="sf_admin_text sf_admin_list_td_view_img">
                                              <?php
												if ($student->getImage () != '') {
													$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
													echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
												}
												?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_student_code"><?php echo $student->getStudentCode(); ?></td>
												<td class="sf_admin_text sf_admin_list_td_first_name"><?php echo $student->getFirstName(); ?></td>
												<td class="sf_admin_text sf_admin_list_td_last_name"><?php echo $student->getLastName(); ?></td>
												<td class="text-center">
                                                <?php
												if ($today == format_date ( $student->getBirthday (), "dd-MM" )) {
													$style1 = "font-weight: bold;color: #f00;";
												} else {
													$style1 = '';
												}
												?>
                                                <span style = "<?php echo $style1?>">
                                                	<?php echo  format_date($student->getBirthday(), "dd-MM-yyyy"); ?>
                                                </span>
												</td>
												<td class="sf_admin_boolean sf_admin_list_td_sex">
                                              <?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?>
                                            </td>
                                            
                                            <?php
												if ($student->getIsActivated () == PreSchool::ACTIVE) {
													$status = PreSchool::loadStatusStudentClass ();
													echo "<td class='text-center'>" . __ ( $status [$student->getStudentType ()] ) . "</td>";
												} else {
													$status = PreSchool::loadPsActivity ();
													echo "<td class='text-center' style='color: #FF0000; '>" . __ ( $status [$student->getIsActivated ()] ) . "</td>";
												}
												?>
                                            <td
													class="sf_admin_text sf_admin_list_td_class">
                                              <?php echo $student->getMcName() ?>
                                            </td>
											</tr>
            							<?php endforeach;?>
            						</tbody>
									</table>
								</div>


							</div>
            		<?php endif; ?>
            		
        		<?php if(count($relatives_list) > 0): ?>
        		<div class="margin-bottom-1">&nbsp;</div>
							<div class="well padding-10">
								<header>
									<p>
										<span class="widget-icon"><i class="fa fa-birthday-cake"
											aria-hidden="true"></i></span> <strong><?php echo __('Relatives list', array(), 'messages') ?></strong>
									</p>
								</header>

								<div class="custom-scroll table-responsive"
									style="max-height: 200px; overflow-y: scroll;">
									<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('Image') ?></th>
												<th class="text-center"><?php echo __('First name') ?></th>
												<th class="text-center"><?php echo __('Last name') ?></th>
												<th class="text-center"><?php echo __('Birthday') ?></th>
												<th class="text-center"><?php echo __('Sex') ?></th>
												<th class="text-center"><?php echo __('Mobile') ?></th>
												<th class="text-center"><?php echo __('Email') ?></th>
												<th class="text-center"><?php echo __('Student') ?></th>

											</tr>
										</thead>
										<tbody>
            							<?php foreach ($relatives_list as $relative):?>
            							
            							<tr>
												<td class="sf_admin_text sf_admin_list_td_view_img">
                                              <?php
												if ($relative->getImage () != '') {
													$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $relative->getSchoolCode () . '/' . $relative->getYearData () . '/' . $relative->getImage ();
													echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
												}
												?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_first_name"><?php echo $relative->getFirstName(); ?></td>
												<td class="sf_admin_text sf_admin_list_td_last_name"><?php echo $relative->getLastName(); ?></td>
												<td class="text-center">
                                            	<?php
												if ($today == format_date ( $relative->getBirthday (), "dd-MM" )) {
													$style = "font-weight: bold;color: #f00;";
												} else {
													$style = '';
												}
												?>
                                                <span style = "<?php echo $style?>">
                                                	<?php echo  format_date($relative->getBirthday(), "dd-MM-yyyy"); ?>
                                                </span>
												</td>
												<td class="sf_admin_boolean sf_admin_list_td_sex">
                                              <?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_mobile">
                                              <?php echo $relative->getMobile() ?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_email">
                                              <?php echo $relative->getEmail() ?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_student">
                                              <?php

												foreach ( $students as $key => $student ) {

													if ($relative->getMemberId () == $key) {
														foreach ( $student as $s ) {
															echo $s . '<br>';
														}
													}
												}
												?>
                                            </td>

											</tr>
            							<?php endforeach;?>
            						</tbody>
									</table>
								</div>


							</div>
            		<?php endif; ?>
            		
        		<?php if(count($teachers_list) > 0): ?>
        		<div class="margin-bottom-1">&nbsp;</div>
							<div class="well padding-10">
								<header>
									<p>
										<span class="widget-icon"><i class="fa fa-birthday-cake"
											aria-hidden="true"></i></span> <strong><?php echo __('Member list', array(), 'messages') ?></strong>
									</p>
								</header>

								<div class="custom-scroll table-responsive"
									style="max-height: 200px; overflow-y: scroll;">
									<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('Image') ?></th>
												<th class="text-center"><?php echo __('Member code') ?></th>
												<th class="text-center"><?php echo __('First name') ?></th>
												<th class="text-center"><?php echo __('Last name') ?></th>
												<th class="text-center"><?php echo __('Birthday') ?></th>
												<th class="text-center"><?php echo __('Sex') ?></th>

											</tr>
										</thead>
										<tbody>
            							<?php foreach ($teachers_list as $teacher):?>
            							
            							<tr>
												<td class="sf_admin_text sf_admin_list_td_view_img">
                                              <?php
												if ($teacher->getImage () != '') {
													$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $teacher->getSchoolCode () . '/' . $teacher->getYearData () . '/' . $teacher->getImage ();
													echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
												}
												?>
                                            </td>
												<td class="sf_admin_text sf_admin_list_td_student_code"><?php echo $teacher->getMemberCode(); ?></td>
												<td class="sf_admin_text sf_admin_list_td_first_name"><?php echo $teacher->getFirstName(); ?></td>
												<td class="sf_admin_text sf_admin_list_td_last_name"><?php echo $teacher->getLastName(); ?></td>
												<td class="text-center">
                                            
                                            	<?php
												if ($today == format_date ( $teacher->getBirthday (), "dd-MM" )) {
													$style2 = "font-weight: bold;color: #f00;";
												} else {
													$style2 = '';
												}
												?>
                                                <span style = "<?php echo $style2?>">
                                                	<?php echo  format_date($teacher->getBirthday(), "dd-MM-yyyy"); ?>
                                                </span>

												</td>
												<td class="sf_admin_boolean sf_admin_list_td_sex">
                                              <?php echo get_partial('global/field_custom/_field_sex', array('value' => $teacher->getSex())) ?>
                                            </td>

											</tr>
            							<?php endforeach;?>
            						</tbody>
									</table>
								</div>

							</div>
            		<?php endif; ?> 					
					<div class="margin-bottom-1">&nbsp;</div>
						</div>

					</div>

					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
            	<?php
													// echo $ps_customer_id.$ps_workplace_id;
													if ($ps_class_id == '') {
														$ps_class_id = 0;
													}
													if ($total > 0) {
														?>
            	<a class="btn btn-default"
							href="<?php echo url_for(@ps_student_growths).'/'.$ps_customer_id.'/'.$ps_workplace_id.'/'.$ps_class_id.'/'.$year_month.'/'; ?>export_birthday"
							id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
            	<?php }?>
            	</article>

				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>

<script>
$(document).on("ready", function(){

	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id 		= '<?php echo __('Please select class to filter the data.')?>';
	var msg_select_date 			= '<?php echo __('Please enter date to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	var valid_date 					= '<?php echo __('The value is not a valid date')?>';

	$('#birthday_notify_school_year_id').change(function() {

		resetOptions('birthday_notify_ps_class_id');
		$('#birthday_notify_ps_class_id').select2('val','');

		if ($(this).val() <= 0) {
			return;
		}

		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#birthday_notify_ps_customer_id').val() + '&w_id=' + $('#birthday_notify_ps_workplace_id').val() + '&y_id=' + $('#birthday_notify_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#birthday_notify_ps_class_id').select2('val','');
			$("#birthday_notify_ps_class_id").html(msg);
			$("#birthday_notify_ps_class_id").attr('disabled', null);
	    });

	});

	$('#birthday_notify_ps_customer_id').change(function() {      
    	
		resetOptions('birthday_notify_ps_workplace_id');
		$('#birthday_notify_ps_workplace_id').select2('val','');

		resetOptions('birthday_notify_ps_class_id');
		$('#birthday_notify_ps_class_id').select2('val','');
		
		$("#birthday_notify_ps_workplace_id").attr('disabled', 'disabled');
		

		if ($(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        //async: false,
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#birthday_notify_ps_workplace_id').select2('val','');
			$("#birthday_notify_ps_workplace_id").html(msg);
			$("#birthday_notify_ps_workplace_id").attr('disabled', null);
			$("#birthday_notify_ps_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#birthday_notify_ps_customer_id').val() + '&w_id=' + $('#birthday_notify_ps_workplace_id').val() + '&y_id=' + $('#birthday_notify_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#birthday_notify_ps_class_id').select2('val','');
				$("#birthday_notify_ps_class_id").html(msg);
				$("#birthday_notify_ps_class_id").attr('disabled', null);
		    });
	    });

    });
    
	$('#birthday_notify_ps_workplace_id').change(function() {      

		resetOptions('birthday_notify_ps_class_id');
		$('#birthday_notify_ps_class_id').select2('val','');

		if ($(this).val() <= 0) {
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#birthday_notify_ps_customer_id').val() + '&w_id=' + $('#birthday_notify_ps_workplace_id').val() + '&y_id=' + $('#birthday_notify_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#birthday_notify_ps_class_id').select2('val','');
			$("#birthday_notify_ps_class_id").html(msg);
			$("#birthday_notify_ps_class_id").attr('disabled', null);
	    });
                
    });

	$('#birthday_notify_track_at').datepicker({	
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
	    maxDate: 0,
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy'
	}).on('changeDate', function(e) {
	     $('#psnew-filter').formValidation('revalidateField', $(this).attr('name'));
	});

    $('#psnew-filter').formValidation({
    	framework : 'bootstrap',
    	addOns : {
			i18n : {}
		},
		err : {
			//container : 'tooltip',
			container: '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
    	fields : {
			"birthday_notify[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "birthday_notify[school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "birthday_notify[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            
		}
    }).on('err.form.fv', function(e) {
    	// Show the message modal
    	$('#messageModal').modal('show');
    });
    $('#psnew-filter').formValidation('setLocale', PS_CULTURE);
});
</script>