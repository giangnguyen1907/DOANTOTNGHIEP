<?php
if ($value->getIsIo () == 1)
	$data = $value->getIsIo ();
else
	$data = 0;

if ($data == 0)
	$lable = 'btn bg-color-green txt-color-white';
else
	$lable = 'btn bg-color-orange txt-color-white';
?>

<a style="width: 75px;"
	class="btn-album-item-activated <?php echo $lable ?>"
	href="javascript:void(0)"
	data-value="<?php echo $value->getMemberId() ?>"> <i
	class="fa-fw fa fa-floppy-o" aria-hidden="true"
	title="<?php echo __('Save');?>"></i><?php echo __(PreSchool::getTimesheet()[$data]);?>
	<input type="hidden" class="filter form-control"
	value="<?php echo $data ?>" name="absent_type"
	id="absent_type_<?php echo $value->getMemberId() ?>">
</a>