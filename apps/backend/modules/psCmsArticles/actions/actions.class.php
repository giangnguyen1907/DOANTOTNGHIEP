<?php
require_once dirname ( __FILE__ ) . '/../lib/psCmsArticlesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCmsArticlesGeneratorHelper.class.php';

/**
 * psCmsArticles actions.
 *
 * @package kidsschool.vn
 * @subpackage psCmsArticles
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsArticlesActions extends autoPsCmsArticlesActions {

	public function executeFilter(sfWebRequest $request)
	{
		$this->setPage(1);
		
		if ($request->hasParameter('_reset'))
		{
			$this->setFilters($this->configuration->getFilterDefaults());
			
			$this->redirect('@ps_cms_articles');
		}
		
		$this->filters = $this->configuration->getFilterForm($this->getFilters());
		
		$this->filters->bind($request->getParameter($this->filters->getName()));
		if ($this->filters->isValid())
		{
			$this->setFilters($this->filters->getValues());
			
			$this->redirect('@ps_cms_articles');
		}
		
		$this->pager = $this->getPager();
		$this->sort = $this->getSort();
		
		$this->setTemplate('blog');
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
		$this->sort  = $this->getSort ();

		$this->setTemplate ( 'blog' );
	}

	public function executeDetail(sfWebRequest $request) {

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$article_id = $request->getParameter ( 'id' );

			if ($article_id < 0) {

				$this->getUser ()
					->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			} else {

				// Lay thong tin bai viet
				$this->article_detail = Doctrine::getTable ( 'PsCmsArticles' )->getArticleById ( $article_id );
				// Lay thong tin bai viet
				$this->article_class = Doctrine::getTable ( 'PsCmsArticlesClass' )->getArticleClassById ( $article_id );
				
				if (! $this->article_detail) {
					$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
				} else {

					if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_ADD' ) || myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_EDIT' ) || myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_DELETE' )) {

						$this->setTemplate ( 'detail' );
					
					} elseif ($this->article_detail->getIsPublish () == PreSchool::ACTIVE) {

						$this->setTemplate ( 'detailPublic' ); // GV
					
					} else {

						$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
					}
				}
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_FILTER_SCHOOL' )) {

			$ps_customer_id = $request->getParameter ( 'customer_id' );

			if ($ps_customer_id > 0) {
				$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );
			}
		}

		$this->ps_cms_articles = $this->form->getObject ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_cms_articles = $this->form->getObject ();

		//$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_cms_articles, 'PS_CMS_ARTICLES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_cms_articles = $this->getRoute ()->getObject ();
		
		// neu ko co quyen sua thi bao loi
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_cms_articles, 'PS_CMS_ARTICLES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		if ($this->ps_cms_articles->is_global == PreSchool::ACTIVE && !myUser::credentialPsCustomers ('PS_CMS_ARTICLES_SYSTEM')) {
			
			$this->getUser()->setFlash('warning', "The article you asked for is secure and you do not have proper credentials.");
			
			$this->setTemplate ('messages','psCpanel');
		}
		
		$this->form = $this->configuration->getForm ( $this->ps_cms_articles );
		
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_cms_articles = $this->getRoute()->getObject();
		
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_cms_articles, 'PS_CMS_ARTICLES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		$this->form = $this->configuration->getForm($this->ps_cms_articles);
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('edit');	
	}
	
	public function executeUpdatePublish(sfWebRequest $request) {

		$article_id = $request->getParameter ( 'id' );

		$state = $request->getParameter ( 'state' );

		$this->ps_cms_articles = Doctrine::getTable ( 'PsCmsArticles' )->findOneById ( $article_id );

		// neu ko co quyen sua thi bao loi
		if (myUser::checkAccessObject ( $this->ps_cms_articles, 'PS_CMS_ARTICLES_FILTER_SCHOOL' ) && ($state >= 0 && $state <= 2)) {

			//$new_isPublish = ($this->ps_cms_articles->getIsPublish () == PreSchool::PUBLISH) ? PreSchool::NOT_PUBLISH : PreSchool::PUBLISH;

		    $ps_customer_id = $this->ps_cms_articles->getPsCustomerId();
		    
		    $ps_workplace_id = $this->ps_cms_articles->getPsWorkplaceId();
		    
		    $status_old = $this->ps_cms_articles->getIsPublish();
		    
		    $is_global = $this->ps_cms_articles->getIsGlobal();
		    
		    $is_access = $this->ps_cms_articles->getIsAccess();
		    
			$this->ps_cms_articles->setIsPublish ( $state );

			$this->ps_cms_articles->save ();
			
			$psCmsArticlesClass = Doctrine::getTable('PsCmsArticlesClass')->checkArticleClassById($article_id);
			
			$ps_class_ids = array();
			
			if($state == PreSchool::PUBLISH && $status_old != $state){
				
				$list_received_id = array ();
				$arr_received_id  = array ();
				
				if(count($psCmsArticlesClass) > 0){
					
					foreach ($psCmsArticlesClass as $articles_class){
						array_push($ps_class_ids,$articles_class->getPsClassId());
					}
					
					$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( $ps_customer_id, $ps_workplace_id, myUser::getUserId (),$ps_class_ids );
					foreach ( $list_teacher as $teacher ) {
						array_push ( $list_received_id, $teacher );
					}
					
					$list_relative = array();
					
					if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
						$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( $ps_customer_id, $ps_workplace_id,$ps_class_ids );
					}
					foreach ( $list_relative as $relative ) {
						array_push ( $list_received_id, $relative );
					}
					
				}else if($is_global == PreSchool::ACTIVE){ // Neu la tin toan he thong
			    	
			    	$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( null, null, myUser::getUserId () );
			    	foreach ( $list_teacher as $teacher ) {
			    		array_push ( $list_received_id, $teacher );
			    	}
			    	$list_relative = array();
			    	if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
			    		$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( null, null );
			        }
			        foreach ( $list_relative as $relative ) {
		    			array_push ( $list_received_id, $relative );
		    		}
		    		
			    }else{ // Khong phai tin toan he thong
			    	
			    	$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( $ps_customer_id, $ps_workplace_id, myUser::getUserId () );
			    	foreach ( $list_teacher as $teacher ) {
			    		array_push ( $list_received_id, $teacher );
			    	}
			    	$list_relative = array();
			    	if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
			    		$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( $ps_customer_id, $ps_workplace_id );
			    	}
			    	foreach ( $list_relative as $relative ) {
			    		array_push ( $list_received_id, $relative );
			    	}
			    	
			    }
			    
			    $check_count = count ( $list_received_id );
			    
			    if($check_count > 0){
			    	
			    	// gui notification
			    	$registrationIds_ios = array ();
			    	$registrationIds_android = array ();
			    	
			    	foreach ( $list_received_id as $user_nocation ) {
			    		if ($user_nocation->getNotificationToken () != '') {
			    			if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS){
			    				array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
			    			}else{
		    					array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
			    			}
			    		}
			    	}
			    	
			    	$psI18n = $this->getContext ()->getI18N ();
			    	
			    	$subTitle = $this->getContext ()->getI18N ()->__('Articles from school');
			    	
			    	if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
			    		
			    		$setting = new \stdClass ();
			    		
			    		$setting->title 	 = $this->ps_cms_articles->getTitle ();
			    		$setting->subTitle 	 = $subTitle;
			    		
			    		$setting->message 	 = PreString::stringTruncate ( $this->ps_cms_articles->getNote (), 100, '...' );
			    		
			    		$setting->tickerText = $this->ps_cms_articles->getTitle ();
			    		
			    		$setting->lights  	= '1';
			    		$setting->vibrate 	= '1';
			    		$setting->sound 	= '1';
			    		$setting->smallIcon = 'ic_small_notification';
			    		
			    		$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
			    		
			    		$setting->largeIcon  = $largeIcon;
			    		
			    		$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_ARTICLES_DETAIL;
			    		$setting->itemId 	 = $this->ps_cms_articles->getId();
			    		$setting->clickUrl 	 = '';
			    		
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
			    
			}
			
			return $this->renderPartial ( 'psCmsArticles/box_is_publish', array (
					'ps_cms_articles' => $this->ps_cms_articles ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->ps_cms_articles = $this->getRoute ()
			->getObject ();

		// neu ko co quyen xoa thi bao loi
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_cms_articles, 'PS_CMS_ARTICLES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Lay thong tin de xoa anh
		/*
		 * $created_at = strtotime ( $this->ps_cms_articles->getCreatedAt () );
		 * $customer_id = $this->ps_cms_articles->getPsCustomerId ();
		 * $yyyy = date ( "Y", $created_at );
		 * $mm = date ( "m", $created_at );
		 * $link = sfConfig::get ( 'sf_web_dir' ) . '/uploads/cms_articles/' . $customer_id . '/' . $yyyy . '/' . $mm;
		 */
		try {

			$link = sfConfig::get ( 'sf_web_dir' ) . '/uploads/cms_articles/' . date ( "Y/m/d", strtotime ( $this->ps_cms_articles->getCreatedAt () ) );

			$file_name = $this->ps_cms_articles->getFileName ();

			if (is_file ( $link . '/' . $file_name )) {
				unlink ( $link . '/' . $file_name );
			}

			if (is_file ( $link . '/thumb/' . $file_name )) {
				unlink ( $link . '/thumb/' . $file_name );
			}

			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		} catch ( Exception $e ) {
		}

		$this->redirect ( '@ps_cms_articles' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {
		
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		
		if ($form->isValid()) {
			
			$notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
			
			try {
				
				$ps_class_ids = $form->getValue('ps_class_ids');
				
				$ps_cms_articles = $form->save();
				
				// set notication topics name for one group
				$topics_id_name = ''; 
				
				$ps_customer_id  = $ps_cms_articles->getPsCustomerId();
				
				$ps_workplace_id = $ps_cms_articles->getPsWorkplaceId();
				
				$is_access 		 = $ps_cms_articles->getIsAccess();
				
				$articles_id = $ps_cms_articles->getId();
				
				$user_id = myUser::getUserId();
				// kiem tra va xoa het cac lop dang ap dung tin tuc
				$check_data = Doctrine::getTable('PsCmsArticlesClass')->checkArticleClassById($articles_id)->delete();
				
				$_ps_class_ids = array();
				
				if (is_array($ps_class_ids) && count($ps_class_ids) > 0) {
					foreach ($ps_class_ids as $ps_class_id){
						if($ps_class_id > 0){
							$psCmsArticlesClass = new PsCmsArticlesClass();
							$psCmsArticlesClass -> setPsArticleId($articles_id);
							$psCmsArticlesClass -> setPsClassId($ps_class_id);
							$psCmsArticlesClass -> setUserCreatedId($user_id);
							$psCmsArticlesClass -> save();
							
							array_push($_ps_class_ids, $ps_class_id);
						}
					}
				}
				
				if($ps_cms_articles->getIsPublish() == PreSchool::PUBLISH) {
					
					$list_received_id = array ();
					
					$arr_received_id = array ();
					
					// Neu tin tuc chon theo lop
					if(count($_ps_class_ids) > 0){
						
						$topics_id_name = 'TOPICS_ID_NAME_'.$ps_customer_id;
						
						$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( $ps_customer_id, $ps_workplace_id, myUser::getUserId (),$ps_class_ids );
						
						foreach ( $list_teacher as $teacher ) {
							array_push ( $list_received_id, $teacher );
						}
						
						$list_relative = array();
						
						if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
							
							$topics_id_name = 'TOPICS_ID_NAME_'.$ps_customer_id.'_'.$ps_workplace_id;
							
							$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( $ps_customer_id, $ps_workplace_id,$ps_class_ids );
						}
						
						foreach ( $list_relative as $relative ) {
							array_push ( $list_received_id, $relative );
						}
						
					} else if($ps_cms_articles->getIsGlobal() == PreSchool::ACTIVE) { // Neu la tin toan he thong
						
						$topics_id_name = 'TOPICS_ID_NAME_GLOBAL';
						
						$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( null, null, myUser::getUserId () );
						
						foreach ( $list_teacher as $teacher ) {
							array_push ( $list_received_id, $teacher );
						}
						
						$list_relative = array();
						
						if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
							
							$topics_id_name = 'TOPICS_ID_NAME_GLOBAL';
							
							$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( null, null );
						}
						
						foreach ( $list_relative as $relative ) {
							array_push ( $list_received_id, $relative );
						}						
					} else { // Khong phai tin toan he thong
						
						$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( $ps_customer_id, $ps_workplace_id, myUser::getUserId () );
						foreach ( $list_teacher as $teacher ) {
							array_push ( $list_received_id, $teacher );
						}
						
						$list_relative = array();
						
						if($is_access == PreSchool::ACTIVE){ // Cả giáo viên và phụ huynh đều xem được
							$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( $ps_customer_id, $ps_workplace_id );
						}
						foreach ( $list_relative as $relative ) {
							array_push ( $list_received_id, $relative );
						}
						
					}
					
					$check_count = count ( $list_received_id );
					
					if($check_count > 0){
						
						$subTitle = $this->getContext ()->getI18N ()->__('Articles from school');
						
						// gui notification
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						foreach ( $list_received_id as $user_nocation ) {
							if ($user_nocation->getNotificationToken () != '') {
								if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS){
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								}else{
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
								}
							}
						}
						
						$psI18n = $this->getContext ()->getI18N ();
						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();
							
							$setting->title = $this->ps_cms_articles->getTitle ();
							$setting->subTitle = $subTitle;
							
							$setting->message = PreString::stringTruncate ( $this->ps_cms_articles->getNote (), 100, '...' );
							
							$setting->tickerText = $this->ps_cms_articles->getTitle ();
							
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';
							
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							
							$setting->largeIcon = $largeIcon;
							
							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_ARTICLES_DETAIL;
							$setting->itemId = $this->ps_cms_articles->getId();
							$setting->clickUrl = '';
							
							// Deviceid registration firebase
							if (count ( $registrationIds_ios ) > 0) {
								$setting->registrationIds = $registrationIds_ios;
								
								$notification = new PsNotification ( $setting );
								if(!myUser::isAdministrator())
									$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
							}
							
							if (count ( $registrationIds_android ) > 0) {
								$setting->registrationIds = $registrationIds_android;
								
								$notification = new PsNotification ( $setting );
								if(!myUser::isAdministrator())
									$result = $notification->pushNotification ();
							}
						}
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
			
			$this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_cms_articles)));
			
			if ($request->hasParameter('_save_and_add')) {
				
				$this->getUser()->setFlash('notice', $notice.' You can add another one below.');
				
				$this->redirect('@ps_cms_articles_new');
			
			} else {
				
				$this->getUser()->setFlash('notice', $notice);
				
				$this->redirect(array('sf_route' => 'ps_cms_articles_edit', 'sf_subject' => $ps_cms_articles));
			}
		}
		else
		{
			$this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
		}
	}
	
}
