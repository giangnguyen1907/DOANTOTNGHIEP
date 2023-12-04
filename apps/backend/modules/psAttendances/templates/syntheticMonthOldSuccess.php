<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('#synthetic_month_filter_ps_customer_id').change(function() {
		resetOptions('synthetic_month_filter_ps_workplace_id');
		$('#synthetic_month_filter_ps_workplace_id').select2('val','');
		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('synthetic_month_filter_class_id');
		$('#synthetic_month_filter_class_id').select2('val','');
		$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#synthetic_month_filter_ps_workplace_id').select2('val','');

			$("#synthetic_month_filter_ps_workplace_id").html(msg);

			$("#synthetic_month_filter_ps_workplace_id").attr('disabled', null);

			$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#synthetic_month_filter_ps_workplace_id').change(function() {
	
	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#synthetic_month_filter_ps_customer_id').val() + '&w_id=' + $('#synthetic_month_filter_ps_workplace_id').val() + '&y_id=' + $('#synthetic_month_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#synthetic_month_filter_class_id').select2('val','');
		$("#synthetic_month_filter_class_id").html(msg);
		$("#synthetic_month_filter_class_id").attr('disabled', null);
    });
});

$('#synthetic_month_filter_ps_school_year_id').change(function() {

	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	$("#synthetic_month_filter_year_month").attr('disabled', 'disabled');
	
	if ($(this).val() > 0) {
		
    	$("#synthetic_month_filter_year_month").attr('disabled', 'disabled');
    	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#synthetic_month_filter_ps_customer_id').val() + '&w_id=' + $('#synthetic_month_filter_ps_workplace_id').val() + '&y_id=' + $('#synthetic_month_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#synthetic_month_filter_class_id').select2('val','');
    		$("#synthetic_month_filter_class_id").html(msg);
    		$("#synthetic_month_filter_class_id").attr('disabled', null);
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
    	    	$('#synthetic_month_filter_year_month').select2('val','');
    			$("#synthetic_month_filter_year_month").html(msg);
    			$("#synthetic_month_filter_year_month").attr('disabled', null);
    	    });
	}
});

});
</script>

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
					<h2><?php echo __('Synthetic statistic month', array(), 'messages').__('Day total', array(), 'messages').$year_month.' : '.$number_day['saturday_day'].' '.__('Day', array(), 'messages') ?></h2>

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
										action="<?php echo url_for('ps_attendances_collection', array('action' => 'syntheticMonthOld')) ?>"
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
        		 	<?php echo $formFilter['year_month']->render() ?>
        		 	<?php echo $formFilter['year_month']->renderError() ?>
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
        		 	<?php echo $formFilter['class_id']->render() ?>
        		 	<?php echo $formFilter['class_id']->renderError() ?>
        		</label>
											</div>
											
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['type_updated']->render() ?>
        		 	<?php echo $formFilter['type_updated']->renderError() ?>
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
								<div style="clear: both"></div>
							</div>
						</div>
						<div style="clear: both"></div>
						<p style="padding: 20px 10px; color: #d81616; font-weight: 600;">
	<?php

	if (isset($class_id) && $class_id > 0) {
		echo __ ( 'Updated attendances and feature branch success.' );
	}
	?>
	</p>
					</div>


				</div>


			</div>

		</article>

	</div>
</section>
<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_class_id		= '<?php echo __('Please enter class to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	var msg_select_ps_month		= '<?php echo __('Please enter month to filter the data.')?>';

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
			"synthetic_month_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "synthetic_month_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            "synthetic_month_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "synthetic_month_filter[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_class_id,
                        		  en_US: msg_select_class_id
                        }
                    },
                }
            },

            "synthetic_month_filter[ps_month]": {
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