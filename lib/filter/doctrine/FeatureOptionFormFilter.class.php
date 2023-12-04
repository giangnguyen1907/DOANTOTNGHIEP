<?php

/**
 * FeatureOption filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureOptionFormFilter extends BaseFeatureOptionFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' );
		
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema ['feature_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $ps_customer_id ),
					'add_empty' => '-Select feature-' ) , 
					array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select feature-' ) )
					);
			
			$this->validatorSchema ['feature_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'Feature',
					'column' => 'id' ) );
			
			$this->widgetSchema ['servicegroup_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'ServiceGroup',
					'query' => Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select servicegroup-' ),
					array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select servicegroup-' ) ));
			
			$this->validatorSchema ['servicegroup_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'ServiceGroup',
					'column' => 'id' ) );
			
		} else {
			
			$this->widgetSchema ['feature_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
									'class' => 'select2',
									'data-placeholder' => _ ( '-Select feature-' ) ) );
			
			$this->validatorSchema ['feature_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
			
			$this->widgetSchema ['servicegroup_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
									'class' => 'select2',
									'data-placeholder' => _ ( '-Select servicegroup-' ) ) );
			
			$this->validatorSchema ['servicegroup_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}
		
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.ps_customer_id IS NULL or ' . $a . '.ps_customer_id = ?', $value );

		return $query;
	}
}
