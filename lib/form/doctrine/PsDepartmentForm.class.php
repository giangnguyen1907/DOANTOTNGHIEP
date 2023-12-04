<?php

/**
 * PsDepartment form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsDepartmentForm extends BasePsDepartmentForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit('PS_HR_DEPARTMENT_FILTER_SCHOOL');
		
		// $this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array('required' => false));
		
		if ($this->getObject()->isNew()) {
			
			$ps_customer_id = myUser::getPscustomerID();
		} else {
			
			$ps_customer_id = $this->getObject()->getPsCustomerId();
		}
		
		$this->setDefault('ps_customer_id', $ps_customer_id);
		
		$ps_customer_id = $this->getDefault('ps_customer_id');
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
					'add_empty' => _('-Select basis enrollment-')
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
					'column' => 'id'
			));
		} else {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormSelect(array(
					'choices' => array(
							'' => _('-Select basis enrollment-')
					)
			), array(
					'class' => 'select2'
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorInteger(array(
					'required' => true
			));
		}
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->addBootstrapForm();
		
		if (! myUser::credentialPsCustomers ( 'PS_HR_DEPARTMENT_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
