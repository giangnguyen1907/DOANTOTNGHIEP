<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_EDIT')): ?>
   <?php echo $helper->linkToEdit($ps_cms_articles, array(  'credentials' => 'PS_CMS_ARTICLE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>


<?php if ($sf_user->hasCredential('PS_CMS_ARTICLES_DELETE')): ?>
    	<?php echo $helper->linkToDelete($ps_cms_articles, array(  'credentials' => 'PS_CMS_ARTICLE_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
