<head>
<meta charset="utf-8">
<title><?php echo PreSchool::getPropertie('production','product_name_vn');?></title>
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn. Giải pháp kết nối thông tin trường mầm non trường mầm non." />
<meta name="keywords"
	content="kidsschool, kids school,kid school,kidsschool.vn, kids online,quản lý mầm non, trường mầm non,Preschool,Software, thực đơn, dinh dưỡng, nhân sự" />
<meta name="copyright" content="2012 Newwaytech Ltd." />
<meta name="GENERATOR"
	content="CÔNG TY TNHH ĐẦU TƯ PHÁT TRIỂN ỨNG DỤNG CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG - www.newwaytech.vn" />
<meta name="author"
	content="CÔNG TY TNHH ĐẦU TƯ PHÁT TRIỂN ỨNG DỤNG CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG - www.newwaytech.vn" />

<meta property="og:title"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn. Giải pháp kết nối thông tin trường mầm non." />
<meta property="og:description"
	content="Hệ thống quản lý trường mầm non - kidsschool.vn" />
<meta property="fb:app_id" content="" />
<meta property="og:image"
	content="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/favicon.png" />

<meta id="MetaCopyright" name="COPYRIGHT"
	content="Copyright © 2012 kidsschool.vn" />
<meta id="MetaRobots" name="ROBOTS" content="index, follow" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://kidsschool.vn" />
<meta name="google-site-verification"
	content="0Bd2fK9p5eJGK6poFseDkwnSODuHCG1MsKQnHOMkvKQ" />		
		<?php include_stylesheets() ?>
		<!-- #FAVICONS -->
<link rel="shortcut icon"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/favicon.png"
	type="image/x-icon">
<link rel="icon" type="image/png"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/favicon.png" />
<!-- #GOOGLE FONT -->
<link rel="stylesheet"
	href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
<!-- #APP SCREEN / ICONS -->
<link rel="apple-touch-icon"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/sptouch-icon-iphone.png">
<link rel="apple-touch-icon" sizes="76x76"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/touch-icon-ipad.png">
<link rel="apple-touch-icon" sizes="120x120"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/touch-icon-iphone-retina.png">
<link rel="apple-touch-icon" sizes="152x152"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/touch-icon-ipad-retina.png">

<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!-- Startup image for web apps -->
<link rel="apple-touch-startup-image"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/ipad-landscape.png"
	media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
<link rel="apple-touch-startup-image"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/ipad-portrait.png"
	media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
<link rel="apple-touch-startup-image"
	href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/img/splash/iphone.png"
	media="screen and (max-device-width: 320px)">

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
			if (!window.jQuery) {
				document.write('<script src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/libs/jquery-2.1.4.min.js"><\/script>');
			}
		</script>
<script
	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
<script
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/libs/jquery-ui-i18n.min.js"></script>
<script>
			var PS_URL_PATH   	= '<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>';
			var PS_CULTURE 		= '<?php echo ($sf_user->getCulture() == 'vi') ? 'vi_VN' : $sf_user->getCulture();?>';
		</script>
</head>