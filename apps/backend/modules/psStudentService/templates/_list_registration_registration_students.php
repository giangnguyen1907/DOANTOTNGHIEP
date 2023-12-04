<script type="text/javascript">
$(document).ready(function() {

	$('#confirmDeleteReceivableStudent').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('.btn-confirm-remover-receivable-student-class').click(function(e){
		var item_id = $(this).attr('data-item');
		$('#rs_id').val(item_id);

		$('#notice_receivable').hide();
		
		if (confirm("<?php echo __('Confirm remove service of student')?>")) {
			if (item_id > 0) {
				$.ajax({
					url: '<?php echo url_for('@ps_service_registration_student_delete_ajax')?>',
			        type: 'POST',
			        data: 'rs_id=' + item_id,
			        success: function(data) {
			        	$("#list_receivable_receivable_students").html("Xóa thành công");
				    	$("#list_receivable_receivable_students").html(data);					
			        }
				});		    
		    } else {
				return false;
			}
		}
		
		return;
	});

	$('.btn-remover-receivable-student-class').click(function(e){
		var item_id = $(this).attr('data-item');
		$('#rs_id').val(item_id);

		$('#notice_receivable').hide();
		
		if (confirm("<?php echo __('Restore service of student')?>")) {
			if (item_id > 0) {
				$.ajax({
					url: '<?php echo url_for('@ps_service_registration_student_delete_ajax')?>',
			        type: 'POST',
			        data: 'rs_id=' + item_id,
			        success: function(data) {
			        	$("#list_receivable_receivable_students").html("Khôi phục thành công");
				    	$("#list_receivable_receivable_students").html(data);					
			        }
				});		    
		    } else {
				return false;
			}
		}
		return;

	});	
});
</script>
<?php use_helper('I18N', 'Date') ?>
<?php if ($sf_user->hasFlash('notice_receivable')): ?>
<div class="alert alert-success no-margin fade in"
	id="notice_receivable">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice_receivable')) ?>
  </div>
<?php endif; ?>
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
			<th class="text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="9"><b><?php echo __ ( 'Service registration' ) ?></b></td>
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
			<td class="text-center"><a
				class="btn btn-xs btn-default btn-confirm-remover-receivable-student-class"
				data-item="<?php echo $service->getSsId()?>"><i
					class="fa-fw fa fa-times txt-color-red"
					title="<?php echo __('Delete')?>"></i></a></td>
		</tr>
	<?php }?>
	<?php endforeach;?>
	
	<tr>
			<td colspan="9"><b><?php echo __ ( 'Service registration remove' ) ?></b>
			</td>
		</tr>
	<?php foreach ( $list_service_notusing as $service ) :
		?>
		<?php //if($service->getDeleteTime() !=''){ ?>
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
			<td class="text-center">
				<!--  
				<a class="btn btn-xs btn-default btn-remover-receivable-student-class"
				data-item="<?php echo $service->getSsId()?>"><i
					class="fa-fw fa fa-rotate-right txt-color-red"
					title="<?php echo __('Restore')?>"></i></a>-->
			</td>
		</tr>
		<?php //}?>
		<?php endforeach;?>
	</tbody>
</table>

