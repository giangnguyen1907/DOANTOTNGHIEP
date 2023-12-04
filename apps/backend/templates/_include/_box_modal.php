<div class="modal fade" id="remoteModal" role="dialog"
	aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">×</button>
				<h4 class="modal-title">&nbsp;</h4>
			</div>

			<div class="modal-body dynamic-content">
				<div class="row">
					<i class="fa fa-spinner fa-2x fa-spin text-success"
						style="padding: 3px;"></i><?php echo __('Loading...')?>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close')?></button>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
      $("#remoteModal").on("hidden.bs.modal", function(){
        	$("#remoteModal .modal-title").html('&nbsp;');    	
        	$("#remoteModal .modal-body").html('<div class="row"><i class="fa fa-spinner fa-2x fa-spin text-success" style="padding:3px;"></i><?php echo __('Loading...')?></div>');
      });		
});
</script>
