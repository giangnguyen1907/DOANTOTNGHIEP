<?php use_helper('I18N', 'Date')?>
<?php include_partial('psMobileApps/assets')?>

<?php
$list_month = array ();
$key = 0;
$total_relative = 0;
$total_user = 0;
$list_month [$key] ['total_mobile'] = 0;
foreach ( $months as $month ) {

	foreach ( $created_on_month as $k => $r ) {
		if ($r ['month'] == $month) {
			$list_month [$key] ['created_on_month'] = $r ['count'];
			break;
		}
	}

	foreach ( $deleted_on_month as $k => $r ) {
		if ($r ['deleted_at'] == $month) {
			$list_month [$key] ['deleted_on_month'] = $r ['count'];
			break;
		}
	}

	foreach ( $total_relative_account as $k => $r ) {
		if ($r ['month'] == $month) {
			$list_month [$key] ['total_account'] = $r ['count'];
			break;
		}
	}

	foreach ( $total_relative_mobile as $k => $r ) {
		if ($r ['month'] == $month) {
			$list_month [$key] ['total_mobile'] += 1;
		}
	}

	$list_month [$key] ['month'] = $month;
	$key ++;
}

$total_month = count ( $list_month );
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
					<h2><?php echo __('Cross checking relative accounts') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psMobileApps/filter_cross_checking', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			    
            			  </div>
						</div>
						<div class="padding-5" style="clear: both;"></div>
						<div id="datatable_fixed_column_wrapper">
							<div class="" style="max-height: 300px; overflow-y: scroll;">

								<table id="dt_basic" class="table table-bordered">
									<thead>
										<tr>
											<th class="text-center"><?php echo __('Month')?></th>
											<th class="text-center" colspan="3"><?php echo __('Relatives') ?></th>
											<th class="text-center" colspan="4"><?php echo __("Users account") ?></th>
										</tr>
										<tr>
											<th class="text-center"><?php echo __('(mm-YYYY)')?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Created on month') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Deleted on month') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Total relative') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Actived on month') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Total user') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Account quantity change') ?></th>
											<th class="text-center" colspan="1" rowspan="1"><?php echo __('Mobile actived during the month') ?></th>
										</tr>
									</thead>
									<tbody>
										<tr style="background: #ffa5006b; font-weight: bold;">
											<td class="text-center"><?php echo __('Before'); ?></td>
											<td></td>
											<td></td>
											<td class="text-center"><?php $total_relative += $total_relative_before_from_date; echo $total_relative_before_from_date; ?></td>
											<td></td>
											<td class="text-center"><?php $total_user+= $total_account_active_before_from_date; echo $total_account_active_before_from_date ?></td>
											<td></td>
											<td></td>
										</tr>
										<!--                 						In ra theo tháng -->
											<?php foreach ($list_month as $key => $month):?>
                							<tr>
											<td class="text-center"><?php echo $month['month']; ?></td>
											<td class="text-center"><?php echo $month['created_on_month']; ?></td>
											<td class="text-center"><?php echo $month['deleted_on_month']; ?></td>
											<td class="text-center"><?php $total_relative = $total_relative + $month['created_on_month'] - $month['deleted_on_month']; echo $total_relative; ?></td>
											<td class="text-center"><?php echo $month['total_account'];?></td>
											<td class="text-center"><?php $total_user2 = $total_user; $total_user+= $month['total_account']; echo $total_user;?></td>
											<td class="text-center">
                                                <?php
												if ($key > 0 && $month ['total_account'] > 0) {
													$key_down = $key - 1;
													$change = $total_user - $total_user2;
													if ($change > 0) {
														echo "<span class='icon-color-good'> <i class='fa fa-arrow-up'></i> {$change}</span>";
													} else if ($change < 0) {
														$change = abs ( $change );
														echo "<span class='icon-color-bad'> <i class='fa fa-arrow-down'></i> {$change}</span>";
													}
												}
												?>
                                                </td>
											<td class="text-center"><?php echo ($month['total_mobile'] != 0) ? $month['total_mobile'] : '';?></td>
										</tr>
                							<?php endforeach;?>
                							<tr
											style="background: #ffa5006b; font-weight: bold;">
											<td class="text-center"><?php echo __('Total'); ?></td>
											<td></td>
											<td></td>
											<td class="text-center"><?php echo $total_relative; ?></td>
											<td class="text-center"><?php echo __('Locked').": {$total_relative_account_lock}"; ?></td>
											<td class="text-center"><?php echo abs($total_user - $total_relative_account_lock); ?></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
            			<?php
															$from_date = $from_date ? $from_date : 0;
															$to_date = $to_date ? $to_date : 0;
															$ps_customer_id = $ps_customer_id ? $ps_customer_id : 0;
															$ps_workplace_id = $ps_workplace_id ? $ps_workplace_id : 0;
															$school_year_id = $school_year_id ? $school_year_id : 0;
															$ps_class_id = $ps_class_id ? $ps_class_id : 0;

															?>
    					<?php if (0 && myUser::isAdministrator() ||$sf_user->hasCredential ( 'PS_REPORT_MOBILE_APPS_EXPORT' )):?>
    					<article
							class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
							<div class="padding-5">
								<a class="btn btn-default"
									href="<?php echo url_for(@ps_mobile_apps).'/'.$school_year_id.'/'.$ps_customer_id.'/'.$ps_workplace_id.'/'.$ps_class_id.'/'.$from_date.'/'.$to_date.'/'; ?>cross_checking_export"
									id="btn-export"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
							</div>
						</article>
                    	<?php endif; ?>
            		</div>
				</div>
			</div>

		</article>

	</div>
</section>

<script>
$(document).ready(function() {
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
	if($('#relative_cross_checking_school_year_id').val() > 0){
		$.ajax({
			url: '<?php echo url_for('@ps_start_end_year') ?>',
	        type: "POST",
	        data: '&y_id=' + $('#relative_cross_checking_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
		    
	    	$('#relative_cross_checking_from_date').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
	    	$('#relative_cross_checking_to_date').datepicker('option', {minDate: $(msg).first().text(), maxDate: $(msg).last().text()});
	    });
	}
});

</script>