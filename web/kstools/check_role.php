<?php
//require_once('/home/kidsschool/domains/quanly.kidsschool.vn/public_html/config/ProjectConfiguration.class.php');
//require_once('F:/project/QuanLyKidsSchool/WebApps/config/ProjectConfiguration.class.php');
require_once (dirname( __FILE__ ).'/../../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'prod', false);

$sf_user = sfContext::createInstance($configuration)->getUser();

if (!$sf_user->isAuthenticated()) {
	die("You don't have permissions to browse server.");
}

if (!$sf_user->hasCredential('PS_CMS_ARTICLES_EDIT') && !$sf_user->hasCredential('PS_CMS_ARTICLES_ADD')) {
	die("You don't have permissions to browse server.");
}

$psHeaderFilter 	= $sf_user->getAttribute ( 'psHeaderFilter', null, 'admin_module' );

if (!$psHeaderFilter) {
	$ps_customer_id = myUser::getPscustomerID ();
} else {
	$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
}


if ($ps_customer_id <= 0) {
	die("You don't have permissions to browse server.");
}

$ps_customer_code 	 = md5($ps_customer_id);

//Lay co so dao tao cua he thong
$ps_workplaces = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id )->execute();
//print_r(sfConfig);die;
// ID co so dao tao
$path_ps_cms_article = sfConfig::get('app_ps_root_dir').'/customer_data/'.$ps_customer_code.'/ps_cms_articles';
//$path_ps_cms_article = 'https://quanly.kidsschool.vn/web/public_html/web/kstools/customer_data/'.$ps_customer_code.'/ps_cms_articles';
//echo "AAAAAAA".$path_ps_cms_article;die;
// check folder
$sfFilesystem = new sfFilesystem ();
$sfFilesystem->mkdirs ( $path_ps_cms_article, 0775 );

foreach ($ps_workplaces as $ps_workplace) {
	$title = PreString::covert_to_latin($ps_workplace->getTitle());
	$sfFilesystem->mkdirs ( $path_ps_cms_article.'/file/'.$title, 0775 );;
	$sfFilesystem->mkdirs ( $path_ps_cms_article.'/image/'.$title, 0775 );;
	$sfFilesystem->mkdirs ( $path_ps_cms_article.'/media/'.$title, 0775 );;
}
if (! defined ( 'KCFINDER_ROOT_DIR' )) {
	define ( 'KCFINDER_ROOT_DIR', $path_ps_cms_article );
}
if (! defined ( 'KCFINDER_UPLOADURL' )) {
	define ( 'KCFINDER_UPLOADURL', sfConfig::get('app_admin_module_web_dir')."/kstools/customer_data/".$ps_customer_code."/ps_cms_articles");
}
