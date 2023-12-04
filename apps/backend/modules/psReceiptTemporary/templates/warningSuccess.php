<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psReceiptTemporary/assets') ?>
<script type="text/javascript">
	var number_page = 0;
</script>
<style>
.float-right {
	float: right;
	margin-right: 20px;
}

.float-right a {
	margin-top: -10px;
}
</style>
<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psReceiptTemporary/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsReceiptTemporary List', array(), 'messages') ?></h2>
					<div class="float-right">
						<a class="btn btn-success"
							href="<?php echo url_for('@ps_receipts_import') ?>"><i
							class="fa fa-upload"></i><?php echo __(' Import file') ?></a>
					</div>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psReceiptTemporary/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psReceiptTemporary/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
						<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin').'. '.__('Please select school to view student list', array(), 'sf_admin') )) ?>
						<form id="frm_batch" class="form-horizontal" action="#"
								method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" />

							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>
