<?php
require_once dirname ( __FILE__ ) . '/../lib/psHrDepartmentsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psHrDepartmentsGeneratorHelper.class.php';

/**
 * psHrDepartments actions.
 *
 * @package kidsschool.vn
 * @subpackage psHrDepartments
 * @author kidsschool.vn <contact@kidsschool.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHrDepartmentsActions extends autoPsHrDepartmentsActions {

	public function executeIndex(sfWebRequest $request) {

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' )
			) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_member = $this->getRoute ()->getObject ();

		if ($this->ps_member->getPsProvinceId () <= 0) {
			$this->forward404 (sprintf ( 'Object does not exist.' ) );
		}

		$this->form = $this->configuration->getForm ( $this->ps_member );

	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$check_new = $notice = $form->getObject ()->isNew ();

			$notice = $check_new ? 'The item was created successfully.' : 'The item was updated successfully.';

			$conn = Doctrine_Manager::connection ();

			try {
				$conn->beginTransaction ();

				$ps_member = $form->save ();

				if ($check_new) {

					$prefix_code = 'HR';

					$renderCode = $prefix_code . PreSchool::renderCode ( "%010s", $ps_member->getId () );
					$ps_member->setMemberCode ( $renderCode );
					$ps_member->save ();
				}

				if ($ps_member->getEmail () != '') {

					// Insert or Update email to ps_emails
					$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $ps_member->getId (), PreSchool::USER_TYPE_TEACHER );

					if (! $ps_email) {

						$ps_email = new PsEmails ();

						$ps_email->setPsEmail ( $ps_member->getEmail () );
						$ps_email->setObjId ( $ps_member->getId () );
						$ps_email->setObjType ( PreSchool::USER_TYPE_TEACHER );
						$ps_email->save ();
					} else {
						$ps_email->setPsEmail ( $ps_member->getEmail () );
						$ps_email->save ();
					}
				} else {

					$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $ps_member->getId (), PreSchool::USER_TYPE_TEACHER );
					if ($ps_email)
						$ps_email->delete ();
				}

				if (! $check_new) {

					$ps_user = Doctrine::getTable ( 'sfGuardUser' )->findOneByMemberId ( $ps_member->getId (), PreSchool::USER_TYPE_TEACHER );

					if ($ps_user) {
						$ps_user->setFirstName ( $ps_member->getFirstName () );
						$ps_user->setLastName ( $ps_member->getLastName () );
						$ps_user->setEmailAddress ( $ps_member->getEmail () );
						$ps_user->save ();
					}
				}

				$conn->commit ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$conn->rollback ();

				$errorStack = $form->getObject ()->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}

				$message = trim ( $message, ', ' );

				$this->getUser ()->setFlash ( 'error', $message );

				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_member
			) ) );

			if ($request->hasParameter ( '_save_and_add' )) {

				$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );
				$this->redirect ( '@ps_hr_departments_new' );
			} else {

				$this->getUser ()->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_hr_departments_edit',
						'sf_subject' => $ps_member
				) );
			}
		} else {
			$this->getUser ()->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}

	}

}
