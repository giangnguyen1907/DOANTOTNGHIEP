<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAttendances/assets') ?>
<script type="text/javascript">
	var number_page = 0;
</script>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psAttendances/flashes') ?>
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false"
				data-widget-colorbutton="false" data-widget-grid="false"
				data-widget-collapsed="false" data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('psAttendances List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psAttendances/list_header', array()) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
						    <?php include_partial('psAttendances/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
						  </div>
							</div>
						<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin').'. '.__('Please select class to view student list', array(), 'sf_admin') )) ?>
						<form id="frm_batch" class="form-horizontal"
								action="<?php echo url_for('@ps_logtime_save') ?>" method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" />

							</form>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>
