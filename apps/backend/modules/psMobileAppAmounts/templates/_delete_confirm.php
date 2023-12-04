<?php $baseForm = new BaseForm() ?>

<div id="deleteConfirm" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo _('Are you sure?') ?></h4>
			</div>
			<div class="modal-body">
				<form id="deleteHistory" class="form-hozirontal" method="post"
					action="">
					<input type="hidden" name="sf_method" value="DELETE">
          <?php echo $baseForm->renderHiddenFields(true);?>
        </form>
        <?php echo __('Are you sure?') ?>
      </div>
			<div class="modal-footer">
				<button type="submit" form="deleteHistory" class="btn btn-success"><?php echo __('Yes') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
			</div>
		</div>

	</div>
</div>