<?php

/**
 * sfGuardUser module configuration.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: sfGuardUserGeneratorConfiguration.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserGeneratorConfiguration extends BaseSfGuardUserGeneratorConfiguration {

	public function getFilterDefaults() {

		$member_id = myUser::getUser ()->getMemberId ();
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

		return array (
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id,
				'is_active' => PreSchool::CUSTOMER_ACTIVATED );
	}
}