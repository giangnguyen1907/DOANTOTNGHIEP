<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_SUBJECT_ADD',    1 => 'PS_STUDENT_SUBJECT_EDIT',  ),))): ?>
<?php echo link_to('<i class="fa-fw fa fa-list-ul"></i>'.__('Back subjects', array(), 'messages'), '@ps_subjects', 'class=btn btn-default btn-success bg-color-green btn-psadmin pull-left') ?>
<?php endif;?>