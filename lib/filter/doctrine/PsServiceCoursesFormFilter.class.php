<?php

/**
 * PsServiceCourses filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceCoursesFormFilter extends BasePsServiceCoursesFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' );
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'form-control',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_service_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $ps_customer_id, PreSchool::ACTIVE, $ps_workplace_id, $school_year_id ),
					'add_empty' => '-Select subjects-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) );
			$this->widgetSchema ['ps_member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMember' )->setSQLMembers ( $ps_customer_id ),
					'add_empty' => '-Select member-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select member-' ) ) );
		} else {

			$this->widgetSchema ['ps_service_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) );
			$this->widgetSchema ['ps_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select member-' ) ) );
		}
		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->validatorSchema ['ps_member_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsMember',
				'required' => false ) );

		$this->validatorSchema ['ps_service_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Service',
				'required' => false ) );

		$this->showUseFields ();
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.title) LIKE ?', array (
					$keywords ) );
		}

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( 's.ps_workplace_id IS NULL OR s.ps_workplace_id = ?', $value );

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( 's.ps_school_year_id IS NULL OR s.ps_school_year_id = ?', $value );

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( 's.ps_customer_id = ?', $value );
		$query->andWhere ( 'm.ps_customer_id = ?', $value );

		return $query;
	}

	protected function showUseFields() {

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			$this->useFields ( array (
					'school_year_id',
					'ps_customer_id',
					'ps_workplace_id',
					'ps_service_id',
					'ps_member_id',
					'keywords' ) );
		} else {

			$this->useFields ( array (
					'school_year_id',
					'ps_customer_id',
					'ps_workplace_id',
					'ps_service_id',
					'ps_member_id',
					'keywords' ) );
		}
	}
}
