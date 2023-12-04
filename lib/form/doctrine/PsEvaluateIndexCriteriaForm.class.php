<?php

/**
 * PsEvaluateIndexCriteria form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexCriteriaForm extends BasePsEvaluateIndexCriteriaForm {

	public function configure() {

		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' )) {
			$ps_customer_id = null;
		} else {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		if (! $this->getObject ()
			->isNew ()) {

			$subject = $this->getObject ()
				->getPsEvaluateSubject ();

			$ps_customer_id = $subject->getPsCustomerId ();

			$ps_workplace_id = $subject->getPsWorkplaceId ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );

			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		}
		// if($this->getObject()->isNew()){
		// $ps_customer_id = $this->getDefault('ps_customer_id');
		// $ps_workplace_id = $this->getDefault('ps_workplace_id');

		// if ($ps_customer_id > 0) {

		// $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
		// 'model' => "PsWorkplaces",
		// 'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
		// 'add_empty' => _('-Select workplaces-')
		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select workplaces-')
		// ));

		// } else {

		// $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormSelect(array(
		// 'choices' => array(
		// '' => _('-Select workplaces-')
		// )
		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select workplaces-')
		// ));
		// }

		// $this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
		// //'required' => true,
		// 'required' => false,
		// 'model' => 'PsWorkplaces',
		// 'column' => 'id'
		// ));

		// if ($ps_customer_id > 0) {

		// $this->widgetSchema['evaluate_subject_id'] = new sfWidgetFormDoctrineChoice(array(
		// 'model' => "PsEvaluateSubject",
		// 'query' => Doctrine::getTable('PsEvaluateSubject')->setSQLEvaluateIndexSubjectByParam(array(
		// 'is_activated' => PreSchool::ACTIVE,
		// 'ps_customer_id' => $ps_customer_id,
		// 'ps_workplace_id' => $ps_workplace_id
		// )),
		// 'add_empty' => _('-Select evaluate subject-')
		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select evaluate subject-')
		// ));

		// } else {

		// $this->widgetSchema['evaluate_subject_id'] = new sfWidgetFormSelect(array(
		// 'choices' => array(
		// '' => _('-Select evaluate subject-')
		// )
		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select evaluate subject-')
		// ));
		// }
		// }

		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, $ps_customer_id ),
				'add_empty' => '-Select customer-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select customer-' ) ) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsCustomer',
				'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, $ps_customer_id ),
				'column' => 'id' ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplaces-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
			
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
				'column' => 'id' ) );
			
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplaces-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
							
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger( array (
				'required' => true ) );
		}

		

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['evaluate_subject_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsEvaluateSubject",
					'query' => Doctrine::getTable ( 'PsEvaluateSubject' )->setSQLEvaluateIndexSubjectByParam ( array (
							'is_activated' => PreSchool::ACTIVE,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-Select evaluate subject-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select evaluate subject-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
			
		} else {

			$this->widgetSchema ['evaluate_subject_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select evaluate subject-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select evaluate subject-' ) ) );
		}

		$this->widgetSchema ['evaluate_subject_id']->setLabel ( 'Subject title' );

		$this->validatorSchema ['criteria_code'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		// $this->mergePostValidator(new sfValidatorCallback(array(
		// 'callback' => array(
		// $this,
		// 'postValidateCriteriaCodeExits'
		// )
		// )));

		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	public function postValidateCriteriaCodeExits(sfValidatorCallback $validator, array $values) {

		$param = array ();

		$param ['criteria_code'] = $values ['criteria_code'];

		$param ['evaluate_subject_id'] = $values ['evaluate_subject_id'];

		$param ['is_activated'] = $values ['is_activated'];

		$checkCriteriaCodeExits = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->checkCriteriaCodeExits ( $param );

		if ($checkCriteriaCodeExits) {
			$error = new sfValidatorError ( $validator, 'Criteria code already exist.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"criteria_code" => $error ) );
		}

		return $values;
	}
}
