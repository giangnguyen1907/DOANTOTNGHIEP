<?php include_partial('psReceipts/fees/_box_modal_confirm_remover_receivable');?>
<?php 
// Danh sách các khoản phí;
$rs_current = array ();

$config_choose_charge_showlate = isset ( $psWorkPlace ) ? $psWorkPlace->getConfigChooseChargeShowlate () : 0;

/**
 * TÌM KHOẢN DƯ ĐẦU KỲ CỦA THÁNG TRƯỚC *
 */

// Là số tiền dư sau khi thanh toán ở phiếu trước
$du_phieu_truoc = $balance_last_month_amount;
?>
<script type="text/javascript">
$(document).ready(function() {
	$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_student_code a ,.sf_admin_list_td_first_name a, .sf_admin_list_td_last_name a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});

	$('.btn-delete-service').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete-service').attr('action', '<?php echo url_for('@ps_receivable_students')?>/' + item_id);
	});
	

});
</script>
<div class="custom-scroll table-responsive"
	style="height: 750px; overflow-y: scroll;">
	<table id="dt_basic"
		class="table table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr style="background-color: #fff;">
				<th colspan="6" class="text-left"
					style="background-color: #fff; border-right: none;"><b>1. <?php echo __('Balance of previous month')?>:</b></th>
				<th style="background-color: #fff; border-left: none;">&nbsp;</th>
				<th
					style="background-color: #fff; border-left: none; border-right: none;"
					class="text-right"></th>
				<th style="background-color: #fff;" class="text-right"><?php echo PreNumber::number_format($du_phieu_truoc);?></th>
				<th style="background-color: #fff;">&nbsp;</th>
				<th style="background-color: #fff;">&nbsp;</th>
			</tr>
			<tr style="background-color: #fff;">
				<th colspan="6" class="text-left" style="background-color: #fff;"><b>2. <?php echo __('Payment fees for previous month')?></b></th>
				<th style="background-color: #fff; border: none;">&nbsp;</th>
				<th style="background-color: #fff;" class="text-right"></th>
				<th style="background-color: #fff; border: none;" class="text-right"></th>
				<th style="background-color: #fff;">&nbsp;</th>
				<th style="background-color: #fff;">&nbsp;</th>
			</tr>
			<tr>
				<th class="text-center"><?php echo __('Month');?></th>
				<th><?php echo __('Name fees');?></th>
				<th class="text-right"><?php echo __('Price');?></th>
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
					data-placement="top"
					data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th class="text-center" style="max-width: 100px;"><?php echo __('Quantily');?></th>
				<th class="text-right"><?php echo __('Actual costs')?></th>
				<th><?php echo __('Note')?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = 0;
			foreach ( $receivable_student as $r_s ) :
				// Cac tháng cũ
			if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $r_s->getRsReceivableAt () ) )) :
					$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
					?>
			<tr>
				<td class="text-center">
					<?php echo false !== strtotime($r_s->getRsReceivableAt()) ? format_date($r_s->getRsReceivableAt(), "MM.yyyy") : '&nbsp;' ?>
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

					if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $r_s->getRsReceiptDate () ) )) {
						$rs_amount = 0;
					}
					
					$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;

					echo PreNumber::number_format ( $rs_amount );
					?>
				</td>
				<td class="text-center">
					<?php
					// So luong su dung de tinh tien
					$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();

					if ($r_s->getRsIsLate () == 1) {
						echo ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					} else {
						echo PreNumber::number_format ( $spentNumber );
					}
					if ($r_s->getIsTypeFee () == 1) {
						echo ' (' . __ ( 'Ko sd' ) . ')';
					}
					?>
				</td>
				<td class="text-right">
					<?php echo PreNumber::number_format($r_s->getRsAmount()); ?>
				</td>
				<td class="text-center">
					<?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote(); ?>
				</td>
				<td></td>
			</tr>
			<?php
				else :
					array_push ( $rs_current, $r_s );
				endif;
			endforeach
			;
			?>
			
			<tr>
				<td colspan="6" class="text-right"><b><?php echo __('Total expected')?>:</b></td>
				<td class="text-right" title="<?php echo __('Expected revenue')?>"><?php echo PreNumber::number_format($tong_du_kien_cac_thang_cu);?></td>

				<td><b><?php echo __('Total reality')?></b></td>
				<td class="text-right"><?php echo PreNumber::number_format($tong_cac_thang_cu);?></td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="6" class="text-right">&nbsp;</td>
				<td></td>

				<td><b><?php echo __('Paid')?>:</b></td>
				<td class="text-right">
			    <?php echo PreNumber::number_format($collectedAmount);?>
			    </td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="6" class="text-right"></td>
				<td class="text-right">&nbsp;</td>
				<td><b><?php echo __('Balance reality')?>:</b></td>
				<td class="text-right">
			    <?php

							$new_balance_last_month_amount = $receipt->getBalanceLastMonthAmount ();

							echo PreNumber::number_format ( $new_balance_last_month_amount );
							?>
			    </td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
				
			</tr>

			<!-- Du kien thu thang nay -->
			<tr style="background-color: #fff;">
				<th colspan="6" class="text-left" style="background-color: #fff;"><b>3. <?php echo __('Estimated monthly fee')?>: <?php echo format_date($receivable_at, "MM-yyyy");?></b></th>
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
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
					data-placement="top"
					data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount fixed');?></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount');?></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th></th>
				<th></th>
				<th><?php echo __('Note')?></th>
				<th><?php echo __('Action')?></th>
			</tr>
			
			<?php
			$tong_du_kien_thang_nay = 0;
			foreach ( $rs_current as $r_s ) : ?>
			<tr>
				<td class="text-center"><?php echo false !== strtotime($r_s->getRsReceivableAt()) ? format_date($r_s->getRsReceivableAt(), "MM.yyyy") : '&nbsp;' ?></td>
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
				if ($r_s->getRsServiceId () > 0 ) {

					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();

					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					
				} else {

					$rs_amount = $r_s->getRsAmount();
					
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				echo PreNumber::number_format ( $rs_amount );
				?>
				</td>
				<td></td>
				<td></td>
				<td>
				<?php echo $r_s->getRsNote();?>
				</td>
				<td>
				<?php if(!$receiptOfStudentNextMonth && $r_s->getRsServiceId() <= 0 && $receipt->getPaymentStatus () == PreSchool::NOT_ACTIVE && $sf_user->hasCredential('PS_FEE_REPORT_DELETE')){?>
				<a data-toggle="modal" data-target="#confirmDeleteService"
					data-backdrop="static"
					class="btn btn-xs btn-default btn-delete-service pull-right"
					data-item="<?php echo $r_s->getId()?>"><i
						class="fa-fw fa fa-times txt-color-red"
						title="<?php echo __('Delete')?>"></i></a>
				<?php }?>
				</td>
			</tr>
			
			<?php endforeach ;
			?>
			<tr>
				<td colspan="6" class="text-right"><b><?php echo __('Total provisional')?>:</b></td>
				<td class="text-right"><?php echo PreNumber::number_format($tong_du_kien_thang_nay);?></td>
				<td></td>
				<td class="text-right"></td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
				<td class="text-right"></td>
			</tr>

		</tbody>
	</table>
</div>
<?php sfConfig::set ( 'global_tong_du_kien_thang_nay', $tong_du_kien_thang_nay );?>
<?php sfConfig::set ( 'global_new_balance_last_month_amount', $new_balance_last_month_amount );?>