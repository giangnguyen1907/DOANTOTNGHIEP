<?php use_helper('I18N', 'Date')?>
<script type="text/javascript">
$('#remoteModal').on('hide.bs.modal', function(e) {
	$(this).removeData('bs.modal');
});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo $student->getFirstName()." ".$student->getLastName()." - "."<code>".$student->getStudentCode()."</code>" ?>
		<small>(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)</small>
	</h4>
</div>
<div class="modal-body" style="overflow: hidden;">
<?php echo __('Receivables months')?>: <?php echo format_date($kstime, "MM-yyyy");?>
	<div class="table-responsive" style="height: auto;">
	<?php //echo count($service_students);die;?>
	<?php if ($sf_user->hasCredential(array('PS_FEE_RECEIVABLE_DELETE'))): ?>
	<span id="list_receivable_receivable_students">
		<?php include_partial('psFeeReports/box/_list_receivable_receivable_students', array('receivable_students' => $receivable_students,'service_students' => $service_students)) ?>
	</span>
	<?php else:?>
	<table id="dt_basic_receivable"
			class="display table table-striped table-bordered table-hover"
			width="100%">
			<thead>
				<tr>
					<th style="width: 20%"><?php echo __('Name');?></th>
					<th style="width: 10%"><?php echo __('Amount')?></th>
					<th style="width: 10%"><?php echo __('Number')?></th>
					<th colspan="3"><?php echo __('Note');?></th>
					<th class="text-center no-order" style="width: 150px"><?php echo __('Updated by');?></th>
				</tr>
			</thead>
			<tbody>
		<?php if(count($receivable_students) > 0){?>
    		<?php foreach ( $receivable_students as $receivable) :?>
    		<tr>
					<td><?php echo $receivable->getTitle()?></td>
					<td><?php echo PreNumber::number_format($receivable->getAmount())?></td>
					<td class="text-center"><?php echo PreNumber::number_format($receivable->getIsNumber())?></td>
					<td><?php echo $receivable->getNote()?></td>
					<td class="text-center">
    			<?php echo $receivable->getUpdatedBy();?><br />
    			<?php echo false !== strtotime($receivable->getUpdatedAt()) ? format_date($receivable->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
    			</td>
				</tr>
    		<?php endforeach;?>
		<?php }else{?>
    	<tr>
					<td colspan="7"><?php echo __('Not receivables');?></td>
				</tr>
    	<?php }?>
    	
    	<tr>
			<th style="width: 25%"><?php echo __('Service name');?></th>
			<th style="width: 10%" class="text-center"><?php echo __('Amount')?></th>
			<th style="width: 10%" class="text-center"><?php echo __('By number')?></th>
			<th style="width: 14%" class="text-center"><?php echo __('Discount fixed')?></th>
			<th style="width: 11%" class="text-center"><?php echo __('Discount')?></th>
			<th style="width: 10%" class="text-center"><?php echo __('Total')?></th>
			<th style="width: 20%" class="text-center"><?php echo __('Updated by')?></th>
		</tr>
    	
    	<?php if(count($service_students) > 0){?>
        	<?php foreach ( $service_students as $service) :?>
        	<tr>
        		<?php
				// giảm trừ %
				$discount = ($service->getAmount () * $service->getDiscount ()) / 100;
				$amount = $service->getAmount () - $discount - $service->getDiscountAmount ();
				?>
        		<td><?php echo $service->getTitle()?></td>
					<td class="text-right"><?php echo PreNumber::number_format($service->getAmount());?></td>
					<td class="text-center"><?php echo PreNumber::number_format($service->getByNumber());?></td>
					<td class="text-right"><?php echo PreNumber::number_format($service->getDiscountAmount())?></td>
					<td class="text-center"><?php echo $service->getDiscount()?></td>
					<td class="text-right"><?php echo PreNumber::number_format($amount);?></td>
					<td class="text-center">
    			<?php echo $service->getUpdatedBy();?><br />
    			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
    			</td>
				</tr>
        	<?php endforeach;?>
        <?php }else{?>
        <tr>
					<td colspan="7"><?php echo __('Not service');?></td>
				</tr>
        <?php }?>
		</tbody>
		</table>

	<?php endif;?>
	</div>
</div>
<div class="modal-footer">
	<button type="button"
		class="btn btn-default btn-sm btn-psadmin btn-cancel"
		data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i> <?php echo __('Close')?></button>
</div>