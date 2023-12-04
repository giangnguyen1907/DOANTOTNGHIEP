<?php
$tong_du_kien_thang_nay = sfConfig::get ( 'global_tong_du_kien_thang_nay' );
$new_balance_last_month_amount = sfConfig::get ( 'global_new_balance_last_month_amount' );
$hoantra = sfConfig::get ( 'global_hoantra' );
$new_balance_last_month_amount = $new_balance_last_month_amount + $hoantra;

$tien_phai_nop = $receipt->getBalanceAmount();

?>


<input type="hidden" name="id" id="id"
	value="<?php echo $receipt->getId();?>" />
<input type="hidden" name="rid" id="rid"
	value="<?php echo $receipt->getId();?>" />
<input type="hidden" name="action_type" id="action_type" value="" />
<div class="custom-scroll table-responsive">
	<table class="table table-bordered table-hover no-footer no-padding">
		<tbody>
			
			<tr>
				<td><label><?php echo __('Invoice no')?></label></td>
				<td class="text-right"><input type="hidden" name="rec[receipt_id]"
					id="rec_receipt_id" value="<?php echo $receipt->getId();?>" /> <input
					type="hidden" name="rec[receipt_no]" id="rec_receipt_no"
					value="<?php echo $receipt->getReceiptNo();?>" />
    				<?php echo $receipt->getReceiptNo();?>
				</td>
			</tr>
			
			<tr>
				<td><label><?php echo __('Payment status')?></label></td>
				<td class="text-right"><?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $receipt->getPaymentStatus())) ?></td>
			</tr>
			
			<tr>
				<td><label><?php echo __('Payment date')?></label></td>
				<td class="text-right"><?php echo $receipt->getPaymentDate(); ?></td>
			</tr>
			
			<div id="ic-loading" style="display: none;">
				<i class="fa fa-spinner fa-2x fa-spin text-success"
					style="padding: 3px;"></i><?php echo __('Loading...')?>
            </div>
			<tr id="load-amount">
			<?php
			$priceLatePayment = 0;
			$chietkhau = 0;
			// Trạng thái thanh toán
			if ($receipt->getPaymentStatus () == PreSchool::NOT_ACTIVE) {
				// Ten thu ngan
				$cashier = $sf_user->getGuardUser()->getFirstName().' '.$sf_user->getGuardUser()->getLastName();
								
				if ($psLatePayment) {
					$priceLatePayment = $psLatePayment->getPrice () + $pricePaymentLate;
				}
				$chietkhau = $receipt->getChietkhau();
			} else {
				
				$cashier = $receipt->getCashierName();
				
				$chietkhau = $receipt->getChietkhau();

				$priceLatePayment = $pricePaymentLate;
			}
			?>
			<?php include_partial('psReceipts/fees/_load_amount', array('priceLatePayment' => $priceLatePayment,'receipt'=>$receipt));?>
			</tr>
			<?php 

			if($receipt->getPaymentStatus() == PreSchool::NOT_ACTIVE){
				$tien_phai_nop = $ps_fee_reports->getReceivable () + $priceLatePayment + $hoantra - $receipt->getChietkhau();
			}
			if(1==1){

				$tien_phai_nop = $receipt->getBalanceAmount();
			
			?>
			<tr>
				<td><label>Đã chiết khấu</label></td>
				<td class="text-right">
					<b><?=PreNumber::number_format ($receipt->getChietkhau(), 0, '.', '.' );?></b>
				</td>
			</tr>
			<tr>
				<td><label>Đã thu tiền</label></td>
				<td class="text-right">
					<b><?=PreNumber::number_format ($receipt->getCollectedAmount(), 0, '.', '.' );?></b>
				</td>
			</tr>
			<tr>
				<td><label>Cần thanh toán</label></td>
				<td class="text-right">
					<b><?=PreNumber::number_format ($tien_phai_nop, 0, '.', '.' );?></b>
				</td>
			</tr>
			
			<tr>
				<td><label>Chiết khấu</label></td>
				<td class="text-right">
					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-md-12">
							<input type="number" max="999999999999" name="rec[chietkhau]" class="form-control text-right" id="rec_chietkhau" onchange="checkChietKhau()" value="" placeholder="<?php echo __('Enter the amount')?>" />
						</div>
					</div>
				</td>
			</tr>

			<tr>
				<td><label><?php echo __('Enter amount')?></label></td>
				<td class="text-right">
					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-md-12">
							<input type="number" max="999999999999" name="rec[collected_amount]" required class="form-control text-right" id="rec_collected_amount" value="<?php echo $tien_phai_nop;?>" placeholder="<?php echo __('Enter the amount')?>" />
							<i><div id="collected_amount_text" class="text-danger" style="padding-top: 5px;"></div></i>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Excess money')?></label></td>
				<td class="text-right">
					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-md-12">
							<span id="excess_money"></span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Relative payment')?></label></td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-md-12">
							<input type="text" name="rec[relative_name]" class="form-control"
								id="rec_relative_name" value=""
								placeholder="<?php echo __('Enter the relative payment')?>" /> <select
								name="rec[relative_id]" class="select2" id="receipt_relative_id">
								<option value=""><?php echo __('or select are here')?></option>
								<?php foreach ($relatives as $relative):?>
								<option value="<?php echo $relative->getTitle()?> <?php echo $relative->getFullName()?>"><?php echo $relative->getTitle()?> <?php echo $relative->getFullName()?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="col-md-12" title="Ngày thanh toán">
							<div class="input-group">
								<input type="text" name="rec[payment_date]" class="form-control"
									id="receipt_payment_date" class="form-control"
									data-mask="99/99/9999" data-mask-placeholder="-"> <span
									class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
				</td>
			</tr>
			
			<tr>
				<td><label><?php echo __('Payment type')?></label></td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;">
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
						<div class="col-md-12" style="margin-bottom: 0px;">
							<input type="text" maxlength="255" name="rec[cashier]" class="form-control" id="rec_cashier" value="<?php echo $cashier;?>" placeholder="<?php echo __('Enter the cashier')?>" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label><?php echo __('Note')?></label></td>
				<td class="text-right">
					<div class="form-group">
						<div class="col-md-12" style="margin-bottom: 0px;">
							<input type="text" maxlength="255" name="rec[note]"
								class="form-control" id="rec_note" value=""
								placeholder="<?php echo __('Enter the note')?>" />
						</div>
					</div>
				</td>
			</tr>
			
			<?php }else{?>
			<tr>
				<td><label><strong><?php echo __('Chiết khấu')?></strong></label></td>
				<td class="text-right">
					<?php echo PreNumber::number_format($receipt->getChietkhau());?>					
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Collected amount')?></strong></label></td>
				<td class="text-right">
					<?php echo PreNumber::number_format($receipt->getCollectedAmount());?>					
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Excess money')?></strong></label></td>
				<td class="text-right"><span id="excess_money"><?php echo PreNumber::number_format($receipt->getBalanceAmount());?></span>
				</td>
			</tr>
			<tr>
				<td><label><strong><?php echo __('Relative payment')?></strong></label></td>
				<td class="text-right"><span id="excess_money"><?php echo $receipt->getPaymentRelativeName();?></span>
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
			<?php }?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var text = to_vietnamese('<?php echo (isset($tien_phai_nop) ? $tien_phai_nop : 0)?>');
		$('#collected_amount_text').html(text);

		$('#rec_collected_amount').keyup(function() {
			//alert($(this).val());
			$('#collected_amount_text').html(to_vietnamese($(this).val()));
		});	
	});
</script>
