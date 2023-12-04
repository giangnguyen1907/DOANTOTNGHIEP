<?php use_helper('I18N', 'Date')?>
<?php //include_partial('psAttendances/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<?php include_partial('global/include/_box_modal_errors');?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal');?>
<script type="text/javascript">
$(document).ready(function() {

	function checkStudent() {		
		var boxes = document.getElementsByTagName('input');
		for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'checkbox _check_all') {						
				if (box.checked == true)
		  		 return true;	
		  	}
		}

		return false;		   
	}
	$('.btnAttendancesDelay').click(function(){

		if (!checkStudent()) {			
			$("#errors").html("<?php echo __('You do not select students to perform')?>");
		    $('#messageModal').modal({show: true,backdrop:'static'});
		    return false;
		}
		
    	$('#ps_attendances_delay').submit();

		return true;		
	});
	
	$('.btn-delay-logtime').click(function() {
		
		var student_id = $(this).attr('data-value');
		
		var note = $('#note_' + student_id).val();

		var relative = $('#select_' + student_id).val();

		var logout_at = $('#logout_at_' + student_id).val();

		var lt_id = $('#lt_id_' + student_id).val();

		var date_at = $('#ps_logtimes_delay_date_time').val();
		
		var ps_workplace_id = $('#delay_filter_ps_workplace_id').val();
		
		var config_attendance = $('#ps_logtimes_delay_config_attendance').val();
		
		if(config_attendance == 1){
			if (student_id <= 0 || logout_at == '' || relative <= 0) {
				alert('<?php echo __("Unknow relative")?>');
				return false;
			}
		}

		$('#ic-loading-' + student_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_logtime_save_delay') ?>',
	        type: 'POST',
	        data: 'student_id=' + student_id + '&relative=' + relative + '&logout_at=' + logout_at + '&note=' + note + '&lt_id=' + lt_id + '&date_at=' + date_at + '&ps_workplace_id=' + ps_workplace_id,
	        success: function(data) {
	        	$('#ic-loading-' + student_id).hide();
	        	$('#box-' + student_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	        },
		});
	    
  	});

	$('.check_all').on('change', function() {

	   if($(".check_all:checked").val() == 0){
		   $('._check_all').prop('checked',true);
	   }else{
		   $('._check_all').prop('checked',false);
		}
	});
	
	//BEGIN: filters_delay
	$('#delay_filter_ps_customer_id').change(function() {

		resetOptions('delay_filter_ps_workplace_id');
		$('#delay_filter_ps_workplace_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#delay_filter_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#delay_filter_ps_workplace_id').select2('val','');

				$('#delay_filter_ps_workplace_id').html(msg);

				$('#delay_filter_ps_workplace_id').attr('disabled', null);

		    });
		}		
	});


	$('#delay_filter_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});

});

</script>
<?php $number_student = count($filter_list_student);?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psAttendances/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Delay logtimes', array(), 'messages') ?>
					<?php echo __('Config time logout', array(), 'messages') . $config_date?>
					</h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psAttendances/filters_delay', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>

						<form class="form-horizontal" id="ps_attendances_delay" action="<?php echo url_for('@ps_attendances_delay_save') ?>" method="post">							
							<input type="hidden" name="tracked_at" value="<?php echo $date_at; ?>" id="ps_logtimes_delay_date_time">
							<input type="hidden" name="config_attendance" value="<?php echo $configChooseAttendancesRelative; ?>" id="ps_logtimes_delay_config_attendance">	
							<input type="hidden" name="ps_school_year_id" value="<?php echo $ps_school_year_id?>" />
							<input type="hidden" name="ps_customer_id" value="<?php echo $ps_customer_id?>" />
							<input type="hidden" name="ps_workplace_id" value="<?php echo $ps_workplace_id?>" />
							<?php if($number_student > 0){?>
							<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
									<div class="text-results">
										<span class="btn-sm btn-psadmin pull-left" style="font-weight: bold">
										<?php echo $number_student . ' '.__('Student')?>
										</span>
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
									<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_ATTENDANCE_ADD',    1 => 'PS_STUDENT_ATTENDANCE_EDIT', 2 => 'PS_STUDENT_ATTENDANCE_TEACHER'),))): ?>
									<button type="buttom" class="btn btn-default btn-success btn-sm btn-psadmin btnAttendancesDelay">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save all')?>"></i> <?php echo __('Save all')?></button>
									<?php endif; ?>
						      	</div>
						      	
							</div>
							<?php }?>
							<div class="clear" style="clear: both;"></div>
							<section class="table_scroll">
							<div class="container_table custom-scroll table-responsive">
							<table id="dt_basic" class="table table-bordered table-striped" width="100%">
								<thead>
									<tr class="header hidden-sm hidden-xs">		
										<th class="text-center"> <?php echo __('STT', array(), 'messages') ?>
										<div><?php echo __('STT', array(), 'messages') ?></div></th>
										<th> <?php echo __('Full name', array(), 'messages') ?>
										<div><?php echo __('Full name', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Class', array(), 'messages') ?>
										<div><?php echo __('Class', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Logout at', array(), 'messages') ?>
										<div><?php echo __('Logout at', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Action', array(), 'messages') ?>
										<div><?php echo __('Action', array(), 'messages') ?></div></th>
										<th class="text-center">
										<div>
										<label><input class="checkbox check_all" type="checkbox" name="attendances_delay" value="0" /><span></span></label>
										</div></th>
									</tr>									
									<tr class="hidden-lg hidden-md">
										<th class="text-center"> <?php echo __('STT', array(), 'messages') ?></th>
										<th> <?php echo __('Full name', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Class', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Logout at', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Action', array(), 'messages') ?></th>
										<th class="text-center">
										<label><input class="checkbox check_all" type="checkbox" name="attendances_delay" value="0" /><span></span></label>
										</th>
									</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="6">
												
											</th>
										</tr>
									</tfoot>
										<tbody>
										<?php if($number_student > 0){?>
            							<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
            							<?php $list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $list_student->getStudentId (), $list_student->getPsCustomerId () ); ?>
            							<tr>
												<td class="text-center"><?php echo $ky+1; ?></td>
												<td><?php echo $list_student->getStudentName() ?></td>
												<td class="text-center"><?php echo $list_student->getClassName() ?></td>

												<td class="sf_admin_text sf_admin_list_td_logout_infomation">
													<input type="hidden" name="student_logtime[<?php echo $list_student->getStudentId(); ?>][class_id]" value="<?php echo $list_student->getClassId() ?>">
													<input type="hidden" name="student_logtime[<?php echo $list_student->getStudentId(); ?>][student_id]" value="<?php echo $list_student->getStudentId() ?>">
													<div
														id="ic-loading-<?php echo $list_student->getStudentId();?>"
														style="display: none;">
														<i class="fa fa-spinner fa-2x fa-spin text-success"
															style="padding: 3px;"></i><?php echo __('Loading...')?>
                                                </div>
													<ul class="list-inline"
														id="box-<?php echo $list_student->getStudentId() ?>">
                                            		<?php echo get_partial('psAttendances/row_li_delay', array('list_relative' => $list_relative, 'list_student' => $list_student,'attendances_relative'=>$configChooseAttendancesRelative))?>
                                            	</ul>
												</td>

												<td class="text-center">
                                           <?php if(date("Hi") > date("Hi", strtotime($config_date))){ $disable = '';}else{ $disable = 'disabled';} ?>
                                           		
                                           		<?php if ($sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_DELAY'))): ?>                                           		
                                                <button type="button" class="btn btn-default btn-sm btn-delay-logtime" data-value="<?php echo $list_student->getStudentId() ?>"><i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save');?>"></i> <?php echo __('Save');?>
                                                </button>
                                                <?php endif;?>                                                                                          	
                                            	<input type="hidden"
													class="filter form-control"
													value="<?php echo $list_student->getId() ?>" name="lt_id"
													id="lt_id_<?php echo $list_student->getStudentId() ?>" />
												</td>
												<td class="text-center">
													<label><input class="checkbox _check_all" type="checkbox" name="student_logtime[<?php echo $list_student->getStudentId(); ?>][checked_delay]" value="<?php echo $list_student->getClassId() ?>" /><span></span></label>
												</td>
										</tr>
                                        <?php endforeach; ?>
                                        <?php }else{?>
                                        <tr>
                                        	<td colspan="6" ><?php echo __('Not student go to school');?></td>
                                        </tr>
                                        <?php }?>
            						</tbody>
									</table>
								</div>
							</section>
							<?php if($number_student > 0){?>
							<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
									<div class="text-results">
										<span class="btn-sm btn-psadmin pull-left" style="font-weight: bold">
										<?php echo $number_student . ' '.__('Student')?>
										</span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
									<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_ATTENDANCE_ADD',    1 => 'PS_STUDENT_ATTENDANCE_EDIT', 2 => 'PS_STUDENT_ATTENDANCE_TEACHER'),))): ?>
									<button type="buttom" class="btn btn-default btn-success btn-sm btn-psadmin btnAttendancesDelay">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save all')?>"></i> <?php echo __('Save all')?></button>
									<?php endif; ?>
						      	</div>
							</div>
							<?php }?>
						</form>
					</div>
				</div>
			</div>

		</article>

	</div>
</section>
<script>

$(document).on("ready", function(){

	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_date_at		= '<?php echo __('Please enter date filter the data.')?>';
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
			"delay_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "delay_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },

            "delay_filter[date_at]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_date_at,
                        		  en_US: msg_select_date_at
                        }
                    }
                }
            },
            
            "delay_filter[ps_workplace_id]": {
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
    	$('#messageModal').modal('show');
    });

    $('#ps-filter').formValidation('setLocale', PS_CULTURE);


	$(document).ready(function() {
		$('.time_picker').timepicker({
			timeFormat : 'HH:mm',
			showMeridian : false,
			defaultTime : null
		});
	});
	
    
});
</script>