<?php use_helper('I18N', 'Date')?>
<?php include_partial('psServiceCourses/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic student registered service course haven\'t yet in class') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
			    <?php include_partial('psServiceCourses/student_service_filter', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
			    
			  </div>
						</div>
						<form id="frm_batch" class="form-horizontal"
							action="@ps_service_courses_statistic" method="post">
							<input class="service_id hidden"
								value="<?php echo $ps_service_id;?>" /> <input
								class="ps_customer_id hidden"
								value="<?php echo $ps_customer_id;?>" /> <input
								class="school_year_id hidden"
								value="<?php echo $school_year_id;?>" /> <input
								class="ps_workplace_id hidden"
								value="<?php echo $ps_workplace_id;?>" /> <input
								class="keywords hidden" value="<?php echo $keywords;?>" />

							<div id="datatable_fixed_column_wrapper"
								class="dataTables_wrapper form-inline no-footer no-padding">
								<div class="custom-scroll table-responsive">
									<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('Image') ?></th>
												<th class="text-center"><?php echo __('Student code') ?></th>
												<th class="text-center"><?php echo __('Full name') ?></th>
												<th class="text-center"><?php echo __('Birthday') ?></th>
												<th class="text-center"><?php echo __('Sex') ?></th>
												<th class="text-center"><?php echo __('Check boxs') ?></th>
											</tr>
										</thead>
						<?php $count = count($filter_list_student)?>
						<tbody class="tbody">
							<?php foreach ($filter_list_student as $list_student ): ?>
							
							<tr>
												<td>			
                    			<?php
								if ($list_student->getImage () != '') {
									$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $school_code . '/' . $list_student->getYearData () . '/' . $list_student->getImage ();
									echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
								}
								?>
                    			</td>
												<td><?php echo $list_student->getStudentCode(); ?></td>
												<td><?php echo $list_student->getFullName(); ?></td>
												<td class="text-center">
													<div class="date"><?php echo (false !== strtotime($list_student->getBirthday())) ? format_date($list_student->getBirthday(),"dd-MM-yyyy").'<div><code>'.PreSchool::getAge($list_student->getBirthday(),false).'</code>' : '';?>
                				
												
												</td>
												<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $list_student->getSex())) ?></td>
												<td class="text-center"><input type="checkbox" class="chk"
													value="<?php echo $list_student->getStudentId(); ?>"></td>

											</tr>
                            <?php endforeach; ?>
						</tbody>
									</table>
								</div>


							</div>

						</form>
					</div>
				</div>
			</div>

		</article>

	</div>

	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="widget-body-toolbar">
			<div class="pull-right">
				<a data-target="#remoteModal" data-backdrop="static"
					class="btn btn-default btn-success btn-sm btn-psadmin btn-move-class"
					href=" javascript:; "><i class="fa-fw fa fa-floppy-o"
					aria-hidden="true" title="<?php echo __('Move class')?>"></i><?php echo ' ' . __('Move class')?></a>
			</div>
		</div>
	</article>
	</div>
</section>
<script type="text/javascript">

$(document).ready(function() {

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_service_id	= '<?php echo __('Please select service filter the data.')?>';
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	
	$('.btn-move-class').click(function () {
		getValueStudentId();
		
	});

	function getValueStudentId(){

		var chkArray = [];
		var service_id = $('.service_id').val();

		$(".chk:checked").each(function() {
			chkArray.push($(this).val());
		});

		var selected;
		selected = chkArray.join(',') ;
		
		if(selected.length <= 0){
			alert('"<?php echo __('Please choose any one student')?>"');			
			return;
		} else {
			$('.btn-move-class').attr('data-toggle', "modal");
			$('.btn-move-class').attr('href', '<?php echo url_for(@ps_service_courses) ?>/statistic/' + selected + '/' + service_id + '/save');
// 			alert($('.btn-move-class').attr('href'));return;
		}
	}

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
			"student_service_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "student_service_filter[school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "student_service_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "student_service_filter[ps_service_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_service_id,
                        		  en_US: msg_select_ps_service_id
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