<div class="modal fade" id="assignStudentsModal" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">
					<i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo __('Confirm remove teacher assignment')?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<?php //echo $formAssignStudents['myclass_id']->renderLabel('Class', array('class' => 'col-md-4 control-label'));?>
							<?php echo $formAssignStudents['myclass_id']->render()?>
						</div>
					</div>
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