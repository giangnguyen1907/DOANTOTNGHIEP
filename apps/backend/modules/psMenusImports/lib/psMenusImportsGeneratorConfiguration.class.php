<?php

/**
 * psMenusImports module configuration.
 *
 * @package    KidsSchool.vn
 * @subpackage psMenusImports
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMenusImportsGeneratorConfiguration extends BasePsMenusImportsGeneratorConfiguration
{
	public function getFilterDefaults() {
		
		$member_id = myUser::getUser ()->getMemberId ();
		
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		
		return array (
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id );
	}
}
