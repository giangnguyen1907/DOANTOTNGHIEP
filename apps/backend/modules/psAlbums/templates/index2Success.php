<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAlbums/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<div id="content" style="opacity: 1">
	<div class="row">
		<div class="widget-body-toolbar no-border-transparent">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
					<h6>
						<i class="fa-fw fa fa-newspaper-o"></i><span>&nbsp;<?php echo __('Albums', array(), 'messages') ?></span>
					</h6>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
        			<?php //include_partial('psAlbums/list_actions', array('helper' => $helper))?>
        		</div>
			</div>
		</div>

		<div class="col-sm-9 col-md-9">
			<div class="">
    		<?php if (!$pager->getNbResults()): ?>
    			<?php include_partial('global/include/_no_result');?>
    		<?php else:?>
    			<?php $index = 0 ?>		
    			<?php foreach ($pager->getResults() as $i => $ps_albums):?>			
    			<?php echo get_partial('psAlbums/block_content', array('ps_albums' => $ps_albums, 'helper' => $helper)) ?>
    			<?php $index ++; if($index % 4 == 0) echo "<div style='clear:both'>&nbsp</div>" ?>
    			<?php endforeach; ?>
    		<?php endif;?>
    		</div>
			<div style="clear: both">&nbsp</div>
			<div class="">
				<!-- Phân trang -->
				<div
					class="sf_admin_actions dt-toolbar-footer no-border-transparent">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">				    	
    			    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psAlbums/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
    		    	</div>
				</div>
			</div>
		</div>
		<div class="col-sm-3 col-md-3">
			<div class="well padding-10">
				<h5 class="margin-top-0">
					<i class="fa fa-search"></i><span> <?php echo __('Albums Search...')?></span>
				</h5>
				<div class="input-group">
    				<?php include_partial('psAlbums/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
    			</div>
			</div>
		</div>
	</div>
</div>
