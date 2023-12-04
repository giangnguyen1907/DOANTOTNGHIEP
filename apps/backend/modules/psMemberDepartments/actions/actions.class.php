<?php
require_once dirname ( __FILE__ ) . '/../lib/psMemberDepartmentsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMemberDepartmentsGeneratorHelper.class.php';

/**
 * psMemberDepartments actions.
 *
 * @package kidsschool.vn
 * @subpackage psMemberDepartments
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMemberDepartmentsActions extends autoPsMemberDepartmentsActions {

	public function executeIndex(sfWebRequest $request) {

		$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );

	}

	// Lay danh sach phong ban cua co so hoac truong
	public function executeDepartmentWorkplace(sfWebRequest $request) {

		// $customer_id = $request->getParameter('c_id');
		if ($request->getParameter ( 'c_id' ) > 0)
			$ps_customer_id = $request->getParameter ( 'c_id' );
		else
			$ps_customer_id = myUser::getPscustomerID ();

		$workplace_id = $request->getParameter ( 'w_id' );

		$this->ps_department = Doctrine::getTable ( 'PsDepartment' )->getDepartmentByWorkplaceId ( $workplace_id, $ps_customer_id );

		return $this->renderPartial ( 'psMemberAbsents/option_select_department', array (
				'option_select' => $this->ps_department
		) );

	}

	// Lay danh sach nhan vien thuoc phong ban
	public function executeMemberDepartment(sfWebRequest $request) {

		$ps_department_id = $request->getParameter ( 'd_id' );

		$this->ps_member = Doctrine::getTable ( 'PsMemberDepartments' )->getMemberDepartments ( $ps_department_id );

		return $this->renderPartial ( 'psMemberAbsents/option_select_member', array (
				'option_select' => $this->ps_member
		) );

	}

	public function executeNew(sfWebRequest $request) {

		$ps_member_id = $request->getParameter ( 'ps_member_id' );

		if ($ps_member_id <= 0) {

			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );

			$this->redirect ( '@ps_member' );
		} else {

			$this->ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $ps_member_id );

			// $this->forward404Unless(myUser::checkAccessObject($this->ps_department, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member->getId()));

			$ps_member_departments = new PsMemberDepartments ();

			$ps_member_departments->setPsMemberId ( $ps_member_id );

			$this->form = $this->configuration->getForm ( $ps_member_departments );

			$this->ps_member_departments = $this->form->getObject ();

			// $this->ps_member = $this->getRoute()->getObject()->getPsMember();

			if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
				$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
				$this->redirect ( '@ps_member' );
			}

			$this->helper = new psMemberDepartmentsGeneratorHelper ();

			return $this->renderPartial ( 'psMemberDepartments/newSuccess', array (
					'ps_member_departments' => $this->ps_member_departments,
					'form' => $this->form,
					'ps_member' => $this->ps_member,
					'configuration' => $this->configuration,
					'helper' => $this->helper
			) );
		}

	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_member_departments = $this->getRoute ()->getObject ();

		$this->ps_member = $this->getRoute ()->getObject ()->getPsMember ();
		// $this->forward404Unless(myUser::checkAccessObject($this->ps_member_departments, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member_departments->getId()));
		if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->form = $this->configuration->getForm ( $this->ps_member_departments );

		$this->form->setDefault ( 'url_callback', $request->getParameter ( 'url_callback' ) );

		$this->helper = new psMemberDepartmentsGeneratorHelper ();

		return $this->renderPartial ( 'psMemberDepartments/newSuccess', array (
				'ps_member_departments' => $this->ps_member_departments,
				'form' => $this->form,
				'configuration' => $this->configuration,
				'helper' => $this->helper
		) );

	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'ps_member_departments' );

		$ps_member_id = isset ( $formValues ['ps_member_id'] ) ? $formValues ['ps_member_id'] : '';

		if ($ps_member_id <= 0) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $ps_member_id );

		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$ps_member_departments = new PsMemberDepartments ();

		$ps_member_departments->setPsMemberId ( $ps_member_id );

		$this->form = $this->configuration->getForm ( $ps_member_departments );

		$this->processForm2 ( $request, $this->form, $ps_member );

		exit ( 0 );

	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_member_departments = $this->getRoute ()->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_member_departments );

		$this->ps_member = $this->ps_member_departments->getPsMember ();

		$ps_member = $this->ps_member;

		// $this->forward404Unless(myUser::checkAccessObject($this->my_class, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member->getId()));
		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->helper = new psMemberDepartmentsGeneratorHelper ();

		$this->processForm2 ( $request, $this->form, $ps_member );

	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_member_departments = $this->getRoute ()->getObject ();

		$ps_member = $ps_member_departments->getPsMember ();

		// $this->forward404Unless(myUser::checkAccessObject($this->ps_member_departments, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member_departments->getId()));
		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_member_departments
		) ) );

		if ($ps_member_departments->delete ()) {
			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_2' );

	}

	protected function processForm2(sfWebRequest $request, sfForm $form, $ps_member) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$notice = $form->getObject ()->isNew () ? 'The member departments was created successfully.' : 'The member departments was updated successfully.';

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				$formValues = $request->getParameter ( 'ps_member_departments' );

				$department = isset ( $formValues ['ps_department_id'] ) ? $formValues ['ps_department_id'] : '';
				$function = isset ( $formValues ['ps_function_id'] ) ? $formValues ['ps_function_id'] : '';

				if ($department == '' || $function == '') {

					$message = 'Error: Department or function of member is null';

					$this->getUser ()->setFlash ( 'error', $message );

					$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_2' );
				}

				$current_active = $formValues ['is_current'];

				$tracked_at = date ( 'Ymd' );

				if (($current_active == PreSchool::ACTIVE))
					$records = Doctrine_Query::create ()->update ( 'PsMemberDepartments' )->set ( 'is_current', PreSchool::NOT_ACTIVE )->where ( 'is_current=? ', PreSchool::ACTIVE )->where ( ' 	ps_member_id=?', $ps_member->getId () )->execute ();

				$ps_member_departments = $form->save ();

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

				$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_2' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_member_departments
			) ) );

			$this->getUser ()->setFlash ( 'notice', $notice );
			$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_2' );
		} else {
			$this->getUser ()->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}

	}

}
