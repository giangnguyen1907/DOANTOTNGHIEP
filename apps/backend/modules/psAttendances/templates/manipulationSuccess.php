<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<script type="text/javascript">
$(document).ready(function() {
	$('#logtimes_filter_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});
});
</script>
<?php
$array_attendance = array ();
$array_attendance2 = array ();
foreach ( $list_attendances as $attendances ) {
	array_push ( $array_attendance, $attendances->getMemberId () . date ( "Ymd", strtotime ( $attendances->getLtLoginAt () ) ) );
}
?>
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
					<h2><?php
					if ($date_at != '') {
						echo __ ( 'Statistic member action of day', array (), 'messages' ) . $date_at;
					} else {
						echo __ ( 'Statistic member action of month', array (), 'messages' ) . $year_month;
					}
					?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psAttendances/filters_manipulation', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>
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
											<th class="text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Member name', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Attendances number', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Feature number', array(), 'messages') ?></th>
										</tr>
									</thead>
									<tbody>
            							<?php
																			// echo $ps_workplace_id;
																			$number = count ( $list_attendances );
																			?>
            							<?php foreach ($filter_list_member as $ky=> $list_member ): ?>
            							<tr>
											<td class="text-center"><?php echo $ky+1 ?></td>
											<td><?php echo $list_member->getMemberName() ?></td>
											<td class="text-center">
                                          	<?php
																				echo $number_att = Doctrine::getTable ( 'PsLogtimes' )->countMemberAttendance ( $ps_workplace_id, $list_member->getMbId (), $year_month, $date_at );
																				?>
                                          	</td>
											<td class="text-center">
                                          	<?php
																				echo $number_feat = Doctrine::getTable ( 'StudentFeature' )->countMemberFeatureOfMonth ( $list_member->getMbId (), $year_month, $date_at );
																				?>
                                          	</td>
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