<?php
/**
 * PsMenus filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMenusFormFilter extends BasePsMenusFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;',
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_object_group_id']->addOption ( 'add_empty', _ ( '-Select object group-' ) );

		$this->widgetSchema ['date_at_from'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_from']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date from' ) ) );

		$this->widgetSchema ['date_at_from']->addOption ( 'tooltip', 'From date' );

		$this->validatorSchema ['date_at_from'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['date_at_to'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_to']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date to' ) ) );

		$this->widgetSchema ['date_at_to']->addOption ( 'tooltip', 'To date' );

		$this->validatorSchema ['date_at_to'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Food title' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addDateAtFromColumnQuery(Doctrine_Query $query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at >= ?', $value );
	}

	public function addDateAtToColumnQuery(Doctrine_Query $query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at <= ?', $value );
	}

	// Tim kiem member_code,first_name,last_name,mobile
	public function addKeywordsColumnQuery($query, $field, $value) {

		// $a = $query->getRootAlias ();
		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(F.title) LIKE ? ', array (
					$keywords ) );
		}

		return $query;
	}

	public function getFields() {

		return array (
				'ps_customer_id' => myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ) ? 'ForeignKey' : 'Number',
				'ps_workplace_id' => 'ForeignKey',
				'date_at_from' => 'Date',
				'date_at_to' => 'Date',
				'ps_meal_id' => 'ForeignKey',
				'ps_food_id' => 'ForeignKey',
				'ps_object_group_id' => 'ForeignKey' );
	}
}
