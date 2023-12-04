<?php
if (!$sf_user->getAttribute ( 'ps_school_year_default')) {
	
	$ps_school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
	
	if ($ps_school_year) {
		
		$school_year_id = $ps_school_year->getId ();	
		
		$ps_school_year_default = new \stdClass ();	
		$ps_school_year_default->id = $ps_school_year->getId ();
		$ps_school_year_default->title = $ps_school_year->getTitle ();
		$ps_school_year_default->from_date = $ps_school_year->getFromDate ();
		$ps_school_year_default->to_date = $ps_school_year->getToDate ();
	
		$sf_user->setAttribute ( 'ps_school_year_default', $ps_school_year_default );
	}
}
$psMobileDetect = new PsMobileDetect();
$ps_mobile_detect_type = ($psMobileDetect->isMobile() ? ($psMobileDetect->isTablet() ? 'tablet' : 'phone') : 'computer');
?>
<!DOCTYPE html>
<html lang="vi" lang="vi">
	<?php include '_include/_tag_head.php' ;?>
	<body class="desktop-detected menu-on-top pace-done smart-style-3 fixed-header fixed-navigation">
		<?php include '_include/_page_header.php' ;?>
		<?php include '_include/_left_panel.php' ;?>
		<div id="main" role="main">
			<div id="content">	
			<?php include_slot('content_top') ?>        	
			<?php echo $sf_content ?>
			</div>
	</div>
		<?php include '_include/_page_footer.php' ;?>
	<!-- IMPORTANT JS -->
		<?php include_javascripts();?>
		<!--[if IE 8]>
		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1><![endif]-->
		<?php use_javascript('/psAdminThemePlugin/js/plugin/jquery-form/jquery-form.min.js')?>
	</body>
</html>