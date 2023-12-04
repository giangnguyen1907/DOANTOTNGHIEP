<?php
require_once dirname ( __FILE__ ) . '/../lib/psWardGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psWardGeneratorHelper.class.php';

/**
 * psWard actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psWard
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psWardActions extends autoPsWardActions {

	// Lay Co so Xa - Phuong theo Quan-Huyen
	public function executePsWardDistrict(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$did = intval ( $request->getParameter ( "did" ) );

			$ps_wards = Doctrine::getTable ( 'PsWard' )->getPsWardsByPsDistrictId ( $did );

			$this->forward404Unless ( $ps_wards, sprintf ( 'Object (%s) does not exist .', $did ) );

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $ps_wards ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->ps_ward = $this->form->getObject ();

		$this->form->setDefault ( 'ps_district_id', $request->getParameter ( 'ps_district_id' ) );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_ward = $form->save ();
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
					'object' => $ps_ward ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_ward_new?ps_district_id=' . $form->getObject ()
					->getPsDistrictId () );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_ward_edit',
						'sf_subject' => $ps_ward ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
