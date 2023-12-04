<?php

require_once dirname(__FILE__).'/../lib/psHistoryFeesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/psHistoryFeesGeneratorHelper.class.php';

/**
 * psHistoryFees actions.
 *
 * @package    KidsSchool.vn
 * @subpackage psHistoryFees
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHistoryFeesActions extends autoPsHistoryFeesActions
{
	public function executeDetail(sfWebRequest $request)
	{
		$this->ps_history_fees = $this->getRoute()->getObject();
	}
}
