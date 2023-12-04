<?php if ($sf_user->hasCredential('PS_HR_HR_ADD')): ?>
<?php echo $helper->linkToNew(array(  'credentials' => 'PS_HR_HR_ADD',  'params' =>   array( 'query_string' => 'customer_id=' ),  'class_suffix' => 'new',  'label' => 'New',)) ?>

<?php //echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('New'), '@ps_member_new', array('class' => 'btn btn-default btn-success btn-sm btn-psadmin',  'query_string' => 'customer_id='.$sf_request->getParameter('ps_customer_id')));?>
<?php endif; ?>


