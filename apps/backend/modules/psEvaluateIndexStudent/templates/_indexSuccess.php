<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psEvaluateIndexStudent/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psEvaluateIndexStudent/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsEvaluateIndexStudent List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psEvaluateIndexStudent/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>

							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psEvaluateIndexStudent/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
										
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php endif;?>
					  					
					<form id="frm_batch"
								action="<?php echo url_for('ps_evaluate_index_student_collection', array('action' => 'batch')) ?>"
								method="post">
					
					
					<?php include_partial('psEvaluateIndexStudent/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper,'students' =>$students,'criteria' => $criteria, 'students_evaluate' => $students_evaluate, 'symbols' => $symbols, 'filterValue' => $filterValue)) ?>
					<?php if(count($students) > 0 /*&& count($criteria) > 0*/):?>
					
						<?php include_partial('psEvaluateIndexStudent/evaluate_content', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper,'students' =>$students,'criteria' => $criteria, 'students_evaluate' => $students_evaluate, 'symbols' => $symbols, 'filterValue' => $filterValue)) ?>
											
					<?php endif;?>
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psEvaluateIndexStudent/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psEvaluateIndexStudent/list_actions', array('helper' => $helper)) ?>
					      <?php include_partial('psEvaluateIndexStudent/list_batch_actions', array('helper' => $helper)) ?>					      
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