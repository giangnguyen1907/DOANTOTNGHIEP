<?php
require_once dirname ( __FILE__ ) . '/../lib/psCamerasGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCamerasGeneratorHelper.class.php';

/**
 * psCameras actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psCameras
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCamerasActions extends autoPsCamerasActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_camera = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_camera->getPsWorkPlaces (), 'PS_SYSTEM_CAMERA_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_camera );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_camera = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_camera->getPsWorkPlaces (), 'PS_SYSTEM_CAMERA_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_camera );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_camera = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_camera->getPsWorkPlaces (), 'PS_SYSTEM_CAMERA_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_camera ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_cameras' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsCamera' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$ps_work_place = $record->getPsWorkPlaces ();
			$this->forward404Unless ( myUser::checkAccessObject ( $ps_work_place, 'PS_SYSTEM_CAMERA_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $record->getId () ) );
		}

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_cameras' );
	}
}