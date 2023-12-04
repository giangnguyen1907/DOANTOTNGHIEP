<?php

/**
 * PsFeeNewsLetters form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFeeNewsLettersForm extends BasePsFeeNewsLettersForm {

	public function configure() {

		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );

		if (! $psHeaderFilter) {

			$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

			$ps_school_year_id = $ps_school_year_default->id;

			$ps_customer_id = sfContext::getInstance ()->getUser ()->getPsCustomerId ();
		} else {
			$ps_school_year_id = $psHeaderFilter ['ps_school_year_id'];
			$ps_customer_id    = $psHeaderFilter ['ps_customer_id'];
		}

		$this->addPsWorkplaceIdForm ( $ps_customer_id, null, true );
		$this->addPsYearMonthForm ( $ps_school_year_id );
		
		$this->widgetSchema ['note'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'form-control' ) );
		
		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['is_public'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean ()
		), array (
				'class' => 'radiobox'
		) );

		$this->validatorSchema ['is_public'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array_keys ( PreSchool::$ps_is_public )
		) );

		$this->removeFields ();

		$this->addBootstrapForm ();

	}

	public function updateObject($values = null) {
		
		$object = parent::baseUpdateObject($values);
		
		return $object;
		
	}
	
	protected function removeFields() {

		unset ( $this ['number_push_notication'], $this ['user_created_id'], $this ['user_updated_id'], $this ['created_at'], $this ['updated_at'] );

	}

}
