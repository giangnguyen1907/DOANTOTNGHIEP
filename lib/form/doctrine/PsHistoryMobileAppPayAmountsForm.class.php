<?php

/**
 * PsHistoryMobileAppPayAmounts form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsHistoryMobileAppPayAmountsForm extends BasePsHistoryMobileAppPayAmountsForm {

	public function configure() {

		if (! $this->getObject ()
			->isNew ()) {

			$ps_customer = $this->getObject ()
				->getUserHistoryMobileAppPayAmounts ()
				->getPsCustomer ();

			$this->setDefault ( 'ps_customer_id', $ps_customer->getId () );

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array (
							$ps_customer->getId () ) ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => true ) );
		} else {

			if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_ADD' )) {

				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $ps_customer_active ),
						'add_empty' => _ ( '-Select customer-' ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'required' => true ) );
			} else {

				$ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneBy ( 'id', myUser::getPscustomerID () );

				$this->setDefault ( 'ps_customer_id', $ps_customer->getId () );

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
						'choices' => array (
								myUser::getPscustomerID () ) ) );

				$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
						'class' => 'form-control',
						'required' => true ) );
			}
		}

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		if ($this->getObject ()
			->get ( 'user_id' ) > 0) {
			$sfGuardUser = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $this->getObject ()
				->get ( 'user_id' ) );

			$ps_customer_id = $sfGuardUser->getPsCustomerId ();

			$ps_customer = $sfGuardUser->getPsCustomer ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );

			$this->setDefault ( 'user_id', $this->getObject ()
				->get ( 'user_id' ) );

			$this->widgetSchema ['user_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$sfGuardUser->getId () => $sfGuardUser->getFirstName () . " " . $sfGuardUser->getLastName () . " (" . $sfGuardUser->getUserName () . ")" ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select user-' ) ) )
			;

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array (
							$ps_customer->getId () ) ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => true ) );
		} else {
			if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_ADD' )) {

				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $ps_customer_active ),
						'add_empty' => _ ( '-Select customer-' ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'required' => true ) );
			} else {

				$ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneBy ( 'id', myUser::getPscustomerID () );

				$this->setDefault ( 'ps_customer_id', $ps_customer->getId () );

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
						'choices' => array (
								myUser::getPscustomerID () ) ) );

				$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
						'class' => 'form-control',
						'required' => true ) );
			}
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$this->widgetSchema ['pay_created_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['pay_created_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required',
				'autocomplete' => 'off' ) );

		$this->validatorSchema ['pay_created_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['amount'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['amount']->setAttributes ( array (
				// 'disabled' => true
				'readonly' => 'readonly' ) );
		$this->widgetSchema ['amount']->setOption ( 'type', 'number' );

		$this->validatorSchema ['amount'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['expiration_date'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['expiration_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required',
				// 'disabled' => true
				'readonly' => 'readonly' ) );

		$this->validatorSchema ['expiration_date'] = new sfValidatorDate ( array (
				'required' => true ) );

		// Số tháng nạp
		$month = range ( 1, 24 );

		$this->widgetSchema ['month'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $month, $month ) ) );

		$this->widgetSchema ['month']->setAttributes ( array (
				'class' => 'select2',
				'required' => true ) );

		$this->validatorSchema ['month'] = new sfValidatorInteger ( array (
				'required' => true ) );

		// Khuyến mại
		// $this->widgetSchema['promotion'] = new sfWidgetFormInputText();

		// $this->widgetSchema['promotion']->setOption('type', 'number');

		// $this->validatorSchema['promotion'] = new sfValidatorInteger(array(
		// 'required' => false
		// ));

		$this->addBootstrapForm ();
	}
}