<?php

/**
 * sfGuardUser form.
 *
 * @package Preschool
 * @subpackage form
 * @author Your name here
 * @version SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm {

	protected $user_type;

	public function configure() {

		$model = $query = '';
		
		/*
		$this->addPsCustomerFormNotEdit ( 'PS_SYSTEM_USER_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true
		) );

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) {
			$this->getObject ()->setPsCustomerId ( myUser::getPscustomerID () );
		}
		
		$ps_customer_id = $this->getObject ()->getPsCustomerId ();
		
		*/
		
		// Lấy trường từ chọn
		$this->setPsCustomerFormHidden();
		
		$ps_customer_id = $this->getDefault ('ps_customer_id');
		
		$user_type = $this->getObject ()->getUserType ();

		$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
				//'class' => 'select2',
				'required' => true
		) );

		$this->widgetSchema ['user_type'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['user_type'] = new sfValidatorString ( array (
				'required' => false
		) );

		$this->widgetSchema ['password']->setOption ( 'type', 'password' );
		$this->widgetSchema ['password']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Passwords must be at least 8 characters to include digits, uppercase, lowercase.' )
		) );

		if ($this->getObject ()->isNew ()) {

			if ($user_type == PreSchool::USER_TYPE_TEACHER) {

				$model = $this->getRelatedModelName ( 'PsMember' );
				$query = Doctrine::getTable ( 'PsMember' )->setSQLMemberForUser ( $ps_customer_id, false );
				
			} elseif ($user_type == PreSchool::USER_TYPE_RELATIVE) {

				$model = $this->getRelatedModelName ( 'Relative' );

				$query = Doctrine::getTable ( 'Relative' )->setSQLRelativeForUser ( $ps_customer_id, false );
			}

			// Custom form GuardUserForm, add field member_id
			$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => $model,
					'query' => $query,
					'add_empty' => true
			) );

			$this->widgetSchema ['member_id']->setLabel ( 'Users' );
		} else {
			
		    $ps_customer_id = $this->getObject ()->getPsCustomerId ();
		    
			$this->widgetSchema ['member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$this->getObject ()->getMemberId () => $this->getObject ()->getFirstName () . ' ' . $this->getObject ()->getLastName ()
					)
			), array (
					'class' => "form-control",
					'style' => "min-width:200px;"
			) );

			$this->validatorSchema ['member_id'] = new sfValidatorInteger ( array (
					'required' => true
			) );
		}

		if ($user_type == PreSchool::USER_TYPE_TEACHER) {
			$this->widgetSchema ['member_id']->setLabel ( 'HR' );
		} elseif ($user_type == PreSchool::USER_TYPE_RELATIVE) {
			$this->widgetSchema ['member_id']->setLabel ( 'Relatives' );
		}

		$this->widgetSchema ['first_name'] = new sfWidgetFormInputHidden ();

		$this->widgetSchema ['last_name'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['first_name'] = new sfValidatorString ( array (
				'required' => false
		) );

		$this->validatorSchema ['last_name'] = new sfValidatorString ( array (
				'required' => false
		) );

		$this->validatorSchema ['member_id'] = new sfValidatorInteger ( array (
				'required' => true
		) );

		$this->widgetSchema ['is_super_admin'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean ()
		), array (
				'class' => 'radiobox'
		) );

		$this->widgetSchema ['is_global_super_admin'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean ()
		), array (
				'class' => 'radiobox'
		) );

		$this->widgetSchema ['is_active'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsUserActivated ()
		), array (
				'class' => 'radiobox'
		) );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )){
			$this->useFields ( array (
					// 'ps_province_id',
					// 'ps_district_id',
					// 'ps_ward_id',
					'user_type',
					// 'user_type_text',
					'ps_customer_id',
					'member_id',
					'first_name',
					'last_name',
					// 'email_address',
					'username',
					'password',
					'is_super_admin',
					'is_global_super_admin',
					'is_active',
					'groups_list',
					'permissions_list'
			) );
		}else{
			$this->useFields ( array (
					// 'ps_province_id',
					// 'ps_district_id',
					// 'ps_ward_id',
					'ps_customer_id',
					'user_type',
					// 'user_type_text',
					'member_id',
					'first_name',
					'last_name',
					// 'email_address',
					'username',
					'password',
					'is_active',
					'groups_list',
					'permissions_list'
			) );
		}
		$this->removeFields ();

		if ($user_type == PreSchool::USER_TYPE_TEACHER) {

			$this->addGroupExpandedForm ( 'groups_list', $ps_customer_id );
			// $this->widgetSchema ['groups_list']->setAttribute ( 'id', 'groups_list_id' );

			$this->addPermissionsForm ( 'permissions_list' );

			$this->widgetSchema ['permissions_list']->setLabel ( 'Permissions' );
		} else {
			$this->widgetSchema ['groups_list'] = new sfWidgetFormInputHidden ();
			$this->widgetSchema ['permissions_list'] = new sfWidgetFormInputHidden ();
			// unset ( $this ['groups_list'], $this ['permissions_list'] );
		}

		$this->addBootstrapForm ();

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

		/*
		 * $this->mergePostValidator ( new sfValidatorCallback ( array (
		 * 'callback' => array (
		 * $this,
		 * 'postValidateUniqueMemberUserTypeExits' ) ) ) );
		 */
		
		/*
		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsProvince',
				'query' => Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code ),
				'add_empty' => _ ( '-Select province-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select province-' )
		) );

		$this->widgetSchema ['ps_province_id']->setLabel ( 'Province' );

		$this->validatorSchema ['ps_province_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsProvince',
				'required' => false
		) );
		*/
		
		
		/*
		 * $ps_province_id = $this->getDefault ( 'ps_province_id' );
		 * if ($ps_province_id > 0) {
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsDistrict',
		 * 'query' => Doctrine::getTable ( 'PsDistrict' )->setSqlPsDistrictByProvinceId ( $ps_province_id),
		 * 'add_empty' => _ ( '-Select district-' ) ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select district-' ) ) );
		 * $this->validatorSchema ['ps_district_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsDistrict',
		 * 'required' => false ) );
		 * } else {
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array (
		 * '' => _ ( '-Select district-' ) ) ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select district-' ) ) );
		 * }
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array (
		 * '' => _ ( '-Select district-' ) ) + Doctrine::getTable ( 'PsDistrict' )->getGroupPsDistricts ( $country_code ) ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select district-' ) ) );
		 */
		
		/*
		$this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoiceGrouped ( array (
				'model' => 'PsDistrict',
				'query' => Doctrine::getTable ( 'PsDistrict' )->setGroupPsDistricts ( $country_code ),
				'expanded' => true,
				'multiple' => true,
				'group_by' => 'province_name',
				'renderer_options' => array (
						'template' => '<label><strong>%group%</strong></label> %options%'
				)
		) );

		$this->validatorSchema ['ps_district_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsDistrict',
				'required' => false
		) );
		
		*/

	}
	
	public function postValidateUniqueMemberUserTypeExits(sfValidatorCallback $validator, array $values) {

		$member_id = $values ['member_id'];
		$user_type = $values ['user_type'];
		$id = $values ['id'];

		if (Doctrine::getTable ( 'sfGuardUser' )->checkUniqueMemberUserTypeExits ( $member_id, $user_type, $id )) {
			$error = new sfValidatorError ( $validator, 'This user already exists.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"member_id" => $error
			) );
		}

		return $values;

	}
	
	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['algorithm'], $this ['salt'], $this ['last_login'] );

		if (! myUser::isAdministrator () || ($this->user_type == PreSchool::USER_TYPE_RELATIVE))
			unset ( $this ['is_super_admin'], $this ['is_global_super_admin'] );

	}

	public function updateObject($values = null) {

		if ($this->getObject ()->getPassword () === $this->getValue ( 'password' )) {
			unset ( $this ['password'] );
		}

		$object = parent::updateObject ( $values );

		// Update first name, last name
		$user_type = $object->user_type;

		if ($user_type == PreSchool::USER_TYPE_TEACHER) {

			$ps_users_info = Doctrine::getTable ( 'PsMember' )->findOneBy ( 'id', $object->member_id );

			if ($ps_users_info) {
				$object->setFirstName ( $ps_users_info->getFirstName () );
				$object->setLastName ( $ps_users_info->getLastName () );
				$object->setEmailAddress ( $ps_users_info->getEmail () );
			}
		} elseif ($user_type == PreSchool::USER_TYPE_RELATIVE) {

			$ps_users_info = Doctrine_Core::getTable ( 'Relative' )->findOneBy ( 'id', $object->member_id );

			if ($ps_users_info) {
				$object->setFirstName ( $ps_users_info->getFirstName () );
				$object->setLastName ( $ps_users_info->getLastName () );
				$object->setEmailAddress ( $ps_users_info->getEmail () );
			}
		}

		$userId = myUser::getUserId ();

		if ($this->getObject ()->isNew ()) {
			$object->setUserCreatedId ( $userId );
			$object->setUserUpdatedId ( $userId );
			$userKey = PsEndCode::psHash256 ( $object->id );
			$object->setUserKey ( $userKey );
		} else {
			$object->setUserUpdatedId ( $userId );
			$currentDateTime = new PsDateTime ();
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );

			$userKey = PsEndCode::psHash256 ( $object->id );
			$object->setUserKey ( $userKey );
		}

		return $object;

	}

}