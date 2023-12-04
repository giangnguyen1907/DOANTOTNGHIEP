<?php
/**
 * PsCamera filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCameraFormFilter extends BasePsCameraFormFilter {

	public function configure() {

		$this->addVirtualPsCustomerIdFormFilter ( 'PS_SYSTEM_CAMERA_FILTER_SCHOOL' );

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
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['ps_workplace_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2' ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_customer_id > 0 || $ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'c.id, c.title', array (
							'ps_workplace_id' => $this->getDefault ( 'ps_workplace_id' ),
							'ps_customer_id' => $ps_customer_id ) ),
					'add_empty' => '-Select class room-' ) );

			$this->validatorSchema ['ps_class_room_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'placeholder' => _ ( '-Select class room-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['ps_class_room_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2' ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'wp.ps_customer_id = ?', $value );

		return $query;
	}

	// Add virtual column_name for filter
	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'cr.ps_workplace_id = ?', $value );

		return $query;
	}

	// Add virtual column_name for filter
	public function addPsClassRoomIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_class_room_id = ?', $value );

		return $query;
	}
}
