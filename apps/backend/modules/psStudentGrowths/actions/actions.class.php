<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentGrowthsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentGrowthsGeneratorHelper.class.php';

/**
 * psStudentGrowths actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psStudentGrowths
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentGrowthsActions extends autoPsStudentGrowthsActions {

	public function executeClassForFilter(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$c_id = $request->getParameter ( 'c_id' );

			$w_id = $request->getParameter ( 'w_id' );

			$y_id = $request->getParameter ( 'y_id' );

			$o_id = $request->getParameter ( 'o_id' );

			$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
					'ps_customer_id' => $c_id,
					'ps_workplace_id' => $w_id,
					'ps_school_year_id' => $y_id,
					'ps_obj_group_id' => $o_id,
					'is_activated' => PreSchool::ACTIVE ) );

			return $this->renderPartial ( 'psStudentGrowths/option_select_class', array (
					'option_select' => $this->ps_class ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeExaminationForFilter(sfWebRequest $request) {

		$cid = $request->getParameter ( 'c_id' );

		$wid = $request->getParameter ( 'w_id' );

		$yid = $request->getParameter ( 'y_id' );

		if ($wid == '') {
			$member_id = myUser::getUser ()->getMemberId ();
			$wid = myUser::getWorkPlaceId ( $member_id );
		}

		$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $wid );

		// Cần check quyền xác thực $wid có thuộc User ko
		if (! myUser::checkAccessObject ( $ps_workplace, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			exit ( 0 );
		} else {

			$this->ps_examination = Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
					'ps_customer_id' => $cid,
					'ps_workplace_id' => $wid,
					'ps_school_year_id' => $yid ) )
				->execute ();

			return $this->renderPartial ( 'psStudentGrowths/option_select_examination', array (
					'option_select' => $this->ps_examination ) );
		}
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			$check_new = $notice = $form->getObject ()->isNew ();
			$user_id = myUser::getUserId();
			$notice = $check_new ? 'The item was created successfully.' : 'The item was updated successfully.';
			$batbuocnhap = 0;
			
			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();
				
				$weight = str_replace(",",".",$form->getValue ( 'weight' ));
				
				$height = str_replace(",",".",$form->getValue ( 'height' ));
				
				if($height <= 0){
					$height = null;
				}
				if($weight <= 0){
					$weight = null;
				}
				
				$student_id = $form->getValue ( 'student_id' );
				
				$examination = $form->getValue ( 'examination_id' );
				
				$index_tooth = $form->getValue ( 'index_tooth' );
				
				$index_throat = $form->getValue ( 'index_throat' );
				
				$index_eye = $form->getValue ( 'index_eye' );
				
				$index_heart = $form->getValue ( 'index_heart' );
				
				$index_lung = $form->getValue ( 'index_lung' );
				
				$index_skin = $form->getValue ( 'index_skin' );
				
				// Neu ca chieu cao hoac can nang ko de trong
				if($weight > 0 || $height > 0 || $index_tooth != '' || $index_throat != '' || $index_eye != '' || $index_heart != '' || $index_lung != '' || $index_skin != ''){
				
					$form->getObject()->setStudentId($student_id);
						
					$form->getObject()->setHeight($height);
					
					$form->getObject()->setWeight($weight);
					
					$form->getObject()->setIndexTooth($form->getValue ( 'index_tooth' ));
					
					$form->getObject()->setIndexThroat($form->getValue ( 'index_throat' ));
					
					$form->getObject()->setIndexEye($form->getValue ( 'index_eye' ));
					
					$form->getObject()->setIndexHeart($form->getValue ( 'index_heart' ));
					
					$form->getObject()->setIndexLung($form->getValue ( 'index_lung' ));
					
					$form->getObject()->setIndexSkin($form->getValue ( 'index_skin' ));
					
					$form->getObject()->setExaminationId($form->getValue ( 'examination_id' ));
					
					$form->getObject()->setPeopleMake($form->getValue ( 'people_make' ));
					
					$form->getObject()->setNote($form->getValue ( 'note' ));
					
					$form->getObject()->setUserUpdatedId($user_id);
					
					if($form->getObject()->isNew()){
						$form->getObject()->setUserCreatedId($user_id);
					}
					
					$form->getObject()->save();
					
					//$examination = $ps_student_growths->getExaminationId ();
	
					$input_date = Doctrine::getTable ( 'PsExamination' )->getSQLByPsExaminationId ( 'id,input_date_at', $examination );
	
					$student = Doctrine::getTable ( 'Student' )->getStudentByField ($student_id, 'id,sex,birthday');
	
					$sex = ( int ) $student->getSex ();
					
					// lay thong tin hoc sinh
					$student_bmi = Doctrine::getTable ( 'PsStudentBmi' )->getStudentBmi ($sex);
					
					$inputdate = $input_date->getInputDateAt ();
	
					$month = PreSchool::getMonthYear ( $student->getBirthday (), $inputdate );
	
					//echo $month; die;
					
					foreach ( $student_bmi as $data ) {
	
						$data_sex = ( int ) $data->getSex ();
	
						$data_month = ( int ) $data->getIsMonth ();
	
						if ($sex == $data_sex and $month >= $data_month) {
							// So sanh chieu cao voi bang chuan
							if($height > 0){
								if ($data->getMinHeight1 () > 0 && $height < $data->getMinHeight1 ()) {
									$index_height = - 2;
								} elseif ($height < $data->getMinHeight () && $height >= $data->getMinHeight1 ()) {
									$index_height = - 1;
								} elseif ($height >= $data->getMaxHeight ()) {
									$index_height = 1;
								} else {
									$index_height = 0;
								}
							}else{$index_height = null;}
							// So sanh can nang voi bang chuan
							if($weight > 0){
								if ($data->getMinWeight1 () > 0 && $weight < $data->getMinWeight1 ()) {
									$index_weight = - 2;
								} elseif ($weight < $data->getMinWeight () && $weight >= $data->getMinWeight1 ()) {
									$index_weight = - 1;
								} elseif ($weight >= $data->getMaxWeight () && $weight < $data->getMaxWeight1 ()) {
									$index_weight = 1;
								}elseif ($weight >= $data->getMaxWeight1 ()) {
									$index_weight = 2;
								}else {
									$index_weight = 0;
								}
							}else{$index_weight = null;}
							
							break;
						}
					}
	
					$form->getObject()->setIndexHeight ( $index_height );
	
					$form->getObject()->setIndexWeight ( $index_weight );
	
					$form->getObject()->setIndexAge ( $month );
	
					$form->getObject()->save ();
					
					$growth_id = $form->getObject()->getId();
					
				}else{
					$batbuocnhap = 1;
				}
				
				$conn->commit ();
				
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";

				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}

				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );

				$conn->rollback ();

				return sfView::SUCCESS;
			}
			
			if($batbuocnhap == 1){
				//$this->redirect('@ps_student_growths_new?student_id='.$student_id.'&date='.$examination);
				$this->getUser () ->setFlash ( 'error', $this->getContext ()->getI18N ()->__('You can input height or weight'));
			}else{
			
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
						'object' => $ps_student_growths ) ) );
	
				if ($request->hasParameter ( '_save_and_add' )) {
	
					$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );
	
					$this->redirect ( '@ps_student_growths_new' );
				} else {
	
					$this->getUser ()
						->setFlash ( 'notice', $notice );
	
					$this->redirect('@ps_student_growths_edit?id='.$growth_id);
	
				}
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	// Lay tat ca du lieu trong bang luu tru thong tin kham
	protected function updategrowth() {

		$get_all_data = Doctrine_Core::getTable ( 'PsStudentGrowths' )->getAllDataStudentsByGrowth ();
		$student_bmi = Doctrine::getTable ( 'PsStudentBmi' )->getStudentBmi ();

		foreach ( $get_all_data as $get_data ) {
			$student_sex = $get_data->getSex (); // lay gioi tinh
			$student_height = $get_data->getHeight (); // lay gia tri chieu cao
			$student_weight = $get_data->getWeight (); // lay gia tri can nang
			$student_month = $get_data->getIndexAge (); // lay so thang kham

			foreach ( $student_bmi as $data ) {

				$data_sex = ( int ) $data->getSex ();

				$data_month = ( int ) $data->getIsMonth ();

				if ($student_sex == $data_sex && $student_month <= $data_month) {
					// So sanh chieu cao voi bang chuan
					if ($data->getMinHeight1 () > 0 && $student_height < $data->getMinHeight1 ()) {
						$index_height = - 2;
					} elseif ($student_height < $data->getMinHeight () && $student_height > $data->getMinHeight1 ()) {
						$index_height = - 1;
					} elseif ($student_height > $data->getMaxHeight ()) {
						$index_height = 1;
					} else {
						$index_height = 0;
					}
					// So sanh can nang voi bang chuan
					if ($data->getMinWeight1 () > 0 && $weight < $data->getMinWeight1 ()) {
						$index_weight = - 2;
					} elseif ($weight < $data->getMinWeight () && $weight > $data->getMinWeight1 ()) {
						$index_weight = - 1;
					} elseif ($weight > $data->getMaxWeight ()) {
						$index_weight = 1;
					} else {
						$index_weight = 0;
					}

					break;
				}
			}

			$get_data->setIndexHeight ( $index_height );

			$get_data->setIndexWeight ( $index_weight );

			$get_data->setIndexAge ( $student_month );

			$get_data->save ();
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$this->filter_list_student = array ();

		$student_id = $request->getParameter ( 'student_id' );

		$examination_id = $request->getParameter ( 'date' );

		if ($examination_id > 0) {

			$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );

			$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );

			$ps_school_year_id = $exami->getSchoolYearId ();

			$ps_customer_id = $exami->getPsCustomerId ();

			$ps_workplace_id = $exami->getPsWorkplaceId ();
		} else {
			$ps_school_year_id = $ps_customer_id = $ps_workplace_id = null;

			$tracked_at = date ( 'Y-m-d' );
		}
		$class_id = null;
		// echo $examination_id; die();

		$history_filter = $request->getParameter ( 'student_filter' );

		if ($history_filter) {

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$this->examination_id = isset ( $history_filter ['examination_id'] ) ? $history_filter ['examination_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if ($student_id > 0) {

			$student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $student_id );

			if ($student) {

				// Check role
				$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$examination_id = $request->getParameter ( 'date' );

				if ($examination_id > 0) {
					$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
					$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
				} else {
					$tracked_at = date ( 'Y-m-d' );
				}
				// echo $examination_id; die;
				// BEGIN: Lay thong lien quan cua hoc sinh
				$student_class = Doctrine::getTable ( 'StudentClass' )->getClassByStudent ( $student_id, $tracked_at );

				if ($student_class) {
					$ps_customer_id = $student_class->getPsCustomerId ();

					$class_id = $student_class->getClassId ();

					$ps_workplace_id = $student_class->getPsWorkplaceId ();
				} else {
					$this->getUser ()
						->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
				}
				// END: Lay thong lien quan cua hoc sinh

				$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

				$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

				$this->formFilter->setDefault ( 'class_id', $class_id );

				$this->formFilter->setDefault ( 'examination_id', $examination_id );

				// Lay cac hoc sinh chua thong tin Y te theo lop
				$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

				// $this->form->getObject()->setStudentId($student_id);

				$this->form->getObject () ->setStudentId ( $student_id );
				
				$this->form->getObject () ->setExaminationId ( $examination_id );

				$this->form = $this->configuration->getForm ( $this->form->getObject () );

				$this->form->setDefault ( 'student_id', $student_id );

				$this->form->setDefault ( 'examination_id', $examination_id );

				$this->form->setDefault ( 'student_name', $student->getFirstName () . ' ' . $student->getLastName () );
			} else {

				$this->getUser ()
					->setFlash ( 'warning', 'This student does not exist' );
			}
		}

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$examination_id = $value_student_filter ['examination_id'];

			if ($examination_id > 0) {
				$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
				$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
			} else {
				$tracked_at = date ( 'Y-m-d' );
			}

			$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );
		}

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		if ($ps_school_year_id == '') {
			$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_school_year_id' => $ps_school_year_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( '-All Examination-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All Examination-' ) ) ) );
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination

			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		}

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorPass ( array (
				'required' => true ) ) );
		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorPass ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_school_year_id', $ps_school_year_id );

		$this->formFilter->setDefault ( 'class_id', $class_id );

		$this->formFilter->setDefault ( 'examination_id', $examination_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		$_ps_student_growths = new PsStudentGrowths ();

		$_ps_student_growths->setStudentId ( $student_id );

		$_ps_student_growths->setExaminationId ( $examination_id );

		$this->form = $this->configuration->getForm ( $_ps_student_growths );

		$this->ps_student_growths = $this->form->getObject ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_student_growths = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$examination_id = null;

		$class_id = null;

		$this->filter_list_student = array ();

		$history_filter = $request->getParameter ( $this->form->getName () );

		$student_id = $history_filter ['student_id'];

		$student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $student_id );

		if ($student) {

			$ps_customer_id = $student->getPsCustomerId ();

			// Check role
			$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$examination_id = $history_filter ['examination_id'];

			if ($examination_id > 0) {
				$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
				$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
			} else {
				$tracked_at = date ( 'Y-m-d' );
			}

			// echo $examination_id; die;
			// BEGIN: Lay thong lien quan cua hoc sinh
			$student_class = Doctrine::getTable ( 'StudentClass' )->getClassByStudent ( $student_id, $tracked_at );

			if ($student_class) {
				$ps_customer_id = $student_class->getPsCustomerId ();

				$class_id = $student_class->getClassId ();

				$ps_workplace_id = $student_class->getPsWorkplaceId ();
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
			}
			// END: Lay thong lien quan cua hoc sinh

			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

			$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

			$this->formFilter->setDefault ( 'class_id', $class_id );

			$this->formFilter->setDefault ( 'examination_id', $examination_id );

			// Lay cac hoc sinh chua thong tin Y te theo lop
			$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

			// $this->form->getObject()->setStudentId($student_id);

			$this->form->getObject ()
				->setStudentId ( $student_id );

			$this->form = $this->configuration->getForm ( $this->form->getObject () );

			$this->form->setDefault ( 'student_id', $student_id );

			$this->form->setDefault ( 'examination_id', $examination_id );

			$this->form->setDefault ( 'student_name', $student->getFirstName () . ' ' . $student->getLastName () );
		}

		if ($history_filter) {

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$examination_id = $this->examination_id = isset ( $history_filter ['examination_id'] ) ? $history_filter ['examination_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if ($examination_id > 0) {
			$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
			$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
		} else {
			$tracked_at = date ( 'Y-m-d' );
		}

		$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_school_year_id' => $ps_school_year_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-All Examination-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All Examination-' ) ) ) );
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination

			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		}

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorPass ( array (
				'required' => true ) ) );
		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorPass ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'class_id', $class_id );

		$this->formFilter->setDefault ( 'examination_id', $examination_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_student_growths = $this->getRoute ()
			->getObject ();

		$student = $this->ps_student_growths->getStudent ();

		$examination = $this->ps_student_growths->getPsExamination ();

		if ($examination) {

			$examination_id = $examination->getId ();

			$ps_customer_id = $examination->getPsCustomerId ();

			$ps_workplace_id = $examination->getPsWorkplaceId ();

			$ps_school_year_id = $examination->getSchoolYearId ();

			$tracked_at = $examination ? $examination->getInputDateAt () : date ( 'Y-m-d' );
		} else {

			$ps_customer_id = null;

			$ps_school_year_id = null;

			$class_id = null;

			$tracked_at = date ( 'Y-m-d' );
		}

		$student_id = $student->getId ();

		$this->formFilter = new sfFormFilter ();

		$this->form = $this->configuration->getForm ( $this->ps_student_growths );

		$this->filter_list_student = array ();

		// Check role
		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$student_class = Doctrine::getTable ( 'StudentClass' )->getClassByStudent ( $student_id, $tracked_at );

		if ($student_class) {

			$ps_customer_id = $student_class->getPsCustomerId ();

			$class_id = $student_class->getClassId ();

			$ps_workplace_id = $student_class->getPsWorkplaceId ();
		}

		$examination_id = $this->ps_student_growths->getExaminationId ();

		$this->formFilter->setDefault ( 'ps_school_year_id', $ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $class_id );

		$this->formFilter->setDefault ( 'examination_id', $examination_id );

		if ($examination_id > 0) {
			$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
			$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
		} else {
			$tracked_at = date ( 'Y-m-d' );
		}

		$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$class_id = $value_student_filter ['class_id'];

			$examination_id = $value_student_filter ['examination_id'];
			if ($examination_id > 0) {
				$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
				$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
			} else {
				$tracked_at = date ( 'Y-m-d' );
			}
			$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );
		}

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_school_year_id' => $ps_school_year_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_school_year_id'=>$ps_school_year_id,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-All Examination-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All Examination-' ) ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination

			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => true ) ) );
		}

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorPass ( array (
				'required' => true ) ) );
		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorPass ( array (
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'examination_id', new sfValidatorPass ( array (
				'required' => true ) ) );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );

			if ($this->formFilter->isValid ()) {
				$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );
			}
		}

		$this->form->setDefault ( 'student_name', $student->getFirstName () . ' ' . $student->getLastName () );

		$this->setTemplate ( 'new' );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_student_growths = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_student_growths );

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$examination_id = null;

		$class_id = null;

		$this->filter_list_student = array ();

		$history_filter = $request->getParameter ( $this->form->getName () );

		$student_id = $history_filter ['student_id'];

		$student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $student_id );

		if ($student) {

			$ps_customer_id = $student->getPsCustomerId ();

			// Check role
			$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$examination_id = $history_filter ['examination_id'];

			if ($examination_id > 0) {
				$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
				$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
			} else {
				$tracked_at = date ( 'Y-m-d' );
			}

			// BEGIN: Lay thong lien quan cua hoc sinh
			$student_class = Doctrine::getTable ( 'StudentClass' )->getClassByStudent ( $student_id, $tracked_at );

			if ($student_class) {
				$ps_customer_id = $student_class->getPsCustomerId ();

				$class_id = $student_class->getClassId ();

				$ps_workplace_id = $student_class->getPsWorkplaceId ();
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
			}

			// END: Lay thong lien quan cua hoc sinh

			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

			$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

			$this->formFilter->setDefault ( 'class_id', $class_id );

			$this->formFilter->setDefault ( 'examination_id', $examination_id );

			// Lay cac hoc sinh chua thong tin Y te theo lop
			$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

			// $this->form->getObject()->setStudentId($student_id);

			$this->form->getObject ()
				->setStudentId ( $student_id );

			$this->form = $this->configuration->getForm ( $this->form->getObject () );

			$this->form->setDefault ( 'student_id', $student_id );

			$this->form->setDefault ( 'examination_id', $examination_id );

			$this->form->setDefault ( 'student_name', $student->getFirstName () . ' ' . $student->getLastName () );
		}

		if ($history_filter) {

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$examination_id = $this->examination_id = isset ( $history_filter ['examination_id'] ) ? $history_filter ['examination_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if ($examination_id > 0) {
			$exami = Doctrine::getTable ( 'PsExamination' )->findOneById ( $examination_id );
			$tracked_at = $exami ? $exami->getInputDateAt () : date ( 'Y-m-d' );
		} else {
			$tracked_at = date ( 'Y-m-d' );
		}

		$this->filter_list_student = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsByClassId ( $class_id, $tracked_at, $examination_id );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_school_year_id' => $ps_school_year_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => _ ( '-All Examination-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All Examination-' ) ) ) );
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters by examination

			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => false ) ) );
		}

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorPass ( array (
				'required' => true ) ) );
		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorPass ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'class_id', $class_id );

		$this->formFilter->setDefault ( 'examination_id', $examination_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	/*
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->ps_student_growths = $this->getRoute ()
			->getObject ();

		$student = $this->ps_student_growths->getStudent ();

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			if ($this->getRoute () ->getObject () ->delete ()) {

				$student_growths_before = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthBeforeByStudentId ( $student->getId (), $this->ps_student_growths->getInputDateAt () );
				$student_growths_after = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthAfterByStudentId ( $student->getId (), $this->ps_student_growths->getInputDateAt () );
				if ($student_growths_before && $student_growths_after) {
					if ($student_growths_after->getHeight () == $student_growths_before->getHeight ()) {
						$index_height = 0;
					} else {
						$index_height = ($student_growths_after->getHeight () > $student_growths_before->getHeight ()) ? 1 : - 1;
					}
					if ($student_growths_after->getWeight () == $student_growths_before->getWeight ()) {
						$index_weight = 0;
					} else {
						$index_weight = ($student_growths_after->getWeight () > $student_growths_before->getWeight ()) ? 1 : - 1;
					}
					$student_growths_after->setIndexHeight ( $index_height );
					$student_growths_after->setIndexWeight ( $index_weight );
					$student_growths_after->save ();
				}
				if (! $student_growths_before && $student_growths_after) {
					$student_growths_after->setIndexHeight ( null );
					$student_growths_after->setIndexWeight ( null );
					$student_growths_after->save ();
				}

				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'The item was deleted failed.' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'The item was deleted failed.' );
			throw new Exception ( $e->getMessage () );
			$conn->rollback ();
		}
		$this->redirect ( '@ps_student_growths' );
	}
	*/
	/*
	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsStudentGrowths' )
			->whereIn ( 'id', $ids )
			->execute ();

		$conn = Doctrine_Manager::connection ();
		try {
			$conn->beginTransaction ();

			foreach ( $records as $record ) {
				
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );

				$record->delete ();

				$student_growths_before = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthBeforeByStudentId ( $record->getStudentId (), $record->getInputDateAt () );
				$student_growths_after = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthAfterByStudentId ( $record->getStudentId (), $record->getInputDateAt () );
				
				if ($student_growths_before && $student_growths_after) {
					if ($student_growths_after->getHeight () == $student_growths_before->getHeight ()) {
						$index_height = 0;
					} else {
						$index_height = ($student_growths_after->getHeight () > $student_growths_before->getHeight ()) ? 1 : - 1;
					}
					if ($student_growths_after->getWeight () == $student_growths_before->getWeight ()) {
						$index_weight = 0;
					} else {
						$index_weight = ($student_growths_after->getWeight () > $student_growths_before->getWeight ()) ? 1 : - 1;
					}
					$student_growths_after->setIndexHeight ( $index_height );
					$student_growths_after->setIndexWeight ( $index_weight );
					$student_growths_after->save ();
				}
				if (! $student_growths_before && $student_growths_after) {
					$student_growths_after->setIndexHeight ( null );
					$student_growths_after->setIndexWeight ( null );
					$student_growths_after->save ();
				}
			}

			$this->getUser ()
				->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
			$conn->commit ();
		} catch ( Exception $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'The item was deleted failed.' );
			throw new Exception ( $e->getMessage () );
			$conn->rollback ();
		}
		$this->redirect ( '@ps_student_growths' );
	}
	*/
	
	public function executeViewheight(sfWebRequest $request) {

		$exami = $request->getParameter ( 'exa' );

		$class_id = $request->getParameter ( 'clid' );

		$this->growths = $request->getParameter ( 'id' );

		$growths_index = $this->growths;

		$this->examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $this->examination->getInputDateAt ();

		$this->class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$this->growths_height = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexh ( $exami, $class_id, $growths_index );
	}

	public function executeViewweight(sfWebRequest $request) {

		$exami = $request->getParameter ( 'exa' );

		$class_id = $request->getParameter ( 'clid' );

		$this->growths = $request->getParameter ( 'id' );

		$growths_index = $this->growths;

		$this->examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $this->examination->getInputDateAt ();

		$this->class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$this->growths_weight = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexw ( $exami, $class_id, $growths_index );
	}

	public function executeViewch(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'id' );

		$exami = $request->getParameter ( 'date' );

		$this->examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $this->examination->getInputDateAt ();

		$this->class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$this->growths_ch = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexch ( $class_id, $date_at, $exami );
	}

	// Xuat danh sach thong ke hoc sinh da kham theo tung dot kham
	public function executeExportByExaminationExamined(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'clid' );

		$exami = $request->getParameter ( 'date' );

		// echo $class_id.$exami; die();

		$this->exportReportStudentGrowthsExamined ( $class_id, $exami );

		$this->redirect ( '@ps_student_growths' );
	}

	// Xuat danh sach thong ke hoc sinh da kham theo tung dot kham
	protected function exportReportStudentGrowthsExamined($class_id, $exami) {

		$exportFile = new ExportStudentGrowthsExaminedReportHelper ( $this );

		$file_template_pb = 'tkhs_dakham_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$ps_customer_id = $class_name->getCusId ();

		$dung = 1;
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$dung = 0;
			}
		}

		if ($dung == 0) {
			$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
		}

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'List student examination by class' );

		$examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $examination->getInputDateAt ();

		$growths_examined = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexch ( $class_id, $date_at, $exami );

		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $examination );

		$exportFile->setStatisticInfoExportGrowths ( $class_name, $examination );

		$exportFile->setDataExportStatistic ( $growths_examined, $examination );

		$exportFile->saveAsFile ( "DSHSDaKham" . ".xls" );
	}

	// Xuat danh sach thong ke hoc sinh theo can nang cua tung dot kham
	public function executeExportByExaminationWeight(sfWebRequest $request) {

		$exami = $request->getParameter ( 'exa' );

		$class_id = $request->getParameter ( 'clid' );

		$growths_index = $request->getParameter ( 'id' );

		$this->exportReportStudentGrowthsWeight ( $class_id, $exami, $growths_index );

		$this->redirect ( '@ps_student_growths' );
	}

	// Xuat danh sach thong ke hoc sinh theo can nang cua tung dot kham
	protected function exportReportStudentGrowthsWeight($class_id, $exami, $growths_index) {

		$exportFile = new ExportStudentGrowthsWeightReportHelper ( $this );

		$file_template_pb = 'tkhs_cannang_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'List student examination by weight' );

		$examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $examination->getInputDateAt ();

		$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$ps_customer_id = $class_name->getCusId ();

		$dung = 1;
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$dung = 0;
			}
		}

		if ($dung == 0) {
			$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
		}

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$growths_weight = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexw ( $exami, $class_id, $growths_index );

		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $examination );

		$exportFile->setStatisticInfoExportGrowths ( $class_name, $examination );

		$exportFile->setDataExportStatistic ( $growths_weight, $examination );

		$exportFile->saveAsFile ( "DSHSThongKeCanNang" . ".xls" );
	}

	// Xuat danh sach thong ke hoc sinh theo chieu cao cua tung dot kham
	public function executeExportByExaminationHeight(sfWebRequest $request) {

		$exami = $request->getParameter ( 'exa' );

		$class_id = $request->getParameter ( 'clid' );

		$growths_index = $request->getParameter ( 'id' );

		$this->exportReportStudentGrowthsHeight ( $class_id, $exami, $growths_index );

		$this->redirect ( '@ps_student_growths' );
	}

	// Xuat danh sach thong ke hoc sinh theo chieu cao cua tung dot kham
	protected function exportReportStudentGrowthsHeight($class_id, $exami, $growths_index) {

		$exportFile = new ExportStudentGrowthsHeightReportHelper ( $this );

		$file_template_pb = 'tkhs_chieucao_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = $this->getContext ()->getI18N ()->__ ( 'List student examination by height' );

		$examination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $exami );

		$date_at = $examination->getInputDateAt ();

		$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$ps_customer_id = $class_name->getCusId ();

		$dung = 1;
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$dung = 0;
			}
		}

		if ($dung == 0) {
			$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
		}

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$growths_weight = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentsGrowthsIndexh ( $exami, $class_id, $growths_index );

		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $examination );

		$exportFile->setStatisticInfoExportGrowths ( $class_name, $examination );

		$exportFile->setDataExportStatistic ( $growths_weight, $examination );

		$exportFile->saveAsFile ( "DSHSThongKeChieuCao" . ".xls" );
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		$ps_student_growths_statistic_url = $request->getParameter ( 'ps_student_growths_statistic_url' );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			if ($ps_student_growths_statistic_url != '')
				$this->redirect ( '@ps_student_growths_statistic' );

			$this->redirect ( '@ps_student_growths' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			if ($ps_student_growths_statistic_url != '')
				$this->redirect ( '@ps_student_growths_statistic' );

			$this->redirect ( '@ps_student_growths' );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		$this->setTemplate ( 'index' );
	}

	// Xuat danh sach thong ke suc khoe cua co so, lop
	public function executeExportByWorkplace(sfWebRequest $request) {

		$ps_school_year_id = $request->getParameter ( 'growths_school_year_id' );

		$ps_customer_id = $request->getParameter ( 'growths_ps_customer_id' );

		$workplace_id = $request->getParameter ( 'growths_ps_workplace_id' );

		$object = $request->getParameter ( 'growths_ps_group_id' );

		$class_id = $request->getParameter ( 'growths_ps_class_id' );

		$exami = $request->getParameter ( 'growths_examination' );
		
		$growths_index = $request->getParameter ( 'growths_index' );
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			if (myUser::getPscustomerID () != $ps_customer_id) {
				$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
			}
		}
		if($growths_index == 0){
			$this->exportReportStatisticGrowths ( $ps_school_year_id, $ps_customer_id, $workplace_id, $object, $class_id, $exami );
		}else{
			$this->exportReportStatisticGrowths2 ( $ps_school_year_id, $ps_customer_id, $workplace_id, $object, $class_id, $exami );
		}
		$this->redirect ( '@ps_student_growths' );
	}

	// Xuat danh sach thong ke suc khoe cua truong, co so, lop
	protected function exportReportStatisticGrowths($ps_school_year_id, $ps_customer_id, $workplace_id, $object, $class_id, $exami) {

		$exportFile = new ExportStatisticGrowthsReportHelper ( $this );

		$file_template_pb = 'tk_yte_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		if ($object > 0) {
			$object_groups = Doctrine::getTable ( 'PsObjectGroups' )->findOneBy ( 'id', $object );
		}

		$workplace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $workplace_id );

		$list_my_class = Doctrine::getTable ( 'MyClass' )->getClassByCustomerGroup ( $ps_school_year_id, $ps_customer_id, $workplace_id, $object, $class_id );

		$psexamination = Doctrine::getTable ( 'PsExamination' )->getAllExamination ( $exami, $ps_customer_id, $ps_school_year_id );

		$all_students = Doctrine::getTable ( 'PsStudentGrowths' )->getAllStudentsByCustomerId ( $ps_school_year_id, $ps_customer_id, $workplace_id, $exami, $object );

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'List student examination by all' );

		$title_xls = 'DSTK_SK';

		// echo $workplace_id; die();

		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $title_xls );

		$exportFile->setDataExportStatistic ( $object_groups, $workplace, $list_my_class, $psexamination, $all_students );

		$exportFile->saveAsFile ( "DSHSThongKe" . ".xls" );
	}
	
	// Xuat danh sach thong ke tre bi suy dinh duong
	protected function exportReportStatisticGrowths2($ps_school_year_id, $ps_customer_id, $workplace_id, $object, $class_id, $exami) {
		
		$exportFile = new ExportStatisticGrowthsReportHelper ( $this );
		
		$file_template_pb = 'bm_malnutrition.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;
		
		$school_name = Doctrine::getTable('PsWorkPlaces')->getWorkPlacesByWorkPlacesId($workplace_id);
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$psExamination = Doctrine::getTable('PsExamination')->getExamination($exami);
		
		$title_info = $this->getContext () ->getI18N () ->__ ( 'List student examination malnutrition %value%',array('%value%'=>$psExamination->getName()) );
		
		$title_xls = 'DSHS_SDD';
		
		$list_student_malnutrition = Doctrine::getTable('PsStudentGrowths')->getAllStudentsMalnutrition($ps_school_year_id, $ps_customer_id, $workplace_id, $exami, $object = null, $class_id);
		
		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $title_xls );
		
		$exportFile->setDataExportStudentMalnutrition ( $list_student_malnutrition );
		
		$exportFile->saveAsFile ( "ThongKeHSThapCoi" . ".xls" );
		
	}
	
	// Load danh sach hoc sinh
	public function executeStudentGrowthsSearch(sfWebRequest $request) {
		
		if ($request->isXmlHttpRequest ()) {
			
			$ps_school_year_id = $request->getParameter ('y_id');
			$ps_customer_id = $request->getParameter ('c_id');
			$ps_workplace_id = $request->getParameter ('w_id');
			$examination = $request->getParameter ('e_id');
			$object = $request->getParameter ('o_id');
			$class_id = $request->getParameter ('cl_id');
			
			$this->pager = new sfDoctrinePager ( 'PsStudentGrowths', 30 );
			$this->pager->setQuery ( Doctrine::getTable ( 'PsStudentGrowths' )->setAllStudentsMalnutrition ($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination, $object, $class_id) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();
			
			$this->list_student_malnutrition = $this->pager->getResults ();
			
			return $this->renderPartial ( 'psStudentGrowths/list_student', array (
			'list_student_malnutrition' => $this->list_student_malnutrition
			) );
		} else {
			exit ( 0 );
		}
	}

	// Ham thong ke
	public function executeStatistic(sfWebRequest $request) {
		
		$this->helper = new psStudentGrowthsGeneratorHelper();
		
		$this->configuration = new psStudentGrowthsGeneratorConfiguration ();
		
		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$examination_id = $this->malnutrition = null;

		$ps_school_year_id = null;

		$class_id = null;

		$this->list_student_malnutrition = $this->all_students = $this->psexamination = array ();

		$growths_filter = $request->getParameter ( 'growths_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $growths_filter;

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$examination = $value_student_filter ['examination_id'];

			$object = $value_student_filter ['ps_obj_group_id'];

			$this->malnutrition = $value_student_filter ['malnutrition'];
			// $object = $this->object;

			if ($ps_customer_id <= 0) {
				$ps_customer_id = myUser::getPscustomerID ();
			}

			if ($object > 0) {
				$this->object_groups = Doctrine::getTable ( 'PsObjectGroups' )->setSQLPsObjectGroups ( 'id,title', $object )->fetchOne();
			}
			if ($ps_workplace_id == '') {
				$member_id = myUser::getUser ()->getMemberId ();
				$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
				// $this->workplaces = Doctrine::getTable('PsWorkPlaces')->getWorkPlacesByCustomerId($ps_customer_id);
			}

			if($this->malnutrition == 1){
				
				//$this->list_student_malnutrition = Doctrine::getTable('PsStudentGrowths')->getAllStudentsMalnutrition($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination, $object, $class_id);
				
				$this->pager = new sfDoctrinePager ( 'PsStudentGrowths', 30 );
				$this->pager->setQuery ( Doctrine::getTable ( 'PsStudentGrowths' )->setAllStudentsMalnutrition ($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination, $object, $class_id) );
				$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
				$this->pager->init ();
				
				$this->list_student_malnutrition = $this->pager->getResults ();
				
			
			}else{
				
				$this->workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id, 'id, title');
	
				$this->list_my_class = Doctrine::getTable ( 'MyClass' )->getClassByCustomerGroup ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $object, $class_id );
	
				$this->psexamination = Doctrine::getTable ( 'PsExamination' )->getAllExamination ( $examination, $ps_customer_id, $ps_school_year_id );
	
				$this->all_students = Doctrine::getTable ( 'PsStudentGrowths' )->getAllStudentsByCustomerId ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination, $object );
			
			}
		} else {

			$member_id = myUser::getUser ()->getMemberId ();

			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

			if ($ps_workplace_id > 0) {
				$this->workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id, 'id, title');
			}
		}

		if ($growths_filter) {

			$this->ps_school_year_id = isset ( $growths_filter ['ps_school_year_id'] ) ? $growths_filter ['ps_school_year_id'] : 0;

			$this->ps_customer_id = isset ( $growths_filter ['ps_customer_id'] ) ? $growths_filter ['ps_customer_id'] : myUser::getPscustomerID ();

			$this->ps_workplace_id = isset ( $growths_filter ['ps_workplace_id'] ) ? $growths_filter ['ps_workplace_id'] : 0;

			$this->ps_obj_group_id = isset ( $growths_filter ['ps_obj_group_id'] ) ? $growths_filter ['ps_obj_group_id'] : 0;

			$this->class_id = isset ( $growths_filter ['class_id'] ) ? $growths_filter ['class_id'] : 0;

			$this->examination_id = isset ( $growths_filter ['examination_id'] ) ? $growths_filter ['examination_id'] : 0;
			
			$this->malnutrition = isset ( $growths_filter ['malnutrition'] ) ? $growths_filter ['malnutrition'] : '';
			
			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:110px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => false ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => false ) ) );

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		}

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}
		// nhom tre
		$this->formFilter->setWidget ( 'ps_obj_group_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => Doctrine::getTable ( 'PsObjectGroups' )->setSQL (),
				'add_empty' => _ ( '-Select object-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;width:100%;",
				'required' => false,
				'data-placeholder' => _ ( '-Select object-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_obj_group_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'required' => false ) ) );
		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );

			// filter examination
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select examination-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => true ) ) );
		} else {
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) ) ) );

			$this->formFilter->setValidator ( 'examination_id', new sfValidatorPass () );
		}
		/*
		$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
				'add_empty' => _ ( '-Select workplace-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;width:100%;",
						'required' => true,
						'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		
		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => true ) ) );
		*/
		
		$this->formFilter->setWidget ('malnutrition', new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( '-All growth-' ),
						'1' => _('Not Normal')
				)
		),array('class' => 'select2','style' => "min-width:150px;width:100%;",) ) );
		
		$this->formFilter->setValidator ('malnutrition', new sfValidatorPass ( array (
				'required' => true
		) ) );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'examination_id', $this->examination_id );

		$this->formFilter->setDefault ( 'ps_obj_group_id', $this->ps_obj_group_id );
		
		$this->formFilter->setDefault ( 'malnutrition', $this->malnutrition );
		
		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'growths_filter[%s]' );
	}

	// Xem thong tin hoc sinh cua tung dot kham
	public function executeDetail(sfWebRequest $request) {

		// ID học sinh
		$id_student = $request->getParameter ( 'id' );

		if ($id_student <= 0) {

			$this->forward404Unless ( $id_student, sprintf ( 'Object does not exist.' ) );
		}

		$this->student = Doctrine::getTable ( 'Student' )->getStudentByField ($id_student, 'id,sex,birthday,first_name,last_name,ps_customer_id'  );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.', $this->student ) );

		// lay thong tin chi tiet dot kham cua tre
		$this->growths = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthsById ( $id_student );

		$this->student_bmi = Doctrine::getTable ( 'PsStudentBmi' )->getStudentBmi ($this->student->getSex());
	}
	
	// Gui thong bao chon nhieu hoc sinh
	protected function executeBatchSendNotication(sfWebRequest $request) {
		
		$ids = $request->getParameter ( 'ids' );
		
		if (myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			//$records = Doctrine_Query::create ()->from ( 'PsStudentGrowths' )->whereIn ( 'id', $ids )->execute ();
			$records = Doctrine::getTable('PsStudentGrowths')->getPsStudentGrowthsByIds($ids);
		}else{
			$records = Doctrine::getTable('PsStudentGrowths')->getPsStudentGrowthsByIds($ids,myUser::getPscustomerID ());
		}
		
		$dagui = $guiloi = 0;
		
		$user_id = myUser::getUserId();
		
		foreach ( $records as $key =>$record ) {
			
			$student_id = $record->getStudentId();
			
			$record->setNumberPushNotication ( $record->getNumberPushNotication () + 1 );
			
			$record->setDatePushNotication(date('Y-m-d H:i:s'));
			
			$record->setUserPushNoticationId($user_id);
			
			$record->save ();
			
			$height = $record->getHeight();
			
			$weight = $record->getWeight();
			
			$dagui ++;
			
			$ps_customer_id = $record->getPsCustomerId ();
			$student_name = $record->getStudentName();
			
			$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_id );
			
			if (count ( $list_received_id ) > 0) {
				
				$registrationIds_ios = array ();
				$registrationIds_android = array ();
				
				foreach ( $list_received_id as $user_nocation ) {
					
					if ($user_nocation->getNotificationToken () != '') {
						
						if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS) {
							array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
						} else {
							array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
						}
					}
				}
				
				$psI18n = $this->getContext ()->getI18N ();
				
				if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
					
					$setting = new \stdClass ();
					
					$setting->title = $psI18n->__ ( 'Notice of growths' );
					
					$setting->subTitle = $psI18n->__ ( 'Notice growths of' ) . $student_name;
					
					$setting->tickerText = $psI18n->__ ( 'Growths from KidsSchool.vn' );
					
					$content = $psI18n->__ ( 'Student: %value1%, Height: %value2%, Weight: %value3%', array('%value1%' => $student_name, '%value2%'=>$height,'%value3%'=>$weight) );
					
					$setting->message = $content;
					
					$setting->lights = '1';
					$setting->vibrate = '1';
					$setting->sound = '1';
					$setting->smallIcon = 'ic_small_notification';
					$setting->smallIconOld = 'ic_small_notification_old';
					
					// Lay avatar nguoi gui thong bao
					$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
					
					if ($profile && $profile->getAvatar () != '') {
						$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
						$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					} else {
						$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					}
					
					$setting->largeIcon = $largeIcon;
					
					$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_GROWTHSTUDENT;
					$setting->itemId = '0';
					$setting->clickUrl = '';
					$setting->studentId = $student_id;
					
					// Deviceid registration firebase
					if (count ( $registrationIds_ios ) > 0) {
						$setting->registrationIds = $registrationIds_ios;
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
					}
					
					if (count ( $registrationIds_android ) > 0) {
						$setting->registrationIds = $registrationIds_android;
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ();
					}
				} // end sent notication
			}
		}
		
		$this->getUser () ->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The selected items have been send notication successfully.' ) );
		
		$this->redirect ( '@ps_student_growths' );
	}
	
	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {
		
		$growths_id = $request->getParameter ( 'growths_id' );
		
		$student_id = $request->getParameter ( 'student_id' );
		
		$records = Doctrine_Core::getTable ( 'PsStudentGrowths' )->getPsStudentGrowthsById ( $growths_id );
		
		if(!$records || $records->getStudentId() != $student_id){
			echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
			exit ( 0 );
		} else {
			
			$user_id = myUser::getUserId();
			
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				if ($records) {
					
					$records->setNumberPushNotication ( $records->getNumberPushNotication() + 1 );
					
					$records->setUserPushNoticationId($user_id);
					
					$records->setDatePushNotication(date('Y-m-d H:i:s'));
					
					$records->save ();
					
					$student_name = $records->getStudentName();
					$ps_customer_id = $records->getPsCustomerId ();
					
					$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, $class_id = null, $student_id );
					
					if (count ( $list_received_id ) > 0) {
						
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						foreach ( $list_received_id as $user_nocation ) {
							
							if ($user_nocation->getNotificationToken () != '') {
								
								if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS) {
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								} else {
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
								}
							}
						}
						
						$psI18n = $this->getContext ()
						->getI18N ();
						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();
							
							$setting->title = $psI18n->__ ( 'Notice of growths' );
							
							$setting->subTitle = $psI18n->__ ( 'Notice growths of' ) . $student_name;
							
							$setting->tickerText = $psI18n->__ ( 'Growths from KidsSchool.vn' );
							
							$content = $psI18n->__ ( 'Student: %value1%, Height: %value2%, Weight: %value3%', array('%value1%' => $student_name, '%value2%'=>$height,'%value3%'=>$weight) );
							
							$setting->message = $content;
							
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							// Lay avatar nguoi gui thong bao
							$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
							
							if ($profile && $profile->getAvatar () != '') {
								
								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
								
								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}
							
							$setting->largeIcon = $largeIcon;
							
							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_GROWTHSTUDENT;
							$setting->itemId = '0';
							$setting->studentId = $student_id;
							$setting->clickUrl = '';
							
							// Deviceid registration firebase
							if (count ( $registrationIds_ios ) > 0) {
								$setting->registrationIds = $registrationIds_ios;
								
								$notification = new PsNotification ( $setting );
								$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
							}
							
							if (count ( $registrationIds_android ) > 0) {
								$setting->registrationIds = $registrationIds_android;
								
								$notification = new PsNotification ( $setting );
								$result = $notification->pushNotification ();
							}
						} // end sent notication
					}
				}
				
				$conn->commit ();
				
				return $this->renderPartial ( 'psStudentGrowths/load_number_notication', array (
						'value' => $records->getNumberPushNotication() ) );
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$conn->rollback ();
				echo $this->getContext ()->getI18N ()->__ ( 'Send notication growths failed.' );
				
				exit ();
			}
		}
	}
}