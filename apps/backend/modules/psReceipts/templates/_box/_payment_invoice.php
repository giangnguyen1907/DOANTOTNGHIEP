<?php
$getTotalAmountOfMonth = $totalAmountReceivableAt ? $totalAmountReceivableAt->getTotalAmount () : 0;
?>
<form class="form-horizontal" id="frm_detail" name="frm_detail"
	method="post"
	action="<?php echo url_for('@ps_receipts_payment?id='.$receipt->getId())?>">
	<input type="hidden" name="id" id="id"
		value="<?php echo $receipt->getId();?>" /> <input type="hidden"
		name="rid" id="rid" value="<?php echo $receipt->getId();?>" /> <input
		type="hidden" name="action_type" id="action_type" value="" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover no-footer no-padding">
			<tbody>
				<tr>
					<td><label><?php echo __('Invoice no')?></label></td>
					<td class="text-right"><?php echo $receipt->getReceiptNo();?></td>
				</tr>
				<tr>
					<td><label><?php echo __('Payment status')?></label></td>
					<td><?php echo get_partial('global/field_custom/_field_payment_status', array('value' => $receipt->getPaymentStatus())) ?></td>
				</tr>
				<tr>
					<td><label><strong><?php echo __('Estimated month')?></strong>: <?php echo format_date($ps_fee_reports->getReceivableAt(), "MM-yyyy");?></label></td>
					<td class="text-right"><?php /*echo PreNumber::number_format($getTotalAmountOfMonth).'-'.*/ echo $ps_fee_reports->getReceivable();?></td>
				</tr>
				<tr>
					<td><label><?php echo __('Balance of previous month')?></label></td>
					<td class="text-right">
				<?php

				// echo $balanceAmount.'--'.$collectedAmount.'-'.$totalAmount.'-'.$getTotalAmountOfMonth;
				$newBalanceAmont = $collectedAmount - ($totalAmount - $getTotalAmountOfMonth);

				?>
				<?php if ($newBalanceAmont < 0):?>
				<code class="txt-color-redLight"><?php echo PreNumber::number_format($newBalanceAmont);?></code>
				<?php else:?>
				<?php echo PreNumber::number_format($newBalanceAmont);?>
				<?php endif;?>
				</td>
				</tr>
			<?php
			if ($receipt->getPaymentStatus () == PreSchool::NOT_ACTIVE) :
				$psLatePaymentAmount = 0;

				if ($psLatePayment) :
					$priceLatePayment = $psLatePayment->getPrice () + $pricePaymentLate;
					if ($priceLatePayment > 0) :
						?>
			<tr class="bg-yellow">
					<td><label><?php echo __('Late payment')?></label></td>
					<td class="text-right">
						<div class="form-group" style="margin-bottom: 0px;">
							<div class="col-md-12">
						<?php $psLatePaymentAmount =  $priceLatePayment;?>
						<input type="number" class="form-control text-right"
									name="rec[late_payment_amount]" id="rec_late_payment_amount"
									value="<?php echo PreNumber::number_format($psLatePaymentAmount, 0, '.', '.')?>" />
							</div>
						</div>
					</td>
				</tr>
			<?php endif;endif;endif;?>
			<tr>
					<td><label> <strong>
						<?php
						// Tong so tien can nop
						$psLatePaymentAmount = 0;
						// $totalPayment = $ps_fee_reports->getReceivable() + $psLatePaymentAmount;
						$totalPayment = $ps_fee_reports->getReceivable ();
						echo __ ( 'Total payment' ) . '<br/><small>Chưa tính phí nộp muộn</small>';
						?>
						</strong>
					</label></td>
					<td class="text-right"><input type="hidden"
						name="rec[total_payment]" id="rec_total_payment"
						value="<?php echo $totalPayment;?>" />
				<?php echo PreNumber::number_format($totalPayment);?>
				</td>
				</tr>			
			<?php if($receipt->getPaymentStatus() == PreSchool::NOT_ACTIVE):?>
			<tr>
					<td><label><?php echo __('Payment')?></label></td>
					<td class="text-right">
						<div class="form-group" style="margin-bottom: 0px;">
							<div class="col-md-12">
								<input type="number" min="0" max="999999999999"
									name="rec[collected_amount]" required
									class="form-control text-right" id="rec_collected_amount"
									value="" placeholder="<?php echo __('Enter the amount')?>" />
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
								<input type="text" name="rec[relative_name]" required
									class="form-control" id="rec_relative_name" value=""
									placeholder="<?php echo __('Enter the relative payment')?>" />
								<select name="rec[relative_id]" class="select2">
									<option><?php echo __('or select are here')?></option>
								<?php foreach ($relatives as $relative):?>
								<option value="<?php $relative->getId()?>"><?php echo $relative->getTitle()?> <?php echo $relative->getFullName()?></option>
								<?php endforeach;?>
							</select>
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
									<option><?php echo __('Payment type')?></option>
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
								<input type="text" maxlength="255" name="rec[cashier]"
									class="form-control" id="rec_cashier" value=""
									placeholder="<?php echo __('Enter the cashier')?>" />
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
			
			<?php if ($sf_user->hasCredential(array(0 => array(0 => 'PS_FEE_REPORT_CASHIER')))): ?>			
			<tr>
					<td>&nbsp;</td>
					<td><button type="submit"
							class="btn btn-default btn-success btn-sm bg-color-greenDark txt-color-white btn-save-receipt">
							<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
								title="<?php echo __('Payment') ?>"></i> <?php echo __('Payment') ?></button></td>
				</tr>
			<?php endif;?>
			
			<?php else:?>
			<tr>
					<td><label><strong><?php echo __('Payment')?></strong></label></td>
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
</form>