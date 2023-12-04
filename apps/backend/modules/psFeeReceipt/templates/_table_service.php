<div id="datatable_fixed_column_wrapper"
	class="dataTables_wrapper form-inline no-footer no-padding">
	<div class="custom-scroll table-responsive">
		<table id="dt_basic"
			class="table table-striped table-bordered table-hover no-footer no-padding"
			width="100%">

			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th colspan="13" class="text-center"><?php echo $receivable_title ?></th>
				</tr>
				<tr>
					<th class="text-center" style="border-bottom: none !important;"><?php echo __('STT', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Birthday', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Class', array(), 'messages') ?></th>
					<th colspan="6" class="text-center"><?php echo __('Last month') ?></th>
					<th colspan="5" class="text-center"><?php echo __('This month') ?></th>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th class="text-center"><?php echo __('Price') ?></th>
					<th class="text-center"><?php echo __('Spent number') ?></th>
					<th class="text-center"><?php echo __('Discount') ?></th>
					<th class="text-center"><?php echo __('GT co dinh') ?></th>
					<th class="text-center"><?php echo __('Da su dung') ?></th>
					<th class="text-center"><?php echo __('Da thu') ?></th>

					<th class="text-center"><?php echo __('Price') ?></th>
					<th class="text-center"><?php echo __('SL') ?></th>
					<th class="text-center"><?php echo __('Discount') ?></th>
					<th class="text-center"><?php echo __('GT co dinh') ?></th>
					<th class="text-center"><?php echo __('Amount') ?></th>

					<th class="text-center"><?php echo __('Last month amount') ?></th>
					<th class="text-center"><?php echo __('Receivable amount') ?></th>
				</tr>

			</thead>
			<?php $tong_thu_thang_truoc = $tong_thu_thang_nay = $phai_nop_thang_nay = $thangtruocchuyensang  = ''; //echo count($list_service);?>
			<tbody>
				<?php foreach ($list_student as $key => $student){?>
				<tr>
					<td class="text-center"><?php echo $key+1?></td>
					<td><?php echo $student->getStudentName()?><br> <code><?php echo $student->getStudentCode()?></code>
					</td>
					<td class="text-center">
					<?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student->getBirthday())) ?>
					</td>
					<td><?php echo $student->getClassName()?></td>
					<?php
					$tt_unitprice = $tt_soluong = $tt_discount = $tt_discount_amount = $tt_amount = $tt_dasudung = '';
					$tn_unitprice = $tn_soluong = $tn_discount = $tn_discount_amount = $tn_amount = '';
					?>
					<?php

foreach ( $list_service as $service ) {

						if ($student->getId () == $service->getStudentId () && date ( 'Ym', strtotime ( $service->getReceiptDate () ) ) != date ( 'Ym', strtotime ( '01-' . $ps_month ) )) {
							$tt_unitprice = $service->getUnitPrice ();
							$tt_dukien = $service->getByNumber ();
							$tt_soluong = $service->getSpentNumber ();
							$tt_discount = $service->getDiscount ();
							$tt_discount_amount = $service->getDiscountAmount ();
							$tt_dasudung = $service->getAmount ();

							$tt_amount = ($tt_discount > 0) ? ((100 - $tt_discount) * $tt_unitprice * $tt_dukien) / 100 : $tt_unitprice * $tt_dukien;
							$tt_amount = $tt_amount - ( float ) $tt_discount_amount;

							$thangtruocchuyensang = $tt_amount - $service->getAmount ();
						}
						if ($student->getId () == $service->getStudentId () && date ( 'Ym', strtotime ( $service->getReceiptDate () ) ) == date ( 'Ym', strtotime ( '01-' . $ps_month ) )) {
							$tn_unitprice = $service->getUnitPrice ();
							$tn_soluong = $service->getByNumber ();
							$tn_discount = $service->getDiscount ();
							$tn_discount_amount = $service->getDiscountAmount ();

							$tn_amount = ($tn_discount > 0) ? ((100 - $tn_discount) * $tn_unitprice * $tn_soluong) / 100 : $tn_unitprice * $tn_soluong;
							$tn_amount = $tn_amount - ( float ) $tn_discount_amount;

							$phai_nop_thang_nay = $tn_amount - $thangtruocchuyensang;
						}
					}
					?>
					<td class="text-right"><?php if($tt_unitprice != ''){ echo PreNumber::number_format($tt_unitprice);}?></td>
					<td class="text-center"><?php if($tt_soluong != ''){ echo PreNumber::number_format($tt_soluong);}?></td>
					<td class="text-center"><?php echo $tt_discount?></td>
					<td class="text-center"><?php echo $tt_discount_amount?></td>
					<td class="text-center"><?php if($tt_dasudung != ''){ echo PreNumber::number_format($tt_dasudung);}?></td>
					<td class="text-right"><?php if($tt_amount != ''){ echo PreNumber::number_format($tt_amount);}?></td>

					<td class="text-right"><?php if($tn_unitprice != ''){ echo PreNumber::number_format($tn_unitprice);}?></td>
					<td class="text-center"><?php if($tn_soluong != ''){ echo PreNumber::number_format($tn_soluong);}?></td>
					<td class="text-center"><?php echo $tn_discount?></td>
					<td class="text-center"><?php echo $tn_discount_amount?></td>
					<td class="text-right"><?php if($tn_amount != ''){ echo PreNumber::number_format($tn_amount);}?></td>

					<td class="text-right"><?php if($thangtruocchuyensang != ''){ echo PreNumber::number_format($thangtruocchuyensang);} $thangtruocchuyensang = '';?></td>
					<td class="text-right"><?php if($phai_nop_thang_nay != ''){ echo PreNumber::number_format($phai_nop_thang_nay);}?></td>
					<?php
					$tong_thu_thang_truoc += $tt_amount;
					$tong_thu_thang_nay += $phai_nop_thang_nay;
					$phai_nop_thang_nay = '';
					?>
				</tr>
				<?php }?>
				<tr>
					<td></td>
					<td></td>
					<td colspan="7" class="text-right"><?php echo __('Da thu').' '.__('Last month')?></td>
					<td class="text-right"><?php if($tong_thu_thang_truoc != ''){ echo PreNumber::number_format($tong_thu_thang_truoc);}?></td>

					<td colspan="6" class="text-right"><?php echo __('Total this month')?></td>
					<td class="text-right"><?php if($tong_thu_thang_nay != ''){ echo PreNumber::number_format($tong_thu_thang_nay);}?></td>
					
				</tr>
			</tbody>
		</table>
	</div>
</div>