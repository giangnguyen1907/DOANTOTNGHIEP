  <?php $psHistoryMobileAppPayAmountsForm = new MyPsHistoryMobileAppPayAmountsForm();?>
<div class="modal fade" id="payAmountModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="font-weight: bold;"><?php echo __('Pay for') ?></h4>
			</div>
			<div class="modal-body">
				<form method="POST"
					action="<?php echo url_for('@ps_mobile_app_amounts_quick_pay') ?>"
					id="pay-amount-form">           
            <?php echo $psHistoryMobileAppPayAmountsForm['user_id']->render();?>
            <input type="hidden" name="item_id" id="item_id">
            <?php echo $psHistoryMobileAppPayAmountsForm->renderHiddenFields(true);?>
            <div class="form-group">
						<label for="pay_amount" style="font-weight: bold"><?php echo __('Amount') ?></label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-fw fa-money"></i>
							</span>
                <?php echo $psHistoryMobileAppPayAmountsForm['amount']->render();?>                
              </div>
					</div>
					<div class="form-group">
						<label for="pay_created_at" style="font-weight: bold"><?php echo __('Expiration date at') ?></label>
              <?php echo $psHistoryMobileAppPayAmountsForm['pay_created_at']->render();?>
            </div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<i class="fa fa-fw fa-ban"></i><?php echo __('Close') ?></button>
				<button type="submit" class="btn btn-success" form="pay-amount-form">
					<i class="fa fa-fw fa-save"></i> <?php echo __('Save') ?></button>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
#ui-datepicker-div {
	z-index: 1151 !important;
}
</style>