<?php

/**
 * Profile actions.
 *
 * @package    Preschool
 * @subpackage errors
 * @author     Your name here
 * @version    1.0
 */
class psProfileActions extends sfActions {

	/**
	 * Executes index action
	 *
	 * @param sfRequest $request
	 *        	A request object
	 */
	public function executeProfile(sfWebRequest $request) {

		$this->user = myUser::getUser ();

		if ($this->user->getUserType () == PreSchool::USER_TYPE_TEACHER) {

			$this->ps_member = Doctrine::getTable ( 'PsMember' )->getPsMemberById ( $this->user->getMemberId () );
		}
	}

	public function executeChangePassword(sfWebRequest $request) {

		$this->form = new sfForm ();
		$this->form->setWidgets ( array (
				'old_password' => new sfWidgetFormInputPassword ( array (
						'type' => 'password' ) ),
				'new_password' => new sfWidgetFormInputPassword (),
				'comfirm_password' => new sfWidgetFormInputPassword () ) );
		$this->form->setValidators ( array (
				'old_password' => new sfValidatorString ( array (
						'max_length' => 20,
						'required' => true ) ),
				'new_password' => new sfValidatorString (),
				'comfirm_password' => new sfValidatorString () ) );

		$this->form->getWidgetSchema ()
			->setNameFormat ( 'change_password[%s]' );
	}

	public function executeSavePassword(sfWebRequest $request) {

		$user = myUser::getUser ();

		$change_password_form = $request->getParameter ( 'change_password' );

		if ($user->checkPassword ( $change_password_form ['old_password'] )) {
			$user->setPassword ( $change_password_form ['new_password'] );
			$user->save ();
			$this->getUser ()
				->setFlash ( 'notice', 'Change password successfully' );
			return $this->redirect ( '@ps_profile' );
		} else
			$this->getUser ()
				->setFlash ( 'error', 'Incorrect old password' );
		return $this->redirect ( '@ps_profile_change_password' );
	}
}



  

