<?php
require_once dirname ( __FILE__ ) . '/../lib/psRegularityGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psRegularityGeneratorHelper.class.php';

/**
 * psRegularity actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psRegularity
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRegularityActions extends autopsRegularityActions {

    //Thêm mới vào CSDL
    public function executeCreate(sfWebRequest $request) 
    {
        $formValues = $request->getParameter ( 'ps_regularity' );

        $is_default = $formValues['is_default'];

        if($is_default == '1'){

            $records = Doctrine_Query::create ()->from ( 'PsRegularity' )
                ->execute ();

            foreach($records as $rc){
                $rc->setIsDefault(0);
                $rc->save();
            }
        }

        $ps_regularity = new PsRegularity ();

        $this->form = $this->configuration->getForm ( $ps_regularity );

        $this->ps_regularity = $this->form->getObject ();

        $this->processForm ( $request, $this->form );

        $this->setTemplate ( 'new' );
    }

    // Update vào csdl
    public function executeUpdate(sfWebRequest $request) {

        $formValues = $request->getParameter ( 'ps_regularity' );

        $is_default = $formValues['is_default'];

        if($is_default == '1'){

            $records = Doctrine_Query::create ()->from ( 'PsRegularity' )
                ->execute ();

            foreach($records as $rc){
                $rc->setIsDefault(0);
                $rc->save();
            }
        }

        $this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

        $this->ps_regularity = $this->getRoute ()->getObject ();

        $this->form = $this->configuration->getForm ( $this->ps_regularity );

        $this->processForm ( $request, $this->form );

        $this->setTemplate ( 'edit' );

    }


}
