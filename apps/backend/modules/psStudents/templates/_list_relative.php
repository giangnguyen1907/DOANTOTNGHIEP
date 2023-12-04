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
$school_code = $form->getObject ()
	->getPsCustomer ()
	->getSchoolCode ();
$relationship = Doctrine::getTable ( 'Relationship' )->sqlAllRelationShip ()
	->execute ()?>
<script>
$(document).ready(function() {

	// luu diem danh den
	$('.btn-relative-student').click(function() {
		
		var re_student_id = $(this).attr('data-value');

		var relationship_id = $('#relation_ship_' + re_student_id).val();
		
		var relation_order = $('#ps_relative_student_order_' + re_student_id).val();
		
		var is_parent_main = $('#is_parent_main_' + re_student_id+':checked').val();
		if(!is_parent_main){
			is_parent_main = 0;
		}

		var is_parent = $('#is_parent_' + re_student_id+':checked').val();
		if(!is_parent){
			is_parent = 0;
		}

		var is_role_avatar = $('#is_role_avatar_' + re_student_id+':checked').val();
		if(!is_role_avatar){
			is_role_avatar = 0;
		}
		
		var is_role_service = $('#is_role_service_' + re_student_id+':checked').val();
		if(!is_role_service){
			is_role_service = 0;
		}
		
		$('#ic-loading-' + re_student_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_relative_student_save_ajax') ?>',
	        type: 'POST',
	        data: 're_student_id=' + re_student_id + '&relationship_id=' + relationship_id + '&relation_order=' + relation_order + '&is_parent_main=' + is_parent_main + '&is_parent=' + is_parent + '&is_role_avatar=' + is_role_avatar + '&is_role_service=' + is_role_service,
	        success: function(data) {
	        	$('#ic-loading-' + re_student_id).hide();
	        },
	        error: function (request, error) {
	            $('#ic-loading-' + re_student_id).hide();
	            window.location.reload();
	        },
		});
		
	});
});
</script>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">   
	 	<?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_ADD')):?>	 	
        <a target="_blank"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_relatives_new')?>"><i
			class="fa-fw fa fa-plus"></i><?php echo __('Add new relative')?></a>
        <?php endif;?> 	
        <?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_REGISTER_STUDENT')):?>
        <a data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_relative_student_new?student_id='.$form->getObject()->getId())?>"
			style="margin-right: 10px;"><i class="fa fa-lg fa-fw fa-street-view"></i><?php echo __('Added from the list')?></a>
        <?php endif;?>
    </div>
	<div class="custom-scroll table-responsive">

		<table id="dt_basic"
			class="table table-striped table-bordered table-hover" width="100%">
			<tr>
				<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
				<th><?php echo __('Full name');?></th>
				<th class="text-center" style="width: 110px;"><?php echo __('Birthday');?></th>
				<th class="text-center" style="width: 80px;"><?php echo __('Sex');?></th>
				<th class="text-center" style="width: 220px;"><?php echo __('Contact');?></th>
				<th class="text-center" style="width: 150px;"><?php echo __('Username');?></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Relation');?></th>
				<th class="text-center sorting_disabled" rowspan="1" colspan="1"
					style="width: 420px;">
              <?php echo __('Role');?>
              <ul style="list-style-type: none;">
						<li class="col-md-3 pull-left text-center"><?php echo __('Main');?></li>
						<li class="col-md-3 pull-left text-center"><?php echo __('Parent');?></li>
						<li class="col-md-3 pull-left text-center"><?php echo __('Avatar');?></li>
						<li class="col-md-3 pull-left text-center"><?php echo __('Register service');?></li>
					</ul>
				</th>
				<th class="text-center" style="width: 90px;"><?php echo __('Is order') ?></th>
				<th class="text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		
		<?php foreach($list_relative as $relative): ?>
		<tr>
				<td>			
			<?php
			if ($relative->getImage () != '') {
				$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $school_code . '/' . $relative->getYearData () . '/' . $relative->getImage ();
				echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
			}
			?>
			</td>
				<td><?php echo $relative->getFullName();?></td>
				<td class="text-center"><?php echo $relative->getRelativeBirthday() ? format_date($relative->getRelativeBirthday(), "dd-MM-yyyy") : '';?></td>
				<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?></td>
				<td class="text-left">
				<div>
					<i class="fa fa-phone"></i> <?php echo $relative->getMobile();?>
				</div>
					<i class="fa fa-envelope-o"></i> <?php echo $relative->getEmail();?>
				<td class="text-center">
				<?php
					if ($sf_user->hasCredential ( 'PS_SYSTEM_USER_EDIT' )) {
						if ($relative->getUserId () > 0)
							echo link_to ( $relative->getUserName (), '@sf_guard_user_edit?id=' . $relative->getUserId (), array (
									'data-original-title' => 'Edit user relative',
									'rel' => 'tooltip',
									'target' => '_blank' ) );
						else {
							// Add new account
							echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@sf_guard_user_new', array (
									'data-original-title' => __ ( 'New user relative' ),
									'rel' => 'tooltip',
									'target' => '_blank',
									'data-placement' => "bottom",
									'class' => 'btn btn-xs btn-default btn-add-td-action',
									'query_string' => 'utype=R&mid=' . $relative->getRelativeId () ) );
						}
					} else {
						echo $relative->getUserName ();
					}
					?>
				<td class="text-center"><?php //echo $relative->getTitle();?>
    			<select
					name="relative_student[<?php echo $relative->getId()?>][relation_ship]"
					id="relation_ship_<?php echo $relative->getId()?>">
						<option selected value=""><?php echo __('Choose') ?></option>
        			<?php foreach ($relationship as $data){?>
        				<?php if($relative->getTitle() == $data->getTitle()){?>
        				<option selected value="<?php echo $data->getId();?>"><?php echo $data->getTitle();?></option>
        				<?php }?>
        				<?php if($relative->getTitle() != $data->getTitle()){?>
        				<option value="<?php echo $data->getId();?>"><?php echo $data->getTitle();?></option>
        				<?php }?>
        			<?php }?>	
    			</select></td>
				<td>
					<div id="ic-loading-<?php echo $relative->getId()?>"
						style="display: none;">
						<i class="fa fa-spinner fa-2x fa-spin text-success"
							style="padding: 3px;"></i><?php echo __('Loading...')?>
            </div>
					<ul style="list-style-type: none;"
						id="box-<?php echo $relative->getId()?>">
						<li class="col-md-3 pull-left text-center"><label
							class="radio no-margin no-padding "> <input
								name="relative_student[<?php echo $relative->getId()?>][is_parent_main]"
								class="checkbox style-0" type="checkbox"
								id="is_parent_main_<?php echo $relative->getId();?>"
								<?php if ($relative->getIsParentMain() == 1) {?>
								checked="checked" <?php }?> value="1"> <span></span>
						</label></li>
						<li class="col-md-3 pull-left text-center"><label
							class="radio no-margin no-padding "> <input
								name="relative_student[<?php echo $relative->getId()?>][is_parent]"
								class="checkbox style-0" type="checkbox"
								id="is_parent_<?php echo $relative->getId();?>"
								<?php if ($relative->getIsParent() == 1) {?> checked="checked"
								<?php }?> value="1"> <span></span>
						</label></li>
						<li class="col-md-3 pull-left text-center"><label
							class="radio no-margin no-padding "> <input
								name="relative_student[<?php echo $relative->getId()?>][is_role_avatar]"
								class="checkbox style-0" type="checkbox"
								id="is_role_avatar_<?php echo $relative->getId();?>"
								<?php if ($relative->getRoleAvatar() == 1) {?> checked="checked"
								<?php }?> value="1"> <span></span>
						</label></li>
						<li class="col-md-3 pull-left text-center"><label
							class="radio no-margin no-padding "> <input
								name="relative_student[<?php echo $relative->getId()?>][is_role_service]"
								class="checkbox style-0" type="checkbox"
								id="is_role_service_<?php echo $relative->getId();?>"
								<?php if ($relative->getRoleService() == 1) {?>
								checked="checked" <?php }?> value="1"> <span></span>
						</label></li>
						<!--  
			<?php if ($relative->getIsParentMain() == 1): ?>
				<li class="col-md-3 pull-left text-center"><i class="fa fa-check txt-color-green" aria-hidden="true" title="<?php echo __('Checked') ?>"></i></li>
			<?php else: ?>
				<li class="col-md-3 pull-left text-center"></li>
			<?php endif;?>
			<?php if ($relative->getIsParent() == 1): ?>
				<li class="col-md-3 pull-left text-center"><i class="fa fa-check txt-color-green" aria-hidden="true" title="<?php echo __('Checked') ?>"></i></li>
			<?php else: ?>
				<li class="col-md-3 pull-left text-center"></li>
			<?php endif;?>
			<?php if ($relative->getRoleAvatar() == 1): ?>
				<li class="col-md-3 pull-left text-center"><i class="fa fa-check txt-color-green" aria-hidden="true" title="<?php echo __('Checked') ?>"></i></li>
			<?php else: ?>
				<li class="col-md-3 pull-left text-center"></li>
			<?php endif;?>		
			
			<?php if ($relative->getRoleService() == 1): ?>
				<li class="col-md-3 pull-left text-center"><i class="fa fa-check txt-color-green" aria-hidden="true" title="<?php echo __('Checked') ?>"></i></li>
			<?php else: ?>
				<li class="col-md-3 pull-left text-center"></li>
			<?php endif;?>
			-->
					</ul>

				</td>
				<td class="text-center"><input type="number" min="1"
					name="ps_relative_student_order"
					id="ps_relative_student_order_<?php echo $relative->getId()?>"
					class="form-control" value="<?php echo $relative->getIorder();?>">
				</td>

				<td>
					<div class="btn-group">
				<?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_REGISTER_STUDENT')):?>
					<a class="btn btn-xs btn-default btn-relative-student"
							id="btn-relative-student-<?php echo $relative->getId()?>"
							data-value="<?php echo $relative->getId()?>"><i
							class="fa-fw fa fa-floppy-o txt-color-orange"
							title="<?php echo __('Save', array())?>"></i></a>
						<!--  
    				<a data-toggle="modal" data-target="#remoteModal" data-backdrop="static" class="btn btn-xs btn-default" href="<?php echo url_for('@ps_relative_student_edit?id='.$relative->getId())?>"><i class="fa-fw fa fa-pencil txt-color-orange" title="<?php echo __('Edit', array())?>"></i></a>
    				-->
						<a data-toggle="modal" data-target="#confirmDeleteModal"
							data-backdrop="static"
							class="btn btn-xs btn-default btn-delete-relative pull-right"
							data-item="<?php echo $relative->getId()?>"><i
							class="fa-fw fa fa-times txt-color-red"
							title="<?php echo __('Delete')?>"></i></a>
 				 <?php endif;?>
    			</div>
				</td>

			</tr>
		<?php endforeach;?>
	</table>
	</div>
</div>