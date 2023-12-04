<?php
$baseFormRestore = new BaseForm ();
?>
<form class="form-horizontal" id="ps-form-restore-service"
	data-fv-addons="i18n" method="post"
	action="<?php echo url_for('@ps_student_service_restore')?>">
	<input type="hidden" name="sf_method" value="post" /> <input
		type="hidden" name="id" id="restore_id" />
<?php echo $baseFormRestore->renderHiddenFields(true);?>
<div class="modal fade" id="confirmRestoreService" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myModalLabel">
						<i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo __('Confirm restore service')?></h3>
				</div>
				<div class="modal-body">
					<p id="show-text" style="font-weight: bold;"></p>
					<p><?php echo __("You sure want to restore this service?")?></p>
				</div>

				<div class="modal-footer">
					<button type="submit"
						class="btn btn-default btn-success btn-sm btn-psadmin btn-submit">
						<i class="fa-fw fa fa-rotate-right"></i> <?php echo __('OK')?></button>
					<button type="button"
						class="btn btn-default btn-sm btn-psadmin btn-cancel"
						data-dismiss="modal">
						<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
				</div>
			</div>
		</div>
	</div>
</form>