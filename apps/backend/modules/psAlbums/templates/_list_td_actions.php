<div class="btn-group" style="padding-left: 15px;">
<?php if ($sf_user->hasCredential('PS_CMS_ALBUMS_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_albums, array(  'credentials' => 'PS_CMS_ALBUMS_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
</div>