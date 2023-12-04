<?php
require_once dirname ( __FILE__ ) . '/../lib/psHistoryScreenRelativesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psHistoryScreenRelativesGeneratorHelper.class.php';

/**
 * psHistoryScreenRelatives actions.
 *
 * @package kidsschool.vn
 * @subpackage psHistoryScreenRelatives
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHistoryScreenRelativesActions extends autoPsHistoryScreenRelativesActions {

	// Lay danh tai khoan phu huynh trong truong
	public function executeCustomerRelative(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$psc_id = $request->getParameter ( "psc_id" );

			if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' )) {
				$psc_id = myUser::getPscustomerID ();
			}

			$ps_relative = array ();

			if ($psc_id > 0)
				$ps_relative = Doctrine::getTable ( 'sfGuardUser' )->setSQLRelativeByCustomerId ( $psc_id, PreSchool::ACTIVE )
					->execute ();

			return $this->renderPartial ( 'psHistoryScreenRelatives/option_select', array (
					'option_select' => $ps_relative ) );
		} else {
			exit ( 0 );
		}
	}
}
