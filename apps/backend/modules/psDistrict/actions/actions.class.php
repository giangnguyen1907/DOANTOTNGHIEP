<?php
require_once dirname ( __FILE__ ) . '/../lib/psDistrictGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psDistrictGeneratorHelper.class.php';

/**
 * psDistrict actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psDistrict
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psDistrictActions extends autoPsDistrictActions {

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->ps_district = $this->form->getObject ();
		$this->form->setDefault ( 'ps_province_id', $request->getParameter ( 'ps_province_id' ) );
	}

	// Function load group District by country filter, action for ajax
	public function executeGroupDistrictList(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$country_code = $request->getParameter ( 'cid' );

			if ($country_code != '')
				$this->psDistricts = Doctrine::getTable ( 'PsDistrict' )->getGroupPsDistricts ( $country_code );

			else
				$this->psDistricts = array ();

			return $this->renderPartial ( 'psDistrict/group_district', array (
					'psDistricts' => $this->psDistricts ) );
		} else {
			exit ( 0 );
		}
	}

	// Function load districts by province id, action for ajax
	public function executeDistrictsProvince(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$pid = $request->getParameter ( 'pid' );

			if ($pid > 0)

				$this->psDistricts = Doctrine::getTable ( 'PsDistrict' )->getAllByProvinceId ( $pid );

			else
				$this->psDistricts = array ();

			return $this->renderPartial ( 'psDistrict/option_select', array (
					'option_select' => $this->psDistricts ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Kiem tra ton tai du lieu Xa/Phuong
		if (count ( $this->getRoute ()
			->getObject ()
			->getPsWards () )) {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} elseif ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_district' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		// Kiem tra ton tai du lieu Xa/Phuong
		$ps_wards = Doctrine_Query::create ()->select ( 'id' )
			->from ( 'PsWard' )
			->whereIn ( 'ps_district_id', $ids )
			->execute ();

		if (count ( $ps_wards ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsDistrict' )
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

		$this->redirect ( '@ps_district' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_district = $form->save ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_district ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_district_new?ps_province_id=' . $form->getObject ()
					->getPsProvinceId () );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_district_edit',
						'sf_subject' => $ps_district ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
