<?php
/**
 * sfGuardGroup module configuration.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardGroup
 * @author     Fabien Potencier
 * @version    SVN: $Id: sfGuardGroupGeneratorConfiguration.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardGroupGeneratorConfiguration extends BaseSfGuardGroupGeneratorConfiguration {

	public function getFilterDefaults() {

		return array (
				'ps_customer_id' => myUser::getPscustomerID () );
	}
}