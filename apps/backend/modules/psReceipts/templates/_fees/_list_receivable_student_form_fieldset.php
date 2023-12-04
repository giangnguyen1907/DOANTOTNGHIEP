<?php include_partial('psReceipts/fees/_box_modal_confirm_remover_receivable');?>
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
<div class="sf_admin_form widget-body"
	id="sf_fieldset_receivable_student">
	<form class="form-horizontal fv-form fv-form-bootstrap" id="ps-form"
		data-fv-addons="i18n" method="post"
		action="<?php echo url_for('@ps_receivable_students_batch_update')?>"
		novalidate="novalidate">
		<input type="hidden" name="sf_method" value="put"> <input
			type="hidden" name="receipt[id]"
			value="<?php echo $receipt->getId();?>"> <input type="hidden"
			name="receipt[sid]" value="<?php echo $receipt->getStudentId();?>">
		<fieldset>
			<legend>
		<?php echo __('List of revenues of month %%month%%', array('%%month%%' => format_date($receipt->getReceiptDate(), "MM-yyyy"))) ?>
	</legend>
			<div class="row">
	  <?php
			if ($sf_user->hasFlash ( 'err_receivable_student' )) {
				$err_receivable_student = $sf_user->getFlash ( 'err_receivable_student' );
			}
			?>
		<?php if ($sf_user->hasFlash('notice_receivable_student')):?>
		  <div class="alert alert-success no-margin fade in">
					<button class="close" data-dismiss="alert">×</button>
					<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice_receivable_student')) ?>
		  </div>
		<?php endif;?>
		
		<?php if ($sf_user->hasFlash('error_receivable_student')):?>
		  <div class="alert alert-danger no-margin fade in">
					<button class="close" data-dismiss="alert">×</button>
					<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error_receivable_student')) ?>
		  </div>
		<?php endif;?>
	  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">
							<thead>
								<tr style="background-color: #fff;">
									<th colspan="5" class="text-left"
										style="background-color: #fff;"><b>1.<?php echo __('Payment fees for previous month')?></b></th>
									<th style="background-color: #fff; border: none;">&nbsp;</th>
									<th style="background-color: #fff;" class="text-right"></th>
									<th style="background-color: #fff; border: none;"
										class="text-right"></th>
									<th style="background-color: #fff;">&nbsp;</th>
									<th style="background-color: #fff;">&nbsp;</th>
									<th style="background-color: #fff;">&nbsp;</th>
								</tr>
								<tr>
									<th class="text-center" style="width: 50px;"><?php echo __('Month');?></th>
									<th><?php echo __('Name fees');?></th>
									<th class="text-right"><?php echo __('Price');?></th>
									<th class="text-center" style="width: 100px;"><span
										rel="tooltip" data-placement="top"
										data-original-title="<?php echo __('Quantily expected full');?>"><?php echo __('Quantily expected');?></span></th>
									<th class="text-center" style="width: 100px;"><?php echo __('Discount fixed');?></th>
									<th class="text-center" style="width: 100px;"><?php echo __('Discount');?></th>
									<th class="text-right" style="width: 120px;"><?php echo __('Temporary money');?></th>
									<th class="text-center" style="width: 100px;"><?php echo __('Used');?></th>
									<th class="text-right" style="width: 120px;"><?php echo __('Actual costs')?></th>
									<th style="width: 150px;"><?php echo __('Note')?></th>
									<th style="width: 80px;"><?php echo __('Action')?></th>
								</tr>
							</thead>
							<tbody>
				<?php
				foreach ( $receivable_student as $r_s ) :
					// Cac tháng cũ
					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) :
						?>
				<tr id="tr_rs_receivable_student_<?php echo $r_s->getId();?>">
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
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice(),0, '', '')?></td>
									<td class="text-center"><input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_by_number]"
										type="number" min="0" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%; text-align: center;"
										value="<?php echo PreNumber::number_format($r_s->getRsByNumber(),0, '', '');?>">
									</td>
									<td class="text-right"
										<?php echo isset($err_receivable_student[$r_s->getId()]['rs_discount_amount']) ? $err_receivable_student[$r_s->getId()]['rs_discount_amount'] : '';?>>
										<input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_discount_amount]"
										type="number" min="0" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%"
										value="<?php echo ($r_s->getRsDiscountAmount() > 0) ? PreNumber::number_format($r_s->getRsDiscountAmount(),0, '', '') : '';?>">
									</td>
									<td class="text-center"><input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_discount]"
										type="number" min="0" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%"
										value="<?php echo ($r_s->getRsDiscount() > 0) ? PreNumber::number_format($r_s->getRsDiscount(),0, '', '') : '';?>">
									</td>
									<td class="text-right">
						<?php
						// Phi du kien - Tạm tính
						if ($r_s->getRsServiceId () > 0) {
							$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

							$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						} else { // Nếu là khoản phải thu khác
						         // $rs_amount = $r_s->getRsAmount();

							$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						}

						if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
							$rs_amount = 0;
						}
						?>
						<?php echo PreNumber::number_format($rs_amount);?>
					</td>
									<td class="text-center">
						<?php 
// So luong su dung de tinh tien
						$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
						?>
						<input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_spent_number]"
										type="number" min="0" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%; text-align: center;"
										value="<?php echo PreNumber::number_format($spentNumber,0, '', '');?>">
						<?php if ($r_s->getRsIsLate() == 1) echo ' '.__('Minute');?>
					</td>
									<td class="text-right"><input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_amount]"
										type="number" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%; text-align: right;"
										value="<?php echo PreNumber::number_format($r_s->getRsAmount(),0, '', '');?>">
									</td>
									<td class="text-center"><input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_note]"
										type="text" maxlength="255" class="form-control"
										style="width: 100%;"
										value="<?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?>">
									</td>
									<td class="text-center"></td>
								</tr>
				<?php endif;

				endforeach
				;
				?>
				
				<tr style="background-color: #fff;">
									<th colspan="5" class="text-left"
										style="background-color: #fff;"><b>2.<?php echo __('Estimated fees this month')?></b></th>
									<th style="background-color: #fff;">&nbsp;</th>
									<th style="background-color: #fff;" class="text-right"></th>
									<th style="background-color: #fff;" class="text-right"></th>
									<th style="background-color: #fff;">&nbsp;</th>
									<th style="background-color: #fff;">&nbsp;</th>
									<th style="background-color: #fff;">&nbsp;</th>
								</tr>

								<tr>
									<th class="text-center"><?php echo __('Month');?></th>
									<th><?php echo __('Name fees');?></th>
									<th class="text-right"><?php echo __('Price');?></th>
									<th class="text-center" style="width: 70px;"><span
										rel="tooltip" data-placement="top"
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
				foreach ( $receivable_student as $r_s ) :
					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) :
						?>
				<tr id="tr_rs_receivable_student_<?php echo $r_s->getId();?>">
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
									<td class="text-center"><input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_by_number]"
										type="number" min="0" pattern="([0-9]{1,3}).([0-9]{1,3})"
										class="form-control" style="width: 100%; text-align: center;"
										value="<?php echo PreNumber::number_format($r_s->getRsByNumber(),0, '', '');?>">
									</td>
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
						// echo PreNumber::number_format($rs_amount);
						?>
						<input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_amount]"
										type="number" pattern="([0-9]{1,3}).-([0-9]{1,3})"
										class="form-control" style="width: 100%; text-align: right;"
										value="<?php echo PreNumber::number_format($r_s->getRsAmount(), 0,'','');?>">
									</td>
									<td></td>
									<td></td>
									<td
										class="<?php echo isset($err_receivable_student[$r_s->getId()]['rs_note']) ? $err_receivable_student[$r_s->getId()]['rs_note'] : '';?>">
										<input
										name="receivable_student[<?php echo $r_s->getId();?>][rs_note]"
										type="text" maxlength="255" class="form-control"
										style="width: 100%;" value="<?php echo $r_s->getRsNote();?>">
									</td>
									<td class="text-center">
					<?php if($receipt->getPaymentStatus () == PreSchool::NOT_ACTIVE && $sf_user->hasCredential('PS_FEE_REPORT_DELETE')){?>
    				<a data-toggle="modal" data-target="#confirmDeleteService"
										data-backdrop="static"
										class="btn btn-xs btn-default btn-danger btn-delete-service"
										data-item="<?php echo $r_s->getId()?>"><i
											class="fa-fw fa fa-times" title="<?php echo __('Delete')?>"></i></a>
    				<?php }?>
					</td>
								</tr>			
				<?php endif;
					endforeach;
				?>
			</tbody>
						</table>
					</div>
					<div class="form-actions">
						<div class="sf_admin_actions">
							<a
								class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
								href="<?php echo url_for('@ps_receipts')?>"><i
								class="fa-fw fa fa-list-ul"
								title="<?php echo __('Back to list');?>"></i> <?php echo __('Back to list');?></a>	
				
				<?php if ($receipt->getPaymentStatus () != PreSchool::ACTIVE):?>				
					<button type="submit"
								class="btn btn-default btn-success btn-sm btn-psadmin">
								<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
									title="Lưu lại"></i> <?php echo __('Save');?></button>	
				<?php endif;?>
			</div>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>