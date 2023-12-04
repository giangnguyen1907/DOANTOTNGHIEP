
<div class="modal fade" id="payModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="font-weight: bold;"><?php echo __('Pay for') ?></h4>
			</div>
			<div class="modal-body">

				<form method="POST"
					action="<?php echo url_for('@ps_mobile_app_amounts_quick_pay') ?>"
					id="pay_form">
					<input type="hidden" name="user_id" id="user_id"> <input
						type="hidden" name="_csrf_token" id="_csrf_token" value="">
					<div class="form-group">
						<label for="pay_amount" style="font-weight: bold"><?php echo __('Amount') ?></label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-fw fa-money"></i>
							</span> <input type="number" name="pay_amount" id="pay_amount"
								class="form-control"
								placeholder="<?php echo __('Input amount') ?>" required="true">
						</div>
					</div>
					<div class="form-group">
						<label for="pay_created_at" style="font-weight: bold"><?php echo __('Expiration date at') ?></label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
							<input type="text" data-dateformat="dd-mm-yyyy"
								placeholder="dd-mm-yyyy" name="pay_created_at"
								id="pay_created_at" class="form-control hasDatepicker">
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<i class="fa fa-fw fa-ban"></i><?php echo __('Close') ?></button>
				<button type="submit" class="btn btn-success" form="pay_form">
					<i class="fa fa-fw fa-save"></i> <?php echo __('Save') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
        $('#pay_created_at').datepicker({
          dateFormat : 'dd-mm-yy',
          prevText : '<i class="fa fa-chevron-left"></i>',
          nextText : '<i class="fa fa-chevron-right"></i>',
          changeMonth : true,
          changeYear : true,
        });   
  </script>
<style type="text/css">
#ui-datepicker-div {
	z-index: 1151 !important;
}
</style>

