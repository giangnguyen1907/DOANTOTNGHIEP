<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Assigned class for activity: %%feature_branch%%', array('%%feature_branch%%' => $feature_branch->getName()), 'messages') ?> (<?php echo __('School year').': '.$schoolYearsDefault->getTitle();?>)</h4>
</div>

<form class="form-horizontal" id="ps-form-save-class"
	data-fv-addons="i18n" method="post"
	action="<?php echo url_for('@ps_feature_branch_save_myclass?id='.$feature_branch->getId())?>">
	<input type="hidden" name="sf_method" value="get" /> <input
		type="hidden" name="ps_feature_branch_id"
		value="<?php echo $feature_branch->getId();?>" />
	<div class="modal-body" style="overflow: hidden;">
		<?php include_partial('psFeatureBranch/table_choose_class', array('list_myclass' => $list_myclass, 'feature_branch' => $feature_branch))?>
	</div>

	<div class="modal-footer">
		<button type="button"
			class="btn btn-default btn-sm btn-psadmin btn-cancel"
			data-dismiss="modal">
			<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?>
		</button>		
		<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')): ?>	
		<button type="submit" id="addMyClass"
			class="btn btn-default btn-success btn-sm btn-psadmin">
			<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i> <?php echo __('Save')?></button>
		<?php endif;?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {	
	$( "#addMyClass" ).click(function() {
		var boxes = document.getElementsByTagName('input');
		var check = false;
		for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'select checkbox') {				
				if (box.checked == true) {
					check = true;
					break;
				}
		  	}
		}
		if (!check) {
			alert('<?php echo __('You need to select a class for execute')?>');
		}

		return check;
	});
	
});
</script>

