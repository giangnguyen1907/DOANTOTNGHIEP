<?php
/**
 * Service form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceSplitForm extends BaseServiceSplitForm {

	/**
	 * Bookmarks scheduled for deletion
	 *
	 * @var array
	 */
	public function configure() {

		// unset($this['service_id']);

		/*
		 * $this->widgetSchema['count_value'] = new sfWidgetFormInputText(array(), array("style"=>'width: 50px;'));
		 * $this->widgetSchema['count_ceil'] = new sfWidgetFormInputText(array(), array("style"=>'width: 50px;'));
		 * $this->widgetSchema['split_value'] = new sfWidgetFormInputText(array(), array("style"=>'width: 50px;'));
		 * $this->validatorSchema['count_value'] = new sfValidatorNumber(
		 * array( 'required' => FALSE),
		 * array( 'required' => 'Nhập số lượng',
		 * 'invalid' => '"%value%" không phải là số'
		 * ));
		 * $this->validatorSchema['count_ceil'] = new sfValidatorNumber(
		 * array( 'required' => FALSE),
		 * array( 'required' => 'Nhập số lượng',
		 * 'invalid' => '"%value%" không phải là số'
		 * ));
		 * $this->validatorSchema['split_value'] = new sfValidatorNumber(
		 * array( 'required' =>FALSE),
		 * array( 'required' => 'Nhập số phần trăm',
		 * 'invalid' => '"%value%" không phải là số'
		 * ));
		 */
		/*
		 * $this->widgetSchema ['service_id'] = new sfWidgetFormInputHidden ();
		 * $this->validatorSchema ['service_id'] = new sfValidatorNumber ();
		 */

		// Choice(array('choices' => array($this->getObject()->get('service_id')), 'empty_value' => $this->getObject()->get('service_id'), 'required' => true));

		// BEGIN: Custom form list
		/*
		 * $this->widgetSchema ['updated_time'] = new sfWidgetFormInputText ();
		 * $this->validatorSchema ['updated_time'] = new sfValidatorString ( array ('required' => false) );
		 * $this->setDefault ( 'updated_time', $this->getObject ()->getUpdatedAt () );
		 */

		// /$this->widgetSchema ['updated_by'] = new sfWidgetFormInputText ();
		// /$this->validatorSchema ['updated_by'] = new sfValidatorString (array('required' => false) );
		// $this->setDefault( 'updated_by', $this->getObject ()->getUserCreated ()->getName () );

		// END: Custom form list
		$this->widgetSchema ['service_id'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['service_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['service_id']->setAttributes ( array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['count_value']->setAttributes ( array (
				'type' => 'number',
				'min' => 0,
				'max' => 100 ) );
		/*
		 * if ($this->getObject()->getService()->getIsTypeFee() == 1) {
		 * $this->widgetSchema['count_value']->setLabel('<code>'.sfContext::getInstance()->getI18n()->__('Number of unused words').'</code>');
		 * }
		 */

		$this->widgetSchema ['count_ceil']->setAttributes ( array (
				'type' => 'number',
				'min' => 0,
				'max' => 100 ) );
		$this->widgetSchema ['split_value']->setAttributes ( array (
				'type' => 'text',
				'data-fv-numeric' => "false",
				'min' => 0,
				'max' => 1000 ) );

		// $this->widgetSchema['split_value']->setDefault(PreNumber::number_clean($this->getObject()->getSplitValue()));

		$this->widgetSchema ['value_price'] = new sfWidgetFormInputText ( array (), array (
				"style" => 'background-color:#fff;' // 'readonly ' => 'readonly '
		) );

		$this->validatorSchema ['value_price'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['value_price']->setLabel ( 'Service amount' );

		if (! $this->getObject ()
			->isNew ()) {

			$percent = $this->getObject ()
				->getSplitValue ();

			$service_detail = $this->getObject ()
				->getService ()
				->getServiceDetailByDate ( time () );

			$amount = $service_detail->getAmount ();

			$percent_price = ($amount * $service_detail->getByNumber () * $percent) / 100;

			sfContext::getInstance ()->getConfiguration ()
				->loadHelpers ( 'Number' );

			// $percent_price = format_currency($percent_price, PreSchool::DEFAULT_CURRENCY);

			$this->widgetSchema ['value_price']->setDefault ( $percent_price );
		}

		/*
		 * if ($this->object->exists ()) {
		 * $this->widgetSchema ['delete'] = new sfWidgetFormInputCheckbox ();
		 * $this->validatorSchema ['delete'] = new sfValidatorPass (array('required' => false));
		 * $this->widgetSchema ['delete']->setAttributes ( array (
		 * 'class' => 'btn btn-xs btn-default checkbox style-0'
		 * ) );
		 * }
		 */

		$this->removeFields ();
		$this->addBootstrapForm ();

		// $this -> embedRelation('ServiceSplit');
	}

	public function updateObject($values = null) {

		// unset($this['updated_by'], $this['updated_time']);
		return parent::baseUpdateObject ( $values );
	}

	/*
	 * public function addNewField($number = 0) {
	 * $subForm = new BaseForm ();
	 * for($i = 0; $i < $number; $i ++) {
	 * $serviceSplit = new ServiceSplit ();
	 * $serviceSplit->Service = $this->getObject ();
	 * $serviceSplitForm = new ServiceSplitForm ( $serviceSplit );
	 * $new_servicesplit->embedForm ( $i, $serviceSplitForm );
	 * }
	 * $this->embedForm ( 'newServiceSplit', $subForm );
	 * }
	 */
	public function removeFields() {

		// unset($this['user_created_id'], $this['user_updated_id'], $this['created_at'],$this['updated_at'], $this['service_id']);
		unset ( $this ['user_created_id'], $this ['user_updated_id'], $this ['created_at'], $this ['updated_at'] );
	}
}
