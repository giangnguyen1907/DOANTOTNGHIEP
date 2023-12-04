<?php

/**
 * psCmsArticles module configuration.
 *
 * @package    one.asia
 * @subpackage psCmsArticles
 * @author     one.asia <contact@one.asia - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsArticlesGeneratorConfiguration extends BasePsCmsArticlesGeneratorConfiguration {
	
	
	public function getFilterDefaults() {
		
		//$ps_customer_id = myUser::getPscustomerID ();
		
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
		
		if (!$psHeaderFilter) {
			$ps_customer_id = myUser::getPscustomerID ();
		} else {
			$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_ADD' ) && ! myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_EDIT' ) && ! myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_DELETE' )) {
			return array ('ps_customer_id' => $ps_customer_id,'is_publish' => PreSchool::PUBLISH );
		}

		return array ('ps_customer_id' => $ps_customer_id );
	}
	
}
