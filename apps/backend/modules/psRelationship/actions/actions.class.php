<?php
require_once dirname ( __FILE__ ) . '/../lib/psRelationshipGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psRelationshipGeneratorHelper.class.php';

/**
 * psRelationship actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psRelationship
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRelationshipActions extends autoPsRelationshipActions {

	public function executeImport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_class_id = null;

		$ps_school_year_id = null;

		$ps_template = $ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

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

		if ($this->ps_workplace_id > 0) {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
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
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_template', new sfWidgetFormChoice ( array (
				'choices' => array (
						1 => _ ( 'Template 1' ),
						2 => _ ( 'Template 2' ) ) ), array (
				'class' => 'select2',
				'style' => "min-width:120px;",
				'required' => true,
				'placeholder' => _ ( '-Select template-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select template' ) ) ) );

		$this->formFilter->setValidator ( 'ps_template', new sfValidatorChoice ( array (
				'choices' => array (
						1,
						2 ),
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_template', $this->ps_template );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) ) );
						
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_template', $this->ps_template );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	public function executeImportSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_class_id = null;

		$ps_school_year_id = null;

		$ps_template = $ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ('ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

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

		if ($this->ps_workplace_id > 0) {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_template', new sfWidgetFormChoice ( array (
				'choices' => array (
						1 => _ ( 'Template 1' ),
						2 => _ ( 'Template 2' ) ) ), array (
				'class' => 'select2',
				'style' => "min-width:120px;",
				'required' => true,
				'placeholder' => _ ( '-Select template-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select template' ) ) ) );

		$this->formFilter->setValidator ( 'ps_template', new sfValidatorChoice ( array (
				'choices' => array (
						1,
						2 ),
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_template', $this->ps_template );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) ) );
						
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_template', $this->ps_template );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );
		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		// id lop hoc
		$ps_class_id = $this->formFilter->getValue ( 'ps_class_id' );

		$ps_template = $this->formFilter->getValue ( 'ps_template' );

		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		$stop_at = $schoolYearsDefault->getToDate ();

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		$array_relationship = array ();

		$relationship = Doctrine_Query::create ()->from ( 'Relationship' )
			->execute ();
		foreach ( $relationship as $relationships ) {
			$array_relationship [$relationships->getId ()] = PreString::strLower ( $relationships->getTitle () );
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Import student' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'Students' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$highestColumn = $provinceSheet->getHighestColumn (); // Lấy số cột lớn nhất trong sheet

				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

				// So hoc sinh import bi loi
				$number_student_error = $suahocsinh = 0;
				// Vi tri dong hoc sinh lỗi
				$arr_line_student_error = array ();

				// So hoc sinh import thanh cong
				$true = $nguoithan = 0;

				// So nguoi than import bi loi
				$er_relative = 0;

				// mã hoc sinh lỗi
				$er_student_code = array ();

				// Vi tri dòng nguoi thân bi loi
				$relative_error = array ();

				// Mang luu dia chi email bi loi: Khong dung dinh dang hoăc da ton tai
				$error_email_relative = array ();

				$bieumau = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 'A1' )
					->getCalculatedValue () );

				if ($ps_template == 1 && $bieumau == 'BM_IPHS01') {

					for($row = 3; $row <= $highestRow; $row ++) {
						
						$error_code = 0;
						
						// Lay du lieu hoc sinh tu file
						$student_code = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 0, $row )->getCalculatedValue () );

						$first_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 1, $row )
								->getCalculatedValue () );

						$last_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 2, $row )
								->getCalculatedValue () );

						$birthday_studen = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row )
								->getCalculatedValue () );
						
						// Neu de dinh dang là date
						if(is_numeric ($birthday_studen)){
							
							$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_studen));
							
							if($receivable_date != '1970-01-01'){
								$date_student = true;
							}else {
								$date_student = false;
							}
							
						}else{ // Neu de dinh dang la text
							
							$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_studen ) ) ); // chuyển định dạng
							
							if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$date_student = true;
							} else {
								$date_student = false;
							}
							
						}
						
						$nick_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 4, $row )->getCalculatedValue () );

						$gioitinh = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row )->getCalculatedValue () );

						if ($gioitinh != 0 && $gioitinh != 1) {
							$gioitinh = null;
						}

						$diachi = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 6, $row )->getCalculatedValue () );

						$ngayvaolop = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 7, $row )->getCalculatedValue () );
						
						// Neu de dinh dang là date
						if(is_numeric ($ngayvaolop)){
							
							$start_at = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayvaolop));
							
							if($start_at == '1970-01-01'){
								$start_at = date('Y-m-d');
							}
							
						}else{ // Neu de dinh dang la text
							
							$start_at = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayvaolop ) ) ); // chuyển định dạng
							
							if ($start_at == '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$start_at = date('Y-m-d');
							}
							
						}
						
						// end student
						$fs_name_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 10, $row )->getCalculatedValue () );

						$ls_name_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 11, $row )->getCalculatedValue () );

						$sex_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 12, $row )->getCalculatedValue () );

						if ($sex_re != 0 && $sex_re != 1) {
							$sex_re = null;
						}

						$birthday_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 13, $row )->getCalculatedValue () );

						// Neu de dinh dang là date
						if(is_numeric ($birthday_re)){
							
							$re_birthday = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_re));
							
							if($re_birthday == '1970-01-01'){
								$re_birthday = null;
							}
							
						}else{ // Neu de dinh dang la text
							
							$re_birthday = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_re ) ) ); // chuyển định dạng
							
							if ($re_birthday == '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$re_birthday = null;
							}
							
						}
						
						$phone = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 14, $row )->getCalculatedValue () );

						$email = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 15, $row )->getCalculatedValue () );

						$job = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 16, $row )->getCalculatedValue () );

						$address = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 17, $row )->getCalculatedValue () );

						// Vai tro
						$vaitro = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue () );

						// Nguoi bao tro chinh
						$is_main = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 9, $row )->getCalculatedValue () );

						if ($is_main != 0 && $is_main != 1) {
							$is_main = 0;
						}

						// end relative

						if ($first_name != '' && $last_name != '') {

							if ($date_student) {

								$student_id = 0; // sau moi lan chen hoc sinh moi thi khoi tao lai

								$student = new Student ();

								if ($student_code != '') {

									//$records = Doctrine_Query::create ()->from ( 'Student' )->where ( 'student_code =?', $student_code )->fetchOne ();									
									$records = Doctrine_Query::create ()->select('id,student_code')->from ( 'Student' )->where ( 'student_code =?', $student_code )->fetchOne ();

									if (! $records) {
										$student->setStudentCode ( $student_code );
									} else {
										$student->setStudentCode ( time () );
										$error_code = 1;
										array_push ( $er_student_code, $student_code );
									}
								} else {
									$error_code = 1;
									$student->setStudentCode ( time () );
								}

								$student->setPsCustomerId ( $ps_customer_id );
								$student->setPsWorkplaceId ( $ps_workplace_id );
								$student->setFirstName ( $first_name );
								$student->setLastName ( $last_name );
								$student->setBirthday ( $receivable_date );
								$student->setStartDateAt ( $start_at );
								$student->setCommonName ( $nick_name );
								$student->setYearData ( date ( 'Y' ) );
								$student->setSex ( $gioitinh );
								$student->setAddress ( $diachi );
								$student->setNationality ( 'VN' );
								$student->setIsImport ( 1 );
								$student->setUserCreatedId ( $user_id );
								$student->setUserUpdatedId ( $user_id );

								$student->save ();

								$student_id = $student->getId ();

								if ($student_id > 0) {

									$true ++;

									if ($error_code == 1) {
										$prefix_code = 'KS';
										$renderCode = $prefix_code . PreSchool::renderCode ( "%010s", $student_id );
										$student->setStudentCode ( $renderCode );
									}

									$student->save ();

									if ($ps_class_id > 0) { // chuyen hoc sinh vao lop

										$student_class = new StudentClass ();
										$student_class->setStudentId ( $student_id );
										$student_class->setMyclassId ( $ps_class_id );
										$student_class->setIsActivated ( 1 );
										$student_class->setMyclassMode ( 0 );
										$student_class->setStartAt ( $start_at ); // ngay vao lop
										$student_class->setStopAt ( $stop_at );
										$student_class->setType ( PreSchool::SC_STATUS_OFFICIAL );
										$student_class->setFromMyclassId ( null );
										$student_class->setUserCreatedId ( $user_id );
										$student_class->setUserUpdatedId ( $user_id );

										$student_class->save ();
										
										$student->setCurrentClassId($ps_class_id);
										$student->save();
									}
								} else {
									$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
									$number_student_error ++;
									array_push ( $arr_line_student_error, $row );
								}
							} else {
								$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
								$number_student_error ++;
								array_push ( $arr_line_student_error, $row );
							}
						}

						if ($vaitro != '' && isset ( $student_id ) && $student_id > 0) { // kiem tra vai tro, neu de trong thi khong them phu huynh

							if ($fs_name_re != '' && $ls_name_re != '') {

								$relative = new Relative ();

								$relative->setPsCustomerId ( $ps_customer_id );
								$relative->setPsWorkplaceId ( $ps_workplace_id );
								$relative->setFirstName ( $fs_name_re );
								$relative->setLastName ( $ls_name_re );
								$relative->setSex ( $sex_re );
								$relative->setYearData ( date ( 'Y' ) );
								$relative->setMobile ( $phone );
								$relative->setNationality ( 'VN' );
								$relative->setAddress ( $address );
								$relative->setBirthday ( $re_birthday );
								$relative->setJob ( $job );
								$relative->setUserCreatedId ( $user_id );
								$relative->setUserUpdatedId ( $user_id );

								$chk_email = true;

								if ($email != '') {
									$chk_email = false;
									if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_RELATIVE )) {
										$chk_email = true;
										$relative->setEmail ( $email );
									} else {
										// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
										array_push ( $error_email_relative, $email );
									}
								}

								$relative->save ();

								if ($relative->getId () > 0) {
									$nguoithan ++;
								}

								$quanhe = null;

								// neu hoc sinh import bi loi thi ko luu quan he, nhung van luu nguoi than

								if ($relative->getId () > 0 && isset ( $student_id ) && $student_id > 0) {

									// Chen vao bang Email
									if ($email != '' && $chk_email) {
										$ps_email = new PsEmails ();
										$ps_email->setPsEmail ( $email );
										$ps_email->setObjId ( $relative->getId () );
										$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
										$ps_email->save ();
									}

									// Chen du lieu moi quan he - nguoi than
									if (in_array ( PreString::strLower ( $vaitro ), $array_relationship )) {

										foreach ( $array_relationship as $key => $relatives ) {

											if ($relatives == PreString::strLower ( $vaitro )) {
												$quanhe = $key;
												break;
											}
										}

										$relative_student = new RelativeStudent ();

										$relative_student->setStudentId ( $student_id );

										$relative_student->setRelativeId ( $relative->getId () );
										$relative_student->setRelationshipId ( $quanhe );
										$relative_student->setIsParent ( $is_main );
										$relative_student->setIsRole ( $is_main );
										$relative_student->setIsParentMain ( $is_main );
										$relative_student->setRoleService ( $is_main );
										$relative_student->setUserCreatedId ( $user_id );
										$relative_student->setUserUpdatedId ( $user_id );

										$relative_student->save ();
									}
								} else {
									// loi them quan he giua phu huynh va hoc sinh
									$er_relationship ++;
									array_push ( $relationship_error, $row );
								}
							} else {
								// loi them phu huynh
								$er_relative ++;
								array_push ( $relative_error, $row );
							}
						}
					}
				} elseif ($ps_template == 2 && $bieumau == 'BM_IPHS02') {

					$list_class = Doctrine::getTable ( 'MyClass' ) -> getClassByCustomerGroup ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, null, null );
					$array_class = array();
					
					foreach ($list_class as $class){
						$array_class[strtolower($class->getCode())] = $class->getMcId() ;
					}
					
					// Danh sách mã ưu tiên
					$listPolicy = Doctrine_Query::create()->from( 'PsPolicyGroup' )->addwhere ('ps_customer_id=?', $ps_customer_id)
					->andWhere('ps_workplace_id=?',$ps_workplace_id)->execute();

					$array_policy = array();


					foreach($listPolicy as $policy){
						$array_policy[strtolower($policy->getPolicyCode())] = $policy->getId();
					}

					for($row = 4; $row <= $highestRow; $row ++) {
						
						$truonghopsuahocsinh = $error_code = 0;
						// Lay du lieu hoc sinh tu file
						$student_code = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue () );
						$ngayvaolop = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue () );
						/** 
						 * 	Truong hop sua thong tin hoc sinh va them moi phu huynh thong qua ma hoc sinh ($student_code)
						 *  Khong sua thong tin lop hoc cua hoc sinh
						 *  
						 **/
						if($student_code !=''){
							
							$records = Doctrine_Query::create ()->from ( 'Student' ) ->where ( 'student_code =?', $student_code )->fetchOne ();
							// Neu da ton tai ma hoc sinh
							if($records){
								// kiem tra xem co cung truong hay khong
								if($records -> getPsCustomerId() == $ps_customer_id){
									
									$suahocsinh ++;
									
									$truonghopsuahocsinh = 1;
									
									$student_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 2, $row )->getCalculatedValue () );
									$birthday_studen = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row ) ->getCalculatedValue () );
									$nick_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 4, $row ) ->getCalculatedValue () );
									$gioitinh = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row ) ->getCalculatedValue () );
									$diachi = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 6, $row ) ->getCalculatedValue () );
									
									$ma_lop = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 7, $row ) ->getCalculatedValue () );
									$hocthu7 = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 9, $row ) ->getCalculatedValue () );

									// end student
									
									if(PreString::trim($student_name) !=''){
										$array_name = PreString::getFullName ( $student_name );
										$first_name = $array_name ['first_name'];
										$last_name = $array_name ['last_name'];
									}
									
									// Neu de dinh dang là date
									if(is_numeric ($birthday_studen)){
										$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_studen));
										if($receivable_date != '1970-01-01'){
											$date_student = true;
										}else {
											$date_student = false;
										}
									}else{ // Neu de dinh dang la text
										$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_studen ) ) ); // chuyển định dạng
										if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$date_student = true;
										} else {
											$date_student = false;
										}
									}
									
									if ($gioitinh != 0 && $gioitinh != 1) {
										$gioitinh = null;
									}
									
									// Neu de dinh dang là date
									if(is_numeric ($ngayvaolop)){
										$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayvaolop));
										if($InvDate != '1970-01-01'){
											$start_at = $InvDate;
										}else {
											$start_at = date ( 'Y-m-d' );
										}
									}else{ // Neu de dinh dang la text
										$ngaybatdau = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayvaolop ) ) ); // chuyển định dạng
										if ($ngaybatdau != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$start_at = $ngaybatdau;
										} else {
											$start_at = date ( 'Y-m-d' );
										}
									}
									//echo $ngayvaolop;die;
									// Xet du lieu
									if($first_name !='' && $last_name != ''){
										$records -> setFirstName($first_name);
										$records -> setLastName($last_name);
									}
									if($nick_name !=''){
										$records -> setCommonName ( $nick_name );
									}
									if($date_student){
										$records -> setBirthday ($receivable_date);
									}
									if($gioitinh != null){
										$records -> setSex($gioitinh);
									}
									if($diachi !=''){
										$records ->setAddress($diachi);
									}

									$records ->setStartDateAt($start_at);

									$records -> save(); // luu du lieu
									
									$student_id = $records->getId();
									
									// Import phu huynh
									if (isset ( $student_id ) && $student_id > 0) {
										
										$i = 0;
										for($k = 10; $k < $highestColumnIndex; $k ++) {
											$start = $row;
											$k_name = $k + 2;
											// Lay ten
											$relative_name = $provinceSheet->getCellByColumnAndRow ( $k_name, $start )->getCalculatedValue ();
											
											if(PreString::trim($relative_name) !=''){
												
												$array_name = PreString::getFullName ( $relative_name );
												$fs_name_re = $array_name ['first_name'];
												$ls_name_re = $array_name ['last_name'];
												
												// Vai tro
												$vaitro = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												// Nguoi bao tro chinh
												$is_main = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												if ($is_main != 0 && $is_main != 1) {
													$is_main = 0;
												}
												
												$k ++;
												// Cot nay lay ten
												$k ++;
												
												$sex_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )->getCalculatedValue () );
												
												if ($sex_re != 0 && $sex_re != 1) {
													$sex_re = null;
												}
												
												$k ++;
												$birthday_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												// Neu de dinh dang là date
												if(is_numeric ($birthday_re)){
													
													$re_birthday = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_re));
													
													if($re_birthday == '1970-01-01'){
														$re_birthday = null;
													}
													
												}else{ // Neu de dinh dang la text
													
													$re_birthday = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_re ) ) ); // chuyển định dạng
													
													if ($re_birthday == '1970-01-01') { // Kiểm tra xem có đúng ngày không
														$re_birthday = null;
													}
													
												}
												
												$k ++;
												$phone = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$email = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$job = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$address = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												// Luu vao DB
												if ($fs_name_re != '' && $ls_name_re != '') {
													
													$relative = new Relative ();
													
													$relative->setPsCustomerId ( $ps_customer_id );
													$relative->setPsWorkplaceId ( $ps_workplace_id );
													$relative->setFirstName ( $fs_name_re );
													$relative->setLastName ( $ls_name_re );
													$relative->setSex ( $sex_re );
													$relative->setYearData ( date ( 'Y' ) );
													$relative->setMobile ( $phone );
													$relative->setNationality ( 'VN' );
													$relative->setAddress ( $address );
													$relative->setBirthday ( $re_birthday );
													$relative->setJob ( $job );
													$relative->setUserCreatedId ( $user_id );
													$relative->setUserUpdatedId ( $user_id );
													
													$chk_email = true;
													
													if ($email != '') {
														$chk_email = false;
														if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_RELATIVE )) {
															$chk_email = true;
															$relative->setEmail ( $email );
														} else {
															// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
															array_push ( $error_email_relative, $email );
														}
													}
													
													$relative->save ();
													
													if ($relative->getId () > 0) {
														$nguoithan ++;
													}
													
													$quanhe = null;
													
													// neu hoc sinh import bi loi thi ko luu quan he, nhung van luu nguoi than
													
													if ($relative->getId () > 0 && isset ( $student_id ) && $student_id > 0) {
														
														// Chen vao bang Email
														if ($email != '' && $chk_email) {
															$ps_email = new PsEmails ();
															$ps_email->setPsEmail ( $email );
															$ps_email->setObjId ( $relative->getId () );
															$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
															$ps_email->save ();
														}
														
														// Chen du lieu moi quan he - nguoi than
														if (in_array ( PreString::strLower ( $vaitro ), $array_relationship )) {
															
															foreach ( $array_relationship as $key => $relatives ) {
																
																if ($relatives == PreString::strLower ( $vaitro )) {
																	$quanhe = $key;
																	break;
																}
															}
														}
														
														if($quanhe > 0){ // Kiem tra moi quan he
															
															$relative_student = new RelativeStudent ();
															
															$relative_student->setStudentId ( $student_id );
															
															$relative_student->setRelativeId ( $relative->getId () );
															$relative_student->setRelationshipId ( $quanhe );
															$relative_student->setIsParent ( $is_main );
															$relative_student->setIsRole ( $is_main );
															$relative_student->setIsParentMain ( $is_main );
															$relative_student->setRoleService ( $is_main );
															$relative_student->setUserCreatedId ( $user_id );
															$relative_student->setUserUpdatedId ( $user_id );
															
															$relative_student->save ();
															
														}
													} else {
														// loi them quan he giua phu huynh va hoc sinh
														$er_relationship ++;
														array_push ( $relationship_error, $row );
													}
												}
											}else{
												$k=$k+8;
											}
										}
									}
								}
							}
						}
						
						if($truonghopsuahocsinh == 0){
							
							$student_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 2, $row )
									->getCalculatedValue () );
							
							if(PreString::trim($student_name) !=''){
							
								$array_name = PreString::getFullName ( $student_name );
								$first_name = $array_name ['first_name'];
								$last_name = $array_name ['last_name'];
		
								$birthday_studen = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row )
										->getCalculatedValue () );
								
								// Neu de dinh dang là date
								if(is_numeric ($birthday_studen)){
								
									$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_studen)); 
									
									if($receivable_date != '1970-01-01'){
										$date_student = true;
									}else {
										$date_student = false;
									}
									
								}else{ // Neu de dinh dang la text
									
									$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_studen ) ) ); // chuyển định dạng
									
									if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$date_student = true;
									} else {
										$date_student = false;
									}
									
								}
								
								$ma_uutien = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 4, $row )
										->getCalculatedValue () );
								$policy_id = null;
								if($ma_uutien!='' and $array_policy[strtolower($ma_uutien)] > 0 ){
									$policy_id = $array_policy[strtolower($ma_uutien)];
								}

								$gioitinh = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row )
										->getCalculatedValue () );
		
								if ($gioitinh != 0 && $gioitinh != 1) {
									$gioitinh = null;
								}
		
								$diachi = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 6, $row )
										->getCalculatedValue () );
		
								$class_id_import = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 7, $row )
									->getCalculatedValue () );
		
								$ngayvaolop = PreString::trim($provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue ());
								
								// Neu de dinh dang là date
								if(is_numeric ($ngayvaolop)){
								
									$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayvaolop)); 
									
									//echo $ngayvaolop.'_'.$InvDate.'<br/>';
									
									if($InvDate != '1970-01-01'){
										$start_at = $InvDate;
									}else {
										$start_at = date ( 'Y-m-d' );
									}
									
								}else{ // Neu de dinh dang la text
									
									$ngaybatdau = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayvaolop ) ) ); // chuyển định dạng
									
									//echo $ngayvaolop.'1_'.$ngaybatdau.'<br/>';
									
									if ($ngaybatdau != '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$start_at = $ngaybatdau;
									} else {
										$start_at = date ( 'Y-m-d' );
									}
									
								}
								
								$saturday_study = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 9, $row )
									->getCalculatedValue () ); // Học thứ 7
		
								if ($saturday_study != 1) {
									$saturday_study = 0;
								}
								// end student
								
								if ($first_name != '' && $last_name != '') {
		
									if ($date_student) {
		
										$student_id = 0; // sau moi lan chen hoc sinh moi thi khoi tao lai
		
										$student = new Student ();
										
										if ($student_code != '') {
		
											$records = Doctrine_Query::create ()->from ( 'Student' )
												->where ( 'student_code =?', $student_code )
												->fetchOne ();
		
											if (! $records) {
												$student->setStudentCode ( $student_code );
											} else {
												$student->setStudentCode ( time () );
												$error_code = 1;
												array_push ( $er_student_code, $student_code );
											}
										} else {
											$error_code = 1;
											$student->setStudentCode ( time () );
										}
										
										$student->setPsCustomerId ( $ps_customer_id );
										$student->setPsWorkplaceId ( $ps_workplace_id );
										$student->setFirstName ( $first_name );
										$student->setLastName ( $last_name );
										$student->setBirthday ( $receivable_date );
										$student->setCommonName ( null );
										$student->setPolicyId($policy_id);
										$student->setYearData ( date ( 'Y' ) );
										$student->setSex ( $gioitinh );
										$student->setAddress ( $diachi );
										$student->setNationality ( 'VN' );
										$student->setIsImport ( 1 );
										$student->setUserCreatedId ( $user_id );
										$student->setUserUpdatedId ( $user_id );
		
										$student->save ();
		
										$student_id = $student->getId ();
		
										if ($student_id > 0) {
		
											$true ++;
											
											if ($error_code == 1) {
												$prefix_code = 'A';
												$renderCode = $prefix_code . PreSchool::renderCode ( "%04s", $student_id );
												$student->setStudentCode ( $renderCode );
												$student->save ();
											}
											
											if ($class_id_import != '' and $array_class[strtolower($class_id_import)] > 0) {
												$ps_class_id = $array_class[strtolower($class_id_import)];
											}else{
												$ps_class_id = 0;
											}
											
											//print_r($array_class);

											//echo $ps_class_id;die;

											// chuyen hoc sinh vao lop
											if ($ps_class_id > 0) {
		
												$student_class = new StudentClass ();
												$student_class->setStudentId ( $student_id );
												$student_class->setMyclassId ( $ps_class_id );
												$student_class->setIsActivated ( 1 );
												$student_class->setMyclassMode ( $saturday_study ); // hoc thu 7
												$student_class->setStartAt ( $start_at ); // ngay vao lop
												$student_class->setStopAt ( $stop_at );
												$student_class->setType ( PreSchool::SC_STATUS_OFFICIAL );
												$student_class->setFromMyclassId ( null );
												$student_class->setUserCreatedId ( $user_id );
												$student_class->setUserUpdatedId ( $user_id );
		
												$student_class->save ();
												
												$student->setStartDateAt($start_at);
												$student->setCurrentClassId($ps_class_id);
												$student->save();
											}
										} else {
											$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
											$number_student_error ++;
											array_push ( $arr_line_student_error, $row );
										}
									} else {
										$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
										$number_student_error ++;
										array_push ( $arr_line_student_error, $row );
									}
		
									// Import phu huynh
									if (isset ( $student_id ) && $student_id > 0) {
		
										$i = 0;
										for($k = 10; $k < $highestColumnIndex; $k ++) {
											$start = $row;
											$k_name = $k + 2;
											// Lay ten
											$relative_name = $provinceSheet->getCellByColumnAndRow ( $k_name, $start )->getCalculatedValue ();
											
											if(PreString::trim($relative_name) !=''){
												
												$array_name = PreString::getFullName ( $relative_name );
												$fs_name_re = $array_name ['first_name'];
												$ls_name_re = $array_name ['last_name'];
												
												// Vai tro
												$vaitro = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												// Nguoi bao tro chinh
												$is_main = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												if ($is_main != 0 && $is_main != 1) {
													$is_main = 0;
												}
												
												$k ++;
												// Cot nay lay ten
												$k ++;
												
												$sex_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )->getCalculatedValue () );
												
												if ($sex_re != 0 && $sex_re != 1) {
													$sex_re = null;
												}
												
												$k ++;
												$birthday_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
			
												// Neu de dinh dang là date
												if(is_numeric ($birthday_re)){
													
													$re_birthday = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_re));
													
													if($re_birthday == '1970-01-01'){
														$re_birthday = null;
													}
													
												}else{ // Neu de dinh dang la text
													
													$re_birthday = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_re ) ) ); // chuyển định dạng
													
													if ($re_birthday == '1970-01-01') { // Kiểm tra xem có đúng ngày không
														$re_birthday = null;
													}
													
												}
												
												$k ++;
												$phone = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$email = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$job = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$address = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												
												// Luu vao DB
												if ($fs_name_re != '' && $ls_name_re != '') {
			
													$relative = new Relative ();
			
													$relative->setPsCustomerId ( $ps_customer_id );
													$relative->setPsWorkplaceId ( $ps_workplace_id );
													$relative->setFirstName ( $fs_name_re );
													$relative->setLastName ( $ls_name_re );
													$relative->setSex ( $sex_re );
													$relative->setYearData ( date ( 'Y' ) );
													$relative->setMobile ( $phone );
													$relative->setNationality ( 'VN' );
													$relative->setAddress ( $address );
													$relative->setBirthday ( $re_birthday );
													$relative->setJob ( $job );
													$relative->setUserCreatedId ( $user_id );
													$relative->setUserUpdatedId ( $user_id );
			
													$chk_email = true;
			
													if ($email != '') {
														$chk_email = false;
														if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_RELATIVE )) {
															$chk_email = true;
															$relative->setEmail ( $email );
														} else {
															// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
															array_push ( $error_email_relative, $email );
														}
													}
			
													$relative->save ();
			
													if ($relative->getId () > 0) {
														$nguoithan ++;
													}
			
													$quanhe = null;
			
													// neu hoc sinh import bi loi thi ko luu quan he, nhung van luu nguoi than
			
													if ($relative->getId () > 0 && isset ( $student_id ) && $student_id > 0) {
			
														// Chen vao bang Email
														if ($email != '' && $chk_email) {
															$ps_email = new PsEmails ();
															$ps_email->setPsEmail ( $email );
															$ps_email->setObjId ( $relative->getId () );
															$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
															$ps_email->save ();
														}
			
														// Chen du lieu moi quan he - nguoi than
														if (in_array ( PreString::strLower ( $vaitro ), $array_relationship )) {
			
															foreach ( $array_relationship as $key => $relatives ) {
			
																if ($relatives == PreString::strLower ( $vaitro )) {
																	$quanhe = $key;
																	break;
																}
															}
														}
														
														if($quanhe > 0){ // Kiem tra moi quan he
															
															$relative_student = new RelativeStudent ();
				
															$relative_student->setStudentId ( $student_id );
				
															$relative_student->setRelativeId ( $relative->getId () );
															$relative_student->setRelationshipId ( $quanhe );
															$relative_student->setIsParent ( $is_main );
															$relative_student->setIsRole ( $is_main );
															$relative_student->setIsParentMain ( $is_main );
															$relative_student->setRoleService ( $is_main );
															$relative_student->setUserCreatedId ( $user_id );
															$relative_student->setUserUpdatedId ( $user_id );
				
															$relative_student->save ();
															
														}
													} else {
														// loi them quan he giua phu huynh va hoc sinh
														$er_relationship ++;
														array_push ( $relationship_error, $row );
													}
												}
											}else{
												$k=$k+8;
											}
										}
									}
								}
							}
						}
					}
				} else {
					$error_template = 1;
				}
				//die;
				$error_line = implode ( ' ; ', $arr_line_student_error );

				$reletive_line = implode ( ' ; ', $relative_error );

				if ($true > 0 || $suahocsinh > 0) { // neu co du lieu dung thi luu lich su ko thi se unlink() va ko luu
				                 // luu lich su import student
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
				}
			}
			
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $e->getMessage () . $this->getContext ()->getI18N ()->__ ( 'Error try-catch.' );
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_students_relationship_import' );
		}

		if ($error_template == 1) {
			
			$error_import = $this->getContext () ->getI18N () ->__ ( 'Error template import' );
			$this->getUser () ->setFlash ( 'error', $error_import );
		
		} else {

			if ($number_student_error == 0 && $er_relative == 0 && $true > 0) { // neu tat ca deu dung

				$successfully = $this->getContext ()
					->getI18N ()
					->__ ( 'Student successfully : ' ) . $true;
				$this->getContext ()
					->getI18N ()
					->__ ( 'Student successfully : %value%, Relative successfully :%value1%', array (
						'%value%' => $true,
						'%value1%' => $nguoithan ) );
				$this->getUser ()
					->setFlash ( 'notice', $successfully );
			} elseif ($number_student_error == 0 && $er_relative > 0 && $true > 0) { // neu hoc sinh dung va phu huynh sai

				$successfully = $this->getContext ()
					->getI18N ()
					->__ ( 'Student successfully : ' ) . $true;
				$this->getUser ()
					->setFlash ( 'successfully', $successfully );

				$notice_relative_error = $this->getContext ()
					->getI18N ()
					->__ ( 'The number of additional relatives is faulty: %value%', array (
						'%value%' => $er_relative ) );
				$notice_relative_error_line = $this->getContext ()
					->getI18N ()
					->__ ( 'Lines: %value%', array (
						'%value%' => $reletive_line ) );

				$this->getUser ()
					->setFlash ( 'notice_relative_error', $notice_relative_error );
				$this->getUser ()
					->setFlash ( 'notice_relative_error_line', $notice_relative_error_line );
			} elseif($true > 0) { // co 1 so hoc sinh loi import

				$successfully = $this->getContext ()
					->getI18N ()
					->__ ( 'Student successfully : ' ) . $true;
				$this->getUser ()
					->setFlash ( 'successfully', $successfully );

				// So hoc sinh bi loi
				$notice_student_error = $this->getContext ()
					->getI18N ()
					->__ ( 'The number of additional students is faulty: %value%', array (
						'%value%' => $number_student_error ) );
				$notice_student_error_line = $this->getContext ()
					->getI18N ()
					->__ ( 'Lines: %value%', array (
						'%value%' => $error_line ) );

				$this->getUser ()
					->setFlash ( 'notice_student_error', $notice_student_error );
				$this->getUser ()
					->setFlash ( 'notice_student_error_line', $notice_student_error_line );

				$notice_relative_error = $this->getContext ()
					->getI18N ()
					->__ ( 'The number of additional relatives is faulty: %value%', array (
						'%value%' => $er_relative ) );
				$notice_relative_error_line = $this->getContext ()
					->getI18N ()
					->__ ( 'Lines: %value%', array (
						'%value%' => $reletive_line ) );

				$this->getUser ()
					->setFlash ( 'notice_relative_error', $notice_relative_error );
				$this->getUser ()
					->setFlash ( 'notice_relative_error_line', $notice_relative_error_line );
			}elseif($suahocsinh > 0){
				
				$successfully = $this->getContext () ->getI18N () ->__ ( 'Edit student and add relative' );
				$this->getUser () ->setFlash ( 'successfully', $successfully );
				
			}

			// Neu co loi ve email
			if (count ( $error_email_relative ) > 0) {

				$warning_email_text = $this->getContext ()
					->getI18N ()
					->__ ( 'The following email address is malformed or has existed: %error_email_relative%', array (
						'%error_email_relative%' => implode ( ' ; ', $error_email_relative ) ) );

				$this->getUser ()
					->setFlash ( 'warning_email', $warning_email_text );
			}

			// neu co loi them moi quan he cua hoc sinh voi phu huynh
			if ($er_relationship > 0) {

				$warning_relationship_text = $this->getContext ()
					->getI18N ()
					->__ ( 'Relationship error line: %relationship_error%', array (
						'%relationship_error%' => implode ( ' ; ', $relationship_error ) ) );

				$this->getUser ()
					->setFlash ( 'warning_email', $warning_relationship_text );
			}

			$text_code = implode ( ' ; ', $er_student_code );

			// Neu co loi ve ma hoc sinh
			if ($text_code != '') {

				$warning_student_code = $this->getContext ()
					->getI18N ()
					->__ ( 'The following student code in system: %er_student_code%', array (
						'%er_student_code%' => $text_code ) );

				$this->getUser ()
					->setFlash ( 'warning_student_code', $warning_student_code );
			}
		}
		$this->redirect ( '@ps_students_relationship_import' );
	}

	/**
	 * Import nhan su
	 *
	 * @param sfWebRequest $request
	 */
	public function executeImportmember(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

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

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) ) );
						
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_member[%s]' );
	}

	public function executeImportmemberSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

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

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) ) );
						
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_member[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_member' );

		$this->formFilter->bind ( $request->getParameter ( 'import_member' ), $request->getFiles ( 'import_member' ) );
		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );

		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		$stop_at = $schoolYearsDefault->getToDate ();

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		$array_relationship = array ();

		$relationship = Doctrine_Query::create ()->from ( 'Relationship' )
			->execute ();
		foreach ( $relationship as $relationships ) {
			$array_relationship [$relationships->getId ()] = $relationships->getTitle ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Import teacher' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'Students' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet
				
				$highestColumn = $provinceSheet->getHighestColumn (); // Lấy số cột lớn nhất trong sheet
				
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
				
				//echo $highestColumnIndex; die;
				
				$error_email_member = $array_error = array ();

				$index_class = $true = $false = $error_class = 0;

				// Bieu mau cu
				if($highestColumnIndex < 13){
				
					for($row = 4; $row <= $highestRow; $row ++) {
	
						$first_name = $provinceSheet->getCellByColumnAndRow ( 0, $row )
						->getCalculatedValue ();
	
						$last_name = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getCalculatedValue ();
	
						$birthday = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 2, $row )
						->getCalculatedValue ());
	
						// Neu de dinh dang là date
						if(is_numeric ($birthday)){
							
							$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday));
							
							if($receivable_date != '1970-01-01'){
								$date_student = true;
							}else {
								$date_student = false;
							}
							
						}else{ // Neu de dinh dang la text
							
							$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday ) ) ); // chuyển định dạng
							
							if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$date_student = true;
							} else {
								$date_student = false;
							}
							
						}
						
						$gioitinh = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 3, $row )
						->getCalculatedValue ());
	
						$phone = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 4, $row )
						->getCalculatedValue ());
	
						$email = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 5, $row )
						->getCalculatedValue ());
	
						$diachi = $provinceSheet->getCellByColumnAndRow ( 6, $row )
						->getCalculatedValue ();
	
						$so_cmnd = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 7, $row )
						->getCalculatedValue ());
	
						$ngaycap = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 8, $row )
						->getCalculatedValue ());
	
						$noicap = $provinceSheet->getCellByColumnAndRow ( 9, $row )
						->getCalculatedValue ();
						// Neu de dinh dang là date
						if(is_numeric ($ngaycap)){
							
							$ngaycap = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngaycap));
							
							if($ngaycap == '1970-01-01'){
								$ngaycap = null;
							}
							
						}else{ // Neu de dinh dang la text
							
							$ngaycap = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngaycap ) ) ); // chuyển định dạng
							
							if ($ngaycap == '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$ngaycap = null;
							}
							
						}
						
						if ($gioitinh != 0 && $gioitinh != 1) {
							$gioitinh = null;
						}
	
						if ($first_name != '' || $last_name != '' || $birthday != '') {
	
							if ($check_birthday == true) {
	
								$true ++;
	
								$ps_member = new PsMember ();
	
								$ps_member->setMemberCode ( time () );
								$ps_member->setPsCustomerId ( $ps_customer_id );
								$ps_member->setPsWorkplaceId ( $ps_workplace_id );
								$ps_member->setFirstName ( $first_name );
								$ps_member->setLastName ( $last_name );
								$ps_member->setBirthday ( $receivable_date );
								$ps_member->setMobile ( $phone );
								$ps_member->setSex ( $gioitinh );
								$ps_member->setAddress ( $diachi );
								$ps_member->setIdentityCard ( $so_cmnd );
								$ps_member->setCardDate ( $ngaycap );
								$ps_member->setCardLocal ( $noicap );
								$ps_member->setReligionId ( - 1 ); // Khong ton giao
								$ps_member->setYearData ( date ( 'Y' ) ); // Nam lam viec
								$ps_member->setNationality ( 'VN' ); // quoc tich Viet Nam
								$ps_member->setIsStatus ( PreSchool::HR_STATUS_WORKING ); // trang thai lam viec
								$ps_member->setIsImport ( 1 ); // la du lieu import
								$ps_member->setUserCreatedId ( $user_id );
								$ps_member->setUserUpdatedId ( $user_id );
	
								$chk_email = true;
	
								if ($email != '') {
									$chk_email = false;
									if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_TEACHER )) {
										$chk_email = true;
										$ps_member->setEmail ( $email );
									} else {
										// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
										array_push ( $error_email_member, $email );
									}
								} else {
									$ps_member->setEmail ( $email );
								}
	
								$ps_member->save ();
	
								$prefix_code = 'HR';
	
								$renderCode = $prefix_code . PreSchool::renderCode ( "%010s", $ps_member->getId () );
								$ps_member->setMemberCode ( $renderCode );
								$ps_member->save ();
	
								if ($ps_member->getId () > 0) {
									// Chen vao bang Email
									if ($email != '' && $chk_email) {
										$ps_email = new PsEmails ();
										$ps_email->setPsEmail ( $email );
										$ps_email->setObjId ( $ps_member->getId () );
										$ps_email->setObjType ( PreSchool::USER_TYPE_TEACHER );
										$ps_email->save ();
									}
								}
							} else {
								$false ++;
								array_push ( $array_error, $row );
							}
						}
					}
				}else{
					
					$list_class = Doctrine::getTable ( 'MyClass' ) -> getClassByCustomerGroup ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, null, null );
					$array_class = array();
					
					foreach ($list_class as $class){
						array_push($array_class, $class->getMcId());
					}
					
					$suanhansu = 0;
					
					for($row = 4; $row <= $highestRow; $row ++) {
						
						$er_member_code = $error_email_member = array();
						$first_name = $last_name = $birthday = '';
						$truonghopsuanhansu = $error_code = 0;
						
						$member_code = PreString::trim( $provinceSheet->getCellByColumnAndRow ( 0, $row )->getCalculatedValue () );
						
						if($member_code != ''){
							
							$records = Doctrine_Query::create ()->from ( 'PsMember' ) ->where ( 'member_code =?', $member_code )->fetchOne ();
							
							if($records && $records->getPsCustomerId() == $ps_customer_id){
								
								$suanhansu ++;
								
								$truonghopsuanhansu = 1;
								
								$full_name = $provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue ();
								
								if(PreString::trim($full_name) !=''){
									
									$array_name = PreString::getFullName ( $full_name );
									$first_name = $array_name ['first_name'];
									$last_name = $array_name ['last_name'];
									
									if($first_name !='' && $last_name != ''){
										$records -> setFirstName($first_name);
										$records -> setLastName($last_name);
									}
									
								}
								
								$birthday = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 2, $row )->getCalculatedValue ());
								
								if($birthday != ''){
									// Neu de dinh dang là date
									if(is_numeric ($birthday)){
										
										$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday));
										
										if($receivable_date != '1970-01-01'){
											$records -> setBirthday($receivable_date);
										}
										
									}else{ // Neu de dinh dang la text
										
										$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday ) ) ); // chuyển định dạng
										
										if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$records -> setBirthday($receivable_date);
										}
									}
								}
								
								$gioitinh = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 3, $row )->getCalculatedValue ());
								if ($gioitinh == 0 || $gioitinh == 1) {
									$records -> setSex($gioitinh);
								}
								$phone = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 4, $row )->getCalculatedValue ());
								if($phone != ''){
									$records -> setMobile ( $phone );
								}
								
								$email = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 5, $row )->getCalculatedValue ());
								
								$chk_email = true;
								
								if ($email != '') {
									$chk_email = false;
									if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_TEACHER )) {
										$chk_email = true;
										$records->setEmail ( $email );
									} else {
										// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
										array_push ( $error_email_member, $email );
									}
									// Chen vao bang Email
									if ($chk_email) {
										$ps_email = new PsEmails ();
										$ps_email->setPsEmail ( $email );
										$ps_email->setObjId ( $records->getId() );
										$ps_email->setObjType ( PreSchool::USER_TYPE_TEACHER );
										$ps_email->save ();
									}
									
								}
								
								$diachi = $provinceSheet->getCellByColumnAndRow ( 6, $row )->getCalculatedValue ();
								if($diachi != ''){
									$records -> setAddress ( $diachi );
								}
								
								$so_cmnd = $provinceSheet->getCellByColumnAndRow ( 7, $row )->getCalculatedValue ();
								if($so_cmnd != ''){
									$records -> setIdentityCard ( $so_cmnd );
								}
								
								$ngaycap = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue ());
								// Neu de dinh dang là date
								if($ngaycap !=''){
									if(is_numeric ($ngaycap)){
										$ngaycap = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngaycap));
										
										if($ngaycap != '1970-01-01'){
											$records->setCardDate ( $ngaycap );
										}
										
									}else{ // Neu de dinh dang la text
										
										$ngaycap = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngaycap ) ) ); // chuyển định dạng
										
										if ($ngaycap != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$records->setCardDate ( $ngaycap );
										}
										
									}
								}
								$noicap = $provinceSheet->getCellByColumnAndRow ( 9, $row )->getCalculatedValue ();
								if($noicap != ''){
									$records->setCardLocal ( $noicap );
								}
								$records-> save();
							}
							
						}
						if($truonghopsuanhansu == 0){
							
							$member_code = PreString::trim( $provinceSheet->getCellByColumnAndRow ( 0, $row )->getCalculatedValue () );
							
							$full_name = $provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue ();
							
							if(PreString::trim($full_name) !=''){
								
								$array_name = PreString::getFullName ( $full_name );
								$first_name = $array_name ['first_name'];
								$last_name = $array_name ['last_name'];
							
								$birthday = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 2, $row )->getCalculatedValue ());
								
								// Neu de dinh dang là date
								if(is_numeric ($birthday)){
									
									$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday));
									
									if($receivable_date != '1970-01-01'){
										$check_birthday = true;
									}else {
										$check_birthday = false;
									}
									
								}else{ // Neu de dinh dang la text
									
									$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday ) ) ); // chuyển định dạng
									
									if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$check_birthday = true;
									} else {
										$check_birthday = false;
									}
									
								}
								
								$gioitinh = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 3, $row )->getCalculatedValue ());
								
								$phone = $provinceSheet->getCellByColumnAndRow ( 4, $row )->getCalculatedValue ();
								
								$email = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 5, $row )->getCalculatedValue ());
								
								$diachi = $provinceSheet->getCellByColumnAndRow ( 6, $row )->getCalculatedValue ();
								
								$so_cmnd = $provinceSheet->getCellByColumnAndRow ( 7, $row )->getCalculatedValue ();
								
								$ngaycap = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue ());
								
								$noicap = $provinceSheet->getCellByColumnAndRow ( 9, $row )->getCalculatedValue ();
								
								$class_id = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 10, $row )->getCalculatedValue ());
								
								// Neu de dinh dang là date
								if(is_numeric ($ngaycap)){
									
									$ngaycap = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngaycap));
									
									if($ngaycap == '1970-01-01'){
										$ngaycap = null;
									}
									
								}else{ // Neu de dinh dang la text
									
									$ngaycap = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngaycap ) ) ); // chuyển định dạng
									
									if ($ngaycap == '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$ngaycap = null;
									}
									
								}
								
								if ($gioitinh != 0 && $gioitinh != 1) {
									$gioitinh = null;
								}
								
								if ($first_name != '' && $last_name != '' && $birthday != '') {
									
									if ($check_birthday == true) {
										
										$true ++;
										
										$ps_member = new PsMember ();
										
										if ($member_code != '') {
											
											$records = Doctrine_Query::create ()->from ( 'PsMember' )
											->where ( 'member_code =?', $member_code )
											->fetchOne ();
											
											if (! $records) {
												$ps_member->setMemberCode ( $student_code );
											} else {
												$ps_member->setMemberCode ( time () );
												$error_code = 1;
												array_push ( $er_member_code, $member_code );
											}
										} else {
											$error_code = 1;
										}
										
										$ps_member->setMemberCode ( time () );
										$ps_member->setPsCustomerId ( $ps_customer_id );
										$ps_member->setPsWorkplaceId ( $ps_workplace_id );
										$ps_member->setFirstName ( $first_name );
										$ps_member->setLastName ( $last_name );
										$ps_member->setBirthday ( $receivable_date );
										$ps_member->setMobile ( $phone );
										$ps_member->setSex ( $gioitinh );
										$ps_member->setAddress ( $diachi );
										$ps_member->setIdentityCard ( $so_cmnd );
										$ps_member->setCardDate ( $ngaycap );
										$ps_member->setCardLocal ( $noicap );
										$ps_member->setReligionId ( 4 ); // Khong ton giao
										$ps_member->setYearData ( date ( 'Y' ) ); // Nam lam viec
										$ps_member->setNationality ( 'VN' ); // quoc tich Viet Nam
										$ps_member->setIsStatus ( PreSchool::HR_STATUS_WORKING ); // trang thai lam viec
										$ps_member->setIsImport ( 1 ); // la du lieu import
										$ps_member->setUserCreatedId ( $user_id );
										$ps_member->setUserUpdatedId ( $user_id );
										
										$chk_email = true;
										
										if ($email != '') {
											$chk_email = false;
											if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_TEACHER )) {
												$chk_email = true;
												$ps_member->setEmail ( $email );
											} else {
												// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
												array_push ( $error_email_member, $email );
											}
										} else {
											$ps_member->setEmail ( $email );
										}
										
										$ps_member->save ();
										
										$prefix_code = 'HR';
										
										$renderCode = $prefix_code . PreSchool::renderCode ( "%010s", $ps_member->getId () );
										$ps_member->setMemberCode ( $renderCode );
										$ps_member->save ();
										
										$member_id = $ps_member->getId ();
										
										if ($member_id > 0) {
											// Chen vao bang Email
											if ($email != '' && $chk_email) {
												$ps_email = new PsEmails ();
												$ps_email->setPsEmail ( $email );
												$ps_email->setObjId ( $member_id );
												$ps_email->setObjType ( PreSchool::USER_TYPE_TEACHER );
												$ps_email->save ();
											}
											
											// Kiem tra lop hoc va phan lop giao vien
											if($class_id > 0 && in_array($class_id,$array_class)){
												
												$index_class ++;
												
												$ngaynhanlop = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 11, $row )->getCalculatedValue ());
												
												$ngayketthuc = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 12, $row )->getCalculatedValue ());
												
												$giaovienchunhiem = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 13, $row )->getCalculatedValue ());
												
												// Neu de dinh dang là date
												if(is_numeric ($ngaynhanlop)){
													
													$ngaynhanlop = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngaynhanlop));
													
													if($ngaynhanlop == '1970-01-01'){
														$ngaynhanlop = date('Y-m-d');
													}
													
												}else{ // Neu de dinh dang la text
													
													$ngaynhanlop = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngaynhanlop ) ) ); // chuyển định dạng
													
													if($ngaynhanlop == '1970-01-01'){
														$ngaynhanlop = date('Y-m-d');
													}
													
												}
												
												// Neu de dinh dang là date
												if(is_numeric ($ngayketthuc)){
													
													$ngayketthuc = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayketthuc));
													
													if($ngayketthuc == '1970-01-01'){
														$ngayketthuc = $stop_at;
													}
													
												}else{ // Neu de dinh dang la text
													
													$ngayketthuc = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayketthuc ) ) ); // chuyển định dạng
													
													if($ngayketthuc == '1970-01-01'){
														$ngayketthuc = $stop_at;
													}
													
												}
												
												if($giaovienchunhiem != 1){
													$giaovienchunhiem = 0;
												}
												
												$ps_teacher_class = new PsTeacherClass();
												$ps_teacher_class -> setPsMemberId($member_id);
												$ps_teacher_class -> setPsMyclassId($class_id);
												$ps_teacher_class -> setStartAt($ngaynhanlop);
												$ps_teacher_class -> setStopAt($ngayketthuc);
												$ps_teacher_class -> setIsActivated(PreSchool::ACTIVE);
												$ps_teacher_class -> setPrimaryTeacher($giaovienchunhiem);
												$ps_teacher_class -> setUserCreatedId($user_id);
												$ps_teacher_class -> setUserUpdatedId($user_id);
												
												$ps_teacher_class -> save();
											}else{
												$error_class ++;
												$text_error_class .= $row.',';
											}
										}
									} else {
										$false ++;
										array_push ( $array_error, $row );
									}
								}
							}
						}
					}
				}
				
				$error_line = implode ( ' ; ', $array_error );

				if ($true > 0) { // neu co du lieu dung thi luu lich su ko thi se unlink() va ko luu
				                 // luu lich su import student
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import member failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_member_import' );
				}
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Error failed import.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_member_import' );
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'Error failed import.' ).$e;
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_member_import' );
		}

		if ($false == 0) { // neu tat ca deu dung
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import member successfully.' );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else { // co 1 so nhan su loi import

			$successfully = $this->getContext () ->getI18N () ->__ ( 'Student successfully : ' ) . $true;
			$this->getUser ()
				->setFlash ( 'successfully', $successfully );

			$notice_relative_error = $this->getContext ()
				->getI18N ()
				->__ ( 'The number of additional relatives is faulty: %value%', array (
					'%value%' => $false ) );
			$notice_relative_error_line = $this->getContext ()
				->getI18N ()
				->__ ( 'Lines: %value%', array (
					'%value%' => $error_line ) );

			$this->getUser ()
				->setFlash ( 'notice_relative_error', $notice_relative_error );
			$this->getUser ()
				->setFlash ( 'notice_relative_error_line', $notice_relative_error_line );
		}
		
		// Neu tat ca giao vien duoc phan vao lop
		if($index_class > 0 && $error_class == 0){
			$successfully_class = $this->getContext () ->getI18N () ->__ ("import teacher class successfully");
			$this->getUser ()->setFlash ( 'successfully_class', $successfully_class );
		}elseif($index_class > 0 && $error_class > 0){
			$warning_class = $this->getContext () ->getI18N () ->__ ("The number of teacher not class %value%",array('%value%' => $text_error_class));
			$this->getUser ()->setFlash ( 'warning_class', $warning_class );
		}elseif($index_class = 0){
			$warning_class = $this->getContext () ->getI18N () ->__ ("Teacher not class");
			$this->getUser ()->setFlash ( 'warning_class', $warning_class );
		}

		// Neu co loi ve email
		if (count ( $error_email_member ) > 0) {

			$warning_email_text = $this->getContext ()
				->getI18N ()
				->__ ( 'The following email address is malformed or has existed: %error_email_relative%', array (
					'%error_email_relative%' => implode ( ' ; ', $error_email_member ) ) );

			$this->getUser ()
				->setFlash ( 'warning_email', $warning_email_text );
		}

		$this->redirect ( '@ps_member_import' );
	}
}
