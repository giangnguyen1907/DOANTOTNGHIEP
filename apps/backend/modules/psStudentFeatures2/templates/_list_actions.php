<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_FEATURE_ADD',  1 => 'PS_STUDENT_FEATURE_EDIT',))): ?>
<?php echo link_to(__('Save', array(), 'messages'), 'psStudentFeatures/List_save', array()) ?>
<?php endif; ?>