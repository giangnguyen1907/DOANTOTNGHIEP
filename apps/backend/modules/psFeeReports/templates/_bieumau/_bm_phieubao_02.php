<style>
@page {
	size: A5;
}
body { margin: 0 }
#A5 {
  width: 148mm;
  height:209mm;
  font-size:10px;
  margin: 0;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
  font-family:sans-serif;
}
</style>
<div class="row page-break-after" id="A5">
	<div class="col-md-12 col-xs-12 col-sm-12">

		<div>
    	
<?php
if ($one_student == 'un') {
	include_partial ( 'psFeeReports/bieumau/_header_fee_student', array (
		'psClass' => $psClass,
		'receipt' => $data ['ps_fee_reports'],
		'student' => $student,
		'type' => 'PB' ) );
} else {
	include_partial ( 'psFeeReports/bieumau/_header_fee_receipt', array (
			'psClass' => $psClass,
			'receipt' => $data ['ps_fee_reports'],
			'student' => $student,
			'type' => 'PB' ) );
}
?>
        
    		<div class="row">

				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-hover table-bordered no-footer no-padding" id="dt_basic">

						<thead>
						</thead>
						<tbody>
        					<?php

							$total_oldRsAmount = 0;
							$total_oldRsLateAmount = 0;
							$tong_cac_thang_cu = $tongtienthangnay = $tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;
							$rs_current = array ();
							?>
<?php 
// Danh sách các khoản phí;
/**
 * TÌM KHOẢN DƯ ĐẦU KỲ CỦA THÁNG TRƯỚC *
 */
// Dư đầu kỳ chuyển sang
$du_dau_chuyen_sang = $data ['balance_last_month_amount'];
$collectedAmount = $data ['collectedAmount'];
?>
    						<tr style="background-color: #fff;">
								<td colspan="5" class="text-left"
									style="background-color: #fff; border-right: none;"><b>1. <?php echo __('Balance of previous month')?>:</b></td>
								<td style="background-color: #fff; border-left: none;">&nbsp;</td>
								<td
									style="background-color: #fff; border-left: none; border-right: none;"
									class="text-right"></td>
								<td style="background-color: #fff;" class="text-right"><?php echo PreNumber::number_format($du_dau_chuyen_sang);?></td>
								<td style="background-color: #fff;">&nbsp;</td>
							</tr>

							<tr style="background-color: #fff;">
								<td colspan="9" class="text-left"
									style="background-color: #fff;"><b>2. <?php echo __('Payment fees for previous month')?></b></td>
							</tr>

							<tr>
								<td class="text-center" width="50px"><b><?php echo __('Month');?></b></td>
								<td class="text-center"><b><?php echo __('Name fees');?></b></td>
								<td class="text-center"><b><?php echo __('Price');?></b></td>
								<td class="text-center" width="50px"><b><?php echo __('D.Kiến');?></b></td>
								<td class="text-center"><b><?php echo __('GT');?></b></td>
								<td class="text-center"><b><?php echo __('Temporary money');?></b></td>
								<td class="text-center"><b><?php echo __('SD');?></b></td>
								<td class="text-center"><b><?php echo __('Actual costs');?></b></td>
								<td class="text-center"><?php echo __('Note')?></td>
							</tr>
    						
        					<?php
													foreach ( $data ['receivable_student'] as $k => $r_s ) {
														if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {

															$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

															$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

															if ($r_s->getRsReceivableId ()) {
																$title_sevice = $r_s->getRTitle ();
															} elseif ($r_s->getRsServiceId ()) {
																$title_sevice = $r_s->getSTitle ();
															} elseif ($r_s->getRsIsLate () == 1) {
																$title_sevice = __ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
															}

															?>
    					        <tr>
								<td class="text-center"><?php echo date('m-Y', $month_prev ) ?></td>
								<td><?php echo $title_sevice;?></td>
								<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice());?></td>
								<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
								<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsDiscountAmount());?></td>
								
        							
        							<?php
															if ($r_s->getRsServiceId () > 0) {
																$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
																$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
															} else {
																$rs_amount = $r_s->getRsAmount ();
															}
															if (date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
																$rs_amount = 0;
															}
															$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;
															$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
															?>
        							
        							<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
								<td class="text-center">
        							<?php
															if ($r_s->getRsIsLate () == 1) {
																echo ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
															} else {
																echo PreNumber::number_format ( $spentNumber );
															}
															?></td>
								<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsAmount());?></td>
								<td><?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?></td>
							</tr>
        					        
        					<?php

} else {
															array_push ( $rs_current, $r_s );
														}
													}
													?>
    						<tr>
								<td colspan="5" class="text-right"><b><?php echo __('Total expected')?>:</b></td>
								<td class="text-right"
									title="<?php echo __('Expected revenue')?>"><?php echo PreNumber::number_format($tong_du_kien_cac_thang_cu);?></td>

								<td class="text-right"></td>
								<td class="text-right"><?php echo PreNumber::number_format($tong_cac_thang_cu);?></td>
								<td style="background-color: #fff; border-left: none;"><b><?php echo __('Tổng thực tế')?></b></td>
							</tr>

							<tr>
								
								<td colspan="7" class="text-right"><b><?php echo __('Paid')?>:</b></td>
								<td class="text-right">
                			    <?php echo PreNumber::number_format($collectedAmount);?>
                			    </td>
								<td style="background-color: #fff; border-left: none;">&nbsp;</td>
							</tr>

							<tr>
								<td colspan="7" class="text-right"><b><?php echo __('Balance reality')?>:</b></td>
								<td class="text-right">
                			    <?php
																							// Du thuc te thang trước
																							$new_balance_last_month_amount = $collectedAmount - ($tong_cac_thang_cu - $du_dau_chuyen_sang);
																							echo PreNumber::number_format ( $new_balance_last_month_amount );
																							?>
                			    </td>
								<td style="background-color: #fff; border-left: none;">&nbsp;</td>
							</tr>

							<tr>
								<td colspan="9" class="text-left"><b>3. <?php echo __('Estimated fees this month')?></b></td>
							</tr>

							<tr>
								<td class="text-center"><b><?php echo __('Month');?></b></td>
								<td class="text-center"><b><?php echo __('Name fees');?></b></td>
								<td class="text-center"><b><?php echo __('Price');?></b></td>
								<td class="text-center"><b><?php echo __('D.Kiến');?></b></td>
								<td class="text-center"><b><?php echo __('GT');?></b></td>
								<td class="text-center"><b><?php echo __('Temporary money');?></b></td>
								<td colspan="3" class="text-center"><?php echo __('Note')?></td>
							</tr>
    						
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

											$month_prev = PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () );

											if ($rs->getRsReceivableId ()) {
												$title_sevice = $rs->getRTitle ();
											} elseif ($rs->getRsServiceId ()) {
												$title_sevice = $rs->getSTitle ();
											} elseif ($rs->getRsIsLate () == 1) {
												$title_sevice = __ ( 'Out late' ) . '(' . format_date ( $rs->getRsReceivableAt (), "MM/yyyy" ) . ')';
											}

											if ($rs->getRsServiceId () > 0) {
												$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
												$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
											} else {
												$rs_amount = $rs->getRsAmount ();
											}
											$tongtienthangnay += $rs_amount;
											?>
    						<tr>
								<td class="text-center"><?php echo date('m-Y', $month_prev ) ?></td>
								<td><?php echo $title_sevice;?></td>
								<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice());?></td>
								<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber());?></td>
								<td class="text-right"><?php echo PreNumber::number_format($rs->getRsDiscountAmount());?></td>
								<?php
											if ($rs->getRsServiceId () > 0) {

												$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();

												$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
												$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
											} else {
												$rs_amount = $rs->getRsAmount ();
												$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
											}
											?>
    							<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
								<td colspan="3"></td>
							</tr>
    						
    						<?php }?>
    						
    						<tr>
								<td class="text-center"><b>A</b></td>
								<td colspan="4" class="text-right"><b><?php echo __('Total provisional') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
								<td colspan="3"></td>
							</tr>
    						<?php	
							$tong_phai_nop = $tongtienthangnay - $new_balance_last_month_amount;
							?>
    						<tr>
								<td class="text-center"><b>B</b></td>
								<td colspan="4" class="text-right"><b><?php echo __('Price previous month') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($new_balance_last_month_amount);?></td>
								<td colspan="3" class="text-center">
    							<?php

if ($new_balance_last_month_amount < 0) {
												echo 'Nợ cũ';
											}
											?>
    							</td>
							</tr>
    						
    						<tr>
								<td class="text-center"><b>C</b></td>
								<td colspan="4" class="text-right"><b><i><?php echo __('Total amount payment') ?></i></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
								<td colspan="3"></td>
							</tr>

						</tbody>
					</table>
				
				<div class="text-right" style="padding-right: 25px">
					<i><?php echo __('Day').' '. date('d').' '. __('Month').' '.date('m').' '. __('Year').' '.date('Y');?></i>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 15px"></div>

			</div>
		</div>
	</div>
</div>