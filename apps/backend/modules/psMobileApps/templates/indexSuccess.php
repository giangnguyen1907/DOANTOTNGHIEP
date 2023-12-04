<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psMobileApps/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psMobileApps/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsMobileApps List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psMobileApps/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>

							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psMobileApps/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>					
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php endif;?>
					  					
					<form id="frm_batch"
								action="<?php echo url_for('ps_mobile_apps_collection', array('action' => 'batch')) ?>"
								method="post">
					
					
					<?php include_partial('psMobileApps/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psMobileApps/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psMobileApps/list_actions', array('helper' => $helper)) ?>
					      <?php include_partial('psMobileApps/list_batch_actions', array('helper' => $helper)) ?>
						  <?php if(count($filtersForm) > 0 && count($pager) > 0):?>
    					<?php
									$school_year_id = $filtersForm ['school_year_id'] ? $filtersForm ['school_year_id'] : 0;
									$ps_month = $filtersForm ['ps_month'] ? $filtersForm ['ps_month'] : 0;
									$ps_customer_id = $filtersForm ['ps_customer_id'] ? $filtersForm ['ps_customer_id'] : 0;
									$ps_workplace_id = $filtersForm ['ps_workplace_id'] ? $filtersForm ['ps_workplace_id'] : 0;
									?>
    					<?php if (myUser::isAdministrator() ||$sf_user->hasCredential ( 'PS_REPORT_MOBILE_APPS_EXPORT' )):?>
    					<article
											class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
											<div class="padding-5">
												<a class="btn btn-default"
													href="<?php echo url_for(@ps_mobile_apps).'/'.$school_year_id.'/'.$ps_month.'/'.$ps_customer_id.'/'.$ps_workplace_id.'/'; ?>export"
													id="btn-export"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
											</div>
										</article>
                    	<?php endif; ?>
                    	<?php endif;?>
				      </div>
								</div>
								<!-- END: sf_admin_footer -->
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>