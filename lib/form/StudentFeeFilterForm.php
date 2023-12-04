<?php
/**
 * Base project form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class StudentFeeFilterForm extends BaseForm {

	public function configure() {

		$years = range ( sfConfig::get ( 'app_begin_year' ), date ( 'Y' ) + 1 );

		$this->setWidgets ( array (
				'date' => new sfWidgetFormDate ( array (
						'format' => '%month%/%year%',
						'default' => date ( 'Y-m-d' ),
						'years' => array_combine ( $years, $years ) ) ) ) );

		$this->validatorSchema ['date'] = new sfValidatorDate ( array (
				'required' => true ), array (
				'required' => 'Required date',
				'invalid' => 'Invalid date' ) );

		$this->widgetSchema->setNameFormat ( 'student_fee_filter[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );
	}
}