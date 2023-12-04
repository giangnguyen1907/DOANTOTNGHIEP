<?php use_helper('I18N', 'Date')?>
<?php include_partial('psReceivableStudents/assets')?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}
</style>
<?php
// echo count($list_students);
$number = count ( $filter_list_student );
$list_receivable_std = array ();
$list_receivable_title = array ();
$_array_student = array ();
foreach ( $filter_list_student as $receivables ) {
	array_push ( $list_receivable_std, $receivables->getStudentId () . '_' . $receivables->getReceivableId () );
}

foreach ( $list_receivable as $receivable ) {
	$list_receivable_title [$receivable->getId ()] = $receivable->getTitle ();
	$_array_student [$receivable->getId ()] = $receivable->getAmount ();
}

?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psReceivableStudents/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Receivable student statistic', array(), 'messages')?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psReceivableStudents/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
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
											<th class="text-center"><?php echo __('STT', array(), 'messages') ?></th>
											<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
                                            <?php foreach ($list_receivable_title as $ky=> $receivable_title){ ?>
                                            	<th class="text-center"><?php echo $receivable_title ?></th>
                                            <?php }?>
                                        </tr>
									</thead>

									<tbody>
            							<?php foreach ($list_students as $ky=> $list_student){ ?>
            							<tr>
											<td class="text-center"><?php echo $ky+1?></td>
											<td>
            									<?php echo $list_student->getStudentName()?><br>
											<code><?php echo $list_student->getStudentCode()?></code>
											</td>
            								<?php

foreach ( $_array_student as $key => $amount ) {
																					$check = $list_student->getId () . '_' . $key;
																					?>
            								<td class="text-center">
            								<?php

if (in_array ( $check, $list_receivable_std )) {
																						echo number_format ( $amount, 0, ",", "." );
																					}
																					?>
            								</td>
            								<?php }?>
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
		<?php if($class_id ==''){ $class_id = 0;}?>
		<?php if($number > 0){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default"
				href="<?php echo url_for(@ps_receivable_students).'/'.$ps_school_year_id.'/'.$year_month.'/'.$ps_customer_id.'/'.$ps_workplace_id.'/'.$class_id.'/'; ?>export_class"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
        <?php }?>
	</div>
</section>