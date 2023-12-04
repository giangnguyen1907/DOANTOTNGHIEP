<?php use_helper('I18N', 'Date')?>
<?php include_partial('global/include/_box_modal_messages');?>
<script type="text/javascript">
$(document).on("ready", function(){
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
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
});



$(document).ready(function() {

	$('#delay_filter_ps_customer_id').change(function() {
		resetOptions('delay_filter_ps_workplace_id');
		$('#delay_filter_ps_workplace_id').select2('val','');
		$("#delay_filter_ps_workplace_id").attr('disabled', 'disabled');

		$.ajax({
	    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
	    	type: 'POST',
	    	data: 'psc_id='+$(this).val(),
	    	success: function(data){
	    		$('#delay_filter_ps_workplace_id').html(data);
	    		$("#delay_filter_ps_workplace_id").attr('disabled', null);
	    	}
	    });
	});
	
});
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psSemester/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Config semester', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
								<div id="dt_basic_filter"
									class="sf_admin_filter dataTables_filter">
        	<?php if ($formFilter->hasGlobalErrors()): ?>
              <?php echo $formFilter->renderGlobalErrors() ?>
            <?php endif; ?>
        	<form id="ps-filter" class="form-inline pull-left"
										action="<?php echo url_for('ps_semester_collection', array('action' => 'config')) ?>"
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
            				<?php echo $helper->linkToFilterSearch() ?>
            			</label>
											</div>

										</div>
									</form>
								</div>
							</div>
						</div>

	<form id="ps-form" class="form-horizontal" action="<?php echo url_for('@ps_semester_config_save') ?>"
	method="post">
	<input type="hidden" name="ps_semester_config[ps_customer_id]" value="<?php echo $ps_customer_id; ?>" id="configlatepayment_ps_customer_id"> 
	<input type="hidden" name="ps_semester_config[ps_workplace_id]"value="<?php echo $ps_workplace_id; ?>" id="configlatepayment_ps_workplace_id"> 
	<input type="hidden" name="ps_semester_config[ps_school_year_id]" value="<?php echo $ps_school_year_id; ?>" id="configlatepayment_ps_school_year_id">
	<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer no-padding">
		<div class="custom-scroll table-responsive">
			<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">

				<thead>
					<tr>
						<th class="text-center"> <?php echo __('School year', array(), 'messages') ?> </th>
						<th class="text-center"> <?php echo __('End semester one', array(), 'messages') ?> </th>
						<th class="text-center"> <?php echo __('End semester two', array(), 'messages') ?> </th>
						<th class="text-center"> <?php echo __('Updated by', array(), 'messages') ?> </th>
					</tr>
				</thead>

				<tbody>
					<?php if(!$list_config){?>
					<tr>
						<td class="text-center"><?php echo $schoolYearsDefault->getTitle();?></td>
						<td>
							<select name="ps_semester_config[end_semester_one]" class="form-control" style="width: 100%;border: 2px solid #f00;">
								<?php $index = 0;
								foreach ($ps_month as $month){ $index++;?>
								<?php if($index == 6){?>
								<option value="<?php echo $month?>" selected="selected"><?php echo $month?></option>
								<?php }else{?>
								<option value="<?php echo $month?>"><?php echo $month?></option>
								<?php }echo $key;}
								?>
							</select>
						</td>
						<td>
							<select name="ps_semester_config[end_semester_two]" class="form-control" style="width: 100%;border: 2px solid #f00;">
								<?php $index = 0;
								foreach ($ps_month as $month){ $index++;?>
								<?php if(count($ps_month) == $index){?>
								<option value="<?php echo $month?>" selected="selected"><?php echo $month?></option>
								<?php }else{?>
								<option value="<?php echo $month?>"><?php echo $month?></option>
								<?php }echo $key;}
								?>
							</select>
						</td>
						<td class="text-center"><span style="color: #f00"><?php echo __('Not config fee')?></span></td>
					</tr>
					<?php }else{?>
					<tr>
						<td class="text-center"><?php echo $schoolYearsDefault->getTitle();?></td>
						<td>
							<?php 
							$array_semester_one = explode(';', $list_config->getSemesterOne());
							$semester_one = $array_semester_one[1]
							?>
							<select class="form-control" style="width: 100%">
								<?php foreach ($ps_month as $key=>$month){?>
								<?php if($semester_one == $month){?>
								<option value="<?php echo $month?>" selected="selected"><?php echo $month?></option>
								<?php }else{?>
								<option value="<?php echo $month?>"><?php echo $month?></option>
								<?php }?>
								<?php }?>
							</select>
						</td>
						<td>
							<?php 
							$array_semester_two = explode(';', $list_config->getSemesterTwo());
							$semester_two = $array_semester_two[1]
							?>
							<select class="form-control" style="width: 100%">
								<?php foreach ($ps_month as $key=>$month){?>
								<?php if($semester_two == $month){?>
								<option value="<?php echo $month?>" selected="selected"><?php echo $month?></option>
								<?php }else{?>
								<option value="<?php echo $month?>"><?php echo $month?></option>
								<?php }?>
								<?php }?>
							</select>
						</td>
						<td class="text-center">
        					<?php echo $list_config->getUpdatedBy(); ?>
        					<br />
        					<?php echo false !== strtotime($list_config->getUpdatedAt()) ? format_date($list_config->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
        				</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="sf_admin_actions dt-toolbar-footer">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
	    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_ROOMS_ADD',  1 => 'PS_SYSTEM_ROOMS_EDIT',))): ?>						
			<button type="submit" class="btn btn-default btn-success btn-sm btn-psadmin pull-right">
			<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
			<?php endif; ?>
		</div>
	</div>
	</form>

					</div>
				</div>
			</div>
		</article>

	</div>
</section>