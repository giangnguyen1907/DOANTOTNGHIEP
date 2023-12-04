<?php

/**
 * PsEvaluateSemester filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateSemesterFormFilter extends BasePsEvaluateSemesterFormFilter {

	public function configure() {

		unset ( $this ['student_id'] );

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false // _ ( '-Select school year-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );
		
		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		
		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['title'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['title']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Input: Student code, Fullname' ) ) );

		$this->validatorSchema ['title'] = new sfValidatorString ( array (
				'required' => false ) );

	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 's.ps_customer_id = ?', $value );

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$query->innerJoin ( 'mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		$query->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->andWhere ( 'sc.myclass_id = ?', $values ['ps_class_id'] );
		}
		if(isset($values ['title'])){
			
			$keywords = PreString::trim ( $values ['title'] );
		
			if ($keywords != '') {
	
				$keywords = '%' . PreString::strLower ( $keywords ) . '%';
	
				$query->addWhere ( 'LOWER(s.student_code) LIKE ? OR LOWER(s.first_name) LIKE ? OR LOWER(s.last_name) LIKE ? OR LOWER(es.title) LIKE ? OR LOWER( CONCAT(s.first_name," ", s.last_name) ) LIKE ?', array (
						$keywords,
						$keywords,
						$keywords,
						$keywords,
						$keywords ) );
			}
		}
		$query->orderBy ( 'es.id, s.last_name, s.first_name, mc.id' );

		return $query;
	}
}
