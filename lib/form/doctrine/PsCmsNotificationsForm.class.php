<?php
/**
 * PsCmsNotifications form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsNotificationsForm extends BasePsCmsNotificationsForm {

	public function configure() {

		$this->disableLocalCSRFProtection ();

		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => 150,
				'class' => 'input_text form-control' ) );

		$this->validatorSchema ['title'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'maxlength' => 500,
				'class' => 'form-control','rows' => 10 ) );

		$this->validatorSchema ['description'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['is_system'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'is_system radiobox' ) );

		// neu toan he thong thi toan truong bi an di
		if ($this->getObject ()->getIsSystem () == 1) {
			$this->widgetSchema ['is_all'] = new psWidgetFormSelectRadio ( array (
					'choices' => PreSchool::loadPsBoolean () ), array (
					'class' => 'is_all radiobox',
					'disabled' => 'disabled' ) );
		} else {
			$this->widgetSchema ['is_all'] = new psWidgetFormSelectRadio ( array (
					'choices' => PreSchool::loadPsBoolean () ), array (
					'class' => 'is_all radiobox' ) );
		}

		$text_object_received = $this->getObject ()
			->getTextObjectReceived ();

		if ($text_object_received) {
			$list_received = explode ( ',', $text_object_received );

			$this->setDefault ( 'list_received_relative', $list_received );
			$this->setDefault ( 'list_received_teacher', $list_received );
		}

		$ps_customer_id = myUser::getUser ()->getPsCustomerID ();

		$ps_member_id = myUser::getUser ()->getMemberId ();

		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_SYSTEM' )) {
			$ps_workplace_id = null;
		} else {
			$ps_workplace_id = myUser::getWorkPlaceId ( $ps_member_id );
		}

		// Danh sach giao vien nhan theo cơ sở
		$this->widgetSchema ['list_received_teacher'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'sfGuardUser',
				'query' => Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( PreSchool::USER_TYPE_TEACHER, $ps_customer_id, myUser::getUserId (), null, $ps_workplace_id ),
				'multiple' => 'multiple',
				'expanded' => true ), array (
				'class' => '_checkbox' ) );

		$this->validatorSchema ['list_received_teacher'] = new sfValidatorDoctrineChoice ( array (
				'multiple' => 'multiple',
				'model' => 'sfGuardUser',
				'required' => false ) );

		// Can xu ly lai, neu khong co quyen GUI TAT CA THI chi gui cho phu huynh cua lop ma giao vien duoc phan cong
		$list_class = $list_service = array ();

		// Lay danh sach nguoi than
		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_SYSTEM' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ADD' )) {

			$list_class = $list_service = array ();

			$this->widgetSchema ['list_received_relative'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'sfGuardUser',
					'query' => Doctrine::getTable ( 'sfGuardUser' )->getUsersRelativeSentNotification ( $ps_customer_id, myUser::getUserId (), $list_class, $list_service, true, $ps_workplace_id ),
					'multiple' => 'multiple',
					'expanded' => true ), array (
					'class' => '_checkbox' ) );

			$this->validatorSchema ['list_received_relative'] = new sfValidatorDoctrineChoice ( array (
					'multiple' => 'multiple',
					'model' => 'sfGuardUser',
					'required' => false ) );
		} else {

			$list_class = $list_service = array ();

			$my_class = Doctrine::getTable ( 'MyClass' )->getClassByPsMember ( $ps_customer_id, $ps_member_id );

			foreach ( $my_class as $class ) {
				array_push ( $list_class, $class->getId () );
			}

			// Lay danh sach dich vu hoc ma giao vien duoc phan cong day
			$services = Doctrine::getTable ( 'Service' )->getServicesByPsMember ( $ps_customer_id, $ps_member_id );

			foreach ( $services as $service ) {
				array_push ( $list_service, $service->getId () );
			}

			$this->widgetSchema ['list_received_relative'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'sfGuardUser',
					'query' => Doctrine::getTable ( 'sfGuardUser' )->getUsersRelativeSentNotification ( $ps_customer_id, myUser::getUserId (), $list_class, $list_service, false, $ps_workplace_id ),
					'multiple' => 'multiple',
					'expanded' => true ), array (
					'class' => '_checkbox' ) );

			$this->validatorSchema ['list_received_relative'] = new sfValidatorDoctrineChoice ( array (
					'multiple' => 'multiple',
					'model' => 'sfGuardUser',
					'required' => false ) );
		}

		$this->widgetSchema ['is_basic'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'is_system radiobox' ) );

		// $this->widgetSchema ['is_basic'] = new psWidgetFormInputCheckbox();

		$this->validatorSchema ['is_basic'] = new sfValidatorPass ( array (
				'required' => false ) );

		// $this->widgetSchema ['is_system'] = new psWidgetFormInputCheckbox();

		// $this->validatorSchema ['is_system'] = new sfValidatorPass( array ('required' => false ) );

		$this->showUseFields ();

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		$object = parent::updateObject ( $values );

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();

		if ($this->getObject ()
			->isNew ()) {
			$object->setUserCreatedId ( $userId );
		} else {
			$currentDateTime = new PsDateTime ();
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );
		}

		return $object;
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );

		$text_received_teacher = $values ['list_received_teacher'] ? implode ( ',', $values ['list_received_teacher'] ) : '';

		$values ['text_object_received'] = $values ['list_received_relative'] ? implode ( ',', $values ['list_received_relative'] ) : $text_received_teacher;

		$values ['text_object_received'] = ($values ['list_received_relative'] && $values ['list_received_teacher']) ? $values ['text_object_received'] . ',' . $text_received_teacher : $values ['text_object_received'];

		$currentDateTime = new PsDateTime ();
		$values ['total_object_received'] = count ( $values ['list_received_relative'] ) + count ( $values ['list_received_teacher'] );
		$values ['date_at'] = $currentDateTime->getCurrentDateTime ();

		return $values;
	}

	protected function showUseFields() {

		if (myUser::isAdministrator ()) {
			$this->useFields ( array (
					'title',
					'list_received_teacher',
					'description',
					'list_received_relative',
					'is_all',
					'is_basic',
					'is_system' ) );
		} else {
			$this->useFields ( array (
					'title',
					'description',
					'list_received_relative',
					'list_received_teacher',
					'is_all',
					'is_basic',
					'is_system' ) );
		}
	}
}
class PsCmsNotificationForm extends BasePsCmsNotificationsForm {

	public function configure() {

		$this->disableLocalCSRFProtection ();

		if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL', 'PS_CMS_NOTIFICATIONS_ALL' )) {
			// unset ($this ['ps_customer_id']);
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$is_object = $this->getDefault ( 'is_object' );

		$is_all = $this->getDefault ( 'is_all' );
		
		$ps_school_year_id = $this->getDefault ( 'ps_school_year_id' );
		
		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$ps_member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $ps_member_id );
			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		}

		if ($is_object == '') {
			$this->setDefault ( 'is_object', 0 );
		}

		if ($is_all == '') {
			$this->setDefault ( 'is_all', 0 );
		}

		if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' )) { // neu khong co quyen gui toan truong

				$ps_member_id = myUser::getUser ()->getMemberId ();

				$workplace_id = $ps_workplace_id = myUser::getWorkPlaceId ( $ps_member_id );

				$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormInputHidden ();

				$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
						'required' => false ) );

				$this->setDefault ( 'ps_workplace_id', $workplace_id );
			} else {

				$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsWorkPlaces',
						'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
						'add_empty' => _ ( '-Select workplace-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;width:100%;",
						'required' => false,
						'data-placeholder' => _ ( '-Select workplace-' ) ) );

				$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'PsWorkPlaces',
						'required' => false ) );
			}
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-All school-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => false ) );

			if ($ps_customer_id > 0) {

				$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsWorkPlaces',
						'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
						'add_empty' => _ ( '-Select workplace-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;width:100%;",
						'required' => false,
						'data-placeholder' => _ ( '-Select workplace-' ) ) );

				$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'PsWorkPlaces',
						'required' => false ) );
			} else {
				$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select workplace-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'required' => true,
						'data-placeholder' => _ ( '-Select workplace-' ) ) );

				$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ();
			}
		}

		if ($ps_school_year_id == '') {
			$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorInteger ( array (
				'required' => false ) );

		$this->setDefault ( 'ps_school_year_id', $ps_school_year_id );

		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => 150,
				'class' => 'input_text form-control' ) );

		$this->validatorSchema ['title'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['description'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'form-control' ) );
		
		$this->validatorSchema ['description'] = new sfValidatorString ( array (
				'required' => true ) );
		/*
		$this->widgetSchema ['description']->setAttributes ( array (
				'maxlength' => 5000,
				'class' => 'input_textarea form-control',
				'rows' => 10 ) );

		$this->validatorSchema ['description'] = new sfValidatorString ( array (
				'required' => true ) );
*/
		$this->widgetSchema ['is_system'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						0 => _ ( '- Is system -' ),
						1 => _ ( 'All system' ),
						2 => _ ( 'All teacher' ),
						3 => _ ( 'All relative' ) ) ) );
		$this->widgetSchema ['is_system']->setAttributes ( array (
				'class' => 'form-control',
				'required' => false ) );
		$this->validatorSchema ['is_system'] = new sfValidatorChoice ( array (
				'choices' => array (
						0,
						1,
						2,
						3 ),
				'required' => false ) );

		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_SYSTEM' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' )) {
			$this->widgetSchema ['is_all'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							0 => _ ( '- Is all -' ),
							1 => _ ( 'Is school' ),
							2 => _ ( 'Is workplace' ) ) ) );
			$this->widgetSchema ['is_all']->setAttributes ( array (
					'class' => 'form-control',
					'required' => false ) );
			$this->validatorSchema ['is_all'] = new sfValidatorChoice ( array (
					'choices' => array (
							0,
							1,
							2 ),
					'required' => false ) );
		} elseif (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_WORKPLACE' )) {

			$this->widgetSchema ['is_all'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							0 => _ ( '- Is all -' ),
							2 => _ ( 'is workplace' ) ) ) );

			$this->widgetSchema ['is_all']->setAttributes ( array (
					'class' => 'form-control',
					'required' => false ) );
			$this->validatorSchema ['is_all'] = new sfValidatorChoice ( array (
					'choices' => array (
							0,
							2 ),
					'required' => false ) );
		}

		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_WORKPLACE' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' )) {

			$this->widgetSchema ['is_object'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							0 => _ ( '- Is object -' ),
							1 => _ ( 'Is teacher' ),
							2 => _ ( 'Is relative' ) ) ) );

			$this->widgetSchema ['is_object']->setAttributes ( array (
					'class' => 'form-control',
					'required' => false ) );

			$this->validatorSchema ['is_object'] = new sfValidatorChoice ( array (
					'choices' => array (
							0,
							1,
							2 ),
					'required' => false ) );
		}

		//$this->showUseFields ();

		$this->addBootstrapForm ();
	}

	// protected function removeFields() {
	// unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
	// unset ( $this ['private_key'], $this ['is_status'],$this ['date_at'], $this ['total_object_received'], $this ['text_object_received'] );
	// }
	public function updateObject($values = null) {

		$object = parent::updateObject ( $values );

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();

		if ($this->getObject ()
			->isNew ()) {
			$object->setUserCreatedId ( $userId );
		} else {
			$currentDateTime = new PsDateTime ();
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );
		}

		return $object;
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );
		$currentDateTime = new PsDateTime ();
		$values ['date_at'] = $currentDateTime->getCurrentDateTime ();

		return $values;
	}

	protected function showUseFields() {

		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_WORKPLACE' ) || myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' )) {
			$this->useFields ( array (
					
					'ps_customer_id',
					'ps_workplace_id',
					'title',
					'description',
					'is_system',
					'is_all',
					'is_object' ) );
		} else {
			$this->useFields ( array (
					'ps_customer_id',
					'title',
					'description' ) );
		}
	}
}
