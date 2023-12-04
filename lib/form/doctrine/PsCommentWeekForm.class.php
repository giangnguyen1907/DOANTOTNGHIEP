<?php

/**
 * PsCommentWeek form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCommentWeekForm extends BasePsCommentWeekForm {

	public function configure() {

		$student_id = $this->getObject ()
			->getStudentId ();
		$ps_year = $this->getObject ()
			->getPsYear ();
		$ps_month = $this->getObject ()
			->getPsMonth ();
		$ps_week = $this->getObject ()
			->getPsWeek ();

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

		if ($student_id > 0) {

			$student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

			$this->setDefault ( 'student_id', $student_id );

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', $student->getPsCustomerId () );
		}

		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$this->widgetSchema ['ps_year'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:80px; width:100%;",
				'data-placeholder' => _ ( '-Select year-' ) ) );

		$this->validatorSchema ['ps_year'] = new sfValidatorPass ( array (
				'required' => true ) );

		if ($ps_year == '') {
			$ps_year = date ( 'Y' );
		}

		$this->setDefault ( 'ps_year', $ps_year );

		$this->widgetSchema ['ps_month'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PreSchool::loadPsMonth () ), array (
				'class' => 'select2',
				'style' => "min-width:100px;width:100%;",
				'required' => false,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) );

		$this->validatorSchema ['ps_month'] = new sfValidatorPass ( array (
				'required' => false ) );

		$this->setDefault ( 'ps_month', $ps_month );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$this->widgetSchema ['ps_week'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						0 => 'Select week' ) + PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'required' => false,
				'style' => "min-width:250px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) );

		$this->validatorSchema ['ps_week'] = new sfValidatorPass ( array (
				'required' => false ) );

		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();

		if (isset ( $weeks [$ps_week - 1] )) {

			$weeks_form = $weeks [$ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];

			$form_week_end = $weeks_form ['week_end'];

			$form_week_list = $weeks_form ['week_list'];
		}

		if ($ps_week == '') {
			$ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		}

		if ($ps_month > 0) {

			$ps_week = 0;

			$this->widgetSchema ['ps_week']->setAttribute ( 'disabled', 'disabled' );

			$this->widgetSchema ['ps_week']->setAttribute ( 'style', 'background-color:#fff;width:100%;' );
		}

		$this->setDefault ( 'ps_week', $ps_week );

// 		$this->widgetSchema ['comment']->setAttributes ( array (
// 				'class' => 'input_textarea form-control',
// 				'rows' => 10 ) );

// 		$this->validatorSchema ['comment'] = new sfValidatorString ( array (
// 				'required' => true ) );
		
		$this->widgetSchema ['comment'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'form-control','rows' => 10 ) );
		
		$this->validatorSchema ['comment'] = new sfValidatorString ( array (
				'required' => true ) );
		
		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadBrowseArticles () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		$this->showUseFields ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'student_id',
				'ps_year',
				'ps_month',
				'ps_week',
				'title',
				'is_activated',
				'comment' ) );
	}
}
