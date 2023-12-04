<script type="text/javascript">
$(document).ready(function() {

	$('#confirmDeleteReceivableStudent').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('.btn-confirm-remover-receivable-student-class').click(function(e){
		var item_id = $(this).attr('data-item');
		$('#rs_id').val(item_id);

		$('#notice_receivable').hide();
		//alert(item_id);
		if (confirm("<?php echo __('Confirm remove receivable in the month')?>")) {
			if (item_id > 0) {
				$.ajax({
					url: '<?php echo url_for('@ps_receivable_students_delete_ajax')?>',
			        type: 'POST',
			        data: 'rs_id=' + item_id,
			        success: function(data) {
			        	$("#list_receivable_receivable_students").html("Xóa thành công");
				    	$("#list_receivable_receivable_students").html(data);					
			        },
			        error: function (request, error) {
			            alert("Can't delete data" + error);
			        },
				});		    
		    } else {
				return false;
			}
		}
		
		return;
	});

	$('.btn-remover-receivable-student-class').click(function(e){
		var item_id = $('#rs_id').val();		
		if (item_id > 0) {
			$.ajax({
				url: '<?php echo url_for('@ps_receivable_students_delete_ajax')?>',
		        type: 'POST',
		        data: 'rs_id=' + item_id,
		        success: function(data) {
		        	$("#list_receivable_receivable_students").html(data);					
		        },
		        error: function (request, error) {
		            alert("Can't delete data");
		        },
			});		    
	    } else {
			return false;
		}
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
			<th style="width: 20%"><?php echo __('Name');?></th>
			<th style="width: 10%"><?php echo __('Amount')?></th>
			<th style="width: 10%"><?php echo __('Number')?></th>
			<th colspan="3"><?php echo __('Note');?></th>
			<th class="text-center no-order" style="width: 150px"><?php echo __('Updated by');?></th>
			<th class="text-center no-order" style="width: 85px;"><?php echo __('Actions')?></th>
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
			<td class="text-center"><a
				class="btn btn-xs btn-default btn-confirm-remover-receivable-student-class"
				data-item="<?php echo $receivable->getId()?>"><i
					class="fa-fw fa fa-times txt-color-red"
					title="<?php echo __('Delete')?>"></i></a></td>
		</tr>
    	<?php endforeach;?>
	<?php }else{?>
	<tr>
			<td colspan="8"><?php echo __('Not receivables');?></td>
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
			<th style="width: 20%" class="text-center"></th>
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
    		<td></td>
		</tr>
    	<?php endforeach;?>
	<?php }else{?>
	<tr>
			<td colspan="8"><?php echo __('Not service');?></td>
		</tr>
	<?php }?>
	</tbody>
	
</table>


