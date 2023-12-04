<?php use_helper('I18N', 'Date')?>
<?php include_partial('psTimesheet/assets')?>
<script>
$(document).ready(function() {

	$('#timesheet_filter_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	
// 	.on('change', function(e) {
// 		$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
// 	});
	
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

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Review timesheet', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psTimesheet/filter_timesheet', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
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
											<th class="text-center"><?php echo __('Member', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Is io', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Time', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Updated at', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Action', array(), 'messages') ?></th>
										</tr>
									</thead>
									<tbody>
            						<?php //echo count($filter_list_timesheet) ?>
            						<?php foreach ($filter_list_timesheet as $list_timesheet){ ?>
            							<tr>
											<td><?php echo $list_timesheet->getMemberName(); ?></td>
											<td class="text-center">
                                            <?php echo get_partial('psTimesheet/field_absent_type', array('data' => $list_timesheet->getIsIo())) ?>
                                            </td>
											<td class="text-center"><?php echo $list_timesheet->getTimeAt(); ?></td>
											<td class="text-center">
                                            <?php echo $list_timesheet->getUpdatedBy() ?><br />
  											<?php echo false !== strtotime($list_timesheet->getUpdatedAt()) ? format_date($list_timesheet->getUpdatedAt(), "HH:mm:ss dd/MM/yyyy") : '&nbsp;' ?>
                                            </td>
											<td class="text-center">
                                              <?php if ($sf_user->hasCredential('PS_HR_TIMESHEET_EDIT')): ?>
                                              <?php echo $helper->linkToEdit($list_timesheet, array(  'credentials' => 'PS_HR_TIMESHEET_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
                                              <?php endif; ?>
                                            </td>
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