<?php
require_once dirname ( __FILE__ ) . '/../lib/psRelativeStudentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psRelativeStudentGeneratorHelper.class.php';

/**
 * psRelativeStudent actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psRelativeStudent
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRelativeStudentActions extends autoPsRelativeStudentActions {

	public function executeSaveAjax(sfWebRequest $request) {

		$re_student_id = $request->getParameter ( 're_student_id' );

		$relative_student = Doctrine_Core::getTable ( 'RelativeStudent' )->findOneById ( $re_student_id );

		$id_s = $relative_student->student_id;

		$checkparentStudent = Doctrine_Core::getTable ( 'RelativeStudent' )->checkparentStudentExits ($id_s,1);

		// moi quan he
		$relationship_id = $request->getParameter ( 'relationship_id' );
		// Quyen uu tien
		$relation_order = $request->getParameter ( 'relation_order' );
		// Quyen giam ho chinh
		$is_parent_main = $request->getParameter ( 'is_parent_main' );

		// Quyen dua don
		$is_parent = $request->getParameter ( 'is_parent' );
		// Quyen cap nhat thong tin cua tre
		$is_role_avatar = $request->getParameter ( 'is_role_avatar' );
		// Quyen dang ky dich vu
		$is_role_service = $request->getParameter ( 'is_role_service' );

		$st_student = $array_status = array();

		$st_student = PreSchool::loadCheckStatusStudent();

		$stt = 0;

		foreach($st_student as $key => $st){
			$array_status[$stt++] = $key;
			array_push($array_status);
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			//nếu check là quyền giám hộ
			if($is_parent_main == '1'){
				//kiểm tra nếu chưa có ng giám hộ thì cập nhật thông tin bình thường
				if(count($checkparentStudent) == '0'){
					if ($relative_student) {

						$relative_student->setIsParentMain ( $is_parent_main );

						$relative_student->setIsParent ( $is_parent );

						$relative_student->setIsRole ( $is_role_avatar );

						$relative_student->setRoleService ( $is_role_service );

						$relative_student->setIorder ( $relation_order );

						$relative_student->setRelationshipId ( $relationship_id );

						$relative_student->save ();

						$student_id = $relative_student->student_id;
						$relative_id = $relative_student->relative_id;

						//cập nhật id ng giám hộ vào bảng student
						$student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
						if($student){
							$student->setRelativeId ($relative_id);
							$student->save();
						}
						
						$ps_customer_id = $student->ps_customer_id;
						$ps_workplace_id = $student->ps_workplace_id;

						//lây ra cơ sở đào tạo của học sinh hiện tại
						$ps_workplace = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
							->addwhere ('id =?',$ps_workplace_id)
							->addwhere ( 'ps_customer_id =?', $ps_customer_id )
							->fetchOne();

						//lấy ra danh sách học sinh có trùng người giám hộ, trạng thái theo array_status, sắp xếp theo id tăng dần là em
						$totalae = Doctrine_Query::create ()->from ( 'Student' )
							->addwhere ( 'relative_id =?', $relative_id )
							->whereIn ('status',$array_status)
							->orderBy('id ASC')
							->execute();	

						if($ps_workplace){
							//kiểm tra cấu hình giảm trừ của cơ sở, nếu có
							if($ps_workplace->is_reduce > 0){
								$giamtru = $ps_workplace->is_reduce;
								if($totalae){
									//cập nhật number_ae theo cấu hình giảm trừ
									foreach ($totalae as $key => $total) {
										if($key >= $giamtru){
											$total->setNumberAe (count($totalae) - 1);
										}else{
											$total->setNumberAe (0);
										}
										$total->save();
									}
								}
							}else{
							//nếu không, cập nhật number_ae đếm tổng số ae cùng ng giám hộ
								if($totalae){
									foreach ($totalae as $key => $total) {
										$total->setNumberAe (count($totalae) - 1);
										$total->save();
									}
								}
							}
						}	
						

					}
				}else{
					//nếu có người giám hộ rồi, kiểm tra xem id hiện tại có là giám hộ chính hay không
					if($relative_student->is_parent_main == '1'){

						$relative_student->setIsParentMain ( $is_parent_main );

						$relative_student->setIsParent ( $is_parent );

						$relative_student->setIsRole ( $is_role_avatar );

						$relative_student->setRoleService ( $is_role_service );

						$relative_student->setIorder ( $relation_order );

						$relative_student->setRelationshipId ( $relationship_id );

						$relative_student->save ();

						$student_id = $relative_student->student_id;
						$relative_id = $relative_student->relative_id;

						//cập nhật id ng giám hộ vào bảng student
						$student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
						if($student){
							$student->setRelativeId ($relative_id);
							$student->save();
						}
						
						$ps_customer_id = $student->ps_customer_id;
						$ps_workplace_id = $student->ps_workplace_id;

						//lây ra cơ sở đào tạo của học sinh hiện tại
						$ps_workplace = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
							->addwhere ('id =?',$ps_workplace_id)
							->addwhere ( 'ps_customer_id =?', $ps_customer_id )
							->fetchOne();

						//lấy ra danh sách học sinh có trùng người giám hộ, trạng thái theo array_status, sắp xếp theo id tăng dần là em
						$totalae = Doctrine_Query::create ()->from ( 'Student' )
							->addwhere ( 'relative_id =?', $relative_id )
							->whereIn ('status',$array_status)
							->orderBy('id ASC')
							->execute();	

						if($ps_workplace){
							//kiểm tra cấu hình giảm trừ của cơ sở, nếu có
							if($ps_workplace->is_reduce > 0){
								$giamtru = $ps_workplace->is_reduce;
								if($totalae){
									//cập nhật number_ae theo cấu hình giảm trừ
									foreach ($totalae as $key => $total) {
										if($key >= $giamtru){
											$total->setNumberAe (count($totalae) - 1);
										}else{
											$total->setNumberAe (0);
										}
										$total->save();
									}
								}
							}else{
							//nếu không, cập nhật number_ae đếm tổng số ae cùng ng giám hộ
								if($totalae){
									foreach ($totalae as $key => $total) {
										$total->setNumberAe (count($totalae) - 1);
										$total->save();
									}
								}
							}
						}
					}else{
						$this->getUser ()->setFlash ( 'error', 'Chỉ được chọn 1 người giám hộ chính' );
					}
					
				}
			}else{
				//nếu không check là quyền giám hộ
				if ($relative_student) {

					$student_id = $relative_student->student_id;
					$relative_id = $relative_student->relative_id;

					//kiếm tra xem id của phụ huynh này đã từng là giám hộ hay chưa
					if($relative_student->is_parent_main == '1'){
						// nếu tồn tại, cập nhật lại id ng giám hộ và tổng số ae về 0
						$student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
						if($student){
							$student->setRelativeId (null);
							$student->setNumberAe (null);
							$student->save();
						}
						
						$ps_customer_id = $student->ps_customer_id;
						$ps_workplace_id = $student->ps_workplace_id;

						//lấy ra cơ sở đào tạo của học sinh hiện tại
						$ps_workplace = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
							->addwhere ('id =?',$ps_workplace_id)
							->addwhere ( 'ps_customer_id =?', $ps_customer_id )
							->fetchOne();

						//lấy ra danh sách học sinh có trùng người giám hộ, trạng thái theo array_status, sắp xếp theo id tăng dần là em
						$totalae = Doctrine_Query::create ()->from ( 'Student' )
							->addwhere ( 'relative_id =?', $relative_id )
							->whereIn ('status',$array_status)
							->orderBy('id ASC')
							->execute();	

						if($ps_workplace){
							//kiểm tra cấu hình giảm trừ của cơ sở, nếu có
							if($ps_workplace->is_reduce > 0){
								$giamtru = $ps_workplace->is_reduce;
								if($totalae){
									//cập nhật number_ae theo cấu hình giảm trừ
									foreach ($totalae as $key => $total) {
										if($key >= $giamtru){
											$total->setNumberAe (count($totalae) - 1);
										}else{
											$total->setNumberAe (0);
										}
										$total->save();
									}
								}
							}else{
							//nếu không, cập nhật number_ae đếm tổng số ae cùng ng giám hộ
								if($totalae){
									foreach ($totalae as $key => $total) {
										$total->setNumberAe (count($totalae) - 1);
										$total->save();
									}
								}
							}
						}	
					}

					$relative_student->setIsParentMain ( $is_parent_main );

					$relative_student->setIsParent ( $is_parent );

					$relative_student->setIsRole ( $is_role_avatar );

					$relative_student->setRoleService ( $is_role_service );

					$relative_student->setIorder ( $relation_order );

					$relative_student->setRelationshipId ( $relationship_id );

					$relative_student->save ();
						
				}
			}

			$conn->commit ();

		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$this->logMessage ( "ERROR CAP NHAT THONG TIN PHU HUYNH: " . $e->getMessage () );

			$conn->rollback ();
		}
	}

	// Lay danh sach phu huynh cua hoc sinh
	public function executeRelativeStudentId(sfWebRequest $request) {

		$student_id = $request->getParameter ( 's_id' );
		$ps_customer_id = myUser::getPscustomerID ();

		if ($student_id <= 0) {
			exit ( 0 );
		} else {

			$this->relative_students = Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student_id, $ps_customer_id )
				->execute ();

			return $this->renderPartial ( 'psRelativeStudent/option_relative_student', array (
					'option_select' => $this->relative_students ) );
		}
	}

	// Lay tat ca phu huynh cua be
	public function executeAllRelative(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'c_id' );
		$student_id = $request->getParameter ( 's_id' );

		if ($student_id <= 0) {
			exit ( 0 );
		} else {

			$this->relative_student = Doctrine::getTable ( 'RelativeStudent' )->sqlFindAllRelativeByStudent ( $student_id, $ps_customer_id )
				->execute ();

			return $this->renderPartial ( 'psRelativeStudent/option_all_relative', array (
					'option_select' => $this->relative_student ) );
		}
	}

	public function executeEdit(sfWebRequest $request) {

		$this->relative_student = $this->getRoute ()
			->getObject ();

		$ps_student_id = $this->relative_student->getStudentId ();

		$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );

		$this->form = $this->configuration->getForm ( $this->relative_student );

		$this->helper = new psRelativeStudentGeneratorHelper ();

		return $this->renderPartial ( 'psRelativeStudent/editSuccess', array (
				'relative_student' => $this->relative_student,
				'form' => $this->form,
				'ps_student' => $this->ps_student,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->relative_student = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->relative_student );

		$ps_student_id = $this->relative_student->getStudentId ();

		$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );

		$this->processForm ( $request, $this->form, $this->ps_student );
	}

	public function executeRelativeKeywords(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			
			$keywords = $request->getParameter ( 'keywords' );
			
			$html = $request->getParameter ( 'html' );
			
			$student_id = $request->getParameter ( 'student_id' );

			if ($student_id < 0) {
				exit ( 0 );
			}

			$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $student_id ) );

			$relative_student = new RelativeStudent ();

			$relative_student->setStudentId ( $student_id );

			$this->form = $this->configuration->getForm ( $relative_student );

			$this->form->setDefault ( 'keywords', $keywords );

			$this->pager = new sfDoctrinePager ( 'Relative', 35 );
			$this->pager->setQuery ( Doctrine::getTable ( 'Relative' )->setSqlAllRelativeForStudent ( $student_id, $this->ps_student->getPsCustomerId (), $keywords ) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();

			$this->list_relative = $this->pager->getResults ();

			$file_result = ($html == 'table') ? 'table_relative' : 'list_relative_main';

			return $this->renderPartial ( 'psRelativeStudent/' . $file_result, array (
					'relative_student' => $relative_student,
					'ps_student' => $this->ps_student,
					'list_relative' => $this->list_relative,
					'pager' => $this->pager,
					'form' => $this->form ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$this->formFilter->setWidget ( 'keywords', new sfWidgetFormInputText () );
		$this->formFilter->setValidator ( 'keywords', new sfValidatorString ( array (
				'required' => false ) ) );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'relative_filter[%s]' );

		$ps_student_id = $request->getParameter ( 'student_id' );

		if ($ps_student_id <= 0) {
			$this->forward404Unless ( $ps_student_id, sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );
		} else {

			$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

			$relative_student = new RelativeStudent ();

			$relative_student->setStudentId ( $ps_student_id );

			$this->form = $this->configuration->getForm ( $relative_student );

			$this->relative_student = $this->form->getObject ();

			$this->helper = new psRelativeStudentGeneratorHelper ();

			$this->pager = new sfDoctrinePager ( 'Relative', 35 );
			$this->pager->setQuery ( Doctrine::getTable ( 'Relative' )->setSqlAllRelativeForStudent ( $ps_student_id, $this->ps_student->getPsCustomerId () ) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();

			$this->list_relative = $this->pager->getResults ();

			return $this->renderPartial ( 'psRelativeStudent/formSuccess', array (
					'relative_student' => $this->relative_student,
					'form' => $this->form,
					'list_relative' => $this->list_relative,
					'ps_student' => $this->ps_student,
					'configuration' => $this->configuration,
					'helper' => $this->helper,
					'pager' => $this->pager,
					'formFilter' => $this->formFilter ) );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'relative_student' );

		$ps_student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';

		if ($ps_student_id <= 0)
			$this->forward404Unless ( $ps_student_id, sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );

		$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student->getId () ) );

		$relative_student = new RelativeStudent ();

		$relative_student->setStudentId ( $ps_student_id );

		$this->form = $this->configuration->getForm ( $relative_student );

		$this->processFormNew ( $request, $this->form, $ps_student );

		exit ( 0 );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$relative_student = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $relative_student ) ) );

		$ps_student_id = $relative_student->getStudentId ();

		$relative_id = $relative_student->getRelativeId ();

		$parent_main = $relative_student->getIsParentMain ();

		$st_student = $array_status = array();

		$st_student = PreSchool::loadCheckStatusStudent();

		$stt = 0;

		foreach($st_student as $key => $st){
			$array_status[$stt++] = $key;
			array_push($array_status);
		}

		if($parent_main == '1'){

			$student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $ps_student_id );
			if($student){
				$student->setRelativeId (null);
				$student->setNumberAe (null);
				$student->save();
			}
			
			$ps_customer_id = $student->ps_customer_id;
			$ps_workplace_id = $student->ps_workplace_id;

			//lây ra cơ sở đào tạo của học sinh hiện tại
			$ps_workplace = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
				->addwhere ('id =?',$ps_workplace_id)
				->addwhere ( 'ps_customer_id =?', $ps_customer_id )
				->fetchOne();

			//lấy ra danh sách học sinh có trùng người giám hộ, trạng thái theo array_status, sắp xếp theo id tăng dần là em
			$totalae = Doctrine_Query::create ()->from ( 'Student' )
				->addwhere ( 'relative_id =?', $relative_id )
				->whereIn ('status',$array_status)
				->orderBy('id ASC')
				->execute();	

			if($ps_workplace){
				//kiểm tra cấu hình giảm trừ của cơ sở, nếu có
				if($ps_workplace->is_reduce > 0){
					$giamtru = $ps_workplace->is_reduce;
					if($totalae){
						//cập nhật number_ae theo cấu hình giảm trừ
						foreach ($totalae as $key => $total) {
							if($key >= $giamtru){
								$total->setNumberAe (count($totalae) - 1);
							}else{
								$total->setNumberAe (0);
							}
							$total->save();
						}
					}
				}else{
				//nếu không, cập nhật number_ae đếm tổng số ae cùng ng giám hộ
					if($totalae){
						foreach ($totalae as $key => $total) {
							$total->setNumberAe (count($totalae) - 1);
							$total->save();
						}
					}
				}
			}
		}

		$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student->getId () ) );

		if ($relative_student->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'Remove relative assignment successfully.' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'Remove relative assignment failed.' );
		}

		$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
	}

	protected function processFormNew(sfWebRequest $request, sfForm $form) {

		$formValues = $request->getParameter ( 'form_relative_student' );

		$_formValues = $request->getParameter ( 'relative_student' );

		$ps_student_id = isset ( $_formValues ['student_id'] ) ? $_formValues ['student_id'] : '';

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$notice = 'Choose a parent for your baby successfully.';

		$st_student = $array_status = array();

		$st_student = PreSchool::loadCheckStatusStudent();

		$stt = 0;

		foreach($st_student as $key => $st){
			$array_status[$stt++] = $key;
			array_push($array_status);
		}

		try {

			foreach ( $formValues as $key => $values ) {
				$relative_id = $key;

				// Kiem tra trong relative_student
				if ($values ['relationship_id'] > 0) {

					$relativeStudentObj = Doctrine_Core::getTable ( 'RelativeStudent' )->checkRelativeStudentExits ( $ps_student_id, $relative_id );
					//kiểm tra có ng giám hộ hay chưa
					$checkparentStudent = Doctrine_Core::getTable ( 'RelativeStudent' )->checkparentStudentExits ($ps_student_id,1);

					//nếu đã có ng giám hộ
					if(count($checkparentStudent) > 0){
						//không tích chọn ng giám hộ mới được tiếp tục
						if ($values ['is_parent_main'] != 'on') {
							//chưa tồn tại mqh học sinh và phụ huynh sẽ đc thêm mới
							if (! $relativeStudentObj) {
								$relativeStudent = new RelativeStudent ();
								$relativeStudent->setStudentId ( $ps_student_id );
								$relativeStudent->setRelativeId ( $relative_id );
								$relativeStudent->setRelationshipId ( $values ['relationship_id'] );
								$relativeStudent->setIsParent ( isset ( $values ['is_parent'] ) ? 1 : 0 );
								$relativeStudent->setIsParentMain ( isset ( $values ['is_parent_main'] ) ? 1 : 0 );
								$relativeStudent->setIsRole ( isset ( $values ['is_role'] ) ? 1 : 0 );
								$relativeStudent->setRoleService ( isset ( $values ['role_service'] ) ? 1 : 0 );
								$relativeStudent->setIorder ( $values ['iorder'] );
								$relativeStudent->save ();
							}
						}
					}else{
					//chưa có ng giám hộ, chưa tồn tại mqh phụ huynh và học sinh sẽ thêm mới	
						if (! $relativeStudentObj) {
							$relativeStudent = new RelativeStudent ();
							$relativeStudent->setStudentId ( $ps_student_id );
							$relativeStudent->setRelativeId ( $relative_id );
							$relativeStudent->setRelationshipId ( $values ['relationship_id'] );
							$relativeStudent->setIsParent ( isset ( $values ['is_parent'] ) ? 1 : 0 );
							$relativeStudent->setIsParentMain ( isset ( $values ['is_parent_main'] ) ? 1 : 0 );
							$relativeStudent->setIsRole ( isset ( $values ['is_role'] ) ? 1 : 0 );
							$relativeStudent->setRoleService ( isset ( $values ['role_service'] ) ? 1 : 0 );
							$relativeStudent->setIorder ( $values ['iorder'] );
							$relativeStudent->save ();

							//nếu check phụ huynh đang thêm là ng giám hộ, cập nhật id ng giám hộ vào bảng học sinh
							if ($values ['is_parent_main'] == 'on') {

								$student_id = $relativeStudent->student_id;
								$relative_id = $relativeStudent->relative_id;

								//cập nhật id ng giám hộ vào bảng student
								$student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
								if($student){
									$student->setRelativeId ($relative_id);
									$student->save();
								}
								
								$ps_customer_id = $student->ps_customer_id;
								$ps_workplace_id = $student->ps_workplace_id;

								//lây ra cơ sở đào tạo của học sinh hiện tại
								$ps_workplace = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
									->addwhere ('id =?',$ps_workplace_id)
									->addwhere ( 'ps_customer_id =?', $ps_customer_id )
									->fetchOne();

								//lấy ra danh sách học sinh có trùng người giám hộ, trạng thái theo array_status, sắp xếp theo id tăng dần là em
								$totalae = Doctrine_Query::create ()->from ( 'Student' )
									->addwhere ( 'relative_id =?', $relative_id )
									->whereIn ('status',$array_status)
									->orderBy('id ASC')
									->execute();	

								if($ps_workplace){
									//kiểm tra cấu hình giảm trừ của cơ sở, nếu có
									if($ps_workplace->is_reduce > 0){
										$giamtru = $ps_workplace->is_reduce;
										if($totalae){
											//cập nhật number_ae theo cấu hình giảm trừ
											foreach ($totalae as $key => $total) {
												if($key >= $giamtru){
													$total->setNumberAe (count($totalae) - 1);
												}else{
													$total->setNumberAe (0);
												}
												$total->save();
											}
										}
									}else{
									//nếu không, cập nhật number_ae đếm tổng số ae cùng ng giám hộ
										if($totalae){
											foreach ($totalae as $key => $total) {
												$total->setNumberAe (count($totalae) - 1);
												$total->save();
											}
										}
									}
								}
								
							}
						}
					}
				}
			}
			if (! $relativeStudent) {
				$this->getUser ()
					->setFlash ( 'error', 'Choose a parent for your baby fail.' );

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
			}
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

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
				'object' => $relative_student ) ) );

		// }

		// else {

		// $this->getUser()->setFlash('error', 'Teacher assignment failed.');

		// $this->redirect('@ps_students_edit?id=' . $ps_student_id . '#pstab_2');
		// }
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$ps_student_id = $form->getObject ()
			->getStudentId ();

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			$notice = 'Relative assignment was updated successfully.';

			try {
				$relative_student = $form->save ();
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

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $relative_student ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
			}
		} else {

			$this->getUser ()
				->setFlash ( 'error', 'Relative assignment was updated fail.' );

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_2' );
		}
	}
}

