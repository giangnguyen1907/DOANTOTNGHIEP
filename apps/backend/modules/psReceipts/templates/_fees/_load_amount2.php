<td>

<?php if($priceLatePayment > 0){?>
	<label style="line-height: 0px;"><strong><?php echo __('Late payment')?></strong></label>
<hr>
<?php }?>
	<label> <strong>
		<?php
		// Tong so tien can nop
		$totalPayment = $ps_fee_reports->getReceivable () + $priceLatePayment + $hoantra - $chietkhau;
		$totalPayment2 = $ps_fee_reports->getReceivable () + $priceLatePayment + $hoantra;
		echo __ ( 'Total payment' );
		?>
		</strong>
</label>
</td>
<td class="text-right">

	<?php
	if ($priceLatePayment > 0) {
		echo PreNumber::number_format ( $priceLatePayment, 0, '.', '.' ) . '<hr>';
	}
	?>
	<input type="hidden" name="tongtienthanhtoan" id="tongtienthanhtoan" value="<?php echo $totalPayment;?>" />
	<input type="hidden" name="rec[late_payment_amount]" id="rec_late_payment_amount" value="<?php echo $priceLatePayment;?>" />
	<input type="hidden" name="rec[total_payment]" id="rec_total_payment" value="<?php echo $totalPayment;?>" /> 
	<b id="sotiencannop"><?php echo PreNumber::number_format($totalPayment2);?></b>

</td>