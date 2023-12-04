<?php use_helper('I18N', 'Date')?>
<?php include_partial('psFeatureBranchTimes/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.float-right {
	float: right;
	margin-right: 20px;
}
.float-right a {
	margin-top: -10px;
}
</style>

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
					<h2><?php echo __('Ps feature branch export template 3', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body">
						<div id="dt_basic_filter"
							class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
    <?php echo $formFilter->renderGlobalErrors()?>
    <?php endif; ?>
		<form id="ps-filter" class="form-inline pull-left"
								action="<?php echo url_for('ps_feature_branch_times_collection', array('action' => 'exportTem3')) ?>"
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
        		 	<?php echo $formFilter['ps_class_id']->render() ?>
        		 	<?php echo $formFilter['ps_class_id']->renderError() ?>
        		 </label>
									</div>
									<div class="form-group">
										<label>
        		 	<?php echo $formFilter['ps_year']->render() ?>
        		 	<?php echo $formFilter['ps_year']->renderError() ?>
        		 </label>
									</div>
									
									<div class="form-group">
										<label>
						        		 	<?php echo $formFilter['ps_week']->render() ?>
						        		 	<?php echo $formFilter['ps_week']->renderError() ?>
						        		 </label>
									</div>
									
									<div class="form-group">
										<label>
											<button type="submit" rel="tooltip" data-placement="bottom"
												data-original-title="<?php echo __('Download template');?>"
												class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
												<i class="fa-fw fa fa-cloud-download"></i><?php echo __('Download template')?>
                    						</button>
										</label>
									</div>

								</div>
							</form>
						</div>
					</div>
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
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_month		    = '<?php echo __('Please select week filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	
	
	$('#export_filter_ps_customer_id').change(function() {
		resetOptions('export_filter_ps_workplace_id');
		$('#export_filter_ps_workplace_id').select2('val','');
		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
	if ($(this).val() > 0) {

		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#export_filter_ps_class_id").attr('disabled', 'disabled');
		
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

	    	$('#export_filter_ps_workplace_id').select2('val','');

			$("#export_filter_ps_workplace_id").html(msg);

			$("#export_filter_ps_workplace_id").attr('disabled', null);

	    });

		$("#export_filter_ps_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#export_filter_ps_customer_id').val() + '&w_id=' + $('#export_filter_ps_workplace_id').val() + '&y_id=' + $('#export_filter_ps_school_year_id').val(),
	        processResults: function (data, page) {
	      		return {
	        		results: data.items
	      		};
	    	},
	    }).done(function(msg) {
	    	$('#export_filter_ps_class_id').select2('val','');
			$("#export_filter_ps_class_id").html(msg);
			$("#export_filter_ps_class_id").attr('disabled', null);
	    });
	    
		}		
	});
	
	$('#export_filter_ps_workplace_id').change(function() {
		
		$("#export_filter_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#export_filter_ps_customer_id').val() + '&w_id=' + $('#export_filter_ps_workplace_id').val() + '&y_id=' + $('#export_filter_ps_school_year_id').val(),
	        processResults: function (data, page) {
	      		return {
	        		results: data.items
	      		};
	    	},
	    }).done(function(msg) {
	    	$('#export_filter_ps_class_id').select2('val','');
			$("#export_filter_ps_class_id").html(msg);
			$("#export_filter_ps_class_id").attr('disabled', null);
	    });
	});
	
    $('#export_filter_ps_year').change(function() {

    	if ($(this).val() <= 0) {
			return;
		}
		
    	$("#export_filter_ps_week").attr('disabled', 'disabled');
    	$.ajax({
            url: '<?php echo url_for('@ps_menus_weeks_year') ?>',
            type: "POST",
            data: {'ps_year': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
  		}).done(function(msg) {
  			 $('#export_filter_ps_week').select2('val','');
 			 $("#export_filter_ps_week").html(msg);
  			 $("#export_filter_ps_week").attr('disabled', null);

  			$('#export_filter_ps_week').val(1);
			$('#export_filter_ps_week').change();
  		});
    });

	
	
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
		"export_filter[ps_customer_id]": {
            validators: {
                notEmpty: {
                    message: {vi_VN: msg_select_ps_customer_id,
                    		  en_US: msg_select_ps_customer_id
                    }
                }
            }
        },
        
        "export_filter[ps_school_year_id]": {
            validators: {
                notEmpty: {
                    message: {vi_VN: msg_select_ps_schoolyear_id,
                    		  en_US: msg_select_ps_schoolyear_id
                    }
                }
            }
        },
        
        "export_filter[ps_workplace_id]": {
            validators: {
                notEmpty: {
                    message: {vi_VN: msg_select_ps_workplace_id,
                    		  en_US: msg_select_ps_workplace_id
                    }
                }
            }
        },
        
        "export_filter[ps_week]": {
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
