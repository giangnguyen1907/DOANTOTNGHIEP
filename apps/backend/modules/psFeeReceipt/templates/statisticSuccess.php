<?php use_helper('I18N', 'Date')?>
<?php include_partial('psFeeReceipt/assets')?>
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
$(document).ready(function(){
//filter statistic
$('#logtimes_filter_ps_customer_id').change(function() {

	resetOptions('logtimes_filter_ps_workplace_id');
	$('#logtimes_filter_ps_workplace_id').select2('val','');
	$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
	resetOptions('logtimes_filter_class_id');
	$('#logtimes_filter_class_id').select2('val','');
	$("#logtimes_filter_class_id").attr('disabled', 'disabled');
	resetOptions('logtimes_filter_ps_service');
	$('#logtimes_filter_ps_service').select2('val','');
	$("#logtimes_filter_ps_service").attr('disabled', 'disabled');
	
    if ($(this).val() > 0) {
    
    	$("#logtimes_filter_ps_workplace_id").attr('disabled', 'disabled');
    	$("#logtimes_filter_class_id").attr('disabled', 'disabled');
    	$("#logtimes_filter_ps_service").attr('disabled', 'disabled');
    	$("#logtimes_filter_ps_receivable").attr('disabled', 'disabled');
    	
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
    
        	$('#logtimes_filter_ps_workplace_id').select2('val','');
    
    		$("#logtimes_filter_ps_workplace_id").html(msg);
    
    		$("#logtimes_filter_ps_workplace_id").attr('disabled', null);
    
    		$("#logtimes_filter_class_id").attr('disabled', 'disabled');
    
    		$.ajax({
    	        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
    	        type: "POST",
    	        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val() + '&e_sc=' + $("#logtimes_filter_ps_customer_id").val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#logtimes_filter_ps_service').select2('val','');
    			$("#logtimes_filter_ps_service").html(msg);
    			$("#logtimes_filter_ps_service").attr('disabled', null);
    	    });

    	    $.ajax({
    	        url: '<?php echo url_for('@ps_receivable_by_ps_customer') ?>',
    	        type: "POST",
    	        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val(),
    	        processResults: function (data, page) {
    	      		return {
    	        		results: data.items
    	      		};
    	    	},
    	    }).done(function(msg) {
    	    	$('#logtimes_filter_ps_receivable').select2('val','');
    			$("#logtimes_filter_ps_receivable").html(msg);
    			$("#logtimes_filter_ps_receivable").attr('disabled', null);
    	    });
        });
    }		
});

$('#logtimes_filter_ps_workplace_id').change(function() {

    $("#logtimes_filter_class_id").attr('disabled', 'disabled');
    $("#logtimes_filter_ps_service").attr('disabled', 'disabled');
    $("#logtimes_filter_ps_receivable").attr('disabled', 'disabled');
    $.ajax({
    	url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_class_id').select2('val','');
    	$("#logtimes_filter_class_id").html(msg);
    	$("#logtimes_filter_class_id").attr('disabled', null);
    });

    $.ajax({
        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
        type: "POST",
        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val() + '&e_sc=' + $("#logtimes_filter_ps_customer_id").val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_ps_service').select2('val','');
		$("#logtimes_filter_ps_service").html(msg);
		$("#logtimes_filter_ps_service").attr('disabled', null);
    });
    
    $.ajax({
        url: '<?php echo url_for('@ps_receivable_by_ps_customer') ?>',
        type: "POST",
        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_ps_receivable').select2('val','');
		$("#logtimes_filter_ps_receivable").html(msg);
		$("#logtimes_filter_ps_receivable").attr('disabled', null);
    });
});

$('#logtimes_filter_ps_school_year_id').change(function() {

    resetOptions('logtimes_filter_class_id');
    $('#logtimes_filter_class_id').select2('val','');
    
    resetOptions('logtimes_filter_ps_month');
    $('#logtimes_filter_ps_month').select2('val','');

    if ($('#logtimes_filter_ps_customer_id').val() <= 0) {
    	return;
    }

    $("#logtimes_filter_class_id").attr('disabled', 'disabled');
    $("#logtimes_filter_ps_month").attr('disabled', 'disabled');
    $("#logtimes_filter_ps_service").attr('disabled', 'disabled');
    $("#logtimes_filter_ps_receivable").attr('disabled', 'disabled');
    
    $.ajax({
    	url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#logtimes_filter_ps_customer_id').val() + '&w_id=' + $('#logtimes_filter_ps_workplace_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_class_id').select2('val','');
    	$("#logtimes_filter_class_id").html(msg);
    	$("#logtimes_filter_class_id").attr('disabled', null);
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
	    	$('#logtimes_filter_ps_month').select2('val','');
			$("#logtimes_filter_ps_month").html(msg);
			$("#logtimes_filter_ps_month").attr('disabled', null);
	    });
    
    $.ajax({
        url: '<?php echo url_for('@ps_service_courses_by_ps_workplace') ?>',
        type: "POST",
        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val() + '&e_sc=' + $("#logtimes_filter_ps_customer_id").val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_ps_service').select2('val','');
		$("#logtimes_filter_ps_service").html(msg);
		$("#logtimes_filter_ps_service").attr('disabled', null);
    });
    
    $.ajax({
        url: '<?php echo url_for('@ps_receivable_by_ps_customer') ?>',
        type: "POST",
        data: 'c_id='+ $('#logtimes_filter_ps_customer_id').val() + '&y_id=' + $('#logtimes_filter_ps_school_year_id').val() + '&w_id=' + $("#logtimes_filter_ps_workplace_id").val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#logtimes_filter_ps_receivable').select2('val','');
		$("#logtimes_filter_ps_receivable").html(msg);
		$("#logtimes_filter_ps_receivable").attr('disabled', null);
    });
});


$('#logtimes_filter_ps_service').change(function() {

	if ($(this).val() > 0) {
		$("#logtimes_filter_ps_receivable").attr('disabled', 'disabled');
    }else{
    	$("#logtimes_filter_ps_receivable").attr('disabled', false);
    }
	
});

$('#logtimes_filter_ps_receivable').change(function() {

	if ($(this).val() > 0) {
		$("#logtimes_filter_ps_service").attr('disabled', 'disabled');
    }else{
    	$("#logtimes_filter_ps_service").attr('disabled', false);
    }
	
});

$('#btn-export-fee-statistic').click(function() {
	
	if ($('#logtimes_filter_ps_customer_id').val() <= 0) {
		alert('<?php echo __('Select customer')?>');
		return false;
	}
	
	$action = $('#ps-filter2').attr('action');
	
	$('#ps-filter2').attr('action', '<?php echo url_for('@ps_fee_receipt_export_receivable') ?>');
	$('#ps-filter2').submit();
	
	$('#ps-filter2').attr('action', $action);
	return true;
});

});
</script>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psFeeReceipt/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic receivable', array(), 'messages') ?></h2>
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
	<form id="ps-filter2" class="form-inline pull-left"
										action="<?php echo url_for('ps_fee_receipt_collection', array('action' => 'statistic')) ?>"
										method="post">

										<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 </label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_month']->render() ?>
        		 </label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_customer_id']->render() ?>
        		</label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		</label>
											</div>

											<div class="form-group">
												<label>
    		 		<?php echo $formFilter['class_id']->render() ?>
    		  	</label>
											</div>

											<div class="form-group">
												<label>
    		 		<?php echo $formFilter['ps_service']->render() ?>
    		  	</label>
											</div>

											<div class="form-group">
												<label>
    		 		<?php echo $formFilter['ps_receivable']->render() ?>
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
            			
<?php

if ($ps_receivable > 0) {
	include_partial ( 'psFeeReceipt/table_receivable', array (
			'list_student' => $list_student,
			'list_receivable' => $list_receivable,
			'ps_month' => $ps_month,
			'receivable_title' => $receivable_title ) );
} elseif ($ps_service > 0) {
	include_partial ( 'psFeeReceipt/table_service', array (
			'list_student' => $list_student,
			'list_service' => $list_service,
			'ps_month' => $ps_month,
			'receivable_title' => $receivable_title ) );
} else {
	?>

    <div style="padding-top: 30px; clear: both">
							<strong><?php echo __('You can select service or receivable')?></strong>
						</div>

<?php }?>

            		</div>
				</div>
			</div>

		</article>
		<?php if($ps_receivable > 0 || $ps_service > 0){?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default" id="btn-export-fee-statistic"><i
				class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
        <?php }?>
	</div>
</section>

<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	var msg_select_ps_month 	= '<?php echo __('Please select month to filter the data.')?>';

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
            "logtimes_filter[ps_month]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_month,
                        		  en_US: msg_select_ps_month
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
</script>