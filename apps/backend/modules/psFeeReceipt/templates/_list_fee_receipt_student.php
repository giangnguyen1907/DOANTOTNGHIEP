<?php

$fee_receipt_id = $form->getObject ()
	->getId ();
$fee_receipt_student = Doctrine::getTable ( 'PsFeeReceivableStudent' )->getAllReceiptStudent ( $fee_receipt_id );
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar text-right">    	
        <?php if ($sf_user->hasCredential(array('PS_FEE_RECEIPT_NOTICATION_ADD', 'PS_FEE_RECEIPT_NOTICATION_EDIT'))):?>
        <a rel="tooltip" data-placement="left"
			data-original-title="<?php echo __('Add receipt');?>"
			data-html="true" data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin"
			href="<?php echo url_for('@ps_fee_receivable_student_new?fbid='.$form->getObject()->getId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif;?>
	</div>
</div>
<div class="custom-scroll table-responsive">
	<table class="table table-bordered table-hover no-footer no-padding">
		<thead>
			<tr>
				<th class="col-md-3"><?php echo __('Receivable name')?></th>
				<th class="text-center col-md-2"><?php echo __('Amount')?></th>
				<th class="text-center col-md-1"><?php echo __('Spent number')?></th>
				<th class="text-center col-md-3"><?php echo __('Note')?></th>
				<th class="text-center col-md-2"><?php echo __('Updated by')?></th>
				<th class="text-center col-md-1"><?php echo __('Actions')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($fee_receipt_student as $fee_receipt){ ?>
		<tr>
				<td>
				<?php echo $fee_receipt->getTitle()?>
			</td>
				<td class="text-center">
				<?php echo number_format($fee_receipt->getAmount(),0,",",".");?>
			</td>
				<td class="text-center">
				<?php echo number_format($fee_receipt->getSpentNumber(),0,",",".");?>
			</td>
				<td>
				<?php echo $fee_receipt->getNote()?>
			</td>
				<td class="text-center">
				<?php echo $fee_receipt->getUpdatedBy()?>
				<br>
  				<?php echo false !== strtotime($fee_receipt->getUpdatedAt()) ? format_date($fee_receipt->getUpdatedAt(), "HH:mm  dd-MM-yyyy") : '&nbsp;' ?>
			</td>
				<td style="border-left: none;" class="text-center">
					<div class="btn-group">
    				<?php if ($sf_user->hasCredential('PS_FEE_RECEIPT_NOTICATION_EDIT')):?>
    				<a data-toggle="modal" data-target="#remoteModal"
							data-backdrop="static" class="btn btn-xs btn-default"
							href="<?php echo url_for('@ps_fee_receivable_student_edit?id='.$fee_receipt->getId())?>"><i
							class="fa-fw fa fa-pencil txt-color-orange"
							title="<?php echo __('Edit', array())?>"></i></a>
    				<?php endif; ?>
    				<?php if ($sf_user->hasCredential('PS_FEE_RECEIPT_NOTICATION_DELETE')):?>
    				<a data-toggle="modal" data-target="#confirmDelete"
							data-backdrop="static"
							class="btn btn-xs btn-default btn-delete-item pull-right"
							data-item="<?php echo $fee_receipt->getId()?>"><i
							class="fa-fw fa fa-times txt-color-red"
							title="<?php echo __('Delete')?>"></i></a>
    				<?php endif; ?>
    			</div>
				</td>
			</tr>
		<?php }?>
	</tbody>
	</table>
</div>