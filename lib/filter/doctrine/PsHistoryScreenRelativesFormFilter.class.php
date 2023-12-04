<?php

/**
 * PsHistoryScreenRelatives filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsHistoryScreenRelativesFormFilter extends BasePsHistoryScreenRelativesFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$user_id = $this->getDefault ( 'user_id' );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['user_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'sfGuardUser',
					'query' => Doctrine::getTable ( 'sfGuardUser' )->setSQLRelativeByCustomerId ( $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select user-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select user-' ) ) );

			$this->validatorSchema ['user_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'sfGuardUser',
					'required' => false ) );
		} else {
			$this->widgetSchema ['user_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select user-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select user-' ) ) );

			$this->validatorSchema ['user_id'] = new sfValidatorPass ();
		}

		$this->setDefault ( 'user_id', $user_id );
	}
}
