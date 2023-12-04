<?php
/**
 * FeatureOption form.
 *
 * @package    backend
 * @subpackage form
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureOptionForm extends BaseFeatureOptionForm {

	public function configure() {

		$this->addPsCustomerFormEdit ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' )) {
			$this->widgetSchema ['is_global'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean() ), array (
						'class' => 'radiobox' ) );
		}else{
			unset($this['is_global']);
		}
		
		$this->widgetSchema ['name']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255 ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255 ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema ['feature_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $ps_customer_id ),
					'add_empty' => _ ( '-Select feature-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select feature-' ) ) );
			
			$this->validatorSchema ['feature_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'Feature',
					'column' => 'id' ) );
			
			$this->widgetSchema ['servicegroup_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'ServiceGroup',
					'query' => Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', $ps_customer_id ),
					'add_empty' => _ ( '-Select servicegroup-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select servicegroup-' ) ) );
			
			$this->validatorSchema ['servicegroup_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'ServiceGroup',
					'column' => 'id' ) );
			
		} else {
			
			$this->widgetSchema ['feature_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'data-placeholder' => _ ( '-Select feature-' ) ) );
			
			$this->validatorSchema ['feature_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
			
			$this->widgetSchema ['servicegroup_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select servicegroup-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'data-placeholder' => _ ( '-Select servicegroup-' ) ) );
			
			$this->validatorSchema ['servicegroup_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}
		
		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
		
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
