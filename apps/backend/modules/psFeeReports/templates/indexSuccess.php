<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('psFeeReports/assets',array('filters' => $filters))?>
<?php
$ktime = '01-' . $filters ['ps_year_month']->getValue ();
$ktime = strtotime ( $ktime );
?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>
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
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					    <?php include_partial('psFeeReports/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
				</div>
				<div>
					<div class="widget-body-toolbar no-border-transparent">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
							<?php //print_r($filter_value);?>
							<?php include_partial('psFeeReports/box_button/_list_action_index', array('helper' => $helper)) ?>
							</div>
						</div>
					</div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">																			
						<?php if (!$pager->getNbResults()): ?>
						<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin').'. '.__('Please select: Class, month, year to view the fee') )) ?>					  					
						<?php else:?>
						<form id="frm_batch"
								action="<?php echo url_for('ps_fee_reports_collection', array('action' => 'batch')) ?>"
								method="post">
								<input type="hidden" name="id" id="id" /> <span
									id="list_ps_fee_reports_month_file">
								<?php if (isset($list_fee_reports_month) && count($list_fee_reports_month) > 0):?>
								<?php //include_partial('psReceivableMonth/list_fee_reports_month', array('list_fee_reports_month' => $list_fee_reports_month)) ?>
								<?php endif;?>
							</span> <span id="list_receivable_temp">
								<?php //include_partial('psFeeReports/box/_list_receivable_month', array('list_receivable_temp_receivable_at' => $list_receivable_temp_receivable_at)) ?>
							</span>

								<div id="box-content">
									<div id="table-ps-fee-reports">						
								<?php include_partial('psFeeReports/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'ktime' => $ktime)) ?>
								</div>
								</div>

								<div class="dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
						    	<?php if ($pager->haveToPaginate()): ?>
		      					<?php include_partial('psFeeReports/pagination', array('pager' => $pager)) ?>
		    					<?php endif; ?>
						    	</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
							      <?php include_partial('psFeeReports/list_batch_actions', array('helper' => $helper)) ?>					      
						       </div>
								</div>

								<input type="hidden" name="batch_action" id="batch_action" />
						  	<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
						    <input type="hidden"
									name="<?php echo $form->getCSRFFieldName() ?>"
									value="<?php echo $form->getCSRFToken()?>" />
						  	<?php endif; ?>  	
						  	<input type="hidden" name="batch_check" id="batch_check"
									value="0" />
							</form>
					<?php endif;?>
					</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>