<?php foreach ($article_related as $ps_cms_articles):?>
<?php if($date = $ps_cms_articles->getCreatedAt()): ?>
<?php $yyyy = format_date($date, "yyyy") ?>
<?php $mm = format_date($date, "MM") ?>
<?php $dt = $yyyy . '/' . $mm ?>
<?php else: ?>
<?php $dt = null ?>
<?php endif;?>
<?php $cid = $ps_cms_articles->getPsCustomerId() ? $ps_cms_articles->getPsCustomerId() : null?>
<div class="row">
	<div class="col-md-5">
		<?php if ($ps_cms_articles->getFileName()):?>
		<img class="img-responsive" src="../../web/uploads/cms_articles/<?php echo $cid .'/' . $dt . '/thumb/' . $ps_cms_articles->getFileName() ?>" class="img-responsive" alt="<?php echo $ps_cms_articles->getTitle() ?>">
		<?php else:?>
		<img class="img-responsive" src="../../web/uploads/cms_articles/no_img.png" class="img-responsive" alt="<?php echo $ps_cms_articles->getTitle() ?>">
		<?php endif;?>
		
		<ul class="list-inline padding-10">
			<li>
				<i class="fa fa-calendar"></i>
				<a href="javascript:void(0);"> 
				<?php echo false !== strtotime($ps_cms_articles->getUpdatedAt()) ? format_date($ps_cms_articles->getUpdatedAt(), "MM-yyyy") : '&nbsp;' ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-7 padding-left-0">
		<p class="margin-top-0">
    		<a data-backdrop="static" data-toggle="modal" data-target="#remoteModal"  href="<?php echo url_for('@ps_cms_articles_detail?id='.$ps_cms_articles->getId())?>">
           		<?php echo strlen($ps_cms_articles->getTitle()) >= 56 ? substr($ps_cms_articles->getTitle(), 0, 54) . '...' : $ps_cms_articles->getTitle();?>
            </a>
		</p>
		<p>
			<?php echo strlen($ps_cms_articles->getNote()) >= 112 ? substr($ps_cms_articles->getNote(), 0, 110) . '...' : $ps_cms_articles->getNote();?>
		</p>
	</div>
</div>
<hr>
<?php endforeach; ?>