<?php
$baseForm = new BaseForm ();
?>
<form class="form-horizontal" id="ps-form-rt-delete"
	data-fv-addons="i18n" method="post" action="">
	<div class="modal fade" id="confirmDeleteRT" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">	
		<?php echo $baseForm->renderHiddenFields(true);?>
		<input type="hidden" name="sf_method" value="delete" /> <input
			type="hidden" name="item_id" id="item_id" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myModalLabel">
						<i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo __('Confirm remove receivable in the month')?></h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<p id="modal-body-text"><?php echo __("You sure want to delete this receivable?")?></p>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-left"><?php echo __("Note remove receivable 1")?></div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-left">
						<button type="button" rel="tooltip" data-placement="auto"
							data-original-title="<?php echo __('Delete but keep the results report fee of the student')?>"
							class="btn btn-default btn-danger btn-sm btn-psadmin btn-ok-delete"
							data-dismiss="modal">
							<i class="fa-fw fa fa-trash-o"></i> <?php echo __('OK')?></button>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-left"><?php echo __("Note remove receivable 2")?></div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-left">
						<button type="button" name="_ok_and_run_fee_report"
							class="btn btn-default btn-danger btn-sm btn-psadmin btn-ok2"
							data-dismiss="modal">
							<i class="fa-fw fa fa-trash-o"></i> <?php echo __('OK and re-run the fee report')?></button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button"
						class="btn btn-default btn-sm btn-psadmin btn-cancel"
						data-dismiss="modal">
						<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
				</div>
			</div>
		</div>
	</div>
</form>