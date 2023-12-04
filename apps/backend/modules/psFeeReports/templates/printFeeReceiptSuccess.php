<!DOCTYPE html>
<html lang="vi" lang="vi">
<head>
<meta charset="utf-8">
<title>Kidsschool.vn - Giải pháp kết nối thông tin trường học</title>
<meta name="description"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn. Giải pháp kết nối thông tin trường mầm non trường mầm non." />
<meta name="keywords"
	content="kidsschool, kids school,kid school,,kidsschool.vn, kids online,quản lý mầm non, trường mầm non,Preschool,Software, thực đơn, dinh dưỡng, nhân sự" />
<meta name="copyright" content="2012 Newwaytech Ltd." />
<meta name="GENERATOR"
	content="CÔNG TY TNHH ĐẦU TƯ PHÁT TRIỂN ỨNG DỤNG CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG - www.newwaytech.vn" />
<meta name="author"
	content="CÔNG TY TNHH ĐẦU TƯ PHÁT TRIỂN ỨNG DỤNG CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG - http://www.newwaytech.vn" />

<meta property="og:title"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn. Giải pháp kết nối thông tin trường mầm non." />
<meta property="og:description"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn" />
<meta property="fb:app_id" content="" />
<meta property="og:image" content="/favicon.png" />

<meta id="MetaCopyright" name="COPYRIGHT"
	content="Copyright © 2012 kidsschool.vn" />
<meta id="MetaRobots" name="ROBOTS" content="index, follow" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://kidsschool.vn" />
<meta name="google-site-verification" content="0Bd2fK9p5eJGK6poFseDkwnSODuHCG1MsKQnHOMkvKQ" />
<link rel="stylesheet" type="text/css" media="screen" href="https://quanly.kidsschool.vn/web/psAdminThemePlugin/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="print" href="https://quanly.kidsschool.vn/web/psAdminThemePlugin/css/bootstrap.min.css">
<style type="text/css">
.row {
	margin: 0px
}

body {
	background: #fff;
	font-size: 12px;
	color: #000
}

.table>tbody>tr>td {
	padding: 2px
}

.tieudephieu h5 {
	text-align: center;
	font-size: 14px
}

.chuky h5 {
	font-size: 12px;
	font-weight: bold;
}

.col-md-6 {
	width: 50%;
	float: left;
	padding: 0
}
</style>
</head>
<body onload="window.print()">
<div class="row">
<?php

$file_template_file = $ps_workplace->getConfigTemplateReceiptExport ();
if ($file_template_file == 'bm_phieuthu_01.xls') { // xuất theo biểu mẫu 1 ?>
    <div class="col-md-6 col-xs-6 col-sm-6">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3">
						<div class="logo">
							<img alt="<?php echo $ps_customer->getTitle();?>"
								src="<?php echo $ps_customer->getLogo();?>">
						</div>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<div class="thongtintruong">
							<p>
								<b><?php echo $ps_customer->getTitle();?></b>
							</p>
							<p><?php echo $ps_workplace->getTitle();?></p>
							<p><?php echo $ps_workplace->getAddress();?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="tieudephieu">
						<h5>
							<b><?php echo __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime($receipt->getReceiptDate ()) )?></b>
						</h5>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong><?php echo __('Lien 1')?></strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong><?php echo __('Receipt no')?></strong><?php echo $receipt->getReceiptNo ()?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong><?php echo __('Student')?> </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong><?php echo __('Student code')?> </strong>
						<code><?php echo $student->getStudentCode ()?></code>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong><?php echo __('Class')?></strong><?php echo $class_name;?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong></strong>
					</div>
				</div>

				<div class="row">

					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">

							<thead>
							</thead>
							<tbody>
        					<?php

	$total_oldRsAmount = 0;
	$total_oldRsLateAmount = 0;
	$tongtienthangnay = 0;
	$rs_current = array ();
	foreach ( $data ['receivable_student'] as $k => $rs ) {
		if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

			if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {

				$total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();

				$title = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . ' (' . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ), "m/Y" ) . ')';

				?>
        					            <tr>
									<td colspan="4"><?php echo $title?></td>
									<td colspan="1"><?php echo $rs->getRsAmount ()?></td>
									<td colspan="1"><?php echo $this->object->getContext ()->getI18N ()->__ ( $rs->getRsNote () )?></td>
								</tr>
        					            <?php
			} else {
				$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
			}
		} else {
			array_push ( $rs_current, $rs );
		}
	}

	?>
    						<?php if ($total_oldRsAmount > 0) {?>
    						<tr>
									<td colspan="6" class="text-left"><b>Thanh toán các khoản phí
											tháng trước</b></td>
								</tr>
								<tr>
									<td colspan="4">Dịch vụ khác</td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
								<tr>
									<td colspan="4" class="text-right"><b>Tổng</b></td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
							<?php }?>
    						<tr>
									<td colspan="6" class="text-left"><b>Dự kiến các khoản phí
											tháng này</b></td>
								</tr>

								<tr>
									<td class="text-center" style="max-width: 15px"><b>STT</b></td>
									<td class="text-center"><b>Học phí</b></td>
									<td class="text-center"><b>Đơn giá</b></td>
									<td class="text-center"><b>SL dự kiến</b></td>
									<td class="text-center"><b>Tạm tính</b></td>
									<td class="text-center"><b>Ghi chú</b></td>
								</tr>
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

		if ($rs->getRsReceivableId ()) {
			$title = $rs->getRTitle ();
		} elseif ($rs->getRsServiceId ()) {
			$title = $rs->getSTitle ();
		} elseif ($rs->getRsIsLate () == 1) {
			$title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Out late' );
		}

		if ($rs->getRsServiceId () > 0) {
			$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
			$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
		} else {
			$rs_amount = $rs->getRsAmount ();
		}
		$tongtienthangnay += $rs_amount;
		?>
    						<tr>
									<td class="text-center"><?php echo $k+1;?></td>
									<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsNote ());?></td>
								</tr>
    						
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b>Tổng tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
									<td></td>
								</tr>
    						<?php
	$tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
	$phi_nop_muon = $data ['psConfigLatePayment'];
	$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
	?>
    						<tr>
									<td colspan="4" class="text-right"><b>Dư tháng trước</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
									<td></td>
								</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
									<td colspan="4" class="text-right"><b>Phí nộp muộn</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
									<td></td>
								</tr>
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b><i>Tổng phải nộp</i></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Số tiền phụ huynh nộp</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Dư tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
									<td></td>
								</tr>

							</tbody>
						</table>
					</div>

					<div class="text-right" style="padding-right: 25px">
						<i>Hà nội, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?></i>
					</div>

					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Phụ huynh</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Người lập</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Thủ quỹ</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

				</div>
			</div>
		</div>

		<!-----------------------------------------Kết thúc liên 1--------------------------------------------->

		<div class="col-md-6 col-xs-6 col-sm-6">

			<div class="modal-body">

				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3">
						<div class="logo">
							<img alt="<?php echo $ps_customer->getTitle();?>"
								src="<?php echo $ps_customer->getLogo();?>">
						</div>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<div class="thongtintruong">
							<p>
								<b><?php echo $ps_customer->getTitle();?></b>
							</p>
							<p><?php echo $ps_workplace->getTitle();?></p>
							<p><?php echo $ps_workplace->getAddress();?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="tieudephieu">
						<h5>
							<b><?php echo __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime($receipt->getReceiptDate ()) )?></b>
						</h5>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Liên 2</strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã phiếu thu: </strong><?php echo $receipt->getReceiptNo ()?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Học sinh : </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã Học sinh : </strong>
						<code><?php echo $student->getStudentCode ()?></code>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Lớp : </strong><?php echo $class_name;?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mẫu : 1</strong>
					</div>
				</div>

				<div class="row">

					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">

							<thead>
							</thead>
							<tbody>
        					<?php

	$total_oldRsAmount = 0;
	$total_oldRsLateAmount = 0;
	$tongtienthangnay = 0;
	$rs_current = array ();
	foreach ( $data ['receivable_student'] as $k => $rs ) {
		if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

			if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {

				$total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();

				$title = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . ' (' . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ), "m/Y" ) . ')';

				?>
        					            <tr>
									<td colspan="4"><?php echo $title?></td>
									<td colspan="1"><?php echo $rs->getRsAmount ()?></td>
									<td colspan="1"><?php echo $this->object->getContext ()->getI18N ()->__ ( $rs->getRsNote () )?></td>
								</tr>
        					            <?php
			} else {
				$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
			}
		} else {
			array_push ( $rs_current, $rs );
		}
	}

	?>
    						<?php if ($total_oldRsAmount > 0) {?>
    						<tr>
									<td colspan="6" class="text-left"><b>Thanh toán các khoản phí
											tháng trước</b></td>
								</tr>
								<tr>
									<td colspan="4">Dịch vụ khác</td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
								<tr>
									<td colspan="4" class="text-right"><b>Tổng</b></td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
							<?php }?>
    						<tr>
									<td colspan="6" class="text-left"><b>Dự kiến các khoản phí
											tháng này</b></td>
								</tr>

								<tr>
									<td class="text-center" style="max-width: 15px"><b>STT</b></td>
									<td class="text-center"><b>Học phí</b></td>
									<td class="text-center"><b>Đơn giá</b></td>
									<td class="text-center"><b>SL dự kiến</b></td>
									<td class="text-center"><b>Tạm tính</b></td>
									<td class="text-center"><b>Ghi chú</b></td>
								</tr>
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

		if ($rs->getRsReceivableId ()) {
			$title = $rs->getRTitle ();
		} elseif ($rs->getRsServiceId ()) {
			$title = $rs->getSTitle ();
		} elseif ($rs->getRsIsLate () == 1) {
			$title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Out late' );
		}

		if ($rs->getRsServiceId () > 0) {
			$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
			$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
		} else {
			$rs_amount = $rs->getRsAmount ();
		}
		$tongtienthangnay += $rs_amount;
		?>
    						<tr>
									<td class="text-center"><?php echo $k+1;?></td>
									<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsNote ());?></td>
								</tr>
    						
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b>Tổng tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
									<td></td>
								</tr>
    						<?php
	$tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
	$phi_nop_muon = $data ['psConfigLatePayment'];
	$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
	?>
    						<tr>
									<td colspan="4" class="text-right"><b>Dư tháng trước</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
									<td></td>
								</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
									<td colspan="4" class="text-right"><b>Phí nộp muộn</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
									<td></td>
								</tr>
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b><i>Tổng phải nộp</i></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Số tiền phụ huynh nộp</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Dư tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
									<td></td>
								</tr>

							</tbody>
						</table>
					</div>

					<div class="text-right" style="padding-right: 25px">
						<i>Hà nội, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?></i>
					</div>

					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Phụ huynh</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Người lập</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Thủ quỹ</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

				</div>
			</div>
		</div>


<?php }elseif($file_template_file == 'bm_phieuthu_02.xls'){ // xuất theo biểu mẫu 2 ?>

	<div class="col-md-12 col-xs-12 col-sm-12">

			<div class="modal-body">

				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3">
						<div class="logo">
							<img alt="<?php echo $ps_customer->getTitle();?>"
								src="<?php echo $ps_customer->getLogo();?>">
						</div>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<div class="thongtintruong">
							<p>
								<b><?php echo $ps_customer->getTitle();?></b>
							</p>
							<p><?php echo $ps_workplace->getTitle();?></p>
							<p><?php echo $ps_workplace->getAddress();?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="tieudephieu">
						<h5>
							<b><?php echo __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime($receipt->getReceiptDate ()) )?></b>
						</h5>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Liên 1</strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã phiếu thu: </strong><?php echo $receipt->getReceiptNo ()?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Học sinh : </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã Học sinh : </strong>
						<code><?php echo $student->getStudentCode ()?></code>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Lớp : </strong><?php echo $class_name;?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mẫu : 2</strong>
					</div>
				</div>

				<div class="row">

					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">

							<thead>
							</thead>
							<tbody>
        					<?php

	$total_oldRsAmount = 0;
	$total_oldRsLateAmount = 0;
	$tongtienthangnay = 0;
	$rs_current = array ();
	?>
        					<tr>
									<td class="text-center"><b><?php echo __('Month');?></b></td>
									<td class="text-center"><b><?php echo __('Name fees');?></b></td>
									<td class="text-center"><b><?php echo __('Price');?></b></td>
									<td class="text-center"><b><?php echo __('Quantily expected');?></b></td>
									<td class="text-center"><b><?php echo __('Discount fixed');?></b></td>
									<td class="text-center"><b><?php echo __('Discount');?></b></td>
									<td class="text-center"><b><?php echo __('Temporary money');?></b></td>
									<td class="text-center"><b><?php echo __('Used');?></b></td>
									<td class="text-center"><b><?php echo __('Actual costs');?></b></td>
									<td class="text-center"><?php echo __('Note')?></td>
								</tr>
    						
        					<?php
	foreach ( $data ['receivable_student'] as $k => $r_s ) {
		if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {

			$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

			$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

			if ($r_s->getRsReceivableId ()) {
				$title_sevice = $r_s->getRTitle ();
			} elseif ($r_s->getRsServiceId ()) {
				$title_sevice = $r_s->getSTitle ();
			} elseif ($r_s->getRsIsLate () == 1) {
				$title_sevice = __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
			}

			?>
    					        <tr>
									<td class="text-center"><?php echo date('m-Y', $month_prev ) ?></td>
									<td><?php echo $title_sevice;?></td>
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsDiscountAmount());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsDiscount());?></td>
        							
        							<?php
			if ($r_s->getRsServiceId () > 0) {
				$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
				$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
			} else {
				$rs_amount = $r_s->getRsAmount ();
			}
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
				$rs_amount = 0;
			}
			$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;
			$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
			?>
        							
        							<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td class="text-center"><?php echo PreNumber::number_format($spentNumber);?></td>
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsAmount());?></td>
									<td><?php echo ($r_s->getRsIsLate() == 1) ? __($r_s->getRsNote()) : $r_s->getRsNote();?></td>
								</tr>
        					        
        					<?php

} else {
			array_push ( $rs_current, $r_s );
		}
	}
	?>
    						<tr>
									<td colspan="6" class="text-right"><b><?php echo __('Total expected')?>:</b></td>
									<td class="text-right"
										title="<?php echo __('Expected revenue')?>"><?php echo PreNumber::number_format($tong_du_kien_cac_thang_cu);?></td>

									<td><b><?php echo __('Total reality')?></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_cac_thang_cu);?></td>
									<td style="background-color: #fff; border-left: none;">&nbsp;</td>
								</tr>

								<tr>
									<td colspan="6" class="text-right"><b><?php echo __('Paid')?>:</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($collectedAmount);$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu;?></td>

									<td><b><?php echo __('Debt')?></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($newBalanceAmont);?></td>
									<td style="background-color: #fff; border-left: none;">&nbsp;</td>
								</tr>


								<tr>
									<td colspan="10" class="text-left"><b>Dự kiến các khoản phí
											tháng này</b></td>
								</tr>

								<tr>
									<td class="text-center"><b><?php echo __('Month');?></b></td>
									<td class="text-center"><b><?php echo __('Name fees');?></b></td>
									<td class="text-center"><b><?php echo __('Price');?></b></td>
									<td class="text-center"><b><?php echo __('Quantily expected');?></b></td>
									<td class="text-center"><b><?php echo __('Discount fixed');?></b></td>
									<td class="text-center"><b><?php echo __('Discount');?></b></td>
									<td class="text-center"><b><?php echo __('Temporary money');?></b></td>
									<td colspan="3" class="text-center"><?php echo __('Note')?></td>
								</tr>
    						
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

		$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

		if ($r_s->getRsReceivableId ()) {
			$title_sevice = $r_s->getRTitle ();
		} elseif ($r_s->getRsServiceId ()) {
			$title_sevice = $r_s->getSTitle ();
		} elseif ($r_s->getRsIsLate () == 1) {
			$title_sevice = __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
		}

		if ($rs->getRsServiceId () > 0) {
			$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
			$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
		} else {
			$rs_amount = $rs->getRsAmount ();
		}
		$tongtienthangnay += $rs_amount;
		?>
    						<tr>
									<td class="text-center"><?php echo date('m-Y', $month_prev ) ?></td>
									<td><?php echo $title_sevice;?></td>
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsUnitPrice());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsByNumber());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($r_s->getRsDiscountAmount());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($r_s->getRsDiscount());?></td>
    							<?php
		if ($r_s->getRsServiceId () > 0) {

			$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

			$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
			$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
		} else {
			$rs_amount = $r_s->getRsAmount ();
			$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
		}
		?>
    							<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td colspan="3"></td>
								</tr>
    						
    						<?php }?>
    						
    						<tr>
									<td colspan="6" class="text-right"><b>Tổng tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
									<td colspan="3"></td>
								</tr>
    						<?php
	$tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
	$phi_nop_muon = $data ['psConfigLatePayment'];
	$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
	?>
    						<tr>
									<td colspan="6" class="text-right"><b>Dư tháng trước</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
									<td colspan="3" class="text-center">
    							<?php

if ($tien_thua_thang_truoc < 0) {
		echo 'Nợ cũ';
	}
	?>
    							</td>
								</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
									<td colspan="6" class="text-right"><b>Phí nộp muộn</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
									<td colspan="3"></td>
								</tr>
    						<?php }?>
    						
    						<tr>
									<td colspan="6" class="text-right"><b><i>Tổng phải nộp</i></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
									<td colspan="3"></td>
								</tr>

								<tr>
									<td colspan="6" class="text-right"><b>Số tiền phụ huynh nộp</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
									<td colspan="3"></td>
								</tr>

								<tr>
									<td colspan="6" class="text-right"><b>Dư tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
									<td colspan="3"></td>
								</tr>

							</tbody>
						</table>
					</div>

					<div class="text-right" style="padding-right: 25px">
						<i>Hà nội, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?></i>
					</div>

					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Phụ huynh</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Người lập</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Thủ quỹ</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

				</div>
			</div>
		</div>

<?php }elseif($file_template_file == 'bm_phieuthu_03.xls'){ // // xuất theo biểu mẫu 3 ?>


    <div class="col-md-6 col-xs-6 col-sm-6">

			<div class="modal-body">

				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3">
						<div class="logo">
							<img alt="<?php echo $ps_customer->getTitle();?>"
								src="<?php echo $ps_customer->getLogo();?>">
						</div>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<div class="thongtintruong">
							<p>
								<b><?php echo $ps_customer->getTitle();?></b>
							</p>
							<p><?php echo $ps_workplace->getTitle();?></p>
							<p><?php echo $ps_workplace->getAddress();?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="tieudephieu">
						<h5>
							<b><?php echo __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime($receipt->getReceiptDate ()) )?></b>
						</h5>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Liên 1</strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã phiếu thu: </strong><?php echo $receipt->getReceiptNo ()?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Học sinh : </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã Học sinh : </strong>
						<code><?php echo $student->getStudentCode ()?></code>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Lớp : </strong><?php echo $class_name;?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong></strong>
					</div>
				</div>

				<div class="row">

					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">

							<thead>
							</thead>
							<tbody>
        					<?php

	$total_oldRsAmount = 0;
	$total_oldRsLateAmount = 0;
	$tongtienthangnay = 0;
	$rs_current = array ();
	foreach ( $data ['receivable_student'] as $k => $rs ) {
		if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

			if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {

				$total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();

				$title = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . ' (' . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ), "m/Y" ) . ')';

				?>
        					            <tr>
									<td colspan="4"><?php echo $title?></td>
									<td colspan="1"><?php echo $rs->getRsAmount ()?></td>
									<td colspan="1"><?php echo $this->object->getContext ()->getI18N ()->__ ( $rs->getRsNote () )?></td>
								</tr>
        					            <?php
			} else {
				$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
			}
		} else {
			array_push ( $rs_current, $rs );
		}
	}

	?>
    						<?php if ($total_oldRsAmount > 0) {?>
    						<tr>
									<td colspan="6" class="text-left"><b>Thanh toán các khoản phí
											tháng trước</b></td>
								</tr>
								<tr>
									<td colspan="4">Dịch vụ khác</td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
								<tr>
									<td colspan="4" class="text-right"><b>Tổng</b></td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
							<?php }?>
    						<tr>
									<td colspan="6" class="text-left"><b>Dự kiến các khoản phí
											tháng này</b></td>
								</tr>

								<tr>
									<td class="text-center" style="max-width: 15px"><b>STT</b></td>
									<td class="text-center"><b>Học phí</b></td>
									<td class="text-center"><b>Đơn giá</b></td>
									<td class="text-center"><b>SL dự kiến</b></td>
									<td class="text-center"><b>Tạm tính</b></td>
									<td class="text-center"><b>Ghi chú</b></td>
								</tr>
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

		if ($rs->getRsReceivableId ()) {
			$title = $rs->getRTitle ();
		} elseif ($rs->getRsServiceId ()) {
			$title = $rs->getSTitle ();
		} elseif ($rs->getRsIsLate () == 1) {
			$title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Out late' );
		}

		if ($rs->getRsServiceId () > 0) {
			$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
			$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
		} else {
			$rs_amount = $rs->getRsAmount ();
		}
		$tongtienthangnay += $rs_amount;
		?>
    						<tr>
									<td class="text-center"><?php echo $k+1;?></td>
									<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsNote ());?></td>
								</tr>
    						
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b>Tổng tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
									<td></td>
								</tr>
    						<?php
	$tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
	$phi_nop_muon = $data ['psConfigLatePayment'];
	$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
	?>
    						<tr>
									<td colspan="4" class="text-right"><b>Dư tháng trước</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
									<td></td>
								</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
									<td colspan="4" class="text-right"><b>Phí nộp muộn</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
									<td></td>
								</tr>
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b><i>Tổng phải nộp</i></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Số tiền phụ huynh nộp</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Dư tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
									<td></td>
								</tr>

							</tbody>
						</table>
					</div>

					<div class="text-right" style="padding-right: 25px">
						<i>Hà nội, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?></i>
					</div>

					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Phụ huynh</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Người lập</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Thủ quỹ</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

				</div>
			</div>
		</div>

		<!-----------------------------------------Kết thúc liên 1--------------------------------------------->

		<div class="col-md-6 col-xs-6 col-sm-6">

			<div class="modal-body">

				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3">
						<div class="logo">
							<img alt="<?php echo $ps_customer->getTitle();?>"
								src="<?php echo $ps_customer->getLogo();?>">
						</div>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<div class="thongtintruong">
							<p>
								<b><?php echo $ps_customer->getTitle();?></b>
							</p>
							<p><?php echo $ps_workplace->getTitle();?></p>
							<p><?php echo $ps_workplace->getAddress();?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="tieudephieu">
						<h5>
							<b><?php echo __ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", strtotime($receipt->getReceiptDate ()) )?></b>
						</h5>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Liên 2</strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã phiếu thu: </strong><?php echo $receipt->getReceiptNo ()?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Học sinh : </strong><?php echo $student->getFirstName().' '.$student->getLastName()?><small>(<?php echo date('d-m-Y', strtotime($student->getBirthday()))?>)</small>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mã Học sinh : </strong>
						<code><?php echo $student->getStudentCode ()?></code>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Lớp : </strong><?php echo $class_name;?>
    			</div>
					<div class="col-md-6 col-xs-6 col-sm-6">
						<strong>Mẫu : 3</strong>
					</div>
				</div>

				<div class="row">

					<div class="custom-scroll table-responsive">
						<table id="dt_basic"
							class="table table-bordered table-hover no-footer no-padding"
							width="100%">

							<thead>
							</thead>
							<tbody>
        					<?php

	$total_oldRsAmount = 0;
	$total_oldRsLateAmount = 0;
	$tongtienthangnay = 0;
	$rs_current = array ();
	foreach ( $data ['receivable_student'] as $k => $rs ) {
		if ((date ( "Ym", PsDateTime::psDatetoTime ( $receipt->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

			if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {

				$total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();

				$title = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . ' (' . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ), "m/Y" ) . ')';

				?>
        					            <tr>
									<td colspan="4"><?php echo $title?></td>
									<td colspan="1"><?php echo $rs->getRsAmount ()?></td>
									<td colspan="1"><?php echo $this->object->getContext ()->getI18N ()->__ ( $rs->getRsNote () )?></td>
								</tr>
        					            <?php
			} else {
				$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
			}
		} else {
			array_push ( $rs_current, $rs );
		}
	}

	?>
    						<?php if ($total_oldRsAmount > 0) {?>
    						<tr>
									<td colspan="6" class="text-left"><b>Thanh toán các khoản phí
											tháng trước</b></td>
								</tr>
								<tr>
									<td colspan="4">Dịch vụ khác</td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
								<tr>
									<td colspan="4" class="text-right"><b>Tổng</b></td>
									<td colspan="2"><?php echo PreNumber::number_format($total_oldRsAmount)?></td>
								</tr>
							<?php }?>
    						<tr>
									<td colspan="6" class="text-left"><b>Dự kiến các khoản phí
											tháng này</b></td>
								</tr>

								<tr>
									<td class="text-center" style="max-width: 15px"><b>STT</b></td>
									<td class="text-center"><b>Học phí</b></td>
									<td class="text-center"><b>Đơn giá</b></td>
									<td class="text-center"><b>SL dự kiến</b></td>
									<td class="text-center"><b>Tạm tính</b></td>
									<td class="text-center"><b>Ghi chú</b></td>
								</tr>
    						
    						<?php

foreach ( $rs_current as $k => $rs ) {

		if ($rs->getRsReceivableId ()) {
			$title = $rs->getRTitle ();
		} elseif ($rs->getRsServiceId ()) {
			$title = $rs->getSTitle ();
		} elseif ($rs->getRsIsLate () == 1) {
			$title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Out late' );
		}

		if ($rs->getRsServiceId () > 0) {
			$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
			$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
		} else {
			$rs_amount = $rs->getRsAmount ();
		}
		$tongtienthangnay += $rs_amount;
		?>
    						<tr>
									<td class="text-center"><?php echo $k+1;?></td>
									<td class="text-left"><?php echo mb_convert_encoding ( $title, "UTF-8", "auto" );?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs->getRsUnitPrice ());?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsByNumber ());?></td>
									<td class="text-right"><?php echo PreNumber::number_format($rs_amount);?></td>
									<td class="text-center"><?php echo PreNumber::number_format($rs->getRsNote ());?></td>
								</tr>
    						
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b>Tổng tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tongtienthangnay);?></td>
									<td></td>
								</tr>
    						<?php
	$tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
	$phi_nop_muon = $data ['psConfigLatePayment'];
	$tong_phai_nop = $tongtienthangnay - $tien_thua_thang_truoc + $phi_nop_muon;
	?>
    						<tr>
									<td colspan="4" class="text-right"><b>Dư tháng trước</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tien_thua_thang_truoc);?></td>
									<td></td>
								</tr>
    						<?php if($phi_nop_muon > 0){?>
    						<tr>
									<td colspan="4" class="text-right"><b>Phí nộp muộn</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($phi_nop_muon);?></td>
									<td></td>
								</tr>
    						<?php }?>
    						
    						<tr>
									<td colspan="4" class="text-right"><b><i>Tổng phải nộp</i></b></td>
									<td class="text-right"><?php echo PreNumber::number_format($tong_phai_nop);?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Số tiền phụ huynh nộp</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getCollectedAmount ());?></td>
									<td></td>
								</tr>

								<tr>
									<td colspan="4" class="text-right"><b>Dư tháng này</b></td>
									<td class="text-right"><?php echo PreNumber::number_format($receipt->getBalanceAmount ());?></td>
									<td></td>
								</tr>

							</tbody>
						</table>
					</div>

					<div class="text-right" style="padding-right: 25px">
						<i>Hà nội, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?></i>
					</div>

					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Phụ huynh</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Người lập</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4">
						<div class="text-center chuky">
							<h5 style="margin-bottom: 3px">Thủ quỹ</h5>
							<p>
								<i>(Ký, họ tên)</i>
							</p>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px"></div>

				</div>
			</div>
		</div>


<?php }?>
</div>

</body>
</html>