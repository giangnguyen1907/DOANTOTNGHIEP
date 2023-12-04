<?php

/**
 * PsMemberAbsents form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberAbsentsForm extends BasePsMemberAbsentsForm {

	public function configure() {

		if ($this->getObject ()
			->isNew ()) {
			$ps_customer_id = myUser::getPscustomerID ();
			$ps_department_id = null;
			$member_id = null;
		} else {

			$member = $this->getObject ()
				->getPsMember ();

			$ps_customer_id = $member->getPsCustomerId ();

			$member_id = $member->getId ();

			// Lay phong ban cua giao vien tại thoi diem nghi
			$deparment = Doctrine::getTable ( 'PsMemberDepartments' )->getDepartmentsMemberId ( $member_id );
			// // echo $myclass->getPsWorkplaceId(); die();
			$ps_department = $deparment->getPsDepartmentId ();

			$this->setDefault ( 'ps_department_id', $ps_department );

			$this->setDefault ( 'ps_workplace_id', $deparment->getPsWorkplaceId () );

			$this->setDefault ( 'member_id', $member->getId () );
		}

		$ps_customer_id = myUser::getPscustomerID ();
		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		// echo $ps_workplace_id; die();
		$this->widgetSchema ['ps_department_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'query' => Doctrine::getTable ( 'PsDepartment' )->setDepartmentByWorkplaceId ( $ps_workplace_id, $ps_customer_id ),
				'add_empty' => _ ( '-Select department-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select department-' ) ) );

		$this->validatorSchema ['ps_department_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsDepartment',
				'column' => 'id' ) );

		$ps_department_id = $this->getDefault ( 'ps_department_id', $ps_department );

		// echo $ps_department_id; die();
		if ($ps_department_id > 0) {

			$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMemberDepartments' )->setMemberDepartments ( $ps_department_id ),
					'add_empty' => _ ( '-Select member-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );

			$this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsMember',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );

			$this->validatorSchema ['member_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$this->setDefault ( 'member_id', $member_id );

		$this->widgetSchema ['absent_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['absent_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['absent_at'] = new sfValidatorDate ( array (
				'required' => true,
				'date_format' => '~(?P<day>\d{2})-(?P<month>\d{2})-(?P<year>\d{4})~' ) );

		$this->widgetSchema ['absent_type'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
