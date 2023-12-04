<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_DETAIL')): ?>
<?php echo $helper->linkToDetail($ps_cms_articles, array(  'credentials' => 'PS_CMS_ARTICLE_DETAIL',  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
<?php endif; ?>

<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_EDIT')): ?>
   <?php echo $helper->linkToEdit($ps_cms_articles, array(  'credentials' => 'PS_CMS_ARTICLE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<span class="btn-action"
	data-item="<?php echo $ps_cms_articles->getId();?>"
	id="status-<?php echo $ps_cms_articles->getId();?>">
	<?php include_partial('psCmsArticles/box_is_publish', array('ps_cms_articles' => $ps_cms_articles)) ?>
   </span>
<?php endif; ?>

<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_DELETE')): ?>
    	<?php echo $helper->linkToDelete($ps_cms_articles, array(  'credentials' => 'PS_CMS_ARTICLE_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
