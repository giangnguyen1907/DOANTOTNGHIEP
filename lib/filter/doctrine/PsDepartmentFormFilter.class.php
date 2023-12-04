<?php

/**
 * PsDepartment filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsDepartmentFormFilter extends BasePsDepartmentFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_HR_DEPARTMENT_FILTER_SCHOOL' );

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

		$this->widgetSchema ['title'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['title']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Title' ) ) );

		$this->validatorSchema ['title'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addTitleColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(TRIM(' . $a . '.title)) LIKE ? OR LOWER(TRIM(' . $a . '.description)) LIKE ?', array (
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		if ($values ['ps_workplace_id'] > 0) {

			$query->andWhere ( $a . '.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		if ($values ['ps_customer_id'] > 0) {

			$query->andWhere ( $a . '.ps_customer_id = ?', $values ['ps_customer_id'] );
		}

		if (isset ( $values ['is_activated'] )) {

			$query->andWhere ( $a . '.is_activated = ?', $values ['is_activated'] );
		}

		return $query;
	}
}
