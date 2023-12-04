<?php //include_partial('psStudentGrowths/field_height', array('value' => $ps_student_growths->getIndexHeight()))?>
<?php if($value != ''){?>
<span class="label <?php echo ($value == 0) ? 'bg-color-greenLight': 'bg-color-orange'; ?>" style="font-weight: normal;">
<?php echo __(PreSchool::getHeightBMI()[$value]);?>
</span>
<?php }?>