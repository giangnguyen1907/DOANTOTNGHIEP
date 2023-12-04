<div class="modal fade" id="errorModal" tabindex="-1" role="dialog"
	aria-labelledby="Login" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title txt-color-red">
					<div class="">
						<i class="fa-fw fa fa-warning txt-color-orangeDark"></i> <?php echo __('Errors') ?></div>
				</h5>
			</div>

			<div class="modal-body">
				<div id="errors" class="txt-color-red"></div>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-default btn-sm btn-psadmin btn-cancel"
					data-dismiss="modal">
					<i class="fa-fw fa fa-ban"></i> <?php echo __('Close')?></button>
			</div>
		</div>
	</div>
</div>