
<?php use_helper('I18N', 'Date')?>
<?php include_partial('psTimesheetSummarys/assets')?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
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
					<h2><?php echo __('Synthetic timesheet', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psTimesheetSummarys/filters_synthetic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>
            			<?php //echo count($delete_student); ?>
            			<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
            						<?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
            						<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
            						<thead>
										<tr>
											<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Timesheet at', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Number time', array(), 'messages') ?></th>
										</tr>
									</thead>
            						<?php //echo $year_month ?>
            						<tbody>
            							
            							<?php foreach ($list_student as $ky=> $timesheet ): ?>
            							
            							<tr>

											<td><?php echo $timesheet->getMemberName(); ?></td>
											<td class="text-center"><?php echo $timesheet->getTimesheetAt(); ?></td>
											<td class="text-center"><?php echo $timesheet->getNumberTime(); ?></td>

										</tr>
                                        
                                        <?php endforeach; ?>
                                        
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
