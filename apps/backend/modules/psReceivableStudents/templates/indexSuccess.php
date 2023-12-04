<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psReceivableStudents/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psReceivableStudents/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsReceivableStudents List', array(), 'messages').', '.__('Month').': ' .$filter_value['ps_month'] ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psReceivableStudents/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>

							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psReceivableStudents/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
												
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php endif;?>
					  					
					<form id="frm_batch"
								action="<?php echo url_for('ps_receivable_students_collection', array('action' => 'batch')) ?>"
								method="post">
					
					<?php include_partial('psReceivableStudents/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper,'filter_value' => $filter_value)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psReceivableStudents/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psReceivableStudents/list_actions', array('helper' => $helper)) ?>
					      <?php include_partial('psReceivableStudents/list_batch_actions', array('helper' => $helper)) ?>					      
				      </div>
								</div>
								<!-- END: sf_admin_footer -->
							</form>
					<?php
					
					if ($pager->getNbResults ()) {
						
						$year_id = $filter_value ['school_year_id'];
						$month = $filter_value ['ps_month'];
						$customer = $filter_value ['ps_customer_id'];
						$workplace = $filter_value ['ps_workplace_id'];
						$class = $filter_value ['ps_class_id'];
						$receivable = $filter_value ['receivable_id'];
						$student_id = $filter_value ['student_id'];
						if ($workplace == '') {
							$workplace = 0;
						}
						if ($class == '') {
							$class = 0;
						}
						if ($receivable == '') {
							$receivable = 0;
						}
						if ($receivable > 0) {
					?>
    				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
						<a class="btn btn-default" href="<?php echo url_for(@ps_receivable_students).'/'.$year_id.'/'.$month.'/'.$customer.'/'.$workplace.'/'.$class.'/'.$receivable.'/'; ?>export"
							id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?>
						</a>
					</article>
                    <?php }}?>
					</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>