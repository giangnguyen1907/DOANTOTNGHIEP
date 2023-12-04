<style>
@page {
	size: A5 landscape;
}
body { margin: 0 }
#A5landscape {
  width: 210mm;
  height: 148mm;
  font-size:10px;
  margin: 0px;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
  font-family:sans-serif;
}
</style>
<div class="row page-break-after" id="A5landscape">
	<div class="col-md-12 col-xs-12 col-sm-12">
		<div>
    	
    	<?php

			if ($one_student == 'un') {
				include_partial ( 'psFeeReports/bieumau/_header_fee_student', array (
						'psClass' => $psClass,
						'receipt' => $receipt,
						'student' => $student,
						'type' => 'PT' ) );
			} else {
				include_partial ( 'psFeeReports/bieumau/_header_fee_receipt', array (
						'psClass' => $psClass,
						'receipt' => $receipt,
						'student' => $student,
						'type' => 'PT' ) );
			}
			?>
    		<div class="row">

				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-hover table-bordered no-footer no-padding" id="dt_basic">
					<thead>
					</thead>
					<tbody>
						<?php 
						// Danh sách các khoản phí;
						/**
						 * TÌM KHOẢN DƯ ĐẦU KỲ CỦA THÁNG TRƯỚC *
						 */
						// Dư đầu kỳ chuyển sang
						$du_dau_chuyen_sang = $data ['balance_last_month_amount'];
						$collectedAmount = $data ['collectedAmount'];
						?>
						<tr>
							<th colspan="6" class="text-left" style="background-color: #fff;"><b>1. <?php echo __('Balance of previous month')?>:</b></th>
							<th>&nbsp;</th>
							<th style="background-color: #fff;" class="text-right"><?php echo PreNumber::number_format($du_dau_chuyen_sang);?></th>
						</tr>
						
						<?php
						$collectedAmount = $data ['collectedAmount']; // so tien da nop
						$receivable_at = $receipt->getReceiptDate(); // Tháng thu phí
						
						$rs_current = array();
						$tong_cac_thang_cu = 0;
						$tong_du_kien_cac_thang_cu = 0;
						$hoantra = 0;
						$array_hoantra_phatsinh = array();
						//echo count($data ['receivable_student']);
						foreach ( $data ['receivable_student'] as $k => $r_s ) {
							$receiptDate  = $r_s->getRsReceiptDate();
							//if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {
							if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
								
								$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
								//echo $tong_cac_thang_cu;
								
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
									
									//echo $r_s->getSTitle()."___".$dutien.'<br>';
									
									//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $dutien;
									//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $r_s->getRsAmount () - $rs_amount;
								}
								$hoantra = $hoantra + $dutien;

							} else {
							
								array_push ( $rs_current, $r_s );
							
							}
						}
						// echo '<pre>';
						// print_r($array_hoantra_phatsinh);
						// echo '</pre>';
						?>

						<tr>
							<th colspan="5" class="text-left" style="background-color: #fff;"><b>2. <?php echo __('Estimated monthly fee')?>: <?php echo PsDateHelper::format_date($receivable_at, "MM-yyyy")?></b></th>
							<th>&nbsp;</th>
							<th class="text-right"></th>
							<th class="text-right"></th>
						</tr>

						<tr>
							<th class="text-center"><?php echo __('Month');?></th>
							<th class="text-center"><?php echo __('Name fees');?></th>
							<th class="text-right"><?php echo __('Price');?></th>
							<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
								data-placement="top"
								data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
							<th class="text-center" style="max-width: 70px;"><?php echo __('Discount amount');?></th>
							<th class="text-right"><?php echo __('Temporary money');?></th>
							<th class="text-center">Hoàn trả / phát sinh</th>
							<th class="text-center"><?php echo __('Note')?></th>
						</tr>
						
						<?php
						$tong_du_kien_thang_nay = 0;
						foreach ( $rs_current as $r_s ) {
							$receiptDate  = $r_s->getRsReceivableAt ();
							if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
						?>
						<tr>
							<td class="text-center"><?php echo false !== strtotime($receiptDate) ? PsDateHelper::format_date($receiptDate, "MM.yyyy") : '&nbsp;' ?></td>
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
							<td class="text-right"><?php echo ($r_s->getRsDiscountAmount() != 0) ? PreNumber::number_format($r_s->getRsDiscountAmount()) : '';?></td>
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
						}
						}
						foreach($array_hoantra_phatsinh as $khoanPhatSinh){
							if($khoanPhatSinh['tien'] !=0){
						?>
						<tr>
							<td></td>
							<td><?=$khoanPhatSinh['tieude']?></td>
							<td></td>
							<td><?=$khoanPhatSinh['sl']?></td>
							<td></td>
							<td></td>
							<td class="text-right"><?=PreNumber::number_format($khoanPhatSinh['tien'])?></td>
							<td><?=$khoanPhatSinh['ghichu']?></td>
						</tr>
						<?php } } ?>
						
						<tr>
							<td class="text-center"><b>A</b></td>
							<td colspan="4" class="text-right"><b><?php echo __('Total provisional')?>:</b></td>
							<td class="text-right"><b><?php echo PreNumber::number_format($tong_du_kien_thang_nay);?></b></td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td class="text-center"><b>B</b></td>
							<td colspan="4" class="text-right"><b>Số nợ kỳ trước:</b></td>
							<td class="text-right"><b><?php echo PreNumber::number_format($data['balance_last_month_amount']);?></b></td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td class="text-center"><b>C</b></td>
							<td colspan="4" class="text-right"><b>Hoàn trả / phát sinh kỳ trước:</b></td>
							<td class="text-right"><b><?php echo PreNumber::number_format($hoantra);?></b></td>
							<td class="text-right"><b>Dư thực tế tháng trước (B+C)</b></td>
							<td ><b><?php 
								$thuctethangtruoc = $data['balance_last_month_amount'] + $hoantra;
								echo PreNumber::number_format($thuctethangtruoc);?></b></td>
						</tr>
						<tr>
							<td class="text-center"><b>D</b></td>
							<td colspan="4" class="text-right"><b>Phải thu kỳ này (A+B+C):</b></td>
							<td class="text-right"><b><?php 
								$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
								echo PreNumber::number_format($phaithukynay);?></b></td>
							<td></td>
							<td class="text-right"></td>
						</tr>
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