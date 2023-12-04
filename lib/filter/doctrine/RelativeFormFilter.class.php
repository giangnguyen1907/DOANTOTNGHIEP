<?php

/**
 * Relative filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RelativeFormFilter extends BaseRelativeFormFilter {

	public function configure() {

		// $this->addPsCustomerFormFilter('PS_STUDENT_RELATIVE_FILTER_SCHOOL');
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		} else {

			$ps_customer_id = null;
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		// ps_workplace_id filter by ps_customer_id
		/*
		 * if ($ps_customer_id > 0) {
		 * $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsWorkPlaces',
		 * 'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
		 * 'add_empty' => '-Select workplaces-'
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select Ward-' )
		 * ) );
		 * $this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'required' => false,
		 * 'model' => 'PsWorkPlaces',
		 * 'column' => 'id'
		 * ) );
		 * } else {
		 * $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
		 * 'choices' => array (
		 * '' => '-All workplaces-'
		 * )
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;"
		 * ) );
		 * $this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass();
		 * }
		 */
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
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
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id );
		/*
		 * if ($ps_customer_id > 0) {
		 * $this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'MyClass',
		 * 'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
		 * 'add_empty' => _('-Select class-')
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:150px;",
		 * 'data-placeholder' => _('-Select class-')
		 * ));
		 * } else {
		 * $this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
		 * 'choices' => array(
		 * '' => _('-Select class-')
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _('-Select class-')
		 * ));
		 * }
		 * $this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'MyClass',
		 * 'column' => 'id'
		 * ));
		 */
		if ($ps_customer_id > 0) {

			$choices = Doctrine::getTable ( 'MyClass' )->getChoisGroupMyClassByCustomerAndYear ( $ps_customer_id, $school_year_id, null, PreSchool::ACTIVE );

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => '-Select class-' ) + $choices ), array (
					'class' => 'select2',
					'style' => "min-width:250px;" ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['ps_class_id']->setLabel ( 'To class' );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:250px;" ) );
			$this->validatorSchema ['ps_class_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['sex'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select sex-' ) + PreSchool::loadPsGender () ), array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['delete'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						PreSchool::NOT_ACTIVE => 'Activity',
						PreSchool::ACTIVE => 'Archives' ) ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['delete'] = new sfValidatorInteger ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->where ( 'ps_customer_id = ?', $value );

		return $query;
	}

	/*
	 * public function addPsWorkplaceIdColumnQuery($query, $field, $value)
	 * {
	 * return $query;
	 * }
	 */
	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	// Tim kiem member_code,first_name,last_name,mobile
	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		$keywords = PreString::strReplace ( $keywords );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.email) LIKE ? OR LOWER(' . $a . '.mobile) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR  LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ? OR  LOWER(acc.username) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function addDeleteColumnQuery($query, $field, $value) {

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		// $date_at = date('Ymd');

		$query->leftJoin ( $a . '.RelativeStudent rs' );

		$query->leftJoin ( 'rs.Student s WITH s.deleted_at IS NULL' );

		$query->addSelect ( 'sc.id, s.id, rs.id,wp.title as workplace_name' );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->leftJoin ( 's.StudentClass sc WITH sc.myclass_id=? AND sc.is_activated=?', array (
					$values ['ps_class_id'],
					PreSchool::ACTIVE ) );

			$query->andWhereIn ( 'sc.type', array (
					PreSchool::SC_STATUS_OFFICIAL,
					PreSchool::SC_STATUS_TEST ) );
		} else {
			$query->leftJoin ( 's.StudentClass sc WITH sc.is_activated=?', PreSchool::ACTIVE );
		}

		$query->leftJoin ( 'sc.MyClass mc' );

		$query->leftJoin ( 'mc.PsClassRooms cr' );

		$query->leftJoin ( $a . '.PsWorkPlaces wp' );

		if (isset($values ['ps_customer_id']) && $values ['ps_customer_id'] > 0) {

			$query->andWhere ( $a . '.ps_customer_id =?', $values ['ps_customer_id'] );
		}

		if(isset($values ['delete'])){
			if ($values ['delete'] == PreSchool::ACTIVE) {
				$query->andWhere ( $a . '.deleted_at IS NOT NULL' );
			} elseif ($values ['delete'] == PreSchool::NOT_ACTIVE) {
				$query->andWhere ( $a . '.deleted_at IS NULL' );
			}
		}
		// if ($values['school_year_id'] > 0) {
		// $query->leftJoin('mc.PsSchoolYear sy With mc.school_year_id = ?', $values['school_year_id']);
		// }

		// $query->groupBy($a.'.id');
		return $query;
	}
}
