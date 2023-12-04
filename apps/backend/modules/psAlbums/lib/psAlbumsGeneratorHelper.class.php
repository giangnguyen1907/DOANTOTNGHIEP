<?php

/**
 * psAlbums module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psAlbums
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAlbumsGeneratorHelper extends BasePsAlbumsGeneratorHelper {
	
	public function linkToList2($params)
	{
		
		$label = '<i class="fa-fw fa fa-list-ul" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';
		
		return link_to($label.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('class' => 'btn btn-default btn-success bg-color-green btn-sm btn-psadmin'));
	}
}
