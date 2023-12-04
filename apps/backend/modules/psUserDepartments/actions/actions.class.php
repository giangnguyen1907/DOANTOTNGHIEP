<?php
require_once dirname(__FILE__) . '/../lib/psUserDepartmentsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/psUserDepartmentsGeneratorHelper.class.php';

/**
 * psUserDepartments actions.
 *
 * @package kidsschool.vn
 * @subpackage psUserDepartments
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psUserDepartmentsActions extends autoPsUserDepartmentsActions {

	public function executeIndex(sfWebRequest $request) {

		$this->redirect('@sf_guard_user');
	
	}

	public function executeNew(sfWebRequest $request) {
		
		$this->ps_member = $this->getRoute ()->getObject ();
		
		if (!$this->ps_member || ($this->ps_member && $this->ps_member->getPsProvinceId () <= 0)) {
			$this->forward404 (sprintf ( 'Object does not exist.' ) );
		}
		
		if (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_PROVINCIAL || myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_DISTRICT) {
			if ($this->ps_member->getPsProvinceId () != myUser::getUser ()->getPsMember ()->getPsProvinceId () ) {
				$this->forward404 (sprintf ( 'Object does not exist.' ) );				
			} elseif ((myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_DISTRICT) && $this->ps_member->getPsDistrictId () != myUser::getUser ()->getPsMember ()->getPsDistrictId ()) {
				$this->forward404 (sprintf ( 'Object does not exist.' ) );
			}
		}
		
		$sf_guard_user = new sfGuardUser();
		
		$sf_guard_user->setMemberId($this->ps_member->getId());
		$sf_guard_user->setUserType(PreSchool::USER_TYPE_TEACHER);
		$sf_guard_user->setPsCustomerId($this->ps_member->getPsCustomerId());		
		$sf_guard_user->setFirstName($this->ps_member->getFirstName());
		$sf_guard_user->setLastName($this->ps_member->getLastName());
		
		$this->form = $this->configuration->getForm($sf_guard_user);
		
		//$this->form = new PsUserDepartmentsForm();
		
		//$this->sf_guard_user = new sfGuardUser();
		//$this->sf_guard_user->setPsCustomerId($this->ps_member->getPsCustomerId());
		//$this->sf_guard_user->setMemberId($this->ps_member->getId());
		//$this->sf_guard_user->setUserType(PreSchool::USER_TYPE_TEACHER);
		//$this->form = new PsUserDepartmentsForm($this->sf_guard_user);
		
		$this->form->setDefault('member_fullname', $this->ps_member->getMemberCode());
		//$this->form->setDefault('member_id', $this->ps_member->getId());
		$this->form->setDefault('member', $this->ps_member->getMemberCode().' - '.$this->ps_member->getFirstName().' '.$this->ps_member->getLastName());
		//$this->form->setDefault('user_type', PreSchool::USER_TYPE_TEACHER);
		//$this->form->setDefault('ps_customer_id', $this->ps_member->getPsCustomerId());
		
		$this->sf_guard_user = $this->form->getObject();
	}

	public function executeCreate(sfWebRequest $request) {
		
		$this->form = $this->configuration->getForm();
		
		$this->sf_guard_user = $this->form->getObject();
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('new');
	}

	public function executeEdit(sfWebRequest $request) {

		$this->sf_guard_user = $this->getRoute()->getObject();
		
		// $this->form = $this->configuration->getForm($this->sf_guard_user);
		
		$this->form = new PsUserDepartmentsForm($this->sf_guard_user);
	
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->sf_guard_user = $this->getRoute()->getObject();
		
		// $this->form = $this->configuration->getForm($this->sf_guard_user);
		$this->form = new PsUserDepartmentsForm($this->sf_guard_user);
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('edit');
	
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {
		
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		
		if ($form->isValid()) {
			echo 'ZZZZZZ';
			$notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
			
			try {
				
				$sf_guard_user = $form->save();
				
			} catch (Doctrine_Validator_Exception $e) {
				
				$errorStack = $form->getObject()->getErrorStack();
				
				$message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
				foreach ($errorStack as $field => $errors) {
					$message .= "$field (" . implode(", ", $errors) . "), ";
				}
				$message = trim($message, ', ');
				
				$this->getUser()->setFlash('error', $message);
				return sfView::SUCCESS;
			}
			
			$this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array(
					'object' => $sf_guard_user
			)));
			
			if ($request->hasParameter('_save_and_add')) {
				$this->getUser()->setFlash('notice', $notice . ' You can add another one below.');
				
				$this->redirect('@ps_user_departments');
			} else {
				$this->getUser()->setFlash('notice', $notice);
				
				$this->redirect(array(
						'sf_route' => 'ps_user_departments',
						'sf_subject' => $sf_guard_user
				));
			}			
		} else {
			echo 'Loi';
			$this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
			
			$this->redirect('@ps_hr_departments');
		}
	
	}

}
