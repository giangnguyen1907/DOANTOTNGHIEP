<?php
/**
 * 
 * @file _form_feild_department.php
 * @file comment: Danh sach phong ban cua giao vien duoc chon
 */
$track_at = null;
$list_member_department = $form->getObject ()
	->getPsMemberDepartment ( $track_at );
$url_callback = PsEndCode::ps64EndCode ( (sfContext::getInstance ()->getRouting ()
	->getCurrentRouteName () . '?id=' . $form->getObject ()
	->getId ()) );
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">    	
        <?php if ($sf_user->hasCredential('PS_HR_HR_ADD')):?>
        <a data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_member_departments_new?ps_member_id='.$form->getObject()->getId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif;?>        
    </div>
	<div class="custom-scroll table-responsive" style="<?php if (count($list_member_department) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
		<table id="dt_basic_member_department"
			class="display table table-striped table-bordered table-hover"
			width="100%">
			<thead>
				<tr>
					<th></th>
					<th class="text-center"><?php echo __('Department'); ?></th>
					<th class="text-center"><?php echo __('Function'); ?></th>
					<th class="text-center"><?php echo __('Note'); ?></th>
					<th class="text-center"><?php echo __('Is current'); ?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Start At'); ?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Stop At'); ?></th>
					<th><?php echo __('Updated By'); ?></th>
					<th class="no-order text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list_member_department as $key => $member_department):?>
				<tr>
					<td><?php echo ($key+1) ?></td>
					<td><?php echo $member_department->getDTitle()?></td>
					<td class="text-center"><?php echo $member_department->getFcTitle()?></td>
					<td class="text-center"><?php echo $member_department->getNote()?></td>
					<td class="text-center">
					<?php echo get_partial('psMember/list_field_boolean', array('value' => $member_department->getIsCurrent())) ?>
					</td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($member_department->getStartAt())) ? format_date($member_department->getStartAt(), "dd/MM/yyyy") : '';?>
						</div>
					</td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($member_department->getStopAt())) ? format_date($member_department->getStopAt(), "dd/MM/yyyy") : '';?>
						</div>
					</td>
					<td>
						<?php echo $member_department->getUpdatedBy()?>
						<?php echo ' - '?>
						<?php echo (false !== strtotime($member_department->getUpdatedAt())) ? format_date($member_department->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
					</td>
					<td class="text-center">
						<div class="btn-group">							
						<?php if ($sf_user->hasCredential ( 'PS_HR_HR_EDIT' )) :?>							
						<a data-toggle="modal" data-target="#remoteModal"
								data-backdrop="static" class="btn btn-xs btn-default"
								href="<?php echo url_for('@ps_member_departments_edit?id='.$member_department->getId().'&url_callback='.$url_callback)?>">
								<i class="fa-fw fa fa-pencil txt-color-orange"
								title="<?php echo __('Edit', array())?>"></i>
							</a> 
						<?php endif;?>
						<?php if ($sf_user->hasCredential ( 'PS_HR_HR_DELETE' )) :?>
						<a data-toggle="modal"
								data-target="#confirmDeleteMemberDepartment"
								data-backdrop="static"
								class="btn btn-xs btn-default btn-delete-department pull-right"
								data-item="<?php echo $member_department->getId() ?>"> <i
								class="fa-fw fa fa-times txt-color-red"
								title="<?php echo __('Delete')?>"></i>
							</a>
						<?php endif;?>
						</div>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>
