<?php

/**
 * PsFoods filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFoodsFormFilter extends BasePsFoodsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_NUTRITION_FOOD_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.ps_customer_id IS NULL or ' . $a . '.ps_customer_id = ?', $value );

		return $query;
	}
}

