<?php
$getTotalAmountOfMonth = $totalAmountReceivableAt ? $totalAmountReceivableAt->getTotalAmount () : 0;
?>
<form class="form-horizontal" id="frm_detail" name="frm_detail"
	method="post"
	action="<?php echo url_for('@ps_fee_reports_payment_receipt?id='.$ps_fee_reports->getId())?>">
	<input type="hidden" name="id" id="id"
		value="<?php echo $ps_fee_reports->getId();?>" /> <input type="hidden"
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
					<td class="text-right"><?php echo PreNumber::number_format($getTotalAmountOfMonth);?></td>
				</tr>
				<tr>
					<td><label><?php echo __('Balance of previous month')?></label></td>
					<td class="text-right">
				<?php $newBalanceAmont = $collectedAmount - ($totalAmount -  $getTotalAmountOfMonth);?>
				<?php if ($newBalanceAmont < 0):?>
				<code class="txt-color-redLight"><?php echo PreNumber::number_format($newBalanceAmont);?></code>
				<?php else:?>
				<?php echo PreNumber::number_format($newBalanceAmont);?>
				<?php endif;?>
				</td>
				</tr>

				<tr>
					<td><label><strong><?php echo __('Total payment')?></strong></label></td>
					<td class="text-right"><input type="hidden"
						name="rec[total_payment]" id="rec_total_payment"
						value="<?php echo $ps_fee_reports->getReceivable();?>" />
				<?php echo PreNumber::number_format($ps_fee_reports->getReceivable());?>
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

				<tr>
					<td>&nbsp;</td>
					<td><button type="submit"
							class="btn btn-default btn-success btn-sm bg-color-greenDark txt-color-white btn-save-receipt">
							<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
								title="<?php echo __('Payment') ?>"></i> <?php echo __('Payment') ?></button></td>
				</tr>
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