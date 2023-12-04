<?php

/**
 * Feature
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    backend
 * @subpackage model
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Feature extends BaseFeature {

	public function checkExistOption($id) {

		if ($check = Doctrine_Core::getTable ( 'FeatureOptionFeature' )->findOneByFeatureBranchId ( $id ))
			return TRUE;
		else
			return FALSE;
	}
}