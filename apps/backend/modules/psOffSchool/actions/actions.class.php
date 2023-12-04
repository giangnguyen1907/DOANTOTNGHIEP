<?php
require_once dirname ( __FILE__ ) . '/../lib/psOffSchoolGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psOffSchoolGeneratorHelper.class.php';

/**
 * psOffSchool actions.
 *
 * @package kidsschool.vn
 * @subpackage psOffSchool
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psOffSchoolActions extends autoPsOffSchoolActions {

    public function executeNew(sfWebRequest $request)
    {
        
        $student_id = $request->getParameter('id');
        //echo $id_student; die;
        if($student_id > 0){
            
            if (myUser::credentialPsCustomers('PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL')) {
                $ps_customer_id = $request->getParameter('ps_customer_id');
                
                if ($ps_customer_id > 0) {
                    $this->form->setDefault('ps_customer_id', $ps_customer_id);
                }
            }
            
            $this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );
            
            $this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );
            
            $student_class = $this->ps_student->getClassByDate ( time () );
            $ps_customer_id = $student_class->getPsCustomerId();
            $ps_workplace_id = $student_class->getPsWorkplaceId();
            $ps_class_id = $student_class->getMyclassId();
            
            $_ps_off_school = new PsOffSchool();
            
            $_ps_off_school->setStudentId($student_id);
            $_ps_off_school->setPsCustomerId($ps_customer_id);
            $_ps_off_school->setPsWorkplaceId($ps_workplace_id);
            $_ps_off_school->setPsClassId($ps_class_id);
            
            $this->form = $this->configuration->getForm($_ps_off_school);
            
            $this->ps_off_school = $this->form->getObject();
            
            $this->helper = new psOffSchoolGeneratorHelper ();
            
            // Tat ca phieu bao nghi cua hoc sinh
            $this->list_informed = Doctrine::getTable('PsOffSchool')->getStudentOffByStudentId($student_id, $ps_class_id);
            $ps_workplace = Doctrine::getTable('PsWorkPlaces')->getWorkPlacesByWorkPlacesId($ps_workplace_id);
            $this->config_time_receive_valid = $ps_workplace->getConfigTimeReceiveValid();
            
            return $this->renderPartial ( 'psOffSchool/formSuccess', array (
                'ps_off_school' => $this->ps_off_school,
                'form' => $this->form,
                'ps_student' => $this->ps_student,
                'configuration' => $this->configuration,
                'helper' => $this->helper,
                'list_informed'=>$this->list_informed,
                'config_time_receive_valid' => $this->config_time_receive_valid,
                'student_class' => $student_class ) );
            
        }else{
            
            $this->form = $this->configuration->getForm();
            // print_r($request->getParameter('ps_customer_id')); die;
            if (myUser::credentialPsCustomers('PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL')) {
                $ps_customer_id = $request->getParameter('ps_customer_id');
                
                if ($ps_customer_id > 0) {
                    $this->form->setDefault('ps_customer_id', $ps_customer_id);
                }
            }
            $this->ps_off_school = $this->form->getObject();
            
        }
    }

	public function executeOffSchoolActivated(sfWebRequest $request) {


		$id = $request->getParameter ( 'id' );
		
		$state = $request->getParameter ( 'state' );
		
		$off_school = Doctrine::getTable ( 'PsOffSchool' )->findOneById ( $id );

		if (myUser::checkAccessObject ( $off_school, 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' ) && ($state >= 0 && $state <= 2)) {
			
			$from_date = $off_school->getFromDate();
			
			$status = $off_school->getIsActivated();
			
			// Nếu đã qua ngày bắt đầu thì không update trạng thái
			if(strtotime($from_date) > time()){
				
				$off_school->setIsActivated ( $state );
				
				$off_school->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
						->getGuardUser ()
						->getId () );
				
				$off_school->save ();
				
			}
			
			return $this->renderPartial ( 'psOffSchool/ajax_activated', array (
					'ps_off_school' => $off_school
			) );
			
		}else {
			exit ( 0 );
		}
		
	}

	public function executeDetail(sfWebRequest $request) {

		// $this->ps_off_school = $this->getRoute()->getObject();
		$this->filter_value = $this->getFilters ();

		$student_off_id = $request->getParameter ( 'id' );

		if ($student_off_id <= 0) {

			$this->forward404Unless ( $student_off_id, sprintf ( 'Object does not exist.' ) );
		}

		// Lay thong tin don xin nghi
		$student_off_detail = Doctrine::getTable ( 'psOffSchool' )->getStudentOffById ( $student_off_id );
		$this->student_off_detail = $student_off_detail;
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->ps_off_school = $this->form->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_off_school, 'PS_STUDENT_OFF_SCHOOL_ADD' ), sprintf ( 'Object does not exist.' ) );
		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_off_school = $this->getRoute ()->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_off_school, 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_off_school );
		$this->student = $this->ps_off_school->getStudent ();
		$this->class = $this->ps_off_school->getMyClass ();
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$this->ps_off_school = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_off_school, 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_off_school' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsOffSchool' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsOffSchool' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_off_school' );
	}

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
//             $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
            
            try {
            	
            	if($form->getObject()->isNew()){
            		
            		if(strtotime($form->getValue('date')['from']) >= strtotime(date('Y-m-d')) ){
            			
            			$notice = 'The item was created successfully.';
            			
            			$student_id = $form->getValue('student_id');
            			
            			$date_off_school = Doctrine::getTable('PsOffSchool')->getStudentOffSchoolByStudentId($student_id);
            			
            			if($date_off_school){
	            			// Nếu ngày xin nghỉ gần đây nhất < ngày bắt đầu nghỉ mới
	            			if(strtotime($date_off_school->getToDate()) < strtotime($form->getValue('date')['from']) ){
	            				$ps_off_school = $form->save();
	            				$ps_off_school_id = $ps_off_school->getId();
	            			}elseif(strtotime($form->getValue('date')['to']) < strtotime($date_off_school->getFromDate()) ){
	            				// Ngày bắt đầu xin nghỉ phải < ngày bắt đầu nghỉ của phiếu trước
	            				$ps_off_school = $form->save();
	            				$ps_off_school_id = $ps_off_school->getId();
	            			}else{
	            				$error_new = 1;
	            			}
            			}else{
            				$ps_off_school = $form->save();
            				$ps_off_school_id = $ps_off_school->getId();
            			}
            		}else{
            			$error_new = 1;
            		}
            		
            	}else{
            		
            		//echo $form;die;
            		
            		$notice = 'The item was updated successfully.';
            		
            		$ps_off_school_id = $form->getObject()->getId();
            		
            		$is_activated = $form->getObject()->getIsActivated();
            		$from_date = $form->getObject()->getFromDate();
            		$to_date = $form->getObject()->getToDate();
            		
            		if(strtotime($from_date) >= strtotime(date('Y-m-d')) && strtotime($to_date) >= strtotime(date('Y-m-d')) ){ // Nếu ngày bắt đầu > ngày hiện tại
            			$ps_off_school = $form->save();
            		}else{
            			$error = 1;
            			//$notice = $this->getContext()->getI18N()->__('The item has not been saved because value isseted');
            		}
            		
            		if($is_activated != 1){
            			$form->getObject()->setIsActivated($form->getValue('is_activated'));
            			$ps_off_school = $form->getObject()->save();
            		}
            		
            	}
                
            } catch (Doctrine_Validator_Exception $e) {
                
                $errorStack = $form->getObject()->getErrorStack();
                
                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');
                
                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }
            
            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_off_school)));
            
            if($request->getParameter('url_ps_students') == "url_ps_students"){
            	if($error_new == 1){
            		$error_text = $this->getContext()->getI18N()->__('The item has not been saved because value isseted');
            		$this->getUser()->setFlash('error', $error_text);
            	}else{
	                $notice = $this->getContext()->getI18N()->__("Created off school of student");
	                $this->getUser()->setFlash('notice', $notice);
            	}
                $this->redirect('@ps_students');
            }elseif ($request->hasParameter('_save_and_add'))
            {
            	$this->getUser()->setFlash('notice', $notice.' You can add another one below.');
            	
                $this->redirect('@ps_off_school_new');
            }
            else
            {
            	if($error == 1){
            		$this->getUser()->setFlash('error', $this->getContext()->getI18N()->__('The item has not been saved due to some errors. Date at invalid'));
            		$this->redirect('@ps_off_school_edit?id='.$ps_off_school_id);
            	}elseif($error_new == 1){
            		$error_text = $this->getContext()->getI18N()->__('The item has not been saved because value isseted');
            		$this->getUser()->setFlash('error', $error_text);
            		$this->redirect('@ps_off_school_new');
            	}else{
            		$this->getUser()->setFlash('notice', $notice);
            		$this->redirect('@ps_off_school_edit?id='.$ps_off_school_id);
            	}
            	
            }
        }
        else
        {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }
}
