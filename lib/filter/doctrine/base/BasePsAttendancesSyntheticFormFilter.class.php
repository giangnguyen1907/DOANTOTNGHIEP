<?php

/**
 * PsAttendancesSynthetic filter form base class.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsAttendancesSyntheticFormFilter extends BaseFormFilterDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'ps_customer_id' => new sfWidgetFormFilterInput (),
				'ps_class_id' => new sfWidgetFormFilterInput (),
				'login_sum' => new sfWidgetFormFilterInput (),
				'logout_sum' => new sfWidgetFormFilterInput (),
				'tracked_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate () ) ),
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
				'ps_customer_id' => new sfValidatorSchemaFilter ( 'text', new sfValidatorInteger ( array (
						'required' => false ) ) ),
				'ps_class_id' => new sfValidatorSchemaFilter ( 'text', new sfValidatorInteger ( array (
						'required' => false ) ) ),
				'login_sum' => new sfValidatorSchemaFilter ( 'text', new sfValidatorInteger ( array (
						'required' => false ) ) ),
				'logout_sum' => new sfValidatorSchemaFilter ( 'text', new sfValidatorInteger ( array (
						'required' => false ) ) ),
				'tracked_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 00:00:00' ) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 23:59:59' ) ) ) ),
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

		$this->widgetSchema->setNameFormat ( 'ps_attendances_synthetic_filters[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsAttendancesSynthetic';
	}

	public function getFields() {

		return array (
				'id' => 'Number',
				'ps_customer_id' => 'Number',
				'ps_class_id' => 'Number',
				'login_sum' => 'Number',
				'logout_sum' => 'Number',
				'tracked_at' => 'Date',
				'user_updated_id' => 'ForeignKey',
				'created_at' => 'Date',
				'updated_at' => 'Date' );
	}
}
