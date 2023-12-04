<?php

/**
 * PsSchoolYear form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QPsSchoolYearForm extends sfDoctrineFormGenerator {
}
class PsSchoolYearForm extends BasePsSchoolYearForm {

	public function configure() {

		$this->widgetSchema ['date'] = new sfWidgetFormDateRange ( array (

				'from_date' => new psWidgetFormInputDate ( array (), array (
						'data-dateformat' => 'dd-mm-yyyy',
						'placeholder' => sfContext::getInstance ()->getI18n ()
							->__ ( 'From date' ) . '(dd-mm-yyyy)',
						'required' => 'required' ) ),

				'to_date' => new psWidgetFormInputDate ( array (), array (
						'data-dateformat' => 'dd-mm-yyyy',
						'placeholder' => sfContext::getInstance ()->getI18n ()
							->__ ( 'To date' ) . '(dd-mm-yyyy)',
						'required' => 'required' ) ),

				'template' => '<div class="row"><div class="col-md-6">%from_date%</div><div class="col-md-6">%to_date%</div></div>' ) );

		$this->validatorSchema ['date'] = new sfValidatorDateRange ( array (

				'required' => true,

				'from_date' => new sfValidatorDate ( array (
						'required' => true ) ),

				'to_date' => new sfValidatorDate ( array (
						'required' => true ) ) ), array (
				'invalid' => 'The from date ("%left_field%") must be before the to date ("%right_field%")' ) )
		;

		$this->validatorSchema ['from_date'] = new sfValidatorPass ();
		$this->validatorSchema ['to_date'] = new sfValidatorPass ();

		unset ( $this ['from_date'], $this ['to_date'] );

		$this->widgetSchema ['note']->setAttribute ( 'class', 'form-control' );
		$this->widgetSchema ['is_default'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateDefaultsFromObject() {

		parent::updateDefaultsFromObject ();

		if (isset ( $this->widgetSchema ['date'] )) {
			$this->setDefault ( 'date', array (
					"from" => $this->getObject ()
						->getFromDate (),
					"to" => $this->getObject ()
						->getToDate () ) );
		}
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );

		$values ['from_date'] = $values ["date"] ["from"];
		$values ['to_date'] = $values ["date"] ["to"];

		return $values;
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
