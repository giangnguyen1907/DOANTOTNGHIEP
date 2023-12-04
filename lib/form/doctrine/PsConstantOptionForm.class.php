<?php
/**
 * PsConstantOption form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConstantOptionForm extends BasePsConstantOptionForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL' );

		// Lay cac constant chua co constant option
		$this->widgetSchema ['ps_constant_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsConstant',
				'query' => Doctrine::getTable ( 'PsConstant' )->loadPsConstantByCustomer ( $this->getObject ()
					->getPsCustomerId (), $this->getObject ()
					->getPsConstantId () ),
				'add_empty' => true ), array (
				'class' => 'form-control' ) );

		$this->addBootstrapForm ();

		if (! $this->getObject ()
			->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );

			$this->widgetSchema ['ps_constant_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
