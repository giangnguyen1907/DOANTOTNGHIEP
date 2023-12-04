<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _list_service.php
 * @filecomment filecomment
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 22-08-2017 -  01:17:58
 */
// $services = Doctrine::getTable ( 'Service' )->getServicesAndClassService ( $form->getObject()->getId())->execute();
$params = array ();
$params ['school_year_id'] = $form->getObject ()
	->getSchoolYearId ();
$params ['ps_customer_id'] = $form->getObject ()
	->getPsCustomerId ();
$params ['ps_workplace_id'] = $form->getObject ()
	->getPsClassRooms ()
	->getPsWorkplaceId ();
$params ['myclass_id'] = $form->getObject ()
	->getId ();
$params ['is_activated'] = PreSchool::ACTIVE;

$services = Doctrine::getTable ( 'Service' )->setSQLServiceByParams ( $params )
	->execute ();
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="custom-scroll table-responsive" style="<?php if (count($services) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
			<table id="dt_basic_student"
				class="display table table-striped table-bordered table-hover form-inline"
				width="100%">
				<thead>
					<tr>
						<th style="width: 50px;" class="text-center no-order"><?php echo __('Image');?></th>
						<th style="width: auto;"><?php echo __('Title');?></th>
						<th><?php echo __('Note');?></th>
						<th class="text-center"><?php echo __('Status in class');?></th>
						<th class="text-center"><?php echo __('Day to class');?></th>
						<th class="text-center no-order" style="width: 60px;"><?php echo __('Actions', array(), 'sf_admin')?></th>
					</tr>
				</thead>

				<tbody>
			
			<?php foreach ($services as $service) :?>
			<tr>
						<td class="text-center">
				<?php
				$image_file = $service->getFileName ();
				if ($image_file != '') {
					echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">' . image_tag ( '/sys_icon/' . $image_file, array (
							'style' => 'max-width:35px;text-align:center;' ) ) . '</div>';
				}
				?>
				</td>

						<td><?php echo $service->getTitle();?></td>
						<td><?php echo $service->getNote();?></td>
						<td></td>
						<td></td>
						<td class="text-center">
					<?php if ($service->getIsActivated() == PreSchool::ACTIVE):?>
						<label class="checkbox-inline"> <input class="checkbox style-0"
								id="my_class_services_list_<?php echo $service->getId();?>"
								name="my_class[services_list][]" type="checkbox"
								value="<?php echo $service->getId();?>"
								<?php echo ($service->getId() == $service->getCsServiceId()) ? 'checked="checked"' : '';?>>
								<span></span>
						</label>
					<?php else:?>						
						<label class="checkbox-inline"> <input class="checkbox style-0"
								name="service_activated[]" type="checkbox" disabled="disabled"
								value="<?php echo $service->getId();?>"
								<?php echo ($service->getId() == $service->getCsServiceId()) ? 'checked="checked"' : '';?> />
								<span></span>
						</label>												
					<?php endif;?>
				</td>
					</tr>
			
			<?php endforeach;?>
			</tbody>
			</table>
		</div>
	</div>
</div>