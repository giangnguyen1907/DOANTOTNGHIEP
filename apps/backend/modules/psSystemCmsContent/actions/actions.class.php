<?php
require_once dirname ( __FILE__ ) . '/../lib/psSystemCmsContentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psSystemCmsContentGeneratorHelper.class.php';

/**
 * psSystemCmsContent actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psSystemCmsContent
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSystemCmsContentActions extends autoPsSystemCmsContentActions {

	public function executeDetail(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$system_content_id = $request->getParameter ( 'id' );

		if ($system_content_id <= 0) {

			$this->forward404Unless ( $system_content_id, sprintf ( 'Object does not exist.' ) );
		}
		// lay thong tin bai viet
		$this->system_content_detail = Doctrine::getTable ( 'PsSystemCmsContent' )->getSystemCmsContentById ( $system_content_id );
		// $this->ps_system_cms_content = $this->getRoute()->getObject();
		// $this->forward404Unless ( myUser::checkAccessObject ( $this->ps_system_cms_content,'PS_SYSTEM_CONTENT_EDIT' ), sprintf ( 'Object does not exist.' ) );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$ps_system_cms_content = $form->save ();

				$is_activated = $ps_system_cms_content->get ( 'is_activated' );

				if ($is_activated == 1) {
					$ps_system_cms_content_code = $ps_system_cms_content->get ( 'ps_system_cms_content_code' );
					PsSystemCmsContentTable::DeactivateByCode ( $ps_system_cms_content_code, $ps_system_cms_content->getId () );
				}
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
					'object' => $ps_system_cms_content ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_system_cms_content_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_system_cms_content_edit',
						'sf_subject' => $ps_system_cms_content ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$is_activated = $this->getRoute ()
			->getObject ()
			->getIsActivated ();

		if ($is_activated == 1) {
			$this->getUser ()
				->setFlash ( 'warning', 'Đang kích hoạt sử dụng không được xóa' );
		} else {
			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				// PsSystemCmsContentTable::ActiveByCode('terms_of_use', $this->getRoute()->getObject()->getId());
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}

		$this->redirect ( '@ps_system_cms_content' );
	}
}
