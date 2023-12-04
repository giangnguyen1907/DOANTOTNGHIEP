<?php use_helper('I18N', 'Date')?>
<style>
@media ( min-width : 992px) .modal-lg {
	min-width
	:
	 
	900
	px
	;
	
	    
	width
	:
	 
	1200
	px
	;
	
	
}

.modal-lg {
	min-width: 900px;
	width: 1200px;
}
</style>
<script type="text/javascript">

$('#remoteModal').on('hide.bs.modal', function(e) {
	$(this).removeData('bs.modal');
});

</script>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo $student->getFirstName()." ".$student->getLastName()?>
		<small>( <i><?php echo $student->getStudentCode()?></i> )
		</small>
	</h4>
</div>
<div class="modal-body" style="overflow: hidden;">
<?php //echo __('School year').': '.$kstime->getTitle();?>
	<div class="table-responsive" style="height: auto;">
	  
	<?php if ($sf_user->hasCredential(array('PS_STUDENT_SERVICE_REGISTER_STUDENT'))): ?>
	<span id="list_receivable_receivable_students">
		<?php include_partial('psStudentService/list_registration_registration_students', array('list_service' => $list_service,'list_service_notusing'=> $list_service_notusing)) ?>
	</span>
	<?php else:?>	
	<table id="dt_basic_receivable"
	class="display table table-striped table-bordered table-hover"
	width="100%">
	<thead>
		<tr>
			<th style="width: 250px;"><?php echo __('Service name');?></th>
			<th class="text-center" style="width: 200px;"><?php echo __('Service amount');?></th>
			<th class="text-center" style="width: 100px;"><?php echo __('Service detail at');?></th>
			<th class="text-center"><?php echo __('By number');?></th>
			<th class="text-center"><?php echo __('Discount fixed');?></th>
			<th class="text-center"><?php echo __('Discount');?></th>
			<th class="text-center" style="width: 110px;"><?php echo __('Total money');?></th>
			<th class="text-center" style="width: 200px;"><?php echo __ ( 'Updated by' );?></th>
			
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="8"><b><?php echo __ ( 'Service registration' ) ?></b></td>
		</tr>
	<?php
	foreach ( $list_service as $service ) :
		?>
	<?php if($service->getDeleteTime() ==''){ ?>
		<tr>
			<td><?php echo $service->getTitle();?><br /> <small
				style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
			</td>
			<?php $servicedetails = $service->getServiceDetailByDate(time());?>
			<td class="text-right"><?php echo PreNumber::number_format($servicedetails['amount']);?></td>
			<td class="text-center"><code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code></td>
			<td class="text-center"><?php echo $servicedetails['by_number'];?></td>
			<td class="text-center"><?php echo PreNumber::number_format($service->getDiscountAmount());?></td>
			<td class="text-center"><?php echo $service->getDiscount();?></td>
			<td class="text-right"><?php echo PreNumber::number_format(($servicedetails['amount']*$servicedetails['by_number']*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
			<td class="text-center">
			<?php echo $service->getUpdatedBy() ?><br />
			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
			</td>
		</tr>
	<?php }?>
	<?php endforeach;?>
	
		<tr>
			<td colspan="8"><b><?php echo __ ( 'Service registration remove' ) ?></b> </td>
		</tr>
	<?php foreach ( $list_service_notusing as $service ) : ?>
		
		<tr>
			<td id="row-<?php echo $service->getSsId()?>"><?php echo $service->getTitle();?><br />
				<small style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
			</td>	
			
			<td class="text-right"><?php echo PreNumber::number_format($service->getAmount());?></td>
			<td class="text-center"><code><?php echo false !== strtotime($service->getDetailAt()) ? format_date($service->getDetailAt(), "MM/yyyy") : '&nbsp;' ?></code>
			</td>
			<td class="text-center"><?php echo PreNumber::number_clean($service->getByNumber());?></td>
			<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscountAmount());?></td>
			<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscount());?></td>
			<td class="text-right"><?php echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>			
			<td class="text-center">
			<?php echo $service->getUpdatedBy() ?><br />
  			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
			</td>
		</tr>
		<?php endforeach;?>
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