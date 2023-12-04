<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _list_relative.php
 * @filecomment Danh sach phu huyenh cua hoc sinh
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 21-10-2017 -  01:17:58
 */
$enable_roll = PreSchool::loadPsRoll ();
?>
<div class="custom-scroll table-responsive"
	style="height: 400px; overflow-y: scroll;">
	<table id="dt_basic" class="table table-striped table-bordered"
		width="100%">
		<thead>
			<tr class="info">
				<th style="width: 55px;" class="text-center"><?php echo __('Icon');?></th>
				<th style="width: 250px;"><?php echo __('Service name');?></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Enable roll');?></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Service amount');?></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Service detail at');?></th>
				<th class="text-center"><?php echo __('By number');?></th>
				<th class="text-center"><?php echo __('Discount fixed');?></th>
				<th class="text-center"><?php echo __('Discount');?></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Total money');?></th>
				<th class="text-center"><?php echo __('Note');?></th>
				<th class="text-center" style="width: 200px;"><?php echo __ ( 'Updated by' );?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $list_service as $service ) :
			?>
		
		<?php if ($service->getDeleteTime() == '' ):?>
		<tr>
				<td class="text-center">			
			<?php
				if ($service->getFileName () != '') {
					echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
							'style' => 'max-width:35px;text-align:center;' ) );
				}
				?>
			</td>
				<td><?php echo $service->getTitle();?><br /> <small
					style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
				</td>
				<td>
			<?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?>
			<?php if ($service->getEnableSchedule() == 1 ) echo '<br><code>'.__('Subject').'</code>';?>
			</td>			
			<?php $servicedetails = $service->getServiceDetailByDate(time());?>
			<td class="text-right"><?php echo PreNumber::number_format($servicedetails['amount']);?></td>
				<td class="text-center"><code><?php echo format_date($servicedetails['detail_at'],"MM/yyyy" )?></code></td>
				<td class="text-center"><?php echo PreNumber::number_clean($servicedetails['by_number']);?></td>
				<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscountAmount());?></td>
				<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscount());?></td>
				<td class="text-right"><?php echo PreNumber::number_format(($servicedetails['amount']*$servicedetails['by_number']*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
				<td class=""><?php echo $service->getNote() ?></td>
				<td class="text-center">
			<?php echo $service->getUpdatedBy() ?><br />
  			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
			</td>
		</tr>
		<?php endif;?>
		<?php endforeach;?>
		<?php if(count($list_service) > 0):?>		
		<tr>
				<td colspan="11"><strong><?php echo __('Service unregistered')?></strong></td>
			</tr>		
		<?php endif;?>
		<?php
		foreach ( $list_service_notusing as $service ) :
			?>
		<tr class="highlight">
					<td class="text-center">			
			<?php
			if ($service->getFileName () != '') {
				echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
						'style' => 'max-width:35px;text-align:center;' ) );
			}
			?>
			</td>
					<td id="row-<?php echo $service->getSsId()?>"><?php echo $service->getTitle();?><br />
						<small style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
					</td>
					<td>
			<?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?>
			<?php if ($service->getEnableSchedule() == 1 ) echo '<br><code>'.__('Subject').'</code>';?>
			</td>

					<td class="text-right"><?php echo PreNumber::number_format($service->getAmount());?></td>
					<td class="text-center"><code><?php echo false !== strtotime($service->getDetailAt()) ? format_date($service->getDetailAt(), "MM/yyyy") : '&nbsp;' ?></code>
					</td>
					<td class="text-center"><?php echo PreNumber::number_clean($service->getByNumber());?></td>
					<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscountAmount());?></td>
					<td class="text-center"><?php echo PreNumber::number_clean($service->getDiscount());?></td>
					<td class="text-right"><?php echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
					<td class="text-center"></td>
					<td class="text-center">
			<?php echo $service->getUpdatedBy() ?><br />
  			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
			</td>
					<td class="text-center"></td>
					
				</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>