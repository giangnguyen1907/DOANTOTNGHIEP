<?php
require_once dirname ( __FILE__ ) . '/../lib/psAttendancesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psAttendancesGeneratorHelper.class.php';

/**
 * psAttendances actions.
 *
 * @package kidsschool.vn
 * @subpackage psAttendances
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAttendancesActions extends autoPsAttendancesActions {

    public function executeAttendance(sfWebRequest $request){
        
        $student_id = $request->getParameter('sid');
        
        $date_at = $request->getParameter('date');
        
        $this->tracked_at = date('Y-m-d',strtotime($date_at));
        
        $this->student = Doctrine::getTable('Student')->findOneById($student_id);
        
        if (! myUser::checkAccessObject ( $this->student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
            
            echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
            
            exit ( 0 );
        }
        
        $ps_customer_id = $this->student->getPsCustomerId();
        
        // Lay lop hoc cua hoc sinh tai thoi diem diem danh
        $infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $student_id, $this->tracked_at );
        // Nếu không xác định được lớp
        if (! $infoClass) {
            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
        }
        $this->ps_class_id = $infoClass->getClassId();
        $this->ps_workplace_id = $infoClass->getWpId();
        $this->school_year_id = $infoClass->getSchoolYearId();
        
        // Kiem tra xem co du lieu diem danh chua
        $this->ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $this->tracked_at );
        // danh sach nguoi than
        $this->list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $student_id, $ps_customer_id);
        // danh sach giao vien cua lop
        $this->list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ($this->ps_class_id, $this->tracked_at)->execute();
        
        //echo $ps_class_id.$this->tracked_at.$ps_customer_id;die;
        // Danh sach dich vu
        $this->list_service = Doctrine::getTable('Service')->getServicesDiaryByStudent($student_id, $this->ps_class_id, $this->tracked_at, $ps_customer_id);
        
    }
    
	public function executeDelete(sfWebRequest $request) {
		
		// $request->checkCSRFProtection ();
		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()->getObject () ) ) );
		
		$getFilter = $this->getFilters ();
		$class_id = $getFilter ['ps_class_id'];
		$date = $getFilter ['tracked_at'];
		
		$user_id = myUser::getUserId ();
		
		$student = $this->getRoute ()->getObject ()->getStudent ();
		
		$current_date = date ( "Ymd" );
		
		$check_current_date = (PsDateTime::psDatetoTime ( $this->getRoute ()->getObject ()->getLoginAt () ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai
		
		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		$logtime_id = $this->getRoute ()->getObject ()->getId ();
		
		$student_id = $student->getId ();
		
		$student_name = $student->getFirstName () . " " . $student->getLastName ();
		
		$login_at = $this->getRoute ()->getObject ()->getLoginAt ();
		
		$login_relative_id = $this->getRoute ()->getObject ()->getLoginRelativeId ();
		
		$login_relative_name = $this->getRoute ()->getObject ()->getRelativeLogin ()->getFirstName () . " " . $this->getRoute ()->getObject ()->getRelativeLogin ()->getLastName ();
		
		$logout_at = $this->getRoute ()->getObject ()->getLogoutAt ();
		
		$logout_relative_id = $this->getRoute ()->getObject ()->getLogoutRelativeId ();
		
		$login_relative_name = $this->getRoute ()->getObject ()->getRelativeLogout ()->getFirstName () . " " . $this->getRoute ()->getObject ()->getRelativeLogout ()->getLastName ();
		
		$login_member_id = $this->getRoute ()->getObject ()->getLoginMemberId ();
		
		$login_member_name = $this->getRoute ()->getObject ()->getPsMemberLogin ()->getFirstName () . " " . $this->getRoute ()->getObject ()->getPsMemberLogin ()->getLastName ();
		
		$logout_member_id = $this->getRoute ()->getObject ()->getLogoutMemberId ();
		
		$logout_member_name = $this->getRoute ()->getObject ()->getPsMemberLogout ()->getFirstName () . " " . $this->getRoute ()->getObject ()->getPsMemberLogout ()->getLastName ();
		
		$currentUser = myUser::getUser ();
		
		$records = Doctrine_Query::create ()->from ( 'StudentServiceDiary' )->addWhere ( 'student_id =?', $student_id )->andWhere ( 'DATE_FORMAT(tracked_at,"%Y%m%d") =?', date ( "Ymd", strtotime ( $login_at ) ) )->execute ();
		
		foreach ( $records as $record ) {
			$record->delete ();
		}
		
		$action = 'delete';
		
		$history_content = $this->getContext ()->getI18N ()->__ ( 'Student id' ) . ": " . $student_id . '\n' . $this->getContext ()->getI18N ()->__ ( 'Student name' ) . ": " . $student_name . '\n' . $this->getContext ()->getI18N ()->__ ( 'Login at' ) . ": " . $login_at . '\n' . $this->getContext ()->getI18N ()->__ ( 'Logout at' ) . ": " . $logout_at . '\n' . $this->getContext ()->getI18N ()->__ ( 'Login relative id' ) . ": " . $login_relative_id . '\n' . $this->getContext ()->getI18N ()->__ ( 'Login relative name' ) . ": " . $login_relative_name . '\n' . $this->getContext ()->getI18N ()->__ ( 'Login member id' ) . ": " . $login_member_id . '\n' . $this->getContext ()->getI18N ()->__ ( 'Login member name' ) . ": " . $login_member_name . '\n' . $this->getContext ()->getI18N ()->__ ( 'Logout relative id' ) . ": " . $logout_relative_id . '\n' . $this->getContext ()->getI18N ()->__ ( 'Logout relative name' ) . ": " . $logout_relative_name . '\n' . $this->getContext ()->getI18N ()->__ ( 'Logout member id' ) . ": " . $logout_member_id . '\n' . $this->getContext ()->getI18N ()->__ ( 'Logout member name' ) . ": " . $logout_member_name . '\n' . $this->getContext ()->getI18N ()->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . " " . $currentUser->getLastName () . '(' . $currentUser->getUsername () . ')' . '\n';
		
		$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $date );
		
		if ($number_attendances) {
			if ($logout_at != '') {
				$number_logout = $number_attendances->getLogoutSum ();
				$number_attendances->setLogoutSum ( $number_logout - 1 );
			}
			$number_login = $number_attendances->getLoginSum ();
			$number_attendances->setLoginSum ( $number_login - 1 );
			$number_attendances->setUserUpdatedId ( $user_id );
			$number_attendances->save ();
		}
		
		if ($this->getRoute ()->getObject ()->delete ()) {
			
			$logHistory = new PsHistoryLogtimes ();
			
			$logHistory->setPsLogtimeId ( $logtime_id );
			
			$logHistory->setPsAction ( $action );
			
			$logHistory->setHistoryContent ( $history_content );
			
			$logHistory->save ();
			
			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
		}
		
		$this->redirect ( '@ps_attendances' );
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );
		
		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );
			
			$this->redirect ( '@ps_attendances' );
		}
		
		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
		
		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );
			
			$this->redirect ( '@ps_attendances' );
		}
		
		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
		
		// $this->list_layout = 'list';
		
		$layout = $request->getParameter ( 'layout' ) ? 'list' : 'list_new';
		$this->list_layout = $layout;
		
		$this->title_page = 'Attendance go';
		
		$this->setTemplate ( 'index' );
	}

	public function executeIndex(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();
		
		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';
		
		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}
		
		if ($this->filter_value ['ps_class_id'] <= 0) {
			
			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
			$this->setTemplate ( 'warning' );
		} else {
			$this->pager = $this->getPager ();
			$this->sort = $this->getSort ();
		}
		
		if ($this->filter_value ['attendance_type'] == '1') { // Diem danh ve
			$this->list_layout = 'list_logout';
			$this->title_page = 'Attendance out';
		} else {
			
			$layout = $request->getParameter ( 'layout' ) ? 'list' : 'list_new';
			
			$this->list_layout = $layout; // Điểm danh đi học
			// $this->list_layout = 'list_new'; // Điểm danh nhiều trạng thái
			$this->title_page = 'Attendance go';
		}
	}
	
	// luu diem danh den
	public function executeSaveLogin(sfWebRequest $request) {

		$student_id = $request->getParameter ( 'student_id' );
		
		// Check role
		$ps_student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
		
		if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
			echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
			
			exit ( 0 );
		} else {
			
			$member = (int)$request->getParameter ( 'member' );
			$login_at = $request->getParameter ( 'login_at' );
			$note = $request->getParameter ( 'note' );
			$service = $request->getParameter ( 'service' );
			$class_id = $request->getParameter ( 'class_id' );
			$status = $request->getParameter ( 'status' );
			$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
			
			if ($status != 1) {
				$relative = 0;
			} else {
				$relative = (int)$request->getParameter ( 'relative' ); // lay ra phu huynh dua con di hoc
			}
			
			// Neu giao vien de trong thi lay id nguoi thao tac
			/*
			if($member = ''){
				$member = myUser::getUser()->getMemberId();
			}
			*/
			$arr_service = explode ( ',', $service ); // mang chua id dich vu
			
			if ($request->getParameter ( 'date_at' ) != '') {
				$date_at = $request->getParameter ( 'date_at' );
			} else {
				$date_at = date ( 'Y-m-d' );
			}
			
			$date_fomat = $date_at . ' ' . $login_at;
			
			$date = date ( 'Y-m-d H:i:s', strtotime ( $date_fomat ) );
			
			$ps_customer_id = $ps_student->getPsCustomerId ();
			
			$student_name = $ps_student->getFirstName () . ' ' . $ps_student->getLastName ();
			
			
			$relative_name = '';
			if ($relative > 0) {
				
				$relativeModel = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative );
				
				$relative_name = ($relativeModel) ? $relativeModel->getName() : '';
			
			}
			
			$member_name = '';
			if ($member > 0) {
				$memberModel = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member );				
				$member_name = ($memberModel) ? $memberModel->getName() : '';				
			}
			$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $ps_workplace_id );
			
			$start_sent_notifi = $ps_workplace->getFromTimeNoticationAttendances (); // gio ket thuc nhan thong bao diem danh den
			// $stop_sent_notifi = $ps_workplace -> getToTimeNoticationAttendances(); // gio ket thuc nhan thong bao
			
			$cauhinh = 0;
			
			if (strtotime ( $date_at ) == strtotime ( date ( 'Y-m-d' ) )) {
				
				if ($start_sent_notifi == "00:00:00") { // neu khong cau hinh thi gui thong bao
					
					$cauhinh = 1;
				} else { // Gui thong bao diem danh truoc gio cau hinh trong co so
					
					$config_time = date ( "Y-m-d H:i", strtotime ( date ( 'Y-m-d' ) . ' ' . $start_sent_notifi ) );
					
					$current_time = date ( "Y-m-d H:i", time () );
					
					if (strtotime ( $current_time ) <= strtotime ( $config_time ))
						$cauhinh = 1;
				}
			}
			
					
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				$records = Doctrine_Query::create ()->from ( 'StudentServiceDiary' )->addWhere ( 'student_id =?', $student_id )->andWhere ( 'DATE_FORMAT(tracked_at,"%Y%m%d") =?', date ( "Ymd", strtotime ( $date ) ) )->execute ();
				
				foreach ( $records as $record ) {
					$record->delete ();
				}
				
				if ($service != '') {
					
					foreach ( $arr_service as $services ) {
						
						$student_service_diary = new StudentServiceDiary ();
						
						$student_service_diary->setServiceId ( $services );
						
						$student_service_diary->setStudentId ( $student_id );
						
						$student_service_diary->setTrackedAt ( date ( 'Y-m-d', strtotime ( $date ) ) );
						
						$student_service_diary->setUserCreatedId ( myUser::getUserId () );
						
						$student_service_diary->save ();
						
						$service_name .= Doctrine::getTable ( 'Service' )->getServiceName ( $services )->getTitle () . ", ";
					}
				} else {
					$service_name = '';
				}
				
				$user_id = myUser::getUserId ();
				
				$ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $date );
				
				if (! $ps_logtimes) {
					
					$ps_logtimes = new PsLogtimes ();
					
					$ps_logtimes->setStudentId ( $student_id );
					
					$ps_logtimes->setLoginAt ( $date );
					
					$ps_logtimes->setLogValue ( $status );
					
					$ps_logtimes->setLoginRelativeId ( $relative );
					
					$ps_logtimes->setNote ( $note );
					
					$ps_logtimes->setLoginMemberId ( $member );
					
					$ps_logtimes->setUserUpdatedId ( $user_id );
					
					$ps_logtimes->setUserCreatedId ( $user_id );
					
					$ps_logtimes->save ();
					
					$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $date );
					
					if (! $number_attendances) {
						$number_attendances = new PsAttendancesSynthetic ();
						$number_attendances->setPsCustomerId ( $ps_customer_id );
						$number_attendances->setPsClassId ( $class_id );
						$number_attendances->setLoginSum ( 1 );
						$number_attendances->setLogoutSum ( 0 );
						$number_attendances->setTrackedAt ( $date_at );
						$number_attendances->setUserUpdatedId ( $user_id );
						$number_attendances->save ();
					} else {
						$number_log = $number_attendances->getLoginSum ();
						$number_attendances->setLoginSum ( $number_log + 1 );
						$number_attendances->save ();
					}
					
					// kiem tra cau hinh nhan thong bao cua co so va trang thai di hoc
					if ($cauhinh == 1 && $status == 1) {
						
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, $class_id, $student_id );
						
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						foreach ( $list_received_id as $user_nocation ) {
							
							if ($user_nocation->getNotificationToken () != '') {
								
								if ($user_nocation->getOsname () == 'IOS')
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								else
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
							}
						}
						
						$psI18n = $this->getContext ()->getI18N ();
						
						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();
							
							$setting->title = $psI18n->__ ( 'Notice of attendance of the baby' ) . " " . $student_name;
							
							if ($member_name != '')
								$setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . $member_name;
							else
								$setting->subTitle = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
							
							$setting->tickerText = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
							
							$content = $psI18n->__ ( 'Time login at' ) . ": " . $login_at . '. ';
							
							if ($relative_name != '')
								$content .= $psI18n->__ ( 'Relative login' ) . ": " . $relative_name . '. ';
							
							if ($member_name != '')
								$content .= $psI18n->__ ( 'Teacher receives' ) . ": " . $member_name;
							
							if ($service_name != '') {
								$content .= '. ' . $psI18n->__ ( 'Service use' ) . ": " . $service_name;
							}
							
							$setting->message = $content;
							
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							
							// Lay avatar nguoi lưu điểm danh
							$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
							
							if ($profile && $profile->getAvatar () != '') {
								
								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
								
								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}
							
							$setting->largeIcon = $largeIcon;
							
							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_ATTENDANCE;
							$setting->itemId = '0';
							$setting->clickUrl = '';
							
							//$setting->studentId = $student_id;
							
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
					} // end kiem tra cau hinh
				} else {
					
					$ps_logtimes->setLoginAt ( $date );
					
					$ps_logtimes->setLogValue ( $status );
					
					$ps_logtimes->setLoginRelativeId ( $relative );
					
					$ps_logtimes->setNote ( $note );
					
					$ps_logtimes->setLoginMemberId ( $member );
					
					$ps_logtimes->setUserUpdatedId ( $user_id );
					
					$ps_logtimes->save ();
				}
				
				// luu log diem danh cua hoc sinh
				
				if ($status == 1) {
					$trangthai = $this->getContext ()->getI18N ()->__ ( 'Go school' );
				} elseif ($status == 0) {
					$trangthai = $this->getContext ()->getI18N ()->__ ( 'Permission' );
				} else {
					$trangthai = $this->getContext ()->getI18N ()->__ ( 'Not Permission' );
				}
				
				$history_content = $this->getContext ()->getI18N ()->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login at' ) . ": " . $date . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative id' ) . ": " . $relative . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative name' ) . ": " . $relative_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member id' ) . ": " . $member . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member name' ) . ": " . $member_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Status' ) . ": " . $trangthai . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Used service' ) . ": " . $service_name . '<br/>';
				
				$historyLogtime = new PsHistoryLogtimes ();
				
				$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );
				
				$historyLogtime->setPsAction ( 'add' );
				
				$historyLogtime->setStudentId ( $student_id );
				
				$historyLogtime->setHistoryContent ( $history_content );
				
				$historyLogtime->save ();
				
				$check_logtime = false;
				
				$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $class_id, $date )->execute ();
				
				$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_student->getId (), $ps_student->getPsCustomerId () );
				
				$conn->commit ();
				
				return $this->renderPartial ( 'psAttendances/row_li_atten_new', array (
						'list_student' => $ps_logtimes,
						'check_logtime' => $check_logtime,
						'list_relative' => $list_relative,
						'list_member' => $list_member ) );
				
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$this->logMessage ( "ERROR SAVE DIEM DANH DEN: " . $e->getMessage () );
				
				$conn->rollback ();
				
				echo $this->getContext ()->getI18N ()->__ ( 'Classroom attendance was saved failed.' );
				
				exit ();
			}
			
			// die();
		} // end check roll
	}
	
	// luu diem danh ve
	public function executeSaveLogout(sfWebRequest $request) {

		$student_id = $request->getParameter ( 'student_id' );
		
		// Check role
		$ps_student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
		
		if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
			echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
			
			exit ( 0 );
		} else {
			
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				$member = (int)$request->getParameter ( 'member' );
				$logout_at = $request->getParameter ( 'logout_at' );
				$note = $request->getParameter ( 'note' );
				$class_id = $request->getParameter ( 'class_id' );
				$relative = (int)$request->getParameter ( 'relative' );
				$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
				
				if ($request->getParameter ( 'date_at' ) != '') {
					$date_at = $request->getParameter ( 'date_at' );
				} else {
					$date_at = date ( 'Y-m-d' );
				}
				
				$date_fomat = $date_at . ' ' . $logout_at;
				
				$date = date ( 'Y-m-d H:i:s', strtotime ( $date_fomat ) );
				
				$ps_customer_id = $ps_student->getPsCustomerId ();
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $ps_workplace_id );
				// $start_sent_notifi = $ps_workplace -> getFromTimeNoticationAttendances(); // gio bat dau nhan thong bao
				$stop_sent_notifi = $ps_workplace->getToTimeNoticationAttendances (); // gio ket thuc nhan thong bao
				
				$cauhinh = 0;
				
				if (strtotime ( $date_at ) == strtotime ( date ( 'Y-m-d' ) )) {
					
					if ($stop_sent_notifi == "00:00:00") { // neu khong cau hinh thi gui thong bao
						
						$cauhinh = 1;
					} else { // Gui thong bao diem danh truoc gio cau hinh trong co so
						
						$config_time = date ( "Y-m-d H:i", strtotime ( date ( 'Y-m-d' ) . ' ' . $stop_sent_notifi ) );
						
						$current_time = date ( "Y-m-d H:i", time () );
						
						if (strtotime ( $current_time ) <= strtotime ( $config_time )) // Gui thong bao diem danh ve truoc gio cau hinh cua co so
							$cauhinh = 1;
					}
				}
				
				$student_name = $ps_student->getFirstName () . ' ' . $ps_student->getLastName ();
				
				$relative_name = '';
				
				if ($relative > 0) {
					
					$relativeModel =  Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative );
					
					$relative_name = $relativeModel ? $relativeModel->getName() : '';
				}
				
				$member_name = '';
				
				if ($relative > 0) {
					
					$memberModel = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member );
					$member_name = ($memberModel) ? $memberModel->getName() : '';		
					
				}
				
				$ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $date );
				
				if (! $ps_logtimes) {
					echo $this->getContext ()->getI18N ()->__ ( 'Not isset data' );
					exit ( 0 );
				} else {
					
					// Nếu không phải cập nhật điểm danh về và cho phép gửi Notication
					if ($ps_logtimes->getLogoutRelativeId () <= 0 && $cauhinh == 1) {
						
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, $class_id, $student_id );
						
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						foreach ( $list_received_id as $user_nocation ) {
							if ($user_nocation->getNotificationToken () != '') {
								if ($user_nocation->getOsname () == 'IOS')
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								else
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
							}
						}
						
						$psI18n = $this->getContext ()->getI18N ();
						
						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();
							
							$setting->title = $psI18n->__ ( 'Update attendance record of the baby' ) . " " . $student_name;
							
							$setting->tickerText = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
							
							if ($member_name != '')
								$setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . $member_name;
							else
								$setting->subTitle = $setting->tickerText;
							
							
							
							$content = $psI18n->__ ( 'Time logout at' ) . ": " . $logout_at . '. ';
							
							if ($relative_name != '')
								$content .= $psI18n->__ ( 'Relative logout' ) . ": " . $relative_name . '. ';
							
							if ($member_name != '')
								$content .= $psI18n->__ ( 'Teacher handover' ) . ": " . $member_name;
							
							$setting->message = $content;
							
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							
							// Lay avatar nguoi gui
							$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
							
							if ($profile && $profile->getAvatar () != '') {
								
								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
								
								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}
							
							$setting->largeIcon = $largeIcon;
							
							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_ATTENDANCE;
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
						}
					}
					
					if ($ps_logtimes->getLogoutAt () == '') {
						
						$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $date );
						
						if ($number_attendances) {
							$number_log = $number_attendances->getLogoutSum ();
							$number_attendances->setLogoutSum ( $number_log + 1 );
							$number_attendances->setUserUpdatedId ( $user_id );
							$number_attendances->save ();
						}
					}
					
					$ps_logtimes->setLogoutAt ( $date );
					
					$ps_logtimes->setLogoutRelativeId ( $relative );
					
					$ps_logtimes->setNote ( $note );
					
					$user_id = myUser::getUserId ();
					
					$ps_logtimes->setLogoutMemberId ( $member );
					
					$ps_logtimes->setUserUpdatedId ( $user_id );
					
					// luu log diem danh ve muon cua hoc sinh
					
					$history_content = $this->getContext ()->getI18N ()->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login at' ) . ": " . $ps_logtimes->getLoginAt () . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout at' ) . ": " . $date . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative id' ) . ": " . $ps_logtimes->getLoginRelativeId () . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $ps_logtimes->getLoginRelativeId () ) ['name'] . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member id' ) . ": " . $ps_logtimes->getLoginMemberId () . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getMemberName ( $ps_logtimes->getLoginMemberId () ) ['name'] . '<br/>' . 

					$this->getContext ()->getI18N ()->__ ( 'Logout relative id' ) . ": " . $relative . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout relative name' ) . ": " . $relative_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout member id' ) . ": " . $member . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout member name' ) . ": " . $member_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>';
					
					$historyLogtime = new PsHistoryLogtimes ();
					
					$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );
					
					$historyLogtime->setPsAction ( 'edit' );
					
					$historyLogtime->setStudentId ( $student_id );
					
					$historyLogtime->setHistoryContent ( $history_content );
					
					$historyLogtime->save ();
					
					$ps_logtimes->save ();
					
					$check_logtime = false;
					$list_member = Doctrine::getTable ( 'PsTeacherClass' )->setTeachersByClassId ( $class_id, $date )->execute ();
					$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_student->getId (), $ps_student->getPsCustomerId () );
					
					$conn->commit ();
					
					return $this->renderPartial ( 'psAttendances/row_li_logout', array (
							'list_student' => $ps_logtimes,
							'check_logtime' => $check_logtime,
							'list_relative' => $list_relative,
							'list_member' => $list_member ) );
				}
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$this->logMessage ( "ERROR SAVE DIEM DANH VE: " . $e->getMessage () );
				
				$conn->rollback ();
				
				echo $this->getContext ()->getI18N ()->__ ( 'Classroom attendance was saved failed.' );
				
				exit ();
			}
		}
	}

	public function executeDeleted(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$year_month = null;
		
		$ps_school_year_id = null;
		
		$this->class_id = null;
		
		$this->filter_list_student = array ();
		
		$logtimes_filter = $request->getParameter ( 'logtimes_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'logtimes_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$this->class_id = $value_student_filter ['class_id'];
			
			$this->year_month = $value_student_filter ['year_month'];
			
			$this->filter_list_student = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesClassId ( $this->class_id, $this->year_month );
			
			$this->filter_list_logtime = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesStatistic ( $this->class_id, $this->year_month );
		}
		
		$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );
		
		// Lay thang hien tai
		// $current_month = $this->year_month;
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		if ($logtimes_filter) {
			
			$this->ps_school_year_id = isset ( $logtimes_filter ['ps_school_year_id'] ) ? $logtimes_filter ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $logtimes_filter ['ps_workplace_id'] ) ? $logtimes_filter ['ps_workplace_id'] : 0;
			
			$this->class_id = isset ( $logtimes_filter ['class_id'] ) ? $logtimes_filter ['class_id'] : 0;
			
			$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
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
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id ) ),
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
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'logtimes_filter[%s]' );
	}
	
	// Ham thong ke diem danh cua thang
	public function executeStatistic(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$year_month = null;
		
		$ps_school_year_id = null;
		
		$this->class_id = null;
		
		$this->filter_list_student = array ();
		
		$logtimes_filter = $request->getParameter ( 'logtimes_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $logtimes_filter;
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$this->class_id = $value_student_filter ['class_id'];
			
			$this->year_month = $value_student_filter ['year_month'];
			
			$this->filter_list_student = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesClassId ( $this->class_id, $this->year_month );
			
			$this->filter_list_logtime = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesStatistic ( $this->class_id, $this->year_month );
		}
		
		$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );
		
		$this->formFilter->setValidator ( 'year_month', new sfValidatorPass());
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		if ($logtimes_filter) {
			
			$this->ps_school_year_id = isset ( $logtimes_filter ['ps_school_year_id'] ) ? $logtimes_filter ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $logtimes_filter ['ps_workplace_id'] ) ? $logtimes_filter ['ps_workplace_id'] : 0;
			
			$this->class_id = isset ( $logtimes_filter ['class_id'] ) ? $logtimes_filter ['class_id'] : 0;
			
			$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
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
							'ps_school_year_id' => $this->ps_school_year_id ) ),
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
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'logtimes_filter[%s]' );
	}
	
	// Xuat so diem danh
	public function executeStatisticExport(sfWebRequest $request) {
	    // Get filters
		$class_id = $request->getParameter ( 'export_class_id' );
		$month_year = $request->getParameter ( 'export_year_month' );
		
		$class_name = Doctrine::getTable ( 'MyClass' )->getClassName($class_id);
		
		$ps_customer_id = $class_name->getPsCustomerId();
		
	    // kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
	    if (! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL')) {
	        if($ps_customer_id != myUser::getPscustomerID()){
	            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
	        }
	    }
	    
	    $this->exportReportLogtimesStatistic($class_id, $month_year,$ps_customer_id);
	    
	    $this->redirect('@ps_attendances_statistic');
	    
	}
	
	protected function exportReportLogtimesStatistic($class_id, $month_year,$ps_customer_id)
	{
	    $exportFile = new ExportStudentLogtimesReportHelper($this);
	    
	    $file_template_pb = 'tkhs_sodiemdanh_00001.xls';
	    
	    $path_template_file = sfConfig::get('sf_web_dir') . '/pschool/template_export/' . $file_template_pb;
	    
	    //$school_name = Doctrine::getTable('Pscustomer')->findOneBy('id', $ps_customer_id);
	    
	    $school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $class_id, $workplace_id=NULL );
	    
	    $filter_list_student = Doctrine::getTable('PsLogtimes')->getStudentsLogtimesClassId($class_id,$month_year);
	    
	    $filter_list_logtime = Doctrine::getTable('PsLogtimes')->getStudentsLogtimesStatistic($class_id,$month_year);
	    
	    $date_at = date('m/Y',strtotime('01-'.$month_year));
	    
	    $number_day = PsDateTime::psNumberDaysOfMonth ( $month_year );
	    
	    $exportFile->loadTemplate($path_template_file);
	    
	    $class_name = $school_name->getClName();
	    
	    $title_info = $this->getContext ()->getI18N ()->__ ( 'Statistic track book' ).$date_at;
	    
	    $title_xls =  substr( $class_name,0,30);
	    
	    $infoclass = $this->getContext ()->getI18N ()->__ ('Class %value1% mumber student %value2%', array('%value1%' => $class_name, '%value2%'=>count($filter_list_student)));
	    
	    $exportFile->setDataExportStatisticInfoExport($school_name, $title_info,$title_xls);
	    
	    $exportFile->setDataExportStatistic($filter_list_student,$filter_list_logtime,$month_year,$infoclass);
	    
	    $exportFile->saveAsFile("BDD".$school_name->getId().'_'. date('Ym',strtotime('01-'.$month_year)) . ".xls");
	
	}
	// Ham thong ke giao vien thao tac
	public function executeManipulation(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$year_month = null;
		
		$ps_school_year_id = null;
		
		$ps_department_id = null;
		
		$this->filter_list_student = array ();
		
		$logtimes_filter = $request->getParameter ( 'logtimes_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'logtimes_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$department_id = $value_student_filter ['ps_department_id'];
			
			$year_month = $value_student_filter ['year_month'];
			
			$date_at = $value_student_filter ['date_at'];
			
			$this->filter_list_member = Doctrine::getTable ( 'PsMember' )->getPsMemberByDepartmentOrWorkplace ( $ps_customer_id, $ps_workplace_id, $department_id, $year_month, $date_at );
			
			$this->list_attendances = Doctrine::getTable ( 'PsLogtimes' )->getUserUpdatedOfMonth ( $ps_workplace_id, $year_month, $date_at );
		}
		
		$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );
		
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		if ($logtimes_filter) {
			
			$this->ps_school_year_id = isset ( $logtimes_filter ['ps_school_year_id'] ) ? $logtimes_filter ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $logtimes_filter ['ps_workplace_id'] ) ? $logtimes_filter ['ps_workplace_id'] : 0;
			
			$this->ps_department_id = isset ( $logtimes_filter ['ps_department_id'] ) ? $logtimes_filter ['ps_department_id'] : 0;
			
			$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
			
			$this->date_at = isset ( $logtimes_filter ['date_at'] ) ? $logtimes_filter ['date_at'] : '';
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
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
					'choices' => array(
							'' => _ ( '-Select workplace-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}
		// Filters department
		
		$this->formFilter->setWidget ( 'ps_department_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'query' => Doctrine::getTable ( 'PsDepartment' )->setDepartmentByWorkplaceId ( $this->ps_workplace_id, $this->ps_customer_id ),
				'add_empty' => _ ( '-Select department-' ) 
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select department-' ) 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_department_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'required' => true 
		) ) );
		
		$this->formFilter->setWidget ( 'date_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );
		
		$this->formFilter->setValidator ( 'date_at', new sfValidatorDate ( array (
				'required' => true ) ) );
		
		$this->formFilter->setDefault ( 'date_at', $this->date_at );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'logtimes_filter[%s]' );
	}
	
	// thong ke tong hop gom, diem danh den, ve và danh gia hoat dong
	public function executeSynthetic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		$this->filter_list_student = array ();
		
		$delay_filter = $request->getParameter ( 'delay_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'delay_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$date_at = $value_student_filter ['date_at'];
			
			if ($date_at == '') {
				$this->date_at = date ( 'Y-m-d' );
			} else {
				$this->date_at = $date_at;
			}
			
			$params = array ();
			$params ['ps_customer_id'] = $ps_customer_id;
			$params ['ps_school_year_id'] = $ps_school_year_id;
			$params ['ps_workplace_id'] = $ps_workplace_id;
			$params ['tracked_at'] = $date_at;
			$params ['is_activated'] = PreSchool::ACTIVE;
			$params ['number_option'] = PreSchool::ACTIVE;
			
			$this->my_class = Doctrine::getTable ( 'MyClass' )->getClassInfoByCustomerId ( $ps_customer_id, $ps_school_year_id, $ps_workplace_id, null );
			$this->filter_list_logtime = Doctrine::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDayOfCustomer ( $ps_customer_id, $date_at );
			
			$this->feture_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByMyClassParams ( $params )->execute ();
			
			$this->list_feture_branch = Doctrine::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchByCustomerInDay ( $ps_customer_id, $date_at );
		}
		
		if ($delay_filter) {
			
			$this->ps_workplace_id = isset ( $delay_filter ['ps_workplace_id'] ) ? $delay_filter ['ps_workplace_id'] : 0;
			
			$this->ps_school_year_id = isset ( $delay_filter ['ps_school_year_id'] ) ? $delay_filter ['ps_school_year_id'] : 0;
			
			$this->date_at = isset ( $delay_filter ['date_at'] ) ? $delay_filter ['date_at'] : date ( 'Y-m-d' );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
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
		
		if ($this->date_at == '') {
			$this->date_at = date ( 'd-m-Y' );
		}
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
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
		
		$this->formFilter->setWidget ( 'date_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );
		
		$this->formFilter->setValidator ( 'date_at', new sfValidatorDate ( array (
				'required' => true ) ) );
		
		$this->formFilter->setDefault ( 'date_at', $this->date_at );
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'delay_filter[%s]' );
	}
	
	// Ham thong ke tong hop cua thang
	public function executeSyntheticMonth(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$year_month = null;
		
		$ps_school_year_id = null;
		
		$this->class_id = null;
		
		$this->filter_list_student = array ();
		
		$synthetic_month_filter = $request->getParameter ( 'synthetic_month_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'synthetic_month_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$this->class_id = $value_student_filter ['class_id'];
			
			$this->year_month = $value_student_filter ['year_month'];
			
			$date_at = '01-' . $this->year_month;
			
			/*
			 * Old Ngày 15/11/2015 $this->my_class = Doctrine::getTable('MyClass')->getClassInfoByCustomerId($ps_customer_id,$ps_school_year_id,$ps_workplace_id,$this->class_id); $this->filter_list_logtime = Doctrine::getTable('PsAttendancesSynthetic')->getAttendanceSyntheticByMonthOfCustomer($ps_customer_id,$date_at); $this->list_feture_branch = Doctrine::getTable( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchByCustomerInMonth($ps_customer_id,$date_at); $this->feture_branch = Doctrine::getTable( 'PsFeatureBranchTimeMyClass' )->getFeatureBranchCustomerIdOfMonth($ps_school_year_id,$ps_customer_id, $date_at,$this->class_id); $this->feture_branch2 = Doctrine::getTable( 'PsFeatureBranchTimeMyClass' )->getFeatureBranchCustomerIdOfMonth($ps_school_year_id,$ps_customer_id, $date_at,$this->class_id);
			 */
			$this->filter_list_logtime = Doctrine::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByMonthOfCustomer ( $ps_customer_id, $date_at );
			$this->list_feture_branch = Doctrine::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchByCustomerInMonth ( $ps_customer_id, $date_at );
			$this->feture_branch = Doctrine::getTable ( 'PsFeatureBranchTimeMyClass' )->getFeatureBranchCustomerIdOfMonthGroup ( $ps_school_year_id, $ps_customer_id, $date_at, $this->class_id );
		}
		
		$this->year_month = isset ( $synthetic_month_filter ['year_month'] ) ? $synthetic_month_filter ['year_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );
		
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		if ($synthetic_month_filter) {
			
			$this->ps_school_year_id = isset ( $synthetic_month_filter ['ps_school_year_id'] ) ? $synthetic_month_filter ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $synthetic_month_filter ['ps_workplace_id'] ) ? $synthetic_month_filter ['ps_workplace_id'] : 0;
			
			$this->class_id = isset ( $synthetic_month_filter ['class_id'] ) ? $synthetic_month_filter ['class_id'] : 0;
			
			$this->year_month = isset ( $synthetic_month_filter ['year_month'] ) ? $synthetic_month_filter ['year_month'] : date ( "m-Y" );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
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
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'synthetic_month_filter[%s]' );
	}
	
	// Ham updated diem danh + danh gia hoat dong
	public function executeSyntheticMonthOld(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$class_id = $year_month = null;
		
		$ps_school_year_id = null;
		
		$this->filter_list_student = array ();
		
		$synthetic_month_filter = $request->getParameter ( 'synthetic_month_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'synthetic_month_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$class_id = $value_student_filter ['class_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$year_month = $value_student_filter ['year_month'];
			
			$date_at = '01-' . $year_month;
			
			$number_day = PsDateTime::psNumberDaysOfMonth ( $year_month );
			
			$conn = Doctrine_Manager::connection();
			
			try {
			    
			    $conn->beginTransaction();
			    
			    // Lay tat ca hoat dong
			    $feture_branchs = Doctrine::getTable ( 'PsFeatureBranchTimeMyClass' )->getFeatureBranchCustomerIdOfMonth ( $ps_school_year_id, $ps_customer_id, $date_at, $class_id );
		
    			// Cap nhat danh gia hoat dong cua thang theo lop
    			for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
    			    
    			    $date_at = $k.'-'. $year_month;
    			    
        			$list_feture_branchs = Doctrine::getTable ( 'StudentFeature' )->getAllPsFeatureBranchTimeByCustomer ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $date_at,$class_id );
        			
        			//echo count($list_feture_branchs); die;
        			
        			$array_list = array ();
        			
        			foreach ( $list_feture_branchs as $list_feture ) {
        			    $array_list [$list_feture->getStudentId () . '_' . $list_feture->getMyclassId () . '_' . $list_feture->getPsFeatureBranchId ()] = $list_feture->getPsFeatureBranchId ();
        			}
        			
        			$date_at = date ( "Y-m-d", strtotime ( $date_at ) );
        			
        			// Đếm số lần xuất hiện của các phần tử giống nhau trong mảng $array_list và trả về một mảng kết quả.
        			// $key = $feture_branchs_id, $value = So luong danh gia hoat dong
        			
        			$array_data_feature = (array_count_values($array_list));
        			
        			foreach ( $feture_branchs as $branch ) {
        			    
        	            $c = 0;
        	            $index = '';
        	            
        	            foreach ( $array_data_feature as $key => $value ) {
        	                $index = $key;
        	                if ($key == $branch->getFbId ()) {
        	                    $c = $value;
        	                    break;
        	                }
        	            }
        	            
        	            unset($array_data_feature[$index]);
        	            
        	            $number_feature = Doctrine_Core::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchSyntheticByClass ( $class_id, $branch->getFbId (), $date_at );
        	            if (! $number_feature) {
        	                $number_feature = new PsFeatureBranchSynthetic ();
        	                $number_feature->setPsCustomerId ( $ps_customer_id );
        	                $number_feature->setPsClassId ( $class_id );
        	                $number_feature->setFeatureId ( $branch->getFbId () );
        	                $number_feature->setFeatureSum ( $c );
        	                $number_feature->setTrackedAt ( $date_at );
        	                $number_feature->setUserUpdatedId ( myUser::getUserId () );
        	                $number_feature->save ();
        	            } else {
        	                
        	                $number_feature->setFeatureSum ( $c );
        	                
        	                $number_feature->save ();
        	            }
        		            
        			}
        			
    			}
    			
    			// Cap nhat diem danh cua thang theo lop
    			$this->filter_list_logtime = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesStatistic ( $class_id, $year_month );
    			
    			$array_logtime = array ();
    			$array_goschool = array ();
    			$array_outschool = array ();
    			
    			foreach ( $this->filter_list_logtime as $list_logtimes ) {
    			    array_push ( $array_logtime, $list_logtimes->getStudentId () . date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
    			    // Diem danh den
    			    if ($list_logtimes->getLogValue () == 1) { 
    			        array_push ( $array_goschool, date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
    			    }
    			    
    			    $array_data_goschool = (array_count_values($array_goschool));
    			    
    			    // Diem danh ve
    			    if ($list_logtimes->getLogValue () == 1 && $list_logtimes->getLogoutAt () != '') {
    			        array_push ( $array_outschool, date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
    			    }
    			    
    			    $array_data_outschool = (array_count_values($array_outschool));
    			    
    			}
    			
    			for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
    			    
    			    $a = 0;
    			    $b = 0;
    			    $index1 = $index2 = '';
    			    
    			    foreach ( $array_data_goschool as $key1 => $value1 ) {
    			        $index1 = $key1;
    			        if ($key1 == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
    			            $a = $value1;
    			            break;
    			        }
    			    }
    			    
    			    unset($array_data_goschool[$index1]);
    			    
    			    foreach ( $array_data_outschool as $key2 => $value2 ) {
    			        $index2 = $key2;
    			        if ($key2 == date ( "Ymd", strtotime ( $k . '-' . $year_month ) ) . '1') {
    			            $b = $value2;
    			            break;
    			        }
    			    }
    			    
    			    unset($array_data_outschool[$index2]);
    			    
    			    // ham update diem danh
    			    $date = date ( "Y-m-d", strtotime ( $k . '-' . $year_month ) );
    			    $number_attendance = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $date );
    			    
    			    if (! $number_attendance) {
    			        $number_attendance = new PsAttendancesSynthetic ();
    			        $number_attendance->setPsCustomerId ( $ps_customer_id );
    			        $number_attendance->setPsClassId ( $class_id );
    			        $number_attendance->setLoginSum ( $a );
    			        $number_attendance->setLogoutSum ( $b );
    			        $number_attendance->setTrackedAt ( $date );
    			        $number_attendance->setUserUpdatedId ( myUser::getUserId () );
    			        $number_attendance->save ();
    			    } else {
    			        $number_attendance->setLoginSum ( $a );
    			        $number_attendance->setLogoutSum ( $b );
    			        $number_attendance->save ();
    			    }
    			}
			
			    $conn->commit();
			}catch (Exception $e) {
			    $conn->rollback();
			    $error_import = $e->getMessage();
			    $this->getUser()->setFlash('error', $error_import);
			    $this->redirect('@ps_attendances_import');
			}
		}
		
		$this->year_month = isset ( $synthetic_month_filter ['year_month'] ) ? $synthetic_month_filter ['year_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );
		
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		if ($synthetic_month_filter) {
			
			$this->ps_school_year_id = isset ( $synthetic_month_filter ['ps_school_year_id'] ) ? $synthetic_month_filter ['ps_school_year_id'] : 0;
			
			$this->ps_workplace_id = isset ( $synthetic_month_filter ['ps_workplace_id'] ) ? $synthetic_month_filter ['ps_workplace_id'] : 0;
			
			$this->class_id = isset ( $synthetic_month_filter ['class_id'] ) ? $synthetic_month_filter ['class_id'] : 0;
			
			$this->year_month = isset ( $synthetic_month_filter ['year_month'] ) ? $synthetic_month_filter ['year_month'] : date ( "m-Y" );
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'year_month', $this->year_month );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'synthetic_month_filter[%s]' );
	}
	
	// So thong ke tong hop theo ngay
	public function executeExportSyntheticDate(sfWebRequest $request) {

		$school_id = $request->getParameter ( 'synthetic_export_date_school_year_id' );
		$customer_id = $request->getParameter ( 'synthetic_export_date_ps_customer_id' );
		$workplace_id = $request->getParameter ( 'synthetic_export_date_ps_workplace_id' );
		$date_at = $request->getParameter ( 'synthetic_export_date_at' );
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL')) {
			if($customer_id != myUser::getPscustomerID()){
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		$this->exportReportSyntheticStatisticDate ( $school_id, $customer_id, $workplace_id, $date_at );
		
		$this->redirect ( '@ps_attendances_synthetic_by_date_export' );
	}

	protected function exportReportSyntheticStatisticDate($school_id, $customer_id, $workplace_id, $date_at) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );
		
		$file_template_pb = 'tkth_hoatdongngay_00001.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;
		
		$school_name = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ($workplace_id );
		
		$params = array ();
		$params ['ps_customer_id'] = $customer_id;
		$params ['ps_school_year_id'] = $school_id;
		$params ['ps_workplace_id'] = $workplace_id;
		$params ['tracked_at'] = $date_at;
		$params ['is_activated'] = PreSchool::ACTIVE;
		$params ['number_option'] = PreSchool::ACTIVE;
		
		$my_class = Doctrine::getTable ( 'MyClass' )->getClassInfoByCustomerId ( $customer_id, $school_id, $workplace_id, null );
		$filter_list_logtime = Doctrine::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDayOfCustomer ( $customer_id, $date_at );
		$feture_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByMyClassParams ( $params )->execute ();
		$list_feture_branch = Doctrine::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchByCustomerInDay ( $customer_id, $date_at );
		
		$title_info = $this->getContext ()->getI18N ()->__ ( 'Statistic date' ) . date ( 'd-m-Y', strtotime ( $date_at ) );
		
		$title_xls = substr( $school_name->getName(),0,30);
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$exportFile->setDataExportStatisticInfoExportA ( $school_name, $title_info, $title_xls );
		
		$exportFile->setDataExportSyntheticStatisticDay ( $my_class, $filter_list_logtime, $feture_branch, $list_feture_branch );
		
		$exportFile->saveAsFile ( "ThongkeTongHopTheoNgay" . ".xls" );
	}
	
	// So thong ke tong hop theo thang
	public function executeExportSynthetic(sfWebRequest $request) {

		$school_id = $request->getParameter ( 'synthetic_export_school_year_id' );
		$ps_month = $request->getParameter ( 'synthetic_export_ps_month' );
		$customer_id = $request->getParameter ( 'synthetic_export_ps_customer_id' );
		$workplace_id = $request->getParameter ( 'synthetic_export_ps_workplace_id' );
		$class_id = $request->getParameter ( 'synthetic_export_class_id' );
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL')) {
			if($customer_id != myUser::getPscustomerID()){
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		$this->exportReportSyntheticStatisticMonth ( $school_id, $ps_month, $customer_id, $workplace_id, $class_id );
		
		$this->redirect ( '@ps_attendances_synthetic_month' );
	}

	protected function exportReportSyntheticStatisticMonth($school_id, $ps_month, $customer_id, $workplace_id, $class_id) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );
		
		$file_template_pb = 'tkth_hoatdong_00001.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;
		
		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $customer_id, $class_id, $workplace_id );
		
		$date_at = '01-' . $ps_month;
		
		$class_name = $school_name->getClName();
		
		// diem danh den
		$filter_list_logtime = Doctrine::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByMonthOfCustomer ( $customer_id, $date_at );
		
		$list_feture_branch = Doctrine::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchByCustomerInMonth ( $customer_id, $date_at );
		
		$feture_branch = Doctrine::getTable ( 'PsFeatureBranchTimeMyClass' )->getFeatureBranchCustomerIdOfMonthGroup ( $school_id, $customer_id, $date_at, $class_id );
		
		$number_day = PsDateTime::psNumberDaysOfMonth ( $ps_month );
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$title_info = $this->getContext ()->getI18N ()->__ ( 'Statistic synthetic' ) . $ps_month;
		
		$title_xls = substr( $class_name,0,30);
		
		$exportFile->setDataExportStatisticInfoExportA ( $school_name, $title_info, $title_xls );
		
		$exportFile->setDataExportSyntheticStatistic ( $filter_list_logtime, $list_feture_branch, $feture_branch, $school_name, $number_day, $ps_month );
		
		$exportFile->saveAsFile ( "ThongkeTongHop_" .date('m/Y',strtotime($date_at)). ".xls" );
	}
	
	// lưu điểm danh cả lớp
	public function executeSavePsLogtime(sfWebRequest $request) {

		$this->logMessage ( "LUU DIEM DANH CA LOP", 'info' );
		
		$tracked_at = $request->getParameter ( 'tracked_at' );
		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );
		$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
		$ps_class_id = $request->getParameter ( 'ps_class_id' );
		
		$attendances_relative = $request->getParameter ( 'attendances_relative' ); // cau hinh chon nguoi dua don hay khong?
		
		//echo $attendances_relative; die;
		
		$student_logtimes = $request->getParameter ( 'student_logtime' );
		
		// print_r($student_logtimes);die;
		
		$current_date = date ( "Ymd" );
		
		$check_current_date = true;
		
		$currentUser = myUser::getUser ();
		
		$array_relative = array ();
		
		$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
		$start_sent_notifi = $ps_workplace->getFromTimeNoticationAttendances (); // gio bat dau nhan thong bao
		$stop_sent_notifi = $ps_workplace->getToTimeNoticationAttendances (); // gio ket thuc nhan thong bao
		
		$user_id = myUser::getUserId ();
		
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			$error = false;
			$diemdanhden = $diemdanhve = 0;
			$dung = $sai = 0;
			$dem = 0;
			foreach ( $student_logtimes as $key => $student_logtime ) {
				
				$dem ++;
				$student_id = $key;
				
				$log_value = (isset ( $student_logtime ['log_value'] )) ? $student_logtime ['log_value'] : '';
				
				$relative_login = (isset ( $student_logtime ['relative_login'] ) && $student_logtime ['relative_login'] > 0) ? $student_logtime ['relative_login'] : null;
				
				$member_login = (isset ( $student_logtime ['member_login'] ) && $student_logtime ['member_login'] > 0) ? $student_logtime ['member_login'] : null;
				
				$relative_logout = (isset ( $student_logtime ['relative_logout'] ) && $student_logtime ['relative_logout'] > 0) ? $student_logtime ['relative_logout'] : null;
				
				$member_logout = (isset ( $student_logtime ['member_logout'] ) && $student_logtime ['member_logout'] > 0) ? $student_logtime ['member_logout'] : null;
				
				if (isset ( $student_logtime ['login_at'] ) && $student_logtime ['login_at'] != '') {
					
					$date_temp = $tracked_at . ' ' . $student_logtime ['login_at'];
					
					$login_at = date ( "Y-m-d H:i:s", PsDateTime::psDatetoTime ( $date_temp ) );
					
					$time_login = $student_logtime ['login_at'];
				} else {
					$login_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $tracked_at ) ) . " " . date ( "H:i:s" );
					$time_login = date ( "H:i:s" );
				}
				
				// thoi gian den - ve cua tre
				if (isset ( $student_logtime ['logout_at'] ) && $student_logtime ['logout_at'] != '') {
					
					$date_temp1 = $tracked_at . ' ' . $student_logtime ['logout_at'];
					
					$logout_at = date ( "Y-m-d H:i:s", PsDateTime::psDatetoTime ( $date_temp1 ) );
					
					$time_logout = $student_logtime ['logout_at'];
				} else {
					$logout_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $tracked_at ) ) . " " . date ( "H:i:s" );
					
					$time_logout = date ( "H:i:s" );
				}
				
				$note = (isset ( $student_logtime ['note'] )) ? $student_logtime ['note'] : '';
				
				$services = (isset ( $student_logtime ['student_service'] )) ? $student_logtime ['student_service'] : null;
				
				// Tim xem da ton tai chua
				$ps_logtimes = Doctrine::getTable ( 'PsLogtimes' )->updateLogtimeByTrackedAt ( $student_id, $tracked_at );
				
				$student_name = Doctrine::getTable ( 'Student' )->getStudentName ( $student_id );
				
				if ($relative_login > 0){
					//$relative_login_name = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_login ) ['name'];
					$relativeModel =  Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_login );
					$relative_login_name = $relativeModel ? $relativeModel->getName() : '';
				}
				if ($member_login > 0){
					//$member_login_name = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_login ) ['name'];
					$psMemberModel =  Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_login );
					$member_login_name   = $psMemberModel ? $psMemberModel->getName() : '';
				}
				if ($relative_logout > 0){
					//$relative_logout_name = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_logout )->getName ();
					$relativeModel =  Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_logout );
					$relative_login_name = $relativeModel ? $relativeModel->getName() : '';
				}
				if ($member_logout > 0){
					//$member_logout_name = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_logout )->getName ();
					$psMemberModel =  Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_logout );
					$member_login_name   = $psMemberModel ? $psMemberModel->getName() : '';
				}
				// Neu trang thai la di hoc
				if ($log_value == 1) {
					// echo 'AAAAAA';die;
					// Luu diem danh den theo ca lop
					// Neu bat buoc chon nguoi dua don
					if (count ( $ps_logtimes ) > 0){
						Doctrine::getTable ( 'StudentServiceDiary' )->findByStudentTrackedAt ( $student_id, $tracked_at )->delete ();
					}
					
					$service_name = "";
					foreach ( $services as $service ) {
						$student_service_diary = new StudentServiceDiary ();
						$student_service_diary->setServiceId ( $service );
						$student_service_diary->setStudentId ( $student_id );
						$student_service_diary->setTrackedAt ( $tracked_at );
						$student_service_diary->setUserCreatedId ( myUser::getUserId () );
						$student_service_diary->save ();
						
						$student_service_name = Doctrine::getTable ( 'Service' )->getServiceName ( $service )->getTitle ();
						$service_name .= $student_service_name . ", ";
					}
					
					// bo dau "," cuoi chuoi
					$service_name = substr ( $service_name, 0, - 2 );
					
					$history_content = $this->getContext ()->getI18N ()->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login at' ) . ": " . $login_at . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative id' ) . ": " . $relative_login . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Relative login' ) . ": " . $relative_login_name . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member id' ) . ": " . $member_login . '<br/>' . $this->getContext()->getI18N()->__('Teacher handover').": ".$member_login_name.'<br/>'
					.$this->getContext()->getI18N()->__('Created by').": ".$currentUser->getFirstName() ." ". $currentUser->getLastName().'('. $currentUser->getUsername() .')'. '<br/>'
					.$this->getContext()->getI18N()->__('Used service').": ".$service_name.'<br/>';
									
					$cauhinh = 0;
					
					if($attendances_relative == 1){
						
						if ($relative_login != '' && $member_login != '') {
							
							$dung ++;
							$diemdanhden ++;
							
							if ($ps_logtimes) { // Neu da ton tai
								
								$action = "edit";
								
								//foreach ($ps_logtimes as $ps_logtime) {
								$ps_logtimes -> setLoginAt($login_at);
								$ps_logtimes -> setLoginRelativeId($relative_login);
								$ps_logtimes -> setLoginMemberId($member_login);
								$ps_logtimes -> setUserUpdatedId($user_id);
								
								$ps_logtimes->save();
								
								$logtimeHistory = new PsHistoryLogtimes();
								
								$logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
								
								$logtimeHistory->setStudentId($student_id);
								
								$logtimeHistory->setPsAction($action);
								
								$logtimeHistory->setHistoryContent($history_content);
								
								$logtimeHistory->save();
								//}
								
							} else {
								
								$action = "add";
								
								$cauhinh = 1;
								
								$ps_logtimes = new PsLogtimes();
								$ps_logtimes -> setStudentId($student_id);
								$ps_logtimes -> setLoginAt($login_at);
								$ps_logtimes -> setLoginRelativeId($relative_login);
								$ps_logtimes -> setLoginMemberId($member_login);
								$ps_logtimes -> setLogoutAt(null);
								$ps_logtimes -> setLogoutRelativeId(null);
								$ps_logtimes -> setLogoutMemberId(null);
								$ps_logtimes -> setLogValue($log_value);
								$ps_logtimes -> setNote($note);
								$ps_logtimes -> setUserCreatedId($user_id);
								$ps_logtimes -> setUserUpdatedId($user_id);
								$ps_logtimes -> save();
								
								$logtimeHistory = new PsHistoryLogtimes();
								
								$logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
								
								$logtimeHistory->setStudentId($ps_logtimes->getStudentId());
								
								$logtimeHistory->setPsAction($action);
								
								$logtimeHistory->setHistoryContent($history_content);
								
								$logtimeHistory->save();
								
								// gui thong bao diem danh cho phu huynh
								if($cauhinh == 1 && strtotime($date_at) == strtotime(date('Y-m-d'))){
									
									if($start_sent_notifi == "00:00:00"){ // neu khong cau hinh thi gui thong bao
										
										$cauhinh = 1;
										
									} else { // Gui thong bao diem danh truoc gio cau hinh trong co so
										
										$config_time  = date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$start_sent_notifi));
										
										$current_time = date("Y-m-d H:i" ,time());
										
										if (strtotime($current_time) <= strtotime($config_time)){
											$cauhinh = 1;
										}else{
											$cauhinh = 0;
										}
									}
								}
								
								if($cauhinh == 1 && $student_id > 0) {
									
									$list_received_id = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationByStudent($ps_customer_id, $ps_class_id, $student_id);
									
									$registrationIds_ios 		= array ();
									$registrationIds_android 	= array ();
									
									foreach ( $list_received_id as $user_nocation ) {
										
										//                                 echo $user_nocation->getUsername().'_';
										
										if ($user_nocation->getNotificationToken() != '') {
											if ($user_nocation->getOsname() == 'IOS')
												array_push ( $registrationIds_ios, $user_nocation->getNotificationToken() );
												else
													array_push ( $registrationIds_android, $user_nocation->getNotificationToken() );
										}
									}
									//                             echo '<br/>';
									$psI18n = $this->getContext()->getI18N();
									if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
										
									$setting = new \stdClass ();
									
									$setting->title = $psI18n->__ ( 'Notice of attendance of the baby' ) . " " . $student_name;
									
									$setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . $member_login_name;
									
									$setting->tickerText = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
									//                                 date_format($date,"Y/m/d H:i:s");
									$content = $psI18n->__ ( 'Login at' ) . ": " . $time_login . '. ';
									
									$content .= $psI18n->__ ( 'Relative login' ) . ": " . $relative_login_name . '. ';
									
									$content .= $psI18n->__ ( 'Teacher receives' ) . ": " . $member_login_name;
									
									if ($service_name != '')
										$content .= '. ' . $psI18n->__ ( 'Service use' ) . ": " . $service_name;
										
										
										$setting->message = $content;
										
										$setting->lights 	= '1';
										$setting->vibrate 	= '1';
										$setting->sound 	= '1';
										$setting->smallIcon = 'ic_small_notification';
										$setting->smallIconOld = 'ic_small_notification_old';
										
										// Lay avatar nguoi gui
										$profile = $this->getUser()->getGuardUser()->getProfileShort();
										
										if ($profile && $profile->getAvatar() != '') {
											
											$url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
											
											$largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
											
										} else {
											$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
										}
										
										$setting->largeIcon 	= $largeIcon;
										
										$setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_CMSNOTIFICATION;
										$setting->itemId 		= '0';
										$setting->clickUrl 		= '';
										
										//$setting->studentId 	= $student_id;
										
										// Deviceid registration firebase
										if (count($registrationIds_ios) > 0) {
											$setting->registrationIds = $registrationIds_ios;
											
											$notification = new PsNotification ( $setting );
											$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
										}
										
										if (count($registrationIds_android) > 0) {
											$setting->registrationIds = $registrationIds_android;
											
											$notification = new PsNotification ( $setting );
											$result = $notification->pushNotification ();
										}
									}
								}
							}
						}
					}else{
						
						$dung ++;
						$diemdanhden ++;
						
						if ($ps_logtimes) { // Neu da ton tai
							
							$action = "edit";
							
							//foreach ($ps_logtimes as $ps_logtime) {
							$ps_logtimes -> setLoginAt($login_at);
							$ps_logtimes -> setLoginRelativeId($relative_login);
							$ps_logtimes -> setLoginMemberId($member_login);
							$ps_logtimes -> setUserUpdatedId($user_id);
							
							$ps_logtimes->save();
							
							$logtimeHistory = new PsHistoryLogtimes();
							
							$logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
							
							$logtimeHistory->setStudentId($student_id);
							
							$logtimeHistory->setPsAction($action);
							
							$logtimeHistory->setHistoryContent($history_content);
							
							$logtimeHistory->save();
							//}
							
						} else {
							
							$action = "add";
							
							$cauhinh = 1;
							
							$ps_logtimes = new PsLogtimes();
							$ps_logtimes -> setStudentId($student_id);
							$ps_logtimes -> setLoginAt($login_at);
							$ps_logtimes -> setLoginRelativeId($relative_login);
							$ps_logtimes -> setLoginMemberId($member_login);
							$ps_logtimes -> setLogoutAt(null);
							$ps_logtimes -> setLogoutRelativeId(null);
							$ps_logtimes -> setLogoutMemberId(null);
							$ps_logtimes -> setLogValue($log_value);
							$ps_logtimes -> setNote($note);
							$ps_logtimes -> setUserCreatedId($user_id);
							$ps_logtimes -> setUserUpdatedId($user_id);
							$ps_logtimes -> save();
							
							$logtimeHistory = new PsHistoryLogtimes();
							
							$logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
							
							$logtimeHistory->setStudentId($ps_logtimes->getStudentId());
							
							$logtimeHistory->setPsAction($action);
							
							$logtimeHistory->setHistoryContent($history_content);
							
							$logtimeHistory->save();
							
							// gui thong bao diem danh cho phu huynh
							if($cauhinh == 1 && strtotime($date_at) == strtotime(date('Y-m-d'))){
								
								if($start_sent_notifi == "00:00:00"){ // neu khong cau hinh thi gui thong bao
									
									$cauhinh = 1;
									
								} else { // Gui thong bao diem danh truoc gio cau hinh trong co so
									
									$config_time  = date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$start_sent_notifi));
									
									$current_time = date("Y-m-d H:i" ,time());
									
									if (strtotime($current_time) <= strtotime($config_time)){
										$cauhinh = 1;
									}else{
										$cauhinh = 0;
									}
								}
							}
							
							if($cauhinh == 1 && $student_id > 0) {
								
								$list_received_id = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationByStudent($ps_customer_id, $ps_class_id, $student_id);
								
								$registrationIds_ios 		= array ();
								$registrationIds_android 	= array ();
								
								foreach ( $list_received_id as $user_nocation ) {
									
									//echo $user_nocation->getUsername().'_';
									
									if ($user_nocation->getNotificationToken() != '') {
										if ($user_nocation->getOsname() == 'IOS')
											array_push ( $registrationIds_ios, $user_nocation->getNotificationToken() );
											else
												array_push ( $registrationIds_android, $user_nocation->getNotificationToken() );
									}
								}
								
								//                             echo '<br/>';
								$psI18n = $this->getContext()->getI18N();
								if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
									
									$setting = new \stdClass ();
									
									$setting->title = $psI18n->__ ( 'Notice of attendance of the baby' ) . " " . $student_name;
									
									$setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . $member_login_name;
									
									$setting->tickerText = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
									//                                 date_format($date,"Y/m/d H:i:s");
									$content = $psI18n->__ ( 'Login at' ) . ": " . $time_login . '. ';
									
									if ($relative_login_name != ''){
										$content .= $psI18n->__ ( 'Relative logout' ) . ": " . $relative_login_name . '. ';
									}
									if ($member_login_name != ''){
										$content .= $psI18n->__ ( 'Teacher handover' ) . ": " . $member_login_name;
									}
									
									if ($service_name != ''){
										$content .= '. ' . $psI18n->__ ( 'Service use' ) . ": " . $service_name;
									}
										
									$setting->message = $content;
									
									$setting->lights 	= '1';
									$setting->vibrate 	= '1';
									$setting->sound 	= '1';
									$setting->smallIcon = 'ic_small_notification';
									$setting->smallIconOld = 'ic_small_notification_old';
									
									// Lay avatar nguoi gui
									$profile = $this->getUser()->getGuardUser()->getProfileShort();
									
									if ($profile && $profile->getAvatar() != '') {
										
										$url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
										
										$largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
										
									} else {
										$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
									}
									
									$setting->largeIcon 	= $largeIcon;
									
									$setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_CMSNOTIFICATION;
									$setting->itemId 		= '0';
									$setting->clickUrl 		= '';
									
									//$setting->studentId 	= $student_id;
									
									// Deviceid registration firebase
									if (count($registrationIds_ios) > 0) {
										$setting->registrationIds = $registrationIds_ios;
										
										$notification = new PsNotification ( $setting );
										$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
									}
									
									if (count($registrationIds_android) > 0) {
										$setting->registrationIds = $registrationIds_android;
										
										$notification = new PsNotification ( $setting );
										$result = $notification->pushNotification ();
									}
								}
							}
						}
					}
          	}elseif($log_value != ''){
              
              $dung ++;
              
              //echo 'BBBBBBBBB';die;
              //echo 'aaaaaaaaa_'.$relative_login_name; die;
              // Xoa bo cac dich vu da dung trong ngay diem danh
              if (count($ps_logtimes) > 0)
                  Doctrine::getTable('StudentServiceDiary')->findByStudentTrackedAt($student_id, $tracked_at)->delete();
                  
              $service_name = "";
              foreach ($services as $service) {
                  $student_service_diary = new StudentServiceDiary();
                  $student_service_diary->setServiceId($service);
                  $student_service_diary->setStudentId($student_id);
                  $student_service_diary->setTrackedAt($tracked_at);
                  $student_service_diary->setUserCreatedId(myUser::getUserId());
                  $student_service_diary->save();
                  
                  $student_service_name = Doctrine::getTable('Service')->getServiceName($service)->getTitle();
                  
                  $service_name .= $student_service_name.", ";
              }
                  
              $history_content = $this->getContext()->getI18N()->__('Student id').": ".$student_id.'<br/>'
              .$this->getContext()->getI18N()->__('Student name').": ".$student_name.'<br/>'
              .$this->getContext()->getI18N()->__('Log value').": ".$log_value.'<br/>'
              .$this->getContext()->getI18N()->__('Login at').": ".$login_at.'<br/>'
              .$this->getContext()->getI18N()->__('Login relative id').": ".$relative_login.'<br/>'
              .$this->getContext()->getI18N()->__('Relative login').": ".$relative_login_name.'<br/>'
              .$this->getContext()->getI18N()->__('Login member id').": ".$member_login.'<br/>'
              .$this->getContext()->getI18N()->__('Teacher handover').": ".$member_login_name.'<br/>'
              .$this->getContext()->getI18N()->__('Created by').": ".$currentUser->getFirstName() ." ". $currentUser->getLastName().'('. $currentUser->getUsername() .')'. '<br/>'
              .$this->getContext()->getI18N()->__('Used service').": ".$service_name.'<br/>';
                                              
              if ($ps_logtimes) { // Neu da ton tai
                  
                  $action = "edit";
                  
//                   foreach ($ps_logtimes as $ps_logtime) {
                  $ps_logtimes -> setLoginAt($login_at);
                  $ps_logtimes -> setLogValue($log_value);
                  $ps_logtimes -> setLoginRelativeId($relative_login);
                  $ps_logtimes -> setLoginMemberId($member_login);
                  $ps_logtimes -> setUserUpdatedId($user_id);
                      
                  $ps_logtimes->save();
//                   }
              }else{
                  
                  $action = "add";
                  
                  $ps_logtimes = new PsLogtimes();
                  $ps_logtimes -> setStudentId($student_id);
                  $ps_logtimes -> setLoginAt($login_at);
                  $ps_logtimes -> setLoginRelativeId($relative_login);
                  $ps_logtimes -> setLoginMemberId($member_login);
                  $ps_logtimes -> setLogoutAt(null);
                  $ps_logtimes -> setLogoutRelativeId(null);
                  $ps_logtimes -> setLogoutMemberId(null);
                  $ps_logtimes -> setLogValue($log_value);
                  $ps_logtimes -> setNote($note);
                  $ps_logtimes -> setUserCreatedId($user_id);
                  $ps_logtimes -> setUserUpdatedId($user_id);
                  $ps_logtimes -> save();
                  
              }
              
              $logtimeHistory = new PsHistoryLogtimes();
              
              $logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
              
              $logtimeHistory->setStudentId($ps_logtimes->getStudentId());
              
              $logtimeHistory->setPsAction($action);
              
              $logtimeHistory->setHistoryContent($history_content);
              
              $logtimeHistory->save();
              
              
          }else{
              // phải xác định giáo viên trả trẻ; - Trước phải bắt buộc cả phụ huynh
              if ($member_logout !=''){ 
                  //echo 'CCCCCCCC';die;
                    $dung ++; $diemdanhve ++;
                    
                    $history_content = $this->getContext()->getI18N()->__('Student id').": ".$student_id.'<br/>'
                    .$this->getContext()->getI18N()->__('Student name').": ".$student_name.'<br/>'
                    .$this->getContext()->getI18N()->__('Logout at').": ".$logout_at.'<br/>'
                    .$this->getContext()->getI18N()->__('Logout relative id').": ".$relative_logout.'<br/>'
                    .$this->getContext()->getI18N()->__('Login relative name').": ".$relative_logout_name.'<br/>'
                    .$this->getContext()->getI18N()->__('Login member id').": ".$member_logout.'<br/>'
                    .$this->getContext()->getI18N()->__('Login member name').": ".$member_logout_name.'<br/>'
                    .$this->getContext()->getI18N()->__('Created by').": ".$currentUser->getFirstName() ." ". $currentUser->getLastName().'('. $currentUser->getUsername() .')'. '<br/>'
                    ;
                    
                    if ($ps_logtimes) { // Neu da ton tai
                        
                        $action = "edit";
                        
                        $cauhinh = 0;
                        
                        if(strtotime($date_at) == strtotime(date('Y-m-d'))) {
                            
                            if($stop_sent_notifi == "00:00:00"){ // neu khong cau hinh thi gui thong bao
                                
                                $cauhinh = 1;
                                
                            } else { // Gui thong bao diem danh truoc gio cau hinh trong co so
                                
                                $config_time  = date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$stop_sent_notifi));
                                
                                $current_time = date("Y-m-d H:i" ,time());
                                
                                if (strtotime($current_time) <= strtotime($config_time)) // Gui thong bao diem danh ve truoc gio cau hinh cua co so
                                    $cauhinh = 1;
                            }
                        }
                        
                        //foreach ($ps_logtimes as $ps_logtime) {
                            
                            if($ps_logtimes->getLogoutAt() == '' && $cauhinh == 1){ // neu hoc sinh diem danh ve lan dau tien thi gui thong bao
                                
                                $list_received_id = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationByStudent($ps_customer_id, $ps_class_id, $student_id);
                                
                                $registrationIds_ios 		= array ();
                                $registrationIds_android 	= array ();
                                
                                foreach ( $list_received_id as $user_nocation ) {
                                    if ($user_nocation->getNotificationToken() != '') {
                                        if ($user_nocation->getOsname() == 'IOS')
                                            array_push ( $registrationIds_ios, $user_nocation->getNotificationToken() );
                                        else
                                            array_push ( $registrationIds_android, $user_nocation->getNotificationToken() );
                                    }
                                }
                                
                                if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
                                    
                                    $setting = new \stdClass ();
                                    
                                    $setting->title = $psI18n->__ ( 'Notice baby' ) . " " . $student_name;
                                    
                                    $setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . $member_login_name;
                                    
                                    $setting->tickerText = $psI18n->__ ( 'Attendance from KidsSchool.vn' );
                                    
                                    $content = $psI18n->__ ( 'Logout at' ) . ": " . $time_logout . '. ';
                                    
                                    $content .= $psI18n->__ ( 'Relative logout' ) . ": " . $relative_login_name . '. ';
                                    
                                    $content .= $psI18n->__ ( 'Teacher handover' ) . ": " . $member_login_name;
                                    
                                    
                                    $setting->message = $content;
                                    
                                    $setting->lights 	= '1';
                                    $setting->vibrate 	= '1';
                                    $setting->sound 	= '1';
                                    
                                    $setting->smallIcon 	= 'ic_small_notification';
                                    $setting->smallIconOld 	= 'ic_small_notification_old';
                                    
                                    // Lay avatar nguoi gui
                                    $profile = $this->getUser()->getGuardUser()->getProfileShort();
                                    
                                    if ($profile && $profile->getAvatar() != '') {
                                        
                                        $url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
                                        
                                        $largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
                                        
                                    } else {
                                        $largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
                                    }
                                    
                                    $setting->largeIcon 	= $largeIcon;
                                    
                                    $setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_ATTENDANCE;
                                    $setting->itemId 		= '0';
                                    $setting->clickUrl 		= '';
                                    
                                    //$setting->studentId 	= $student_id;
                                    
                                    // Deviceid registration firebase
                                    if (count($registrationIds_ios) > 0) {
                                        $setting->registrationIds = $registrationIds_ios;
                                        
                                        $notification = new PsNotification ( $setting );
                                        $result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
                                    }
                                    
                                    if (count($registrationIds_android) > 0) {
                                        $setting->registrationIds = $registrationIds_android;
                                        
                                        $notification = new PsNotification ( $setting );
                                        $result = $notification->pushNotification ();
                                    }
                                }
                            }
                            
                            $ps_logtimes->setLogoutAt($logout_at);
                            $ps_logtimes->setLogoutRelativeId($relative_logout);
                            $ps_logtimes->setLogoutMemberId($member_logout);
                            $ps_logtimes->setUserUpdatedId($user_id);
                            
                            $ps_logtimes->save();
                            
                            $logtimeHistory = new PsHistoryLogtimes();
                            
                            $logtimeHistory->setPsLogtimeId($ps_logtimes->getId());
                            
                            $logtimeHistory->setStudentId($student_id);
                            
                            $logtimeHistory->setPsAction($action);
                            
                            $logtimeHistory->setHistoryContent($history_content);
                            
                            $logtimeHistory->save();
                        //}
                    }
                }else{
                    //echo 'DDDDD';die;
                    $loidong .= $dem.', ';
                    $sai ++;
                }
            }
        }
            
        $number_attendances = Doctrine_Core::getTable('PsAttendancesSynthetic')->getAttendanceSyntheticByDate($ps_class_id, $tracked_at);
            
        if($number_attendances){
        	if($diemdanhden > $number_attendances->getLoginSum()){
                 $number_attendances -> setLoginSum($diemdanhden);
                 $number_attendances -> setUserUpdatedId($user_id);
                 $number_attendances -> save();
            }elseif ($diemdanhve > $number_attendances->getLogoutSum()){
                 $number_attendances -> setLogoutSum($diemdanhve);
                 $number_attendances -> setUserUpdatedId($user_id);
                 $number_attendances -> save();
            }
        }else{
            $number_attendances = new PsAttendancesSynthetic();
            $number_attendances -> setPsCustomerId($ps_customer_id);
            $number_attendances -> setPsClassId($ps_class_id);
            $number_attendances -> setLoginSum($diemdanhden);
            $number_attendances -> setLogoutSum($diemdanhve);
            $number_attendances -> setTrackedAt($tracked_at);
            $number_attendances -> setUserUpdatedId($user_id);
            $number_attendances -> save();
        }

        $conn->commit();
            
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
            
            $this->getUser()->setFlash('error', 'Classroom attendance was saved failed.');
            
            $conn->rollback();
        }
        
        if($dung == 0){  // neu ko co du lieu nao duoc luu
            
            $this->getUser()->setFlash('error', 'Classroom attendance was saved failed.');
            
        }elseif($sai == 0 && $dung > 0){  // neu khong co du lieu sai
            
            $this->getUser()->setFlash('notice', 'Classroom attendance was saved successfully. You can add another one below.');
        
        }else{  // neu co 1 so hoc sinh ko chọn phụ huynh hoac la giao vien
            
            $warning = $this->getContext()->getI18N()->__('Some one item not save %value%.', array (
                '%value%'  => $loidong
            ));
            
            $this->getUser()->setFlash('warning', $warning);
            
        }
        
        $this->redirect('@ps_attendances');
    }
    
    
    public function executeTrackbook(sfWebRequest $request)
    {
        $student_id = $request->getParameter('sid');
        
        $date_at = $request->getParameter('date_at');
        
        $this->student = Doctrine::getTable('Student')->findOneById($student_id);
        
        // ? Cần check lại
        $this->forward404Unless(myUser::checkAccessObject($this->student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL'), sprintf('Object does not exist.'));
        
        $this->form_filter = new sfForm();
        
        $this->year  = $request->getParameter('ps_year');
        
        $this->month = $request->getParameter('ps_month');
        
        if (!$this->year || !$this->month) {
            $this->month = date('m');
            $this->year = date('Y');
        }
        
        if ($date_at) {
            $this->month = date('m', $date_at);
            $this->year = date('Y', $date_at);
        }
        
        $default_month 	= $this->month;
        
        $this->month 	= ($this->month < 10) ? '0' . (int) $this->month : $this->month;
        
        $tracked_at 	= '01' . '-' . $this->month . '-' . $this->year;
        
        $years 			= range(date('Y'), sfConfig::get('app_begin_year'));
        
        $this->form_filter->setWidget('ps_year', new sfWidgetFormChoice(array(
            'choices' => array_combine($years, $years)
        ), array(
            'class' => 'select2',
            'style' => "min-width:100px; width:auto;",
            'data-placeholder' => _('-Select year-')
        )));
        
        $month = range(1,12);
        
        $this->form_filter->setWidget('ps_month', new sfWidgetFormChoice(array(
            'choices' => array_combine($month, $month)
        ), array(
            'class' 			=> 'select2',
            'style' 			=> "min-width:100px; width:auto;",
            'data-placeholder' 	=> _('-Select month-')
        )));
        
        $this->form_filter->setDefault('ps_month', (int)$default_month);
        $this->form_filter->setDefault('ps_year', $this->year);
        
        $this->list_relative = $this->student->getRelativesOfStudent();
        
        $this->list_member 	 = array();
        
        //$this->class 		 = $this->student->getMyClassByStudent();
        
        // Lay lop hoc o thoi diem hien tai
        if($tracked_at !=''){
            $this->class = $this->student->getClassByDate(strtotime($tracked_at));
        }else{
            $this->class = $this->student->getClassByDate(time());
        }
        
        $this->class = $this->student->getClassByDate(strtotime($tracked_at));
        
        $this->ps_work_places = null;
        
        if ($this->class) {
            
            $this->class_id  = $this->class->getMyclassId();
            $this->ps_work_places  = Doctrine::getTable('PsWorkPlaces')->findOneById($this->class->getPsWorkplaceId());
            $ps_config_late  = Doctrine::getTable('PsConfigLateFees')->getListLates($this->class->getPsWorkplaceId());
            $this->count_late_fee = count($ps_config_late);
        } else {
            $this->class_id  = null;
        }
        
        $this->student_logtime = $this->student->getLogtimeByDate($this->year . $this->month);
        
        // Kiem tra lop cua hoc sinh trong thang
        //$this->check_student_class = $this->student->checkStudentClassByDate($this->year . $this->month);
        
        $this->check_student_class = true;
        
        // Lay tat ca cac dich vu - dich vu hoc ma hoc sinh dang dang ky su dung
        $this->list_registered_service = Doctrine::getTable('Service')->getServicesByStudentId($student_id, null, time());
        
    }
    
    // Luu diem danh so theo doi  => Chưa thấy có Save History
    public function executeSaveTrackbook(sfWebRequest $request) {
        
        $student_logtimes = $request->getParameter('student_logtime');
        
        $user_id = myUser::getUserId();
        
        $conn = Doctrine_Manager::connection();
        
        try {
            $conn->beginTransaction();
            
            $index = 0;
            
            foreach ($student_logtimes as $key => $student_logtime) {
                
                $index ++;
                
                $tracked_at = date('Y-m-d', $key);
                
                if($index == 1){ // chi lay id hoc sinh 1 lan
                    $student_id = $student_logtime['student_id'];
                    $class_id = Doctrine::getTable('StudentClass')->getCurrentClassOfStudent($student_id)->getClassId();
                    $ps_student = Doctrine::getTable('Student')->findOneById($student_id);
                    $ps_customer_id = $ps_student->getPsCustomerId();
                    $student_name = $ps_student->getFirstName().' '.$ps_student->getLastName();
                }
                // Tim xem da ton tai chua
                $ps_logtimes = Doctrine_Core::getTable('PsLogtimes')->updateLogtimeByTrackedAt($student_id, $tracked_at);
                
                // Xoa bo cac dich vu da dung trong ngay diem danh
                Doctrine_Core::getTable('StudentServiceDiary')->findByStudentTrackedAt($student_id, $tracked_at)->delete();
                
                $log_value = (isset($student_logtime['log_value'])) ? $student_logtime['log_value'] : null;
                
                $choose = (isset($student_logtime['data_value'])) ? $student_logtime['data_value'] : null;
                
                if ($choose > 0) {
                    
                    $relative_login = ($student_logtime['relative_login'] != null) ? $student_logtime['relative_login'] : null;
                    
                    $member_login 	= ($student_logtime['member_login'] != null) ? $student_logtime['member_login'] : null;
                    
                    $time_in 		= ($student_logtime['login_at'] != null) ? $student_logtime['login_at'] : '00:00:00';
                    
                    $login_at 		= date("Y-m-d", strtotime($tracked_at)) . ' ' . date("H:i:s", strtotime($time_in));
                    
                    $relative_logout 	= ($student_logtime['relative_logout'] != null) ? $student_logtime['relative_logout'] : null;
                    
                    $member_logout 		= ($student_logtime['member_logout'] != null) ? $student_logtime['member_logout'] : null;
                    
                    $time_out 			= ($student_logtime['login_at'] != null) ? $student_logtime['logout_at'] : null;
                    $logout_at 			= ($time_out) ? date("Y-m-d", strtotime($tracked_at)) . ' ' . date("H:i:s", strtotime($time_out)) : null;
                    
                    // Cắt lấy 255 ký tự nếu vượt 255
                    $note 				= (strlen($student_logtime['note']) <= 255) ? $student_logtime['note'] : substr( $student_logtime['note'],  0, 255 );
                    
                    $service_name = '';
                    
                    $services = (isset($student_logtime['student_service'])) ? $student_logtime['student_service'] : array();
                    
                    foreach ($services as $service) {
                        $student_service_diary = new StudentServiceDiary();
                        $student_service_diary->setServiceId($service);
                        $student_service_diary->setStudentId($student_id);
                        $student_service_diary->setTrackedAt($tracked_at);
                        $student_service_diary->setUserCreatedId(myUser::getUserId());
                        $student_service_diary->save();
                        
                        $service_name .= Doctrine::getTable('Service')->getServiceName($service)->getTitle().", " ;
                    }
                    
                    if ($ps_logtimes) { // Neu da ton tai
//                         foreach ($ps_logtimes as $ps_logtime) {
                        
                        // kiem tra neu du lieu thay doi thi luu, khong thay doi thi thoi
                        if(strtotime($login_at)  != strtotime($ps_logtimes -> getLoginAt()) || strtotime($logout_at)  != strtotime($ps_logtimes -> getLogoutAt()) ){
                            
                            // luu diem danh
                            $ps_logtimes->setStudentId($student_id);
                            $ps_logtimes->setLoginAt($login_at);
                            $ps_logtimes->setLoginRelativeId($relative_login);
                            $ps_logtimes->setLoginMemberId($member_login);
                            $ps_logtimes->setStudentId($student_id);
                            $ps_logtimes->setLogoutAt($logout_at);
                            $ps_logtimes->setLogoutRelativeId($relative_logout);
                            $ps_logtimes->setLogoutMemberId($member_logout);
                            $ps_logtimes->setLogValue($log_value);
                            $ps_logtimes->setNote($note);
                            $ps_logtimes->setUserUpdatedId(myUser::getUserId());
                            $ps_logtimes->save();
                            
                            $trangthai = $this->getContext()->getI18N()->__('Go school');
                            
                            $history_content = $this->getContext()->getI18N()->__('Student id').": ".$student_id.'<br/>'
                            .$this->getContext()->getI18N()->__('Student name').": ". $student_name .'<br/>'
                            .$this->getContext()->getI18N()->__('Login at').": ". $login_at .'<br/>'
                            .$this->getContext()->getI18N()->__('Logout at').": ". $logout_at .'<br/>'
                            .$this->getContext()->getI18N()->__('Login relative id').": ". $relative_login .'<br/>'
                            .$this->getContext()->getI18N()->__('Login member id').": ". $member_login .'<br/>'
                            .$this->getContext()->getI18N()->__('Logout relative id').": ". $relative_logout .'<br/>'
                            .$this->getContext()->getI18N()->__('Logout member id').": ". $member_logout .'<br/>'
                            .$this->getContext()->getI18N()->__('Status').": ". $trangthai .'<br/>'
                            .$this->getContext()->getI18N()->__('Created by').": ". myUser::getUser()->getFirstName() ." ". myUser::getUser()->getLastName().'('. myUser::getUser()->getUsername() .')'. '<br/>'
                            .$this->getContext()->getI18N()->__('Used service').": ". $service_name .'<br/>'
                            ;
                            
                            // luu lich su diem danh
                            
                            $historyLogtime = new PsHistoryLogtimes();
                            
                            $historyLogtime->setPsLogtimeId($ps_logtimes->getId());
                            
                            $historyLogtime->setPsAction('edit');
                            
                            $historyLogtime->setStudentId($student_id);
                            
                            $historyLogtime->setHistoryContent($history_content);
                            
                        $historyLogtime->save();
                                                                        
                        }
                            
//                         }
                    } else {
                        
                        $ps_logtimes = new PsLogtimes();
                        $ps_logtimes->setStudentId($student_id);
                        $ps_logtimes->setLoginAt($login_at);
                        $ps_logtimes->setLoginRelativeId($relative_login);
                        $ps_logtimes->setLoginMemberId($member_login);
                        $ps_logtimes->setStudentId($student_id);
                        $ps_logtimes->setLogoutAt($logout_at);
                        $ps_logtimes->setLogoutRelativeId($relative_logout);
                        $ps_logtimes->setLogoutMemberId($member_logout);
                        $ps_logtimes->setLogValue($log_value);
                        $ps_logtimes->setNote($note);
                        $ps_logtimes->setUserCreatedId(myUser::getUserId());
                        $ps_logtimes->setUserUpdatedId(myUser::getUserId());
                        $ps_logtimes->save();
                        
                        $number_attendances = Doctrine_Core::getTable('PsAttendancesSynthetic')->getAttendanceSyntheticByDate($class_id, $tracked_at);
                        
                        if(!$number_attendances){
                            $number_attendances = new PsAttendancesSynthetic();
                            $number_attendances -> setPsCustomerId($ps_customer_id);
                            $number_attendances -> setPsClassId($class_id);
                            $number_attendances -> setLoginSum(1);
                            
                            if($logout_at != ''){
                                $number_attendances -> setLogoutSum(1);
                            }else{
                                $number_attendances -> setLogoutSum(0);
                            }
                            
                            $number_attendances -> setTrackedAt($date_at);
                            $number_attendances -> setUserUpdatedId($user_id);
                            $number_attendances -> save();
                            
                        }else{
                            
                            $number_login = $number_attendances -> getLoginSum();
                            $number_logout = $number_attendances -> getLogoutSum();
                            
                            $number_attendances -> setLoginSum($number_login+1);
                            
                            if($logout_at != ''){
                                $number_attendances -> setLogoutSum($number_logout+1);
                            }
                            $number_attendances -> setUserUpdatedId($user_id);
                            $number_attendances -> save();
                        }
                        
                        $trangthai = $this->getContext()->getI18N()->__('Go school');
                        
                        $history_content = $this->getContext()->getI18N()->__('Student id').": ".$student_id.'<br/>'
                        .$this->getContext()->getI18N()->__('Student name').": ". $student_name .'<br/>'
                        .$this->getContext()->getI18N()->__('Login at').": ". $login_at .'<br/>'
                        .$this->getContext()->getI18N()->__('Logout at').": ". $logout_at .'<br/>'
                        .$this->getContext()->getI18N()->__('Login relative id').": ". $relative_login .'<br/>'
                        .$this->getContext()->getI18N()->__('Login member id').": ". $member_login .'<br/>'
                        .$this->getContext()->getI18N()->__('Logout relative id').": ". $relative_logout .'<br/>'
                        .$this->getContext()->getI18N()->__('Logout member id').": ". $member_logout .'<br/>'
                        .$this->getContext()->getI18N()->__('Status').": ". $trangthai .'<br/>'
                        .$this->getContext()->getI18N()->__('Created by').": ". myUser::getUser()->getFirstName() ." ". myUser::getUser()->getLastName().'('. myUser::getUser()->getUsername() .')'. '<br/>'
                        .$this->getContext()->getI18N()->__('Used service').": ". $service_name .'<br/>'
                        ;
                        
                        // luu lich su diem danh
                        
                        $historyLogtime = new PsHistoryLogtimes();
                        
                        $historyLogtime->setPsLogtimeId($ps_logtimes->getId());
                        
                        $historyLogtime->setPsAction('add');
                        
                        $historyLogtime->setStudentId($student_id);
                        
                        $historyLogtime->setHistoryContent($history_content);
                        
                        $historyLogtime->save();
                                                                    
                    }
                } else {
                    
                    // Tim va xoa du lieu neu da co
                    
                    $number_attendances = Doctrine_Core::getTable('PsAttendancesSynthetic')->getAttendanceSyntheticByDate($class_id, $tracked_at);
                    
                    if($number_attendances){
                        if($logout_at !='' && $number_attendances -> getLogoutSum() > 0){
                            $number_logout = $number_attendances -> getLogoutSum();
                            $number_attendances -> setLogoutSum($number_logout-1);
                        }
                        $number_login = $number_attendances -> getLoginSum();
                        if($number_login > 0){
                            $number_attendances -> setLoginSum($number_login-1);
                        }
                        $number_attendances -> setUserUpdatedId($user_id);
                        $number_attendances -> save();
                        
                        // luu lich su xoa diem danh
                        
                        $history_content = $this->getContext()->getI18N()->__('Student id').": ".$student_id.'<br/>'
                        .$this->getContext()->getI18N()->__('Student name').": ". $student_name .'<br/>'
                        .$this->getContext()->getI18N()->__('Delete attendance').": ". $tracked_at .'<br/>'
                        ;
                        
                        if($ps_logtimes){
                            $log_id = $ps_logtimes->getId(); // xoa diem danh cua ngay
                        }
                        
                        $historyLogtime = new PsHistoryLogtimes();
                        
                        $historyLogtime->setPsLogtimeId($log_id);
                        
                        $historyLogtime->setPsAction('delete');
                        
                        $historyLogtime->setStudentId($student_id);
                        
                        $historyLogtime->setHistoryContent($history_content);
                        
                        $historyLogtime->save();
                                    
                    }
                    if($ps_logtimes){
                        $ps_logtimes->delete(); // xoa diem danh cua ngay
                    }
                }
            }
            
            $conn->commit();
            
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
            
            $this->getUser()->setFlash('error', 'Trackbook attendance was saved failed.');
            
            $conn->rollback();
        }
        
        $this->getUser()->setFlash('notice', 'Trackbook attendance was saved successfully. You can add another one below.');
        
        $this->redirect('@ps_student_info_trackbook?sid=' . $student_id . '&date_at=' . strtotime($tracked_at));
    }
    
    // luu diem danh tren man hinh so diem danh
    public function executeSaveStatistic(sfWebRequest $request) {
        
        $student_logtimes = $request->getParameter('student_logtime');
        
        $student_id = $student_logtimes['student_id'];
        // Check role
        $ps_student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );
        
        if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
            
            echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
            
            exit ( 0 );
        }
        
        $student_name = $ps_student->getFirstName () . ' ' . $ps_student->getLastName ();
        
        $user_id = myUser::getUserId();
        $service_name= '' ;
        
        $conn = Doctrine_Manager::connection();
        $student_service = array();
        try {
            $conn->beginTransaction();
            
            $index = 0;

            $tracked_at = $student_logtimes['tracked_at'];
            
            $ps_class_id = $student_logtimes['ps_class_id'];
            
            $ps_customer_id = $student_logtimes['ps_customer_id'];
            
            $log_value = $student_logtimes['log_value'];
            $relative_login = $student_logtimes['relative_login'];
            $member_login = $student_logtimes['member_login'];
            $login_at = $student_logtimes['login_at'];
            $note1 = $student_logtimes['note1'];
            $relative_logout = $student_logtimes['relative_logout'];
            $member_logout = $student_logtimes['member_logout'];
            $logout_at = $student_logtimes['logout_at'];
            $note2 = $student_logtimes['note2'];
            
            if($login_at ==''){
                $login_at = date('H:i');
            }
            if($logout_at == ''){
                $logout_date = null;
            }else{
                $logout_date = $tracked_at.' '.$logout_at;
            }
            
            $login_date = $tracked_at.' '.$login_at;
            
            if($note1 == ''){
                $note = $note2;
            }else{
                $note = $note1;
            }
            
            $relative_name_login = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_login ) ['name'];
            $member_name_login = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_login ) ['name'];
            
            $relative_name_logout = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_logout ) ['name'];
            $member_name_logout = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_logout ) ['name'];
            
            $student_service = $student_logtimes['student_service'];
            
            $records = Doctrine_Query::create ()->from ( 'StudentServiceDiary' )->addWhere ( 'student_id =?', $student_id )->andWhere ( 'DATE_FORMAT(tracked_at,"%Y%m%d") =?', date ( "Ymd", strtotime ( $tracked_at ) ) )->execute ();
            
            foreach ( $records as $record ) {
                $record->delete ();
            }
            
            foreach ( $student_service as $services ) {
                
                $student_service_diary = new StudentServiceDiary ();
                
                $student_service_diary->setServiceId ( $services );
                
                $student_service_diary->setStudentId ( $student_id );
                
                $student_service_diary->setTrackedAt ( date ( 'Y-m-d', strtotime ( $tracked_at ) ) );
                
                $student_service_diary->setUserCreatedId ( myUser::getUserId () );
                
                $student_service_diary->save ();
                
                $service_name .= Doctrine::getTable ( 'Service' )->getServiceName ( $services )->getTitle () . ", ";
            }
            
            $ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $tracked_at );
            
            if (! $ps_logtimes) {
                
                $ps_logtimes = new PsLogtimes ();
                
                $ps_logtimes->setStudentId ( $student_id );
                
                $ps_logtimes->setLoginAt ( $login_date );
                
                $ps_logtimes->setLogoutAt ( $logout_date );
                
                $ps_logtimes->setLogValue ( $log_value );
                
                $ps_logtimes->setLoginRelativeId ( $relative_login );
                
                $ps_logtimes->setLogoutRelativeId ( $relative_logout );
                
                $ps_logtimes->setNote ( $note );
                
                $ps_logtimes->setLoginMemberId ( $member_login );
                
                $ps_logtimes->setLogoutMemberId ( $member_logout );
                
                $ps_logtimes->setUserUpdatedId ( $user_id );
                
                $ps_logtimes->setUserCreatedId ( $user_id );
                
                $ps_logtimes->save ();
                
                $number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $ps_class_id, $tracked_at );
                
                if (! $number_attendances) {
                    $number_attendances = new PsAttendancesSynthetic ();
                    $number_attendances->setPsCustomerId ( $ps_customer_id );
                    $number_attendances->setPsClassId ( $ps_class_id );
                    $number_attendances->setLoginSum ( 1 );
                    if($logout_date !=''){
                        $number_attendances->setLogoutSum ( 1 );
                    }else{
                        $number_attendances->setLogoutSum ( 0 );
                    }
                    $number_attendances->setTrackedAt ( $tracked_at );
                    $number_attendances->setUserUpdatedId ( $user_id );
                    $number_attendances->save ();
                } else {
                    $number_log = $number_attendances->getLoginSum ();
                    $number_attendances->setLoginSum ( $number_log + 1 );
                    if($logout_date !=''){
                        $number_logout = $number_attendances->getLogoutSum ();
                        $number_attendances->setLogoutSum ( $logout_date + 1 );
                    }
                    $number_attendances->save ();
                }
                
            } else {
                
                $check_out = $ps_logtimes->getLogoutAt();
                
                if($check_out == '' && $logout_date !=''){
                    $number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $ps_class_id, $tracked_at );
                    if ($number_attendances) {
                        $number_log = $number_attendances->getLogoutSum ();
                        $number_attendances->setLogoutSum ( $number_log + 1 );
                        $number_attendances->setUserUpdatedId ( $user_id );
                        $number_attendances->save ();
                    }
                }
                
                $ps_logtimes->setLoginAt ( $login_date );
                
                $ps_logtimes->setLogoutAt ( $logout_date );
                
                $ps_logtimes->setLogValue ( $log_value );
                
                $ps_logtimes->setLoginRelativeId ( $relative_login );
                
                $ps_logtimes->setLogoutRelativeId ( $relative_logout );
                
                $ps_logtimes->setNote ( $note );
                
                $ps_logtimes->setLoginMemberId ( $member_login );
                
                $ps_logtimes->setLogoutMemberId ( $member_logout );
                
                $ps_logtimes->setUserUpdatedId ( $user_id );
                
                $ps_logtimes->save ();
            }
            
            // luu log diem danh cua hoc sinh
            
            if ($log_value == 1) {
                $trangthai = $this->getContext ()->getI18N ()->__ ( 'Go school' );
            } elseif ($status == 0) {
                $trangthai = $this->getContext ()->getI18N ()->__ ( 'Permission' );
            } else {
                $trangthai = $this->getContext ()->getI18N ()->__ ( 'Not Permission' );
            }
            
            $history_content = $this->getContext ()->getI18N ()->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Student name' ) . ": " . $student_name . '<br/>' 
            . $this->getContext ()->getI18N ()->__ ( 'Login at' ) . ": " . $login_date . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative id' ) . ": " . $relative_login . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login relative name' ) . ": " . $relative_name_login . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member id' ) . ": " . $member_login . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Login member name' ) . ": " . $member_name_login . '<br/>' 
            . $this->getContext ()->getI18N ()->__ ( 'Logout at' ) . ": " . $logout_date . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout relative id' ) . ": " . $relative_logout . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout relative name' ) . ": " . $relative_name_logout . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout member id' ) . ": " . $member_logout . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Logout member name' ) . ": " . $member_name_logout . '<br/>'
            . $this->getContext ()->getI18N ()->__ ( 'Status' ) . ": " . $trangthai . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . $this->getContext ()->getI18N ()->__ ( 'Used service' ) . ": " . $service_name . '<br/>';
            
            $historyLogtime = new PsHistoryLogtimes ();
            
            $historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );
            
            $historyLogtime->setPsAction ( 'add' );
            
            $historyLogtime->setStudentId ( $student_id );
            
            $historyLogtime->setHistoryContent ( $history_content );
            
            $historyLogtime->save ();
            
            $conn->commit();
            
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
            
            $this->getUser()->setFlash('error', 'Trackbook attendance was saved failed.');
            
            $conn->rollback();
        }
        
        $this->redirect('@ps_logtimes_statistic?cid='.$ps_class_id.'&date='.date('Ymd',strtotime($tracked_at)));
        
    }
    
    public function executeExportStudent(sfWebRequest $request)
    {
        $this->formFilter = new sfFormFilter();
        
        $ps_customer_id = null;
        
        $ps_workplace_id = null;
        
        $class_id = null;
        
        $ps_school_year_id = null;
        
        $export_filter = $request->getParameter ( 'export_filter' );
        
        if ($request->isMethod('post')) {
            
            $value_student_filter = $request->getParameter('export_filter');
            
            $ps_customer_id = $value_student_filter['ps_customer_id'];
            
            $ps_workplace_id = $value_student_filter['ps_workplace_id'];
            
            $ps_school_year_id = $value_student_filter['ps_school_year_id'];
            
            $class_id = $value_student_filter['class_id'];
            
            $ps_month = $value_student_filter['ps_month'];
            
            $this->exportReportFeeReceiptStudent($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$class_id,$ps_month);
            
        }
        
        $this->ps_month = isset($value_student_filter['ps_month']) ? $value_student_filter['ps_month'] : date("m-Y");
        
        // Lay nam hoc hien tai
        if ($ps_school_year_id == '') {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('is_default', PreSchool::ACTIVE);
        } else {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('id', $ps_school_year_id);
        }
        
        $yearsDefaultStart = date("Y-m", strtotime($schoolYearsDefault->getFromDate()));
        
        $yearsDefaultEnd = date("Y-m", strtotime($schoolYearsDefault->getToDate()));
        
        $this->formFilter->setWidget('ps_month', new sfWidgetFormChoice(array(
            'choices' => array(
                '' => _('-Select month-')
            ) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd)
        ), array(
            'class' => 'select2',
            'style' => "min-width:100px;",
            'required' => true,
            'placeholder' => _('-Select month-'),
            'rel' => 'tooltip',
            'data-original-title' => _('Select month')
        )));
        
        // Lay thang hien tai
        
        $this->number_day = PsDateTime::psNumberDaysOfMonth($this->ps_month);
        
        $this->formFilter->setDefault('ps_month', $this->ps_month);
        
        
        if (! myUser::credentialPsCustomers('PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL')) {
            
            $this->ps_customer_id = myUser::getPscustomerID();
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorInteger(array(
                'required' => true
            )));
        } else {
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsCustomer',
                'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
                'add_empty' => _('-All school-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => true,
                'data-placeholder' => _('-All school-')
            )));
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsCustomer',
                'required' => true
            )));
        }
        
        if($this->ps_customer_id ==''){
            $this->ps_customer_id = myUser::getPscustomerID();
            $this->formFilter->setDefault('ps_customer_id' , $this->ps_customer_id);
        }
        $this->formFilter->setDefault('ps_customer_id', $this->ps_customer_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
        
        $this->formFilter->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
            'add_empty' => true
        ), array(
            'class' => 'select2',
            'style' => "width:100%;min-width:150px;",
            'data-placeholder' => _('-Select school year-'),
            'required' => true
        )));
        
        $this->formFilter->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'column' => 'id',
            'required' => true
        )));
        
        if ($this->ps_customer_id > 0) {
            
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $this->ps_customer_id, PreSchool::ACTIVE),
                'add_empty' => _('-Select workplace-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => true,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'required' => true
            )));
            
            $this->formFilter->setWidget('class_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'MyClass',
                'query' => Doctrine::getTable('MyClass')->setClassByParams(array(
                    'ps_customer_id' => $this->ps_customer_id,
                    'ps_workplace_id' => $this->ps_workplace_id,
                    'ps_school_year_id' => $this->ps_school_year_id,
                    'is_activated' => PreSchool::ACTIVE
                )),
                'add_empty' => _('-Select class-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:150px;",
                'required' => false,
                'data-placeholder' => _('-Select class-')
            )));
            
            $this->formFilter->setValidator('class_id', new sfValidatorDoctrineChoice(array(
                'model' => 'MyClass',
                'required' => false
            )));
            
        } else {
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormChoice(array(
                'choices' => array(
                    '' => _('-Select workplace-')
                )
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;",
                'required' => false,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorPass());
            
            $this->formFilter->setWidget('class_id', new sfWidgetFormChoice(array(
                'choices' => array(
                    '' => _('-Select class-')
                )
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;",
                'required' => false,
                'data-placeholder' => _('-Select class-')
            )));
            
            $this->formFilter->setValidator('class_id', new sfValidatorPass());
            
        }
        //echo $this->ps_workplace_id;
        
        $this->formFilter->setDefault('ps_school_year_id', $this->ps_school_year_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->formFilter->setDefault('class_id', $this->class_id);
        
        $this->formFilter->setDefault('ps_month', $this->ps_month);
        
        $this->formFilter->getWidgetSchema()->setNameFormat('export_filter[%s]');
        
    }
    
    protected function exportReportFeeReceiptStudent($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$class_id,$ps_month)
    {
        $exportFile = new ExportStudentLogtimesReportHelper($this);
        
        $file_template_pb = 'bm_dulieudiemdanh.xls';
        
        $path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
        
        if($class_id > 0){
            $psClass = Doctrine::getTable ( 'MyClass' )->getClassName($class_id);
            $class_name = $psClass->getName();
            $title_xls = "DiemDanh_".date('Ym', strtotime('01-'.$ps_month)).$class_name;
        }else{
            $psWorkplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceById($ps_workplace_id);
            $class_name = $psWorkplace->getTitle();
            $title_xls = "DiemDanh_".date('Ym', strtotime('01-'.$ps_month));
        }
        
        $title_info = $this->getContext ()->getI18N ()->__ ( 'List student of month' ).$ps_month;
        
        $students = Doctrine::getTable('Student')->getListStudentServiceByClass($ps_customer_id,$ps_workplace_id,$class_id,$ps_month)->execute();
        
        $list_service =  Doctrine::getTable('Service')->getListServiceOfSchool2($ps_school_year_id, $ps_customer_id,$ps_workplace_id);
        
        $school_name = Doctrine::getTable('Pscustomer')->findOneBy('id', $ps_customer_id);
        
        $exportFile->loadTemplate($path_template_file);
        
        $exportFile->setDataExportStatisticInfoExport($school_name, $title_info,$title_xls);
        
        $exportFile->setDataExportAttendanceStudent($students,$list_service,$ps_month);
        
        $exportFile->saveAsFile("DiemDanh_".date('Ym', strtotime('01-'.$ps_month)).$class_name.".xls");
    }
    
    // Màn hình import du lieu diem danh và su dung dich vụ
    public function executeImport(sfWebRequest $request)
    {
        $this->formFilter = new sfFormFilter();
        
        $ps_customer_id = null;
        
        $ps_workplace_id = null;
        
        $ps_school_year_id = null;
        
        $this->ps_month = null;
        
        $ps_file = null;
        
        if (! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL')) {
            
            $this->ps_customer_id = myUser::getPscustomerID();
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorInteger(array(
                'required' => false
            )));
        } else {
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsCustomer',
                'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
                'add_empty' => _('-All school-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => true,
                'data-placeholder' => _('-All school-')
            )));
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsCustomer',
                'required' => true
            )));
        }
        
        if($this->ps_customer_id ==''){
            $this->ps_customer_id = myUser::getPscustomerID();
            $this->formFilter->setDefault('ps_customer_id' , $this->ps_customer_id);
        }
        
        $this->formFilter->setDefault('ps_customer_id', $this->ps_customer_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
        
        $this->formFilter->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
            'add_empty' => false
        ), array(
            'class' => 'select2',
            'style' => "width:100%;min-width:150px;",
            'data-placeholder' => _('-Select school year-'),
            'required' => true
        )));
        
        $this->formFilter->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'column' => 'id',
            'required' => true
        )));
        
        // Lay nam hoc hien tai
        if ($this->ps_school_year_id == '') {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('is_default', PreSchool::ACTIVE);
        } else {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('id', $this->ps_school_year_id);
        }
        
        // Lay thang hien tai
//         if($this->ps_month ==''){
//             $this->ps_month = date('m-Y');
//             $this->formFilter->setDefault('ps_month' , $this->ps_month);
//         }
        
        $yearsDefaultStart = date("Y-m", strtotime($schoolYearsDefault->getFromDate()));
        
        $yearsDefaultEnd = date("Y-m", strtotime($schoolYearsDefault->getToDate()));
        
        $this->formFilter->setWidget('ps_month', new sfWidgetFormChoice(array(
            'choices' => array(
                '' => _('-Select month-')
            ) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd)
        ), array(
            'class' => 'select2',
            'style' => "min-width:100px;",
            'required' => true,
            'placeholder' => _('-Select month-'),
            'rel' => 'tooltip',
            'data-original-title' => _('Select month')
        )));
        
        $this->formFilter->setValidator('ps_month', new sfValidatorChoice ( array (
            'choices' => array(
                '' => _('-Select month-')
            ) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd),
            'required' => true,
        ) ));
        
        if ($this->ps_customer_id > 0) {
            
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $this->ps_customer_id, PreSchool::ACTIVE),
                'add_empty' => _('-Select workplace-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => false,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'required' => false
            )));
            
        } else {
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormChoice(array(
                'choices' => array(
                    '' => _('-Select workplace-')
                )
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;",
                'required' => false,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorPass());
            
        }
        //echo $this->ps_workplace_id;
        
        $upload_max_size = 2000; // KB
        $upload_max_size_byte = $upload_max_size * 1024; // bytes
        
        $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
            'class' => 'form-control btn btn-default btn-success btn-psadmin',
            'style' => 'width:100%;' ) ) );
        
        $this->formFilter->setValidator('ps_file', new myValidatorFile(array(
            'required'   => true,
            'mime_types' => 'web_excel',
            'max_size' => $upload_max_size_byte
        ), array(
            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
            'max_size' => sfContext::getInstance()->getI18n()->__('The file is too large. Allowed maximum size is %value%KB', array(
                '%value%' => $upload_max_size
            ))
        )));
        
        $this->formFilter->setDefault('ps_file', $this->ps_file);
        
        $this->formFilter->setDefault('ps_school_year_id', $this->ps_school_year_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->formFilter->setDefault('ps_month', $this->ps_month);
        
        $this->formFilter->getWidgetSchema()->setNameFormat('import_filter[%s]');
        
    }
    
    // Màn hình Lưu dữ liệu import du lieu diem danh và su dung dich vụ
    public function executeImportSave(sfWebRequest $request)
    {
        $this->formFilter = new sfFormFilter();
        
        $ps_customer_id = null;
        
        $ps_workplace_id = null;
        
        $ps_school_year_id = null;
        
        $this->ps_month = null;
        
        $ps_file = null;
        
        if (! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL')) {
            
            $this->ps_customer_id = myUser::getPscustomerID();
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorInteger(array(
                'required' => false
            )));
        } else {
            
            $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsCustomer',
                'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
                'add_empty' => _('-All school-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => true,
                'data-placeholder' => _('-All school-')
            )));
            
            $this->formFilter->setValidator('ps_customer_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsCustomer',
                'required' => true
            )));
        }
        
        if($this->ps_customer_id ==''){
            $this->ps_customer_id = myUser::getPscustomerID();
            $this->formFilter->setDefault('ps_customer_id' , $this->ps_customer_id);
        }
        
        $this->formFilter->setDefault('ps_customer_id', $this->ps_customer_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
        
        $this->formFilter->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
            'add_empty' => false
        ), array(
            'class' => 'select2',
            'style' => "width:100%;min-width:150px;",
            'data-placeholder' => _('-Select school year-'),
            'required' => true
        )));
        
        $this->formFilter->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
            'model' => 'PsSchoolYear',
            'column' => 'id',
            'required' => true
        )));
        
        // Lay nam hoc hien tai
        if ($this->ps_school_year_id == '') {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('is_default', PreSchool::ACTIVE);
        } else {
            $schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('id', $this->ps_school_year_id);
        }
        
        // Lay thang hien tai
        if($this->ps_month ==''){
            $this->ps_month = date('m-Y');
            $this->formFilter->setDefault('ps_month' , $this->ps_month);
        }
        
        $yearsDefaultStart = date("Y-m", strtotime($schoolYearsDefault->getFromDate()));
        
        $yearsDefaultEnd = date("Y-m", strtotime($schoolYearsDefault->getToDate()));
        
        $this->formFilter->setWidget('ps_month', new sfWidgetFormChoice(array(
            'choices' => array(
                '' => _('-Select month-')
            ) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd)
        ), array(
            'class' => 'select2',
            'style' => "min-width:100px;",
            'required' => true,
            'placeholder' => _('-Select month-'),
            'rel' => 'tooltip',
            'data-original-title' => _('Select month')
        )));
        
        $this->formFilter->setValidator('ps_month', new sfValidatorChoice ( array (
            'choices' => array(
                '' => _('-Select month-')
            ) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd),
            'required' => true,
        ) ));
        
        
        if ($this->ps_customer_id > 0) {
            
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $this->ps_customer_id, PreSchool::ACTIVE),
                'add_empty' => _('-Select workplace-')
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => false,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorDoctrineChoice(array(
                'model' => 'PsWorkPlaces',
                'required' => false
            )));
            
        } else {
            $this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormChoice(array(
                'choices' => array(
                    '' => _('-Select workplace-')
                )
            ), array(
                'class' => 'select2',
                'style' => "min-width:200px;",
                'required' => false,
                'data-placeholder' => _('-Select workplace-')
            )));
            
            $this->formFilter->setValidator('ps_workplace_id', new sfValidatorPass());
            
        }
        //echo $this->ps_workplace_id;
        
        $upload_max_size = 2000; // KB
        $upload_max_size_byte = $upload_max_size * 1024; // bytes
        
        $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
            'class' => 'form-control btn btn-default btn-success btn-psadmin',
            'style' => 'width:100%;' ) ) );
        
        $this->formFilter->setValidator('ps_file', new myValidatorFile(array(
            'required'   => true,
            'mime_types' => 'web_excel',
            'max_size' => $upload_max_size_byte
        ), array(
            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
            'max_size' => sfContext::getInstance()->getI18n()->__('The file is too large. Allowed maximum size is %value%KB', array(
                '%value%' => $upload_max_size
            ))
        )));
        
        $this->formFilter->setDefault('ps_file', $this->ps_file);
        
        $this->formFilter->setDefault('ps_school_year_id', $this->ps_school_year_id);
        
        $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
        
        $this->formFilter->setDefault('ps_month', $this->ps_month);
        
        $this->formFilter->getWidgetSchema()->setNameFormat('import_filter[%s]');
        
        /*** Import file excel ***/
        
        $import_filter_form = $request->getParameter('import_filter');
        
        $import_filter_file = $request->getFiles('import_filter');
        
        $this->formFilter->bind($request->getParameter('import_filter'), $request->getFiles('import_filter'));
        
        // id nam hoc
        $ps_school_year_id = $this->formFilter->getValue('ps_school_year_id');
        // id truong hoc
        $ps_customer_id = $this->formFilter->getValue('ps_customer_id');
        // id co so
        $ps_workplace_id = $this->formFilter->getValue('ps_workplace_id');
        // id lop hoc
        //$class_id = $this->formFilter->getValue('class_id');
        // ps_month
        $ps_month = $this->formFilter->getValue('ps_month');
        
        if($ps_customer_id <= 0){
            $ps_customer_id = myUser::getPscustomerID();
        }
        
        // lay id giao vien cua lop
        $date_class = '01-'.$ps_month;
        $datetime = strtotime($date_class);
        
        $teacher_id = null;
        
        /*
        $teacher = Doctrine::getTable('PsTeacherClass')->getTeachersFindOneByClassId($class_id, $date_class);
        if($teacher){
            $teacher_id = $teacher -> getTeacherId();
        }else{
            $teacher_id = null;
        }
        */
        
        $array_service  = array();
        
        $service_school = Doctrine::getTable('Service')->getListServiceOfSchool2($ps_school_year_id, $ps_customer_id);
        foreach ($service_school as $services){
            array_push($array_service, $services->getId());
        }
        
        // Lấy danh sách học sinh của trường
        $students = Doctrine::getTable('Student')->getListStudentServiceByClass($ps_customer_id,$ps_workplace_id,null,null)->execute();
        //echo count($students); die;
        $array_student  = array();
        
        // Mang chứa ID học sinh của trường
        $_array_student = array();
        
        foreach ($students as $student){
            
            array_push($array_student, $student->getStudentCode());
            
            $_array_student[$student->getStudentCode()] = $student->getId();
        }
        
        $conn = Doctrine_Manager::connection();
        
        try {
            
            $conn->beginTransaction();
            
            if ($this->formFilter->isValid()) {
                
                $user_id = myUser::getUserId();
                
                $file_classify = $this->getContext()->getI18N()->__('Import logtimes');
                
                $file = $this->formFilter->getValue('ps_file');
                
                $filename = time().$file->getOriginalName();
                
                $file_link = 'Attendances'.'/'.'School_'.$ps_customer_id.'/'.date('Ym');
                
                $path_file = sfConfig::get('sf_upload_dir') .'/'.'import_data'.'/'.$file_link .'/';
                
                $file->save($path_file . $filename);
                
                $objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
                
                $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
                
                $highestRow    = $provinceSheet->getHighestRow(); // Lấy số hàng lớn nhất trong sheet
                
                $highestColumn = $provinceSheet->getHighestColumn(); // Lấy số cột lớn nhất trong sheet
                
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                
                $array_error = array();
                $note = 'Ip';
                $false = $check = $true  = 0;
                $array_id_service = $array_id_service_error = array();
                
                // $array_id_service_error : mang chua các dịch vụ không thuộc nhà truong
                
                // lay ra id cua dich vu su dung
                for ($k = 37 ;$k < $highestColumnIndex; $k++ ){
                    
                    $start = 5;
                    $id_service = $provinceSheet->getCellByColumnAndRow($k, $start)->getValue();
                    if($id_service !=''){
                        if(in_array($id_service, $array_service)){
                            array_push($array_id_service, $id_service);
                        }else{
                            $check = 1;
                            array_push($array_id_service_error, $id_service);
                        }
                    }
                    
                }
                
                // Neu tất cả dịch vụ đều thuộc của trường
                if(count($array_id_service_error) <= 0){ // kiem tra xem dich vu phai nam trong truong khong
                
                    for ($row = 6; $row <= $highestRow; $row++) {
                        
                        $index_log = 0;
                        
                        $student_code = $provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue();
                        
                        $student_code = PreString::trim($student_code);
                        
                        if($student_code !=''){
                            
                            if(in_array($student_code, $array_student)){ // Kiem tra ma hoc sinh
                                $true++;
                                $student_id = null;
                                
                                foreach ($_array_student as $key => $_student_id) {
                                    if ($key == $student_code) {
                                        $student_id = $_student_id;
                                        break;
                                    }
                                }
                                
                                // xoa du lieu diem danh cua hoc sinh trong thang
                                Doctrine::getTable('PsLogtimes')->getStudentsLogtimesCheck($student_id,$date_class)->delete();
                                
                                // lay id phu huynh cua hoc sinh
                                $relative_id = null;
                                /*
                                $relative = Doctrine::getTable('RelativeStudent')->sqlFindOneByStudentId($student_id, $ps_customer_id);
                                if($relative){
                                    $relative_id = $relative->getRelativeId();
                                }
                                */
                                $array_date = $array_date_out = array();
                                for ($k = 3 ;$k <= 33; $k++ ){  // doc du lieu tu ngay mung 1 den ngay 31
                                    
                                    $start = $row;
                                    $goschool = $provinceSheet->getCellByColumnAndRow($k, $start)->getValue();
                                    
                                    $date_at = ($k-2)."-".$ps_month;
                                    
                                    $tracked_at = date('Y-m-d', strtotime($date_at));
                                    
                                    if($goschool !=''){ // neu di hoc
                                        
                                        $index_log ++;
                                        
                                        $login_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("08:00:00");
                                        
                                        $logout_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("17:00:00");
                                        
                                        if($goschool =='p' || $goschool =='P'){             // nghi hoc co phep
                                            $value_log = 0;
                                        }elseif($goschool =='k' || $goschool =='K'){      // nghi hoc khong phep
                                            $value_log = 2;
                                            array_push($array_date_out, $tracked_at);
                                        }else{                                          // đi học
                                            $value_log = 1;
                                            array_push($array_date, $tracked_at);
                                        }
                                        
                                        $ps_logtime = new PsLogtimes();
                                        $ps_logtime -> setStudentId($student_id);
                                        $ps_logtime -> setLoginAt($login_at);
                                        $ps_logtime -> setLoginRelativeId($relative_id);
                                        $ps_logtime -> setLoginMemberId($teacher_id);
                                        $ps_logtime -> setLogoutAt($logout_at);
                                        $ps_logtime -> setLogoutRelativeId($relative_id);
                                        $ps_logtime -> setLogoutMemberId($teacher_id);
                                        $ps_logtime -> setLogValue($value_log);
                                        $ps_logtime -> setNote($note);
                                        $ps_logtime -> setUserCreatedId($user_id);
                                        $ps_logtime -> setUserUpdatedId($user_id);
                                        $ps_logtime -> save();
                                        
                                    }else{
                                        array_push($array_date_out, $tracked_at);
                                    }
                                }
                                
                                if($index_log == 0){
                                    
                                    // trang thai di hoc -- $value_log = 1;
                                    $total_log_1 = $provinceSheet->getCellByColumnAndRow(34, $row)->getCalculatedValue();
                                    $jump = 1;
                                    
                                    if($total_log_1 > 0){ // trang thai di hoc
                                        
                                        for ($jump; $jump <= $total_log_1; $jump++){
                                            
                                            $date_at = $jump."-".$ps_month;
                                            $tracked_at = date('Y-m-d', strtotime($date_at));
                                            
                                            $login_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("08:00:00");
                                            
                                            $logout_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("17:00:00");
                                            
                                            $ps_logtime = new PsLogtimes();
                                            $ps_logtime -> setStudentId($student_id);
                                            $ps_logtime -> setLoginAt($login_at);
                                            $ps_logtime -> setLoginRelativeId($relative_id);
                                            $ps_logtime -> setLoginMemberId($teacher_id);
                                            $ps_logtime -> setLogoutAt($logout_at);
                                            $ps_logtime -> setLogoutRelativeId($relative_id);
                                            $ps_logtime -> setLogoutMemberId($teacher_id);
                                            $ps_logtime -> setLogValue(1);
                                            $ps_logtime -> setNote($note);
                                            $ps_logtime -> setUserCreatedId($user_id);
                                            $ps_logtime -> setUserUpdatedId($user_id);
                                            $ps_logtime -> save();
                                            
                                        }
                                        
                                    }
                                    // trang thai nghi hoc co phep -- $value_log = 0;
                                    $total_log_2 = $provinceSheet->getCellByColumnAndRow(35, $row)->getCalculatedValue();
                                    
                                    if($total_log_2 > 0){
                                        
                                        $jump2 = $total_log_1 + 1;
                                        $total_log = $total_log_2 + $total_log_1;
                                        
                                        for ($jump2; $jump2 <= $total_log; $jump2++){
                                            
                                            $date_at = $jump2."-".$ps_month;
                                            $tracked_at = date('Y-m-d', strtotime($date_at));
                                            
                                            $login_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("08:00:00");
                                            
                                            $logout_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("17:00:00");
                                            
                                            $ps_logtime = new PsLogtimes();
                                            $ps_logtime -> setStudentId($student_id);
                                            $ps_logtime -> setLoginAt($login_at);
                                            $ps_logtime -> setLoginRelativeId($relative_id);
                                            $ps_logtime -> setLoginMemberId($teacher_id);
                                            $ps_logtime -> setLogoutAt($logout_at);
                                            $ps_logtime -> setLogoutRelativeId($relative_id);
                                            $ps_logtime -> setLogoutMemberId($teacher_id);
                                            $ps_logtime -> setLogValue(0);
                                            $ps_logtime -> setNote($note);
                                            $ps_logtime -> setUserCreatedId($user_id);
                                            $ps_logtime -> setUserUpdatedId($user_id);
                                            $ps_logtime -> save();
                                            
                                        }
                                        
                                    }
                                    // trang thai nghi hoc khong phep -- $value_log = 2;
                                    $total_log_3 = $provinceSheet->getCellByColumnAndRow(36, $row)->getCalculatedValue();
                                    
                                    if($total_log_3 > 0){
                                        
                                        $jump3 = $total_log_1 + 1 + $total_log_2;
                                        
                                        $total_log = $total_log_2 + $total_log_1 + $total_log_3;
                                        
                                        for ($jump3; $jump3 <= $total_log; $jump3++){
                                            
                                            $date_at = $jump3."-".$ps_month;
                                            
                                            $tracked_at = date('Y-m-d', strtotime($date_at));
                                            
                                            $login_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("08:00:00");
                                            
                                            $logout_at = date("Y-m-d", PsDateTime::psDatetoTime($tracked_at))." ".date("17:00:00");
                                            
                                            $ps_logtime = new PsLogtimes();
                                            $ps_logtime -> setStudentId($student_id);
                                            $ps_logtime -> setLoginAt($login_at);
                                            $ps_logtime -> setLoginRelativeId($relative_id);
                                            $ps_logtime -> setLoginMemberId($teacher_id);
                                            $ps_logtime -> setLogoutAt($logout_at);
                                            $ps_logtime -> setLogoutRelativeId($relative_id);
                                            $ps_logtime -> setLogoutMemberId($teacher_id);
                                            $ps_logtime -> setLogValue(2);
                                            $ps_logtime -> setNote($note);
                                            $ps_logtime -> setUserCreatedId($user_id);
                                            $ps_logtime -> setUserUpdatedId($user_id);
                                            $ps_logtime -> save();
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                                // xoa tat ca dich vu cua hoc sinh trong 1 thang
                                
                                //Doctrine::getTable('StudentServiceDiary')->findByStudentServiceTrackedAt($student_id,$date_class,$service_id)->delete ();
                                
                                $records = Doctrine_Query::create ()->from ( 'StudentServiceDiary' )->addWhere ( 'student_id =?', $student_id )->andWhere ( 'DATE_FORMAT(tracked_at,"%Y%m") =?', date ( "Ym", strtotime ( $date_class ) ) )->execute ();
                                
                                foreach ( $records as $record ) {
                                    $record->delete ();
                                }
                                
                                
                                $i = 0;
                                
                                for ($k = 37 ;$k < $highestColumnIndex; $k++ ){ // lay so lan su dung cua dich vu
                                    
                                    $start = $row;
                                    
                                    $number_used = 0;
                                    
                                    $service_id = $array_id_service[$i];
                                    
                                    $number_used = $provinceSheet->getCellByColumnAndRow($k, $start)->getCalculatedValue();
                                    //echo 'AAA'.$number_used.'<br/>';
                                    $number_used = trim($number_used);
                                    
                                    if(is_numeric($number_used) && $number_used > 0){
                                        
                                        $number_date = count($array_date);
                                        
                                        if($number_used <= $number_date){ // số lần sử dụng dịch vụ <= số ngày đi học
                                            
                                            for ($j = 0; $j < $number_used; $j++){
                                                
                                                $logtime_date = $array_date[$j];
                                                
                                                $student_service_diary = new StudentServiceDiary();
                                                $student_service_diary->setServiceId($service_id);
                                                $student_service_diary->setStudentId($student_id);
                                                $student_service_diary->setTrackedAt($logtime_date);
                                                $student_service_diary->setUserCreatedId($user_id);
                                                $student_service_diary->save();
                                                
                                            }
                                            
                                        }else{ // số lần sử dụng > số ngày đi học
                                            
                                            for ($j = 0; $j < $number_date; $j++){
                                                
                                                $logtime_date = $array_date[$j];
                                                
                                                $student_service_diary = new StudentServiceDiary();
                                                $student_service_diary->setServiceId($service_id);
                                                $student_service_diary->setStudentId($student_id);
                                                $student_service_diary->setTrackedAt($logtime_date);
                                                $student_service_diary->setUserCreatedId($user_id);
                                                $student_service_diary->save();
                                                
                                            }
                                            // Số lần sử dụng - Số ngày đi học = Số ngày dịch vụ vượt quá
                                            for ($q = 0; $q < $number_used - $number_date; $q++){
                                                
                                                $logtime_date_out = $array_date_out[$q];
                                                
                                                $student_service_diary = new StudentServiceDiary();
                                                $student_service_diary->setServiceId($service_id);
                                                $student_service_diary->setStudentId($student_id);
                                                $student_service_diary->setTrackedAt($logtime_date_out);
                                                $student_service_diary->setUserCreatedId($user_id);
                                                $student_service_diary->save();
                                                
                                            }
                                        }
                                    }
                                    $i ++;
                                }
                                //die;
                            }else{
                                $false++;
                                array_push($array_error, $student_code);
                            }
                        }
                    }
                    
                    $error_line = implode(' ; ', $array_error);
                    
                    if($true > 0){
                        // luu lich su import file phieu ghi no
                        $ps_history_import = new PsHistoryImport();
                        $ps_history_import -> setPsCustomerId($ps_customer_id);
                        $ps_history_import -> setPsWorkplaceId($ps_workplace_id);
                        $ps_history_import -> setFileName($filename);
                        $ps_history_import -> setFileLink($file_link);
                        $ps_history_import -> setFileClassify($file_classify);
                        $ps_history_import -> setUserCreatedId($user_id);
                        
                        $ps_history_import -> save();
                        
                    }else{
                        unlink($path_file . $filename);
                    }
                }
                
            }else{
                $error_import = $this->getContext()->getI18N()->__('Import file failed.');
                $this->getUser()->setFlash('error', $error_import);
                $this->redirect('@ps_attendances_import');
            }
            
            $conn->commit();
        }catch (Exception $e) {
            $conn->rollback();
            $error_import = $e->getMessage();
            $this->getUser()->setFlash('error', $error_import);
            $this->redirect('@ps_attendances_import');
        }
        if($check == 0){
            
            if($false == 0 && $true > 0){
                
                $successfully = $this->getContext()->getI18N()->__( 'Import file successfully %value% data. No error student code', array (
                    '%value%'  => $true
                ) );
                $this->getUser()->setFlash('notice', $successfully);
                
            }elseif($true == 0){
                $error_array     = $this->getContext()->getI18N()->__('Student code').': ' .$error_line;
                $this->getUser()->setFlash('error', $error_array);
            }else{
                $successfully   = $this->getContext()->getI18N()->__( 'Import file successfully.');
                $success_number = $this->getContext()->getI18N()->__( 'Successfully : ') .$true;
                $error_number   = $this->getContext()->getI18N()->__('Error : ').$false;
                $error_array     = $this->getContext()->getI18N()->__('Student code').': ' .$error_line;
                
                $this->getUser()->setFlash('notice', $successfully);
                $this->getUser()->setFlash('notice1', $success_number);
                $this->getUser()->setFlash('notice2', $error_number);
                $this->getUser()->setFlash('notice3', $error_array);
            }
        }else{
            $error_array     = $this->getContext()->getI18N()->__('Id service not found.')
            .$this->getContext()->getI18N()->__('ID: ') .implode(' ; ', $array_id_service_error);
            ;
            $this->getUser()->setFlash('error', $error_array);
        }
        
        $this->redirect('@ps_attendances_import');
    }
    
}
