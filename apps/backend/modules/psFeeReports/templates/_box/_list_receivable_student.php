<div class="custom-scroll table-responsive"
	style="height: 400px; overflow-y: scroll;">
	<table id="dt_basic"
		class="table table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr style="background-color: #fff;">
				<th colspan="5" class="text-left" style="background-color: #fff;"><b><?php echo __('Payment fees for previous month')?></b></th>
				<th style="background-color: #fff;" class="text-right"></th>
				<th style="background-color: #fff; border: none;" class="text-right"></th>
				<th style="background-color: #fff; border: none;">&nbsp;</th>
			</tr>
			<tr>
				<th class="text-center"><?php echo __('Month');?></th>
				<th><?php echo __('Name fees');?></th>
				<th class="text-right"><?php echo __('Price');?></th>
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
					data-placement="top"
					data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th class="text-center" style="max-width: 100px;"><?php echo __('Used');?></th>
				<th class="text-right"><?php echo __('Actual costs')?></th>
				<th><?php echo __('Note')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$current_month = false;
			$tong = 0;
			$tong_so_tien = 0;
			$tong_thang_nay = 0;
			$tong_cac_thang_cu = 0;
			foreach ( $receivable_student as $r_s ) :

				$tong_so_tien = $tong_so_tien + $r_s->getRsAmount ();

				if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
					$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
				} else {
					$tong_thang_nay = $tong_thang_nay + $r_s->getRsAmount ();
				}
				?>			
			<?php if (!$current_month && date("Ym",PsDateTime::psDatetoTime($receivable_at)) == date("Ym",PsDateTime::psDatetoTime($r_s->getRsReceivableAt()))):?>
			<tr style="background-color: #fff;">
				<th colspan="6" class="text-right" style="background-color: #fff;"><b><?php echo __('Total amount')?></b></th>
				<th style="background-color: #fff; border-right: none;"
					class="text-right">		  
			  <?php echo PreNumber::number_format($tong_cac_thang_cu);?>		  
			  </th>
				<th style="background-color: #fff; border-left: none;">&nbsp;</th>
			</tr>
			<tr>
				<td colspan="8" class="text-left"><b><?php echo __('Estimated fees this month')?></b></td>
			</tr>
			<?php

$current_month = true;
			
			endif;

				?>
				  
			<tr>
				<td class="text-center">
				<?php echo false !== PsDateTime::psDatetoTime($r_s->getRsReceivableAt()) ? format_date($r_s->getRsReceivableAt(), "MM.yyyy") : '&nbsp;' ?>
				</td>
				<td>
				<?php
				if ($r_s->getRsReceivableId ()) {
					echo $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					echo $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					echo __ ( 'Out late' ) . ' ' . $r_s->getRsReceivableAt ();
				}
				?>
				</td>
				<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>

				<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>

				<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice()*$r_s->getRsByNumber());?></td>

				<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsSpentNumber());?></td>

				<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsAmount());?></td>

				<td><?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?></td>
			</tr>		
			<?php endforeach;?>
			
			<tr style="background-color: #fff;">
				<th colspan="6" class="text-right" style="background-color: #fff;"><b><?php echo __('Total amount')?></b></th>
				<th style="background-color: #fff; border-right: none;"
					class="text-right">		  
			  <?php echo ($totalAmountReceivableAt ? PreNumber::number_format($totalAmountReceivableAt->getTotalAmount()) : 0);?>		  
			  </th>
				<th style="background-color: #fff; border-left: none;">&nbsp;</th>
			</tr>

			<!-- 
			<tr style="background-color: #fff;">
			  <th colspan="6" class="text-right" style="background-color: #fff;"><b><?php echo __('Balance of previous month')?></b></th>
			  <th style="background-color: #fff;border-right: none;" class="text-right">		  
			  <?php echo $balanceAmount;?>		  
			  </th>
			  <th style="background-color: #fff;border-left: none;">&nbsp;</th>
			</tr>
			
			<tr style="background-color: #fff;">
			  <th colspan="6" class="text-right" style="background-color: #fff;"><b><i><?php echo __('Total amount')?>(<?php echo __('Total')?> - <?php echo __('Balance of previous month')?>)</i></b></th>
			  <th style="background-color: #fff;border-right: none;" class="text-right">		  
			  <?php echo PreNumber::number_format($ps_fee_reports->getReceivable());?>		  
			  </th>
			  <th style="background-color: #fff;border-left: none;">&nbsp;</th>
			</tr> -->

		</tbody>
	</table>
</div>