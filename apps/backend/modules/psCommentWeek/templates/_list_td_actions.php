<td class="text-center">
	<div class="btn-group">
  	<?php if($ps_comment_week->getCommentId() > 0){ ?>
  	<a rel="tooltip" data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static" class="btn btn-default btn-xs btn-psadmin"
			href="<?php echo url_for('@ps_comment_week_edit?id='.$ps_comment_week->getCommentId())?>"><i
			class="fa-fw fa fa-pencil txt-color-orange" aria-hidden="true"></i></a>
  	<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_DELETE')):?>
	<a class="btn btn-xs btn-default pull-right"
			onclick="if (confirm('<?php echo __('Are you sure?') ?>')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); f.appendChild(m);f.submit(); };return false;"
			href="<?php echo url_for(@ps_comment_week).'/'.$ps_comment_week->getCommentId(); ?>"><i
			class="fa-fw fa fa-times txt-color-red"
			title="<?php echo __('Delete')?>"></i></a>
	<?php endif; ?>
  	<?php }else{?>
  	<a rel="tooltip" data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static" class="btn btn-default btn-xs btn-psadmin"
			href="<?php echo url_for('@ps_comment_week_new?sid='.$ps_comment_week->getStudentId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
    <?php }?>
    
  </div>
</td>