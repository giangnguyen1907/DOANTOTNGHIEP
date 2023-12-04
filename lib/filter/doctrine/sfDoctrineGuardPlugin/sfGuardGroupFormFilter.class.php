<?php
/**
 * sfGuardGroup filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupFormFilter extends PluginsfGuardGroupFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		if ($value > 0) {
			$query->where ( $a . '.ps_customer_id = ? OR ' . $a . '.ps_customer_id IS NULL', $value );
		}

		return $query;
	}
}