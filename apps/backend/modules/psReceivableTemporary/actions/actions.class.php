<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceivableTemporaryGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceivableTemporaryGeneratorHelper.class.php';

/**
 * psReceivableTemporary actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceivableTemporary
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableTemporaryActions extends autoPsReceivableTemporaryActions {

	public function executeIndex(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		if ($this->filter_value ['ps_customer_id'] <= 0) {

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
			$this->setTemplate ( 'warning' );
		} else {
			$this->pager = $this->getPager ();
			$this->sort = $this->getSort ();
		}
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_receivable_temporary' );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			$this->redirect ( '@ps_receivable_temporary' );
		}

		if (! method_exists ( $this, $method = 'execute' . ucfirst ( $action ) )) {
			throw new InvalidArgumentException ( sprintf ( 'You must create a "%s" method for action "%s"', $method, $action ) );
		}

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( $action ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'PsReceivableTemporary' ) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_receivable_temporary' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$user_school_id = myUser::getPscustomerID ();

		$true = $false = 0;
		$user_id = myUser::getUserId ();
		$sfGuardUser = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( $user_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $sfGuardUser, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$records = Doctrine_Query::create ()->from ( 'PsReceivableTemporary' )
			->whereIn ( 'id', $ids )
			->execute ();

		if (count ( $records ) <= 0) {
			$loi = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been forward error.' );
			$this->getUser ()
				->setFlash ( 'error', $loi );
			$this->redirect ( '@ps_receivable_temporary' );
		}

		$conn = Doctrine_Manager::connection ();
		try {

			$conn->beginTransaction ();

			$array_fee = array ();

			foreach ( $records as $record ) {
				$student_id = $record->getStudentId ();
				$receivable_id = $record->getReceivableId ();
				$receivable_at = $record->getReceivableAt ();

				// check xem da co khoan phai thu chưa
				$check_receivable = Doctrine::getTable ( 'ReceivableStudent' )->checkReceivableStudentOfMonth ( $receivable_id, $student_id, $receivable_at );

				// Check phieu thu. Nếu đã có phiếu thu thì ko import vào ReceivableStudent nữa

				// $student_id,$receivable_at
				$ps_receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, strtotime ( $receivable_at ) );

				if ($check_receivable || $ps_receipt) {
					$false ++;
					if ($check_receivable) {
						array_push ( $array_fee, $check_receivable->getStudentName () );
					}
				} else {

					$true ++;

					$get_mumber = Doctrine::getTable ( 'ReceivableStudent' )->getCountMumberReceivableStudent ( $receivable_id, $student_id );

					$receivable_student = new ReceivableStudent ();
					$receivable_student->setStudentId ( $student_id );
					$receivable_student->setReceivableId ( $receivable_id );
					$receivable_student->setAmount ( $record->getAmount () );
					$receivable_student->setReceivableAt ( $receivable_at );

					$receivable_student->setReceiptDate ( $receivable_at );

					$receivable_student->setNote ( $record->getNote () );
					$receivable_student->setByNumber ( 1 );
					$receivable_student->setSpentNumber ( 1 );
					$receivable_student->setUnitPrice ( $record->getAmount () );
					$receivable_student->setIsLate ( 0 );
					$receivable_student->setIsNumber ( $get_mumber + 1 );
					$receivable_student->setUserCreatedId ( $user_id );
					$receivable_student->setUserUpdatedId ( $user_id );

					$receivable_student->save ();

					$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
							'object' => $record ) ) );

					$record->delete ();
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items forward failed.' );
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receipt_temporary' );
		}

		if ($false > 0) {

			$error_name = implode ( ' ; ', $array_fee );

			$loi = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been forward error.' );

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen name' ) . $error_name;

			if ($true > 0) {
				$success = $this->getContext ()
					->getI18N ()
					->__ ( 'The selected items have been forward successfully %value% data.', array (
						'%value%' => $true ) );
				$this->getUser ()
					->setFlash ( 'notice1', $success );
			}

			$this->getUser ()
				->setFlash ( 'notice5', $loi );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		} else {
			$success = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been forward successfully %value% data.', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $success );
		}
		$this->redirect ( '@ps_receivable_temporary' );
	}

	// Lay cac khoan phai thu khac theo truong, co so
	public function executeCustomerReceivable(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$yid = $request->getParameter ( "y_id" );

			$cid = $request->getParameter ( "psc_id" );

			$wp_id = $request->getParameter ( "wp_id" );

			$receivable = Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
					'ps_school_year_id' => $yid,
					'ps_customer_id' => $cid,
					'ps_workplace_id' => $wp_id ) )
				->execute ();

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $receivable ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeImport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_receivable_id = null;

		$ps_school_year_id = null;

		$ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
			// $member_id = myUser::getUser()->getMemberId();
			// $this->ps_workplace_id = myUser::getWorkPlaceId($member_id);
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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_receivable_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Receivable',
					'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_school_year_id' => $this->ps_school_year_id,
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id ) ),
					'add_empty' => _ ( '-Select receivable-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'Receivable',
					'required' => true ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_receivable_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select receivable-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;

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

		$this->formFilter->setDefault ( 'ps_receivable_id', $this->ps_receivable_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	protected function verifyDate($date, $format = 'Y-m-d') {

		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}

	protected function verifyTime($date, $format = 'H:i:s') {

		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}

	/**
	 * Import khoản phải thu khác
	 */
	public function executeImportSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_receivable_id = null;

		$ps_school_year_id = null;

		$ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_receivable_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Receivable',
					'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_school_year_id' => $this->ps_school_year_id,
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id ) ),
					'add_empty' => _ ( '-Select receivable-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'Receivable',
					'required' => true ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_receivable_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select receivable-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;

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

		$this->formFilter->setDefault ( 'ps_receivable_id', $this->ps_receivable_id );

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
		// id khoan phai thu
		$receivable_id = $this->formFilter->getValue ( 'ps_receivable_id' );

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($ps_school_year_id <= 0) {
			$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
				->getId ();
		}

		$check_date = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );

		$date_from = strtotime ( $check_date->getFromDate () );
		$date_to = strtotime ( $check_date->getToDate () );

		// echo $date_from.$date_to;die();

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id, $ps_workplace_id );

		$array_student = array ();

		$_array_student = array ();

		foreach ( $students as $student ) {

			array_push ( $array_student, $student->getStudentCode () );

			$_array_student [$student->getStudentCode ()] = $student->getId ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Receivable student import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'Receivable' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$array_error = array ();

				$false = 0;

				$true = 0;

				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					$amount = $provinceSheet->getCellByColumnAndRow ( 2, $row )
						->getValue ();

					$receiva = $provinceSheet->getCellByColumnAndRow ( 3, $row )
						->getValue ();

					$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $receiva ) ) ); // chuyển định dạng

					if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
						$date_receivable = true;
					} else {
						$date_receivable = false;
					}

					$note = $provinceSheet->getCellByColumnAndRow ( 4, $row )
						->getValue ();

					$str_number = strlen ( $note );

					$data_check = strtotime ( $receivable_date );

					// echo $date_from.'_'.$date_to.'_'.$data_check;
					$dung = 0;
					if ($date_from <= $data_check && $data_check <= $date_to && $date_receivable == true) {
						$dung = 1;
					}

					if ($student_code != '') {

						if (in_array ( $student_code, $array_student ) && $dung == 1 && $str_number < 255) {

							$true ++;

							$student_id = null;

							foreach ( $_array_student as $key => $_student_id ) {
								if ($key == $student_code) {
									$student_id = $_student_id;
									break;
								}
							}

							if ($student_id > 0) {

								$receivable_data = new PsReceivableTemporary ();

								$receivable_data->setStudentId ( $student_id );
								$receivable_data->setReceivableId ( $receivable_id );
								$receivable_data->setAmount ( $amount );
								$receivable_data->setReceivableAt ( $receivable_date );
								$receivable_data->setNote ( $note );
								$receivable_data->setUserCreatedId ( $user_id );
								$receivable_data->setUserUpdatedId ( $user_id );

								$receivable_data->save ();
							}
						} else {
							$false ++;
							array_push ( $array_error, $row );
						}
					}
				}
				$error_line = implode ( ' ; ', $array_error );
				// unlink($path_file . $filename);
				// echo $false; die();
				if ($true > 0) {
					// luu lich su import file phieu ghi no
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
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_receivable_temporary_import' );
				}
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receivable_temporary_import' );
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file failed.' ) . $e->getMessage ();
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receivable_temporary_import' );
		}

		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Line' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_receivable_temporary_import' );
	}
}
