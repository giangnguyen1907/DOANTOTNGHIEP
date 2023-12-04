<?php
/**
 * FeatureBranch form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureBranchForm extends BaseFeatureBranchForm {

	/**
	 * Bookmarks scheduled for deletion
	 *
	 * @var array
	 */
	protected $scheduledForDeletion = array ();

	public function configure() {

		$this->removeFields ();

		if (! $this->getObject ()
			->isNew ()) {

			$ps_customer = $this->getObject ()
				->getFeature ()
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

			if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $ps_customer_active ),
						'add_empty' => _ ( '-Select customer-' ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $ps_customer_active ),
						'required' => true ) );
			} else {

				$ps_customer = $ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneBy ( 'id', myUser::getPscustomerID () );

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

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );

			$this->widgetSchema ['feature_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $ps_customer_id ),
					'add_empty' => _ ( '-Select feature-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select feature-' ) ) );

			$this->validatorSchema ['feature_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'Feature',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['feature_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select feature-' ) ) );

			$this->validatorSchema ['feature_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_obj_group_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => Doctrine::getTable ( 'PsObjectGroups' )->setSQL (),
				'add_empty' => _ ( '-Select-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select-' ) ) );

		$this->validatorSchema ['ps_obj_group_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsObjectGroups',
				'column' => 'id' ) );

		$this->widgetSchema ['mode'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsBranchMode () ), array (
				'class' => 'select2' ) );

		// Icon fof service
		$this->widgetSchema ['ps_image_id'] = new psWidgetFormSelectImage ( array (
				'choices' => array (
						'' => _ ( '-Select icon-' ) ) + Doctrine::getTable ( 'PsImages' )->setChoisPsImagesByGroup ( PreSchool::FILE_GROUP_FEATURE ) ), array (
				'class' => 'select2 select_icon',
				'style' => "width:100%",
				'data-placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( '-Select icon-' ) ) );

		$this->validatorSchema ['ps_image_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['name']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255,
				'required' => 'required' ) );
		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255 ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['is_study'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['is_continuity'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
						'class' => 'radiobox' ) );
		
		$this->widgetSchema ['is_depend_attendance'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
						'class' => 'radiobox' ) );		

		$this->addBootstrapForm ();

		if (! $this->getObject ()
			->isNew ()) {
			// $this->embedRelation ( 'FeatureBranchTimes' );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	public function removeFields() {

		unset ( $this ['number_option'], $this ['user_created_id'], $this ['user_updated_id'], $this ['created_at'], $this ['updated_at'] );
	}
}