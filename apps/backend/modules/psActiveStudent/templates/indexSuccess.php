<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psActiveStudent/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid"><!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psActiveStudent/flashes') ?>

		<!-- sf_admin_container -->
		<div class="jarviswidget" id="wid-id-0"
		data-widget-editbutton="false"
		data-widget-colorbutton="false"
		data-widget-grid="false"
		data-widget-collapsed="false"
		data-widget-fullscreenbutton="false"
		data-widget-deletebutton="false"
		data-widget-togglebutton="false"
		>
		<header>
			<span class="widget-icon"><i class="fa fa-table"></i></span>
			<h2><?php echo __('PsActiveStudent List', array(), 'messages') ?></h2>
			<div class="widget-toolbar">
				<a class="btn btn-xs btn-success" href="<?php echo url_for('@ps_active_student_import') ?>"><i class="fa fa-upload"></i> Import hoạt động</a>
			</div>		
		</header>
			<div>
				<div class="widget-body no-padding" >
					<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer no-padding" >											
						<div class="dt-toolbar no-margin no-padding no-border">
							<div class="col-xs-12 col-sm-12">
								<div id="sf_admin_header"><?php include_partial('psActiveStudent/list_header', array('pager' => $pager)) ?></div>
							</div>
						</div>

												  <div id="sf_admin_bar" class="dt-toolbar">
							  <div class="col-xs-12 col-sm-12">
							    <?php include_partial('psActiveStudent/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
						  </div>
												
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php endif;?>
					  					
					<form id="frm_batch" action="<?php echo url_for('ps_active_student_collection', array('action' => 'batch')) ?>" method="post">
					
					<?php include_partial('psActiveStudent/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
					
					<!-- sf_admin_footer -->
					<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
				    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psActiveStudent/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>
				    	
				    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psActiveStudent/list_actions', array('helper' => $helper)) ?>
					      <?php include_partial('psActiveStudent/list_batch_actions', array('helper' => $helper)) ?>					      
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