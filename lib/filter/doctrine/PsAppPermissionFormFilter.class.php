<?php

/**
 * PsAppPermission filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAppPermissionFormFilter extends BasePsAppPermissionFormFilter {

	public function configure() {

		// unset($this['ps_app_id']);
		$this->widgetSchema ['ps_app_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select All-' ) ) + Doctrine::getTable ( 'PsApp' )->getGroupPsApps () ) );
	}

	// Add virtual_column_name for filter
	public function addPsAppIdColumnQuery($query, $field, $value) {

		$alias = $query->getRootAlias ();

		// $query->where ( $alias . ".file_group =?", $value );

		return $query;
	}
}
