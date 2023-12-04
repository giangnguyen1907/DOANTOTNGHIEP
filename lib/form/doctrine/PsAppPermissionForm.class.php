<?php
/**
 * PsAppPermission form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAppPermissionForm extends BasePsAppPermissionForm {

	public function configure() {

		$this->widgetSchema ['ps_app_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '--Select Application--' ) ) + Doctrine::getTable ( 'PsApp' )->getGroupPsApps () ) );

		// $this->widgetSchema['ps_app_id']->setOption('renderer_class', 'sfWidgetFormDoctrineChoiceGrouped');

		// Dinh dang lai ma
		if (! $this->getObject ()
			->isNew ()) {

			// Lay app_code cua ps_app_id(id)
			$app_code = Doctrine::getTable ( 'PsApp' )->findOneBy ( 'id', $this->getObject ()
				->get ( 'ps_app_id' ) )
				->get ( 'app_code' );
			$this->getObject ()
				->set ( 'app_permission_code', str_replace ( $app_code . '_', '', $this->getObject ()
				->get ( 'app_permission_code' ) ) );
		} else {
			$this->getObject ()
				->set ( 'ps_app_id', ( int ) sfContext::getInstance ()->getRequest ()
				->getParameter ( 'ps_app_id' ) );
			$this->getObject ()
				->set ( 'app_permission_code', 'SHOW DETAIL EDIT ADD DELETE' );
		}

		$this->widgetSchema ['app_permission_code'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( '--Select--' ) ) + PreConst::InitAppPermissionCode () ) );

		$this->widgetSchema ['is_system'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		if ($this->getObject ()
			->isNew ())
			$this->setDefault ( 'iorder', Doctrine::getTable ( 'PsAppPermission' )->getMaxIorder () + 1 );
		/*
		 * $this->validatorSchema ['app_permission_code'] = new sfValidatorDoctrineUnique ( array ( 'model' => 'PsAppPermission', 'column' => array ( 'app_permission_code', 'id' ) ), array ( 'invalid' => 'App permission code already exist.' ) );
		 */

		$this->widgetSchema ['ps_app_id']->setAttributes ( array (
				'class' => 'select2' ) );

		$this->widgetSchema ['app_permission_code']->setAttributes ( array (
				'class' => 'select2' ) );

		$this->addBootstrapForm ();

		$this->mergePostValidator ( new sfValidatorCallback ( array (
				'callback' => array (
						$this,
						'postValidateAppPermissionCodeExits' ) ) ) );
	}

	public function updateObject($values = null) {

		// $object = parent :: updateObject($values);
		$object = parent::baseUpdateObject ( $values );

		// Lay app_code cua ps_app_id
		$app_code = Doctrine::getTable ( 'PsApp' )->findOneById ( $this->getObject ()
			->get ( 'ps_app_id' ) )
			->get ( 'app_code' );
		$object->setAppPermissionCode ( $app_code . '_' . strtoupper ( $this->getObject ()
			->get ( 'app_permission_code' ) ) );

		/*
		 * if ($this->getObject()->isNew()) {
		 * $object->set('user_created_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
		 * $object->set('user_updated_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
		 * } else {
		 * $object->set('user_updated_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
		 * }
		 */

		return $object;
	}

	// Them vao sfGuardPermission (customer plugin)
	protected function addSfGuardPermission() {

		$sfGuardPermissionForm = new sfGuardPermissionForm ();
	}

	// Check app_permission_code
	public function postValidateAppPermissionCodeExits(sfValidatorCallback $validator, array $values) {

		$app_permission_code = $values ['app_permission_code'];
		$ps_app_id = $values ['ps_app_id'];
		$id = $values ['id'];

		$app_code = Doctrine::getTable ( 'PsApp' )->findOneById ( $ps_app_id )
			->get ( 'app_code' );
		$app_permission_code = $app_code . '_' . strtoupper ( $app_permission_code );

		$check = Doctrine::getTable ( 'PsAppPermission' )->checkAppPermissionCodeExits ( $app_permission_code, $id );

		if (! $check) {
			$error = new sfValidatorError ( $validator, 'App permission code already exist.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"app_permission_code" => $error ) );
		}

		return $values;
	}
}