<?php

/**
 * PsImagesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsImagesTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsImagesTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsImages' );
	}

	// Lay gia tri Max cua iorder, return: int - max order
	public function getMaxIorder() {

		return $this->createQuery ()
			->select ( 'MAX(iorder) AS max_order' )
			->fetchOne ()
			->getMaxOrder ();
	}

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *        
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id,' . $a . '.title AS title, ' . $a . '.file_name AS file_name, ' . $a . '.file_group AS file_group, ' . $a . '.iorder AS iorder, ' . $a . '.is_activated AS is_activated, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		return $query;
	}

	/**
	 * FUNCTION: setSqlPsImagesByGroup($file_group)
	 *
	 * @param $file_group -
	 *        	string
	 * @return string - sql
	 */
	public function setSqlPsImagesByGroup($file_group = null) {

		$q = $this->createQuery ()
			->select ( 'id, title, file_name' );

		$q->where ( 'is_activated = ?', PreSchool::ACTIVE );

		if ($file_group != '') {

			$q->andWhere ( 'file_group = ?', $file_group );

			$q->orWhere ( 'file_group IS NULL' );

			$q->orWhere ( 'file_group =?', '' );
		} else {
			// $q->andWhere('file_group IS NULL');
		}

		$q->orderBy ( 'title,iorder' );

		return $q;
	}

	/**
	 * FUNCTION: loadPsImagesByGroup($country_code)
	 *
	 * @param $file_group -
	 *        	string
	 * @return $list obj
	 */
	public function loadPsImagesByGroup($file_group = null) {

		$ps_images = $this->setSqlPsImagesByGroup ( $file_group )
			->execute ();
		/*
		 * $chois = array ();
		 * foreach ( $ps_images as $ps_image ) {
		 * $chois [$ps_image->getId ()] = $ps_image->getTitle();
		 * }
		 */

		return $ps_images;
	}

	public function setChoisPsImagesByGroup($file_group = null) {

		$ps_images = $this->setSqlPsImagesByGroup ( $file_group )
			->execute ();

		$chois = array ();

		// $chois [] = array('title' => sfContext::getInstance()->getI18n()->__("-Select icon-"), 'imagesrc' => null);

		foreach ( $ps_images as $ps_image ) {
			$chois [$ps_image->getId ()] = array (
					'title' => $ps_image->getTitle (),
					'imagesrc' => sfContext::getInstance ()->getRequest ()
						->getRelativeUrlRoot () . '/sys_icon/' . $ps_image->getFileName () );
		}

		return $chois;
	}
}