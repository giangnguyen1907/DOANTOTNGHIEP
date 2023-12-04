<?php

/**
 * psFeeNewsLetters module configuration.
 *
 * @package    KidsSchool.vn
 * @subpackage psFeeNewsLetters
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeNewsLettersGeneratorConfiguration extends BasePsFeeNewsLettersGeneratorConfiguration {

	public function getFilterDefaults() {

		return array (
			'ps_year_month' 	=> date ( "Ym" )
		);

	}

}
