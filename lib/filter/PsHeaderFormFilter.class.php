<?php
class PsHeaderFormFilter extends sfFormFilter {
	
	public function configure() {
		
		$this->disableLocalCSRFProtection ();

		$query_school_year = Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears ();

		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => $query_school_year,
				'add_empty' => true
		), array (
				'class' => 'form-control',
				'onchange' => 'this.form.submit();'
		) );

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'query' => $query_school_year,
				'column' => 'id'
		) );

		$params = array ();

		$choice_customer_filter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter.ChoiceCustomerFilter', null, 'admin_module' );

		if ($choice_customer_filter) {
			
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
					'choices' => $choice_customer_filter
			), array (
					'onchange' => 'this.form.submit();',
					'class' => 'select2',
					'style' => "min-width:200px;"
			) );
			
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'required' => true,
					'choices'  => array_keys($choice_customer_filter) ) );
			
		} else {
			
			$choice_customer_filter = Doctrine::getTable ( 'PsCustomer' )->getChoisCustomerByParams ( $params );
			
			sfContext::getInstance ()->getUser ()->setAttribute('psHeaderFilter.ChoiceCustomerFilter', $choice_customer_filter, 'admin_module');
			
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
					'choices' => $choice_customer_filter
			), array (
					'onchange' => 'this.form.submit();',
					'class' => 'select2',
					'style' => "min-width:200px;"
			) );
			
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'required' => true,
					'choices'  => array_keys($choice_customer_filter) ) );
		}
		
		/*
		$query = Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams ( $params );

		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'query' => $query,
				'add_empty' => true
		), array (
				'onchange' => 'this.form.submit();',
				'class' => 'select2',
				'style' => "min-width:200px;"
		) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsCustomer',
				'query' => $query,
				'column' => 'id'
		) );
		
		*/
		
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );

		if (! $psHeaderFilter) {

			$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

			$this->setDefault ( 'ps_school_year_id', $ps_school_year_default->id );
			$this->setDefault ( 'ps_customer_id', sfContext::getInstance ()->getUser ()->getPsCustomerId () );
		} else {
			$this->setDefaults ( $psHeaderFilter );
		}

		$this->widgetSchema->setNameFormat ( 'ps_header_filters[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );
	}
}