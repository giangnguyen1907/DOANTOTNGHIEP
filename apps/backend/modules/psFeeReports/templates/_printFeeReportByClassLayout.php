<!DOCTYPE html>
<html lang="vi" lang="vi">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" media="screen" href="https://quanly.kidsschool.vn/web/psAdminThemePlugin/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="print" href="https://quanly.kidsschool.vn/web/psAdminThemePlugin/css/bootstrap.min.css">
<style type="text/css">
.row {margin: 0px}
body {
	background: #fff;
	font-size: 10px;
	color: #000;
	margin: 0 auto;
}

.table>tbody>tr>td {
	padding: 2px
}
.tieudephieu h5 {
	text-align: center;
	font-size: 12px
}
.chuky h5 {
	font-size: 10px;
	font-weight: bold;
}
.col-md-6 {
	width: 50%;
	float: left;
	padding: 0
}
</style>
<style>
@page {
	size: A5 landscape;
}
body { margin: 0px auto;}
#A5 {
  width: 210mm; 
  height: 148mm;
  font-size:10px;
  margin: 0px;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
  font-family:sans-serif;
}
@media print {
    a[href]:after {
        content: none !important;
    }
}
</style>
</head>
<body onload="window.print()">
	<div class="row">

<?php
$file_template_file = $psClass->getConfigTemplateReceiptExport ();
$config_choose_charge_showlate = $psClass->getConfigChooseChargeShowlate ();
// echo $file_template_file;

foreach ( $all_data_fee as $data_fee ) {

	if ($file_template_file == '01') { // xuất theo biểu mẫu 1
		include_partial ( 'psFeeReports/bieumau/_bm_phieubao_01', array (
				'receipt' => $data_fee ['ps_fee_receipt'],
				'data' => $data_fee,
				'psClass' => $psClass,
				'student' => $data_fee ['student'],
				'one_student' => 'in' ) );
	} elseif ($file_template_file == '02') { // xuất theo biểu mẫu 2
		include_partial ( 'psFeeReports/bieumau/_bm_phieubao_02', array (
				'receipt' => $data_fee ['ps_fee_receipt'],
				'data' => $data_fee,
				'psClass' => $psClass,
				'student' => $data_fee ['student'],
				'config_choose_charge_showlate' => $config_choose_charge_showlate,
				'one_student' => 'in' ) );
	} elseif ($file_template_file == '03') { // // xuất theo biểu mẫu 3
		include_partial ( 'psFeeReports/bieumau/_bm_phieubao_03', array (
				'receipt' => $data_fee ['ps_fee_receipt'],
				'data' => $data_fee,
				'psClass' => $psClass,
				'student' => $data_fee ['student'],
				'config_choose_charge_showlate' => $config_choose_charge_showlate,
				'one_student' => 'in' ) );
	}elseif ($file_template_file == '04') { // // xuất theo biểu mẫu 4
		include_partial ( 'psFeeReports/bieumau/_bm_phieubao_04', array (
		'receipt' => $data_fee ['ps_fee_receipt'],
		'data' => $data_fee,
		'psClass' => $psClass,
		'student' => $data_fee ['student'],
		'config_choose_charge_showlate' => $config_choose_charge_showlate,
		'one_student' => 'in' ) );
	}
}

?>
</div>

</body>
</html>