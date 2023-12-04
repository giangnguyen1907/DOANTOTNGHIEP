<?php
require_once dirname(__FILE__) . '/../lib/psServiceSplitsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/psServiceSplitsGeneratorHelper.class.php';

/**
 * psServiceSplits actions.
 *
 * @package kidsschool.vn
 * @subpackage psServiceSplits
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceSplitsActions extends autoPsServiceSplitsActions {
	
	public function executeIndex(sfWebRequest $request) {
	    $this->forward404Unless(false, sprintf('Object does not exist.'));    
	}
  
	public function executeSplitValue(sfWebRequest $request) {
        
    	$service_id = $request->getParameter('sid');
        
    	$percent 	= $request->getParameter('split_value');
    	
    	$percent = str_replace(",", ".", $percent);
        
        if ($service_id <= 0)
        	$this->forward404Unless($service_id, sprintf('Object does not exist.'));
        
        $this->service = Doctrine::getTable('Service')->findOneById($service_id);
        
        $this->forward404Unless(myUser::checkAccessObject($this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL') && ($this->service->getEnableRoll() != PreSchool::SERVICE_TYPE_SCHEDULE), sprintf('Object does not exist.'));
        
        $service_detail = $this->service->getServiceDetailByDate(time());
        
        $amount = $service_detail->getAmount();
        
        $percent_price = ($amount * $service_detail->getByNumber() * $percent) / 100;
        
        // => $percent = $percent_price * 100/$amount * $service_detail->getByNumber()
        
        return $this->renderPartial('psServiceSplits/form_field_price', array(
            'value' => $percent_price,
        	'percent' => $percent
        ));
    }

    public function executeNew(sfWebRequest $request)
    {
        $service_id = $request->getParameter('sid');
        
        if ($service_id <= 0)
            $this->forward404Unless($service_id, sprintf('Object does not exist.'));
        
        $this->service = Doctrine::getTable('Service')->findOneById($service_id);
        
        $this->forward404Unless(myUser::checkAccessObject($this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL')&& ($this->service->getEnableRoll() != PreSchool::SERVICE_TYPE_SCHEDULE), sprintf('Object does not exist.'));
        
        $this->form = $this->configuration->getForm();
        
        $this->form->setDefault('service_id', $service_id);
        
        $this->service_split = $this->form->getObject();
        
        $this->service_split->setServiceId($service_id);
        
        $this->service_split->setService($this->service);
        
        $this->ps_service_splits = $this->service->getAllServiceSplit();
        
        // Chi tiet dich vu
        if ($this->ps_service_splits)
            $this->service_detail = $this->service->getServiceDetailByDate(time());
        
        // Load service_splits of service_id
    }

    public function executeEdit(sfWebRequest $request)
    {
        $this->service_split = $this->getRoute()->getObject();
        
        $this->service = $this->service_split->getService();
        
        $this->forward404Unless(myUser::checkAccessObject($this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL')&& ($this->service->getEnableRoll() != PreSchool::SERVICE_TYPE_SCHEDULE), sprintf('Object does not exist.'));
     
        $this->form = $this->configuration->getForm($this->service_split);
        
        $this->ps_service_splits = $this->service->getAllServiceSplit();
        
        // Chi tiet dich vu
        if ($this->ps_service_splits)
            $this->service_detail = $this->service->getServiceDetailByDate(time());
        
        $this->setTemplate('new');
        // Load service_splits of service_id
    }

    public function executeDelete(sfWebRequest $request)
    {
        $request->checkCSRFProtection();
        
        $this->service = $this->getRoute()
            ->getObject()
            ->getService();
        
        $this->forward404Unless(myUser::checkAccessObject($this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL')&& ($this->service->getEnableRoll() != PreSchool::SERVICE_TYPE_SCHEDULE), sprintf('Object does not exist.'));
        
        $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array(
            'object' => $this->getRoute()
                ->getObject()
        )));
        
        if ($this->getRoute()
            ->getObject()
            ->delete()) {
            $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
        }
        
        $this->redirect('@ps_service_splits_new?sid=' . $this->service->getId());
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        
        if ($form->isValid()) {
            
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
            
            try {
                
                $service_split = $form->save();
            } catch (Doctrine_Validator_Exception $e) {
                
                $errorStack = $form->getObject()->getErrorStack();
                
                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                
                $message = trim($message, ', ');
                
                $this->getUser()->setFlash('error', $message);
                
                return sfView::SUCCESS;
            }
            
            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array(
                'object' => $service_split
            )));
            
            $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');
            
            $this->redirect('@ps_service_splits_new?sid=' . $form->getObject()
                ->getServiceId());
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }
}
