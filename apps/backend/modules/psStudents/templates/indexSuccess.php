<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psStudents/assets') ?>

<?php
$status_student = PreSchool::loadStatusStudent ();
?>

<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psStudents/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Student list', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psStudents/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
						<?php if (!$pager->getNbResults()): ?>
						<?php include_partial('global/include/_no_result') ?>  
					  	<?php endif;?>
					  					
						<form id="frm_batch"
								action="<?php echo url_for('ps_students_collection', array('action' => 'batch')) ?>"
								method="post">

								<div class="widget-body-toolbar">
									<div class="row">
									<?php include_partial('psStudents/list_btn_export');?>
									</div>
								</div>
						
						<?php include_partial('psStudents/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'status_student' => $status_student)) ?>
						<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
					    	<?php if ($pager->haveToPaginate()): ?>
	      					<?php include_partial('psStudents/pagination', array('pager' => $pager)) ?>
	    					<?php endif; ?>
					    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
						      <?php include_partial('psStudents/list_actions', array('helper' => $helper))?>						      
						      <?php
												if (! isset ( $filters ['delete'] ) || (isset ( $filters ['delete'] ) && $filters ['delete']->getValue () <= 0))
													include_partial ( 'psStudents/list_batch_actions', array (
															'helper' => $helper ) );

												?>						      
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