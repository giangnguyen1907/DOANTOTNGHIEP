<?php use_helper('I18N', 'Date')?>
<?php include_partial('psLogtimes/assets')?>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}
</style>
<?php
$array_logtime = array ();
$array_goschool = array ();
foreach ( $filter_list_logtime as $list_logtimes ) {
	array_push ( $array_logtime, $list_logtimes->getStudentId () . date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
	if ($list_logtimes->getLogValue () == 1) {
		array_push ( $array_goschool, date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
	}
}
?>

<section id="widget-grid">
	<div class="row">
		<article
			class="col-xs-12 col-sm-12 col-md-12 col-lg-12 custom-scroll table-responsive">
		<?php include_partial('psLogtimes/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic logtimes', array(), 'messages').', '.__('Day total', array(), 'messages').$year_month.' : '.$number_day['saturday_day'].' '.__('Day', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psLogtimes/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>
            			
            			<?php if (!$sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_EDIT'))){ ?>
            			
<!-- Không có quyền sửa điểm danh trên màn hình thống kê -->

						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive"
								style="overflow-x: scroll; width: 100%;">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
			<?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
			<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
			<thead>
										<tr>
											<th class="text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                    <?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                    <th
												class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                    <?php } ?>
                    <th class="text-center"><?php echo __('Not Permission', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Permission', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Go school', array(), 'messages') ?></th>
										</tr>
									</thead>

									<tbody>
				
				<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
				
				<tr>
											<td class="text-center"><?php echo $ky+1 ?></td>
											<td><?php echo $list_student->getFullName() ?></td>
                      <?php for ($i =1 ;$i <= $number_day['number_day_month']; $i++ ){ ?>
                      <?php if(date("Ymd", strtotime($i.'-'.$year_month)) <= date("Ymd")){?>
                      <?php

$id_logtime1 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '1';
																			$id_logtime2 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '2';
																			$id_logtime0 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '0';
																			?>
                      <td
												class="text-center <?php if(in_array($i, $sunday)){ echo 'bg-color-red';} if(in_array($i, $saturday)){ echo 'bg-color-orange'; }?>">
                          
                              <?php

$log_value = '';
																			if (in_array ( $id_logtime1, $array_logtime )) {
																				$log_value = 'x';
																				$c ++;
																			} elseif (in_array ( $id_logtime2, $array_logtime )) {
																				$log_value = 'K';
																				$a ++;
																			} elseif (in_array ( $id_logtime0, $array_logtime )) {
																				$log_value = 'P';
																				$b ++;
																			}
																			echo $log_value;
																			?>
                           
                      </td>
                      <?php  } else{ ?>
                      <td style='background: #eee'></td>
                      <?php } }?>
                      
                      <td class="text-center">
                      	<?php echo $a; $a='';?>
                      </td>
											<td class="text-center">
                      	<?php echo $b; $b='';?>
                      </td>
											<td class="text-center">
                      	<?php echo $c; $c='';?>
                      </td>
										</tr>
                <?php endforeach; ?>
                
                <tr>
											<td class="text-center"></td>
											<td class="text-center"><?php echo __('Total')?></td>
                	<?php

for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
																	?>
                    <td
												class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                    <?php
																	foreach ( $array_goschool as $list ) {
																		if ($list == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
																			// echo $list;
																			$e ++;
																			$d ++;
																		}
																	}
																	echo $e;
																	$e = '';
																	?>
                    </td>
                    <?php } ?>
                    <td class="text-center"></td>
											<td class="text-center"></td>
											<td class="text-center"></td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>

            			<?php }else{?>
<!-- Có quyền sửa điểm danh trên màn hình thống kê -->
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive"
								style="overflow-x: scroll; width: 100%;">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
			<?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
			<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
			<thead>
										<tr>
											<th class="text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                    <?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                    <th
												class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                    <?php } ?>
                    <th class="text-center"><?php echo __('Not Permission', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Permission', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Go school', array(), 'messages') ?></th>
										</tr>
									</thead>

									<tbody>
				
				<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
				
				<tr>
											<td class="text-center"><?php echo $ky+1 ?></td>
											<td><?php echo $list_student->getFullName() ?></td>
                      <?php for ($i =1 ;$i <= $number_day['number_day_month']; $i++ ){ ?>
                      <?php if(date("Ymd", strtotime($i.'-'.$year_month)) <= date("Ymd")){?>
                      <?php

$id_logtime1 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '1';
																			$id_logtime2 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '2';
																			$id_logtime0 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '0';
																			?>
                      <td
												class="text-center <?php if(in_array($i, $sunday)){ echo 'bg-color-red';} if(in_array($i, $saturday)){ echo 'bg-color-orange'; }?>">

												<a data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"
												href="<?php echo url_for('@ps_attendances_student_in_statistic?sid='.$list_student->getStudentId().'&date='.date("Ymd", strtotime($i.'-'.$year_month)))?>"
												data-value="<?php echo date("Ymd", strtotime($i.'-'.$year_month)).$list_student->getStudentId()?>"
												class="btn btn-default btn-attendance"
												style="padding: 1px 6px;">
                          
                              <?php

$log_value = '';
																			if (in_array ( $id_logtime1, $array_logtime )) {
																				$log_value = 'x';
																				$c ++;
																			} elseif (in_array ( $id_logtime2, $array_logtime )) {
																				$log_value = 'K';
																				$a ++;
																			} elseif (in_array ( $id_logtime0, $array_logtime )) {
																				$log_value = 'P';
																				$b ++;
																			}
																			echo $log_value;
																			?>
                              
                          </a>

											</td>
                      <?php  } else{ ?>
                      <td style='background: #eee'></td>
                      <?php } }?>
                      
                      <td class="text-center">
                      	<?php echo $a; $a='';?>
                      </td>
											<td class="text-center">
                      	<?php echo $b; $b='';?>
                      </td>
											<td class="text-center">
                      	<?php echo $c; $c='';?>
                      </td>
										</tr>
                <?php endforeach; ?>
                
                <tr>
											<td class="text-center"></td>
											<td class="text-center"><?php echo __('Total')?></td>
                	<?php

for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
																	?>
                    <td
												class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                    <?php
																	foreach ( $array_goschool as $list ) {
																		if ($list == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
																			// echo $list;
																			$e ++;
																			$d ++;
																		}
																	}
																	echo $e;
																	$e = '';
																	?>
                    </td>
                    <?php } ?>
                    <td class="text-center"></td>
											<td class="text-center"></td>
											<td class="text-center"></td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>
            			<?php }?>
            		</div>
				</div>
			</div>

			<div class="modal fade" id="remoteModal12" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"
								aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel"><?php echo __('Update attendance') ?></h4>
						</div>
						<div class="modal-body">

							<div class="row">

								<div class="tab-content">
									<div id="home" class="tab-pane fade in active">
										<div class="widget-body">

											<div class="row">

												<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
													<div class="input-group" style="width: 100%">
														<span class="input-group-addon"><i
															class="icon-append fa fa-clock-o"></i></span> <input
															id="ps_attendance_student" name="student_logtime"
															class="time_picker form-control" value="">
													</div>
												</div>

											</div>
											<br>
											<div class="row">

												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div
														class="form-group sf_admin_form_row sf_admin_foreignkey sf_admin_form_field_type ">
														<label class="col-md-3 control-label"
															for="student_class_is_activated"><?php echo __('Status')?></label>

														<div class="col-md-9">
															<label class="radio radio-inline"
																for="form_student_myclass_mode_1" style="margin-top: 0">
																<input class="radiobox" name="form_student_myclass_mode"
																type="radio" value="1" id="form_student_myclass_mode_1"><span><?php echo __('Go school')?></span>
															</label> <label class="radio radio-inline"
																for="form_student_myclass_mode_0"> <input
																class="radiobox" name="form_student_myclass_mode"
																type="radio" value="0" id="form_student_myclass_mode_0"
																checked="checked"><span><?php echo __('Permission')?></span>
															</label> <label class="radio radio-inline"
																for="form_student_myclass_mode_2"> <input
																class="radiobox" name="form_student_myclass_mode"
																type="radio" value="2" id="form_student_myclass_mode_2"><span><?php echo __('Not Permission')?></span>
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
							<button type="button" class="btn btn-default"
								data-dismiss="modal">
								<i class="fa-fw fa fa-ban"></i><?php echo __('Close');?></button>
						</div>
					</div>
				</div>
			</div>

		</article>
		<?php if($class_id > 0){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default"
				href="<?php echo url_for(@ps_logtimes).'/'.$class_id.'/'.$year_month.'/'; ?>export_logtimes"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
        <?php } ?>
        
	</div>
</section>


<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id		= '<?php echo __('Please enter class filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';

	$('#ps-filter').formValidation({
    	framework : 'bootstrap',
    	addOns : {
			i18n : {}
		},
		err : {
			container: '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
    	fields : {
			"logtimes_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "logtimes_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "logtimes_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "logtimes_filter[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    },
                }
            },

		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps-filter').formValidation('setLocale', PS_CULTURE);

});
</script>