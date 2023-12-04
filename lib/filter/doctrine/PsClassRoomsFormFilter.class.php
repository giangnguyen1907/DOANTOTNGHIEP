<?php

/**
 * PsClassRooms filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsClassRoomsFormFilter extends BasePsClassRoomsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		// ps_workplace_id filter by ps_customer_id
		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplaces-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select Ward-' ) ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => '-All workplaces-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );
			/*
			 * $this->validatorSchema['ps_workplace_id'] = new sfValidatorChoice(array(
			 * 'required' => false,
			 * 'choices' => array('')
			 * ));
			 */
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );
	}

	// Add virtual_column_name for filter
	public function addPsProvinceIdColumnQuery($query, $field, $value) {

		if ($value != '') {
			$query->andWhere ( 'p.id = ?', $value );
		}
		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsDistrictIdColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$query->andWhere ( 'd.id = ?', $value );
		}
		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsWardIdColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$query->andWhere ( 'pw.id = ?', $value );
		}

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$query->andWhere ( 'wp.ps_customer_id = ?', $value );
		}
		return $query;
	}
}
