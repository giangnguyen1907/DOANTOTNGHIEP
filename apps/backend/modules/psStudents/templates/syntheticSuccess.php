<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudents/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}

.infomation_student h4 {
	margin: 10px 0px;
	font-weight: 500;
}
</style>

<script type="text/javascript">

$(document).ready(function() {

});

</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psStudents/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-calendar-check-o"></i></span>
					<h2><?php echo __('Students synthetic', array(), 'messages') ?>
					<span><strong><?php echo $student->getFirstName().' '.$student->getLastName()?></strong>(<code><?php echo $student->getStudentCode()?></code>)</span>
						<span>
				       		<?php
													$student_sex = $student->getSex ();
													if (false !== strtotime ( $student->getBirthday () ))
														echo '(' . format_date ( $student->getBirthday (), "dd-MM-yyyy" ) . ')&nbsp;<code>' . PreSchool::getAge ( $student->getBirthday (), false ) . '</code>';
													else
														echo '&nbsp;';
													?>		
							- <strong>
							<?php if ($class_name):?>
								<?php echo $class_name;?>
							<?php endif?>
							</strong>
						</span>
					</h2>
					<?php $student_1 = $student;?>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5"></div>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="div_form_filter form-inline">
    	<?php echo $form_filter->renderFormTag('synthetic') ?>
    		<div class="form-group ">
    			<?php echo $form_filter['month']->renderError()?>
    			<?php echo $form_filter['month']->render()?>
    		</div>
									<div class="form-group ">
    			<?php echo $form_filter['year']->renderError()?>
    			<?php echo $form_filter['year']->render()?>
    		</div>
									<button type="submit" rel="tooltip" data-placement="bottom"
										data-original-title="<?php echo __('Select')?>"
										class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
										<i class="fa-fw fa fa-search"></i>
									</button>
									</form>
								</div>
							</div>


						</div>
						<div style="clear: both"></div>

						<div class="row" style="padding: 10px">

							<div class="col-md-2 col-lg-2">

								<div id="datatable_fixed_column_wrapper" class="form-inline">
									<div class="custom-scroll table-responsive"
										style="width: 100%; max-height: 550px; overflow-y: scroll;">
										<table id="dt_basic"
											class="table table-striped table-bordered" width="100%">

											<thead>
												<tr>
													<th><?php echo __('Student')?></th>
													<th style="width: 50px"></th>
												</tr>
											</thead>

											<tbody>
        				
        				<?php foreach ($list_student as $student){?>
        				<tr
													class="student_click <?php if($student_id == $student->getId()){ echo "highlight";}?>"
													id="student_click_<?php echo $student->getId();?>">
													<td><?php echo $student->getStudentName();?></td>
													<td><a class="btn btn-xs btn-default"
														href="<?php echo url_for('@ps_students_synthetic?sid='.$student->getId().'&date='.strtotime('01-'.$ps_month))?>"><i
															class="fa-fw fa fa-eye txt-color-blue"></i></a></td>
												</tr>
                        <?php }?>
        			</tbody>
										</table>
									</div>
								</div>

							</div>

							<div id="ic-loading" style="display: none;">
								<i class="fa fa-spinner fa-2x fa-spin text-success"
									style="padding: 3px;"></i><?php echo __('Loading...')?>
  	</div>

							<div id="load-ajax-1909" class="col-md-10 col-lg-10">
    
    	<?php echo get_partial('psStudents/tab_infomation', array('defaultLogout' => $defaultLogout,'ps_customer_id' => $ps_customer_id,'ps_month' => $ps_month, 'student'=>$student_1,'year'=>$year,'month'=>$month, 'tabmenu' => $tabmenu));?>
    	
    </div>

						</div>

					</div>


				</div>
			</div>

		</article>

	</div>
</section>


<script>

$(document).on("ready", function(){

	$('#student_synthetic_ps_customer_id').change(function() {

		resetOptions('student_synthetic_ps_workplace_id');
		$('#student_synthetic_ps_workplace_id').select2('val','');
		$("#student_synthetic_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('student_synthetic_class_id');
		$('#student_synthetic_class_id').select2('val','');
		$("#student_synthetic_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#student_synthetic_ps_workplace_id").attr('disabled', 'disabled');
		$("#student_synthetic_class_id").attr('disabled', 'disabled');
		
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

	    	$('#student_synthetic_ps_workplace_id').select2('val','');

			$("#student_synthetic_ps_workplace_id").html(msg);

			$("#student_synthetic_ps_workplace_id").attr('disabled', null);

			$("#student_synthetic_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#student_synthetic_ps_workplace_id').change(function() {
	
	$("#student_synthetic_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#student_synthetic_ps_customer_id').val() + '&w_id=' + $('#student_synthetic_ps_workplace_id').val() + '&y_id=' + $('#student_synthetic_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#student_synthetic_class_id').select2('val','');
		$("#student_synthetic_class_id").html(msg);
		$("#student_synthetic_class_id").attr('disabled', null);
    });
});

$('#student_synthetic_ps_school_year_id').change(function() {
	
	resetOptions('student_synthetic_class_id');
	$('#student_synthetic_class_id').select2('val','');

	resetOptions('student_synthetic_ps_month');
	$('#student_synthetic_ps_month').select2('val','');
	
	if ($('#student_synthetic_ps_customer_id').val() <= 0) {
		return;
	}

	$("#student_synthetic_class_id").attr('disabled', 'disabled');
	$("#student_synthetic_ps_month").attr('disabled', 'disabled');
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#student_synthetic_ps_customer_id').val() + '&w_id=' + $('#student_synthetic_ps_workplace_id').val() + '&y_id=' + $('#student_synthetic_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#student_synthetic_class_id').select2('val','');
		$("#student_synthetic_class_id").html(msg);
		$("#student_synthetic_class_id").attr('disabled', null);
    });
    
	$.ajax({
		url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
        type: "POST",
        data: {'ym_id': $(this).val()},
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
	    }).done(function(msg) {
	    	$('#student_synthetic_ps_month').select2('val','');
			$("#student_synthetic_ps_month").html(msg);
			$("#student_synthetic_ps_month").attr('disabled', null);
	    });

    
});
	
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id		= '<?php echo __('Please enter class filter the data.')?>';
	var msg_select_ps_month  		= '<?php echo __('Please enter month filter the data.')?>';
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
			"student_synthetic[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "student_synthetic[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "student_synthetic[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "student_synthetic[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    },
                }
            },

            "student_synthetic[ps_month]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_month,
                        		  en_US: msg_select_ps_month
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