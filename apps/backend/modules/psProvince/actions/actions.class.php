<?php
require_once dirname ( __FILE__ ) . '/../lib/psProvinceGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psProvinceGeneratorHelper.class.php';

/**
 * psProvince actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psProvince
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psProvinceActions extends autoPsProvinceActions {

	public function executeProvinceList(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$country_code = $request->getParameter ( 'cid' );
			if ($country_code != '')
				$this->psProvinces = Doctrine::getTable ( 'PsProvince' )->loadPsProvinceByCountry ( $country_code );
			else
				$this->psProvinces = array ();

			return $this->renderPartial ( 'psProvince/district_ps_province', array (
					'psProvinces' => $this->psProvinces ) );
		} else {
			exit ( 0 );
		}
	}

	// Overwrite auto
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Kiem tra ton tai du lieu Quan/Huyen
		$ps_districts = $this->getRoute ()
			->getObject ()
			->getPsDistricts ();

		if (count ( $ps_districts ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} elseif ($this->getRoute ()
			->getObject ()
			->delete ()) {

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_province' );
	}

	// Overwrite auto
	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		// Kiem tra ton tai du Quan/Huyen
		$ps_districts = Doctrine_Query::create ()->select ( 'id' )
			->from ( 'PsDistrict' )
			->whereIn ( 'ps_province_id', $ids )
			->execute ();

		if (count ( $ps_districts ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} else {

			$records = Doctrine_Query::create ()->from ( 'PsProvince' )
				->whereIn ( 'id', $ids )
				->execute ();

			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );

				$record->delete ();
			}

			$this->getUser ()
				->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		}

		$this->redirect ( '@ps_province' );
	}
}