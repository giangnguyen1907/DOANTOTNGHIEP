<?php

/**
 * PsMemberContract form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberContractForm extends BasePsMemberContractForm {

	public function configure() {

		// Custom form GuardUserForm, add field member_id
		$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsMember' ),
				'query' => Doctrine::getTable ( 'PsMember' )->setSQLMemberForUser ( myUser::getPscustomerID () ),
				'add_empty' => true ) );

		$this->widgetSchema ['ps_customer_id']->setDefault ( myUser::getPscustomerID () );

		// $this->widgetSchema['member_id']->setDefault($this->getObject()->get('member_id'));
		$this->validatorSchema ['member_id'] = new sfValidatorInteger ( array (
				'required' => ! myUser::credentialPsCustomers ( 'PS_HR_MEMBERCONTRACT_FILTER_SCHOOL' ) ? true : false ) );

		$this->setDateWidgetSchema ( 'start_at' );
		$this->setDateWidgetSchema ( 'expire_at' );
		$this->setDateWidgetSchema ( 'signature_at' );
	}
}
