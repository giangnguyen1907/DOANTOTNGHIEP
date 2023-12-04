<?php
if ($data == 0) {
	$data = 1;
	$lable = 'btn bg-color-orange txt-color-white';
} else {
	$lable = 'btn bg-color-green txt-color-white';
	$data = 0;
}
?>
<a style="width: 60px;" class="<?php echo $lable ?>" href="javascript:void(0)"><?php echo __(PreSchool::getTimesheet()[$data]);?></a>