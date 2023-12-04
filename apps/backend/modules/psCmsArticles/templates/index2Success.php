<?php use_helper('I18N', 'Date')?>
<?php include_partial('psCmsArticles/assets')?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<div id="content" style="opacity: 1">
	<div class="row">		
		<!--  
		<div class="dt-toolbar-footer no-border-transparent">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
        			<h6>
           				<i class="fa-fw fa fa-newspaper-o"></i><span>&nbsp;<?php echo __('Articles List', array(), 'messages') ?></span>
            		</h6>
        		</div>
        		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
        			<?php include_partial('psCmsArticles/list_actions', array('helper' => $helper))?>
        		</div>        		
    		</div>
		</div>-->
		<div class="widget-body-toolbar no-border-transparent">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
        			<h6>
           				<i class="fa-fw fa fa-newspaper-o"></i><span>&nbsp;<?php echo __('Articles List', array(), 'messages') ?></span>
            		</h6>
        		</div>
        		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
        			<?php include_partial('psCmsArticles/list_actions', array('helper' => $helper))?>
        		</div>        		
    		</div>
		</div>
		
		<div class="col-sm-9">
    		<div class="well padding-10">
    		<?php if (!$pager->getNbResults()): ?>
    			<?php include_partial('global/include/_no_result');?>
    		<?php else:?>		
    			<?php foreach ($pager->getResults() as $i => $ps_cms_articles):?>			
    			<?php echo get_partial('psCmsArticles/block_content', array('ps_cms_articles' => $ps_cms_articles, 'helper' => $helper)) ?>
    			<?php endforeach; ?>
    		<?php endif;?>
    		</div>
    		<div><!-- Phân trang -->
    			<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
			    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">				    	
    			    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psCmsArticles/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
			    	</div>
    			</div>
			</div>
		</div>
				
		<div class="col-sm-3"><!-- Form tìm kiếm và Tin mới nhất -->
			<!--  Form tìm kiếm -->
			<div class="well padding-10">
    			<h5 class="margin-top-0"><i class="fa fa-search"></i><span> <?php echo __('Blog Search...')?></span></h5>
    			<div class="input-group">
    				<?php include_partial('psCmsArticles/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper))?>
    			</div>
    		</div>
    		
    		<!--  Form bài viết mới -->
    		<div class="well padding-10">
    			<h5 class="margin-top-0"><i class="fa fa-newspaper-o"></i><span> <?php echo __('Newest Articles')?></span></h5>
    			<div class="input-group">
    				<div class="well padding-10">
    					<?php //echo $article_related; ?>
    				
            			<?php echo get_partial('psCmsArticles/newest_content', array('article_related' => $article_related, 'helper' => $helper)) ?>
            			   			
            		</div>
    			</div>

    		</div>
		</div>
	</div>
</div>