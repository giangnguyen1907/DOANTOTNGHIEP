<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudentFeatures/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<script>
$(document).ready(function() {
    $('#history_filter_date_at_from').datepicker({
    	dateFormat : 'dd-mm-yy',
    	maxDate : new Date(),
    	prevText : '<i class="fa fa-chevron-left"></i>',
    	nextText : '<i class="fa fa-chevron-right"></i>',
    	changeMonth : true,
    	changeYear : true,
    
    })
    .on('change', function(e) {
    	// Revalidate the date field
    	$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    });

    $('#history_filter_date_at_to').datepicker({
    	dateFormat : 'dd-mm-yy',
    	maxDate : new Date(),
    	prevText : '<i class="fa fa-chevron-left"></i>',
    	nextText : '<i class="fa fa-chevron-right"></i>',
    	changeMonth : true,
    	changeYear : true,
    
    })
    .on('change', function(e) {
    	// Revalidate the date field
    	$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    });
})
</script>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php //include_partial('psLogtimes/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Show history student features', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psStudentFeatures/filters_history', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>

						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
									<thead>
										<tr>
											<th class="text-center"><?php echo __('Action', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('History content', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Created at', array(), 'messages') ?></th>
										</tr>
									</thead>

									<tbody>
            						<?php foreach ($filter_list_history as $list_history) { ?>
            							<tr>
											<td><?php echo $list_history->getPsAction() ?></td>
											<td><?php echo $list_history->getHistoryContent() ?></td>
											<td><?php echo $list_history->getCreatedAt() ?></td>
										</tr>
                                        <?php } ?>
            						</tbody>
								</table>
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

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	var msg_select_student_id       = '<?php echo __('Please select student to filter the data.')?>';
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_class_id		= '<?php echo __('Please enter class filter the data.')?>';

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
			"history_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "history_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "history_filter[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    },
                }
            },

            "history_filter[student_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_student_id,
                        		  en_US: msg_select_student_id
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