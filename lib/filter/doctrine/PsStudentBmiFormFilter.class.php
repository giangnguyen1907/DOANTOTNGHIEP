<?php

/**
 * PsStudentBMI filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsStudentBmiFormFilter extends BasePsStudentBmiFormFilter {

	public function configure() {

		$this->widgetSchema ['sex'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Gender-' ) + PreSchool::loadPsGender () ), array (
				'class' => 'form-control' ) );
		$this->validatorSchema ['sex'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::$ps_gender ),
				'required' => false ) );
	}
}
