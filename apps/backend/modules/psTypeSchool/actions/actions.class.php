<?php
require_once dirname ( __FILE__ ) . '/../lib/psTypeSchoolGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psTypeSchoolGeneratorHelper.class.php';

/**
 * psTypeSchool actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psTypeSchool
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psTypeSchoolActions extends autoPsTypeSchoolActions {

	public function getDefaultSort() {

		return array (
				'iorder',
				'asc' );
	}

	protected function addSortQuery($query) {

		if (array (
				null,
				null ) == ($sort = $this->getSort ())) {
			return;
		}

		if (! in_array ( strtolower ( $sort [1] ), array (
				'asc',
				'desc' ) )) {
			$sort [1] = 'asc';
		}

		$query->addOrderBy ( $sort [0] . ' ' . $sort [1] );
	}

	protected function getSort() {

		if (null !== $sort = $this->getUser ()
			->getAttribute ( 'psTypeSchool.sort', null, 'admin_module' )) {
			return $sort;
		}

		$this->setSort ( $this->getDefaultSort () );

		return $this->getUser ()
			->getAttribute ( 'psTypeSchool.sort', null, 'admin_module' );
	}

	protected function setSort(array $sort) {

		if (null !== $sort [0] && null === $sort [1]) {
			$sort [1] = 'asc';
		}

		$this->getUser ()
			->setAttribute ( 'psTypeSchool.sort', $sort, 'admin_module' );
	}
}