<?php
require_once dirname(__FILE__) . '/../lib/psAlbumsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/psAlbumsGeneratorHelper.class.php';

/**
 * psAlbums actions.
 *
 * @package kidsschool.vn
 * @subpackage psAlbums
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAlbumsActions extends autoPsAlbumsActions {

	// Cap nhat trang thai album
	public function executeUpdateStatus(sfWebRequest $request) {

		$album_id = $request->getParameter('album_id');
		$status   = $request->getParameter('status');
		$user_id  = myUser::getUserId();
		
		// Check role
		$ps_albums 	= Doctrine_Core::getTable('PsAlbums')->findOneById($album_id);		
		$status2 	= $ps_albums->getIsActivated();
		
		if (! myUser::checkAccessObject($ps_albums, 'PS_CMS_ALBUMS_FILTER_SCHOOL')) {
			echo $this->getContext()
				->getI18N()
				->__('Object does not exist .');
			
			exit(0);
		} else {
			
			if ($ps_albums->getIsActivated() != $status && in_array($status, array(0,1,2))) {
				
				$ps_albums->setIsActivated($status);
				
				$ps_albums->setUserUpdatedId($user_id);
				
				$ps_albums->save();
				
				// Nếu trạng thái = 1 và trạng thái ban đầu khác 1 thì gửi tin nhắn (Tránh gửi n lần liên tục khi chọn cùng 1 trạng thái public)
				if ($status == PreSchool::PUBLISH /*&& $status2 != $status*/) {
					
					// Gui thong bao
					$ps_customer_id = $ps_albums->getPsCustomerId();
					$class_id = $ps_albums->getPsClassId();
					
					$psClass = Doctrine::getTable('MyClass')->getCustomerInfoByClassId($class_id);
					
					$class_name = $psClass->getMcName();
					
					if ($ps_customer_id > 0) {
						
						// Lay danh sach phu huynh de gui thong bao
						$notication_relatives = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationMsg($ps_customer_id, $class_id, null);
						
						$registrationIds_ios 	 = array();
						$registrationIds_android = array();
						
						foreach ($notication_relatives as $relative) {
							if ($relative->notification_token != '') {
								if ($relative->osname == PreSchool::PS_CONST_PLATFORM_IOS) {
									array_push($registrationIds_ios, $relative->notification_token);
								} else {
									array_push($registrationIds_android, $relative->notification_token);
								}
							}
						}
						
						$psI18n = $this->getContext()->getI18N();
						
						if ((count($registrationIds_android) > 0 || count($registrationIds_ios) > 0)) {
							
							$setting = new \stdClass();
							
							$setting->title = $psI18n->__('Albums') . ': ' . $ps_albums->getTitle();
							
							$setting->subTitle = $psI18n->__('Of class') . ': ' . $class_name;
							
							$setting->tickerText = $psI18n->__('Albums') . ': ' . $ps_albums->getTitle();
							
							$setting->message = PreString::stringTruncate($ps_albums->getNote(), 100, '...');
							
							$setting->lights   = 1;
							$setting->vibrate  = 1;
							$setting->sound    = 1;
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							
							// Lay avatar nguoi public album
							$profile = $this->getUser()->getGuardUser()->getProfileShort();
							
							if ($profile && $profile->getAvatar() != '') {
								
								$url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
								
								$largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}
							
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							
							$setting->largeIcon 	= $largeIcon;
							
							$setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_ALBUMS;							
							//$setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_ALBUM_DETAIL;							
							
							$setting->itemId 	 	= $ps_albums->getId();
							$setting->clickUrl 		= '';
							
							// Deviceid registration firebase
							if (count($registrationIds_ios) > 0) {
								$setting->registrationIds = $registrationIds_ios;
								
								$notification = new PsNotification($setting);
								$result = $notification->pushNotification(PreSchool::PS_CONST_PLATFORM_IOS);
							}
							
							if (count($registrationIds_android) > 0) {
								$setting->registrationIds = $registrationIds_android;
								
								$notification = new PsNotification($setting);
								$result = $notification->pushNotification();
							}
						} // end sent notication
					}
				}
			}
			
			return $this->renderPartial('psAlbums/list_field_boolean', array(
					'type' => 'list',
					'value' => $status,
					'album_id' => $album_id
			));
		}
	
	}

	// Cap nhat trang thai anh
	public function executeAlbumItemActivated(sfWebRequest $request) {

		$album_item_id = $request->getParameter('id');
		
		$album_item_state = $request->getParameter ( 'state' );

		if ($album_item_state == PreSchool::ACTIVE) {

			$status = 1;
		} elseif ($album_item_state == PreSchool::NOT_ACTIVE) {

			$status = 0;
		} else {

			$status = 2;
		}

		$albumItem = Doctrine::getTable ( 'PsAlbumItems' )->findOneById ( $album_item_id );

		if ($albumItem) {

			$albumItem->setIsActivated ( $status );

			$albumItem->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getId () );

			$albumItem->save ();

			return $this->renderPartial ( 'psAlbums/box_status_2', array (
					'a' => $albumItem ) );
		} else {

			return $this->renderPartial ( 'psAlbums/box_status_error' );
		}
	}

	// Download images
	public function executeArchiveDownload(sfWebRequest $request) {
		
		$album_id 		= $request->getParameter ( 'export_album_id' );
		
		// Check role
		$ps_album 	= Doctrine_Core::getTable('PsAlbums')->findOneById($album_id);
				
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_album, 'PS_CMS_ALBUMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist .') );
		
		$album_items = Doctrine::getTable ( 'PsAlbumItems' )->getItemsByAlbumId ( $album_id );
		
		try {
			
			$folder_name = PreString::trim(PreString::covert_to_latin($ps_album->getTitle()));
			
			if (PreString:: length($folder_name) <= 0) {
				$folder_name = 'album-'.$album_id;
			}
			
			$tmpFile = tempnam(sys_get_temp_dir(), 'KidsSchoolAlbum');
						
			$zip = new ZipArchive;
			
			$zip->open($tmpFile, ZipArchive::CREATE);
			
			foreach ($album_items as $item) {
				
				// download file				
				$file_last   = strstr($item->getUrlFile(),'?');
				
				$file 		 = str_replace( $file_last, '', $item->getUrlFile() );
				
				$fileContent = file_get_contents($item->getUrlFile());
				
				$zip->addFromString(basename($file), $fileContent);
				
			}
			
			$zip->close();
			
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header ( "Cache-Control: private", false );
			header ( "Content-Description: File Transfer" );
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$folder_name.'.zip');
			header ( "Content-Transfer-Encoding: binary" );
			header('Content-Length: ' . filesize($tmpFile));
			
			readfile($tmpFile);
			
			unlink($tmpFile);			
			
		} catch (Exception $e) {
			
			$this->getUser ()->setFlash ( 'error', $e->getMessage () );
			
		}
		
		$this->redirect('@ps_albums_detail?id='.$album_id);
		
	}
	
	public function executeDetail(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();
		
		$albums_id = $request->getParameter ( 'id' );

		if ($albums_id <= 0) {

			$this->forward404Unless ( $albums_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_album = Doctrine::getTable ( 'PsAlbums' )->getAlbumsById ( $albums_id );

		if (! $this->ps_album) {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_album, 'PS_CMS_ALBUMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist .') );

		// Lay thong tin items
		$this->album_items = Doctrine::getTable ( 'PsAlbumItems' )->getItemsByAlbumId ( $albums_id );

	}

	/* */
	public function executeNew(sfWebRequest $request) {

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext()->getI18N()->__('Can not create Object on web.') );
		$this->redirect ( '@ps_albums' );
	}

	public function executeDelete(sfWebRequest $request) {

		// $this->forward404Unless($request->getParameter('id'), sprintf('Can not delete Object on web.'));
		$this->getUser ()
		->setFlash ( 'notice', $this->getContext()->getI18N()->__('Can not delete Object on web.') );
		$this->redirect ( '@ps_albums' );
	}

	public function executeEdit(sfWebRequest $request) {

		$ps_albums = $this->ps_albums = $this->getRoute ()->getObject ();
		
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_albums, 'PS_CMS_ALBUMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist .' ) );
		
		$this->form = $this->configuration->getForm ( $this->ps_albums );
		
	}

	public function executeIndex(sfWebRequest $request) {
		
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

		$layout = $request->getParameter ( 'layout', 'index' );

		$this->setTemplate ( $layout );
	}

	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		if ($form->isValid())
		{
			$notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
			
			try {
				$ps_albums = $form->save();
				
				if($ps_albums->getIsActivated() == PreSchool::PUBLISH){
					
					// Gui thong bao
					$ps_customer_id = $ps_albums->getPsCustomerId();
					$class_id = $ps_albums->getPsClassId();
					
					$psClass = Doctrine::getTable('MyClass')->getCustomerInfoByClassId($class_id);
					
					$class_name = $psClass->getMcName();
					
					if ($ps_customer_id > 0) {
						
						// Lay danh sach phu huynh de gui thong bao
						$notication_relatives = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationMsg($ps_customer_id, $class_id, null);
						
						$registrationIds_ios 	 = array();
						$registrationIds_android = array();
						
						foreach ($notication_relatives as $relative) {
							if ($relative->notification_token != '') {
								if ($relative->osname == PreSchool::PS_CONST_PLATFORM_IOS) {
									array_push($registrationIds_ios, $relative->notification_token);
								} else {
									array_push($registrationIds_android, $relative->notification_token);
								}
							}
						}
						
						$psI18n = $this->getContext()->getI18N();
						
						if ((count($registrationIds_android) > 0 || count($registrationIds_ios) > 0)) {
							
							$setting = new \stdClass();
							
							$setting->title = $psI18n->__('Albums') . ': ' . $ps_albums->getTitle();
							
							$setting->subTitle = $psI18n->__('Of class') . ': ' . $class_name;
							
							$setting->tickerText = $psI18n->__('Albums') . ': ' . $ps_albums->getTitle();
							
							$setting->message = PreString::stringTruncate($ps_albums->getNote(), 100, '...');
							
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							
							// Lay avatar nguoi public album
							$profile = $this->getUser()
							->getGuardUser()
							->getProfileShort();
							
							if ($profile && $profile->getAvatar() != '') {
								
								$url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
								
								$largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}
							
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							
							$setting->largeIcon = $largeIcon;
							
							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_ALBUMS;
							$setting->itemId = $ps_albums->getId();
							$setting->clickUrl = '';
							
							// Deviceid registration firebase
							if (count($registrationIds_ios) > 0) {
								$setting->registrationIds = $registrationIds_ios;
								
								$notification = new PsNotification($setting);
								$result = $notification->pushNotification(PreSchool::PS_CONST_PLATFORM_IOS);
							}
							
							if (count($registrationIds_android) > 0) {
								$setting->registrationIds = $registrationIds_android;
								
								$notification = new PsNotification($setting);
								$result = $notification->pushNotification();
							}
						} // end sent notication
					}
				}
				
			} catch (Doctrine_Validator_Exception $e) {
				
				$errorStack = $form->getObject()->getErrorStack();
				
				$message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
				foreach ($errorStack as $field => $errors) {
					$message .= "$field (" . implode(", ", $errors) . "), ";
				}
				$message = trim($message, ', ');
				
				$this->getUser()->setFlash('error', $message);
				return sfView::SUCCESS;
			}
			
			$this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_albums)));
			
			if ($request->hasParameter('_save_and_add'))
			{
				$this->getUser()->setFlash('notice', $notice.' You can add another one below.');
				
				$this->redirect('@ps_albums_new');
			}
			else
			{
				$this->getUser()->setFlash('notice', $notice);
				
				$this->redirect(array('sf_route' => 'ps_albums_edit', 'sf_subject' => $ps_albums));
			}
		}
		else
		{
			$this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
		}
	}
	
}
