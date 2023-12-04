<div class="row">
	<div class="col-md-3 col-sm-3 col-xs-3">
		<div class="logo">
			<img alt="<?php echo $psClass->getCusTitle();?>" style="text-align: center; width: 90px; height: 90px;position: absolute;" src="<?php echo '/media-web/'.$psClass->getYearData().'/logo/'.$psClass->getLogo();?>">
		</div>
	</div>
	<div class="col-md-9 col-sm-9 col-xs-9">
		<div class="thongtintruong" style="padding-top: 10px">
			<p><?php echo $psClass->getTitle();?></p>
			<p><?php echo $psClass->getAddress().', '.__('Tel2').': '.$psClass->getTel();?></p>
		</div>
	</div>
</div>
<?php

if ($type == 'PT') {
	$title_receipt = __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime ( $receipt->getReceiptDate () ) );
	$receipt_no = $receipt->getReceiptNo ();
	$lable_receipt = __ ( 'Receipt no' ) . ': ';
} else { // Phieu bao
	$title_receipt = __ ( 'Notice of tuition fees' ) . ' ' . date ( "m-Y", strtotime ( $receipt->getReceivableAt () ) );
	$receipt_no = $receipt->getPsFeeReportNo ();
	$lable_receipt = __ ( 'Report no' ) . ': ';
}
?>
<div class="row">
	<div class="tieudephieu">
		<h5>
			<b><?php echo $title_receipt?></b>
		</h5>
	</div>
	<?php if(isset($lien)){?>
	
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php
		if ($lien == 1) {
			echo __ ( 'Lien 1' );
		} elseif ($lien == 2) {
			echo __ ( 'Lien 2' );
		}
		?></strong>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo $lable_receipt?></strong><?php echo $receipt_no?>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Student').': '?> </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Student code').': '?></strong>
		<code><?php echo $student->getStudentCode ()?></code>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Class').': '?></strong><?php echo $student->getClassName();?>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong></strong>
	</div>
	<?php }else{?>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Student').': '?> </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo $lable_receipt?></strong><?php echo $receipt_no?>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Class').': '?></strong><?php echo $student->getClassName();?>
	</div>
	<div class="col-md-6 col-xs-6 col-sm-6">
		<strong><?php echo __('Student code').': '?></strong>
		<code><?php echo $student->getStudentCode ()?></code>
	</div>
	<?php }?>
</div>
