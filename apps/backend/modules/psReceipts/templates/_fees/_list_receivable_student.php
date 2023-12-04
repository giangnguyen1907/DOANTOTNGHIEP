<?php include_partial('psReceipts/fees/_box_modal_confirm_remover_receivable');?>
<?php include_partial('global/include/_box_modal') ?>
<?php 
// Danh sách các khoản phí;

$config_choose_charge_showlate = isset ( $psWorkPlace ) ? $psWorkPlace->getConfigChooseChargeShowlate () : 0;

// $birthday = '2021-01-01';
// $var_date = '2023-06-01';
// $thangtuoi = PreSchool::getMonthYear($birthday, $var_date);
// echo $thangtuoi;

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
<script type="text/javascript">
	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
</script>
<div class="custom-scroll table-responsive"
	style="height: 750px; overflow-y: scroll;">
	<table id="dt_basic"
		class="table table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr style="background-color: #fff;">
				<th colspan="5" class="text-left"
					style="background-color: #fff; border-right: none;"><b>1. <?php echo __('Balance of previous month')?>:</b></th>
				<th style="background-color: #fff; border-left: none;">&nbsp;</th>
				
				<th style="background-color: #fff;" class="text-right"><?php echo PreNumber::number_format($du_phieu_truoc);?></th>
				<th style="background-color: #fff;">&nbsp;</th>
				<th style="background-color: #fff;">&nbsp;</th>
			</tr>
			
		</thead>
		<tbody>
			<?php
			$rs_current = array();
			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = 0;
			$hoantra = 0;
			$array_hoantra_phatsinh = array();
			$du_no_thang_truoc = $du_phieu_truoc;
			//echo count($receivable_student);
			//echo $receivable_at;
			
			$date = new DateTime($receivable_at);
			$date->modify('-1 month');
			$thangtruoc = $date->format('Y-m-d');
			
			foreach ( $receivable_student as $r_s ){
				
				// Cac tháng cũ   thành thay getRsReceivableAt() = $receiptDate getRsReceiptDate()
				$receiptDate  = $r_s->getRsReceivableAt();
				
				if (date ( "Ym", strtotime ( $thangtruoc ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
					//echo $receivable_at;
					$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
					//echo $tong_cac_thang_cu;
					
					if ($r_s->getRsServiceId () > 0) {
						$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

						$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					} else {
						$rs_amount = $r_s->getRsAmount ();
					}

					$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
					$dutien = 0;
					if($r_s->getRsEnableRoll () == 0){
						$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
					}
					
					if ($r_s->getRsIsLate () == 1) {
						$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
						$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
						$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
						$array_hoantra_phatsinh[-1]['tieude'] = __( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
						$array_hoantra_phatsinh[-1]['ghichu'] = __($r_s->getRsNote());
						$dutien = $r_s->getRsAmount ();
					} else {
						$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
						$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
						$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
						$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = __($r_s->getRsNote());
						
						//echo $r_s->getSTitle()."___".$dutien.'<br>';
						//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $dutien;
						//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $r_s->getRsAmount () - $rs_amount;
					}
					$hoantra = $hoantra + $dutien;
					//echo $r_s->getSTitle().'__'.$dutien.$receiptDate.'<br>';
					//echo $r_s->getRsAmount ().'__'.$rs_amount.'<br>';

				}else{
					//echo $receiptDate.$r_s->getSTitle().'<br>';
					array_push ( $rs_current, $r_s );
				}
			}
			//echo $hoantra;
			// echo '<pre>';
			// print_r($array_hoantra_phatsinh);
			// echo '</pre>';
			?>
			<!-- Du kien thu thang nay -->
			<tr style="background-color: #fff;">
				<th colspan="5" class="text-left" style="background-color: #fff;"><b>2. <?php echo __('Estimated monthly fee')?>: <?php echo format_date($receivable_at, "MM-yyyy");?></b></th>
				<th style="background-color: #fff;">&nbsp;</th>
				<th style="background-color: #fff;" class="text-right"></th>
				<th style="background-color: #fff;" class="text-right"></th>
				<th style="background-color: #fff;">&nbsp;</th>
			</tr>
			<tr>
				<th class="text-center"><?php echo __('Month');?></th>
				<th><?php echo __('Name fees');?></th>
				<th class="text-right"><?php echo __('Price');?></th>
				<th class="text-center" style="max-width: 70px;"><span rel="tooltip"
					data-placement="top"
					data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
				<th class="text-center" style="max-width: 70px;"><?php echo __('Discount amount');?></th>
				<th class="text-right"><?php echo __('Temporary money');?></th>
				<th>Hoàn trả / phát sinh</th>
				<th><?php echo __('Note')?></th>
				<th class="text-center"><?php echo __('Action', array(), 'messages') ?></th>
			</tr>
			
			<?php
			$tong_du_kien_thang_nay = 0;
			foreach ( $rs_current as $r_s ) {
				$receiptDate  = $r_s->getRsReceivableAt ();
				//$receiptDate  = $r_s->getRsReceiptDate ();
				//echo ''.$receiptDate.'<br>' ;
				if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
			?>
			<tr>
				<td class="text-center"><?php echo false !== strtotime($receiptDate) ? format_date($receiptDate, "MM.yyyy") : '&nbsp;' ?></td>
				<td>
					<?php
					if ($r_s->getRsReceivableId ()) {
						echo $r_s->getRTitle ();
					} elseif ($r_s->getRsServiceId ()) {
						echo $r_s->getSTitle ();
					} elseif ($r_s->getRsIsLate () == 1) {
						echo __ ( 'Out late' ) . '(' . format_date ( $receiptDate, "MM/yyyy" ) . ')';
					}
					?>
				</td>
				<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice())?></td>
				<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
				<td class="text-right"><?php echo ($r_s->getRsDiscountAmount() != 0) ? PreNumber::number_format($r_s->getRsDiscountAmount()) : '';?></td>
				<td class="text-right">
					<?php
					// Phi du kien
					if ($r_s->getRsServiceId () > 0) {

						// Bỏ chỗ này vì không có tính giảm trừ % ở đây nữa
						//$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();

						// Thành viết lại chỗ này. lấy số tiền thực tế
						
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					} else {

						// $rs_amount = $r_s->getRsAmount();
						$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}
					echo PreNumber::number_format ( $rs_amount );
					?>
				</td>
				<td class="text-right">
					<?php if(isset($array_hoantra_phatsinh[$r_s->getRsServiceId()])){
						echo PreNumber::number_format($array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien']);
						unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
					} ?>
				</td>
				
				<td><?php echo $r_s->getRsNote();?></td>
				<td class="text-center">

					<?php if($r_s->getRsDiscountAmount() != 0){ ?>
						<a data-toggle="modal" data-target="#remoteModal" class="btn btn-xs btn-default btn-delete-service" href="<?php echo url_for('@ps_student_service_reduce_detail?sid='.$r_s->getStudentId().'&rid='.$r_s->getServiceId().'&date='.strtotime($receiptDate)) ?>"><i
								class="fa-fw fa fa-eye txt-color-red"
								title="<?php echo __('View')?>"></i>
						</a>
					<?php }?>

					<?php if(!$receiptOfStudentNextMonth && $r_s->getRsServiceId() <= 0 && $receipt->getPaymentStatus () == PreSchool::NOT_ACTIVE && $sf_user->hasCredential('PS_FEE_REPORT_DELETE')){?>
					<a data-toggle="modal" data-target="#confirmDeleteService"
						data-backdrop="static"
						class="btn btn-xs btn-default btn-delete-service"
						data-item="<?php echo $r_s->getId()?>"><i
							class="fa-fw fa fa-times txt-color-red"
							title="<?php echo __('Delete')?>"></i></a>
					<?php }?>

					<?php  ?>

				</td>
			</tr>
			<?php 
			}
			}
			foreach($array_hoantra_phatsinh as $khoanPhatSinh){
				if($khoanPhatSinh['tien'] !=0){
			?>
			<tr>
				<td></td>
				<td><?=$khoanPhatSinh['tieude']?></td>
				<td></td>
				<td><?=$khoanPhatSinh['sl']?></td>
				<td></td>
				<td></td>
				<td class="text-right"><?= number_format($khoanPhatSinh['tien'])?></td>
				<td><?=$khoanPhatSinh['ghichu']?></td>
				<td></td>
			</tr>
			<?php }} ?>
			<tr>
				<td class="text-center"><b>A</b></td>
				<td colspan="4" class="text-right"><b><?php echo __('Total provisional')?>:</b></td>
				<td class="text-right"><b><?php echo PreNumber::number_format($tong_du_kien_thang_nay);?></b></td>
				<td></td>
				<td class="text-right"></td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
			</tr>
			<tr>
				<td class="text-center"><b>B</b></td>
				<td colspan="4" class="text-right"><b>Số nợ kỳ trước:</b></td>
				<td class="text-right"><b><?php echo PreNumber::number_format($du_no_thang_truoc);?></b></td>
				<td></td>
				<td class="text-right"></td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
			</tr>
			<tr>
				<td class="text-center"><b>C</b></td>
				<td colspan="4" class="text-right"><b>Hoàn trả / phát sinh kỳ trước:</b></td>
				<td class="text-right"><b><?php echo PreNumber::number_format($hoantra);?></b></td>
				<td class="text-right" colspan="2"><b>Dư thực tế tháng trước (B+C)</b></td>
				<td ><b><?php 
					$thuctethangtruoc = $du_no_thang_truoc + $hoantra;
					echo PreNumber::number_format($thuctethangtruoc);?></b></td>
			</tr>
			<tr>
				<td class="text-center"><b>D</b></td>
				<td colspan="4" class="text-right"><b>Phải thu kỳ này (A+B+C):</b></td>
				<td class="text-right"><b><?php 
					$phaithukynay = $tong_du_kien_thang_nay + $du_no_thang_truoc + $hoantra;
					echo PreNumber::number_format($phaithukynay);?></b></td>
				<td></td>
				<td class="text-right"></td>
				<td style="background-color: #fff; border-left: none;">&nbsp;</td>
			</tr>
		</tbody>
	</table>
</div>
<?php sfConfig::set ( 'global_tong_du_kien_thang_nay', $tong_du_kien_thang_nay );?>
<?php sfConfig::set ( 'global_new_balance_last_month_amount', $du_phieu_truoc );?>
<?php sfConfig::set ( 'global_hoantra', $hoantra );?>