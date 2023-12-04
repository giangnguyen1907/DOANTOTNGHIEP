<?php

/**
 * PsMemberFunctions filter form base class.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsMemberFunctionsFormFilter extends BaseFormFilterDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'member_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsMember' ),
						'add_empty' => true ) ),
				'ps_function_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsFunction' ),
						'add_empty' => true ) ),
				'start_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate (),
						'with_empty' => false ) ),
				'stop_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate () ) ),
				'is_current' => new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => 'yes or no',
								1 => 'yes',
								0 => 'no' ) ) ),
				'note' => new sfWidgetFormFilterInput (),
				'user_created_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'add_empty' => true ) ),
				'user_updated_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'add_empty' => true ) ),
				'created_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate (),
						'with_empty' => false ) ),
				'updated_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate (),
						'with_empty' => false ) ) ) );

		$this->setValidators ( array (
				'member_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'PsMember' ),
						'column' => 'id' ) ),
				'ps_function_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'PsFunction' ),
						'column' => 'id' ) ),
				'start_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDate ( array (
								'required' => false ) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false ) ) ) ),
				'stop_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDate ( array (
								'required' => false ) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false ) ) ) ),
				'is_current' => new sfValidatorChoice ( array (
						'required' => false,
						'choices' => array (
								'',
								1,
								0 ) ) ),
				'note' => new sfValidatorPass ( array (
						'required' => false ) ),
				'user_created_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'column' => 'id' ) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'column' => 'id' ) ),
				'created_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 00:00:00' ) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 23:59:59' ) ) ) ),
				'updated_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 00:00:00' ) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 23:59:59' ) ) ) ) ) );

		$this->widgetSchema->setNameFormat ( 'ps_member_functions_filters[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsMemberFunctions';
	}

	public function getFields() {

		return array (
				'id' => 'Number',
				'member_id' => 'ForeignKey',
				'ps_function_id' => 'ForeignKey',
				'start_at' => 'Date',
				'stop_at' => 'Date',
				'is_current' => 'Boolean',
				'note' => 'Text',
				'user_created_id' => 'ForeignKey',
				'user_updated_id' => 'ForeignKey',
				'created_at' => 'Date',
				'updated_at' => 'Date' );
	}
}
