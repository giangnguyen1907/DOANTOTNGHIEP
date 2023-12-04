<?php
/**
 * PsMeals form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMealsForm extends BasePsMealsForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_NUTRITION_MEALS_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
				'class' => 'select2',
				'required' => true ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
					'add_empty' => _ ( '-Select workplace-' ) ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );
		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		if ($this->getObject ()
			->isNew ()) { // Add new

			$is_select = true;

			if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MEALS_FILTER_SCHOOL' )) {

				$is_select = false;

				$ps_customer_id = myUser::getPscustomerID ();

				$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			}
		} else {
			$is_select = false;
			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => 255 ) );
		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MEALS_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}

		$this->showUseFields ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	protected function showUseFields() {

		if (myUser::credentialPsCustomers ( 'PS_NUTRITION_MEALS_FILTER_SCHOOL' )) {
			$this->useFields ( array (
					'ps_customer_id',
					'ps_workplace_id',
					'title',
					'iorder',
					'is_activated',
					'note' ) );
		} else {
			$this->useFields ( array (
					'ps_customer_id',
					'ps_workplace_id',
					'title',
					'note',
					'iorder',
					'is_activated',
					'note' ) );
		}
	}
}
