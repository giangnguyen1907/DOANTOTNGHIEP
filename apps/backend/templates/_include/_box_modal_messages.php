<div class="modal fade" id="messageModal" tabindex="-1" role="dialog"
	aria-labelledby="Login" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title txt-color-orangeDark">
					<div class="">
						<i class="fa-fw fa fa-warning text-info"></i> <?php echo __('Message') ?></div>
				</h5>
			</div>

			<div class="modal-body">
				<div id="errors" class="text-info"></div>
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