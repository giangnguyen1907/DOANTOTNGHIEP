<?php

/**
 * psUserDepartments module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psUserDepartments
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psUserDepartmentsGeneratorHelper extends BasePsUserDepartmentsGeneratorHelper
{
	public function getUrlForAction($action)
	{
		return 'list' == $action ? 'sf_guard_user' : 'ps_user_departments_'.$action;
	}
	
	public function linkToBack($params)
	{
		
		$label = '<i class="fa-fw fa fa-undo" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';
		
		return link_to($label.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('class' => 'btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left'));
	}
}
