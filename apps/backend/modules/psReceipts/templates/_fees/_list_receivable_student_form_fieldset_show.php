<fieldset id="sf_fieldset_receivable_student">
	<legend>
		<?php echo __('List of revenues of month %%month%%', array('%%month%%' => format_date($receipt->getReceiptDate(), "MM-yyyy"))) ?>
	</legend>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="custom-scroll table-responsive"
				style="height: 600px; overflow-y: scroll;">
				<table id="dt_basic"
					class="table table-bordered table-hover no-footer no-padding"
					width="100%">
					<thead>
						<tr style="background-color: #fff;">
							<th colspan="6" class="text-left" style="background-color: #fff;"><b>1.<?php echo __('Payment fees for previous month')?></b></th>
							<th style="background-color: #fff; border: none;">&nbsp;</th>
							<th style="background-color: #fff;" class="text-right"></th>
							<th style="background-color: #fff; border: none;"
								class="text-right"></th>
							<th style="background-color: #fff;">&nbsp;</th>
							<th style="background-color: #fff;">&nbsp;</th>
						</tr>
						<tr>
							<th class="text-center" style="width: 50px;"><?php echo __('Month');?></th>
							<th><?php echo __('Name fees');?></th>
							<th class="text-right"><?php echo __('Price');?></th>
							<th class="text-center" style="max-width: 70px;"><span
								rel="tooltip" data-placement="top"
								data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
							<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
							<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
							<th class="text-right"><?php echo __('Temporary money');?></th>
							<th class="text-center" style="max-width: 100px;"><?php echo __('Used');?></th>
							<th class="text-right"><?php echo __('Actual costs')?></th>
							<th><?php echo __('Note')?></th>
							<th><?php echo __('Actions')?></th>
						</tr>
					</thead>
					<tbody>
				<?php
				foreach ( $receivable_student as $r_s ) :
					// Cac tháng cũ
					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) :
						?>
				<tr>
							<td class="text-center">
						<?php echo false !== PsDateTime::psDatetoTime($r_s->getRsReceivableAt()) ? format_date($r_s->getRsReceivableAt(), "MM.yyyy") : '&nbsp;' ?>
					</td>
							<td class="text-left">
						<?php
						if ($r_s->getRsReceivableId ()) {
							echo $r_s->getRTitle ();
						} elseif ($r_s->getRsServiceId ()) {
							echo $r_s->getSTitle ();
						} elseif ($r_s->getRsIsLate () == 1) {
							echo __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
						}
						?>
					</td>
							<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
							<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
							<td class="text-right"><?php echo ($r_s->getRsDiscountAmount() > 0) ? PreNumber::number_format($r_s->getRsDiscountAmount()) : '';?></td>
							<td class="text-center"><?php echo ($r_s->getRsDiscount() > 0) ? PreNumber::number_format($r_s->getRsDiscount()) : '';?></td>
							<td class="text-right">
						<?php
						// Phi du kien
						if ($r_s->getRsServiceId () > 0) {
							$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

							$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						} else {
							$rs_amount = $r_s->getRsAmount ();
						}

						if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
							$rs_amount = 0;
						}
						echo PreNumber::number_format ( $rs_amount );
						?>
					</td>
							<td class="text-center">
						<?php
						// So luong su dung de tinh tien
						$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
						echo PreNumber::number_format ( $spentNumber );
						if ($r_s->getRsIsLate () == 1)
							echo ' ' . __ ( 'Minute' );
						?>
					</td>
							<td class="text-right">
						<?php
						echo PreNumber::number_format ( $r_s->getRsAmount () );
						?>
					</td>
							<td class="text-center"><?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?></td>
							<td>Edit | Save</td>
						</tr>
				<?php endif;

				endforeach
				;
				?>
				
				<tr style="background-color: #fff;">
							<th colspan="6" class="text-left" style="background-color: #fff;"><b>2.<?php echo __('Estimated fees this month')?></b></th>
							<th style="background-color: #fff;">&nbsp;</th>
							<th style="background-color: #fff;" class="text-right"></th>
							<th style="background-color: #fff;" class="text-right"></th>
							<th style="background-color: #fff;">&nbsp;</th>
							<th style="background-color: #fff;">&nbsp;</th>
						</tr>
						<tr>
							<th class="text-center"><?php echo __('Month');?></th>
							<th><?php echo __('Name fees');?></th>
							<th class="text-right"><?php echo __('Price');?></th>
							<th class="text-center" style="max-width: 70px;"><span
								rel="tooltip" data-placement="top"
								data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
							<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
							<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
							<th class="text-right"><?php echo __('Temporary money');?></th>
							<th></th>
							<th></th>
							<th><?php echo __('Note')?></th>
							<th></th>
						</tr>
				
				<?php
				foreach ( $receivable_student as $r_s ) :
					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) :
						?>
				<tr>
							<td class="text-center"><?php echo false !== PsDateTime::psDatetoTime($r_s->getRsReceivableAt()) ? format_date($r_s->getRsReceivableAt(), "MM.yyyy") : '&nbsp;' ?></td>
							<td>
						<?php
						if ($r_s->getRsReceivableId ()) {
							echo $r_s->getRTitle ();
						} elseif ($r_s->getRsServiceId ()) {
							echo $r_s->getSTitle ();
						} elseif ($r_s->getRsIsLate () == 1) {
							echo __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
						}
						?>
					</td>
							<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
							<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
							<td class="text-right"><?php echo ($r_s->getRsDiscountAmount() > 0) ? PreNumber::number_format($r_s->getRsDiscountAmount()) : '';?></td>
							<td class="text-center"><?php echo ($r_s->getRsDiscount() > 0) ? PreNumber::number_format($r_s->getRsDiscount()) : '';?></td>
							<td class="text-right">
						<?php
						// Phi du kien
						if ($r_s->getRsServiceId () > 0) {

							$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

							$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						} else {
							$rs_amount = $r_s->getRsAmount ();
						}
						echo PreNumber::number_format ( $rs_amount );
						?>
					</td>
							<td></td>
							<td></td>
							<td></td>
							<td>Edit | Save</td>
						</tr>			
				<?php endif;
					endforeach;
				?>
			</tbody>
				</table>
			</div>
		</div>
	</div>
</fieldset>