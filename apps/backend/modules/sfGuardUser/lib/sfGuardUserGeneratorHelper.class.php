<?php

/**
 * sfGuardUser module helper.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: sfGuardUserGeneratorHelper.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserGeneratorHelper extends BaseSfGuardUserGeneratorHelper {
	
	public function linkToEditManager($object, $params) {
		return link_to('<i class="fa-fw fa fa-pencil txt-color-orange" title="'.__($params['label'], array(), 'sf_admin').'"></i>', 'ps_user_departments_edit', $object, array('class' => 'btn btn-xs btn-default btn-edit-td-action'));
	}
}
