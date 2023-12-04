<?php //include_partial('psStudentGrowths/field_weight', array('value' => $ps_student_growths->getIndexWeight()))?>
<?php if($value != ''){?>
<span class="label 
<?php echo ($value == 0) ? 'bg-color-greenLight': 'bg-color-orange'; ?>"
	style="font-weight: normal;">
<?php echo __(PreSchool::getWeightBMI()[$value]);?>
</span>
<?php }?>