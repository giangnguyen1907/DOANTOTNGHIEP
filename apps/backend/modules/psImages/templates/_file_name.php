<?php
$image_file = $ps_images->getFileName();
if ($image_file != '') {
	$image_tag = image_tag('/sys_icon/'.$image_file);
?>
<div rel="tooltip" data-placement="top" data-original-title='<?php echo $image_tag;?>' data-html="true" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"><?php echo image_tag('/sys_icon/'.$image_file, array('style' => 'max-width:35px;text-align:center;'));?></div>
<?php
}
?>


