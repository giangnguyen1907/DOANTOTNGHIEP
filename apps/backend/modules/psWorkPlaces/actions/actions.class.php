<?php
require_once dirname ( __FILE__ ) . '/../lib/psWorkPlacesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psWorkPlacesGeneratorHelper.class.php';

/**
 * psWorkPlaces actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psWorkPlaces
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psWorkPlacesActions extends autoPsWorkPlacesActions {

	// Lay ra thong tin gio nhan don xin nghi hop le cua mot don vi
	public function executeWorkplaceTimeReceiveValid(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$w_id = $request->getParameter ( "w_id" );

			if ($w_id > 0) {

				$ps_work_places = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $w_id );
			}
			$time_receive_valid = $ps_work_places->getConfigTimeReceiveValid () ? $ps_work_places->getConfigTimeReceiveValid () : null;
			echo $time_receive_valid;
			exit ( 0 );
		} else {
			exit ( 0 );
		}
		return 1;
	}

	// Lay Co so dao tao cua 1 don vi
	public function executeCustomerWorkPlaces(sfWebRequest $request) {

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$psc_id = $request->getParameter ( "psc_id" );

			$ps_work_places = array ();

			if (myUser::credentialPsCustomers ('PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL')) {
				if ($psc_id > 0)
					$ps_work_places = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $psc_id )->execute ();
			}

			return $this->renderPartial ( 'psWorkPlaces/option_select', array ('option_select' => $ps_work_places ) );
			
		} else {
			exit ( 0 );
		}
	}

	public function executeDetail(sfWebRequest $request) {

		// $this->filter_value = $this->getFilters();
		$work_place_id = $request->getParameter ( 'id' );

		if ($work_place_id <= 0) {

			$this->forward404Unless ( $work_place_id, sprintf ( 'Object does not exist.' ) );
		}
		// lay thong tin co so
		// $q = Doctrine_Query::create()
		// ->from('PsWorkPlaces')
		// ->where('id = ?', 1);

		// $this->work_place_detail = $q->fetchOne();

		$this->work_place_detail = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceById ( $work_place_id );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_work_places = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_work_places, 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		parent::executeEdit ( $request );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_work_places = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_work_places, 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		parent::executeUpdate ( $request );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Check role tac dong den id
		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.', $this->getRoute ()
			->getObject ()
			->getId () ) );

		// Check ton tai du lieu trong PsClassRooms truoc khi xoa
		$ps_workplaces = Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id', array (
				'ps_workplace_id' => $this->getRoute ()
					->getObject ()
					->getId () ) )
			->execute ();

		if (count ( $ps_workplaces ) > 0) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'The item has not been remove due have data related.' ) );

			$this->redirect ( '@ps_work_places' );
		} else {

			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}

			$this->redirect ( '@ps_work_places' );
		}
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );
		// Check dieu kien xoa
		$ps_workplaces = Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id', array (
				'ps_workplace_id' => $ids ) )
			->execute ();

		if (count ( $ps_workplaces ) > 0) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'The item has not been remove due have data related.' ) );

			$this->redirect ( '@ps_work_places' );
		} else {

			$records = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
				->whereIn ( 'id', $ids )
				->execute ();

			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );

				$record->delete ();
			}

			$this->getUser ()
				->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
			$this->redirect ( '@ps_work_places' );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->ps_work_places = $this->form->getObject ();

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneBy ( 'id', $ps_customer_id );
			$this->forward404Unless ( $ps_customer, sprintf ( 'Object (%s) does not exist .', $ps_customer_id ) );

			$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );
			$this->form->setDefault ( 'ps_ward_id', $ps_customer->getPsWardId () );
			$this->form->setDefault ( 'ps_district_id', $ps_customer->getPsWard ()
				->getPsDistrictId () );
			$this->form->setDefault ( 'ps_province_id', $ps_customer->getPsWard ()
				->getPsDistrict ()
				->getPsProvinceId () );
		}
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_work_places = $form->save ();
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
					'object' => $ps_work_places ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_work_places_new?ps_customer_id=' . $ps_work_places->getPsCustomerId () );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_work_places_edit',
						'sf_subject' => $ps_work_places ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
