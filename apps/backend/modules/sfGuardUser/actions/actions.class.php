<?php
/**
 * sfGuardUser actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage sfGuardUser
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 31002 2010-09-27 12:04:07Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions {
	
	private $user_ids = array(1,109,4614);// Id khong duoc xoa
	
	public function executeManagerNew(sfWebRequest $request) {
		
		/*
		$this->form = new sfGuardUserMForm();// $this->configuration->getForm ();		
		
		$utype = 'M';
		
		$this->sf_guard_user = $this->form->getObject ();
		
		$this->sf_guard_user->setUserType ( $utype );
		
		$this->form->setDefault ( 'user_type', $utype );		
				
		$this->form = new sfGuardUserMForm($this->sf_guard_user );
		*/
		
		$this->form = new sfGuardUserMForm();
		$this->sf_guard_user = $this->form->getObject();
	}
	
	public function executeManagerCreate(sfWebRequest $request) {
		
		$this->form = new sfGuardUserMForm();
		
		$this->sf_guard_user = $this->form->getObject ();
		
		$this->processFormManager ( $request, $this->form );
		
		$this->setTemplate ( 'managerNew' );
	}
	
	public function executeManagerEdit(sfWebRequest $request)
	{
		//$this->sf_guard_user = $this->getRoute()->getObject();		
		
		$this->sf_guard_user = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $request->getParameter ( 'id' ) );
		
		$this->form = new sfGuardUserMForm($this->sf_guard_user);
		
		$this->setTemplate('managerNew');
	}
	
	public function executeManagerUpdate(sfWebRequest $request)
	{
		$this->sf_guard_user = $this->getRoute()->getObject();
		$this->form = new sfGuardUserMForm($this->sf_guard_user);
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('managerNew');
	}
	
	public function executeUserActivated(sfWebRequest $request) {

		$id = $request->getParameter ( 'id' );

		$user = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $id );

		$check = (myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_EDIT' ) && $user && $this->isCredentials ( $user ) && myUser::checkAccessObject ( $user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ));

		if (! $check) {

			return $this->renderPartial ( 'sfGuardUser/field_user_activated', array (
					'type' => 'list',
					'sf_guard_user' => $user ) );
		}

		$state = $request->getParameter ( 'state' );

		try {

			if ($user->getIsActive () != $state && in_array ($state, array (0,1,2))) {
				$user->setIsActive ( $state );
				$user->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
				$user->save ();
			}
		} 
		catch ( Exception $e ) {
			$this->redirect ( '@sf_guard_user' );
		}

		return $this->renderPartial ( 'sfGuardUser/field_user_activated', array (
				'type' => 'list',
				'sf_guard_user' => $user ) );
	}

	public function executeCheckUsername(sfWebRequest $request) {

		$sf_guard_user = $request->getParameter ( 'sf_guard_user' );
		$userid = $request->getParameter ( 'userid' );

		if (isset ( $sf_guard_user ['username'] ) && $sf_guard_user ['username'] != '') {

			$sfGuardUser = Doctrine::getTable ( 'sfGuardUser' )->checkUsernameExits ( $sf_guard_user ['username'], $userid );

			echo json_encode ( array (
					'valid' => ! $sfGuardUser ) );
		} else {

			echo json_encode ( array (
					'valid' => true ) );
		}

		exit ( 0 );
	}

	public function executeUsersCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = intval ( $request->getParameter ( 'cid' ) );
			$user_type = $request->getParameter ( 'type' );

			if ($ps_customer_id > 0 && ($user_type == PreSchool::USER_TYPE_TEACHER || $user_type == PreSchool::USER_TYPE_RELATIVE)) {

				// Neu la nhan su cua truong => Lay tu ps_members
				if ($user_type == PreSchool::USER_TYPE_TEACHER) {

					// Lay danh sach nhan su chua duoc cap account
					$ps_users_info = Doctrine_Core::getTable ( 'PsMember' )->setSQLMemberForUser ( $ps_customer_id, false )->execute ();

					return $this->renderPartial ( 'option_select', array (
							'option_select' => $ps_users_info ) );
				} elseif ($user_type == PreSchool::USER_TYPE_RELATIVE) {

					// Lay danh sach nguoi dung chua duoc cap account
					$ps_users_info = Doctrine_Core::getTable ( 'Relative' )->setSQLRelativeForUser ( $ps_customer_id, false )->execute ();

					return $this->renderPartial ( 'option_select', array (
							'option_select' => $ps_users_info ) );
				} else {
					exit ( 0 );
				}
			} else {
				exit ( 0 );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeRelativeCustomer(sfRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$ps_customer_id = intval ( $request->getParameter ( 'cid' ) );
			if ($ps_customer_id > 0) {
				$relative_info = Doctrine::getTable ( 'Relative' )->setSQLRelative ( $ps_customer_id )
					->execute ();

				return $this->renderPartial ( 'option_select', array (
						'option_select' => $relative_info ) );
			} else
				exit ( 0 );
		} else
			exit ( 0 );
	}

	public function executeUserList(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = $request->getParameter ( 'cid' );

			if ($ps_customer_id > 0) {

				$this->sfGuardGroupForm = new sfGuardGroupForm ();

				$this->sfGuardGroupForm->addUsersExpandedForm ( 'users_list', $ps_customer_id );

				return $this->renderPartial ( 'sfGuardUser/list_user', array (
						'sfGuardGroupForm' => $this->sfGuardGroupForm ) );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeGroupUserByCustomer(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'cid' );

		if ($ps_customer_id > 0) {

			// $ps_guard_groups = Doctrine :: getTable('sfGuardGroup')->getGroupsUserByCustomer($ps_customer_id);

			$this->sfGuardUserForm = new sfGuardUserForm ();
			$this->sfGuardUserForm->addGroupExpandedForm ( 'groups_list', $ps_customer_id );

			return $this->renderPartial ( 'sfGuardGroup/list_group', array (
					'sfGuardUserForm' => $this->sfGuardUserForm ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$utype = $request->getParameter ( 'utype' );

		if (($utype != PreSchool::USER_TYPE_RELATIVE) && ($utype != PreSchool::USER_TYPE_TEACHER)) {

			$utype = PreSchool::USER_TYPE_TEACHER;
		}

		$this->sf_guard_user = $this->form->getObject ();

		$this->form->setDefault ( 'user_type', $utype );

		$this->sf_guard_user->setUserType ( $utype );

		$mid = $request->getParameter ( 'mid' );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) {
			$cid = $request->getParameter ( 'cid' );
		} else {
			$cid = myUser::getPscustomerID ();
		}
		
		if ($mid > 0) {

			if ($utype == PreSchool::USER_TYPE_TEACHER)
				$ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $mid );
			else
				$ps_member = Doctrine::getTable ( 'Relative' )->findOneById ( $mid );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_member, 'PS_SYSTEM_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->form->setDefault ( 'member_id', $mid );
			$this->form->setDefault ( 'ps_customer_id', $ps_member->getPsCustomerId () );

			$this->sf_guard_user->setMemberId ( $mid );

			$this->sf_guard_user->setPsCustomerId ( $ps_member->getPsCustomerId () );
		} elseif ($cid > 0) {

			$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $cid );

			if ((! myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' ) && $cid != myUser::getPscustomerID ()) || ! $psCustomer) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}

			$this->sf_guard_user->setPsCustomerId ( $cid );
		}

		$this->form = $this->configuration->getForm ( $this->sf_guard_user );
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->sf_guard_user = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->sf_guard_user = $this->getRoute ()->getObject ();

		$this->forward404Unless ( $this->isCredentials ( $this->sf_guard_user ), 'The data you asked for is secure and you do not have proper credentials.' );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->sf_guard_user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->sf_guard_user );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->sf_guard_user = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( $this->isCredentials ( $this->sf_guard_user ), 'The data you asked for is secure and you do not have proper credentials.' );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->sf_guard_user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->sf_guard_user );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$user = $this->getRoute ()->getObject ();

		$this->forward404Unless ( $this->isCredentials ( $user ), 'The data you asked for is secure and you do not have proper credentials.' );

		$this->forward404Unless ( myUser::checkAccessObject ( $user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array ('object' => $this->getRoute ()->getObject () ) ) );

		if ($this->getRoute ()->getObject ()->delete ()) {
			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.');
		}

		$this->redirect ( '@sf_guard_user' );
	}

	public function executeDetail(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$user_id = $request->getParameter ( 'id' );

			if ($user_id < 0) {
				$this->forward404Unless ( $user_id, sprintf ( 'Object does not exist.' ) );
			}

			$user = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $user_id );
			
			if (!myUser::checkAccessObject ( $user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ) || !$this->isCredentials ( $user ))
				$this->setTemplate('detailError404','psCpanel');

			$user_detail = Doctrine::getTable ( 'sfGuardUser' )->getUserDetail ( $user_id );
			
			if (!$user_detail) {
				
				$this->setTemplate('detailError404','psCpanel');
				
			} else {
				
				$this->user_detail = $user_detail;
				
				// Quyen
				$this->_permissions = Doctrine::getTable ( 'sfGuardPermission' )->getPermissionByUserId ( $user_id );
				
				// Nhom nguoi dung
				$this->groups = Doctrine::getTable ( 'sfGuardUserGroup' )->getGroupByUserId ( $user_id, $user_detail->getCustomerId () );				
			}						
		} else {
			$this->setTemplate('detailError404','psCpanel');
		}
	}

	public function executeRefreshpassword(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$user_id = $request->getParameter ( 'id' );

			if ($user_id < 0) {
				$this->setTemplate('detailError404','psCpanel');
			}

			$user = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $user_id );

			if (!myUser::checkAccessObject ( $user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ) || !$this->isCredentials ( $user ))
				$this->setTemplate('detailError404','psCpanel');
				
			
			$user_detail = Doctrine::getTable ( 'sfGuardUser' )->getUserDetail ( $user_id );

			$this->user_detail = $user_detail;

			$this->form = new sfForm ();

			$this->form->setWidgets ( array (
					'new_password' => new sfWidgetFormInputPassword (array(),array('minlength' => 8,'autofocus'=>'autofocus')),
					'comfirm_password' => new sfWidgetFormInputPassword (array(),array('minlength' => 8)) ) );
			
			$this->form->setValidators ( array (
					'new_password' => new sfValidatorString (),
					'comfirm_password' => new sfValidatorString () ) );

			$this->form->getWidgetSchema ()
				->setNameFormat ( 'change_password[%s]' );

			$track_at = date ( 'Ymd' );
			// Qua trinh cong tac hien tai
			if ($user_detail->getUserType () == PreSchool::USER_TYPE_TEACHER) {
				$this->member_department = Doctrine::getTable ( 'PsMemberDepartments' )->getMemberDepartmentByMemberId ( $user_detail->getMemberId (), $track_at );
				$this->workplace_name = Doctrine::getTable ( 'PsMember' )->getWorkplaceName ( $user_detail->getMemberId () );
					if($this->workplace_name){
						$this->workplace_name = $this->workplace_name->getWpTitle ();
					}else{$this->workplace_name = '';}
			} else {
				$this->relative_students = Doctrine::getTable ( 'RelativeStudent' )->findStudentsByRelativeId ( $user_detail->getMemberId (), $user_detail->getPsCustomerId () );
				$this->workplace_name = Doctrine::getTable ( 'Relative' )->getRelativeById ( $user_detail->getMemberId () );
					if($this->workplace_name){
						$this->workplace_name = $this->workplace_name->getWpTitle ();
					}else{$this->workplace_name = '';}
			}
		} else
			exit ( 0 );
	}

	public function executeSavePasswordRefresh(sfWebRequest $request) {

		// $user = myUser::getUser();
		$change_password_form = $request->getParameter ( 'change_password' );

		$user_id_pass = $change_password_form ['user_id'];

		$user = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $user_id_pass );
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		
		if (!myUser::checkAccessObject ( $user, 'PS_SYSTEM_USER_FILTER_SCHOOL' ) || !$this->isCredentials ( $user ))
			$this->setTemplate('detailError404','psCpanel');
			
		$user->setPassword ( $change_password_form ['new_password'] );
		$user->setUserUpdatedId ( myUser::getUserId () );

		$user->save ();

		$this->getUser ()
			->setFlash ( 'notice', 'Change password successfully' );

		return $this->redirect ( '@sf_guard_user' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			$utype = $cid = '';
			try {

				$sf_guard_user = $form->save ();

				$utype = $sf_guard_user->getUserType ();
				$cid = $sf_guard_user->getPsCustomerId ();
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
					'object' => $sf_guard_user ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$qry = 'utype=' . $utype;

				if (myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) {
					$qry .= '&cid=' . $cid;
				}

				$this->redirect ( '@sf_guard_user_new?' . $qry );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'sf_guard_user_edit',
						'sf_subject' => $sf_guard_user ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
	
	protected function processFormManager(sfWebRequest $request, sfForm $form) {
		
		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		
		if ($form->isValid ()) {
			
			$notice = $form->getObject ()->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';
			
			try {
				
				$sf_guard_user = $form->save ();
				
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
					'object' => $sf_guard_user ) ) );
			
			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );
				
				$this->redirect ( '@sf_guard_user_new_manager');
				
			} else {
				$this->getUser ()->setFlash ( 'notice', $notice );
				
				$this->redirect ( array ('sf_route' => 'sf_guard_user_edit','sf_subject' => $sf_guard_user ) );
			}
		} else {
			$this->getUser ()->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	// Check quyen sua/xoa voi admin
	protected function isCredentials($sf_guard_user) {

		$boolean = true;
		
		if (in_array($sf_guard_user->getId(), $this->user_ids) && !myUser::isAdministrator()) {
			//echo "AAAAAAAA";die;
			$boolean = false;
		} else {
			// Neu user bị sửa dữ liệu là Admin hệ thống => Chỉ Administrator mới có quyền sửa
			if ($sf_guard_user->getIsSuperAdmin () == 1 || $sf_guard_user->getIsGlobalSuperAdmin () == 1) {
				//$boolean = (myUser::getUser ()->getUsername () == myUser::fakeGlobalAdministratorName ());
				//echo "AAAAAAAA".$boolean;die;
			}
		}
		return $boolean;
	}
	
	public function executeCreatedAccount(sfWebRequest $request) {
	    
	    $user_id = myUser::getUserId ();
	    
	    $this->formFilter = new sfFormFilter ();
	    
	    $this->psMember = array ();
	    $this->ps_user = $this->ps_password = $this->is_type = '';
	    $this->array_error = array();
	    $scientific_filter = $request->getParameter ( 'scientific_filter' );
	    
	    if ($request->isMethod ( 'post' )) {
	        
	        // Handle the form submission
	        $value_student_filter = $scientific_filter;
	        
	        $this->ps_user = $value_student_filter ['ps_user'];
	        
	        $this->ps_password = $value_student_filter ['ps_password'];
	        
	        $this->is_type = $value_student_filter ['is_type'];
	        
	        if($this->ps_user == 0){ // Tao tai khoan giao vien
				/*
			   // Lay tat ca nhan su chua co tai khoan và co email
	            if($this->is_type == 0){ // Chọn tạo tài khoản là email
	                $this->psMember = Doctrine::getTable ( 'PsMember' )->getPsMemberNotAccount('email');
	            }elseif($this->is_type == 1){
	                $this->psMember = Doctrine::getTable ( 'PsMember' )->getPsMemberNotAccount('member_code');
	            }else{ // Chọn tạo tài khoản là sđt
	                $this->psMember = Doctrine::getTable ( 'PsMember' )->getPsMemberNotAccount('phone');
	            }
	            foreach ($this->psMember as $psMember){
	                $is_check = 1;
	                if($this->is_type == 0){
	                    $email = $psMember->getEmail();
	                    $cat_chuoi = strstr( $email, '@'); // Cắt chuỗi từ ký tự @
	                    $username = trim(str_replace( $cat_chuoi, '',$email ));
	                }elseif($this->is_type == 1){
	                    $username = trim($psMember->getMemberCode());
	                }elseif($this->is_type == 2){
	                    $phone = $psMember->getPhone();
	                    $username = trim(str_replace( $phone, ' ', '' ));
	                }else{
	                    $username = '';
	                }
	                if($username != ''){
    	                // Kiem tra xem da co tai khoan hay chua
    	                $checkUsername = Doctrine::getTable ( 'sfGuardUser' )->checkUsernameIsset($username);
    	                // Nếu đã tồn tại tài khoản thì chuyển vào mảng
    	                if($checkUsername){
    	                    $is_check = 0;
    	                    array_push($this->array_error, $psMember);
    	                }
    	                if($is_check == 1){
    	                    
        	                $sfGuardUser = new sfGuardUser ();
        	                
        	                $sfGuardUser -> setPsWorkplaceId($psMember->getPsWorkplaceId());
        	                
        	                $sfGuardUser -> setFirstName($psMember->getFirstName());
        	                
        	                $sfGuardUser -> setLastName($psMember->getLastName());
        	                
        	                $sfGuardUser -> setEmailAddress($psMember->getEmail());
        	                
        	                $sfGuardUser -> setUsername($username);
        	                
        	                $sfGuardUser -> setAlgorithm('sha1');
        	                
        	                $sfGuardUser -> setSalt(null);
        	                
        	                if($this->ps_password !=''){
        	                    $sfGuardUser -> setPassword(trim(str_replace(' ', '',$this->ps_password )));
        	                }else{
        	                    $sfGuardUser -> setPassword($username);
        	                }
        	                
        	                $sfGuardUser -> setIsActive(PreSchool::ACTIVE);
        	                
        	                $sfGuardUser -> setIsSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setIsGlobalSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setMemberId($psMember->getId());
        	                
        	                $sfGuardUser -> setSDomain(null);
        	                
        	                $sfGuardUser -> setUserType(PreSchool::USER_TYPE_TEACHER);
        	                
        	                $sfGuardUser -> setUserUpdatedId($user_id);
        	                
        	                $sfGuardUser -> setUserCreatedId($user_id);
        	                
        	                $sfGuardUser -> save();
        	                
    	                }
	                }
	            }
				*/
	        }else{ // Tao tai khoan sinh vien
	            
				// Chọn tài khoản là số điện thoại - Lấy người dám hộ chính
				$this->listRelative = Doctrine::getTable('Relative')->layDanhSachNguoiThan(null,false);
				
				//echo 'AAAAAAA'.count($listRelative);
				
				foreach ($this->listRelative as $relative){
					$is_check = 1;
					$mobile = $relative->getMobile();
					
					$username = trim(str_replace(' ', '',$mobile ));
					//echo ''.$username.'<br>';
					if($username != ''){
						
						// Kiem tra xem da co tai khoan hay chua
						$checkUsername = Doctrine::getTable ( 'sfGuardUser' )->checkUsernameExits($username);
    	                
						// Nếu đã tồn tại tài khoản thì chuyển vào mảng
    	                if($checkUsername){
							
    	                    $is_check = 0;
    	                    array_push($this->array_error, $relative);
    	                }
    	                if($is_check == 1){
    	                    
        	                $sfGuardUser = new sfGuardUser();
        	                
							$sfGuardUser -> setPsCustomerId($relative->getPsCustomerId());
							
        	                //$sfGuardUser -> setPsWorkplaceId($relative->getPsWorkplaceId());
        	                
        	                $sfGuardUser -> setFirstName($relative->getFirstName());
        	                
        	                $sfGuardUser -> setLastName($relative->getLastName());
        	                
        	                $sfGuardUser -> setEmailAddress($relative->getEmail());
        	                
        	                $sfGuardUser -> setUsername($username);
        	                
        	                $sfGuardUser -> setAlgorithm('sha1');
        	                
        	                $sfGuardUser -> setSalt(null);
        	                
        	                if($this->ps_password !=''){
        	                    $sfGuardUser -> setPassword(trim(str_replace(' ', '',$this->ps_password )));
        	                }else{
        	                    $sfGuardUser -> setPassword($username);
        	                }
        	                
        	                $sfGuardUser -> setIsActive(PreSchool::ACTIVE);
        	                
        	                $sfGuardUser -> setIsSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setIsGlobalSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setMemberId($relative->getId());
        	                
        	                $sfGuardUser -> setUserType(PreSchool::USER_TYPE_RELATIVE);
        	                
        	                $sfGuardUser -> setUserUpdatedId($user_id);
        	                
        	                $sfGuardUser -> setUserCreatedId($user_id);
        	                
        	                $sfGuardUser -> save();
        	                
    	                }
					}
					
				}
				
				
				/*
				// Lay tat ca sinh vien chua co tai khoan
	            if($this->is_type == 0){ // Chọn tạo tài khoản là email
	                $this->psStudent = Doctrine::getTable ( 'PsStudent' )->getPsStudentNotAccount('email');
	            }elseif($this->is_type == 2){
	                $this->psStudent = Doctrine::getTable ( 'PsStudent' )->getPsStudentNotAccount('phone');
	            }else{ // Chọn tạo tài khoản là sđt
	                $this->psStudent = Doctrine::getTable ( 'PsStudent' )->getPsStudentNotAccount('student_code');
					//echo count($this->psStudent);die;
	            }
	            foreach ($this->psStudent as $psStudent){
	                $is_check = 1;
	                if($this->is_type == 0){
	                    $email = $psStudent->getEmail();
	                    $cat_chuoi = strstr( $email, '@'); // Cắt chuỗi từ ký tự @
	                    $username = trim(str_replace( $cat_chuoi, '',$email ));
	                }elseif($this->is_type == 2){
	                    $phone = $psStudent->getPhone();
	                    $username = trim(str_replace( $phone, ' ', '' ));
	                }elseif($this->is_type == 1){
	                    $username = trim($psStudent->getStudentCode());
	                }else{
	                    $username = '';
	                }
	                if($username != ''){
    	                // Kiem tra xem da co tai khoan hay chua
    	                $checkUsername = Doctrine::getTable ( 'sfGuardUser' )->checkUsernameIsset($username);
    	                // Nếu đã tồn tại tài khoản thì chuyển vào mảng
    	                if($checkUsername){
    	                    $is_check = 0;
    	                    array_push($this->array_error, $psStudent);
    	                }
    	                if($is_check == 1){
    	                    
        	                $sfGuardUser = new sfGuardUser ();
        	                
        	                $sfGuardUser -> setPsWorkplaceId($psStudent->getPsWorkplaceId());
        	                
        	                $sfGuardUser -> setFirstName($psStudent->getFirstName());
        	                
        	                $sfGuardUser -> setLastName($psStudent->getLastName());
        	                
        	                $sfGuardUser -> setEmailAddress($psStudent->getEmail());
        	                
        	                $sfGuardUser -> setUsername($username);
        	                
        	                $sfGuardUser -> setAlgorithm('sha1');
        	                
        	                $sfGuardUser -> setSalt(null);
        	                
        	                if($this->ps_password !=''){
        	                    $sfGuardUser -> setPassword(trim(str_replace(' ', '',$this->ps_password )));
        	                }else{
        	                    $sfGuardUser -> setPassword($username);
        	                }
        	                
        	                $sfGuardUser -> setIsActive(PreSchool::ACTIVE);
        	                
        	                $sfGuardUser -> setIsSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setIsGlobalSuperAdmin(PreSchool::NOT_ACTIVE);
        	                
        	                $sfGuardUser -> setMemberId($psStudent->getId());
        	                
        	                $sfGuardUser -> setSDomain(null);
        	                
        	                $sfGuardUser -> setUserType(PreSchool::USER_TYPE_STUDENT);
        	                
        	                $sfGuardUser -> setUserUpdatedId($user_id);
        	                
        	                $sfGuardUser -> setUserCreatedId($user_id);
        	                
        	                $sfGuardUser -> save();
        	                
    	                }
	                }
	            }
				*/
	        }
	    }
	    
	    if ($scientific_filter) {
	        
	        $this->ps_user = isset ( $scientific_filter ['ps_user'] ) ? $scientific_filter ['ps_user'] : 0;
	        
	        $this->ps_password = isset ( $scientific_filter ['ps_password'] ) ? $scientific_filter ['ps_password'] : '';
	        
	    }
	    
	    $this->formFilter->setWidget ('is_type', new sfWidgetFormSelect(array (
	        'choices' => array (
	            '1' => 'Created user by phone', // Tạo tài khoản theo email
	        )
	    ), array (
	        'class' => 'form-control'
	    )));
	    
	    $this->formFilter->setValidator ( 'is_type', new sfValidatorChoice ( array (
	        'required' => true,
	        'choices' => array(1)
	    ) ) );
	    
	    $this->formFilter->setWidget ('ps_user', new sfWidgetFormSelect(array (
	        'choices' => array (
	            '1' => 'Created user relative', // Tạo tài khoản phụ huynh
	        )
	    ), array (
	        'class' => 'form-control'
	    )));
	    
	    $this->formFilter->setValidator ( 'ps_user', new sfValidatorChoice ( array (
	        'required' => true,
	        'choices' => array(1)
	    ) ) );
	    
	    $this->formFilter->setWidget ('ps_password', new sfWidgetFormInput(array(),array (
	        'class' => 'form-control',
	        'placeholder' => sfContext::getInstance ()->getI18n ()
	        ->__ ('Enter password')
	    )));
	    
	    $this->formFilter->setValidator ( 'ps_password', new sfValidatorString( array () ) );
	    
	    $this->formFilter->setDefault ( 'is_type', $this->is_type );
	    $this->formFilter->setDefault ( 'ps_user', $this->ps_user );
	    $this->formFilter->setDefault ( 'ps_password', $this->ps_password );
	    
	    $this->formFilter->getWidgetSchema ()->setNameFormat ( 'scientific_filter[%s]' );
	    
	}

	
}