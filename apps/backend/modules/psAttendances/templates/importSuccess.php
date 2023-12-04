<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<style>
.float-right {
	float: right;
	margin-right: 20px;
}

.float-right a {
	margin-top: -10px;
}
</style>
<?php $upload_max_size = 2000;?>
<script type="text/javascript">
$(document).ready(function() {

	$('#import_filter_ps_customer_id').change(function() {
		resetOptions('import_filter_ps_workplace_id');
		$('#import_filter_ps_workplace_id').select2('val','');
		$("#import_filter_ps_workplace_id").attr('disabled', 'disabled');
	if ($(this).val() > 0) {

		$("#import_filter_ps_workplace_id").attr('disabled', 'disabled');
		
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

	    	$('#import_filter_ps_workplace_id').select2('val','');

			$("#import_filter_ps_workplace_id").html(msg);

			$("#import_filter_ps_workplace_id").attr('disabled', null);

	    });
	}		
});

$('#import_filter_ps_school_year_id').change(function() {
	
	resetOptions('import_filter_ps_month');
	$('#import_filter_ps_month').select2('val','');
	if ($('#ps_logtimes_filters_ps_customer_id').val() <= 0) {
		return;
	}

	$("#import_filter_ps_month").attr('disabled', 'disabled');
	
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
    	$('#import_filter_ps_month').select2('val','');
		$("#import_filter_ps_month").html(msg);
		$("#import_filter_ps_month").attr('disabled', null);
    });
    
});


var msg_name_file_invalid 	= '<?php

echo __ ( 'The excel file must be in the format: xls, xlsx. File size less than %value%KB.', array (
		'%value%' => $upload_max_size ) )?>';
var PsMaxSizeFile = '<?php echo $upload_max_size;?>';

$('#ps-form').formValidation({
	framework : 'bootstrap',
	excluded : [ ':disabled' ],
	addOns : {
		i18n : {}
	},
	errorElement : "div",
	errorClass : "help-block with-errors",
	message : {
		vi_VN : 'This value is not valid'
	},
	fields : {
		'import_filter[ps_file]' : {
			validators : {
				file : {
					extension : 'xls,xlsx',
					maxSize : PsMaxSizeFile * 1024,
					message : {
						en_US : msg_name_file_invalid,
						vi_VN : msg_name_file_invalid
					}
				}
			}
		}
	}
});
$('#ps-form').formValidation('setLocale', PS_CULTURE);

});

</script>

<section id="widget-grid">
	<div class="row">
		<?php include_partial('psAttendances/flashes2')?>
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Attendances import', array(), 'messages') ?></h2>

					<div class="widget-toolbar">
						<a class="btn btn-success" target="_blank"
							href="<?php echo url_for('@ps_attendances_student_export') ?>"><i
							class="fa fa-cloud-download"></i><?php echo __('Download template') ?></a>
					</div>
				</header>
				<div id="sf_admin_content">

					<div class="sf_admin_form widget-body">
						<?php if ($formFilter->hasGlobalErrors()): ?>
					    <?php echo $formFilter->renderGlobalErrors()?>
					    <?php endif; ?>
							<form id="ps-form"
							class="form-horizontal fv-form fv-form-bootstrap"
							action="<?php echo url_for('@ps_attendances_import_save') ?>"
							method="post" enctype="multipart/form-data">
							<fieldset>
					    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
					    	 	<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                                		 	<?php echo __('School year', array(), 'messages') ?>
                                		 	<span class="required"> *</span>
										</label>
										<div class="col-md-9">
                                		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
                                		 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
                                		 </div>
									</div>
								</div>
								<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                                		 	<?php echo __('Select month', array(), 'messages') ?>
                                		 	<span class="required"> *</span>
										</label>
										<div class="col-md-9">
                                		 	<?php echo $formFilter['ps_month']->render() ?>
                                		 	<?php echo $formFilter['ps_month']->renderError() ?>
                                		 </div>
									</div>
								</div>
                        		<?php if ($sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL'))): ?>
                        		<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                                		 	<?php echo __('Select customer', array(), 'messages') ?>
                                		 	<span class="required"> *</span>
										</label>
										<div class="col-md-9">
                                		 	<?php echo $formFilter['ps_customer_id']->render() ?>
                                		 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
                                		 </div>
									</div>
								</div>
                        		<?php endif?>
                        		<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                                		 	<?php echo __('Select workplace', array(), 'messages') ?>
                                		</label>
										<div class="col-md-9">
                                		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
                                		 	<?php echo $formFilter['ps_workplace_id']->renderError() ?>
                                		 </div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                            		 		<?php echo __('Input file', array(), 'messages') ?>
                            		 		<span class="required"> *</span>
										</label>
										<div class="col-md-9">
                            		  		<?php echo $formFilter['ps_file']->render() ?>
                            		  		<?php echo $formFilter['ps_file']->renderError() ?>
                                		</div>
									</div>
								</div>
							</fieldset>

							<div class="form-actions">
								<div class="sf_admin_actions">
									<span class="btn-sm btn-psadmin pull-left"><b
										style="color: #f00"><?php echo __('Note').': '?></b><strong><?php echo __('Import data student in class by month')?></strong></span>
									<button type="submit"
										onclick="if (!confirm('<?php echo __('Import attendance in month') ?>' + $('#import_filter_ps_month').val() + '?')){return false;}"
										class="btn btn-default btn-success btn-sm">
										<i class="fa-fw fa fa-cloud-upload" aria-hidden="true"
											title="<?php echo __('Upload');?>"></i><?php echo __('Upload', array(), 'messages') ?>
						                </button>
								</div>
							</div>
						</form>
					</div>
					<div id="sf_admin_footer" class="no-border no-padding"></div>
				</div>
			</div>
		</article>
	</div>
</section>

