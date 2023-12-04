<?php
/**
 * PsMenus form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMenusForm extends BasePsMenusForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' );

		$ps_customer_id = $this->getObject ()
			->getPsCustomerId ();

		$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
					'add_empty' => _ ( '-Select workplace-' ) ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['date_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->widgetSchema ['date_at']->addOption ( 'add-class', 'id_datepicker' );

		$ps_workplace_id = $this->getObject ()
			->getPsWorkplaceId (); // $this->getDefault ('ps_workplace_id');

		// echo 'ps_customer_id:'.$ps_customer_id;

		// echo 'ps_workplace_id:'.$ps_workplace_id;

		if ($ps_customer_id > 0) {

			$params = array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id );

			$this->widgetSchema ['ps_meal_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMoods',
					'query' => Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( $params ),
					'add_empty' => _ ( '-Select meal-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select meal-' ) ) );

			$this->widgetSchema ['ps_meal_id']->setLabel ( 'Meals' );

			$this->validatorSchema ['ps_meal_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => $this->getRelatedModelName ( 'PsMeals' ),
					'required' => true ) );

			$this->widgetSchema ['ps_food_id'] = new psWidgetFormSelectImage ( array (
					'choices' => Doctrine::getTable ( 'PsFoods' )->setChoisPsFoods ( 'a.id, a.title', $ps_customer_id, PreSchool::ACTIVE ) ), array (
					'class' => 'select2',
					'style' => "width:100%",
					'data-placeholder' => _ ( '-Select food-' ) ) );

			$this->widgetSchema ['ps_food_id']->setLabel ( 'Foods' );

			$this->validatorSchema ['ps_food_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => $this->getRelatedModelName ( 'PsFoods' ),
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_food_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select food-' ) ) ), array (
					'class' => 'select2',
					'style' => "width:100%",
					'data-placeholder' => _ ( '-Select food-' ) ) );

			$this->widgetSchema ['ps_meal_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select meal-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select meal-' ) ) );
		}

		$this->widgetSchema ['ps_object_group_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => Doctrine::getTable ( 'PsObjectGroups' )->setSQL ( $this->getObject ()
					->isNew () ? PreSchool::ACTIVE : null ),
				'add_empty' => false // _ ( '-Select group-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select group-' ),
				'required' => true ) );

		$this->validatorSchema ['ps_object_group_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsObjectGroups' ),
				'required' => true ) );

		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'maxlength' => 2000,
				'class' => 'form-control' ) );

		$this->showUseFields ();

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'ps_meal_id',
				'date_at',
				'ps_food_id',
				'ps_object_group_id',
				'note' ) );
	}
}
