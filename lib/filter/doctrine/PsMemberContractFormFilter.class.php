<?php

/**
 * PsMemberContract filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberContractFormFilter extends BasePsMemberContractFormFilter {

	public function configure() {

		// Custom form GuardUserForm, add field member_id
		$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsMember' ),
				'query' => Doctrine::getTable ( 'PsMember' )->setSQLMemberForUser ( myUser::getPscustomerID () ),
				'add_empty' => true ) );

		$this->setDateWidgetSchemaFormFilter ( 'start_at' );
		$this->setDateWidgetSchemaFormFilter ( 'expire_at' );
		$this->setDateWidgetSchemaFormFilter ( 'signature_at' );
	}
}
