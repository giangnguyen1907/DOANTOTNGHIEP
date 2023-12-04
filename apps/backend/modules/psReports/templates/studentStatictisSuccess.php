<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudents/assets')?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php //include_partial('psServiceSaturday/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Students statistic in customer: %%title%%', array('%%title%%' => ''), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
			    <?php include_partial('psStudents/filters', array('filter' => $filter, 'helper' => $helper)) ?>
			  </div>
						</div>
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
									<tbody>
										<tr>
											<th class="text-center" rowspan="2"><?php echo __('No.') ?></th>
											<th class="text-center" rowspan="2"><?php echo __('Class') ?></th>
											<th class="text-center" rowspan="2"><?php echo __('Teacher') ?></th>
											<th class="text-center" rowspan="2"><?php echo __('Total Class') ?></th>
											<th class="text-center" rowspan="2"><?php echo __('Total Student') ?></th>
											<th class="text-center" colspan="5"><?php echo __('Status')?></th>
											<th class="text-center" rowspan="2"><?php echo __('Note') ?></th>
										</tr>
										<tr>
											<th class="text-center"><?php echo __('Offical')?></th>
											<th class="text-center"><?php echo __('Test')?></th>
											<th class="text-center"><?php echo __('Pause')?></th>
											<th class="text-center"><?php echo __('Stop studying')?></th>
											<th class="text-center"><?php echo __('Graduation')?></th>
										</tr>
									</tbody>


									<tbody>
										<!-- 							Trường học -->
										<tr>
											<td colspan="3">Toàn trường</td>
											<td class="text-center"><?php echo __('Total Class') ?></td>
											<td class="text-center"><?php echo __('Total Student') ?></td>
											<td class="text-center"><?php echo __('Offical')?></td>
											<td class="text-center"><?php echo __('Test')?></td>
											<td class="text-center"><?php echo __('Pause')?></td>
											<td class="text-center"><?php echo __('Stop studying')?></td>
											<td class="text-center"><?php echo __('Graduation')?></td>
											<td class="text-center"><?php echo __('Note') ?></td>

										</tr>

										<!--                             Cơ sở -->
										<tr>
											<td colspan="3">&emsp;Toàn cơ sở</td>
											<td class="text-center"><?php echo __('Total Class') ?></td>
											<td class="text-center"><?php echo __('Total Student') ?></td>
											<td class="text-center"><?php echo __('Offical')?></td>
											<td class="text-center"><?php echo __('Test')?></td>
											<td class="text-center"><?php echo __('Pause')?></td>
											<td class="text-center"><?php echo __('Stop studying')?></td>
											<td class="text-center"><?php echo __('Graduation')?></td>
											<td class="text-center"><?php echo __('Note') ?></td>

										</tr>

										<!--                             Lớp học -->
										<tr>
											<td class="text-center"><?php echo __('No.') ?></td>
											<td class="text-center"><?php echo __('Class') ?></td>
											<td class="text-center"><?php echo __('Teacher') ?></td>
											<td class="text-center"><?php echo __('Total Class') ?></td>
											<td class="text-center"><?php echo __('Total Student') ?></td>
											<td class="text-center"><?php echo __('Offical')?></td>
											<td class="text-center"><?php echo __('Test')?></td>
											<td class="text-center"><?php echo __('Pause')?></td>
											<td class="text-center"><?php echo __('Stop studying')?></td>
											<td class="text-center"><?php echo __('Graduation')?></td>
											<td class="text-center"><?php echo __('Note') ?></td>

										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</article>
		<!-- 			Xuất file els -->
		</article>

	</div>
</section>