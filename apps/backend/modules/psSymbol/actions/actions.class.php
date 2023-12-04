<?php
require_once dirname ( __FILE__ ) . '/../lib/psSymbolGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psSymbolGeneratorHelper.class.php';

/**
 * psClassRooms actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psClassRooms
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSymbolActions extends autopsSymbolActions {

	// // Lay phong hoc cua Truong hoac Co so dao tao
	// public function executeWorkplacePsClassRooms(sfWebRequest $request) {

	// 	if ($this->getRequest ()
	// 		->isXmlHttpRequest ()) {

	// 		$cid = intval ( $request->getParameter ( "psc_id" ) );

	// 		$wp_id = intval ( $request->getParameter ( "wp_id" ) );

	// 		$ps_class_rooms = Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id, title', array (
	// 				'ps_customer_id' => $cid,
	// 				'ps_workplace_id' => $wp_id ) )
	// 			->execute ();

	// 		return $this->renderPartial ( 'option_select', array (
	// 				'option_select' => $ps_class_rooms ) );
	// 	} else {
	// 		exit ( 0 );
	// 	}
	// }

	// public function executeEdit(sfWebRequest $request) {

	// 	// Check role tac dong den id
	// 	$this->ps_class_rooms = $this->getRoute ()
	// 		->getObject ();

	// 	$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_class_rooms->getPsWorkPlaces (), 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

	// 	$this->form = $this->configuration->getForm ( $this->ps_class_rooms );
	// }

	// public function executeUpdate(sfWebRequest $request) {

	// 	$this->ps_class_rooms = $this->getRoute ()
	// 		->getObject ();

	// 	$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_class_rooms->getPsWorkPlaces (), 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

	// 	$this->form = $this->configuration->getForm ( $this->ps_class_rooms );

	// 	$this->processForm ( $request, $this->form );

	// 	$this->setTemplate ( 'edit' );
	// }

	// public function executeDelete(sfWebRequest $request) {

	// 	$request->checkCSRFProtection ();

	// 	// Check role tac dong den id
	// 	$ps_class_rooms = $this->getRoute ()
	// 		->getObject ();

	// 	$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
	// 			'object' => $ps_class_rooms ) ) );

	// 	$this->forward404Unless ( myUser::checkAccessObject ( $ps_class_rooms->getPsWorkPlaces (), 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

	// 	// Check ton tai du lieu trong FeatureBranch
	// 	$featureBranchTimes = Doctrine::getTable ( 'FeatureBranchTimes' )->findBy ( 'ps_class_room_id', $ps_class_rooms->getId () );

	// 	$myClass = Doctrine::getTable ( 'MyClass' )->findBy ( 'ps_class_room_id', $ps_class_rooms->getId () );

	// 	$psServiceCourseSchedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->findBy ( 'ps_class_room_id', $ps_class_rooms->getId () );

	// 	$notice = $this->getContext ()
	// 		->getI18N ()
	// 		->__ ( 'The %value% was deleted successfully.', array (
	// 			"%value%" => $ps_class_rooms->getTitle () ), 'messages' );

	// 	if (count ( $featureBranchTimes ) > 0 || count ( $myClass ) > 0 || count ( $psServiceCourseSchedules ) > 0) {

	// 		$this->getUser ()
	// 			->setFlash ( 'error', 'The item has not been remove due have data related.' );

	// 		$this->redirect ( '@ps_class_rooms' );
	// 	} else if ($ps_class_rooms->delete ()) {

	// 		$this->getUser ()
	// 			->setFlash ( 'notice', $notice );

	// 		$this->redirect ( '@ps_class_rooms' );
	// 	}
	// }

	// protected function executeBatchDelete(sfWebRequest $request) {

	// 	$ids = $request->getParameter ( 'ids' );

	// 	$records = Doctrine_Query::create ()->from ( 'PsClassRooms' )
	// 		->whereIn ( 'id', $ids )
	// 		->execute ();

	// 	// Check ton tai du lieu trong FeatureBranch
	// 	$feature_branch_times = Doctrine_Query::create ()->select ( 'id' )
	// 		->from ( 'FeatureBranchTimes' )
	// 		->whereIn ( 'ps_class_room_id', $ids )
	// 		->count ();

	// 	$myClass = Doctrine_Query::create ()->select ( 'id' )
	// 		->from ( 'MyClass' )
	// 		->whereIn ( 'ps_class_room_id', $ids )
	// 		->count ();

	// 	if ($feature_branch_times > 0 || $myClass > 0) {

	// 		$this->getUser ()
	// 			->setFlash ( 'error', 'The item has not been remove due have data related.' );

	// 		$this->redirect ( '@ps_class_rooms' );
	// 	}

	// 	$check_role = true;

	// 	foreach ( $records as $record ) {

	// 		// Kiem tra du lieu thuoc ve Don vi/Nha truong
	// 		if (! myUser::checkAccessObject ( $record->getPsWorkPlaces (), 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' )) {

	// 			$check_role = false;

	// 			$this->forward404Unless ( $check_role, sprintf ( 'Object does not exist.' ) );

	// 			break;
	// 		} else {

	// 			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
	// 					'object' => $record ) ) );

	// 			$record->delete ();
	// 		}
	// 	}

	// 	$this->getUser ()
	// 		->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

	// 	$this->redirect ( '@ps_class_rooms' );
	// }

	// protected function processForm(sfWebRequest $request, sfForm $form) {

	// 	$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
	// 	if ($form->isValid ()) {
	// 		$notice = $form->getObject ()
	// 			->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

	// 		try {
	// 			$ps_class_rooms = $form->save ();
	// 		} catch ( Doctrine_Validator_Exception $e ) {

	// 			$errorStack = $form->getObject ()
	// 				->getErrorStack ();

	// 			$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
	// 			foreach ( $errorStack as $field => $errors ) {
	// 				$message .= "$field (" . implode ( ", ", $errors ) . "), ";
	// 			}
	// 			$message = trim ( $message, ', ' );

	// 			$this->getUser ()
	// 				->setFlash ( 'error', $message );
	// 			return sfView::SUCCESS;
	// 		}

	// 		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
	// 				'object' => $ps_class_rooms ) ) );

	// 		if ($request->hasParameter ( '_save_and_add' )) {
	// 			$this->getUser ()
	// 				->setFlash ( 'notice', $notice . ' You can add another one below.' );

	// 			$this->redirect ( '@ps_class_rooms_new' );
	// 		} else {
	// 			$this->getUser ()
	// 				->setFlash ( 'notice', $notice );

	// 			// $this->redirect(array('sf_route' => 'ps_class_rooms_edit', 'sf_subject' => $ps_class_rooms));
	// 			$this->redirect ( '@ps_class_rooms' );
	// 		}
	// 	} else {
	// 		$this->getUser ()
	// 			->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
	// 	}
	// }
}
