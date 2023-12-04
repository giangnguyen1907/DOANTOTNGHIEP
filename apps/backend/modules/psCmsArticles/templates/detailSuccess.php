<?php use_helper('I18N', 'Date')?>
<style>
.image-articles img {
	max-width: 100%;
}
iframe{max-width: 100%;max-height: 500px;}
.control-label {
	font-weight: bold;
}
</style>
<?php if ($article_detail):?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $article_detail->getTitle()?></h4>
	<small><i class="fa fa-calendar"></i> <?php echo false !== strtotime($article_detail->getUpdatedAt()) ? format_date($article_detail->getUpdatedAt(), "dd-MM-yyyy") : '&nbsp;' ?></small>
</div>
<div class="modal-body">
	<?php include_partial('psCmsArticles/flashes')?>
	
	<div class="col-lg-9 col-md-9 col-xs-12 col-sm-12 custom-scroll table-responsive" style="max-height: 550px; overflow: scroll;">
		<div class="image-articles text-center">
			<?php
			$path = format_date ( $article_detail->getCreatedAt (), "yyyy/MM/dd" );
			if ($article_detail->getFileName ()) :
				$path_file = '/media-articles/article/thumb/' . $path . '/' . $article_detail->getFileName ();
			else :
				$path_file = '/images/article_no_img.png';
			endif;
			?>
			<img style="width: 100%" src="<?php echo $path_file; ?>" alt="<?php echo $article_detail->getTitle()?>" />
			<h5>
				<i><?php echo $article_detail->getTitle()?></i>
			</h5>
		</div>
		<?php echo $article_detail->getDescription() ? sfOutputEscaperGetterDecorator::unescape($article_detail->getDescription()) : ''?>
	</div>
	<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Ps customer') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p>
					<?php echo $article_detail->getSchoolName()?>
				</p>
			</div>
		</div>
		<div style="clear: both"></div>
		<?php if($article_detail->getWpTitle()){?>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Work places') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p>
					<?php echo $article_detail->getWpTitle()?>
				</p>
			</div>
		</div>
		<?php }?>
		<?php if(count($article_class) > 0){?>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Class') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p>
					<?php foreach ($article_class as $class){
						echo $class->getClassName().', ';
					}?>
				</p>
			</div>
		</div>
		<?php }?>
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Is access') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p>
					<?php
	$list_article_access = PreSchool::loadCmsArticleAccess ();
	if (isset ( $list_article_access [$article_detail->getIsAccess ()] ))
		echo __ ( $list_article_access [$article_detail->getIsAccess ()] );
	?>
				</p>
			</div>
		</div>
		<div style="clear: both"></div>
		<?php if($article_detail->getIsGlobal () == PreSchool::ACTIVE):?>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Is global') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p>
				<?php
					$list_article_global = PreSchool::loadPsBoolean ();
					if (isset ( $list_article_global [$article_detail->getIsGlobal ()] ))
						echo __ ( $list_article_global [$article_detail->getIsGlobal ()] );
				?>
				</p>
			</div>
		</div>
		<?php endif;?>
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Created at') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p><?php echo date('H:i:s d/m/Y',strtotime($article_detail->getCreatedAt()));?></p>
			</div>
		</div>
		
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('User created') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p><?php echo $article_detail->getCreatedBy();?></p>
			</div>
		</div>
		
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Updated at') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p><?php echo date('H:i:s d/m/Y',strtotime($article_detail->getUpdatedAt()));?></p>
			</div>
		</div>
				
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('User updated') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<p><?php echo $article_detail->getUpdatedBy();?></p>
			</div>
		</div>
		
		<div style="clear: both"></div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
				<label class="control-label"><?php echo __('Is Publish') ?></label>
			
			<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_EDIT')): ?>
            <span class="btn-action"
            	data-item="<?php echo $article_detail->getId();?>"
            	id="status-<?php echo $article_detail->getId();?>">
            	<?php include_partial('psCmsArticles/box_is_publish', array('ps_cms_articles' => $article_detail)) ?>
               </span>
			<?php endif; ?>
			
			</div>
			
		</div>
	</div>

	<div style="clear: both"></div>
</div>
<div class="modal-footer">	
	<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_EDIT')): ?>
   <?php echo $helper->linkToEdit($article_detail, array(  'credentials' => 'PS_CMS_ARTICLE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>

<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_DELETE')): ?>
    	<?php echo $helper->linkToDelete($article_detail, array(  'credentials' => 'PS_CMS_ARTICLE_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
	
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>
<?php else:?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('System an error', array(), 'messages') ?></h4>
</div>
<div class="modal-body">
	<?php include_partial('psCmsArticles/flashes')?>	
</div>
<div class="modal-footer"></div>
<?php endif;?>
