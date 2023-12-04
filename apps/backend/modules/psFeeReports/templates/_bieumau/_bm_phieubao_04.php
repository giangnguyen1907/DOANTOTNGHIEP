<div class="row page-break-after" id="A5">  
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
		<div><?php echo __('Estimated fees')?></div>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-bordered no-footer no-padding" id="dt_basic">
        <tbody>
          <?php
          		$stt = $total_oldRsAmount = 0;
				$total_oldRsLateAmount = 0;
				$tong_cac_thang_cu = $tongtienthangnay = $tong_du_kien_thang_nay = 0;
				$service_id = $rs_current = array ();
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
          
          <?php
			foreach ( $data ['receivable_student'] as $k => $r_s ) {
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {

					$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

					$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
					/*
					if ($r_s->getIsTypeFee () == 1) {
						$spentNumber = $spentNumber;
					}else{
						$spentNumber = '';
					}
					*/
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					
					//if ($r_s->getRsEnableRoll() == 0) { // Neu loai dich vu khong co dinh
						if ($r_s->getRsServiceId () > 0) {
							$service_id[$r_s->getRsServiceId ()] = array('amount'=>($rs_amount - $r_s->getRsAmount ()),'number'=>$spentNumber) ;
						}
					//}
					if($r_s->getRsIsLate() == 1){
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
						$service_id['is_late'] = array('amount'=>($r_s->getRsAmount ()),'number'=>$spentNumber) ;
					}
					
				} else { 
					array_push ( $rs_current, $r_s );
				}
			}
		?>
          
          <tr>
            <td class="text-center" style="width: 10mm;"><b><?php echo __('STT');?></b></td>
            <td class="text-center"><b><?php echo __('Name fees');?></b></td>
            <td class="text-center" style="width: 25mm;"><b><?php echo __('Price');?></b></td>
            <td class="text-center" style="width: 20mm;"><b><?php echo __('Quantily');?></b></td>
            <td class="text-center" style="width: 25mm;"><b><?php echo __('Fee expected');?></b></td>
            <td class="text-center" style="width: 15mm;"><b><?php echo __('SL SD');?></b></td>
            <td class="text-center" style="width: 20mm;"><b><?php echo __('So ngay nghi');?></b></td>
            <td class="text-center" style="width: 30mm;"><b><?php echo __('Giam tru');?></b></td>
            <td class="text-center" style="width: 35mm;"><?php echo __('Note')?></td>
          </tr>
          <?php
		  	foreach ( $rs_current as $k => $rs ) {
		  		$tien_thua = $number_use = $number_not_use = 0;
		  		$stt ++ ;
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
					if(isset($service_id[$rs->getRsServiceId ()]['amount'])){
						$tien_thua = $service_id[$rs->getRsServiceId ()]['amount'];
						if($rs->getIsTypeFee() == 1){
							$number_not_use = $service_id[$rs->getRsServiceId ()]['number'];
						}else{
							$number_use = $service_id[$rs->getRsServiceId ()]['number'];
						}
					}
				} else {
					$rs_amount = $rs->getRsAmount ();
				}
				$tongtienthangnay += $rs_amount;
			?>
          <tr>
            <td class="text-center"><?php echo $stt ?></td>
            <td><?php echo $title_sevice;?></td>
            <td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice());?></td>
            <td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>
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
            <td class="text-center"> <?php echo PreNumber::number_format($number_use); ?> </td>
            <td class="text-center"><?php if($number_not_use > 0) echo PreNumber::number_format($number_not_use) ?></td>
            <td class="text-right"><?php echo PreNumber::number_format($tien_thua); ?></td>
            <td class="text-center"><?php echo ($rs->getRsIsLate() == 1) ? __($rs->getRsNote()) : $rs->getRsNote();?></td>
          </tr>
          <?php }
          if(isset($service_id['is_late'])){
		  	$stt = $stt+1;
		  ?>
		  <tr>
		  	<td class="text-center"><?php echo $stt?></td>
		  	<td><?php echo __ ( 'Out late' );?><td>
		  	<td></td>
		  	<td></td>
		  	<td class="text-center"><?php echo $service_id['is_late']['number']?></td>
		  	<td></td>
		  	<td class="text-right"><?php echo PreNumber::number_format((0 - $service_id['is_late']['amount']))?></td>
		  	<td></td>
		  </tr>
		  <?php }
		  if($du_dau_chuyen_sang != 0){
		  	$stt = $stt+1;
		  ?>
		  <tr>
		  	<td class="text-center"><?php echo $stt?></td>
		  	<td><?php echo __('Balance previous month');?><td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td class="text-right"><?php echo PreNumber::number_format($du_dau_chuyen_sang);?></td>
		  	<td></td>
		  </tr>
		  <?php } 
		  $new_balance_last_month_amount = $collectedAmount - ($tong_cac_thang_cu - $du_dau_chuyen_sang);
		  if($collectedAmount <= 0){
		  	$stt = $stt+1;
		  ?>
		  <tr>
		  	<td class="text-center"><?php echo $stt?></td>
		  	<td><?php echo __('No thang truoc');?><td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td class="text-right"><?php echo PreNumber::number_format($new_balance_last_month_amount);?></td>
		  	<td><?php echo __('Old debt'); ?></td>
		  </tr>
		  <?php }?>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Total provisional') ?></b></td>
            <td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <?php
		  $tong_phai_nop = $tongtienthangnay - $new_balance_last_month_amount;
		  ?>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Excess number money') ?></b></td>
            <td class="text-right"><?php echo PreNumber::number_format($new_balance_last_month_amount);?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td class="text-center">
				<?php if ($new_balance_last_month_amount < 0) {echo __ ( 'Old debt' );} ?>
			</td>
          </tr>
          <tr>
            <td colspan="4" class="text-right"><b><i><?php echo __('Total amount payment') ?></i></b></td>
            <td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <?php if($receipt->getCollectedAmount () > 0){?>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
            <td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Amount relative') ?></b></td>
            <td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <?php }else{?>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Relative payment') ?></b></td>
            <td class="text-right"></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <tr>
            <td colspan="4" class="text-right"><b><?php echo __('Debt') ?></b></td>
            <td class="text-right"></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="text-right" style="padding-right: 25px"> <i><?php echo __('Day').' '. date('d').' '. __('Month').' '.date('m').' '. __('Year').' '.date('Y');?></i> </div>
      <div class="col-md-4 col-xs-4 col-sm-4">
        <div class="text-center chuky">
          <h5 style="margin-bottom: 3px"><?php echo __('Relative')?></h5>
          <p> <i><?php echo __('Ki ten')?></i> </p>
        </div>
      </div>
      <div class="col-md-4 col-xs-4 col-sm-4">
        <div class="text-center chuky">
          <h5 style="margin-bottom: 3px"><?php echo __('User create')?></h5>
          <p> <i><?php echo __('Ki ten')?></i> </p>
        </div>
      </div>
      <div class="col-md-4 col-xs-4 col-sm-4">
        <div class="text-center chuky">
          <h5 style="margin-bottom: 3px"><?php echo __('Cashier')?></h5>
          <p> <i><?php echo __('Ki ten')?></i> </p>
        </div>
      </div>
        
</div>
