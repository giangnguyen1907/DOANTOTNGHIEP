<script type="text/javascript">
$(document).ready(function() {

	$('#confirmDeleteReceivableStudent').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
</script>
<?php
$baseForm = new BaseForm ();
?>
<form class="form-horizontal" id="ps-form-rs-delete"
	data-fv-addons="i18n" method="post" action="">
	<div class="modal fade" id="confirmDeleteReceivableStudent"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
		<?php echo $baseForm->renderHiddenFields(true);?>
		<input type="hidden" name="sf_method" value="post" /> <input
			type="hidden" name="rs_id" id="rs_id" />
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
					<button type="button"
						class="btn btn-default btn-sm btn-psadmin btn-cancel"
						data-dismiss="modal">
						<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
					<button type="button"
						class="btn btn-default btn-danger btn-sm btn-psadmin btn-remover-receivable-student-class"
						data-dismiss="modal">
						<i class="fa-fw fa fa-trash-o"></i> <?php echo __('OK')?></button>
				</div>
			</div>
		</div>
	</div>
</form>