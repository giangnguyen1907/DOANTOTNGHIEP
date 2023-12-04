<?php
$baseForm = new BaseForm ();
?>

<div class="modal fade" id="confirmDelete" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal" id="ps-form-delete" data-fv-addons="i18n"
		method="post"
		action="<?php echo url_for('@ps_feature_branch_times')?>">
	<?php echo $baseForm->renderHiddenFields(true);?>
	<input type="hidden" name="sf_method" value="delete" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myModalLabel">
						<i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo __('Confirm remove time apply')?></h3>
				</div>

				<div class="modal-body">
					<p><?php echo __("You sure want to delete this time?")?></p>
				</div>

				<div class="modal-footer">
					<button type="button"
						class="btn btn-default btn-sm btn-psadmin btn-cancel"
						data-dismiss="modal">
						<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
					<button type="submit"
						class="btn btn-default btn-danger btn-sm btn-psadmin btn-submit">
						<i class="fa-fw fa fa-trash-o"></i> <?php echo __('OK')?></button>
				</div>
			</div>
		</div>
	</form>
</div>