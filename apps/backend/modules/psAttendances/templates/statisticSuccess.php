<?php use_helper('I18N', 'Date')?>
<?php include_partial('psLogtimes/assets')?>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
td, th {border: 1px solid #ccc;padding: 8px;}
@media (min-width: 768px){
table {table-layout: fixed;width: 100%;}
td, th {border: 1px solid #ccc;padding: 8px;width: 36px;height: 53px;}
.sunday {background: #999 !important;}
.saturday {background: #ccc !important;}
.hard_left {position: absolute;left: -1px;width: 46px;border-bottom: none;}
.next_left {position: absolute;left: 44px;width: 184px;border-bottom: none;}
.border_solid { padding: 5px 0px;}
.last-child {border-bottom: 1px solid #ccc !important;height: 59px;padding: 20px 0px;}
.inner {overflow-x: scroll;overflow-y: visible;margin-left: 214px;margin-right: 202px;}
.goschool1 {width: 55px;position: absolute;right: 161px;border-bottom: none;}
.goschool2 {width: 55px;position: absolute;right: 107px;border-bottom: none;}
.goschool3 {width: 55px;position: absolute;right: 53px;border-bottom: none;}
.goschool4 {width: 55px;position: absolute;right: -1px;border-bottom: none;}
}
</style>
<?php 
/*
$txt_json_service = '{"593":"60","596":"100","599":"100","600":"100","602":"100"}';
$arr_service =  json_decode($txt_json_service) ;
print_r($arr_service);

$birthday = '2024-04-19';
$thangtuoi = PreSchool::getMonthYear($birthday, date('Y-m-d')); 
echo $thangtuoi;

$diemDanh = Doctrine::getTable('PsLogtimes')->layCacTrangThaiDiemDanh(9,'2023-06-01');
echo '<pre>';
print_r($diemDanh);
echo '</pre>';*/
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.btn-filter-search').click(function() {
		$('#ps-filter').attr('action', '<?php echo url_for('ps_attendances_collection', array('action' => 'statistic')) ?>');
	});
	// xuat so diem danh theo lop
	$('#btn-export-statistic').click(function() {
		if ($('#logtimes_filter_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}
		$('#export_ps_school_year_id').val($('#logtimes_filter_ps_school_year_id').val());
		$('#export_ps_customer_id').val($('#logtimes_filter_ps_customer_id').val());
		$('#export_ps_workplace_id').val($('#logtimes_filter_ps_workplace_id').val());
		$('#export_class_id').val($('#logtimes_filter_class_id').val());
		$('#export_year_month').val($('#logtimes_filter_year_month').val());
		$('#export_by_workplace').val(0);
		$('#frm_export_01').submit();
		return true;
    });
    
	// xuat so diem danh theo co so
	$('#btn-export-statistic-all').click(function() {
		if ($('#logtimes_filter_ps_workplace_id').val() <= 0) {
			alert('<?php echo __('Select workplace')?>');
			return false;
		}
		$('#export_ps_school_year_id').val($('#logtimes_filter_ps_school_year_id').val());
		$('#export_ps_customer_id').val($('#logtimes_filter_ps_customer_id').val());
		$('#export_ps_workplace_id').val($('#logtimes_filter_ps_workplace_id').val());
		$('#export_class_id').val($('#logtimes_filter_class_id').val());
		$('#export_year_month').val($('#logtimes_filter_year_month').val());
		$('#export_by_workplace').val(1);
		
		$('#frm_export_01').submit();
		return true;
    });
});
</script>

<?php 
$a = $b= $c= $d =$e = 0;
$songaykohocthu7 = $number_day ['normal_day'];
$songayhocthu7 = $number_day ['saturday_day'];

$number_student = count($filter_list_student);

$array_logtime = array();
$array_goschool = array();
foreach ($filter_list_logtime as $list_logtimes){
    array_push($array_logtime, $list_logtimes->getStudentId().date("Ymd", strtotime($list_logtimes->getLtLoginAt())).$list_logtimes->getLogValue());
    if($list_logtimes->getLogValue() == 1){
        array_push($array_goschool, date("Ymd", strtotime($list_logtimes->getLtLoginAt())).$list_logtimes->getLogValue() );
    }
}
?>
<section id="widget-grid">
	<div class="row">
		<article
			class="col-xs-12 col-sm-12 col-md-12 col-lg-12 custom-scroll table-responsive">
		<?php include_partial('psAttendances/flashes')?>
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
            			    <?php include_partial('psAttendances/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>
            			<div style="clear: both"></div>
            			<?php if (!$sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_EDIT'))){ ?>
            			
<!-- Không có quyền sửa điểm danh trên màn hình thống kê -->

						<div class="outer col-md-12">
							<div class="inner custom-scroll table-responsive">
								<table>
        <?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
		<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
		<thead>
										<tr>
											<th class="hard_left text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="next_left text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                <?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                <th
												class="border_solid text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                <?php } ?>
                <th class="border_solid text-center goschool1"><?php echo __('Permission', array(), 'messages') ?></th>
											<th class="border_solid text-center goschool2"><?php echo __('Not Permission', array(), 'messages') ?></th>
											<th class="border_solid text-center goschool3"><?php echo __('Go school', array(), 'messages') ?></th>
											<th class="border_solid text-center goschool4"><?php echo __('Ratio', array(), 'messages') ?></th>
										</tr>
									</thead>
									<tbody>
				
				<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
				
				<tr>
											<td class="hard_left text-center"><?php echo $ky+1 ?></td>
											<td class="next_left"><?php echo $list_student->getFullName() ?>
					<br />
											<code><?php echo $list_student->getStudentCode() ?></code></td>
                      <?php for ($i =1 ;$i <= $number_day['number_day_month']; $i++ ){ ?>
                      <?php if(date("Ymd", strtotime($i.'-'.$year_month)) <= date("Ymd")){?>
                      <?php

$id_logtime1 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '1';
$id_logtime2 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '2';
$id_logtime0 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '0';
?>
                      <td class="border_solid text-center <?php if(in_array($i, $sunday)){ echo 'bg-color-red';} if(in_array($i, $saturday)){ echo 'bg-color-orange'; }?>">
                          
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
                      <?php 
                      if($list_student->getMyclassMode() == 1){ // Nếu học sinh này đi học cả t7
                      	$h = ($c/$songayhocthu7)*100;
                      }else{ // Nếu không đi học thứ 7
                      	$h = ($c/$songaykohocthu7)*100;
                      }
                      ?>
                      <td class="text-center goschool1">
                      	<?php echo $b; $b='';?>
                      </td>
					<td class="text-center goschool2">
                      	<?php echo $a; $a='';?>
                      </td>
					<td class="text-center goschool3">
                      	<?php echo $c; $c='';?>
                      </td>
                      <td class="text-center goschool4" >
                      <?php echo PreNumber::number_format ($h,1);$h = '';?>
                      </td>

										</tr>
                <?php endforeach; ?>
                <tr>
					<td class="hard_left text-center last-child"><?php echo $ky+2 ?></td>
					<td class="next_left text-center last-child"><?php echo __('Total')?></td>
                	<?php

for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
																	?>
                    <td class="border_solid text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
<?php
foreach ( $array_goschool as $list ) {
	if ($list == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
		// echo $list;
		$e ++;
		$d ++;
	}
}
echo $e.'<hr style="padding : 0; margin: 5px 0px;"/>';
echo PreNumber::number_format (($e/$number_student)*100,1);
$e = 0;
?>
                    </td>
                    <?php } ?>
                    <td class="text-center last-child goschool1"></td>
					<td class="text-center last-child goschool2"></td>
					<td class="text-center last-child goschool3"></td>
					<td class="text-center last-child goschool4"></td>
				</tr>
			</tbody>

								</table>
							</div>
						</div>      

            			<?php }else{?>
            			
         			
<div class="outer col-md-12">
							<div class="inner custom-scroll table-responsive">
								<table>
        <?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
		<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
		<thead>
										<tr>
											<th class="hard_left text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="next_left text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                <?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                <th
												class="border_solid text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                <?php } ?>
                <th class="border_solid text-center goschool1"><?php echo __('Permission', array(), 'messages') ?></th>
				<th class="border_solid text-center goschool2"><?php echo __('Not Permission', array(), 'messages') ?></th>
				<th class="border_solid text-center goschool3"><?php echo __('Go school', array(), 'messages') ?></th>
				<th class="border_solid text-center goschool4"><?php echo __('Ratio', array(), 'messages') ?></th>
			</tr>
		</thead>
		<tbody>
				
				<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
				
				<tr>
											<td class="hard_left text-center"><?php echo $ky+1 ?></td>
											<td class="next_left"><?php echo $list_student->getFullName() ?>
					<br />
						<code><?php echo $list_student->getStudentCode() ?></code></td>
                        <?php for ($i =1 ;$i <= $number_day['number_day_month']; $i++ ){ ?>
                        <?php if(date("Ymd", strtotime($i.'-'.$year_month)) <= date("Ymd")){?>
                      	<?php

							$id_logtime1 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '1';
							$id_logtime2 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '2';
							$id_logtime0 = $list_student->getStudentId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ) . '0';
						?>
                      <td class="text-center <?php if(in_array($i, $sunday)){ echo 'bg-color-red';} if(in_array($i, $saturday)){ echo 'bg-color-orange'; }?>">
							<a data-backdrop="static" data-toggle="modal"
							data-target="#remoteModal"
							href="<?php echo url_for('@ps_attendances_student_in_statistic?sid='.$list_student->getStudentId().'&date='.date("Ymd", strtotime($i.'-'.$year_month)))?>"
							data-value="<?php echo date("Ymd", strtotime($i.'-'.$year_month)).$list_student->getStudentId()?>"
							class="btn btn-default btn-attendance" style="padding: 1px 6px;">
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
                      <?php 
                      if($list_student->getMyclassMode() == 1){ // Nếu học sinh này đi học cả t7
                      	$h = ($c/$songayhocthu7)*100;
                      }else{ // Nếu không đi học thứ 7
                      	$h = ($c/$songaykohocthu7)*100;
                      }
                      ?>
                      <td class="text-center goschool1">
                      	<?php echo $b; $b='';?>
                      </td>
											<td class="text-center goschool2">
                      	<?php echo $a; $a='';?>
                      </td>
											<td class="text-center goschool3">
                      	<?php echo $c; $c='';?>
                      </td>
                      <td class="text-center goschool4"><?php echo PreNumber::number_format ($h,1); $h='';?></td>

										</tr>
                <?php endforeach; ?>
                
                <tr>
				<td class="hard_left text-center last-child"></td>
				<td class="next_left text-center last-child"><?php echo __('Total')?></td>
                	<?php for($k = 1; $k <= $number_day ['number_day_month']; $k ++) { ?>
                    <td class="border_solid text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                    <?php
						foreach ( $array_goschool as $list ) {
							if ($list == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
								// echo $list;
								$e ++;
								$d ++;
							}
						}
						if($number_student > 0){
						echo $e.'<hr style="padding : 0; margin: 5px 0px;"/>';
						echo PreNumber::number_format (($e/$number_student)*100,1);
						$e = 0;
						}
						?>
                    </td>
                    <?php } ?>
                    
                    <td class="text-center last-child goschool1"></td>
					<td class="text-center last-child goschool2"></td>
					<td class="text-center last-child goschool3"></td>
					<td class="text-center last-child goschool4"></td>
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
		<?php if($class_id > 0) { ?>
		<form id="frm_export_01" action="<?php echo url_for('@ps_attendances_statistic_export') ?>">
			
			<input type="hidden" name="export_ps_school_year_id" id="export_ps_school_year_id">
			<input type="hidden" name="export_ps_customer_id" id="export_ps_customer_id">
			<input type="hidden" name="export_ps_workplace_id" id="export_ps_workplace_id">
			<input type="hidden" name="export_class_id" id="export_class_id">
			<input type="hidden" name="export_year_month" id="export_year_month">
			<input type="hidden" name="export_by_workplace" id="export_by_workplace">
					
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
	        	<a class="btn btn-default btn-export-statistic-all" href="javascript:void(0);" id="btn-export-statistic-all"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export by workplace')?></a>
	        	<a class="btn btn-default btn-export-statistic" href="javascript:void(0);" id="btn-export-statistic"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export by class')?></a>
	        </article>
        </form>
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

            "logtimes_filter[year_month]": {
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

