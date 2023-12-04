<?php
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexStudentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexStudentGeneratorHelper.class.php';

/**
 * psEvaluateIndexStudent actions.
 *
 * @package kidsschool.vn
 * @subpackage psEvaluateIndexStudent
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateIndexStudentActions extends autoPsEvaluateIndexStudentActions {
	
	private function searchIndexKey($needle, $array) {
		foreach ( $array as $key => $val ) {
			
			if (($val ['evaluate_index_criteria_id'] == $needle ['criteria_id']) && ($val ['ps_student_id'] == $needle ['student_id']) && (date ( 'm-Y', strtotime ( $val ['date_at'] ) ) == $needle ['date_at'])) {
				
				unset ( $array [$key] );
				return array (
						'evaluate_index_symbol_id' => $val ['evaluate_index_symbol_id'],
						'symbol_code' => $val ['symbol_code'],
						'symbol_title' => $val ['symbol_title'],
						'is_public' => $val ['is_public'],
						'is_awaiting_approval' => $val ['is_awaiting_approval'] 
				);
			}
		}
		return false;
	}
	
	public function executeExportEvaluateStudent(sfWebRequest $request) {
		
		$year_id = $request->getParameter ( 'y_id' );
		$customer_id = $request->getParameter ( 'c_id' );
		$wp_id = $request->getParameter ( 'w_id' );
		$class_id = $request->getParameter ( 'class_id' );
		$month = date ( 'd' ) . '-' . $request->getParameter ( 'month' );
		
		// Danh sach hoc sinh theo lop
		$students = Doctrine::getTable ( 'StudentClass' )->getTestOfficalStudentsByClassId ( $class_id, $month )->toArray ();
		
		// Danh sach hoc sinh da duoc danh gia chi so
		$students_evaluate = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->getEvaluteStudentByClassId ( $class_id, $month )->toArray ();
		// $count_students_evaluate = Doctrine::getTable('PsEvaluateIndexStudent')->getCountEvaluteStudentBySubjectId($class_id, $month);
		// print_r($count_students_evaluate);die;
		
		// Danh sach linh vuc danh gia theo class id
		$subject = Doctrine::getTable ( 'PsEvaluateClassTime' )->getEvaluateSubjectByClassId ( $class_id, $month, $year_id )->toArray ();
		
		$evaluate = array ();
		
		$param = array (
				'schoolyear_id' => $year_id,
				'ps_customer_id' => $customer_id,
				'ps_workplace_id' => $wp_id,
				'ps_class_id' => $class_id,
				'month' => $month,
				'total_student' => count ( $students ) 
		);
		
		foreach ( $subject as $subject ) {
			// lay thong tin lĩnh vực đánh giá
			array_push ( $evaluate, array (
					'subject_id' => $subject ['subject_id'],
					'subject_code' => $subject ['subject_code'],
					'subject_title' => $subject ['subject_title'] 
			) );
			
			// Lay ra danh sach tiêu chí đánh giá theo class va subject
			$criteria = Doctrine::getTable ( 'PsEvaluateClassTime' )->getEvaluateCriteriaByClassId ( $class_id, $month, $year_id, $subject ['subject_id'] )->toArray ();
			
			foreach ( $criteria as $criteria ) {
				
				array_push ( $evaluate, array (
						'criteria_id' => $criteria ['criteria_id'],
						'criteria_code' => $criteria ['criteria_code'],
						'criteria_title' => $criteria ['criteria_title'],
						'evaluate' => array () 
				) );
			}
		}
		// print_r($evaluate);die;
		foreach ( $evaluate as $key => $eval ) {
			// Nếu tồn tại tiêu chí đánh giá
			if (isset ( $eval ['criteria_id'] )) {
				
				foreach ( $students as $student ) {
					
					$needle = array ();
					$needle ['student_id'] = $student ['student_id'];
					$needle ['criteria_id'] = $eval ['criteria_id'];
					$needle ['date_at'] = $request->getParameter ( 'month' );
					
					$array_result = $this->searchIndexKey ( $needle, $students_evaluate );
					
					if ($array_result) {
						array_push ( $evaluate [$key] ['evaluate'], array (
								// 'criteria_id' => $evaluate['criteria_id'],
								// 'student_id' => $student['student_id'],
								'symbol_id' => $array_result ['evaluate_index_symbol_id'],
								'symbol_code' => $array_result ['symbol_code'],
								'symbol_title' => $array_result ['symbol_title'],
								'is_public' => $array_result ['is_public'],
								'is_awaiting_approval' => $array_result ['is_awaiting_approval'] 
						) );
					} else {
						array_push ( $evaluate [$key] ['evaluate'], array (
								// 'criteria_id' => $evaluate['criteria_id'],
								// 'student_id' => $student['id'],
								'symbol_id' => 0,
								'symbol_code' => 0,
								'is_public' => 0,
								'is_awaiting_approval' => 0 
						) );
					}
				}
			}
		}
		// Lấy tất cả ký hiệu đánh giá
		$symbols = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->getSymbolByCustomerSchoolyearId ( $customer_id, $year_id, $wp_id );
		// echo '<pre>';
		// print_r($evaluate);
		// echo '</pre>';
		$evaluate = json_encode ( $evaluate );
		// print_r($evaluate);
		// die;
		$this->exportReportEvaluate ( $students, $evaluate, $symbols, $param );
		
		$this->redirect ( '@ps_evaluate_index_student' );
		
	}
	
	public function executeSaveStateByClass(sfWebRequest $request) {
		
		$class_id = $request->getParameter ( 'class_id' );
		$date = date ( 'Ymd', strtotime ( $request->getParameter ( 'date' ) ) );
		$date2 = date ( 'Ym', strtotime ( $request->getParameter ( 'date' ) ) );
		$is_publish = $request->getParameter ( 'is_publish' );
		$is_awaiting = $request->getParameter ( 'is_awaiting' );
		
		$user_id = myUser::getUserId ();
		
		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_EDIT' )) {
			
			$student = Doctrine::getTable ( 'StudentClass' )->getStudentsActiveInClass ( $class_id, $date )->toArray ();
			
			if (myUser::isAdministrator ()) {
				$q = Doctrine_Query::create ()->update ( 'PsEvaluateIndexStudent s' )->set ( 's.is_public', $is_publish )->set ( 's.is_awaiting_approval', $is_awaiting )->set ( 's.user_updated_id', $user_id )->where ( 'DATE_FORMAT(s.date_at,"%Y%m") = ?', $date2 )->andWhereIn ( 's.ps_student_id', array_column ( $student, 'student_id' ) )->execute ();
			} else {
				$q = Doctrine_Query::create ()->update ( 'PsEvaluateIndexStudent s' )->set ( 's.is_public', $is_publish )->set ( 's.user_updated_id', $user_id )->where ( 'DATE_FORMAT(s.date_at,"%Y%m") = ?', $date2 )->andWhereIn ( 's.ps_student_id', array_column ( $student, 'student_id' ) )->execute ();
			}
		}
		
		exit ( 0 );
	}
	
	public function executeSaveEvaluateStudent(sfWebRequest $request) {
		$student_arr = $request->getParameter ( 'student_arr' );
		$symbol_arr = $request->getParameter ( 'symbol_arr' );
		$criteria_id = $request->getParameter ( 'criteria_id' );
		$date = date ( 'Y-m-d', strtotime ( $request->getParameter ( 'date' ) ) );
		$is_publish = $request->getParameter ( 'is_publish' );
		$is_awaiting = $request->getParameter ( 'is_awaiting' );
		
		$new_student_arr_id = explode ( ',', $student_arr );
		$new_symbol_arr = explode ( ',', $symbol_arr );
		
		$user_id = myUser::getUserId ();
		
		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_EDIT' ) || myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_ADD' ) || myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_DELETE' )) {
			
			foreach ( $new_symbol_arr as $key => $symbol_id ) {
				
				$ps_student = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->findOneById ( $new_student_arr_id [$key] );
				
				if ($symbol_id > 0) {
					
					$evalute_student = Doctrine_Core::getTable ( 'PsEvaluateIndexStudent' )->getEvaluateStudent ( $criteria_id, $new_student_arr_id [$key], $date );
					
					if ($evalute_student) {
						
						$evalute_student->setEvaluateIndexSymbolId ( $symbol_id );
						$evalute_student->setIsPublic ( $is_publish );
						if (myUser::isAdministrator ()) {
							$evalute_student->setIsAwaitingApproval ( $is_awaiting );
						}
						$evalute_student->setUserUpdatedId ( $user_id );
						$evalute_student->setUserCreatedId ( $user_id );
						
						$evalute_student->save ();
					} else {
						
						$evalute_student = new PsEvaluateIndexStudent ();
						$evalute_student->setEvaluateIndexCriteriaId ( $criteria_id );
						$evalute_student->setPsStudentId ( $new_student_arr_id [$key] );
						$evalute_student->setEvaluateIndexSymbolId ( $symbol_id );
						$evalute_student->setDateAt ( $date );
						$evalute_student->setIsPublic ( $is_publish );
						if (myUser::isAdministrator ()) {
							$evalute_student->setIsAwaitingApproval ( $is_awaiting );
						}
						$evalute_student->setUserUpdatedId ( $user_id );
						$evalute_student->setUserCreatedId ( $user_id );
						$evalute_student->save ();
					}
				} else {
					$q = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->getEvaluateStudent ( $criteria_id, $new_student_arr_id [$key], $date );
					if ($q) {
						$q->delete ();
					}
				}
			}
		}
		exit ( 0 );
	}
	
	public function executeIndex(sfWebRequest $request) {
		
		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) 
			) );
		}
		
		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}
		
		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
		
		// filter
		$filterValue = $this->getFilters ();
		
		$this->filterValue = $filterValue;
		
		$this->evaluate = array ();
		
		// Danh sach hoc sinh theo lop
		$this->students = array ();
		
		// Danh sach hoc sinh da duoc danh gia chi so
		$this->students_evaluate = array ();
		
		if (isset ( $filterValue ['ps_class_id'] ) && $filterValue ['ps_class_id'] > 0) {
			
			// Danh sach hoc sinh theo lop
			// $this->students = Doctrine::getTable('Student')->setSqlListStudentsByClassId($filterValue['ps_class_id'], date('d').'-'. $filterValue['ps_month'])->fetchArray();
			$this->students = Doctrine::getTable ( 'StudentClass' )->getTestOfficalStudentsByClassId ( $filterValue ['ps_class_id'], date ( 'd' ) . '-' . $filterValue ['ps_month'] )->toArray ();
			
			// Danh sach hoc sinh da duoc danh gia chi so
			$this->students_evaluate = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->getEvaluteStudentByClassId ( $filterValue ['ps_class_id'], date ( 'd' ) . '-' . $filterValue ['ps_month'] )->toArray ();
			
			$subject = Doctrine::getTable ( 'PsEvaluateClassTime' )->getEvaluateSubjectByClassId ( $filterValue ['ps_class_id'], date ( 'd' ) . '-' . $filterValue ['ps_month'], $filterValue ['school_year_id'] )->toArray ();
			
			foreach ( $subject as $subject ) {
				
				array_push ( $this->evaluate, array (
						'subject_id' => $subject ['subject_id'],
						'subject_code' => $subject ['subject_code'],
						'subject_title' => $subject ['subject_title'] 
				) );
				
				// Lay ra danh sach criteria theo class va subject
				$criteria = Doctrine::getTable ( 'PsEvaluateClassTime' )->getEvaluateCriteriaByClassId ( $filterValue ['ps_class_id'], date ( 'd' ) . '-' . $filterValue ['ps_month'], $filterValue ['school_year_id'], $subject ['subject_id'] )->toArray ();
				
				foreach ( $criteria as $criteria ) {
					
					array_push ( $this->evaluate, array (
							'criteria_id' => $criteria ['criteria_id'],
							'criteria_code' => $criteria ['criteria_code'],
							'criteria_title' => $criteria ['criteria_title'] 
					) );
				}
			}
			
			unset ( $subject );
			unset ( $criteria );
		}
		// if(isset($filterValue ['school_year_id'])){
		$this->symbols = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->getSymbolByCustomerSchoolyearId ( $filterValue ['ps_customer_id'], $filterValue ['school_year_id'], $filterValue ['ps_workplace_id'] );
		// }else{
		// $this->symbols = array();
		// }
		if (isset ( $filterValue ['school_year_id'] ) && $filterValue ['school_year_id'] > 0) {
			$this->schoolyear = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $filterValue ['school_year_id'] );
		}
		
		$this->setTemplate ( 'index' );
	}
	
	public function executeDeleteForClass(sfWebRequest $request) {
		$class_id = $request->getParameter ( 'class_id' );
		$date = date ( 'Ym', strtotime ( $request->getParameter ( 'date' ) ) );
		$student_id = $request->getParameter ( 'student_list' );
		
		$student_arr_id = explode ( ',', $student_id );
		
		$records = Doctrine_Query::create ()->from ( 'PsEvaluateIndexStudent' )->where ( 'DATE_FORMAT(date_at,"%Y%m") =?', $date )->whereIn ( 'ps_student_id', $student_arr_id )->execute ();
		
		foreach ( $records as $record ) {
			$record->delete ();
		}
		die ();
	}
	
	protected function exportReportEvaluate($students, $evaluate, $symbols, $param) {
		
		$evaluate = json_decode ( $evaluate, true );
		
		$exportFile = new ExportEvaluateIndexStudentHelper ( $this );
		
		$file_template_pb = 'ps_evaluate_student_00001.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $param ['ps_class_id'] );
		
		$exportFile->setCustomerInfoExport ( $school_info, $symbols, $param );
		
		$exportFile->setDataEvaluateExport ( $students, $evaluate, $symbols, $param );
		
		$file_name = $this->getContext ()->getI18N ()->__ ( 'Evaluate index student month' ) . date("m-Y",strtotime($param ['month']));
		
		$exportFile->saveAsFile ( $file_name . ".xls" );
	}
	
	// cap nhat danh gia hoc ky
	public function executeStatistic(sfWebRequest $request){
		
		$this->formFilter = new sfFormFilter ();
		
		$this->ps_customer_id = null;
		
		$this->ps_workplace_id = null;
		
		$this->ps_school_year_id = null;
		
		$this->class_id = null; $this->type = 0;
		
		$this->ps_semester = 1;
		
		$this->getDataEvaluate = $this->list_student = $this->list_symbols = array();
		
		$semester_statistic = $request->getParameter ( 'semester_statistic' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $semester_statistic;
			
			$this->ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$this->ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$this->class_id = $value_student_filter ['class_id'];
			
			$this->ps_semester = $value_student_filter ['ps_semester'];
			
			$this->type = $value_student_filter ['type'];
			
			$user_id = myUser::getUserId();
			
			if($this->type == 0){
				
				$this->getDataEvaluate = Doctrine::getTable('PsEvaluateSemesterStudent')->getListDataEvaluateStudent($this->ps_school_year_id,$this->class_id,$this->ps_semester);
				$this->list_student = Doctrine::getTable('Student')->psGetStudentsByClass($this->class_id);
				$this->list_symbols = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->getSymbolByCustomerSchoolyearId ( $this->ps_customer_id, $this->ps_school_year_id, $this->ps_workplace_id );
				
			}else{
			
				 $conn = Doctrine_Manager::connection();
				 
				 try {
				 
				 $conn->beginTransaction();
				 
				 if($this->ps_semester == 3){ // Neu danh gia ca nam
				 
				 $schoolYear = Doctrine::getTable('PsSchoolYear')->getPsSchoolYearByField($this->ps_school_year_id,'from_date,to_date');
				 $from_month = date('d-m-Y', strtotime($schoolYear->getFromDate()));
				 $to_month = date('d-m-Y', strtotime($schoolYear->getToDate()));
				 
				 }else{ // Neu danh gia hoc ky
					 
					 // lay ra gioi han cua hoc ky
					 $psSemester = Doctrine::getTable('PsSemester')->getSemesterConfig($this->ps_school_year_id, $this->ps_customer_id, $this->ps_workplace_id);
					 
					 if($psSemester){
						 if($this->ps_semester == 1){
						 $date_from = explode(';',$psSemester->getSemesterOne());
						 }else{
						 $date_from = explode(';',$psSemester->getSemesterTwo());
						 }
						 $from_month = '01-'.$date_from[0]; // Thang bat dau cua hoc ky
						 $to_month = '01-'.$date_from[1];  // Thang ket thuc cua hoc ky
						 }else{
						 $this->getUser () ->setFlash ( 'error', 'You must config semester by year.' );
						 $this->redirect('@ps_evaluate_index_student_statistic');
						 }
					 }
					 
					 $array_evalute = array();
					 // Lay tat ca chi so danh gia cua hoc sinh
					 $evaluteStudent = Doctrine::getTable('PsEvaluateIndexStudent')->getEvaluteStudentByClassId3($this->class_id,$from_month,$to_month);
					 
					 foreach ($evaluteStudent as $evalute){
					 array_push($array_evalute,$evalute->getPsStudentId().'_'.$evalute->getEvaluateIndexSymbolId());
					 }
					 $array_data_evalute = (array_count_values($array_evalute));
					 // Lay danh sach hoc sinh trong lop hoc co trang thai la hoc thu + chinh thuc
					 $list_student = Doctrine::getTable('Student')->psGetStudentsByClass($this->class_id);
					 // Lay cac ky hieu danh gia
					 $list_symbols = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->getSymbolByCustomerSchoolyearId ( $this->ps_customer_id, $this->ps_school_year_id, $this->ps_workplace_id );
					 // Xoa tat ca cac ban ghi da co trong bang danh gia cuoi hoc ky
					 Doctrine::getTable('PsEvaluateSemesterStudent')->checkDataEvaluateStudent($this->ps_school_year_id,$this->class_id,$this->ps_semester)->delete();
					 
					 foreach ($list_student as $student){
						 $student_id = $student->getId();
						 foreach ($list_symbols as $symbol){
							 $symbol_id = $symbol->getId();
							 if(isset($array_data_evalute[$student_id.'_'.$symbol_id])){
							 	$c = $array_data_evalute[$student_id.'_'.$symbol_id];
							 }else{
							 	$c = 0;
							 }
							 
							 $semesterStudent = new PsEvaluateSemesterStudent();
							 $semesterStudent -> setSchoolYearId($this->ps_school_year_id);
							 $semesterStudent -> setPsCustomerId($this->ps_customer_id);
							 $semesterStudent -> setPsSemester($this->ps_semester);
							 $semesterStudent -> setStudentId($student_id);
							 $semesterStudent -> setSymbolId($symbol_id);
							 $semesterStudent -> setNumber($c);
							 $semesterStudent -> setUserCreatedId($user_id);
							 $semesterStudent -> save();
						 }
					 }
					 
					 $conn->commit();
				 }catch (Exception $e) {
				 $conn->rollback();
				 $error_import = $e->getMessage();
				 $this->getUser()->setFlash('error', $error_import);
				 $this->redirect('@ps_attendances_synthetic_updated_month');
				 }
			}
			
		}
		
		if ($semester_statistic) {
			
			$this->ps_school_year_id = isset ( $semester_statistic ['ps_school_year_id'] ) ? $semester_statistic ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $semester_statistic ['ps_workplace_id'] ) ? $semester_statistic ['ps_workplace_id'] : 0;
			
			$this->class_id = isset ( $semester_statistic ['class_id'] ) ? $semester_statistic ['class_id'] : 0;
			
			$this->ps_semester = isset ( $semester_statistic ['ps_semester'] ) ? $semester_statistic ['ps_semester'] : 0;
			
			$this->type = isset ( $semester_statistic ['type'] ) ? $semester_statistic ['type'] : 0;
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_EVALUATE_INDEX_STUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;width:100%;",
							'required' => true,
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
		
		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
						'class' => 'select2',
						'style' => "width:100%;min-width:150px;",
						'data-placeholder' => _ ( '-Select school year-' ),
						'required' => true ) ) );
		
		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );
		
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		if ($this->ps_workplace_id > 0) {
			
			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE
					) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:150px;",
							'required' => true,
							'data-placeholder' => _ ( '-Select class-' ) ) ) );
					
					$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
							'model' => 'MyClass',
							'required' => true ) ) );
		} else {
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'required' => true,
									'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		
		$this->formFilter->setWidget ( 'ps_semester', new sfWidgetFormChoice ( array (
			'choices' => array(
					'1' => 'Semester one',
					'2' => 'Semester two',
					'3' => 'Semester all'
			) ), array (
					'class' => 'select2',
					'style' => "min-width:100px;",
					'required' => true,
					'placeholder' => _ ( '-Select semester-' ),
					'rel' => 'tooltip',
					'data-original-title' => _ ( 'Select semester' ) ) 
		) );
				
		$this->formFilter->setValidator ( 'ps_semester', new sfValidatorChoice ( array (
				'choices' => array(1,2,3),
				'required' => true ) ) );
		
		
		$this->formFilter->setWidget ( 'type', new sfWidgetFormChoice ( array (
				'choices' => array(
						'0' => 'Statistic evaluate',
						'1' => 'Update evaluate',
				) ), array (
						'class' => 'select2',
						'style' => "min-width:150px;",
						'required' => true,
						'placeholder' => _ ( '-Select type-' ),
						'rel' => 'tooltip',
						'data-original-title' => _ ( 'Select type' ) )
				) );
		
		$this->formFilter->setValidator ( 'type', new sfValidatorChoice ( array (
				'choices' => array(0,1),
				'required' => true ) ) );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_semester', $this->ps_semester );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->setDefault ( 'type', $this->type );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'semester_statistic[%s]' );
				
	}
	
}
