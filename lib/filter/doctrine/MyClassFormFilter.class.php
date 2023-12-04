<?php
/**
 * MyClass filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MyClassFormFilter extends BaseMyClassFormFilter {

	public function configure() {

		// $this->addPsCustomerFormFilterByWard($ps_ward_id, 'PS_STUDENT_CLASS_FILTER_SCHOOL');
		$this->addPsCustomerFormFilter ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$sql_query = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id );
			
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $sql_query,
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
					
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $sql_query,
					'required' => false
			) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
							
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}
		
		$sql_query_psObjectGroups = Doctrine::getTable ( 'PsObjectGroups' )->setSQL ();
		
		$this->widgetSchema ['ps_obj_group_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => $sql_query_psObjectGroups,
				'add_empty' => '-Select object group-' ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select object group-' ) ) );
		
		$this->validatorSchema ['ps_obj_group_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => $sql_query_psObjectGroups,
				'required' => false
		) );
		
		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', true );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'wp.id = ?', $value );

		return $query;
	}
}
