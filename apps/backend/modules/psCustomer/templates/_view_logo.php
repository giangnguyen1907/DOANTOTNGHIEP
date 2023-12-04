<?php
$logo = $ps_customer->getLogo ();
// Path folder luu anh
// sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/pschool/logo/'.$this->getObject()->logo;
/*
 * if ($logo != '') {
 * echo image_tag('/media/'.$ps_customer->getYearData().'/logo/'.$logo, array('style' => 'max-width:35px;text-align:center;'));
 * }
 */
?>
<?php
if ($logo != '') :
	?>
<img style="max-width: 45px; text-align: center;"
	src="<?php echo '/media-web/'.$ps_customer->getYearData().'/logo/'.$logo;?>">
<?php endif;?>