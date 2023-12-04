<?php

/**
 * PsMemberAbsents filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberAbsentsFormFilter extends BasePsMemberAbsentsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SERVICE_SATURDAY_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
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
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$this->widgetSchema ['ps_department_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'query' => Doctrine::getTable ( 'PsDepartment' )->setDepartmentByWorkplaceId ( $ps_workplace_id, $ps_customer_id ),
				'add_empty' => _ ( '-Select department-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select department-' ) ) );

		$this->validatorSchema ['ps_department_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsDepartment',
				'column' => 'id' ) );

		$ps_department_id = $this->getDefault ( 'ps_department_id' );

		// echo $ps_department_id; die();
		if ($ps_department_id > 0) {

			$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMemberDepartments' )->setMemberDepartments ( $ps_department_id ),
					'add_empty' => _ ( '-Select member-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );

			$this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsMember',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );

			$this->validatorSchema ['member_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = 'mb';

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.member_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR (LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
