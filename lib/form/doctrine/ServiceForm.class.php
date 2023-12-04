<?php
/**
 * Service form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceForm extends BaseServiceForm {

	/**
	 * Bookmarks scheduled for deletion
	 *
	 * @var array
	 */
	protected $scheduledForDeletion = array ();

	public function configure() {

		$this->removeFields ();
		/*
		 * $enableRoll = $this->getObject()->getEnableRoll(); $function_code = ($enableRoll == PreSchool::SERVICE_TYPE_SCHEDULE) ? 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' : 'PS_STUDENT_SERVICE_FILTER_SCHOOL';
		 */

		$enable_schedule = $this->getObject ()->getEnableSchedule ();

		$function_code = ($enable_schedule == PreSchool::ACTIVE) ? 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' : 'PS_STUDENT_SERVICE_FILTER_SCHOOL';

		//$this->addPsCustomerFormNotEdit ( $function_code );
		
		$this->setPsCustomerFormHidden();
		if ($this->getObject () ->isNew ()) {
			$ps_customer_id = $this->getDefault('ps_customer_id');
		}else{
			$ps_customer_id = $this->getObject()->getPsCustomerId();
		}
		//echo 'AAAAAA'.$ps_customer_id;
		if (myUser::credentialPsCustomers ( $function_code )) {
			/*
			if ($this->getObject ()
				->isNew ()) {
				$this->widgetSchema ['service_group_id'] = new sfWidgetFormSelect ( array (
						'choices' => array () ), array (
						'class' => 'select2' ) );
			} else {

				$query = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ();

				$this->widgetSchema ['service_group_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'ServiceGroup',
						'query' => $query,
						'add_empty' => true ) );
			}*/

			$query = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ();

				$this->widgetSchema ['service_group_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'ServiceGroup',
						'query' => $query,
						'add_empty' => true ) );

		} else {

			$query = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', myUser::getPscustomerID () );

			$this->widgetSchema ['service_group_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'ServiceGroup',
					'query' => $query,
					'add_empty' => true ) );
		}

		// Icon fof service
		$this->widgetSchema ['ps_image_id'] = new psWidgetFormSelectImage ( array (
				'choices' => Doctrine::getTable ( 'PsImages' )->setChoisPsImagesByGroup ( PreSchool::FILE_GROUP_SERVICE ) ), array (
				'class' => 'select2',
				'style' => "width:100%",
				'placeholder' => _ ( '-Select icon-' ) ) );

		$this->validatorSchema ['service_group_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'ServiceGroup',
				'column' => 'id' ) );

		$this->widgetSchema ['number_course'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['number_course'] = new sfValidatorInteger ( array (
				'required' => false ) );

		//$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );
		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
					'add_empty' => true ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$service_month = $this->getObject ()->getServiceMonth();
	    if(trim($service_month,',') != ''){
	        $arr_service_month = explode(',', trim($service_month,','));
	        $this->getObject ()->setServiceMonth($arr_service_month);
	    }
		
		$this->widgetSchema ['service_month'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsMonth (),
				'multiple' => true ,
			),array (
				'class' => 'select2',
				'required' => false,
				'placeholder' => 'Tháng 1, Tháng 2,...',
			) 
		);

		$this->validatorSchema['service_month'] = new sfValidatorChoice (
			array(
				'choices' => array_keys(PreSchool::$ps_month),
				'multiple' => true,
				'required' => false
			)
		);

		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsSchoolYear",
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$enable_roll = PreSchool::loadPsRoll ();
		// unset($enable_roll[PreSchool::SERVICE_TYPE_SCHEDULE]);
		
		/*
		$this->widgetSchema ['enable_roll'] = new sfWidgetFormSelect ( array (
				'choices' => $enable_roll ), array (
				'class' => 'select2' ) );
				*/
				
		$this->widgetSchema ['enable_roll'] = new psWidgetFormSelectRadio ( array (
				'choices' => $enable_roll ), array (
						'class' => 'radiobox' ) );
				
		if ($enable_schedule != PreSchool::ACTIVE) {				
			$this->widgetSchema ['is_kidsschool'] = new psWidgetFormSelectRadio ( array ('choices' => PreSchool::loadPsBoolean () ), array (
							'class' => 'radiobox' ) );
		} else {
			unset ( $this ['is_kidsschool']);
		}

		$this->widgetSchema ['mode'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsBranchMode () ), array (
				'class' => 'select2' ) );

		$this->widgetSchema ['is_type_fee'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsIsTypeFee () ), array (
				'class' => 'form-control' ) );
		$this->widgetSchema ['is_type_fee'] = new sfWidgetFormInputHidden();

		$this->widgetSchema ['is_default'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsServiceDefault () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['enable_saturday'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );
		
		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255,
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Note help' ) ) );
		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 2000,
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Description help' ) ) );

		if ($this->getObject ()->isNew ())
			$this->addNewFields ( 0 );

		$this->embedRelation ( 'ServiceDetail' );

		$this->addBootstrapForm ();

		// echo 'setEnableRoll:'.$this->getObject()->getEnableRoll();

		if (! myUser::credentialPsCustomers ( $function_code )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
		$this->widgetSchema ['price']->setAttributes ( array (
				'class' => 'form-control',
				'type' => 'number',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Price help' ) ) );
		$this->validatorSchema ['price'] = new sfValidatorInteger ( array (
				'required' => false ) );
		
		$service_reduce = $this->getObject ()->getServiceReduce();
	    if(trim($service_reduce,',') != ''){
	        $arr_service = explode(',', trim($service_reduce,','));
	        $this->getObject ()->setServiceReduce($arr_service);
	    }
		
		$this->widgetSchema ['service_reduce'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsReduceStatus (),
				'multiple' => true 
			),array (
				'class' => 'select2',
				'required' => false
			) 
		);

		$this->validatorSchema['service_reduce'] = new sfValidatorChoice (
			array(
				'choices' => array_keys(PreSchool::$ps_reduce_status),
				'multiple' => true,
				'required' => false
			)
		);

		/*
		$service_reduce = $this->getObject ()->getServiceReduce();
	    if(trim($service_reduce,',') != ''){
	        $arr_service = explode(',', trim($service_reduce,','));
	        $this->getObject ()->setServiceReduce($arr_service);
	    }
		
		$this->widgetSchema['service_reduce'] = new sfWidgetFormDoctrineChoice(array(
		  'model' => 'PsReduceYourself',
		  'add_empty' => false,
		  'multiple' => true,

		), array(
			'required' => false,
		  'class' => 'select2',
		  'style' => "min-width:200px;",
		  'data-placeholder' => '-Áp dụng giảm trừ-'
		));

		$this->validatorSchema['service_reduce'] = new sfValidatorDoctrineChoice(array(
		  'multiple' => true,
		  'required' => false,
		  'model' => 'PsReduceYourself',
		));
		
		$choices = PreSchool::loadPsReduceStatus ();
	    //$this->setWidget('service_reduce', new sfWidgetFormChoice(array('multiple' => true, 'choices' => $choices)));
	    $this->setValidator('service_reduce', new sfValidatorChoice(array('choices' => array_keys($choices), 'multiple' => true)));
		*/

		$this->widgetSchema ['caphoc'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadCapHoc () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => ''));
		
		$this->widgetSchema ['caphoc']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		$this->widgetSchema ['chuongtrinh'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadChuongTrinhDaoTao () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => ''));
		
		$this->widgetSchema ['chuongtrinh']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		/*
		$this->widgetSchema ['service_type'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadLoaiDichVu () ), array (
				'class' => 'radiobox' ) );
		*/

		$this->widgetSchema ['service_type'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadLoaiDichVu () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => ''));
		
		$this->widgetSchema ['service_type']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );


		$this->widgetSchema->setAttribute ( 'name', 'frm_service' );
	}

	public function addNewFields($number) {

		$new_servicedetail = new BaseForm ();

		for($i = 0; $i <= $number; $i += 1) {
			$servicedetail = new ServiceDetail ();
			$servicedetail->setService ( $this->getObject () );
			$servicedetail_form = new ServiceDetailForm ( $servicedetail );
			$new_servicedetail->embedForm ( $i, $servicedetail_form );
		}

		$this->embedForm ( 'new', $new_servicedetail );
	}

	public function loadRowSeviceDetailTemplate() {

		$new_servicedetail = new BaseForm ();
		$servicedetail = new ServiceDetail ();

		$servicedetail->setService ( $this->getObject () );
		$servicedetail_form = new ServiceDetailForm ( $servicedetail );
		$new_servicedetail->embedForm ( 'temp', $servicedetail_form );

		$this->embedForm ( 'new', $new_servicedetail );
	}

	public function bind(array $taintedValues = null, array $taintedFiles = null) {

		$new_servicedetails = new BaseForm ();

		if (isset ( $taintedValues ['new'] )) {

			foreach ( $taintedValues ['new'] as $key => $new_servicedetail ) {

				// no caption and no filename, remove the empty values
				if ($this->getObject ()->getId ()) {
					if (! $new_servicedetail ['amount'] && ! $new_servicedetail ['by_number']) {
						unset ( $taintedValues ['new'] [$key] );
					} else {
						$servicedetail = new ServiceDetail ();
						$servicedetail->setService ( $this->getObject () );
						$servicedetail_form = new ServiceDetailForm ( $servicedetail );
						// unset($servicedetail_form['user_created_id']);
						$new_servicedetails->embedForm ( $key, $servicedetail_form );
					}
				} else {
					$servicedetail = new ServiceDetail ();
					$servicedetail->setService ( $this->getObject () );
					$servicedetail_form = new ServiceDetailForm ( $servicedetail );
					$new_servicedetails->embedForm ( $key, $servicedetail_form );
				}

				// $taintedValues['new'][$key]['detail_at']['day'] = '01';
				// $taintedValues['new'][$key]['detail_end']['day'] = '01';
			}

			$this->embedForm ( 'new', $new_servicedetails );
		}

		parent::bind ( $taintedValues, $taintedFiles );
	}

	/**
	 * Here we just drop the bookmark embedded creation form if no value has been provided for it (it somewhat simulates a non-required embedded form)
	 *
	 * @see sfForm::doBind()
	 */
	protected function doBind(array $values) {

		if (isset ( $values ['ServiceDetail'] )) {
			foreach ( $values ['ServiceDetail'] as $i => $bookmarkValues ) {
				if (isset ( $bookmarkValues ['delete'] ) && $bookmarkValues ['id']) {
					$this->scheduledForDeletion [$i] = $bookmarkValues ['id'];
				}
			}
		}

		parent::doBind ( $values );
	}

	/**
	 * Updates object with provided values, dealing with evantual relation deletion
	 *
	 * @see sfFormDoctrine::doUpdateObject()
	 */
	protected function doUpdateObject($values) {

		if (count ( $this->scheduledForDeletion )) {
			foreach ( $this->scheduledForDeletion as $index => $id ) {
				unset ( $values ['ServiceDetail'] [$index] );
				unset ( $this->object ['ServiceDetail'] [$index] );
				Doctrine::getTable ( 'ServiceDetail' )->findOneById ( $id )
					->delete ();
			}
		}
		/*
		 * $userId = sfContext :: getInstance()->getUser()->getGuardUser()->getId(); if ($this->getObject()->isNew()) { $this ->getObject()->setUserCreatedId($userId); $this ->getObject()->setUserUpdatedId($userId); } else { $this ->getObject()->setUserUpdatedId($userId); }
		 */

		$this->getObject ()
			->fromArray ( $values );
	}

	public function updateObject($values = null) {

		//return parent::baseUpdateObject ( $values );
		$object = parent::baseUpdateObject($values);
		
		//print_r($this->getValue('service_reduce')); die;

		$service_reduce = $this->getValue('service_reduce') ? implode(',', $this->getValue('service_reduce')) : '';
        $object->setServiceReduce(','.$service_reduce.',');

        $service_month = $this->getValue('service_month') ? implode(',', $this->getValue('service_month')) : '';
        $object->setServiceMonth(','.$service_month.',');

        return $object;

	}

	public function removeFields() {

		unset ( $this ['user_created_id'], $this ['user_updated_id'], $this ['created_at'], $this ['updated_at'] );
	}
}

/**
 * My Service form into ServiceForm
 *
 * @package backend
 * @subpackage form
 * @author Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MyServiceForm extends BaseServiceForm {

	/**
	 * Bookmarks scheduled for deletion
	 *
	 * @var array
	 */
	protected $scheduledForDeletion = array ();

	public function configure() {

		$this->removeFields ();

		$this->addBootstrapForm ();

		$this->embedRelation ( 'ServiceSplit' );
	}

	public function addNewFieldSplit($number) {

		$new_servicesplit = new BaseForm ();

		for($i = 0; $i <= $number; $i += 1) {
			$servicesplit = new ServiceSplit ();
			$servicesplit->setService ( $this->getObject () );
			$servicesplit_form = new ServiceSplitForm ( $servicesplit );
			$new_servicesplit->embedForm ( $i, $servicesplit_form );
		}

		$this->embedForm ( 'new', $new_servicesplit );
	}

	public function bind(array $taintedValues = null, array $taintedFiles = null) {

		$new_servicesplits = new BaseForm ();
		if (isset ( $taintedValues ['new'] )) {
			foreach ( $taintedValues ['new'] as $key => $new_servicesplit ) {

				// no caption and no filename, remove the empty values
				if ($this->getObject ()
					->getId ()) {

					if (! $new_servicesplit ['count_value'] && ! $new_servicesplit ['split_value']) {
						unset ( $taintedValues ['new'] [$key] );
					} else {

						$servicesplit = new ServiceSplit ();
						$servicesplit->setService ( $this->getObject () );
						$servicesplit_form = new ServiceSplitForm ( $servicesplit );
						$new_servicesplits->embedForm ( $key, $servicesplit_form );
					}
				} else {
					$servicesplit = new ServiceSplit ();
					$servicesplit->setService ( $this->getObject () );
					$servicesplit_form = new ServiceSplitForm ( $servicesplit );
					$new_servicesplits->embedForm ( $key, $servicesplit_form );
				}
			}

			$this->embedForm ( 'new', $new_servicesplits );
		}
		parent::bind ( $taintedValues, $taintedFiles );
	}

	/**
	 * Here we just drop the bookmark embedded creation form if no value has been provided for it (it somewhat simulates a non-required embedded form)
	 *
	 * @see sfForm::doBind()
	 */
	protected function doBind(array $values) {

		if (isset ( $values ['ServiceSplit'] )) {
			foreach ( $values ['ServiceSplit'] as $i => $bookmarkValues ) {
				if (isset ( $bookmarkValues ['delete'] ) && $bookmarkValues ['id']) {
					$this->scheduledForDeletion [$i] = $bookmarkValues ['id'];
				}
			}
		}
		parent::doBind ( $values );
	}

	/**
	 * Updates object with provided values, dealing with evantual relation deletion
	 *
	 * @see sfFormDoctrine::doUpdateObject()
	 */
	protected function doUpdateObject($values) {

		if (count ( $this->scheduledForDeletion )) {
			foreach ( $this->scheduledForDeletion as $index => $id ) {
				unset ( $values ['ServiceSplit'] [$index] );
				unset ( $this->object ['ServiceSplit'] [$index] );
				Doctrine::getTable ( 'ServiceSplit' )->findOneById ( $id )
					->delete ();
			}
		}

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();

		/*
		 * if ($this->getObject()->isNew()) { $this ->getObject()->setUserCreatedId($userId); $this ->getObject()->setUserUpdatedId($userId); } else { $this ->getObject()->setUserUpdatedId($userId); } $this -> getObject() -> fromArray($values);
		 */

		// return parent :: baseUpdateObject($values);
	}

	public function removeFields() {

		unset ( $this ['user_created_id'], $this ['user_updated_id'], $this ['created_at'], $this ['updated_at'], $this ['title'], $this ['enable_roll'], $this ['iorder'], $this ['is_activated'], $this ['ps_customer_id'], $this ['ps_image_id'], $this ['service_group_id'] );

		// unset($this['created_at'], $this['updated_at'], $this['title'], $this['enable_roll'],$this['is_activated']);
	}

	// Add new row
	public function loadServiceSplitRowTemplate() {

		$new_servicesplit = new BaseForm ();

		$servicesplit = new ServiceSplit ();

		$servicesplit->setService ( $this->getObject () );

		$servicesplit_form = new ServiceSplitForm ( $servicesplit );

		$new_servicesplit->embedForm ( 'temp', $servicesplit_form );

		$this->embedForm ( 'new', $new_servicesplit );
	}
}
