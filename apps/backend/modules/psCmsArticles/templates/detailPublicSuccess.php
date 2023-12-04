<?php use_helper('I18N', 'Date')?>
<?php if ($article_detail):?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $article_detail->getTitle()?></h4>
	<small><?php echo false !== strtotime($article_detail->getUpdatedAt()) ? format_date($article_detail->getUpdatedAt(), "dd-MM-yyyy") : '&nbsp;' ?></small>
</div>
<div class="modal-body">
	<?php include_partial('psCmsArticles/flashes')?>	
	<?php

if ($article_detail->getFileName ()) :
		$path = $article_detail->getPsCustomerId () . '/' . format_date ( $article_detail->getCreatedAt (), "yyyy/MM" );
		?>
	<div>
		<?php
			$path = format_date ( $article_detail->getCreatedAt (), "yyyy/MM/dd" );
			if ($article_detail->getFileName ()) :
				$path_file = '/media-articles/article/thumb/' . $path . '/' . $article_detail->getFileName ();
			else :
				$path_file = '/images/article_no_img.png';
			endif;
		?>
		<img style="width: 100%" src="<?php echo $path_file; ?>" alt="<?php echo $article_detail->getTitle()?>" />
	</div>	
	<?php endif;?>	
	<div class="col-lg-9 col-md-9 col-xs-12 col-sm-12 custom-scroll table-responsive" style="max-height: 550px; overflow: scroll;">
		<?php echo $article_detail->getDescription() ? sfOutputEscaperGetterDecorator::unescape($article_detail->getDescription()) : ''?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>
<?php endif;?>