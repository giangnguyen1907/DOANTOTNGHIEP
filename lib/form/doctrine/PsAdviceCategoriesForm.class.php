<?php

/**
 * PsAdviceCategories form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAdviceCategoriesForm extends BasePsAdviceCategoriesForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_STUDENT_ADVICE_CATEGORIES_FILTER_SCHOOL' );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::getStatus () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['note'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'col-md-12' ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->addBootstrapForm ();
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ADVICE_CATEGORIES_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
