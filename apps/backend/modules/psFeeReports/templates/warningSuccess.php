<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('psFeeReports/assets') ?>
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
					<h2><?php echo __('Find fee reports', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">

							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
						    <?php include_partial('psFeeReports/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
						  </div>
							</div>
				  	<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin').'. '.__('Please select: School, month, year to view the fee') )) ?>					  					
					</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>