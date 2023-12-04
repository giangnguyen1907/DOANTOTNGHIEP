<?php

/**
 * PsMemberNational filter form base class.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsMemberNationalFormFilter extends BaseFormFilterDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'created_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate (),
						'with_empty' => false ) ),
				'updated_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate (),
						'with_empty' => false ) ) ) );

		$this->setValidators ( array (
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

		$this->widgetSchema->setNameFormat ( 'ps_member_national_filters[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsMemberNational';
	}

	public function getFields() {

		return array (
				'national_code' => 'Text',
				'member_id' => 'Number',
				'created_at' => 'Date',
				'updated_at' => 'Date' );
	}
}
