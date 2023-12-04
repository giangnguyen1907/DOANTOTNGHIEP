<?php $feature_branch_times = $form->getObject()->getFeatureBranchTimes();?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar text-right">    	
	        <?php if ($sf_user->hasCredential(array('PS_SYSTEM_FEATURE_BRANCH_SHOW','PS_SYSTEM_FEATURE_BRANCH_ADD', 'PS_SYSTEM_FEATURE_BRANCH_EDIT'))):?>
	        <a rel="tooltip" data-placement="left"
			data-original-title="<?php echo __('View calendar details');?>"
			class="btn btn-default btn-success btn-sm btn-psadmin"
			href="<?php echo url_for('@ps_feature_branch_times?fbid='.$form->getObject()->getId())?>"><i
			class="fa-fw fa fa-calendar-o" aria-hidden="true"></i></a>
	        <?php endif;?>
	        
	        <?php if ($sf_user->hasCredential(array('PS_SYSTEM_FEATURE_BRANCH_ADD', 'PS_SYSTEM_FEATURE_BRANCH_EDIT'))):?>
	        <a rel="tooltip" data-placement="left"
			data-original-title="<?php echo __('Add times apply');?>"
			data-html="true" data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin"
			href="<?php echo url_for('@ps_feature_branch_times_new?fbid='.$form->getObject()->getId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
	        <?php endif;?>
	</div>
</div>
<div class="custom-scroll table-responsive">
	<table class="table table-bordered table-hover no-footer no-padding">
		<thead>
			<tr>
				<th class="col-md-2"><?php echo __('Start at')?> | <?php echo __('End at')?></th>
				<th class="text-center col-md-2"><?php echo __('Start time')?> | <?php echo __('End time')?></th>
				<th class="text-center col-md-1"><?php echo __('Saturday')?></th>
				<th class="text-center col-md-1"><?php echo __('Sunday')?></th>
				<th class="text-center col-md-3"><?php echo __('Description')?></th>
				<th class="text-center col-md-2"><?php echo __('Class apply')?></th>
				<th class="text-center col-md-1"><?php echo __('Actions')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($feature_branch_times as $i => $ps_feature_branch_time):?>
		<tr>
				<td style="border-left: none;">
        	<?php echo false !== strtotime($ps_feature_branch_time->getStartAt()) ? format_date($ps_feature_branch_time->getStartAt(), "dd-MM-yyyy") : '&nbsp;' ?> &rarr; <?php echo false !== strtotime($ps_feature_branch_time->getEndAt()) ? format_date($ps_feature_branch_time->getEndAt(), "dd-MM-yyyy") : '&nbsp;' ?>
        	</td>
				<td class="text-center"><?php echo $ps_feature_branch_time->getStartTime() ?> | <?php echo $ps_feature_branch_time->getEndTime()?></td>
				<td class="text-center">
        		<?php if ($ps_feature_branch_time->getIsSaturday() > 0) {?>
        		<i class="fa fa-check-square-o txt-color-green pre-fa-15x"
					aria-hidden="true" title="<?php echo __('Checked') ?>"></i>
        		<?php }?>
        	</td>
				<td class="text-center">
        		<?php if ($ps_feature_branch_time->getIsSunday() > 0) {?>
        		<i class="fa fa-check-square-o txt-color-green pre-fa-15x"
					aria-hidden="true" title="<?php echo __('Checked') ?>"></i>
        		<?php }?>
        	</td>
				<td style="white-space: pre-wrap;"><?php echo $ps_feature_branch_time->getNote();?></td>
				<td>
        	<?php
			if ($ps_feature_branch_time->getNoteClassName () != '')
				echo $ps_feature_branch_time->getNoteClassName ();
			/*
			 * elseif ($form->getObject()->getPsObjGroupId() > 0) {
			 * echo $form->getObject()->getPsObjectGroups()->getTitle();
			 * } else {
			 * echo __('All object group');
			 * }
			 */
			?>
        	</td>
				<td style="border-left: none;" class="text-center">
					<div class="btn-group">
    				<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')):?>
    				<a data-toggle="modal" data-target="#remoteModal"
							data-backdrop="static" class="btn btn-xs btn-default"
							href="<?php echo url_for('@ps_feature_branch_times_edit?id='.$ps_feature_branch_time->getId())?>"><i
							class="fa-fw fa fa-pencil txt-color-orange"
							title="<?php echo __('Edit', array())?>"></i></a>
    				<?php endif; ?>
    				<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')):?>
    				<a data-toggle="modal" data-target="#confirmDelete"
							data-backdrop="static"
							class="btn btn-xs btn-default btn-delete-item pull-right"
							data-item="<?php echo $ps_feature_branch_time->getId()?>"><i
							class="fa-fw fa fa-times txt-color-red"
							title="<?php echo __('Delete')?>"></i></a>
    				<?php endif; ?>
    			</div>
				</td>
			</tr>
        <?php endforeach;?>
	</tbody>
	</table>
</div>