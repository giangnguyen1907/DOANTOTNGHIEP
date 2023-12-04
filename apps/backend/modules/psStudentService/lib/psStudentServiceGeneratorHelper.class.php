<?php

/**
 * psStudentService module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psStudentService
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentServiceGeneratorHelper extends BasePsStudentServiceGeneratorHelper {

	public function linkToFilterReset2() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'registration' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}

	public function linkToFilterSearchReceivableStudent() {

		return '<button type="submit" rel="tooltip" data-placement="bottom" data-original-title="' . __ ( 'Search' ) . '" class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin" ><i class="fa-fw fa fa-search"></i></button>';
	}
	
	public function linkToDelete($object, $params)
	{
		if ($object->isNew())
		{
			return '';
		}
		
		//$label = '<i class="fa-fw fa fa-times txt-color-red" title="'.__($params['label'], array(), 'sf_admin').'"></i>';
		
		//return link_to($label, $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'class' => 'btn btn-xs btn-default pull-right'));
		
		return '<a data-toggle="modal" data-target="#confirmDeleteService" data-backdrop="static" class="btn btn-xs btn-default btn-delete-service pull-right" data-item="'.$object->getId().'"><i class="fa-fw fa fa-times txt-color-red" title="'.__('Delete').'"></i></a>';
	}
}
