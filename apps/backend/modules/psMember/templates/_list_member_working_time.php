<?php
/**
 * 
 * @file _form_feild_department.php
 * @file comment: Danh sach phong ban cua giao vien duoc chon
 */
$track_at = null;
$list_member_working_time = $form->getObject ()
	->getPsMemberWorkingTime ( $track_at );
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
			href="<?php echo url_for('@ps_member_working_time_new?ps_member_id='.$form->getObject()->getId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif;?>        
    </div>
	<div class="custom-scroll table-responsive" style="<?php if (count($list_member_working_time) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
		<table id="dt_basic_member_salary"
			class="display table table-striped table-bordered table-hover"
			width="100%">
			<thead>
				<tr>
					<th style="width: 30px"></th>
					<th class="text-center" style="width: 200px"><?php echo __('Title'); ?></th>
					<th class="text-center" style="width: 150px;"><?php echo __('Start At'); ?></th>
					<th class="text-center" style="width: 150px;"><?php echo __('Stop At'); ?></th>
					<th class="text-center" style="width: 400px;"><?php echo __('Note'); ?></th>
					<th><?php echo __('Updated By'); ?></th>
					<th class="no-order text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list_member_working_time as $key => $member_working_time):?>
				<tr>
					<td><?php echo ($key+1) ?></td>
					<td class="text-center"><?php echo $member_working_time->getTitle() . ' (' . $member_working_time->getStartTime() . ' - ' . $member_working_time->getEndTime() . ') '?></td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($member_working_time->getStartAt())) ? format_date($member_working_time->getStartAt(), "dd/MM/yyyy") : '';?>
						</div>
					</td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($member_working_time->getStopAt())) ? format_date($member_working_time->getStopAt(), "dd/MM/yyyy") : '';?>
						</div>
					</td>
					<td><?php echo $member_working_time->getNote() ?></td>
					<td>
						<?php echo $member_working_time->getUpdatedBy()?>
						<?php echo ' - '?>
						<?php echo (false !== strtotime($member_working_time->getUpdatedAt())) ? format_date($member_working_time->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
					</td>
					<td class="text-center">
						<div class="btn-group">							
						<?php if ($sf_user->hasCredential ( 'PS_HR_HR_EDIT' )) :?>							
						<a data-toggle="modal" data-target="#remoteModal"
								data-backdrop="static" class="btn btn-xs btn-default"
								href="<?php echo url_for('@ps_member_working_time_edit?id='.$member_working_time->getId().'&url_callback='.$url_callback)?>">
								<i class="fa-fw fa fa-pencil txt-color-orange"
								title="<?php echo __('Edit', array())?>"></i>
							</a> 
						<?php endif;?>
						<?php if ($sf_user->hasCredential ( 'PS_HR_HR_DELETE' )) :?>
						<a data-toggle="modal"
								data-target="#confirmDeleteMemberWorkingTime"
								data-backdrop="static"
								class="btn btn-xs btn-default btn-delete-member-working-time pull-right"
								data-item="<?php echo $member_working_time->getId() ?>"> <i
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
