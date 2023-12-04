<?php
/**
 * PsMember filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberFormFilter extends BasePsMemberFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_HR_HR_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => 'Input: Hr code, Fullname, email, mobile' ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['sex'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select sex-' ) + PreSchool::loadPsGender () ), array (
				'class' => 'form-control' ) );

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;',
					'data-placeholder' => _ ( 'Select workplace' ) ) );
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

		$this->widgetSchema ['is_status'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( '-Select status-' ) ) + PreSchool::loadHrStatus () ), array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['rank'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						0 => _ ( '-Select member rank-' ) ) + PreSchool::loadHrRank () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['rank'] = new sfValidatorInteger ( array (
				'required' => false ) );
	}

	// Add virtual_column_name for filter
	public function addRankColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$a = $query->getRootAlias ();

			$query->andWhere ( $a . '.rank = ?', $value );
		}
		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsProvinceIdColumnQuery($query, $field, $value) {

		if ($value != '') {

			$query->andWhere ( 'p.id = ?', $value );
		}

		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsDistrictIdColumnQuery($query, $field, $value) {

		if ($value > 0) {

			$query->andWhere ( 'd.id = ?', $value );
		}

		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsWardIdColumnQuery($query, $field, $value) {

		if ($value > 0) {

			$query->andWhere ( 'pw.id = ?', $value );
		}

		return $query;
	}

	// Tim kiem member_code,first_name,last_name,mobile
	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$query->addSelect ( 'em.id AS e_id' );

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(em.ps_email) LIKE ? OR LOWER(' . $a . '.member_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR LOWER(' . $a . '.mobile) LIKE ? OR LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );

			$query->leftJoin ( $a . '.PsEmails em With em.obj_type = "T"' );
		}

		return $query;
	}

	/*
	 * public function addPsWorkplaceIdColumnQuery($query, $field, $value)
	 * {
	 * $a = $query->getRootAlias();
	 * if($value > 0){
	 * $query->addWhere($a.'.ps_workplace_id = ?', $value);
	 * }
	 * return $query;
	 * }
	 */
}