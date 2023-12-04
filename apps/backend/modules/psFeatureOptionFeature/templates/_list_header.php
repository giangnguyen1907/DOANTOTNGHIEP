<?php $modeText = PreSchool :: loadPsBranchMode();?>
<div class="alert alert-info no-margin fade in">
	<i class="fa-fw fa fa-info"></i><span class="lable"><?php echo __('Branch name')?>:</span>
	<code><?php echo $feature_branch->getName();?>(<?php echo __('Group feature').': '.$feature->getName()?>)</code> <?php echo __('Mode')?>: <code><?php echo __($modeText[$feature_branch->getMode()]);?></code>
</div>