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
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar"> 
        <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_REGISTER_STUDENT')):?>
        <a data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_student_service_new?student_id='.$form->getObject()->getId())?>"><i
			class="fa-fw fa fa-plus"></i><?php echo __('Add service')?></a>
        <?php endif;?>
    </div>
	<div class="custom-scroll table-responsive"
		style="height: 300px; overflow-y: scroll;">
		<table id="dt_basic" class="table table-striped table-bordered"
			width="100%">
			<thead>
				<tr>
					<th style="width: 55px;" class="text-center"><?php echo __('Icon');?></th>
					<th><?php echo __('Service name');?></th>
					<th class="text-center" style="width: 120px;"><?php echo __('Enable roll');?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Service amount');?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Service detail at');?></th>
					<th class="text-center" style="width: 80px;"><?php echo __('By number');?></th>
					<th class="text-center" style="width: 110px;"><?php echo __('Total money');?></th>
					<th class="text-center"><?php echo __('Tần xuất thu');?></th>
					<th class="text-center" style="width: 200px;"><?php echo __ ( 'Updated by' );?></th>
					<th class="text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
				</tr>
			</thead>
			<tbody>
		<?php
		foreach ( $list_service ['list_service'] as $service ) :
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

					<td class="text-right"><?php echo PreNumber::number_format($service->getAmount());?></td>
					<td class="text-center"><code><?php echo false !== strtotime($service->getDetailAt()) ? format_date($service->getDetailAt(), "MM/yyyy") : '&nbsp;' ?></code>
					</td>
					<td class="text-center"><?php echo PreNumber::number_format($service->getByNumber());?></td>
					<td class="text-right"><?php echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
					<td class="text-center">
					<?php echo $service->getNumberMonth() ?> tháng
					</td>
					<td class="text-center">
					<?php echo $service->getUpdatedBy() ?><br />
		  			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
					</td>
					<td class="text-center">
						<div class="btn-group">
						<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_REGISTER_STUDENT')):?>
							<a data-toggle="modal" data-target="#remoteModal"
								data-backdrop="static" class="btn btn-xs btn-default"
								href="<?php echo url_for('@ps_student_service_edit?id='.$service->getStudentServicesId())?>"><i
								class="fa-fw fa fa-pencil txt-color-orange"
								title="<?php echo __('Edit', array())?>"></i></a> 
							<a
								data-toggle="modal" data-target="#confirmDeleteService"
								data-backdrop="static"
								class="btn btn-xs btn-default btn-delete-service pull-right"
								data-item="<?php echo $service->getStudentServicesId()?>"><i
								class="fa-fw fa fa-times txt-color-red"
								title="<?php echo __('Delete')?>"></i></a>
	     				<?php endif;?>
     					</div>
					</td>
				</tr>
		<?php endif;?>
		<?php endforeach;?>
		<?php if(count($list_service['list_service_notusing']) > 0):?>		
		<tr>
					<td colspan="12"><strong><?php echo __('Service unregistered')?></strong></td>
				</tr>		
		<?php endif;?>
		<?php
		foreach ( $list_service ['list_service_notusing'] as $service ) :
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
					<td class="text-right"><?php echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
					<td class="text-center"></td>
					<td class="text-center">
			<?php echo $service->getUpdatedBy() ?><br/>
  			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
			</td>
					<td class="text-center"></td>
					
				</tr>
		<?php endforeach;?>
		</tbody>
		</table>
	</div>
</div>