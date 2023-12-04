<?php use_helper('I18N', 'Date') ?>
<?php echo false !== strtotime($ps_timesheet->getTimeAt()) ? format_date($ps_timesheet->getTimeAt(), "HH:mm:ss dd/MM/yyyy") : '&nbsp;' ?>