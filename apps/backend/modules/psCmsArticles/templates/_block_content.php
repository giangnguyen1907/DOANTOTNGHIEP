<div class="row">
	<div class="col-md-4">
		<?php if($date = $ps_cms_articles->getCreatedAt()): ?>
		<?php $yyyy = format_date($date, "yyyy") ?>
		<?php $md = format_date($date, "MM") ?>
		<?php $dt = $yyyy . '/' . $md ?>
		<?php else: ?>
		<?php $dt = null ?>
		<?php endif;?>
		
		<?php $cid = $ps_cms_articles->getPsCustomerId() ? $ps_cms_articles->getPsCustomerId() : null;?>
		<?php
			if ($ps_cms_articles->getFileName()):
				$path_file 	 = '/media-articles/article/thumb/'.$cid.'/'.$dt.'/'.$ps_cms_articles->getFileName();
			else:
				$path_file 	 = '/images/article_no_img.png';
			endif;
		?>
		<img class="img-responsive" src="<?php echo $path_file;?>" class="img-responsive" alt="<?php echo $ps_cms_articles->getTitle() ?>">
		
		
		<ul class="list-inline padding-10">
			<li>
				<i class="fa fa-calendar"></i>
				<a href="javascript:void(0);"> 
				<?php echo false !== strtotime($ps_cms_articles->getUpdatedAt()) ? format_date($ps_cms_articles->getUpdatedAt(), "dd-MM-yyyy") : '&nbsp;' ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-8 padding-left-0">
		<h3 class="margin-top-0">
    		<a data-backdrop="static" data-toggle="modal" data-target="#remoteModal"  href="<?php echo url_for('@ps_cms_articles_detail?id='.$ps_cms_articles->getId())?>">
           	<?php echo $ps_cms_articles->getTitle() ?>
            </a>
		
		<br><small class="font-xs"><i><?php echo __('Created by', array(), 'messages') ?> <a href="javascript:void(0);"><?php echo $ps_cms_articles->getCreatorBy(); ?></a></i></small>
		</h3>
		<p>
			<?php echo $ps_cms_articles->getNote() ? $ps_cms_articles->getNote() : '' ?>
		</p>
		<?php include_partial('psCmsArticles/list_td_actions', array('ps_cms_articles' => $ps_cms_articles, 'helper' => $helper)) ?>
	</div>
</div>
<hr>