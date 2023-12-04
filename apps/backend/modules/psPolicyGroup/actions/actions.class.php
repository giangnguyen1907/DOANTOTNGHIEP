<?php
require_once dirname ( __FILE__ ) . '/../lib/psPolicyGroupGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psPolicyGroupGeneratorHelper.class.php';

/**
 * psClassRooms actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psClassRooms
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psPolicyGroupActions extends autopsPolicyGroupActions {

    // Update vào csdl
    // public function executeUpdate(sfWebRequest $request) {

    //     $formValues = $request->getParameter ( 'json_service' );

    //     $list_service = json_encode($formValues);

    //     echo $list_service;die;

    //     $this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

    //     $this->ps_policy_group = $this->getRoute ()->getObject ();

    //     $this->form = $this->configuration->getForm ( $this->ps_policy_group );

    //     $this->processForm ( $request, $this->form );

    //     $this->setTemplate ( 'edit' );

    // }
}
