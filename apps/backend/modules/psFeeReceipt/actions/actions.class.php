<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeeReceiptGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeeReceiptGeneratorHelper.class.php';

/**
 * psFeeReceipt actions.
 *
 * @package kidsschool.vn
 * @subpackage psFeeReceipt
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeReceiptActions extends autoPsFeeReceiptActions {

	public function executeIndex(sfWebRequest $request) {

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		$ps_fee_receipt_id = $request->getParameter ( 'fbid' );

		if ($ps_fee_receipt_id > 0) {

			$ps_fee_receipt = Doctrine::getTable ( 'PsFeeReceipt' )->findOneById ( $ps_fee_receipt_id );

			$this->forward404Unless ( $ps_fee_receipt, sprintf ( 'Object does not exist.' ) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}

	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {

		$receipt_id = $request->getParameter ( 'receipt_id' );

		$student_id = $request->getParameter ( 'student_id' );

		$student = Doctrine_Core::getTable ( 'Student' )->getStudentByField ( $student_id,'id,first_name,last_name,student_code,ps_customer_id' );

		$records = Doctrine_Core::getTable ( 'PsFeeReceipt' )->getPsFeeReceiptByField ( $receipt_id,'id,number_push_notication,receipt_date' );

		if (! myUser::checkAccessObject ( $student, 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {

			echo $this->getContext ()
				->getI18N ()
				->__ ( 'Not roll data' );

			exit ( 0 );
		} else {

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				if ($records) {

					$records->setNumberPushNotication ( $records->getNumberPushNotication () + 1 );

					$records->save ();

					$receipt_date = $records->getReceiptDate ();

					$student_name = $student->getFirstName () . ' ' . $student->getLastName ();

					$student_code = $student->getStudentCode ();

					$ps_customer_id = $student->getPsCustomerId ();

					$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, $class_id = null, $student_id );

					if (count ( $list_received_id ) > 0) {

						$registrationIds_ios = array ();
						$registrationIds_android = array ();

						foreach ( $list_received_id as $user_nocation ) {

							if ($user_nocation->getNotificationToken () != '') {

								if ($user_nocation->getOsname () == 'IOS') {
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

							$setting->title = $psI18n->__ ( 'Notice of fee receipt' ) . date ( 'm-Y', strtotime ( $receipt_date ) );

							$setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ) . $student_name;

							$setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );

							$content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - ' . $student_name;

							$content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ( 'm-Y', strtotime ( $receipt_date ) ) . '. ';

							$setting->message = $content;

							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';

							// Lay avatar nguoi gui thong bao
							$profile = $this->getUser ()
								->getGuardUser ()
								->getProfileShort ();

							if ($profile && $profile->getAvatar () != '') {

								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}

							$setting->largeIcon = $largeIcon;

							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
							$setting->itemId = '0';
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

				return $this->renderPartial ( 'psFeeReceipt/load_number_notication', array (
						'ps_fee_receipt' => $records ) );
			} catch ( Exception $e ) {

				throw new Exception ( $e->getMessage () );

				$this->logMessage ( "ERROR SAVE DIEM DANH DEN: " . $e->getMessage () );

				$conn->rollback ();

				echo $this->getContext ()
					->getI18N ()
					->__ ( 'Classroom attendance was saved failed.' );

				exit ();
			}
		}
	}

	// Thanh toan
	protected function executeBatchPayment(sfWebRequest $request) {
		
		$ids = $request->getParameter ( 'ids' );
		
		if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
			->whereIn ( 'id', $ids )
			->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
			->whereIn ( 'id', $ids )
			->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
			->execute ();
		}
		
		foreach ( $records as $record ) {
			
			$record->setPaymentStatus ( PreSchool::ACTIVE );
			
			$record->save ();
		}
		
		$this->getUser ()
		->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been publish successfully.' ) );
		
		$this->redirect ( '@ps_fee_receipt' );
	}
	
	
	// Hien thi ra app phu huynh
	protected function executeBatchPublishReceipts(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {

			$record->setIsPublic ( 1 );

			$record->save ();
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'The selected items have been publish successfully.' ) );

		$this->redirect ( '@ps_fee_receipt' );
	}

	// Gui thong bao cho nhieu phu huynh
	protected function executeBatchPushNotication(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$dagui = $guiloi = 0;

		if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsFeeReceipt' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $key => $record ) {

			if ($key == 0) { // chi lay 1 lan
				$ps_customer_id = $record->getPsCustomerId ();
				$receipt_date = $record->getReceiptDate ();
			}

			if ($record->getIsPublic () > 0) { // neu trang thai cho phu huynh xem thi moi gui thong bao

				$student_id = $record->getStudentId ();

				$record->setNumberPushNotication ( $record->getNumberPushNotication () + 1 );

				$record->save ();

				$dagui ++;

				$student = Doctrine::getTable ( 'Student' )->getStudentName ( $student_id );

				$student_name = $student->getName ();

				$student_code = $student->getStudentCode ();

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

						$setting->title = $psI18n->__ ( 'Notice of fee receipt' ) . date ('m-Y', strtotime($receipt_date));

						$setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ) . $student_name;

						$setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );

						$content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - ' . $student_name;

						$content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ('m-Y', strtotime($receipt_date)) . '. ';

						$setting->message = $content;

						$setting->lights = '1';
						$setting->vibrate = '1';
						$setting->sound = '1';
						$setting->smallIcon = 'ic_small_notification';
						$setting->smallIconOld = 'ic_small_notification_old';

						// Lay avatar nguoi gui thong bao
						$profile = $this->getUser ()
							->getGuardUser ()
							->getProfileShort ();

						if ($profile && $profile->getAvatar () != '') {

							$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

							$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						} else {
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}

						$setting->largeIcon = $largeIcon;

						$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
						$setting->itemId = '0';
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
			} else {
				$guiloi ++;
			}
		}
		if ($dagui > 0 && $guiloi == 0) { // tat ca da duoc gui thong bao di
			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been send notication successfully.' ) );
		} elseif ($dagui == 0 && $guiloi == 0) { // khong co du lieu nao dung
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You must at least select one item is public' ) );
		} else {
			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'Some one item not save' ) );
		}
		$this->redirect ( '@ps_fee_receipt' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$ps_customer_id = $request->getParameter ( $form->getName () ) ['ps_customer_id'];
		if (! $ps_customer_id) {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		if ($form->isValid ()) {
			$check_new = $form->getObject ()
				->isNew ();
			try {
				$ps_fee_receipt = $form->save ();

				if ($check_new) {

					$notice = 'The item was created successfully.';

					$renderCode = 'PB' . date ( 'Ym' ) . '000' . $ps_fee_receipt->getId ();

					$ps_fee_receipt->setReceiptNo ( $renderCode );

					$ps_fee_receipt->setPsCustomerId ( $ps_customer_id );

					$ps_fee_receipt->save ();
				} else {
					$notice = 'The item was updated successfully.';
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
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_fee_receipt ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_fee_receipt_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_fee_receipt_edit',
						'sf_subject' => $ps_fee_receipt ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeExport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$class_id = null;

		$ps_school_year_id = null;

		//$export_filter = $request->getParameter ( 'export_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'export_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$this->exportReportFeeReceiptStudent ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id );
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {

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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => true ), array (
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
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
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

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'export_filter[%s]' );
	}

	protected function exportReportFeeReceiptStudent($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );

		$file_template_pb = 'ps_fee_receipt_student.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

		//$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );
		
		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $class_id, $ps_workplace_id );
		$class_name = $school_name -> getClName();
		$title_class = $this->getContext ()
			->getI18N ()
			->__ ( 'List student fee import' ) . $class_name;

		//$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );
		
		$students = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $ps_customer_id, $ps_workplace_id, $class_id )
			->execute ();

		$exportFile->loadTemplate ( $path_template_file );

		$title_xls = $class_name;

		$title_xls = substr($title_xls,0,30);
		
		$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_class, $title_xls );

		$exportFile->setDataExportFeeReceiptStudent ( $students );
		
		$exportFile->saveAsFile ( "QLTB_Phi_" . $class_name . ".xls" );
	}

	public function executeStudentSyntheticExport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$class_id = null;

		$ps_school_year_id = null;

		$export_filter = $request->getParameter ( 'export_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'export_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$ps_month = $value_student_filter ['ps_month'];

			$this->exportFeeReceiptStudentSynthetic ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month );
		}

		$this->ps_month = isset ( $value_student_filter ['ps_month'] ) ? $value_student_filter ['ps_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->ps_month );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					
					//'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),					
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
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

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}

		// echo $this->ps_workplace_id;

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}

	protected function exportFeeReceiptStudentSynthetic($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month) {

		$exportFile = new ExportStudentReportsHelper ( $this );

		$file_template_pb = 'bm_tonghopcongno.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

		$title_xls = "TongHopCongNo_" . date ( 'Ym', strtotime ( '01-' . $ps_month ) );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'Bang tong hop theo doi cong no thang' ) . $ps_month;

		$list_service = Doctrine::getTable ( 'Service' )->getListServiceOfSchool2 ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $enable_schedule = 1 );
		// Lay danh sach cac khoan phai thu
		$receivable_params = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );

		$list_receivables = Doctrine::getTable ( "Receivable" )->getListReceivableByParams ( $receivable_params );

		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, null, $ps_workplace_id );
		
		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		$ps_customer = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );

		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment) {
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();
		}
		$ConfigStartDateSystemFee = $ps_customer->getConfigStartDateSystemFee ();

		$all_data_fee = array ();

		// lay danh sach hoc sinh trong lop
		$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $class_id, $receivable_at, $ps_workplace_id );

		$exportFile->loadTemplate ( $path_template_file );

		$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_info, $title_xls );

		$exportFile->setDataExportStudentSynthetic ( $list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee );

		$exportFile->saveAsFile ( "TongHopCongNo_" . date ( 'Ym', strtotime ( '01-' . $ps_month ) ) . ".xls" );
	}

	public function executePaymentSynthetic(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		$ps_workplace_id = null;
		$this->date_at = date('d-m-Y');
		$this->list_student_payment = array();
		
		$export_filter = $request->getParameter ( 'export_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			$value_student_filter = $export_filter;
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$date_at = $value_student_filter ['date_at'];
			
			if($date_at ==''){$date_at = date('d-m-Y');}
			
			$this->list_student_payment = Doctrine::getTable('Receipt')->getListStudentFeeReceiptPayment($ps_customer_id,$ps_workplace_id,$date_at);
			
			//$this->exportFeeReceiptPaymentSynthetic ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id,$date_at );
		
		}else{
			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
			$date_at = date('d-m-Y');
			
			$this->list_student_payment = Doctrine::getTable('Receipt')->getListStudentFeeReceiptPayment($ps_customer_id,$ps_workplace_id,$date_at);
			
		}
		if ($export_filter) {
			
			$this->ps_workplace_id = isset ( $export_filter ['ps_workplace_id'] ) ? $export_filter ['ps_workplace_id'] : 0;
			
			$this->date_at = isset ( $export_filter ['date_at'] ) ? $export_filter ['date_at'] : date ( "d-m-Y" );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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
									'required' => false,
									'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}
		
		$this->formFilter->setDefault ( 'date_at', $this->date_at );
		
		$this->formFilter->setWidget ( 'date_at', new psWidgetFormFilterInputDate ( array (), array (
			'data-dateformat' => 'dd-mm-yyyy',
			'placeholder' => 'dd-mm-yyyy',
			'title' => _ ( 'Date at' ),
			'data-original-title' => $this->getContext ()
			->getI18N ()
			->__ ( 'Date at' ),
			'rel' => 'tooltip' ) ) );
			
		$this->formFilter->setValidator ( 'date_at', new sfValidatorDate ( array (
				'required' => false ) ) );
	
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'date_at', $this->date_at );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}

	public function executePaymentSyntheticExport(sfWebRequest $request) {
		// Get filters
		
		$ps_customer_id = $request->getParameter ( 'export_ps_customer_id' );
		$ps_workplace_id = $request->getParameter ( 'export_ps_workplace_id' );
		$date_at = $request->getParameter ( 'export_date_at' );
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
			if($ps_customer_id != myUser::getPscustomerID()){
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		$this->exportFeeReceiptPaymentSynthetic ($ps_customer_id, $ps_workplace_id,$date_at );
		
		$this->redirect('@ps_fee_receipt_payment_synthetic_export');
		
	}
	
	protected function exportFeeReceiptPaymentSynthetic($ps_customer_id, $ps_workplace_id,$date_at) {
		
		$exportFile = new ExportStudentReportsHelper ( $this );
		
		$file_template_pb = 'ps_fee_receipt_payment.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;
		
		$title_xls = "ThanhToan_" . date ( 'Ymd', strtotime ( $date_at ) );
		
		$title_info = $this->getContext ()->getI18N ()->__ ( 'Thong ke thanh toan theo ngay' ) . date ( 'd/m/Y', strtotime ( $date_at ) );
		
		$school_name = Doctrine::getTable('PsWorkPlaces')->getWorkPlacesByWorkPlacesId($ps_workplace_id);
		
		$list_student_payment = Doctrine::getTable('Receipt')->getListStudentFeeReceiptPayment($ps_customer_id,$ps_workplace_id,$date_at);
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$exportFile->setDataExportReceivableStatisticInfoExport ( $school_name, $title_info, $title_xls );
		
		$exportFile->setDataExportStudentSyntheticPayment ( $list_student_payment );
		//die;
		$exportFile->saveAsFile ( "TongHopThanhToan_" . date ( 'Ymd', strtotime ( $date_at ) ) . ".xls" );
	
	}
	
	public function executeImport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = $request->getParameter ( 'cid' );
		
		$ps_workplace_id = $request->getParameter ( 'wid' );
		
		$class_id = $request->getParameter ( 'clid' );
		
		$ps_school_year_id = $request->getParameter ( 'yid' );
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

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

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		}
		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
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

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
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

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $class_id );
		
		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	public function executeImportSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		$ps_customer_id = $request->getParameter ( 'cid' );
		
		$ps_workplace_id = $request->getParameter ( 'wid' );
		
		$class_id = $request->getParameter ( 'clid' );
		
		$ps_school_year_id = $request->getParameter ( 'yid' );
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' )) {
			
			$ps_customer_id = myUser::getPscustomerID ();
			
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
		
		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		}
		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		
		$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
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
		
		if ($ps_customer_id > 0) {
			
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;width:100%;",
							'required' => true,
							'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:150px;",
							'required' => true,
							'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
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
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'required' => true,
									'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
			'required' => true,
			'mime_types' => 'web_excel',
			'max_size' => $upload_max_size_byte ), array (
			'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
			'max_size' => sfContext::getInstance ()->getI18n ()
			->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
					'%value%' => $upload_max_size ) ) ) ) );
			
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $class_id );
				
		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$import_filter_file = $request->getFiles ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		// id lop hoc
		$class_id = $this->formFilter->getValue ( 'class_id' );

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		$check_date = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );

		$date_from = strtotime ( $check_date->getFromDate () );
		$date_to = strtotime ( $check_date->getToDate () );

		$students = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $ps_customer_id, $ps_workplace_id, $class_id )
			->execute ();

		//$array_student = array ();

		$_array_student = array ();

		foreach ( $students as $student ) {

			//array_push ( $array_student, $student->getStudentCode () );

			$_array_student [$student->getStudentCode ()] = $student->getId ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Receipt student import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'ReceiptStudent' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số hàng lớn nhất trong sheet

				$highestColumn = $provinceSheet->getHighestColumn (); // Lấy số cột lớn nhất trong sheet

				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

				$array_error = array ();
				$false = 0;
				$true = 0;
				$array_name = array ();
				// lay ra ten khoan thu
				for($k = 8; $k < $highestColumnIndex; $k ++) {

					$start = 4;
					$name_receipt = $provinceSheet->getCellByColumnAndRow ( $k, $start )
					->getCalculatedValue ();
					if ($name_receipt != '') {
						array_push ( $array_name, $name_receipt );
					}
				}
				// echo $array_name[0].'_'.$array_name[1].'_'.$array_name[2];
				// die;
				for($row = 6; $row < $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 2, $row ) ->getCalculatedValue ();

					if ($student_code != '' && array_key_exists ( $student_code, $_array_student )) {
						
						$receiva = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 3, $row )
						->getCalculatedValue ());
						
						// Neu de dinh dang là date
							if(is_numeric ($receiva)){
							
								$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($receiva));
							
							if($receivable_date != '1970-01-01'){
								$date_receivable = true;
							}else {
								$date_receivable = false;
							}
							
						}else{ // Neu de dinh dang la text
							
							$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $receiva ) ) ); // chuyển định dạng
							
							if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$date_receivable = true;
							} else {
								$date_receivable = false;
							}
							
						}
						
						$cannop = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 4, $row )
							->getCalculatedValue ());
						$danop = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 5, $row )
							->getCalculatedValue ());
						$conlai = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 6, $row )
							->getCalculatedValue ());
						$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
						->getCalculatedValue ();
						$str_number = strlen ( $note );
						$data_check = strtotime ( $receivable_date );
						$receipt_no = 'PB' . date ( 'Ym', strtotime ( $receivable_date ) );

						$dung = 0;
						if ($date_from <= $data_check && $data_check <= $date_to && $date_receivable == true) {
							$dung = 1;
						}

						if ($dung == 1 && $str_number < 500) {
							
							$student_id = null;

							$student_id = $_array_student[$student_code];
							
							// kiem tra xem da co du lieu chua
							$kiemtra = Doctrine_Core::getTable ( 'PsFeeReceipt' )->getCheckStudentAndDate ( $student_id, $receivable_date );

							if (! $kiemtra) {
								
								$true ++;
								$is_activated = PreSchool::NOT_ACTIVE;
								if(!is_numeric($cannop)){$cannop = 0;}
								if(!is_numeric($danop)){$danop = 0;}
								if(!is_numeric($conlai)){$conlai = 0;}elseif($conlai >= 0){$is_activated = PreSchool::ACTIVE;}
								
								$receipt_data = new PsFeeReceipt ();

								$receipt_data->setPsCustomerId ( $ps_customer_id );
								$receipt_data->setStudentId ( $student_id );
								$receipt_data->setReceivableAmount ( $cannop );
								$receipt_data->setCollectedAmount ( $danop );
								$receipt_data->setBalanceAmount ( $conlai );
								$receipt_data->setReceiptDate ( $receivable_date );
								$receipt_data->setPaymentStatus($is_activated);
								$receipt_data->setNote ( $note );
								$receipt_data->setUserCreatedId ( $user_id );
								$receipt_data->setUserUpdatedId ( $user_id );

								$receipt_data->setReceiptNo ( $ps_customer_id . time () );
								$receipt_data->save ();

								$receipt_id = $receipt_data->getId ();
								$receipt_no = $receipt_no . '-' . PreSchool::renderCode ( "%010s", $receipt_data->getId () );

								$receipt_data->setReceiptNo ( $receipt_no );

								$receipt_data->save ();

								$i = 0;
								for($k = 8; $k < $highestColumnIndex; $k ++) {
									$start = $row;
									$price = PreString::trim ($provinceSheet->getCellByColumnAndRow ( $k, $start )
										->getCalculatedValue ());
									$k ++;
									$quantity = PreString::trim ($provinceSheet->getCellByColumnAndRow ( $k, $start )
										->getCalculatedValue ());

									if (is_numeric($price)) {
										
										$receipt_student = new PsFeeReceivableStudent ();
										$receipt_student->setPsFeeReceiptId ( $receipt_id );
										$receipt_student->setStudentId ( $student_id );
										$receipt_student->setTitle ( $array_name [$i] );
										$receipt_student->setAmount ( $price );
										$receipt_student->setSpentNumber ( (int)$quantity );
										$receipt_student->setUserCreatedId ( $user_id );
										$receipt_student->setUserUpdatedId ( $user_id );
										$receipt_student->save ();
										
									}
									$i ++;
								}
							}else {
								$false ++;
								array_push ( $array_error, $row );
							}
						} else {
							$false ++;
							array_push ( $array_error, $row );
						}
					}
				}
				
				$error_line = implode ( ' ; ', $array_error );
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
				}
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_fee_receipt_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $e->getMessage ();
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_fee_receipt_import' );
		}

		if ($false == 0 && $true > 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} elseif($false > 0 && $true > 0) {

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
		}else{
			
			$error_number = $this->getContext () ->getI18N () ->__ ( 'Error : ' ) . $false;
			
			$error_array = $this->getContext () ->getI18N () ->__ ( 'Line' ) . $error_line;
			
			$this->getUser ()->setFlash ( 'error',$this->getContext () ->getI18N () ->__ ( 'Import file not found. You can check date or note.' ).$error_number.$error_array);
			
		}

		$this->redirect ( '@ps_fee_receipt_import?cid='.$ps_customer_id.'&wid='.$ps_workplace_id.'&yid='.$ps_school_year_id.'&clid='.$class_id );
	}

	public function executeExportReceivable(sfWebRequest $request) {

		$value_student_filter = $request->getParameter ( 'logtimes_filter' );

		$ps_customer_id = $value_student_filter ['ps_customer_id'];

		$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

		$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

		$class_id = $value_student_filter ['class_id'];

		$ps_month = $value_student_filter ['ps_month'];

		$ps_service = $value_student_filter ['ps_service'];

		$ps_receivable = $value_student_filter ['ps_receivable'];

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$this->exportReceivableStatistic ( $ps_customer_id, $ps_workplace_id, $class_id, $ps_school_year_id, $ps_month, $ps_receivable, $ps_service );
		
		$this->redirect ( '@ps_fee_receipt_statistic' );
	}

	protected function exportReceivableStatistic($ps_customer_id, $ps_workplace_id, $class_id, $ps_school_year_id, $ps_month, $ps_receivable, $ps_service) {

		$exportFile = new ExportStudentReportsHelper ( $this );

		$list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $ps_customer_id, $ps_workplace_id, $class_id, $ps_month )
			->execute ();

		if ($ps_service > 0) {
			$file_template_pb = 'ps_fee_receipt_statistic.xls';
			$date_ExportReceipt = '01-' . $ps_month;
			$date = date_create ( $date_ExportReceipt );
			date_modify ( $date, "-1 month" );
			$last_month = date_format ( $date, "Y-m-d" );
			$receivable_title = Doctrine::getTable ( 'Service' )->getServiceByField ( $ps_service,'id,title' )
				->getTitle ();
			$list_service = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableStudentByServiceId ( $ps_customer_id, $ps_month, $last_month, $ps_service );
		} elseif ($ps_receivable > 0) {
			$file_template_pb = 'ps_fee_receipt_statistic_2.xls';
			$receivable_title = Doctrine::getTable ( 'Receivable' )->getReceivableByField ( $ps_receivable,'id,title' )
				->getTitle ();
			$list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableStudentByReceivableId ( $ps_customer_id, $ps_month, $ps_receivable );
		}

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

		$title_xls = $receivable_title;

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'Statistic receivable %value% in month %value2%', array (
					'%value%' => $receivable_title ,
				'%value2%' => $ps_month ) );

		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $class_id, $ps_workplace_id );
		
		$exportFile->loadTemplate ( $path_template_file );

		$exportFile->setDataExportReceivableStatisticInfoExport ( $school_name, $title_info, $title_xls );

		if ($ps_service > 0) {
			$exportFile->setDataExportReceivableStudentStatistic ( $list_student, $list_service, $ps_month );
		} elseif ($ps_receivable > 0) {
			$exportFile->setDataExportReceivableStudentStatistic2 ( $list_student, $list_receivable, $ps_month );
		}

		$exportFile->saveAsFile ( $receivable_title . ".xls" );
	}

	// Ham thong ke khoan thu cua thang
	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_month = null;

		$ps_school_year_id = null;

		$this->class_id = null;

		$this->ps_receivable = $this->ps_service = null;
		
		$this->list_student = $this->list_receivable = $this->list_service = array ();

		$logtimes_filter = $request->getParameter ( 'logtimes_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $logtimes_filter;

			$this->ps_customer_id = $value_student_filter ['ps_customer_id'];

			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$this->ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$this->class_id = $value_student_filter ['class_id'];

			$this->ps_month = $value_student_filter ['ps_month'];
			
			if(isset($value_student_filter ['ps_service'])){
				$this->ps_service = $value_student_filter ['ps_service'];
			}
			
			if(isset($value_student_filter ['ps_receivable'])){
				$this->ps_receivable = $value_student_filter ['ps_receivable'] ;
			}
			
			$this->list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $this->ps_customer_id, $this->ps_workplace_id, $this->class_id, $this->ps_month )
				->execute ();

				if (isset($this->ps_receivable) && $this->ps_receivable > 0) {
				$this->receivable_title = Doctrine::getTable ( 'Receivable' )->getReceivableByField ( $this->ps_receivable,'id,title' )
					->getTitle ();
				$this->list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableStudentByReceivableId ( $this->ps_customer_id, $this->ps_month, $this->ps_receivable, null );
			} elseif ($this->ps_service > 0) {
				$date_ExportReceipt = '01-' . $this->ps_month;
				$date = date_create ( $date_ExportReceipt );
				date_modify ( $date, "-1 month" );
				$last_month = date_format ( $date, "Y-m-d" );
				$this->receivable_title = Doctrine::getTable ( 'Service' )->getServiceByField ( $this->ps_service,'id,title' )
					->getTitle ();
				$this->list_service = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableStudentByServiceId ( $this->ps_customer_id, $this->ps_month, $last_month, $this->ps_service );
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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

		$this->ps_month = isset ( $logtimes_filter ['ps_month'] ) ? $logtimes_filter ['ps_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->ps_month );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_service', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE, $this->ps_workplace_id, $this->ps_school_year_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select service-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select service-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_service', new sfValidatorDoctrineChoice ( array (
					'model' => 'Service',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_service', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select service-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select service-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_service', new sfValidatorPass () );
		}

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
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_service', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE, $this->ps_workplace_id, $this->ps_school_year_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select service-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select service-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_service', new sfValidatorDoctrineChoice ( array (
					'model' => 'Service',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_receivable', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Receivable',
					'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select receivable-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable', new sfValidatorDoctrineChoice ( array (
					'model' => 'Receivable',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_service', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select service-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select service-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_service', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_receivable', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select receivable-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_receivable', new sfValidatorPass () );
		}

		if ($this->ps_service > 0) {

			$this->formFilter->setWidget ( 'ps_receivable', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Receivable',
					'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select receivable-' ) ), array (
					'disabled' => 'disabled',
					'class' => 'select2',
					'style' => "min-width:150px;background-color:#fff",
					'required' => false,
					'data-placeholder' => _ ( '-Select receivable-' ) ) ) );
		}

		if ($this->ps_receivable > 0) {

			$this->formFilter->setWidget ( 'ps_service', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE, $this->ps_workplace_id, $this->ps_school_year_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select service-' ) ), array (
					'disabled' => 'disabled',
					'class' => 'select2',
					'style' => "min-width:150px;background-color:#fff",
					'required' => false,
					'data-placeholder' => _ ( '-Select service-' ) ) ) );
		}

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'ps_service', $this->ps_service );

		$this->formFilter->setDefault ( 'ps_receivable', $this->ps_receivable );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'logtimes_filter[%s]' );
	}
}
