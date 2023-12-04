<?php

/**
 * PsUserDepartmentsForm form.
 *
 * @package Preschool
 * @subpackage form
 * @author Your name here
 * @version SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsUserDepartmentsForm extends PluginsfGuardUserForm {
	
	public function configure() {
		
		$this->disableLocalCSRFProtection();
		
		/*
		 * $this->widgetSchema ['user_type']->setDefault ( PreSchool::USER_TYPE_TEACHER );
		 * $this->getObject ()->setUserType ( PreSchool::USER_TYPE_TEACHER );
		 *
		 * /*
		 * $ps_customer_root = Doctrine::getTable ( 'PsCustomer' )->getCustomerRootById ( $this->getObject ()->getPsCustomerId () );
		 *
		 * $this->getObject ()->setPsCustomerId ( $ps_customer_root->getId () );
		 *
		 * $member_id = $this->getObject ()->getMemberId();
		 * $query_member = Doctrine::getTable ( 'PsMember' )->setSQLMemberCustomerRootForUser ($ps_customer_root->getId (),$member_id);
		 * $this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsMember',
		 * 'query' => $query_member,
		 * 'column' => 'id',
		 * 'required' => true
		 * ) );
		 */
		
		/*
		 * if (myUser::isAdministrator() || myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_PROVINCIAL) { // Neu la cap So thi lay nhan su thuoc So hoac Phong cua so
		 *
		 * $ps_province_id = myUser::getUser ()->getPsMember ()->getPsProvinceId ();
		 *
		 * $ps_province_id = 1;
		 *
		 * $choices_member_id = Doctrine::getTable ( 'PsMember' )->getGroupMemberByProvince ($ps_province_id);
		 *
		 * $this->widgetSchema ['member_id'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array ('' => _ ( '-Select member-' )) + $choices_member_id
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select member-' )
		 * ) );
		 *
		 * $this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsMember',
		 * 'query' => Doctrine::getTable ( 'PsMember' )->setSQLMemberByProvince ($ps_province_id),
		 * 'column' => 'id',
		 * 'required' => true
		 * ) );
		 *
		 * } else {
		 *
		 * $query = Doctrine::getTable ( 'PsMember' )->setSQLMemberCustomerRootForUser ( $this->getObject ()->getPsCustomerId (), $this->getObject ()->getMemberId () );
		 *
		 * $this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsMember',
		 * 'query' => $query,
		 * 'add_empty' => true
		 * ) );
		 *
		 * $this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsMember',
		 * 'query' => $query,
		 * 'column' => 'id',
		 * 'required' => true
		 * ) );
		 * }
		 */
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['user_type'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['first_name'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['last_name'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['first_name'] = new sfValidatorString ( array (
				'required' => false 
		) );
		
		$this->validatorSchema ['last_name'] = new sfValidatorString ( array (
				'required' => false 
		) );
		$this->widgetSchema ['member_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['member'] = new sfWidgetFormInputText ( array (), array (
				'style' => 'border:none;' 
		) );
		
		echo 'PS ID'.$this->getValue('ps_customer_id');
		
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
			'choices' => array ($this->getObject ()->getPsCustomerId ()),
			'required' => true 
		) );
		
		$this->validatorSchema ['user_type'] = new sfValidatorChoice ( array (
				'choices' => array (PreSchool::USER_TYPE_TEACHER),
				'required' => true 
		) );
		
		$this->validatorSchema ['member_id'] = new sfValidatorChoice ( array (
				'choices' => array ($this->getObject ()->getMemberId ()),
				'required' => true 
		) );
		
		$this->validatorSchema ['member'] = new sfValidatorString ( array (
				'required' => true 
		) );
		
		$this->widgetSchema ['is_active'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsUserActivated () 
		), array (
				'class' => 'radiobox' 
		) );
		
		$this->validatorSchema ['is_active'] = new sfValidatorChoice ( array (
				'required' => true,
				'choices' => array_keys ( PreSchool::$ps_boolean ) 
		) );
		
		$list_manager_type = PreSchool::loadPsManagerType ();
		unset ( $list_manager_type [PreSchool::MANAGER_TYPE_GLOBAL] );
		
		$this->widgetSchema ['manager_type'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Choose management level-' 
				) + $list_manager_type 
		), array (
				'class' => 'form-control' 
		) );
		
		$this->validatorSchema ['manager_type'] = new sfValidatorChoice ( array (
				'required' => true,
				'choices' => array_keys ($list_manager_type) 
		) );
		
		$this->widgetSchema ['password']->setOption ( 'type', 'password' );
		
		$this->widgetSchema ['password']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Passwords must be at least 8 characters to include digits, uppercase, lowercase.' ) 
		) );
		
		$this->addBootstrapForm ();
		
		$this->widgetSchema ['ps_customer_id']->setAttribute ( 'class', 'form-control' );
		
		if ($this->getObject ()->isNew ()) {
			$this->useFields ( array (
					'user_type',
					'ps_customer_id',
					'first_name',
					'last_name',
					'member_id',
					'member',
					'username',
					'password',
					'manager_type',
					'is_active' 
			) );
		} else {
			
			$this->useFields ( array (
					'user_type',
					'ps_customer_id',
					'first_name',
					'last_name',
					'member_id',
					'member',
					'username',
					'password',
					'manager_type',
					'is_active' 
			) );
		}
		
		$this->validatorSchema->setPostValidator ( new sfValidatorAnd ( array (
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'id' 
						) 
				) ),
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'username' 
						) 
				) ),
				
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'member_id',
								'user_type' 
						) 
				) ) 
		) ) );
		
		$this->widgetSchema->setNameFormat('ps_user_departments[%s]');
	}
	protected function removeFields() {
		unset ( $this ['created_at'], $this ['updated_at'], $this ['algorithm'], $this ['salt'], $this ['last_login'] );
		unset ( $this ['is_super_admin'], $this ['is_global_super_admin'] );
	}
	public function updateObject($values = null) {
		if ($this->getObject ()->getPassword () === $this->getValue ( 'password' )) {
			unset ( $this ['password'] );
		}
		
		unset ( $this ['member'] );
		
		$object = parent::updateObject ( $values );
		
		$ps_users_info = Doctrine::getTable ( 'PsMember' )->findOneBy ( 'id', $object->member_id );
		
		if ($ps_users_info) {
			$object->setFirstName ( $ps_users_info->getFirstName () );
			$object->setLastName ( $ps_users_info->getLastName () );
			$object->setEmailAddress ( $ps_users_info->getEmail () );
		}
		
		$userId = myUser::getUserId ();
		
		$currentDateTime = new PsDateTime ();
		
		if ($this->getObject ()->isNew ()) {
			
			$object->setUserCreatedId ( $userId );
			
			$object->setUserUpdatedId ( $userId );
			
			$userKey = PsEndCode::psHash256 ( $object->id );
			
			$object->setUserKey ( $userKey );
		
		} else {
			
			$object->setUserUpdatedId ( $userId );
			
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );
			
			$userKey = PsEndCode::psHash256 ( $object->id );
			
			$object->setUserKey ( $userKey );
		}
		
		return $object;
	}
}
