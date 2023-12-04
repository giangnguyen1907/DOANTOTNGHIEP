<?php
require_once dirname ( __FILE__ ) . '/../lib/psTeacherClassGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psTeacherClassGeneratorHelper.class.php';

/**
 * psTeacherClass actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psTeacherClass
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psTeacherClassActions extends autoPsTeacherClassActions {

	/*
	 * public function executeEdit(sfWebRequest $request)
	 * {
	 * $this->ps_teacher_class = $this->getRoute()->getObject();
	 * $this->my_class = $this->ps_teacher_class->getMyClass();
	 * $this->forward404Unless ( myUser::checkAccessObject($this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL'), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );
	 * $this->form = $this->configuration->getForm($this->ps_teacher_class);
	 * $this->helper = new psTeacherClassGeneratorHelper();
	 * return $this->renderPartial('psTeacherClass/formSuccess',array('ps_teacher_class' => $this->ps_teacher_class, 'form' => $this->form,'my_class' => $this->my_class ,'configuration' => $this->configuration, 'helper' => $this->helper));
	 * //parent::executeEdit($request);
	 * }
	 * public function executeUpdate(sfWebRequest $request)
	 * {
	 * $this->ps_teacher_class = $this->getRoute()->getObject();
	 * $this->my_class = $this->ps_teacher_class->getMyClass();
	 * $this->forward404Unless ( myUser::checkAccessObject($this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL'), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );
	 * $this->form = $this->configuration->getForm($this->ps_teacher_class);
	 * $this->processForm($request, $this->form, $this->my_class);
	 * exit(0);
	 * return $this->renderPartial('psTeacherClass/formSuccess',array('ps_teacher_class' => $this->ps_teacher_class, 'form' => $this->form,'my_class' => $this->my_class ,'configuration' => $this->configuration, 'helper' => $this->helper));
	 * }
	 * public function executeNew(sfWebRequest $request)
	 * {
	 * $ps_class_id = $request->getParameter('ps_class_id');
	 * // Check thong so quyen doi voi lop
	 * if ($ps_class_id <= 0)
	 * $this->forward404Unless ($ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );
	 * $this->my_class = Doctrine::getTable('MyClass')->findOneById($ps_class_id);
	 * $this->forward404Unless ( myUser::checkAccessObject($this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL'), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );
	 * $this->ps_teacher_class = new PsTeacherClass();
	 * $this->ps_teacher_class->setMyClass($this->my_class);
	 * $this->form = $this->configuration->getForm($this->ps_teacher_class);
	 * $this->helper = new psTeacherClassGeneratorHelper();
	 * return $this->renderPartial('psTeacherClass/formSuccess',array('ps_teacher_class' => $this->ps_teacher_class, 'form' => $this->form, 'my_class' => $this->my_class, 'configuration' => $this->configuration, 'helper' => $this->helper));
	 * }
	 * public function executeCreate(sfWebRequest $request)
	 * {
	 * $this->form = $this->configuration->getForm();
	 * $formValues = $request->getParameter ( $this->form->getName () );
	 * $ps_class_id = isset ( $formValues ['ps_myclass_id'] ) ? $formValues ['ps_myclass_id'] : '';
	 * if ($ps_class_id <= 0)
	 * $this->forward404Unless ($ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );
	 * $my_class = Doctrine::getTable('MyClass')->findOneById($ps_class_id);
	 * $this->forward404Unless ( myUser::checkAccessObject($my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL'), sprintf ( 'Object (%s) does not exist .', $my_class->getId () ) );
	 * $this->processForm($request, $this->form, $my_class);
	 * exit(0);
	 * }
	 * protected function processForm(sfWebRequest $request, sfForm $form, $my_class = null)
	 * {
	 * $formValues = $request->getParameter ( $form->getName () );
	 * $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
	 * if ($form->isValid())
	 * {
	 * $notice = 'Assign the teacher successfully.';
	 * try {
	 * $ps_teacher_class = $form->save();
	 * echo $ps_teacher_class->getId();
	 * die('XXXXXXXXXXXXXXX');
	 * } catch (Doctrine_Validator_Exception $e) {
	 * die('EEEEEEEEÊ');
	 * $errorStack = $form->getObject()->getErrorStack();
	 * $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
	 * foreach ($errorStack as $field => $errors) {
	 * $message .= "$field (" . implode(", ", $errors) . "), ";
	 * }
	 * $message = trim($message, ', ');
	 * $formValues = $request->getParameter ( $form->getName () );
	 * $ps_class_id = isset ( $formValues ['ps_myclass_id'] ) ? $formValues ['ps_myclass_id'] : '';
	 * $this->getUser()->setFlash('error', $message);
	 * $this->redirect('@ps_class_edit?id='.$ps_class_id.'#pstab_3');
	 * }
	 * $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_teacher_class)));
	 * $this->getUser()->setFlash('notice', $notice);
	 * $this->redirect('@ps_class_edit?id='.$ps_teacher_class->getPsMyclassId().'#pstab_3');
	 * }
	 * else
	 * {
	 * $this->getUser()->setFlash('error', 'Teacher assignment failed.');
	 * $this->redirect('@ps_class_edit?id='.$my_class->getId().'#pstab_3');
	 * }
	 * }
	 */
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_teacher_class = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_teacher_class ) ) );

		$my_class = $ps_teacher_class->getMyClass ();

		$this->forward404Unless ( myUser::checkAccessObject ( $my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $my_class->getId () ) );

		if ($ps_teacher_class->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'Remove teacher assignment successfully.' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'Remove teacher assignment failed.' );
		}

		$this->redirect ( '@ps_class_edit?id=' . $my_class->getId () . '#pstab_3' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_teacher_class = $this->getRoute ()
			->getObject ();

		$this->my_class = $this->ps_teacher_class->getMyClass ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_teacher_class );

		$this->helper = new psTeacherClassGeneratorHelper ();

		return $this->renderPartial ( 'psTeacherClass/formSuccess', array (
				'ps_teacher_class' => $this->ps_teacher_class,
				'form' => $this->form,
				'my_class' => $this->my_class,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_teacher_class = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_teacher_class );

		$this->my_class = $this->ps_teacher_class->getMyClass ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );

		$this->helper = new psTeacherClassGeneratorHelper ();

		$this->processForm ( $request, $this->form, $this->my_class );

		/*
		 * return $this->renderPartial('psTeacherClass/formSuccess', array(
		 * 'ps_teacher_class' => $this->ps_teacher_class,
		 * 'form' => $this->form,
		 * 'my_class' => $this->my_class,
		 * 'configuration' => $this->configuration,
		 * 'helper' => $this->helper
		 * ));
		 * $this->setTemplate('edit');
		 */
	}

	public function executeNew(sfWebRequest $request) {

		$ps_class_id = $request->getParameter ( 'ps_class_id' );

		if ($ps_class_id <= 0)

			$this->forward404Unless ( $ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );

		$this->my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $ps_class_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->my_class->getId () ) );

		$ps_teacher_class = new PsTeacherClass ();

		$ps_teacher_class->setPsMyclassId ( $ps_class_id );

		$this->form = $this->configuration->getForm ( $ps_teacher_class );

		$this->ps_teacher_class = $this->form->getObject ();

		$this->helper = new psTeacherClassGeneratorHelper ();

		return $this->renderPartial ( 'psTeacherClass/formSuccess', array (
				'ps_teacher_class' => $this->ps_teacher_class,
				'form' => $this->form,
				'my_class' => $this->my_class,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'ps_teacher_class' );

		$ps_class_id = isset ( $formValues ['ps_myclass_id'] ) ? $formValues ['ps_myclass_id'] : '';

		if ($ps_class_id <= 0)
			$this->forward404Unless ( $ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );

		$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $ps_class_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $my_class->getId () ) );

		$this->ps_teacher_class = new PsTeacherClass ();

		$this->ps_teacher_class->setPsMyclassId ( $ps_class_id );

		$this->form = $this->configuration->getForm ( $this->ps_teacher_class );

		$this->processForm ( $request, $this->form, $my_class );

		exit ( 0 );
	}

	// Phan nhieu giao vien vao lop
	public function executeMembers(sfWebRequest $request){
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_class_id = $request->getParameter ( 'id' );
		
		$this->ps_class_id = $ps_class_id;
		
		$ps_teacher_class = $request->getParameter ( 'ps_teacher_class' );
		
		if ($request->isMethod ( 'post' )) {
			
			$class_id = $ps_teacher_class['ps_class_id'];
			$ps_member_ids = $ps_teacher_class['ps_member_id'];
			$start_at = $ps_teacher_class['start_at'];
			$stop_at = $ps_teacher_class['stop_at'];
			$primary_teacher = $ps_teacher_class['primary_teacher'];
			$user_id = myUser::getUserId();
			
			if($class_id > 0){
				$ps_customer_id = Doctrine::getTable('MyClass')->getMyClassByField($class_id,'ps_customer_id')->getPsCustomerId();
			}
			
			if (! myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' )) {
				if ($ps_customer_id != myUser::getPscustomerID ()) {
					$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
				}
			}
			
			if($start_at !='' && strtotime($stop_at) > strtotime($start_at)){
				
				foreach ($ps_member_ids as $member_id){
					
					$ps_teacher_class = new PsTeacherClass();
					$ps_teacher_class -> setPsMemberId($member_id);
					$ps_teacher_class -> setPsMyclassId($class_id);
					$ps_teacher_class -> setStartAt(date('Y-m-d',strtotime($start_at)));
					$ps_teacher_class -> setStopAt(date('Y-m-d',strtotime($stop_at)));
					$ps_teacher_class -> setIsActivated(PreSchool::ACTIVE);
					$ps_teacher_class -> setPrimaryTeacher($primary_teacher);
					$ps_teacher_class -> setUserUpdatedId($user_id);
					$ps_teacher_class -> setUserCreatedId($user_id);
					$ps_teacher_class -> save();
					
				}
				$this->getUser () ->setFlash ( 'notice', 'Assign the teacher successfully.' );
				$this->redirect ( '@ps_class_edit?id=' . $class_id . '#pstab_3' );
			}else{
				$this->getUser () ->setFlash ( 'error', 'Teacher assignment failed.' );
				$this->redirect ( '@ps_class_edit?id=' . $class_id . '#pstab_3' );
			}
			
		}
		
		$psClass = Doctrine::getTable('MyClass')->getMyClassByField($ps_class_id,'ps_customer_id,school_year_id');
		
		$ps_customer_id = $psClass -> getPsCustomerId();
		$school_year_id = $psClass -> getSchoolYearId();
		$schoolYear = Doctrine::getTable('PsSchoolYear')->findOneById($school_year_id);
		
		$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
			'model' => 'MyClass',
			'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array ('ps_myclass_id' => $ps_class_id ) ),
				'add_empty' => false ), array (
						'class' => 'select2',
						'style' => "width:100%;",
						'required' => true ) ) );
						
		$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true ) ) );
		
		$this->formFilter->setWidget ( 'ps_member_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsMember',
				'query' => Doctrine::getTable ( 'PsMember' )->setSQLTeachersNotInClass ($ps_class_id, $ps_customer_id,null ),
				'multiple' => true
		), array (
						'class' => 'select2',
						'style' => "width:100%;",
						'required' => true ) ) );
						
		$this->formFilter->setValidator ( 'ps_member_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsMember',
				'required' => true ) ) );
		
		$this->formFilter->setWidget ('primary_teacher', new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
						'class' => 'radiobox' ) ) );
		
		$this->formFilter->setWidget ( 'start_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Start at' ),
				'rel' => 'tooltip',
				'required' => true ),array(
						'class' => 'select2',
						'style' => "width:100%;",
						'required' => true) ) );
		
		$this->formFilter->setValidator ( 'start_at', new sfValidatorDate ( array (
				'required' => true ) ) );
		
		
		$this->formFilter->setWidget ( 'stop_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Stop at' ),
				'rel' => 'tooltip',
				'required' => true ) ) );
		
		$this->formFilter->setValidator ( 'stop_at', new sfValidatorDate ( array (
				'required' => true ) ) );
		
		$school_years_todate = date ( "d-m-Y", strtotime ($schoolYear->getToDate()) );
		
		$this->formFilter->setDefault ( 'start_at', date ( "d-m-Y", strtotime ($schoolYear->getFromDate()) ) );
		$this->formFilter->setDefault ( 'stop_at', $school_years_todate );
		$this->formFilter->setDefault ( 'primary_teacher', 0 );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'ps_teacher_class[%s]' );
		
	}
	
	protected function processForm(sfWebRequest $request, sfForm $form, $my_class = null) {

		$formValues = $request->getParameter ( $form->getName () );

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		
		if ($form->isValid ()) {
			
			$notice = 'Assign the teacher successfully.';

			$conn = Doctrine_Manager::connection ();

			try {
				
				$user_id = myUser::getUserId();
				
				$conn->beginTransaction ();
				
				/*
				$array_member_id = $form->getValue('ps_member_id');
				print_r($array_member_id); die;
				$class_id =  $form->getValue('ps_class_id');
				
				foreach ($array_member_id as $member_id){
					
					//echo $member_id.'<br>';
					
					$ps_teacher_class = Doctrine::getTable('PsTeacherClass')->checkTeacherClass($member_id,$class_id);
					
					// Neu da ton tai thi sua ngay bat dau va ket thuc 
					if($ps_teacher_class){
						$ps_teacher_class -> setStartAt($form->getValue('start_at'));
						$ps_teacher_class -> setStopAt($form->getValue('stop_at'));
						$ps_teacher_class -> setPrimaryTeacher($form->getValue('primary_teacher'));
						$ps_teacher_class -> setUserUpdatedId($user_id);
						$ps_teacher_class -> save();
					}else{
						$ps_teacher_class = new PsTeacherClass();
						$ps_teacher_class -> setPsMemberId($member_id);
						$ps_teacher_class -> setPsMyclassId($class_id);
						$ps_teacher_class -> setStartAt($form->getValue('start_at'));
						$ps_teacher_class -> setStopAt($form->getValue('stop_at'));
						$ps_teacher_class -> setIsActivated(PreSchool::ACTIVE);
						$ps_teacher_class -> setPrimaryTeacher($form->getValue('primary_teacher'));
						$ps_teacher_class -> setUserUpdatedId($user_id);
						$ps_teacher_class -> setUserCreatedId($user_id);
						$ps_teacher_class -> save();
					}
				}
				die;
				*/
				
				$ps_teacher_class = $form->save ();

				$conn->commit ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$conn->rollback ();

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";

				foreach ( $errorStack as $field => $errors ) {

					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}

				$message = trim ( $message, ', ' );

				$formValues = $request->getParameter ( $form->getName () );

				$ps_class_id = isset ( $formValues ['ps_myclass_id'] ) ? $formValues ['ps_myclass_id'] : '';

				$this->getUser ()
					->setFlash ( 'error', $message );

				$this->redirect ( '@ps_class_edit?id=' . $ps_class_id . '#pstab_3' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_teacher_class ) ) );

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_class_edit?id=' . $ps_teacher_class->getPsMyclassId () . '#pstab_3' );
		} else {

			$this->getUser ()
				->setFlash ( 'error', 'Teacher assignment failed.' );

			$this->redirect ( '@ps_class_edit?id=' . $my_class->getId () . '#pstab_3' );
		}
	}
}// End class
