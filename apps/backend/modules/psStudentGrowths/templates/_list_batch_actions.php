<?php if ($sf_user->hasCredential(array( 'PS_MEDICAL_GROWTH_PUSH' ))): ?>
<span id="batch-actions" class="batch-actions">  
<button type="button" id="batch_action_batchSendNotication"
		class="btn btn-default btn-success  btn-sm btn-psadmin "
		value="batchSendNotication" data-action="batchSendNotication">
		<i class="fa-fw fa fa-bell" aria-hidden="true"
			title="<?php echo __('Send notication') ?>"></i> <?php echo __('Send notication');?></button>
</span>
<?php endif; ?>

<span id="batch-actions">  
  <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_DELETE')): ?>
<button type="button" id="batch_action_batchDelete" class="btn btn-default btn-danger  btn-sm btn-psadmin hidden-xs" value="batchDelete" data-action="batchDelete"><span class="fa fa-trash-o"></span> <?php echo __('Delete', array(), 'sf_admin') ?></button>
<?php endif; ?>
    <input type="hidden" name="batch_action" id="batch_action" value="" />
  <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
  <?php endif; ?>
</span>

<script type="text/javascript">

$(document).ready(function() {

	function checkStudent() {
		var boxes = document.getElementsByTagName('input');
		for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox checkbox style-0') {						
				if (box.checked == true)
		  		 return true;	
		  	}
		}

		return false;		   
	}
	$('#batch-actions button').click(function(){

		if (!checkStudent()) {
			$("#errors").html("<?php echo __('You do not select students to perform')?>");
		    $('#messageModal').modal({show: true,backdrop:'static'});
		    return false;
		}
		
		$('#batch_action').val($(this).attr("data-action"));

		$('#frm_batch').submit();

		return true;		
	});
});

</script>
