<?php
/**
 * StudentService filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceFormFilter extends BaseStudentServiceFormFilter {
	public function configure() {
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' 
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) 
		) );
		
		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', true );
		
		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'required' => true 
		) );
		
		$this->addPsCustomerFormFilter ( 'PS_STUDENT_CLASS_FILTER_SCHOOL', true );
		
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		//echo $ps_customer_id;die;
		if ($ps_customer_id > 0) {
			
			// ps_workplace_id filter by ps_customer_id
			$sql_query = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id );
			
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $sql_query,
					'add_empty' => '-Select workplace-' 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) );
			
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $sql_query,
					'required' => false 
			) );
			
			$param_class = array (
					'ps_school_year_id' => $this->getDefault ( 'school_year_id' ),
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $this->getDefault ( 'ps_workplace_id' ),
					'is_activated' => PreSchool::ACTIVE
			);
			
			$sql_query_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sql_query_class,
					'add_empty' => _ ( '-Select class-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' )
			) );
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sql_query_class,
					'required' => false
			) );
			
			$sql_query_service = Doctrine::getTable ( 'Service' )->getChoisGroupServiceByParams($ps_customer_id,$this->getDefault ( 'school_year_id' ),null);
			
			$this->widgetSchema ['service_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => '-Select service-' ) + $sql_query_service ),
					array(
							'class' => "form-control",
							'required' => true
					) ) ;
			$this->validatorSchema ['service_id'] = new sfValidatorInteger ( array ('required' => true ) );
			
		} else {
			
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) );
			
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false 
			) );
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' )
			) );
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'column' => 'id',
					'required' => false
			) );
		}
		
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		
		$param_class = array (
				'ps_school_year_id' => $this->getDefault ( 'school_year_id' ),
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE 
		);
		
		if ($ps_workplace_id > 0) {
			
			$sql_query_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sql_query_class,
					'add_empty' => _ ( '-Select class-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) 
			) );
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sql_query_class,
					'required' => false 
			) );
			
		} 
		/*else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) 
			) );
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'column' => 'id',
					'required' => false 
			) );
		}
		*/
		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords' ) 
		) );
		
		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false 
		) );
	}
	public function addPsCustomerIdColumnQuery($query, $field, $value) {
		$a = $query->getRootAlias ();
		
		$query->addWhere ( $a . '.ps_customer_id = ?', $value );
		
		return $query;
	}
	
	/*
	 * public function addPsWorkplaceIdColumnQuery($query, $field, $value) {
	 *
	 * $a = $query->getRootAlias ();
	 *
	 * $query->addWhere ( $a . '.ps_workplace_id = ?', $value );
	 * return $query;
	 * }
	 */
	public function addPsClassIdColumnQuery($query, $field, $value) {
		$query->andWhere ( 'sc.myclass_id = ?', $value );
		
		return $query;
	}
	public function addServiceIdColumnQuery($query, $field, $value) {
		return $query;
	}
	
	// Tim kiem member_code,first_name,last_name,mobile
	public function addKeywordsColumnQuery($query, $field, $value) {
		$keywords = PreString::trim ( $value );
		
		if (PreString::length ( $keywords ) > 0) {
			
			$keywords = '%' . PreString::strLower ( $keywords ) . '%';
			
			$query->addWhere ( 'LOWER(s.first_name) LIKE ? OR LOWER(s.last_name) LIKE ? OR  LOWER(s.student_code) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords 
			) );
		}
		
		return $query;
	}
	public function doBuildQuery(array $values) {
		$query = parent::doBuildQuery ( $values );
		
		$a = $query->getRootAlias ();
		
		$query->addSelect ( 'ss.id AS id,se.title as service_title' );
		$query->addSelect ( 'ss.created_at AS created_at, ss.updated_at AS updated_at' );
		
		$query->addSelect ( 'CONCAT(u1.first_name, " ", u1.last_name) as created_by' );
		$query->addSelect ( 'u.id AS u_id, CONCAT(u.first_name, " ", u.last_name) as updated_by' );
		
		if (isset($values ['service_id']) && $values ['service_id'] > 0) {
			$query->leftJoin ( 's.StudentService ss With (ss.delete_at IS NULL AND ss.service_id =?)', $values ['service_id'] );
			$query->leftJoin ( 'ss.Service se' );
		} else {
			$query->leftJoin ( 's.StudentService ss With ss.delete_at IS NULL' );
			$query->leftJoin ( 'ss.Service se' );
		}
		
		$query->leftJoin ( 'ss.UserCreated u1' );
		$query->leftJoin ( 'ss.UserCreated u' );
		
		return $query;
	}
}
