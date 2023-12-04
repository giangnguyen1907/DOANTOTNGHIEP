<div class="row">
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php
		$path = format_date ( $ps_cms_articles->getCreatedAt (), "yyyy/MM/dd" );
		if ($ps_cms_articles->getFileName ()) :
			//$path_file = '/media-articles/' . $path . '/' . $ps_cms_articles->getFileName ();
			$path_file = '/web/uploads/cms_articles/' . $path . '/thumb/' . $ps_cms_articles->getFileName ();
		else :
			$path_file = '/images/article_no_img.png';
		endif;

		// echo image_tag($path_file, array('class' => 'img-responsive'));
		?>
		<div class="ps-article-thumb" style="background-image: url('<?php echo $path_file;?>');"></div>
		<ul class="list-inline padding-10">
			<li><i class="fa fa-calendar"></i> <a href="javascript:void(0);"> 
				<?php echo false !== strtotime($ps_cms_articles->getUpdatedAt()) ? format_date($ps_cms_articles->getUpdatedAt(), "dd-MM-yyyy") : '&nbsp;' ?>
				</a></li>
		</ul>
	</div>

	<div class="col-md-9 col-sm-9 col-xs-12 padding-left-0">
		<h3 class="margin-top-0">
			<a data-backdrop="static" data-toggle="modal"
				data-target="#remoteModal"
				href="<?php echo url_for('@ps_cms_articles_detail?id='.$ps_cms_articles->getId())?>">
           	<?php echo $ps_cms_articles->getTitle() ?>
            </a><br> <small class="font-xs-75"><i><?php echo __('Created by', array(), 'messages') ?> <a
					href="javascript:void(0);"><?php echo $ps_cms_articles->getCreatedBy(); ?></a> - <?php echo $ps_cms_articles->getSchoolName();?> <?php if($ps_cms_articles->getWpTitle() != ''){ echo ' ( '.$ps_cms_articles->getWpTitle().' ) '; }?> </i></small>
		</h3>
		<p class="intro_articles">
			<?php echo $ps_cms_articles->getNote() ? $ps_cms_articles->getNote() : '' ?>
		</p>
		<div class="action_cmsarticles" >
		<?php include_partial('psCmsArticles/list_td_actions', array('ps_cms_articles' => $ps_cms_articles,'helper' => $helper)) ?>
		</div>
	</div>
</div>
<hr />
