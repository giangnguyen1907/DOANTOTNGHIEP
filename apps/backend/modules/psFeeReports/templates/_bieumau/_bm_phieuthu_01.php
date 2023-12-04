<style>
@page {
	size: A4 landscape;
}
body { margin: 0 }

</style>
<div class="row page-break-after">
	<div class="col-md-6 col-xs-6 col-sm-6">
		<div>
    	
    	<?php if ($one_student == 'un') {
			include_partial ( 'psFeeReports/bieumau/_header_fee_student', array (
					'psClass' => $psClass,
					'receipt' => $receipt,
					'student' => $student,
					'type' => 'PT',
					'lien' => 1 ) );
		} else {
			include_partial ( 'psFeeReports/bieumau/_header_fee_receipt', array (
					'psClass' => $psClass,
					'receipt' => $receipt,
					'student' => $student,
					'type' => 'PT',
					'lien' => 1 ) );
		}
		?>
    	
    		<div class="row">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-hover table-bordered no-footer no-padding" id="dt_basic">
					<thead>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$collectedAmount = $data ['collectedAmount']; // so tien da nop
						$receivable_at = $receipt->getReceiptDate(); // Tháng thu phí
						
						$rs_current = array();
						$tong_cac_thang_cu = 0;
						$tong_du_kien_cac_thang_cu = 0;
						$hoantra = 0;
						$array_hoantra_phatsinh = array();
						?>
    						<tr>
								<th colspan="6" class="text-left"><b><?php echo __('Estimated monthly fee')?>: <?php echo PsDateHelper::format_date($receivable_at, "MM-yyyy")?></b></th>
							</tr>

							<tr>
								
								<th class="text-center"></th>
								<th class="text-center"><?php echo __('Name fees');?></th>
								<th class="text-right"><?php echo __('Price');?></th>
								<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
									data-placement="top"
									data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
								<th class="text-right"><?php echo __('Temporary money');?></th>
								<th class="text-center">Hoàn trả / phát sinh</th>
								<th class="text-center"><?php echo __('Note')?></th>
							</tr>
    						
    						<?php
							
							foreach ( $data ['receivable_student'] as $k => $r_s ) {
								$receiptDate  = $r_s->getRsReceiptDate();
								if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
									
									$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
									
									if ($r_s->getRsServiceId () > 0) {
										$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

										$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
									} else {
										$rs_amount = $r_s->getRsAmount ();
									}

									$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
									$dutien = 0;
									if($r_s->getRsEnableRoll () == 0){
										$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
									}
									
									if ($r_s->getRsIsLate () == 1) {
										$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
										$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
										$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
										$array_hoantra_phatsinh[-1]['tieude'] = __( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
										$array_hoantra_phatsinh[-1]['ghichu'] = __($r_s->getRsNote());
										$dutien = $r_s->getRsAmount ();
									} else {
										
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = __($r_s->getRsNote());
									}
									$hoantra = $hoantra + $dutien;

								} else {
								
									array_push ( $rs_current, $r_s );
								
								}
							}
							?>
							<?php
							$tong_du_kien_thang_nay = 0;
							foreach ( $rs_current as $r_s ) {
								$receiptDate  = $r_s->getRsReceivableAt ();
								if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
							?>
        					<tr>
								<td class="text-center"><?php echo $i; $i++;?></td>
								<td>
									<?php
									if ($r_s->getRsReceivableId ()) {
										echo $r_s->getRTitle ();
									} elseif ($r_s->getRsServiceId ()) {
										echo $r_s->getSTitle ();
									} elseif ($r_s->getRsIsLate () == 1) {
										echo __ ( 'Out late' ) . '(' . format_date ( $receiptDate, "MM/yyyy" ) . ')';
									}
									?>
								</td>
								<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
								<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
								<td class="text-right">
									<?php
									// Phi du kien
									if ($r_s->getRsServiceId () > 0) {

										// Bỏ chỗ này vì không có tính giảm trừ % ở đây nữa
										//$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
										$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
										$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();

										// Thành viết lại chỗ này. lấy số tiền thực tế
										
										$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
									} else {

										// $rs_amount = $r_s->getRsAmount();
										$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

										$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
									}
									echo PreNumber::number_format ( $rs_amount );
									?>
								</td>
								<td class="text-right">
									<?php if(isset($array_hoantra_phatsinh[$r_s->getRsServiceId()])){
										echo PreNumber::number_format($array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien']);
										unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
									} ?>
								</td>
								<td><?php echo $r_s->getRsNote();?></td>
							</tr>
    						
							<?php
								
							// } else {
									// $tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
								// }
							// }
							?>
							
							<?php 
							} }
							foreach($array_hoantra_phatsinh as $khoanPhatSinh){
								if($khoanPhatSinh['tien'] !=0){
							?>
							<tr>
								<td></td>
								<td><?=$khoanPhatSinh['tieude']?></td>
								<td></td>
								<td><?=$khoanPhatSinh['sl']?></td>
								<td></td>
								<td class="text-right"><?=PreNumber::number_format($khoanPhatSinh['tien'])?></td>
								<td><?=$khoanPhatSinh['ghichu']?></td>
							</tr>
							<?php } } ?>
							
							<tr>
								<td class="text-center"><b>A</b></td>
								<td colspan="3" class="text-right"><b><?php echo __('Total provisional')?>:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($tong_du_kien_thang_nay);?></b></td>
								<td></td>
								<!--<td class="text-right"></td>-->
							</tr>
							<tr>
								<td class="text-center"><b>B</b></td>
								<td colspan="3" class="text-right"><b>Số nợ kỳ trước:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($data['balance_last_month_amount']);?></b></td>
								<td></td>
								<td class="text-right"></td>
							</tr>
							<tr>
								<td class="text-center"><b>C</b></td>
								<td colspan="3" class="text-right"><b>Hoàn trả / phát sinh kỳ trước:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($hoantra);?></b></td>
								<td class="text-right"><b>Dư thực tế tháng trước (B+C)</b></td>
								<td ><b><?php 
									$thuctethangtruoc = $data['balance_last_month_amount'] + $hoantra;
									echo PreNumber::number_format($thuctethangtruoc);?></b></td>
							</tr>
							<tr>
								<td class="text-center"><b>D</b></td>
								<td colspan="3" class="text-right"><b>Phải thu kỳ này (A+B+C):</b></td>
								<td class="text-right"><b><?php 
									$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
									echo PreNumber::number_format($phaithukynay);?></b></td>
								<td></td>
								<td class="text-right"></td>
							</tr>
    						
    						<!--<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Total provisional') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
								<td></td>
							</tr>
    						<?php
								// echo $tong_cac_thang_cu.'___'.$data ['totalAmountReceivableAt'];

								// $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt'] - $du_dau_chuyen_sang);

								$tien_thua_thang_truoc = $collectedAmount - ($tong_cac_thang_cu - $du_dau_chuyen_sang);
								$phi_nop_muon = $data ['psConfigLatePayment'];
								$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
								?>
    						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Price previous month') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
								<td></td>
							</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Late payment') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
								<td></td>
							</tr>
    						<?php }?>
    						
    						<tr>
								<td colspan="4" class="text-right"><b><i><?php echo __('Total amount payment detail') ?></i></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
								<td></td>
							</tr>
    						<?php if($receipt->getCollectedAmount () > 0){?>
        						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
								<td></td>
							</tr>

							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Amount relative') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
								<td></td>
							</tr>
    						<?php }else{?>
    							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
								<td class="text-right"></td>
								<td></td>
							</tr>

							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Amount relative') ?></b></td>
								<td class="text-right"></td>
								<td></td>
							</tr>
    						<?php }?>-->
    					</tbody>
					</table>
				
				<div class="text-right" style="padding-right: 25px">
					<i><?php echo __('Day').' '. date('d').' '. __('Month').' '.date('m').' '. __('Year').' '.date('Y');?></i>
				</div>

				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('Relative')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>
				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('User create')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>
				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('Cashier admin')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

			</div>
		</div>
	</div>

	<!-----------------------------------------Kết thúc liên 1--------------------------------------------->

	<div class="col-md-6 col-xs-6 col-sm-6">

		<div>
    	
    	<?php
		if ($one_student == 'un') {
				include_partial ( 'psFeeReports/bieumau/_header_fee_student', array (
						'psClass' => $psClass,
						'receipt' => $receipt,
						'student' => $student,
						'type' => 'PT',
						'lien' => 2 ) );
			} else {
				include_partial ( 'psFeeReports/bieumau/_header_fee_receipt', array (
						'psClass' => $psClass,
						'receipt' => $receipt,
						'student' => $student,
						'type' => 'PT',
						'lien' => 2 ) );
			}
		?>
    	
    		<div class="row">

				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-hover table-bordered no-footer no-padding" id="dt_basic">

						<thead>
						</thead>
						<tbody>
        					<?php
						// Dư đầu kỳ chuyển sang
						// $du_dau_chuyen_sang = $balance_last_month_amount = $data ['balance_last_month_amount'];
						// $collectedAmount = $data ['collectedAmount']; // so tien da thanh toan
						// $total_oldRsAmount = 0;
						// $total_oldRsLateAmount = 0;
						// $tong_cac_thang_cu = $tongtienthangnay = 0;
						// $i = 1;
						
						$i = 1;
						$collectedAmount = $data ['collectedAmount']; // so tien da nop
						$receivable_at = $receipt->getReceiptDate(); // Tháng thu phí
						
						$rs_current = array();
						$tong_cac_thang_cu = 0;
						$tong_du_kien_cac_thang_cu = 0;
						$hoantra = 0;
						$array_hoantra_phatsinh = array();
						?>
    						<tr>
								<!--<td colspan="6" class="text-left"><b><?php echo __('Estimated fees this month')?></b></td>-->
								<th colspan="6" class="text-left"><b><?php echo __('Estimated monthly fee')?>: <?php echo PsDateHelper::format_date($receivable_at, "MM-yyyy")?></b></th>
							</tr>

							<tr>
								<!--<td class="text-center" style="max-width: 15px"><b></b></td>
								<td class="text-center"><b><?php echo __('Name fees'); ?></b></td>
								<td class="text-center"><b><?php echo __('Price'); ?></b></td>
								<td class="text-center"><b><?php echo __('Quantily expected'); ?></b></td>
								<td class="text-center"><b><?php echo __('Temporary money'); ?></b></td>
								<td class="text-center"><b><?php echo __('Note'); ?></b></td>-->
								
								<th class="text-center"></th>
								<th class="text-center"><?php echo __('Name fees');?></th>
								<th class="text-right"><?php echo __('Price');?></th>
								<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
									data-placement="top"
									data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
								<th class="text-right"><?php echo __('Temporary money');?></th>
								<th class="text-center">Hoàn trả / phát sinh</th>
								<th class="text-center"><?php echo __('Note')?></th>
							</tr>
    						
    						<?php
							
							foreach ( $data ['receivable_student'] as $k => $r_s ) {
								$receiptDate  = $r_s->getRsReceiptDate();
								if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
									
									$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
									
									if ($r_s->getRsServiceId () > 0) {
										$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

										$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
									} else {
										$rs_amount = $r_s->getRsAmount ();
									}

									$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
									$dutien = 0;
									if($r_s->getRsEnableRoll () == 0){
										$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
									}
									
									if ($r_s->getRsIsLate () == 1) {
										$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
										$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
										$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
										$array_hoantra_phatsinh[-1]['tieude'] = __( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
										$array_hoantra_phatsinh[-1]['ghichu'] = __($r_s->getRsNote());
										$dutien = $r_s->getRsAmount ();
									} else {
										
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
										$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = __($r_s->getRsNote());
									}
									$hoantra = $hoantra + $dutien;

								} else {
								
									array_push ( $rs_current, $r_s );
								
								}
							}
							?>
							<?php
							$tong_du_kien_thang_nay = 0;
							foreach ( $rs_current as $r_s ) {
								$receiptDate  = $r_s->getRsReceivableAt ();
								if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
							?>
        					<tr>
								<td class="text-center"><?php echo $i; $i++;?></td>
								<td>
									<?php
									if ($r_s->getRsReceivableId ()) {
										echo $r_s->getRTitle ();
									} elseif ($r_s->getRsServiceId ()) {
										echo $r_s->getSTitle ();
									} elseif ($r_s->getRsIsLate () == 1) {
										echo __ ( 'Out late' ) . '(' . format_date ( $receiptDate, "MM/yyyy" ) . ')';
									}
									?>
								</td>
								<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
								<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
								<td class="text-right">
									<?php
									// Phi du kien
									if ($r_s->getRsServiceId () > 0) {

										// Bỏ chỗ này vì không có tính giảm trừ % ở đây nữa
										//$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
										$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
										$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();

										// Thành viết lại chỗ này. lấy số tiền thực tế
										
										$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
									} else {

										// $rs_amount = $r_s->getRsAmount();
										$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

										$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
									}
									echo PreNumber::number_format ( $rs_amount );
									?>
								</td>
								<td class="text-right">
									<?php if(isset($array_hoantra_phatsinh[$r_s->getRsServiceId()])){
										echo PreNumber::number_format($array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien']);
										unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
									} ?>
								</td>
								<td><?php echo $r_s->getRsNote();?></td>
							</tr>
    						
							<?php
								
							// } else {
									// $tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
								// }
							// }
							?>
							
							<?php 
							} }
							foreach($array_hoantra_phatsinh as $khoanPhatSinh){
								if($khoanPhatSinh['tien'] !=0){
							?>
							<tr>
								<td></td>
								<td><?=$khoanPhatSinh['tieude']?></td>
								<td></td>
								<td><?=$khoanPhatSinh['sl']?></td>
								<td></td>
								<td class="text-right"><?=PreNumber::number_format($khoanPhatSinh['tien'])?></td>
								<td><?=$khoanPhatSinh['ghichu']?></td>
							</tr>
							<?php } } ?>
							
							<tr>
								<td class="text-center"><b>A</b></td>
								<td colspan="3" class="text-right"><b><?php echo __('Total provisional')?>:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($tong_du_kien_thang_nay);?></b></td>
								<td></td>
								<!--<td class="text-right"></td>-->
							</tr>
							<tr>
								<td class="text-center"><b>B</b></td>
								<td colspan="3" class="text-right"><b>Số nợ kỳ trước:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($data['balance_last_month_amount']);?></b></td>
								<td></td>
								<td class="text-right"></td>
							</tr>
							<tr>
								<td class="text-center"><b>C</b></td>
								<td colspan="3" class="text-right"><b>Hoàn trả / phát sinh kỳ trước:</b></td>
								<td class="text-right"><b><?php echo PreNumber::number_format($hoantra);?></b></td>
								<td class="text-right"><b>Dư thực tế tháng trước (B+C)</b></td>
								<td ><b><?php 
									$thuctethangtruoc = $data['balance_last_month_amount'] + $hoantra;
									echo PreNumber::number_format($thuctethangtruoc);?></b></td>
							</tr>
							<tr>
								<td class="text-center"><b>D</b></td>
								<td colspan="3" class="text-right"><b>Phải thu kỳ này (A+B+C):</b></td>
								<td class="text-right"><b><?php 
									$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
									echo PreNumber::number_format($phaithukynay);?></b></td>
								<td></td>
								<td class="text-right"></td>
							</tr>
    						
    						<!--<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Total provisional') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
								<td></td>
							</tr>
    						<?php
								// echo $tong_cac_thang_cu.'___'.$data ['totalAmountReceivableAt'];

								// $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt'] - $du_dau_chuyen_sang);

								$tien_thua_thang_truoc = $collectedAmount - ($tong_cac_thang_cu - $du_dau_chuyen_sang);
								$phi_nop_muon = $data ['psConfigLatePayment'];
								$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
								?>
    						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Price previous month') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
								<td></td>
							</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Late payment') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
								<td></td>
							</tr>
    						<?php }?>
    						
    						<tr>
								<td colspan="4" class="text-right"><b><i><?php echo __('Total amount payment detail') ?></i></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
								<td></td>
							</tr>
    						<?php if($receipt->getCollectedAmount () > 0){?>
        						<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
								<td></td>
							</tr>

							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Amount relative') ?></b></td>
								<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
								<td></td>
							</tr>
    						<?php }else{?>
    							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
								<td class="text-right"></td>
								<td></td>
							</tr>

							<tr>
								<td colspan="4" class="text-right"><b><?php echo __('Amount relative') ?></b></td>
								<td class="text-right"></td>
								<td></td>
							</tr>
    						<?php }?>-->
    					</tbody>
					</table>
				
				<div class="text-right" style="padding-right: 25px">
					<i><?php echo __('Day').' '. date('d').' '. __('Month').' '.date('m').' '. __('Year').' '.date('Y');?></i>
				</div>

				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('Relative')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>
				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('User create')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>
				<div class="col-md-4 col-xs-4 col-sm-4">
					<div class="text-center chuky">
						<h5 style="margin-bottom: 3px"><?php echo __('Cashier admin')?></h5>
						<p>
							<i><?php echo __('Ki ten')?></i>
						</p>
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

			</div>
		</div>
	</div>
</div>

