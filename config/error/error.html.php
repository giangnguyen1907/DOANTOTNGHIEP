<!DOCTYPE html>
<html lang="vi" lang="vi" id="extr-page" class="ng-scope">
	<?php include '_include/_tag_head.php' ;?>
	<body class="desktop-detected menu-on-top ng-scope animated fadeInDown">

		<header id="header">
			<div id="logo-group">
				<span id="logo"><?php echo image_tag('banner_kidsschool.vn.png', array('alt' => 'kidsschool.vn')) ?></span>
			</div>
		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<h2 class="font-md">
						<div class="alert alert-danger fade in">
						    <button class="close" data-dismiss="alert">×</button>
						  <i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __('Page Not Found or The data you asked for is secure and you do not have proper credentials.') ?>
						 </div>
					</h2>
				</div>
			</div>

		</div>
		
		<!-- PAGE FOOTER -->		
		<?php include '_include/_page_footer.php' ;?>		
		<!-- END PAGE FOOTER -->

		<!--================================================== -->
		<?php $getRelativeUrlRoot = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/pace/pace.min.js"></script>

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events  -->		
		<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

		<!-- BOOTSTRAP JS -->		
		<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/bootstrap/bootstrap.min.js"></script>

		<!--[if IE 8]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

		<!-- MAIN APP JS FILE
		<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/app.min.js"></script>
		-->

	</body>
</html>