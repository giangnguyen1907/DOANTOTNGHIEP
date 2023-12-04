<?php
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationsGeneratorHelper.class.php';

/**
 * psCmsNotifications actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psCmsNotifications
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsNotificationsActions extends autoPsCmsNotificationsActions {

	public function executeIndex(sfWebRequest $request) {

		// get values filter
		$this->type = $request->getParameter ( 'type' );
		if ($this->type) {
			$this->setFilters ( array (
					'type' => $this->type ) );
		} else
			$this->type = $this->filter_value ['type'];

		$this->filter_value = $this->getFilters ();

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_cms_notifications' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_cms_notifications' );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		$this->setTemplate ( 'index' );
	}

	public function executeDetail(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$notification_id = $request->getParameter ( 'id' );

		if ($notification_id <= 0) {

			$this->forward404Unless ( $notification_id, sprintf ( 'Object does not exist.' ) );
		}
		// lay thong tin thong bao
		$this->notification = Doctrine::getTable ( 'PsCmsNotifications' )->getNotificationById ( $notification_id );
		if ($this->filter_value ['type'] == 'received') {
			// chuyen tin chua doc thanh da doc
			$ps_cms_received_notification = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );
			if ($ps_cms_received_notification) {
				$ps_cms_received_notification->setIsRead ( '1' );
				$ps_cms_received_notification->save ();
			}
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->form = $this->configuration->getForm ();
		$this->ps_cms_notifications = $this->form->getObject ();

		// Lấy danh sách cơ sở
		// $choices = Doctrine::getTable ( 'MyClass' )->getChoisGroupMyClassByCustomer($student->getPsCustomerId(), $this->getObject ()->getMyclassId (), PreSchool::ACTIVE);
	}

	public function executeCreate(sfWebRequest $request) {

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->form = $this->configuration->getForm ();
		$this->ps_cms_notifications = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
		$this->ps_cms_notifications = $this->getRoute ()
			->getObject ();
		$this->form = $this->configuration->getForm ( $this->ps_cms_notifications );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
		$this->ps_cms_notifications = $this->getRoute ()
			->getObject ();
		$this->form = $this->configuration->getForm ( $this->ps_cms_notifications );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->filter_value = $this->getFilters ();
		$notification_id = $this->getRoute ()
			->getObject ()
			->getId ();
		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );
		// lay thong bao nhan theo $notification_id va $user_id
		$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );
		// neu thong bao nhan, chuyen vao thung rac
		if ($this->filter_value ['type'] == 'received') {

			$received->setIsDelete ( '1' );
			$received->save ();
		} else if ($this->filter_value ['type'] == 'drafts') {
			$this->getRoute ()
				->getObject ()
				->delete ();
		} else {
			// neu o thung rac hoac da gui xoa ban ghi o bang PsCmsReceivedNotification
			$received->delete ();
		}
		$this->getUser ()
			->setFlash ( 'notice', 'The item was deleted successfully.' );

		$this->redirect ( '@ps_cms_notifications' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsCmsNotifications' )
			->whereIn ( 'id', $ids )
			->execute ();
		$this->filter_value = $this->getFilters ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $record->getId (), myUser::getUserId () );
			// neu thong bao nhan, chuyen vao thung rac
			if ($this->filter_value ['type'] == 'received') {

				$received->setIsDelete ( '1' );
				$received->save ();
			} else if ($this->filter_value ['type'] == 'drafts') {
				// neu o nhap xoa PsCmsNotifications
				$record->delete ();
			} else {
				// neu o thung rac hoac da gui xoa ban ghi o bang PsCmsReceivedNotification
				$received->delete ();
			}
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_cms_notifications' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';
			$ps_customer_id = sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getPsCustomerId ();

			try {

				$ps_cms_notifications = $form->save ();

				if ($request->hasParameter ( '_save_and_add' )) {
					$ps_cms_notifications->setIsStatus ( 'sent' );
					$ps_cms_notifications->save ();

					// neu la toan he thong thi gui cho toan bo user
					if ($ps_cms_notifications->getIsSystem () == 1) {
						// lay toan bo danh sach user_id
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, null, myUser::getUserId () )
							->execute ();
					} else {
						// neu la toan truong thi gui cho toan bo user trong truong
						if ($ps_cms_notifications->getIsAll () == 1) {
							// lay toan bo danh sach user_id trong truong
							$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, $ps_customer_id, myUser::getUserId () )
								->execute ();
						}
					}

					$list_received = array ();

					if (isset ( $list_received_id )) {
						$total_object_received = count ( $list_received_id );
						$ps_cms_notifications->setTotalObjectReceived ( $total_object_received );
						$ps_cms_notifications->save ();
						// gui cho danh sach người nhận
						foreach ( $list_received_id as $received_id ) {

							if ($received_id->getId () != myUser::getUserId ()) {

								$ps_cms_received_notification = new PsCmsReceivedNotification ();

								$ps_cms_received_notification->setPsCmsNotificationId ( $ps_cms_notifications->getId () );

								$received_id = $received_id->getId ();

								array_push ( $list_received, $received_id );

								$ps_cms_received_notification->setUserId ( $received_id );

								$ps_cms_received_notification->save ();
							}
						}

						if ($list_received) {

							$str_received_id = implode ( ',', $list_received );

							$ps_cms_notifications->setTextObjectReceived ( $str_received_id );

							$ps_cms_notifications->save ();
						}
					} else { // trường hợp chọn người nhận

						$arr_received_id = explode ( ',', $ps_cms_notifications->getTextObjectReceived () );

						foreach ( $arr_received_id as $received_id ) {
							if ($received_id != myUser::getUserId ()) {
								$ps_cms_received_notification = new PsCmsReceivedNotification ();
								$ps_cms_received_notification->setPsCmsNotificationId ( $ps_cms_notifications->getId () );
								$ps_cms_received_notification->setUserId ( $received_id );
								$ps_cms_received_notification->save ();
							}
						}

						// Lay danh sách user để gửi Notify
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, $ps_customer_id, myUser::getUserId (), $arr_received_id )
							->execute ();
					}

					// gui them mot thong bao cho chinh nguoi gui
					$ps_cms_received_notification = new PsCmsReceivedNotification ();
					$ps_cms_received_notification->setPsCmsNotificationId ( $ps_cms_notifications->getId () );
					$ps_cms_received_notification->setUserId ( myUser::getUserId () );
					$ps_cms_received_notification->save ();

					// Gui Notify
					// $users_push = UserModel::getUserByArrayUserId ( $users_id, false );

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

					if (count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0) {

						$setting = new \stdClass ();

						$setting->title = $ps_cms_notifications->getTitle ();
						$setting->subTitle = $this->getContext ()
							->getI18N ()
							->__ ( 'From' ) . ' ' . sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getFirstName () . ' ' . sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getLastName ();

						$setting->message = PreString::stringTruncate ( $ps_cms_notifications->getDescription (), 100, '...' );
						$setting->tickerText = $this->getContext ()
							->getI18N ()
							->__ ( 'From' ) . ' ' . sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getFirstName () . ' ' . sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getLastName ();
						$setting->lights = '1';
						$setting->vibrate = '1';
						$setting->sound = '1';
						$setting->smallIcon = 'ic_small_notification';

						// Chỗ này cần thay bằng avatar của user gửi hoặc Logo trường
						$setting->largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

						$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_CMSNOTIFICATION;
						$setting->itemId = '0';
						$setting->clickUrl = '';

						// Deviceid registration firebase
						if ($registrationIds_ios > 0) {

							$setting->registrationIds = $registrationIds_ios;

							$notification = new PsNotification ( $setting );

							$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
						}

						if ($registrationIds_android > 0) {

							$setting->registrationIds = $registrationIds_android;

							$notification = new PsNotification ( $setting );

							$result = $notification->pushNotification ();
						}
					}
				} else {

					$ps_cms_notifications->setIsStatus ( 'drafts' );

					$ps_cms_notifications->setDateAt ( null );

					$ps_cms_notifications->save ();

					// neu la toan he thong thi gui cho toan bo user
					if ($ps_cms_notifications->getIsSystem () == 1) {
						// lay toan bo danh sach user_id
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, null, myUser::getUserId () )
							->execute ();
					} else 
					// neu la toan truong thi gui cho toan bo user trong truong
					if ($ps_cms_notifications->getIsAll () == 1) {
						// lay toan bo danh sach user_id trong truong
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( null, $ps_customer_id, myUser::getUserId () )
							->execute ();
					}
					$list_received = array ();

					if ($list_received_id) {

						$total_object_received = count ( $list_received_id );

						$ps_cms_notifications->setTotalObjectReceived ( $total_object_received );

						$ps_cms_notifications->save ();

						foreach ( $list_received_id as $received_id ) {
							if ($received_id->getId () != myUser::getUserId ()) {
								array_push ( $list_received, $received_id->getId () );
							}
						}

						if ($list_received) {
							$list_received_id = implode ( ',', $list_received );
							$ps_cms_notifications->setTextObjectReceived ( $list_received_id );
							$ps_cms_notifications->save ();
						}
					}
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
					'object' => $ps_cms_notifications ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', 'Sent successful notification.' );

				$this->redirect ( '@ps_cms_notifications?type=sent' );
			} else {

				$this->getUser ()
					->setFlash ( 'notice', 'Save to drafts successfully.' );

				$this->redirect ( '@ps_cms_notifications?type=drafts' );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}