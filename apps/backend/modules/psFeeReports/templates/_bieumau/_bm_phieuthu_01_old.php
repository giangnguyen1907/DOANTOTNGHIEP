<style>

@page {

	size: A4 landscape;

}

body { margin: 0 }



</style>

<div class="row page-break-after">

	<div class="col-md-6 col-xs-6 col-sm-6">



		<div>

    	

    	<?php



if ($one_student == 'un') {

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

													// Dư đầu kỳ chuyển sang

													$du_dau_chuyen_sang = $balance_last_month_amount = $data ['balance_last_month_amount'];

													$collectedAmount = $data ['collectedAmount']; // so tien da thanh toan

													$total_oldRsAmount = 0;

													$total_oldRsLateAmount = 0;

													$tong_cac_thang_cu = $tongtienthangnay = 0;

													$i = 1;

													?>

    						<tr>

								<td colspan="6" class="text-left"><b><?php echo __('Estimated fees this month')?></b></td>

							</tr>



							<tr>

								<td class="text-center" style="max-width: 15px"><b></b></td>

								<td class="text-center"><b><?php echo __('Name fees'); ?></b></td>

								<td class="text-center"><b><?php echo __('Price'); ?></b></td>

								<td class="text-center"><b><?php echo __('Quantily expected'); ?></b></td>

								<td class="text-center"><b><?php echo __('Temporary money'); ?></b></td>

								<td class="text-center"><b><?php echo __('Note'); ?></b></td>

							</tr>

    						

    						<?php



foreach ( $data ['receivable_student'] as $k => $rs ) {

											if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

												if ($rs->getRsReceivableId ()) {

													$title = $rs->getRTitle ();

												} elseif ($rs->getRsServiceId ()) {

													$title = $rs->getSTitle ();

												} elseif ($rs->getRsIsLate () == 1) {

													$title = $this->object->getContext ()

														->getI18N ()

														->__ ( 'Out late' );

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

								<td class="text-center"><?php echo $i; $i++;?></td>

								<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>

								<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>

								<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>

								<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>

								<td class="text-center"><?php echo $rs->getRsNote ();?></td>

							</tr>

    						

    						<?php



} else {

												$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();

											}

										}

										?>

    						

    						<tr>

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

    						<?php }?>

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

													$du_dau_chuyen_sang = $balance_last_month_amount = $data ['balance_last_month_amount'];

													$collectedAmount = $data ['collectedAmount']; // so tien da thanh toan

													$total_oldRsAmount = 0;

													$total_oldRsLateAmount = 0;

													$tong_cac_thang_cu = $tongtienthangnay = 0;

													$i = 1;

													?>

    						<tr>

								<td colspan="6" class="text-left"><b><?php echo __('Estimated fees this month')?></b></td>

							</tr>



							<tr>

								<td class="text-center" style="max-width: 15px"><b></b></td>

								<td class="text-center"><b><?php echo __('Name fees'); ?></b></td>

								<td class="text-center"><b><?php echo __('Price'); ?></b></td>

								<td class="text-center"><b><?php echo __('Quantily expected'); ?></b></td>

								<td class="text-center"><b><?php echo __('Temporary money'); ?></b></td>

								<td class="text-center"><b><?php echo __('Note'); ?></b></td>

							</tr>

    						

    						<?php



foreach ( $data ['receivable_student'] as $k => $rs ) {

											if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

												if ($rs->getRsReceivableId ()) {

													$title = $rs->getRTitle ();

												} elseif ($rs->getRsServiceId ()) {

													$title = $rs->getSTitle ();

												} elseif ($rs->getRsIsLate () == 1) {

													$title = $this->object->getContext ()

														->getI18N ()

														->__ ( 'Out late' );

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

								<td class="text-center"><?php echo $i; $i++;?></td>

								<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>

								<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>

								<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>

								<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>

								<td class="text-center"><?php echo $rs->getRsNote ();?></td>

							</tr>

    						

    						<?php



} else {

												$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();

											}

										}

										?>

    						

    						<tr>

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

    						<?php }?>

    					</tbody>

					</table>

				

				<div class="text-right" style="padding-right: 25px">

					<i>Hà nội, <?php echo __('Day').' '. date('d').' '. __('Month').' '.date('m').' '. __('Year').' '.date('Y');?></i>

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



