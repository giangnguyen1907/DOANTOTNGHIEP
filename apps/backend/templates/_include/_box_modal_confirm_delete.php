<?php
$baseForm = new BaseForm ();
?>
<form class="form-horizontal" id="ps-form-delete" data-fv-addons="i18n"
	method="post" action="<?php echo url_for('@ps_teacher_class')?>">
	<input type="hidden" name="sf_method" value="delete" />
<?php echo $baseForm->renderHiddenFields(true);?>
<div class="modal fade" id="confirmDeleteModal" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">
						<i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo __('Confirm delete')?></h4>
				</div>

				<div class="modal-body">
					<p><?php echo __('Are you sure you want to delete this item?')?></p>
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
	</div>
</form>