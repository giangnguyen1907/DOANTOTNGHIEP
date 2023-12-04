<?php

/**
 * PsAdvices filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAdvicesFormFilter extends BasePsAdvicesFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$start_at = $this->getDefault ( 'start_at' );
		
		$stop_at = $this->getDefault ( 'stop_at' );
		
		$list_date = PsDateTime::getStartAndEndDateOfWeek(date('W'),date('Y'));
		
		if($start_at == ''){
			$start_at = date('d-m-Y', strtotime($list_date['week_start']));
		}
		if($stop_at == ''){
			$stop_at = date('d-m-Y', strtotime($list_date['week_end']));
		}
		
		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false ), // _ ( '-Select school year-' )
		array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		
		if ($school_year_id <= 0){
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}
		
		$this->setDefault ( 'school_year_id', $school_year_id );
			
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		
		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
				'is_activated' => PreSchool::ACTIVE
		);
		
		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}
		
		$this->setDefault ( 'start_at', $start_at );
		
		$this->setDefault ( 'stop_at', $stop_at );
		
		$this->widgetSchema ['start_at'] = new psWidgetFormFilterInputDate ();
		
		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => false,
				), array (
						'invalid' => 'Invalid tracked at',
						 ) );
		
		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Start at',
				'style' => "width:120px;",
				'required' => false ) );
		
		$this->widgetSchema ['stop_at'] = new psWidgetFormFilterInputDate ();
		
		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false,
				 ), array (
						'invalid' => 'Invalid tracked at',
						 ) );
		
		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Stop at',
				'style' => "width:120px;",
				'required' => false ) );
		
		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		// $query->innerJoin('mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values);
		// $query->addWhere('s.ps_workplace_id = ?', $value);
		// echo $value;die;
		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		return $query;
	}
	
	public function addStartAtColumnQuery($query, $field, $value) {
		
		return $query;
	}
	
	public function addStopAtColumnQuery($query, $field, $value) {
		
		return $query;
	}
	
	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = 's';

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.student_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR (LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();
		
		$query->andWhere ( 's.ps_customer_id = ? ', $values ['ps_customer_id'] );
		
		$query->leftJoin ( 'mc.PsClassRooms cr' );

		$query->leftJoin ( 'cr.PsWorkPlaces wp' );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {
			$query->andWhere ( 'mc.id = ?', $values ['ps_class_id'] );
		}

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {
			$query->andWhere ( 'wp.id = ?', $values ['ps_workplace_id'] );
		}

		if (isset($values ['school_year_id']) && $values ['school_year_id'] > 0) {
			$query->leftJoin ( 'mc.PsSchoolYear sy With mc.school_year_id = ?', $values ['school_year_id'] );
		}

		if (isset ( $values ['is_activated'] )) {
			$query->andWhere ( $a . '.is_activated = ?', $values ['is_activated'] );
		}
		
		$list_date = PsDateTime::getStartAndEndDateOfWeek(date('W'),date('Y'));
		
		if($values ['start_at'] ==''){
			$values ['start_at'] = $list_date['week_start'];
		}
		
		if($values ['stop_at'] ==''){
			$values ['stop_at'] = $list_date['week_end'];
		}
		
		$query->andWhere ( ' DATE_FORMAT('.$a.'.date_at,"%Y%m%d") >= ? AND DATE_FORMAT('.$a.'.date_at,"%Y%m%d") <= ? ', array(date('Ymd',strtotime($values ['start_at'])),date('Ymd',strtotime($values ['stop_at']))));
		
		return $query;
	}
}