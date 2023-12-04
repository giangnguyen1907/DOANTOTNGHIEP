<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAlbums/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psAlbums/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Albums List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psAlbums/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psAlbums/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
							<?php if (!$pager->getNbResults()): ?>
                    			<?php include_partial('global/include/_no_result');?>
                    		<?php else:?>
                    			<?php $index = 0 ?>		
                    			<?php foreach ($pager->getResults() as $i => $ps_albums):?>			
                    			<?php echo get_partial('psAlbums/block_content', array('ps_albums' => $ps_albums, 'helper' => $helper)) ?>
                    			<?php $index ++; if($index % 6 == 0) echo "<div style='clear:both'>&nbsp</div>" ?>
                    			<?php endforeach; ?>
                    		<?php endif;?>
                    
                    	<div style='clear: both'>&nbsp</div>
							<!-- sf_admin_footer -->
							<div
								class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psAlbums/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

							</div>
							<!-- END: sf_admin_footer -->
						</div>

					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>