<?php use_helper('I18N') ?>
<?php include_partial('global/field_custom/_ps_assets');?>
<style>
.widget-body header {
	display: none;
}
</style>
<!--  
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="alert alert-warning fade in">
				<button class="close" data-dismiss="alert">×</button>
				<i class="fa-fw fa fa-warning ps-fa-2x" aria-hidden="true"></i><?php echo __('Oops! The page you asked for is secure and you do not have proper credentials.', null) ?>
		    </div>
		    
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-key"></i></span>
					<h2><?php echo __('Login below to gain access', null) ?></h2>
				</header>
				
				<div id="sf_admin_content">
					<div class="sf_admin_form widget-body">    	
					<?php echo get_component('sfGuardAuth', 'signin_form') ?>    
					</div>
				</div>
			</div>
		</article>

	</div>
</section>-->

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
			<div class="col-sm-12">
				<div class="text-center error-box">
					<h1 class="error-text tada animated font-400">
						<i class="fa fa-times-circle text-danger error-icon-shadow"></i>
						<?php echo __('Login below to gain access', null) ?>
					</h1>
					<h2 class="font-md">
						<strong>
							<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">×</button>
								<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __('Oops! The page you asked for is secure and you do not have proper credentials.', null) ?>
						 </div>
						</strong>
					</h2>

				</div>

			</div>

		</div>

	</div>

</div>
