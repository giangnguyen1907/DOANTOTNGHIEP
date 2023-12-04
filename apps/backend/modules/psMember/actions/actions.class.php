<?php
require_once dirname ( __FILE__ ) . '/../lib/psMemberGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMemberGeneratorHelper.class.php';

/**
 * psMember actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psMember
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psMemberActions extends autoPsMemberActions {
	
	private $member_ids = array();
	
	public function executeMemberProfile(sfWebRequest $request) {

		$ps_member_id = $request->getParameter ( 'id' );

		if ($ps_member_id <= 0) {
			$this->forward404Unless ( $ps_member_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_member = Doctrine::getTable ( 'PsMember' )->getPsMemberById ( $ps_member_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_member_id ) );

		// Quyen rieng
		$this->_permissions = Doctrine::getTable ( 'sfGuardPermission' )->getPermissionByUserId ( $this->ps_member->getUserId () );

		// Nhom nguoi dung
		$this->groups = Doctrine::getTable ( 'sfGuardUserGroup' )->getGroupByUserId ( $this->ps_member->getUserId (), $this->ps_member->getPsCustomerId () );

		$track_at = date ( 'Ymd' );
		// Qua trinh cong tac hien tai
		$this->member_department = Doctrine::getTable ( 'PsMemberDepartments' )->getMemberDepartmentByMemberId ( $ps_member_id, $track_at );

		// Luong nhan vien hien tai
		$this->member_salary = Doctrine::getTable ( 'PsMemberSalary' )->getMemberSalaryByMemberId ( $ps_member_id, $track_at );

		$this->setTemplate ( 'memberProfile' );
	}

	/**
	 * Kiem tra email da ton tai chua.
	 * Neu da ton tai tra ve false <=> Ko cho nhap email nay nua
	 *
	 * @param
	 *        	string - email address
	 * @return json
	 */
	public function executeCheckEmail(sfWebRequest $request) {

		$email = $request->getParameter ( 'email' );

		$objid = $request->getParameter ( 'objid' );

		echo json_encode ( array (
				'valid' => psValidatorEmail::checkUniqueEmailPsMember ( $email, $objid, PreSchool::USER_TYPE_TEACHER ) ) );

		exit ( 0 );
	}

	public function executeCheckIdentityCard(sfWebRequest $request) {

		$identity_card = $request->getParameter ( 'identity_card' );

		$objid = $request->getParameter ( 'objid' );

		if (isset ( $identity_card ) && $identity_card != '') {

			$identity_card_boolean = Doctrine::getTable ( 'PsMember' )->checkIdentityCardExits ( $identity_card, $objid );

			echo json_encode ( array (
					'valid' => ! $identity_card_boolean ) );
		} else {

			echo json_encode ( array (
					'valid' => true ) );
		}

		exit ( 0 );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$check_new = $notice = $form->getObject ()
				->isNew ();

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
					// update firstname lastname email to sf_guard_user
					$ps_user = Doctrine::getTable ( 'sfGuardUser' )->findOneByMemberId ( $ps_member->getId (), PreSchool::USER_TYPE_TEACHER );

					if ($ps_user) {
						$ps_user->setFirstName ( $ps_member->getFirstName () );
						$ps_user->setLastName ( $ps_member->getLastName () );
						$ps_user->setEmailAddress ( $ps_member->getEmail () );
						$ps_user->save ();
					}

					// update member department
					// $ps_member_department =
				}
				// Tao user
				/*
				 * $sfGuardUser = Doctrine :: getTable('sfGuardUser')->findOneBy('member_id',$ps_member->getId()); if (!$sfGuardUser) {
				 * $sfGuardUser = new sfGuardUser();
				 * // Xu ly tao ten dang nhap tu dia chi email $numberUser = Doctrine::getTable('sfGuardUser')->checkLikeByUsername($ps_member->getEmail())->getNumberUser(); $setUsername = $ps_member->getEmail(); if ($numberUser > 0) { $setUsername = $ps_member->getEmail().($numberUser + 1);// Can tao thuat toan kiem tra trung lap }
				 * //echo $setUsername;die;
				 * $sfGuardUser->setMemberId($ps_member->getId()); $sfGuardUser->setPsCustomerId($ps_member->getPsCustomerId()); $sfGuardUser->setUsername($setUsername); //$sfGuardUser->setAlgorithm(); //$sfGuardUser->setAalt(); $sfGuardUser->setPassword($ps_member->getMemberCode()); $sfGuardUser->setIsActive(0); $sfGuardUser->setIsSuperAdmin(0); }
				 * $sfGuardUser->setPassword('123456'); $sfGuardUser->setFirstName($ps_member->getFirstName()); $sfGuardUser->setLastName($ps_member->getLastName()); $sfGuardUser->setEmailAddress($ps_member->getEmail()); $sfGuardUser->save();
				 */
				$conn->commit ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$conn->rollback ();

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
					'object' => $ps_member ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {

				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				if (myUser::credentialPsCustomers ( 'PS_HR_HR_FILTER_SCHOOL' )) {
					$this->redirect ( '@ps_member_new?customer_id=' . $ps_member->getPsCustomerId () );
				} else {
					$this->redirect ( '@ps_member_new' );
				}
			} else {

				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_member_edit',
						'sf_subject' => $ps_member ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_member = $this->form->getObject ();

		if (myUser::credentialPsCustomers ( 'PS_HR_HR_FILTER_SCHOOL' )) {

			$ps_customer_id = $request->getParameter ( 'customer_id' );

			if ($ps_customer_id > 0) {
				$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );
				$this->forward404Unless ( $psCustomer, sprintf ( 'Object does not exist .' ) );
				$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );
				$this->form->getObject ()
					->setPsCustomerId ( $ps_customer_id );
				$this->form = $this->configuration->getForm ( $this->form->getObject () );
			}
		}
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_member = $this->getRoute ()->getObject ();
		
		if ( !myUser::isAdministrator() && ( in_array($this->ps_member->getId(), $this->member_ids))) {
			$this->forward404 (sprintf ( 'Object does not exist.' ) );
		}
		
		if ($this->ps_member->getPsProvinceId() > 0) {
			$this->forward404 (sprintf ( 'Object does not exist.' ) );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_member );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_member = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_member );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_member = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.', $ps_member->getId () ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array ('object' => $this->getRoute ()->getObject () ) ) );
		
		if (in_array($ps_member->getId(), $this->member_ids)) {
			$this->forward404 (sprintf ( 'Object does not exist.' ) );
		}

		// Check dieu kien xoa

		// Bang User		
		$check_user = Doctrine::getTable ( 'sfGuardUser' )->checkUniqueMemberUserTypeExits ( $ps_member->getId(), PreSchool::USER_TYPE_TEACHER );
		
		if ($check_user) {
			$this->getUser ()->setFlash ( 'error', 'HR has been granted account. You cannot delete.' );
			$this->redirect ( '@ps_member' );
		}
		
		// Bang phan cong giao vien

		// Bang nhan su - phong ban

		// Bang Nhan su - luong

		// Bang

		try {
			if ($ps_member->delete ()) {
				$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		} catch ( Exception $e ) {
			// $this->getUser ()->setFlash ( 'error', $e->getMessage() );
			$this->getUser ()->setFlash ( 'error', 'The item has not been remove due have data related.' );
		}

		$this->redirect ( '@ps_member' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsMember' )->whereIn ( 'id', $ids )->whereNotIn('id', $this->member_ids);

		if (! myUser::credentialPsCustomers ( 'PS_HR_HR_FILTER_SCHOOL' )) { // La Admin he thong

			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );
		}

		$records->execute ();

		foreach ( $records as $record ) {
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_member' );
	}

	public function executeDetail(sfWebRequest $request) {

		$ps_member_id = $request->getParameter ( 'id' );

		if ($ps_member_id <= 0) {
			$this->forward404Unless ( $ps_member_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_member = Doctrine::getTable ( 'PsMember' )->getPsMemberById ( $ps_member_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_member_id ) );

		// Quyen rieng
		$this->_permissions = Doctrine::getTable ( 'sfGuardPermission' )->getPermissionByUserId ( $this->ps_member->getUserId () );

		// Nhom nguoi dung
		$this->groups = Doctrine::getTable ( 'sfGuardUserGroup' )->getGroupByUserId ( $this->ps_member->getUserId (), $this->ps_member->getPsCustomerId () );

		$track_at = date ( 'Ymd' );
		// Qua trinh cong tac hien tai
		$this->member_department = Doctrine::getTable ( 'PsMemberDepartments' )->getMemberDepartmentByMemberId ( $ps_member_id, $track_at );

		// Luong nhan vien hien tai
		$this->member_salary = Doctrine::getTable ( 'PsMemberSalary' )->getMemberSalaryByMemberId ( $ps_member_id, $track_at );
	}
}