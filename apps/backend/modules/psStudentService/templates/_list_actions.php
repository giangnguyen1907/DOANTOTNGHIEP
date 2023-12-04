<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_REGISTER_STUDENT')): ?>

<a class="btn btn-default btn-success btn-sm btn-psadmin" href="<?php echo url_for('@ps_service_registration_student') ?>"><i class="fa-fw fa fa-plus"></i><?php echo __('Register service')?> </a>

<?php endif; ?>