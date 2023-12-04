<?php

/**
 * PsClassRooms form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsClassRoomsForm extends BasePsClassRoomsForm {

	public function configure() {

		if (! $this->getObject()->isNew()) {
			
			$ps_customer = $this->getObject()
				->getPsWorkPlaces()
				->getPsCustomer();
			
			$this->setDefault('ps_customer_id', $ps_customer->getId());
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
					)
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
					'choices' => array(
							$ps_customer->getId()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
		} else {
			
			if (myUser::credentialPsCustomers('PS_SYSTEM_ROOMS_FILTER_SCHOOL')) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active),
						'required' => true
				));
			} else {
				
				$ps_customer = $ps_customer = Doctrine::getTable('PsCustomer')->findOneBy('id', myUser::getPscustomerID());
				
				$this->setDefault('ps_customer_id', $ps_customer->getId());
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
						)
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
						'choices' => array(
								myUser::getPscustomerID()
						)
				));
				
				$this->widgetSchema['ps_customer_id']->setAttributes(array(
						'class' => 'form-control',
						'required' => true
				));
			}
		}
		
		$ps_customer_id = $this->getDefault('ps_customer_id');
		
		if ($ps_customer_id > 0) {
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
					'add_empty' => _('-Select workplaces-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
		} else {
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select workplaces-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
		}
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['is_global'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['title']->setAttributes(array(
				'maxlength' => 255
		));
		
		$this->widgetSchema['note']->setAttributes(array(
				'maxlength' => 255
		));
		
		$this->widgetSchema['description']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 2000
		));
		
		$this->widgetSchema['iorder']->setAttributes(array(
				'min' => 1
		));
		
		$this->addBootstrapForm();

		// $this->widgetSchema ['ps_customer_id']->setAttributes ( array ('required' => 'required') );

		// $this->addPsCustomerFormNotEdit();

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
				'required' => false ) );
	}

	public function updateObject($values = null) {

		unset ( $this ['ps_customer_id'] );

		return parent::baseUpdateObject ( $values );
	}
}
