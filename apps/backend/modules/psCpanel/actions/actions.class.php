<?php
/**
 * psCpanel actions.
 *
 * @package    Preschool
 * @subpackage errors
 * @author     Your name here
 * @version    1.0
 */
class psCpanelActions extends sfActions {

	public function executeLoadKcFinder(sfWebRequest $request) {
		
		//require_once $app_admin_module_web_dir.'/kcfinder/index.php';		
		
		//$browser = "kcfinder\\browser"; // To execute core/bootstrap.php on older
		//$browser = new $browser();      // PHP versions (even PHP 4)
		//$browser->action();
		//return $this->renderPartial ( 'psCpanel/kcFinder');	
		//return sfView::NONE;
		$languages = array ('en_GB','vi_VN' );
		return $languages;
	}
	
	public function executeChangeLanguage(sfWebRequest $request) {

		$languages = array ('en_GB','vi_VN' );

		if (in_array ( $request->getParameter ( 'lang' ), $languages )) {
			$this->getUser ()->setCulture ( $request->getParameter ( 'lang' ) );
		}

		//return $this->redirect ( '@localized_homepage' );
		return $this->redirect ( '@homepage' );
	}
	
	public function executePsHeaderFilterReset(sfWebRequest $request) {
		
		$this->getUser()->setAttribute('psHeaderFilter', null, 'admin_module');
		
		$this->getUser()->setAttribute('psHeaderFilter.ChoiceCustomerFilter', null, 'admin_module');
		
		$this->redirect('@homepage');
	}
	
	public function executePsHeaderFilter(sfWebRequest $request) {
		
		$ps_header_filter = new PsHeaderFormFilter($this->getUser()->getAttribute('psHeaderFilter', null, 'admin_module'));
		
		$ps_header_filter->bind($request->getParameter($ps_header_filter->getName()));
		
		if ($ps_header_filter->isValid()) {
			$this->getUser()->setAttribute('psHeaderFilter', $ps_header_filter->getValues(), 'admin_module');			
		} else {
			// co loi
			$this->forward404Unless (false, sprintf ( 'Object does not exist.' ) );
		}
		
		//$this->setVar('ps_header_filter', $ps_header_filter->getValues());
		
		$this->redirect('@homepage');
	}

	/**
	 * Executes index action
	 *
	 * @param sfRequest $request
	 *        	A request object
	 */
	public function executeDashboard(sfWebRequest $request) {

		/*
		if (myUser::isAdministrator()) {			
			
			$setting = new \stdClass ();
			
			$setting->registrationId = 'f-cZtVXUl0g:APA91bEgim6NrYqXraGISpAQelWgqDhbuV_dZlkL5ISSSRjIt91DvpDuT6Y0M46hQFCUTt0M-z5VVb8hRg47xkclh1nZcuXMVCoS1pkS9kCbxYp9sYKNND0uH-HGj-qeyvhDMnlrxMax';
			
			$registrationId_info = new PsGoogleAPI ( $setting );
			
			print_r($registrationId_info->getInfo());
			
			die;
			
		}
		*/
		
		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_CUSTOMER_FILTER_SCHOOL' )) {
			$ps_id = $request->getParameter ( 'cabcid', myUser::getPscustomerID () );
		} else {
			$ps_id = myUser::getPscustomerID ();
		}

		$ps_member_id = myUser::getUser ()->getMemberId ();
		
		$ps_workplace_id = null;
		
		if ($ps_member_id > 0)
			$ps_workplace_id = myUser::getWorkPlaceId ( $ps_member_id );

		$this->list_users = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, $ps_id, myUser::getUserId (), null, $ps_workplace_id )->execute ();

		$this->articles   = Doctrine::getTable ( 'psCmsArticles' )->getNewestArticles ( 10, $ps_id );

		$this->workplaces = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByCustomerId ($ps_id,PreSchool::ACTIVE);
		
		//$this->list_my_class    = Doctrine::getTable ( 'MyClass' )->getAttendancesOfClassByPsWorkplace (35, time());

		// Tuần trong năm cua ngay hien tai
		$ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );

		// Lay danh sach tuan cua nam
		$weeks = PsDateTime::getWeeksOfYear ( date ( 'Y' ) );

		$this->weeks_form = array ();
		if (isset ( $weeks [$ps_week - 1] )) {
			$this->weeks_form = $weeks [$ps_week - 1];
		}
		
		$schoolYearsDefault = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		
		if (! $schoolYearsDefault)
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		// TOP
		$this->total_workplaces = count ( $this->workplaces );

		// Lay tong so hoc sinh cua truong
		$this->total_student = Doctrine::getTable ( 'Student' )->getTotalStudentOfCustomerByDate ( $ps_id, date ( "Y-m-d" ), $schoolYearsDefault->id );

		$this->total_student_not_in_class = Doctrine::getTable ( 'Student' )->getTotalStudentNotInStudentClassOfCustomerByTime ( $ps_id, date ( "Y-m-d" ) );

		

		$this->total_class = 0;

		$this->schoolYears = '';

		// Ngày sinh học sinh
		$this->student_birthday = array ();

		if ($schoolYearsDefault) {

			$this->total_class = Doctrine::getTable ( 'MyClass' )->getTotalClassInYearOfCustomer ( $ps_id, $schoolYearsDefault->id );

			$this->schoolYears = $schoolYearsDefault->title;

			// Tháng băt đầu; kết thúc của năm học
			// $yearsDefaultStart = date ( "Y", strtotime ( $schoolYearsDefault->from_date ) );

			// $yearsDefaultEnd = date ( "Y", strtotime ( $schoolYearsDefault->to_date ) );

			$current_date = date ( "Ym" );

			$before_number_month = 3;

			$this->student_statistic = array ();

			for($i = $before_number_month; $i >= 0; $i --) {

				$before_month = date ( 'Y-m-d', strtotime ( "-" . $i . " month" ) );

				if ($current_date == date ( 'Ym', strtotime ( "-" . $i . " month" ) )) {
					$before_month_end_date = date ( 'd' );
				} else {
					// Lay ngay cuoi cung cua thang
					$before_month_end_date = date ( 't', strtotime ( $before_month ) );
				}

				// Ngay bat dau cua thang
				$start_date = date ( 'Y-m-', strtotime ( $before_month ) ) . '01';

				$end_date = date ( 'Y-m-', strtotime ( $before_month ) ) . $before_month_end_date;

				// Lay si so hoc sinh học thử
				$this->student_statistic [date ( 'm-Y', strtotime ( $before_month ) )] ['hoc_thu'] = Doctrine::getTable ( 'Student' )->getTotalStudentClassTypeOfCustomerByTime ( $ps_id, PreSchool::SC_STATUS_TEST, strtotime ( $start_date ), strtotime ( $end_date ) , $schoolYearsDefault->id);
				$this->student_statistic [date ( 'm-Y', strtotime ( $before_month ) )] ['chinh_thuc'] = Doctrine::getTable ( 'Student' )->getTotalStudentClassTypeOfCustomerByTime ( $ps_id, PreSchool::SC_STATUS_OFFICIAL, strtotime ( $start_date ), strtotime ( $end_date ) , $schoolYearsDefault->id);
			}

			// Hiển thị ngày sinh học sinh
			$class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
					'ps_school_year_id' => $schoolYearsDefault->id,
					'ps_customer_id'    => $ps_id,
					'is_activated'      => PreSchool::ACTIVE ) )
				->toArray ();

				$this->student_birthday = Doctrine::getTable ( 'StudentClass' )->getStudentsBirthdayOfMonth ( $ps_id, null, array_column ( $class, 'id' ) );			
		}

		// So tai khoan da cap cho phu huynh (Không tính phụ huynh hủy)
		$this->number_users = Doctrine::getTable ( 'sfGuardUser' )->totalUser ( $ps_id, PreSchool::USER_TYPE_RELATIVE );

		// Thống kê số tài khoản người thân hiện đang kích hoạt sử dụng app
		$this->number_users_active = Doctrine::getTable ( 'sfGuardUser' )->totalUserActivityApp ( $ps_id, PreSchool::USER_TYPE_RELATIVE );
		
		$this->number_users_online = Doctrine::getTable ( 'sfGuardUser' )->totalUserOnlineApp ( $ps_id, PreSchool::USER_TYPE_RELATIVE );
	}

	public function executeError404(sfWebRequest $request) {

		// if (! $this->getUser ()->hasFlash ( 'error' ))
		// $this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Page Not Found or The data you asked for is secure and you do not have proper credentials.' ) );
	}

	public function executeError500(sfWebRequest $request) {

		$this->getUser ()
			->setFlash ( 'error', $this->getContext ()
			->getI18N ()
			->__ ( 'System error.' ) );

		$this->setTemplate ( 'error404' );
	}

	public function executeError403(sfWebRequest $request) {

	}

	// Hien thi file anh
	public function executeShowImage(sfWebRequest $request) {

		/*
		 * $imageData = $registro[0]['imagen'];
		 * $response = $this->getResponse();
		 * $response->clearHttpHeaders();
		 * $response->setContentType('image/png');
		 * $response->setContent(base64_decode($imageData));
		 * return sfView:NONE;
		 */
	}

	// Hien thi file anh
	public function executeShowMemberThumb(sfWebRequest $request) {

		$path = sfConfig::get ( 'app_ps_data_dir' );

		$file_name = base64_decode ( $request->getParameter ( 'file_name' ) );

		$school_code = $request->getParameter ( 'school_code' );

		$path_file = $path . '/' . $school_code . '/hr/' . $file_name;

		if (ereg ( "\.", $path_file )) {
			$str = substr ( $path_file, (strrpos ( $path_file, "." ) + 1), strlen ( $path_file ) );
		}

		$list ["jpg"] = "image/jpeg";

		$list ["gif"] = "image/gif";

		$list ["png"] = "image/png";

		$content_type = $list [$str];

		/*
		 * header("Content-Type: ".$content_type."\n");
		 * header("Content-Length: ".$content_length."\n");
		 * echo $data;
		 */

		$img = new sfImage ( $path_file );

		$response = $this->getResponse ();

		$response->setContentType ( $img->getMIMEType () );

		$response->setContent ( $img );

		return sfView::NONE;
	}
}
