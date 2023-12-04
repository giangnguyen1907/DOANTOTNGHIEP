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
						<?php include_partial('psFeeReports/box/_list_step', array('ps_customer_id' => $ps_customer_id)) ?>
						</div>
						<div id="box-content">
							<article class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
								<div class="widget-body">
								<?php include_partial('psFeeReports/box/_controlFilters', array('formFilter' => $formFilter)) ?>
								</div>
							</article>
							<article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
								<div class="widget-body-toolbar bg-color-white">
									<?php include_partial('psFeeReports/box/_list_actions', array('helper' => $helper)) ?>
								</div>
								<div class="widget-body">
									<?php include_partial('global/include/_ic_loading');?>
									<?php include_partial('psFeeReports/ic_status_processing');?>
									<span id="list_ps_fee_reports_month_file">
										<?php if (isset($list_fee_reports_month) && count($list_fee_reports_month) > 0):?>
										<?php include_partial('psReceivableMonth/list_fee_reports_month', array('list_fee_reports_month' => $list_fee_reports_month)) ?>
										<?php endif;?>
									</span> <span id="list_receivable_temp">
										<!-- Khoan phai thu cua thang -->
										<?php include_partial('psReceivableMonth/list_receivable_month', array('list_receivable_temp_receivable_at' => $list_receivable_temp_receivable_at)) ?>
									</span>

								</div>
							</article>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>