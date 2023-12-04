<!DOCTYPE html>
<html lang="vi" lang="vi" id="extr-page" class="ng-scope">
	<?php include '_include/_tag_head.php' ;?>
	<body class="desktop-detected menu-on-top ng-scope animated fadeInDown">

	<header id="header">

		<div id="logo-group">
			<span id="logo"><?php echo image_tag('banner_kidsschool.vn.png', array('alt' => 'kidsschool.vn')) ?></span>
		</div>
		<span id="extr-page-header-space">&nbsp;</span>
	</header>

	<div id="main" role="main">

		<!-- MAIN CONTENT -->
		<div id="content" class="container">

			<div class="row">
				<div
					class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
					<h1 class="txt-color-red login-header-big"><?php echo PreSchool::getPropertie('production','product_name_vn');?></h1>
					<div class="hero">
						<div class="pull-left login-desc-box-l">
							<h4 class="paragraph-header">
								<?php echo image_tag('kids.jpg', array('alt' => '')) ?>      
								</h4>
						</div>
					</div>
					<div class="row"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
					<div class="well no-padding">
							<?php include_slot('content_top') ?>        	
							<?php echo $sf_content ?>
						</div>
					<h5 class="text-center"><?php echo __('Connect with KidsSchool.vn')?></h5>
					<ul class="list-inline text-center">
						<li><a href="https://www.facebook.com/KidsSchool.vn"
							class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>
						</li>
						<li><a href="http://kidsschool.vn#twitter"
							class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
						</li>
						<li><a href="https://www.facebook.com/KidsSchool.vn"
							class="btn btn-warning btn-circle"><i
								class="fa fa-google-plus-official"></i></a></li>
					</ul>
				</div>
			</div>
		</div>

	</div>

	<!-- PAGE FOOTER -->		
		<?php include '_include/_page_footer.php' ;?>		
		<!-- END PAGE FOOTER -->

	<!--================================================== -->
		<?php $getRelativeUrlRoot = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
	<script
		src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/pace/pace.min.js"></script>

	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script> if (!window.jQuery) { document.write('<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	<script
		src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script> if (!window.jQuery.ui) { document.write('<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

	<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

	<!-- BOOTSTRAP JS -->
	<script
		src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/bootstrap/bootstrap.min.js"></script>

	<!-- JQUERY VALIDATE -->
	<script
		src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/jquery-validate/jquery.validate.min.js"></script>

	<!-- JQUERY MASKED INPUT -->
	<script
		src="<?php echo $getRelativeUrlRoot;?>/psAdminThemePlugin/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

	<!--[if IE 8]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

	<script type="text/javascript">
			//runAllForms();

			$(function() {

				$("#signin_username").focus();
				
				// Validation
				$("#login-form").validate({
					// Rules for form validation
					rules : {
						"signin[username]" : {
							required : true,
							minlength : 4,
							maxlength : 128
						},
						"signin[password]" : {
							required : true,
							minlength : 6,
							maxlength : 128
						}
					},

					// Messages for form validation
					messages : {
						"signin[username]" : {
							required : '<?php echo __('Please enter your username')?>',
							minlength : '<?php echo __('Please enter at least 4 characters')?>',
							maxlength : '<?php echo __('Please enter no more than 128 characters')?>'
							
						},
						"signin[password]" : {
							required : '<?php echo __('Please enter your password')?>',
							minlength : '<?php echo __('Please enter at least 6 characters')?>',
							maxlength : '<?php echo __('Please enter no more than 128 characters')?>'
						}
					},

					// Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});
			});
		</script>

</body>
</html>