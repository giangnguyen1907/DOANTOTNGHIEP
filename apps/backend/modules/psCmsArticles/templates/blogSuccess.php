<?php use_helper('I18N', 'Date')?>
<?php include_partial('psCmsArticles/assets')?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>
<style>
.font-xs-75 {
	font-size: 70%
}
.intro_articles{max-height: 88px;line-height: 22px;margin-bottom: 15px;overflow: hidden;}
</style>
<?php
// $status_cmsArticles = PreSchool::loadCmsArticles();
?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psCmsArticles/flashes')?>
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false"
				data-widget-colorbutton="false" data-widget-grid="false"
				data-widget-collapsed="false" data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-newspaper-o"></i></span>
					<h2><?php echo __('Articles List', array(), 'messages') ?></h2>
					<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_ADD')): ?>
					<div class="widget-toolbar">
						<?php include_partial('psCmsArticles/list_actions', array('helper' => $helper))?>
					</div>
					<?php endif;?>
				</header>
				<div class="row">
					<div class="col-sm-9">
						<div class="well padding-10">
							<form id="frm_batch" action="<?php echo url_for('ps_cms_articles_collection', array('action' => 'batch')) ?>" method="post">
							<input type="hidden" name="id" id="id" />
							<?php if (!$pager->getNbResults()): ?>
							<?php include_partial('global/include/_no_result', array('text' => __('No article'))) ?>  
						  	<?php endif;?>
    						
    						<?php if($sf_user->hasCredential('PS_CMS_ARTICLES_FILTER_SCHOOL')){?>
    						  	<?php foreach ($pager->getResults() as $i => $ps_cms_articles):?>			
    			    			<?php echo get_partial('psCmsArticles/blog_content', array('ps_cms_articles' => $ps_cms_articles,'helper' => $helper)) ?>
    			    			<?php endforeach; ?>
						  	<?php }else{?>
						  		<?php foreach ($pager->getResults() as $i => $ps_cms_articles):?>			
    			    			<?php echo get_partial('psCmsArticles/blog_content_2', array('ps_cms_articles' => $ps_cms_articles,'helper' => $helper)) ?>
    			    			<?php endforeach; ?>
						  	<?php }?>
						  	
						  	<?php if ($pager->haveToPaginate()): ?>
	      					<?php include_partial('psCmsArticles/pagination', array('pager' => $pager)) ?>
	    					<?php endif; ?>
						</form>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="well padding-10">
							<?php include_partial('psCmsArticles/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
						</div>
					</div>
				</div>
				<div class="sf_admin_actions dt-toolbar-footer no-border-transparent" style="padding-bottom: 10px;">
			    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left"></div>
			    	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
				      <?php include_partial('psCmsArticles/list_actions', array('helper' => $helper))?>
			        </div>
				</div>
			</div>
		</article>
	</div>
</section>