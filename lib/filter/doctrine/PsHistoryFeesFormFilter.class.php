<?php

/**
 * PsHistoryFees filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsHistoryFeesFormFilter extends BasePsHistoryFeesFormFilter {

	public function configure() {
		
		$this->widgetSchema ['student_code'] = new sfWidgetFormInputText ();
		
		$this->widgetSchema ['student_code']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Student code' ) ) );
		
		$this->validatorSchema ['student_code'] = new sfValidatorString ( array (
				'required' => false ) );
		
		
		$years = range(date('Y'),sfConfig::get('app_start_year'));
		
		$this->widgetSchema ['receipt_date'] = new sfWidgetFormDate ( array (
				'years'   => array_combine($years, $years),
				'format'  => "%month%-%year%"
		),
				array(	'class' => 'form-control'));
		
	}
	
	public function addStudentCodeColumnQuery($query, $field, $value) {
	
		$query->andWhere ( 's.student_code = ?', $value );
		
		return $query;
	
	}
	
	public function addReceiptDateColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias ();
		
		$time_date = $value['year'].'-'.$value['month'].'-01';
		
		$time_date = date("mY", strtotime($time_date) );
		
		$query->andWhere ( 'DATE_FORMAT('.$a.'.receipt_date,"%m%Y") = ?', $time_date );
		
		return $query;
	
	}
}
