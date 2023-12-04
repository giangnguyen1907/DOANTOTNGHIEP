<?php

/**
 * psMenusImports module helper.
 *
 * @package    KidsSchool.vn
 * @subpackage psMenusImports
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMenusImportsGeneratorHelper extends BasePsMenusImportsGeneratorHelper
{
    
    public function linkToFilterReset2() {
        
        $url = url_for ( '@ps_menus_imports_by_week');
        
        return $link = '<a class="btn btn-sm btn-default btn-filter-reset btn-psadmin" href="' . $url . '"><i class="fa-fw fa fa-refresh txt-color-blue" title="' . __ ( 'Reset', array (), 'sf_admin'  ) . '"></i>.'. __ ( 'Reset', array (), 'sf_admin'  ).'</a>';
    }
}
