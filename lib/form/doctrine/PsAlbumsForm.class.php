<?php

/**
 * PsAlbums form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumsForm extends BasePsAlbumsForm {

	protected $path;

	protected $cus_id;

	protected $yyyymm;

	protected $dd;

	protected $album_key;

	protected $title;

	public function configure() {

		$this->setDefault('is_activated', PreSchool::NOT_ACTIVE);
		
		if ($this->getObject()->isNew()) {
			
			$schoolyear_query = Doctrine::getTable('PsSchoolYear')->getPsSchoolYearsDefault();
			
			$ps_customer_id = myUser::getPscustomerID();
			$member_id = myUser::getUser()->getMemberId();
			$ps_workplace_id = myUser::getWorkPlaceId($member_id);
			$ps_service_course_id = null;
			$schoolyear_id = $schoolyear_query->fetchOne()->getId();
			$tracked_at = date('Y-m-d');
		} else {
			
			$schoolyear_query = Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears();
			
			$obj_album = $this->getObject();
			
			$ps_customer_id = $obj_album->getPsCustomerId();
			
			$ps_class_id = $obj_album->getPsClassId();
			
			if ($ps_class_id > 0) {
				
				$workplace = Doctrine::getTable('MyClass')->getCustomerInfoByClassId($ps_class_id);
				
				$ps_workplace_id = $workplace->getWpId();
				
				$schoolyear_id = $workplace->getSyId();
			} else {
				
				$ps_workplace_id = null;
				
				$schoolyear_id = null;
				
				$ps_class_id = null;
			}
			
			$ps_service_course_id = ($obj_album->getPsServiceCourseScheduleId()) ? ($obj_album->getPsServiceCourseScheduleId()) : '';
			
			if ($ps_service_course_id != '') {
				
				$obj_cource = Doctrine::getTable('PsServiceCourseSchedules')->findOneById($ps_service_course_id);
				
				$tracked_at = ($obj_cource) ? ($obj_cource->getDateAt()) : '';
			} else {
				
				$ps_service_course_id = null;
				
				$tracked_at = date('Y-m-d');
			}
		}
		
		$this->widgetSchema['school_year_id'] = new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'query' => $schoolyear_query
		), array(
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _('-Select school year-')
		));
		
		$this->validatorSchema['school_year_id'] = new sfValidatorDoctrineChoice(array(
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id'
		));
		
		$this->setDefault('school_year_id', $schoolyear_id);
		
		$this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
		$this->setDefault('ps_customer_id', $ps_customer_id);
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
					'add_empty' => false
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
					'column' => 'id'
			));
		} else {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormSelect(array(
					'choices' => array(
							'' => _('-Select workplaces-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'PsWorkplaces',
					'column' => 'id'
			));
		}
		
		$this->setDefault('ps_workplace_id', $ps_workplace_id);
		
		$param_class = array(
				'ps_school_year_id' => $schoolyear_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_myclass_id' => $ps_class_id
		);
		
		if ($ps_workplace_id > 0) {
			
			$this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'MyClass',
					'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
					'add_empty' => false
			), array(
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _('-Select class-')
			));
			
			$this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id'
			));
		} else {
			$this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select class-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select class-')
			));
			
			$this->validatorSchema['ps_class_id'] = new sfValidatorPass(array(
					'required' => false
			));
		}
		
		$this->widgetSchema['ps_class_id']->setAttribute('readonly', 'readonly');
		
		$this->widgetSchema['ps_class_id']->setAttribute('style', 'background-color:#fff;width:100%;');
		
		$ps_class_id = $this->getDefault('ps_class_id');
		
		$this->widgetSchema['ps_service_course_schedule_id'] = new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsServiceCourseSchedules',
				'query' => Doctrine::getTable('PsServiceCourseSchedules')->setPsServiceCourseSchedulesByPsServiceCourse($ps_service_course_id, $tracked_at),
				'add_empty' => _('-Select Service Course-')
		), array(
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _('-Select Service Course-')
		));
		
		$this->validatorSchema['ps_service_course_schedule_id'] = new sfValidatorDoctrineChoice(array(
				'required' => false,
				'model' => 'PsServiceCourseSchedules',
				'column' => 'id'
		));
		
		if ($this->isNew()) {
			$this->validatorSchema['album_key'] = new sfValidatorString(array(
					'required' => false
			));
		}
		
		$this->widgetSchema['album_key']->setAttribute('readonly', 'readonly');
		$this->widgetSchema['album_key']->setAttribute('placeholder', sfContext::getInstance()->getI18n()
			->__('Automatic system generated'));
		
		$this->widgetSchema['album_key'] = new sfWidgetFormInputHidden();
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
				new sfValidatorDoctrineUnique(array(
						'model' => 'PsAlbums',
						'column' => array(
								'album_key'
						)
				), array(
						'invalid' => 'Code already exist'
				))
		)));
		
		$this->widgetSchema['note'] = new sfWidgetFormTextarea(array(), array(
				'maxlength' => 300,
				'class' => 'input_textarea form-control'
		));
		
		$this->validatorSchema['note'] = new sfValidatorString(array(
				'required' => false
		));
		
		if (myUser::credentialPsCustomers('PS_CMS_ALBUMS_LOCK')) { // Neu co quyen khoa album
			
			$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
					'choices' => PreSchool::loadCmsArticlesLock()
			), array(
					'class' => 'radiobox'
			));
		} else {
			
			$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
					'choices' => PreSchool::loadCmsArticles()
			), array(
					'class' => 'radiobox'
			));
		}
		
		$this->addBootstrapForm();
		
		if (! myUser::credentialPsCustomers('PS_CMS_ALBUMS_FILTER_SCHOOL') || ! $this->isNew()) {
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control'
			));
		}
		
		unset($this['number_view'], $this['number_like'], $this['number_dislike'], $this['created_at'], $this['updated_at']);
	
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject($values);
	
	}

}
