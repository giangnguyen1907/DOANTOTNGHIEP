<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('psFeeReports/assets2') ?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psFeeReports/flashes') ?>
		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Process fee report', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body fuelux">
						<div class="widget-body-toolbar">
						<?php include_partial('psFeeReports/box/_list_step', array('ps_customer_id' => $ps_customer_id, 'current_step' => '2')) ?>
						</div>
						<div id="box-content">
							<div class="widget-body">
							<?php include_partial('psFeeReports/box/_controlStep2Filters', array('formFilter' => $formFilter, 'ps_workplace' => $ps_workplace, 'ps_fee_reports_my_class' => $ps_fee_reports_my_class)) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>