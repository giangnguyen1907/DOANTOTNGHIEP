<?php if(isset($value)){?>
<span
	class="label <?php echo ($value == 1) ? 'label-primary': 'label-warning'; ?>"
	style="font-weight: normal;"><?php echo __(PreSchool::loadPsPaymentStatus()[$value]);?></span>
<?php }?>