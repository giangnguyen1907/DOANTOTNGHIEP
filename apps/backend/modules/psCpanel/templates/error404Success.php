<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
			<div class="col-sm-12">
				<div class="text-center error-box">
					<h1 class="error-text tada animated font-400">
						<i class="fa fa-times-circle text-danger error-icon-shadow"></i>
						<?php echo __('System an error', array(), 'messages') ?>
					</h1>
					<h2 class="font-md">
						<strong>
						<?php include_partial('global/include/_flashes'); ?>
						<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">×</button>
								<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __('Page Not Found or The data you asked for is secure and you do not have proper credentials.') ?>
						 </div>
						</strong>
					</h2>
				</div>
			</div>
		</div>
	</div>
</div>
