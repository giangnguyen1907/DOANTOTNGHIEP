<div id="pay_multi_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo __('Pay for multiple user') ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<!-- <input type="hidden" name="batch_action" value="pay"> -->
					<div class="col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 20px">

						<label for="pay_created_at"
							class="col-sm-12 col-md-3 col-lg-3 control-label"><?php echo __('Pay created at') ?></label>

						<div class="col-sm-12 col-md-9 col-lg-9">
							<div class="input-group">
								<span class="input-group-addon"><i
									class="icon-append fa fa-calendar"></i></span> <input
									data-dateformat="dd-mm-yyyy" placeholder="dd-mm-yyyy"
									required="required" autocomplete="off"
									class="form-control hasDatepicker" type="date"
									name="pay_created_at" id="pay_created_at">
							</div>
						</div>

					</div>

					<div class="col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 20px">

						<label for="month"
							class="col-sm-12 col-md-3 col-lg-3 control-label"><?php echo __('Month') ?></label>

						<div class="col-sm-12 col-md-9 col-lg-9">
							<select name="month" class="select2 form-control" id="month"
								required="required">
                <?php for($i = 1; $i < 25; $i++): ?>
                  <option value="<?php echo $i ?>"><?php echo $i; ?></option>
                 <?php endfor; ?>
               </select>
						</div>

					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-action="batchPay"
					value="batchPay" id="batch_action_batchPay">
					<i class="fa fa-fw fa-money"></i> <?php echo __('Yes') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
			</div>
		</div>

	</div>
</div>
<style>
.ui-datepicker {
	z-index: 1151 !important;
	display: block;
}

.datepicker {
	z-index: 1151 !important;
	display: block;
}

.bootstrap-datetimepicker-widget {
	z-index: 99999 !important;
}
</style>
<script type="text/javascript">
  $(function () {
    $('#batch_action_batchPay').click(function(){
      $('#frm_batch').attr('action', "<?php echo url_for('@ps_history_app_pay_amounts_pay_multi') ?>");

      var value = $(this).attr("data-action");

      $('#batch_action').val($(this).attr("data-action"));

      $('#frm_batch').submit();

      return true;
    });
  });
  
  $('#pay_multi_modal').on('shown.bs.modal', function (event) {
    $('#pay_created_at').datepicker({
      dateFormat: "dd-mm-yyyy",
      changeMonth: true,
      changeYear: true,
      beforeShow: function() { 
        $('#ui-datepicker-div').addClass('datepicker');
      }
    });
  });
</script>