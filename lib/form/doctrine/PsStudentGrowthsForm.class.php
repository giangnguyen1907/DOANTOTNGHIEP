<?php

/**
 * PsStudentGrowths form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsStudentGrowthsForm extends BasePsStudentGrowthsForm {

	public function configure() {

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

		$this->widgetSchema ['student_name'] = new sfWidgetFormInputText ();

		$this->validatorSchema ['student_name'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['student_name']->setAttribute ( 'readonly', 'readonly' );

		// config dot kham theo co so

		$this->widgetSchema ['student_name']->setAttribute ( 'readonly', 'readonly' );

		$this->widgetSchema ['student_name']->setAttribute ( 'readonly', 'readonly' );

		$this->widgetSchema ['student_name']->setAttribute ( 'style', 'background-color:#fff' );

		$ps_school_year_id = null;$date_at = date("Y-m-d");

		$ps_workplace_id = null;

		$student_id = $this->getObject () ->getStudentId ();

		$exead_id = $this->getObject () ->getExaminationId ();
		
		if ($student_id > 0) {

			$student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'first_name,last_name,ps_customer_id' );

			if ($exead_id > 0) {
				$ps_examination = Doctrine::getTable ( 'PsExamination' )->findOneById ( $exead_id );
				$ps_school_year_id = $ps_examination->getSchoolYearId ();
				$date_at = $ps_examination->getInputDateAt ();
			}
			$ps_customer_id = $student->getPsCustomerId();
			$student_class = Doctrine::getTable ( 'StudentClass' )->getClassByStudent ( $student_id, $date_at );

			if ($student_class) {
				$ps_workplace_id = $student_class->getPsWorkplaceId ();
				$ps_school_year_id = $student_class->getSchoolYearId ();
			}
			
			$this->setDefault ( 'student_id', $student_id );

			$this->setDefault ( 'student_name', $student->getFirstName () . ' ' . $student->getLastName () );
			//echo $exead_id;die;
		}
		
		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['examination_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsExamination",
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_school_year_id' => $ps_school_year_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_customer_id' => $ps_customer_id
							 ) ),
					'add_empty' => _ ( '-Select examination-' ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;',
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select examination-' ) ) );

			$this->validatorSchema ['examination_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsExamination',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['examination_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select examination-' ) ) );

			$this->validatorSchema ['examination_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		// height - cm
		$this->widgetSchema ['height']->setAttributes ( array (
				'maxlength' => 6,
				'min' => 1 ) );

		// weight - Kg
		$this->widgetSchema ['weight']->setAttributes ( array (
				'maxlength' => 5,
				'min' => 0.5 ) );
		
		$this->validatorSchema ['weight'] = new sfValidatorPass ( array (
				'required' => false ) );
		
		$this->widgetSchema ['people_make']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->showUseFields ();

		$this->addBootstrapForm ();
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'student_name',
				'examination_id',
				'height',
				'weight',
				'index_tooth',
				'index_throat',
				'index_eye',
				'index_heart',
				'index_lung',
				'index_skin',
				'people_make',
				'note' ) );
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
