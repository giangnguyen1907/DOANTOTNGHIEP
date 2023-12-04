<span
	class="label 
<?php
if ($value == 0)
	echo 'bg-color-greenLight';
else
	echo 'bg-color-orange';

?>"
	style="font-weight: normal;">
<?php echo __(PreSchool::getAbsentType()[$value]);?>
</span>