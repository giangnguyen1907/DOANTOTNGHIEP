<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeatureOptionSubject/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psFeatureOptionSubject/flashes') ?>
		</article>
		<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Featureoptionsubject List "%%subject%%"', array('%%subject%%' => $service->getTitle()), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header">
								<?php include_partial('psFeatureOptionSubject/list_header', array('pager' => $pager, 'service' => $service2)) ?>
								</div>
								</div>
							</div>
												
					<?php if (!$pager->getNbResults()): ?>
					<div class="dt-toolbar no-margin">
								<div class="col-xs-12 col-sm-12">
									<div class="alert alert-warning fade in">	    
							    <?php echo __('No result', array(), 'sf_admin') ?>
						  	</div>
								</div>
							</div>  
				  	<?php endif;?>
					  					
					<form id="frm_batch"
								action="<?php echo url_for('ps_feature_option_subject_collection', array('action' => 'batch')) ?>"
								method="post">

								<input type="hidden" name="service_id"
									value="<?php echo $service_id;?>">
					
					<?php include_partial('psFeatureOptionSubject/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psFeatureOptionSubject/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">					      
					      <?php include_partial('psFeatureOptionSubject/list_batch_actions', array('helper' => $helper)) ?>					      					      
					      <?php include_partial('psFeatureOptionSubject/list_actions', array('helper' => $helper, 'service' => $service)) ?>
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

		<article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		<?php include_partial('psFeatureOptionSubject/tpl_custom/feature_option_list', array('feature_options' => $feature_options, 'service_id' => $service_id)) ?>
	</article>
	</div>
</section>