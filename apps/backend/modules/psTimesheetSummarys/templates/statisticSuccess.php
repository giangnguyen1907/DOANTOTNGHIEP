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
<?php
$array_time = array ();
foreach ( $timesheet_summarys as $timesheet ) {
	array_push ( $array_time, $timesheet->getMemberId () . date ( "Ymd", strtotime ( $timesheet->getTimesheetAt () ) ) );
}

$array_absent = array ();
foreach ( $member_absent as $absent ) {
	array_push ( $array_absent, $absent->getMemberId () . $absent->getAbsentType () );
}

?>
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
					<h2><?php echo __('Statistic timesheet', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psTimesheetSummarys/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
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
											<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                                            <?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                            <th
												class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                                            <?php } ?>
                                            <th class="text-center"><?php echo __('Day total', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Invalid', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('illegal', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Go school', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Sum', array(), 'messages') ?></th>
										</tr>
									</thead>
            						<?php //echo count($member_absent); ?>
            						<tbody>
            							<?php foreach ($filter_list_student as $ky=>$list_member ): ?>
            							<tr>
											<td class="text-center"><?php echo $ky+1; ?></td>
											<td><?php echo $list_member->getMemberName() ?></td>
                                              <?php for ($i =1 ;$i <= $number_day['number_day_month']; $i++ ){ ?>
                                              <?php if(date("Ymd", strtotime($i.'-'.$year_month)) <= date("Ymd")){?>
                                              
                                              <td
												class="text-center <?php if(in_array($i, $sunday)){ echo 'bg-color-red';} if(in_array($i, $saturday)){ echo 'bg-color-orange'; }?>">
                                              
                                              <?php
																						if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( $i . '-' . $year_month ) ), $array_time )) {
																							echo 'x';
																							$a ++;
																						}
																						?>
                                              </td>
                                              <?php  } else{ ?>
                                              <td
												style='background: #eee'></td>
                                              <?php } }?>
                                              <td class="text-center"><?php echo $number_day['saturday_day'] ?></td>
											<td class="text-center">
                                              <?php
																				if (in_array ( $list_member->getMbId () . '1', $array_absent )) {
																					$b ++;
																				} elseif (in_array ( $list_member->getMbId () . '0', $array_absent )) {
																					$c ++;
																				}
																				?>
                                              <?php echo $b; ?>
                                              </td>
											<td class="text-center">
                                              <?php echo $c; ?>
                                              </td>
											<td class="text-center">
                                              <?php echo $a; ?>
                                              </td>
											<td class="text-center">
                                              	<?php echo $a + $b; ?>
                                              </td>
                                              <?php $a=''; $b='';$c=''; ?>
                                        <?php endforeach; ?>
            						
									
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
			</div>
		</article>
		<?php

if ($ps_customer_id > 0) {
			if ($ps_department_id == '') {
				$ps_department_id = 0;
			}
			if ($ps_workplace_id == '') {
				$ps_workplace_id = 0;
			}
			?>
            <article
			class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default"
				href="<?php echo url_for(@ps_timesheet_summarys).'/'.$ps_customer_id.'/'.$ps_workplace_id.'/'.$ps_department_id.'/'.$year_month.'/'; ?>export_summarys"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
       <?php } ?>
	</div>
</section>