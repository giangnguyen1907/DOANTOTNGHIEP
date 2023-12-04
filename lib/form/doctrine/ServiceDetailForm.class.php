<?php

/**
 * ServiceDetail form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceDetailForm extends BaseServiceDetailForm {

	public function configure() {

		$this->removeFields ();

		// $this->widgetSchema['service_id'] = new sfWidgetFormInputHidden();
		/*
		 * $this->widgetSchema['amount'] = new sfWidgetFormInputText(array(), array("style"=>'width: 150px;'));
		 * $this->widgetSchema['by_number'] = new sfWidgetFormInputText(array(), array("style"=>'width: 100px;'));
		 * $years = range(date('Y') - 10, date('Y')+20);
		 * $this->widgetSchema['detail_at'] = new sfWidgetFormDate(array(
		 * 'format' => '%month%/%year%',
		 * 'years' => array_combine($years,$years),
		 * 'days' => array('01'),
		 * 'empty_values'=>array('day'=>'Day','month'=>'Month','year'=>'Year')
		 * ));
		 * $this->widgetSchema['detail_end'] = new sfWidgetFormDate(array(
		 * 'format' => '%month%/%year%',
		 * 'years' => array_combine($years,$years),
		 * 'days' => array('01'),
		 * 'empty_values'=>array('day'=>'Day','month'=>'Month','year'=>'Year')
		 * ));
		 * $this->validatorSchema['detail_at'] = new sfValidatorDate(
		 * array('required' => true),
		 * array('required' => 'Required field','invalid' => 'Invalid Detail_at', 'max'=>'Không lớn hơn ngày hiện tại'));
		 * $this->validatorSchema['detail_end'] = new sfValidatorDate(
		 * array('required' => true),
		 * array('required' => 'Required field',
		 * 'invalid' => 'Invalid Detail_end',
		 * 'max' =>'Không lớn hơn ngày hiện tại'));
		 * $this->validatorSchema['amount'] = new sfValidatorNumber(
		 * array( 'required' => true),
		 * array( 'required' => 'Nhập giá',
		 * 'invalid' =>'"%value%" không phải là số'
		 * )
		 * );
		 * $this->validatorSchema['by_number'] = new sfValidatorNumber(
		 * array( 'required' =>true),
		 * array( 'required' => 'Nhập số lượng',
		 * 'invalid' =>'"%value%" không phải là số'
		 * )
		 * );
		 */

		$this->widgetSchema ['amount']->setAttributes ( array (
				'class' => 'selectorAmount',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Enter service price' ) ) );

		$this->widgetSchema ['by_number']->setOption ( 'type', 'number' );
		$this->widgetSchema ['by_number']->setAttributes ( array (
				'class' => 'selectorNumber',
				'min' => 1,
				'max' => 100,
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Enter the quantity' ) ) );

		$this->widgetSchema ['detail_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['detail_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'class' => 'startDate' ) );

		$this->widgetSchema ['detail_end'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['detail_end']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'class' => 'endDate' ) );

		$this->validatorSchema ['detail_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->validatorSchema ['detail_end'] = new sfValidatorDate ( array (
				'required' => true ) );

		if ($this->object->exists ()) {

			$this->widgetSchema ['delete'] = new sfWidgetFormInputCheckbox ();
			$this->validatorSchema ['delete'] = new sfValidatorPass ( array (
					'required' => false ) );
			$this->widgetSchema ['delete']->setAttributes ( array (
					'class' => 'btn btn-xs btn-default checkbox style-0' ) );
		}

		$this->addBootstrapForm ();
	}

	protected function removeFields() {

		parent::removeFields ();
		unset ( $this ['service_id'] );
	}

	/*
	 * public function updateObject($values = null) {
	 * return parent::baseUpdateObject ( $values );
	 * }
	 */
}
