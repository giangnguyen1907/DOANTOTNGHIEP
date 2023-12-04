<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psLogtimes/assets') ?>
<script type="text/javascript">
	var number_page = 0;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psLogtimes/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsLogtimes List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psLogtimes/list_header', array()) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
						    <?php include_partial('psLogtimes/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
						  </div>
							</div>
						<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin').'. '.__('Please select class to view student list', array(), 'sf_admin') )) ?>
						<form id="frm_batch" class="form-horizontal"
								action="<?php echo url_for('@ps_logtime_save') ?>" method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" />
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
					    	<?php if ($sf_user->hasCredential('PS_STUDENT_ATTENDANCE_ADD')): ?>
							<?php $tracked_at = strtotime($filter_value['tracked_at'])?>
							<a class="btn btn-default btn-success btn-sm btn-psadmin"
											href="<?php echo url_for('@ps_logtimes_new?tracked_at='.$tracked_at.'&class_id='.$filter_value['ps_class_id']);?>"><i
											class="fa-fw fa fa-plus"></i> <?php echo  __('Attendance by baby')?></a>
							<?php endif; ?>					    
					      	</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>
