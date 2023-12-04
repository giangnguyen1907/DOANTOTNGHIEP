<?php

/**
 * PsConfigLateFees form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConfigLateFeesForm extends BasePsConfigLateFeesForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL' );

		$ps_customer_id = $this->getObject ()
			->getPsCustomerId ();

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		if ($ps_customer_id > 0) {
			
			$query = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id );
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $query,
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $query,
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass(array (
					'required' => true ) );
		}

		$this->widgetSchema ['from_minute']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 4,
				'min' => 0,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['from_minute'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['to_minute']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 4,
				'min' => 1,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['to_minute'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['price']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 10,
				'min' => 1,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['price'] = new sfValidatorNumber ( array (
				'required' => true ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );

		return $object;
	}
}
