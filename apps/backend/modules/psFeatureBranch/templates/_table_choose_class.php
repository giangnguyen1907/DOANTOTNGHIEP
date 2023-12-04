<?php
$ps_customer_id = $feature_branch->getFeature ()
	->getPsCustomerId ();
// Lay danh sach cơ so dao tao
$list_ps_workplaces = Doctrine::getTable ( 'PsWorkPlaces' )->getListShortWorkPlacesByCustomerId ( $ps_customer_id );
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="custom-scroll table-responsive"
		style="height: 290px; overflow-y: scroll;">
		<table id="dt_basic_class"
			class="table table-striped table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th class="no-order"><?php echo __('Class name');?></th>
					<th class="no-order"><?php echo __('Obj group title');?></th>
					<th class="no-order"><?php echo __('HTeacher');?></th>
					<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose') ?></th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ( $list_ps_workplaces as $workplace ) {?>						
						<tr>
					<td><b><?php echo $workplace->getTitle();?></b></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>						
						<?php foreach($list_myclass as $obj ) {?>
						<?php if ($workplace->getId() == $obj->getWorkplaceId()) {?>
						<tr>
					<td style="padding-left: 20px;"><?php echo $obj->getClassName();?>
							<small><?php echo $obj->getRoomName();?></small></td>
					<td class="w-20"><?php echo $obj->getGroupName();?></td>
					<td class="w-20"></td>
					<td class="w-10 text-center"><label class="checkbox-inline"> <input
							class="select checkbox" type="checkbox"
							name="form_ps_feature_branch_myclass[]"
							id="form_ps_feature_branch_myclass_<?php echo $obj->getId();?>_select"
							value="<?php echo $obj->getId();?>"><span></span>
					</label></td>
						<?php }?>
						</tr>
					<?php }?>
					<?php }?>
				</tbody>
		</table>
	</div>
</div>