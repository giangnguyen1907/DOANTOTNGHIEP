<?php

/**
 * ReceivableDetail form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableDetailForm extends BaseReceivableDetailForm {

	public function configure() {

		$receivable_id = $this->getObject ()
			->getReceivableId ();

		$receivable = Doctrine::getTable ( 'Receivable' )->findOneById ( $receivable_id );

		$this->widgetSchema ['receivable_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						$receivable->getId () => $receivable->getTitle () ) ), array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['amount']->setOption ( 'type', 'number' );
		$this->widgetSchema ['amount']->setAttributes ( array (
				'class' => 'selectorAmount',
				'min' => - 9999999999,
				'max' => 9999999999,
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Enter receivable price' ) ) );

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
		$this->widgetSchema->setNameFormat ( 'psactivitie[%s]' );
	}
}
