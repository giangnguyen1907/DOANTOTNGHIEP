<?php // Danh sách các khoản phí?>
<div class="custom-scroll table-responsive" style="height:400px; overflow-y: scroll;">
	<table id="dt_basic" class="table table-bordered table-hover no-footer no-padding" width="100%">
		<thead>
			<tr style="background-color: #fff;">
			  <th colspan="6" class="text-left" style="background-color: #fff;"><b>1.<?php echo __('Payment fees for previous month')?></b></th>
			  <th style="background-color: #fff;border: none;">&nbsp;</th>
			  <th style="background-color: #fff;" class="text-right"></th>
			  <th style="background-color: #fff;border: none;" class="text-right"></th>
			  <th style="background-color: #fff;">&nbsp;</th>			  
			</tr>
			<tr>
				<th class="text-center"><?php echo __('Month');?></th>
				<th><?php echo __('Name fees');?></th>
				<th class="text-right"><?php echo __('Price');?></th>
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip" data-placement="top" data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
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
			
			$tong_du_kien_cu = $tong_du_kien_hientai = 0;
			
			foreach ($receivable_student as $r_s):
				
				$tong_so_tien = $tong_so_tien + $r_s->getRsAmount();
			
				if (date("Ym",PsDateTime::psDatetoTime($receivable_at)) != date("Ym",PsDateTime::psDatetoTime($r_s->getRsReceivableAt()))) {
					
					$tong_cac_thang_cu  	= $tong_cac_thang_cu 	+ $r_s->getRsAmount();
					
					$tong_du_kien_cu    	= $tong_du_kien_cu 		+ $r_s->getRsUnitPrice()*$r_s->getRsByNumber();
					
				} else {
					
					$tong_thang_nay 		= $tong_thang_nay 		+ $r_s->getRsAmount();
					
					$tong_du_kien_hientai   = $tong_du_kien_hientai + $r_s->getRsUnitPrice()*$r_s->getRsByNumber();
				}					
			?>			
			<?php if (!$current_month && date("Ym",PsDateTime::psDatetoTime($receivable_at)) == date("Ym",PsDateTime::psDatetoTime($r_s->getRsReceivableAt()))):?>
			<tr>
			    <td colspan="6" class="text-right"><b><?php echo __('Tổng dự kiến')?>:</b></td>
			    <td class="text-right" title="<?php echo __('Expected revenue')?>"><?php echo PreNumber::number_format( $ps_fee_reports_nearest ? $ps_fee_reports_nearest->getReceivable() : 0);?></td>
			    <td><b>Tổng thực tế</b></td>
			    <td class="text-right"><?php echo PreNumber::number_format($tong_cac_thang_cu);?></td>
			    <td style="background-color: #fff;border-left: none;">&nbsp;</td>
			</tr>
									
			<tr>
				<td colspan="10" class="text-left"><b>2.<?php echo __('Estimated fees this month')?></b></td>
			</tr>
			
			<tr>
				<th class="text-center"><?php echo __('Month');?></th>
				<th><?php echo __('Name fees');?></th>
				<th class="text-right"><?php echo __('Price');?></th>
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip" data-placement="top" data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
				<th class="text-center" style="max-width: 100px;"><?php echo __('Quantity calculated');?></th>
				<th class="text-right"><?php echo __('Expected fee')?></th>
				<th><?php echo __('Note')?></th>
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
					if ($r_s->getRsReceivableId()) {
						echo $r_s->getRTitle();
					} elseif($r_s->getRsServiceId()) {
						echo $r_s->getSTitle();
					} elseif($r_s->getRsIsLate() == 1) {
						echo __('Out late').'('.format_date($r_s->getRsReceivableAt(), "MM/yyyy").')';
					}
				?>
				</td>
				<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
				
				<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
				
				<td class="text-right">				
				<?php
					if($r_s->getRsServiceId() > 0) {
						echo PreNumber::number_format($r_s->getRsUnitPrice()*$r_s->getRsByNumber());
					} else {
						echo PreNumber::number_format($r_s->getRsAmount());
					}
				?>
				</td>
				
				<td class="text-right"><?php echo ($r_s->getRsDiscountAmount() > 0) ? PreNumber::number_format($r_s->getRsDiscountAmount()) : '';?></td>
				
				<td class="text-center"><?php echo ($r_s->getRsDiscount() > 0) ? PreNumber::number_format($r_s->getRsDiscount()) : '';?></td>
				
				<td class="text-center">
					<?php /** Nếu là Hiện tại thì Số lượng sử dụng = Số lượng sử dụng dự kiến **/
						echo PreNumber::number_format($current_month ? $r_s->getRsByNumber() :  ($r_s->getRsEnableRoll() == 1 ? $r_s->getRsByNumber() : $r_s->getRsSpentNumber()) );
					?>
				</td>
				
				<td class="text-right">
					<?php //echo PreNumber::number_format($r_s->getRsAmount());?>
					
					<?php
					if($r_s->getRsServiceId() > 0) {
						echo 'A'.PreNumber::number_format($r_s->getRsUnitPrice()*$r_s->getRsByNumber());
						echo '<br/>'.$r_s->getRsAmount();
					} else {
						echo 'B'.PreNumber::number_format($r_s->getRsAmount());
					}
					?>
				</td>
				
				<td><?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?></td>
			</tr>		
			<?php endforeach;?>
			
			<tr>
			    <td colspan="6"><b><?php echo __('Total amount')?>:</b></td>
			    <td class="text-right" title="<?php echo __('Expected revenue')?>"><?php //echo PreNumber::number_format($ps_fee_reports->getReceivable());?></td>
			    <td>&nbsp;</td>
			    <td class="text-right">
			    	<?php
			    	/*	
			    	echo ($totalAmountReceivableAt ? PreNumber::number_format($totalAmountReceivableAt->getTotalAmount()) : 0).'<br/>';
			    		
			    		if ($current_month)
			    			echo ($totalAmountReceivableAt ? PreNumber::number_format($totalAmountReceivableAt->getTotalAmount()) : 0);
			    		else
			    			echo PreNumber::number_format($ps_fee_reports->getReceivable());
			    	*/
			    	// Tong du kien cua thang
			    	echo PreNumber::number_format($tong_thang_nay);
			    	?>
			    </td>
			    <td style="background-color: #fff;border-left: none;">&nbsp;</td>
			</tr>			
		</tbody>
	</table>
</div>