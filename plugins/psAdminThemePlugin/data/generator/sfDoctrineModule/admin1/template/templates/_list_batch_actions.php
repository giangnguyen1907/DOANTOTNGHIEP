<?php if ($listActions = $this->configuration->getValue('list.batch_actions')): ?>
<span id="batch-actions">  
  <?php foreach ((array) $listActions as $action => $params):  
  	if ($action == 'batchDelete') {
  		echo $this->addCredentialCondition('<button type="button" id="batch_action_'.$action.'" class="btn btn-default btn-danger  btn-sm btn-psadmin hidden-xs" value="'.$action.'" data-action="'.$action.'"><span class="fa fa-trash-o"></span> [?php echo __(\''.$params['label'].'\', array(), \'sf_admin\') ?]</button>', $params);
  	} elseif ($action == 'batchUpdateOrder') {
  		echo $this->addCredentialCondition('<button type="button" id="batch_action_'.$action.'" class="btn btn-default btn-success  btn-sm btn-psadmin hidden-xs" value="'.$action.'" data-action="'.$action.'"><span class="fa fa-fw fa-floppy-o"></span> [?php echo __(\''.$params['label'].'\', array(), \'sf_admin\') ?]</button>', $params);
  	} else {
  		echo $this->addCredentialCondition('<button type="button" id="batch_action_'.$action.'" class="btn btn-default btn-success  btn-sm btn-psadmin " value="'.$action.'" data-action="'.$action.'">[?php echo __(\''.$params['label'].'\', array(), \'sf_admin\') ?]</button>', $params);
  	}
  ?>
  <?php endforeach; ?>
  <input type="hidden" name="batch_action" id="batch_action" value="" />
  [?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?]
    <input type="hidden" name="[?php echo $form->getCSRFFieldName() ?]" value="[?php echo $form->getCSRFToken() ?]" />
  [?php endif; ?]
</span>

<script type="text/javascript">
$(function () {
	$('#batch-actions button').click(function(){
		var value = $(this).attr("data-action");

    	$('#batch_action').val($(this).attr("data-action"));

		$('#frm_batch').submit();

		return true;
	});
});
</script>
<?php endif; ?>