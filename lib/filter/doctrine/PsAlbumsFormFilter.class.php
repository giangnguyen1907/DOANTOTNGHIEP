<?php

/**
 * PsAlbums filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumsFormFilter extends BasePsAlbumsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_CMS_ALBUMS_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		// if($ps_customer_id ==''){
		// $ps_customer_id = myUser::getPscustomerID();
		// $this->setDefault('ps_customer_id' , $ps_customer_id);
		// $member_id = myUser::getUser()->getMemberId();
		// $ps_workplace_id = myUser::getWorkPlaceId($member_id);
		// }

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

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

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

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

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

		$this->widgetSchema ['is_activated'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select state-' ) + PreSchool::loadCmsArticlesLock () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['is_activated'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array_keys ( PreSchool::$ps_is_lock ) ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInput ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'ps_class_id' ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(TRIM(' . $a . '.title)) LIKE ? OR LOWER(TRIM(' . $a . '.note)) LIKE ?', array (
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

// 		$query->leftJoin ( 'mc.PsClassRooms cr' );

// 		$query->leftJoin ( 'cr.PsWorkPlaces wp' );

		if (isset($values ['school_year_id']) && $values ['school_year_id'] > 0) {
			
			$query->andWhere ('mc.school_year_id = ?', $values ['school_year_id'] );
		}
		
		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->andWhere ( $a . '.ps_class_id = ?', $values ['ps_class_id'] );
		}

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$query->andWhere ( 'wp.id = ?', $values ['ps_workplace_id'] );
		}

		if (isset($values ['ps_customer_id']) && $values ['ps_customer_id'] > 0) {

			$query->andWhere ( $a . '.ps_customer_id = ?', $values ['ps_customer_id'] );
		}

		if (isset ( $values ['is_activated'] )) {
			$query->andWhere ( $a . '.is_activated = ?', $values ['is_activated'] );
		}

		return $query;
	}
}
