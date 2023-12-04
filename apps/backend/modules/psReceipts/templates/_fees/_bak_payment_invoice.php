<?php
// Tong so tiền dự kiến thu của tháng đang chọn xem
$getTotalAmountOfMonth = $totalAmountReceivableAt ? $totalAmountReceivableAt->getTotalAmount() : 0;

//tong_tien_thuc_te_phai_nop_cua_phieu_gan_day_nhat
$tong_tien_thuc_te_phai_nop_cu = $totalAmount -  $getTotalAmountOfMonth;

$tong_du_kien_thang_nay = sfConfig::get ( 'global_tong_du_kien_thang_nay');
?>
<input type="hidden" name="id" id="id" value="<?php echo $receipt->getId();?>" />
<input type="hidden" name="rid" id="rid" value="<?php echo $receipt->getId();?>" />
<input type="hidden" name="action_type" id="action_type" value="" />
<div class="custom-scroll table-responsive">
	<table class="table table-bordered table-hover no-footer no-padding">
		<tbody>
			<tr>
				<td><label><?php echo __('Invoice no')?></label></td>
				<td class="text-right">
					<input type="hidden" name="rec[receipt_id]" id="rec_receipt_id" value="<?php echo $receipt->getId();?>"/>
    				<input type="hidden" name="rec[receipt_no]" id="rec_receipt_no" value="<?php echo $receipt->getReceiptNo();?>"/>
    				<?php echo $receipt->getReceiptNo();?>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Payment status')?></label></td>
				<td><?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $receipt->getPaymentStatus())) ?></td>
			</tr>
			
			<?php if ($receiptOfStudentNextMonth):?>			
			<tr>
				<td><label><strong>
				1. <?php echo __('Actual monthly fee')?>: <?php echo format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy");?></strong></label></td>
				<td class="text-right">	
				<?php
				
				//echo ($totalAmountReceivableAt ? PreNumber::number_format($totalAmountReceivableAt->getTotalAmount()) : 0);
				
				// Lấy 2. Dự kiến các khoản phí tháng này				
				echo PreNumber::number_format($tong_du_kien_thang_nay);
				?>
				</td>
			</tr>
			<?php else:?>		
			
			<tr>
				<td><label><strong>
				2. <?php echo __('Estimated monthly fee')?>: <?php echo format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy");?></strong></label></td>
				<td class="text-right">
				<?php //echo PreNumber::number_format($ps_fee_reports->getReceivable());?>
				<?php
				// Dự kiến các khoản thu của tháng đang chọn
				echo ($getTotalAmountOfMonth ? PreNumber::number_format($getTotalAmountOfMonth):0);?>
				</td>
			</tr>
			
			<?php endif;?>
			
			
			<tr>
				<td><label><strong>
				3. <?php echo __('Estimated monthly fee')?>: <?php echo format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy");?></strong></label></td>
				<td class="text-right">
				<?php
				// Dự kiến các khoản thu của tháng đang chọn
				echo PreNumber::number_format($tong_du_kien_thang_nay);
				?>
				</td>
			</tr>
			
			<tr>
				<td><label><?php echo __('Balance last month reality')?></label></td>
				<td class="text-right">
				<?php
				
				//$newBalanceAmont = $collectedAmount - ($totalAmount -  $getTotalAmountOfMonth);
				
				//$tong_tien_thuc_te_phai_nop_cu = $totalAmount -  $getTotalAmountOfMonth;
				
				// Dư tháng cũ thực tế = Số tiền tháng trước đã nộp - số tiền phải thu thực tế của tháng trước
				/*
				if (myUser::isAdministrator()):
					echo 'Tổng của phiếu: '.$totalAmount.'<br/>';// tháng đang chạy + các tháng trước chưa thanh toán
					echo 'Dự kiến thu của riêng 1 tháng: '.$getTotalAmountOfMonth.'<br/>';
					echo 'Tổng thực tế của tháng trước: '.$tong_tien_thuc_te_phai_nop_cu.'<br/>';
					echo 'Đã nộp tháng trước: '.$collectedAmount.'<br/>';					
				endif;
				*/
				$newBalanceAmont = $collectedAmount - $tong_tien_thuc_te_phai_nop_cu;
				?>
				<?php if ($newBalanceAmont < 0):?>
				<code class="txt-color-redLight"><?php echo PreNumber::number_format($newBalanceAmont);?></code>
				<?php else:?>
				<?php echo PreNumber::number_format($newBalanceAmont);?>
				<?php endif;?>
				</td>
			</tr>
			
			<div id="ic-loading" style="display: none;">
            	<i class="fa fa-spinner fa-2x fa-spin text-success" style="padding:3px;"></i><?php echo __('Loading...')?>
            </div>
			<tr id="load-amount">
			<?php
			$priceLatePayment = 0;
			if($receipt->getPaymentStatus() == PreSchool::NOT_ACTIVE){
    			if ($psLatePayment){
        			$priceLatePayment = $psLatePayment->getPrice() + $pricePaymentLate;
    			}
			}else{
			    $priceLatePayment = $pricePaymentLate;
			}
			?>
			<?php include_partial('psReceipts/fees/_load_amount', array('ps_fee_reports' => $ps_fee_reports,'priceLatePayment' => $priceLatePayment));?>
			</tr>			
			<?php if($receipt->getPaymentStatus() == PreSchool::NOT_ACTIVE):?>
			<tr>
				<td><label><?php echo __('Collected amount')?></label></td>
				<td class="text-right">
					<div class="form-group" style="margin-bottom:0px;">
						<div class="col-md-12">
						<input type="number" min="0" max="999999999999" name="rec[collected_amount]" required class="form-control text-right" id="rec_collected_amount" value="" placeholder="<?php echo __('Enter the amount')?>" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Excess money')?></label></td>
				<td class="text-right">
					<div class="form-group" style="margin-bottom:0px;">
						<div class="col-md-12">
							<span id="excess_money"></span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Relative payment')?></label></td>
				<td>
					<div class="form-group" style="margin-bottom:0px;">
						<div class="col-md-12">
							<input type="text" name="rec[relative_name]" required class="form-control" id="rec_relative_name" value="" placeholder="<?php echo __('Enter the relative payment')?>" />
							<select name="rec[relative_id]" class="select2" id="receipt_relative_id">
								<option><?php echo __('or select are here')?></option>
								<?php foreach ($relatives as $relative):?>
								<option value="<?php echo $relative->getTitle()?> <?php echo $relative->getFullName()?>"><?php echo $relative->getTitle()?> <?php echo $relative->getFullName()?></option>
								<?php endforeach;?>
							</select>							
						</div>
						<div class="col-md-12">
							<div class="input-group">
								<input type="text" name="rec[payment_date]" class="form-control" id="receipt_payment_date" class="form-control" data-mask="99/99/9999" data-mask-placeholder="-">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<?php //print_r(PreSchool::loadPsPaymentType ());?>
			<tr>
				<td><label><?php echo __('Payment type')?></label></td>
				<td>
					<div class="form-group" style="margin-bottom:0px;">
						<div class="col-md-12">
							<select name="rec[payment_type]" class="select2">
								<?php foreach (PreSchool::loadPsPaymentType () as $key=> $type):?>
								<option value="<?php echo $key?>"><?php echo  __($type) ?></option>
								<?php endforeach;?>
							</select>							
						</div>
					</div>
				</td>
			</tr>
			
			<tr>
				<td><label><?php echo __('Cashier')?></label></td>
				<td class="text-right">
					<div class="form-group">
						<div class="col-md-12" style="margin-bottom:0px;">
						<input type="text" maxlength="255" name="rec[cashier]" class="form-control" id="rec_cashier" value="" placeholder="<?php echo __('Enter the cashier')?>" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Note')?></label></td>
				<td class="text-right">
					<div class="form-group">
						<div class="col-md-12" style="margin-bottom:0px;">
						<input type="text" maxlength="255" name="rec[note]" class="form-control" id="rec_note" value="" placeholder="<?php echo __('Enter the note')?>" />
						</div>
					</div>
				</td>
			</tr>
			
			<?php else:?>
			<tr>
				<td><label><strong><?php echo __('Collected amount')?></strong></label></td>
				<td class="text-right">
					<?php echo PreNumber::number_format($receipt->getCollectedAmount());?>					
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Excess money')?></strong></label></td>
				<td class="text-right">
					<span id="excess_money"><?php echo PreNumber::number_format($receipt->getBalanceAmount());?></span>
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Relative payment')?></strong></label></td>
				<td class="text-right">
					<span id="excess_money"><?php echo $receipt->getPaymentRelativeName();?></span>
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Payment date')?></strong></label></td>
				<td class="text-right">
					<?php if (false !== strtotime($receipt->getPaymentDate())) echo format_date($receipt->getPaymentDate(), "dd-MM-yyyy");?>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Payment type')?></label></td>
				<td>
					<?php echo __(PreSchool::loadPsPaymentType()[$receipt->getPaymentType()]);?>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Cashier')?></label></td>
				<td>
					<?php echo $receipt->getCashierName();?>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Note')?></label></td>
				<td>
					<?php echo $receipt->getNote();?>
				</td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
