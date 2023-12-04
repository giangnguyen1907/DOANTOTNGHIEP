<?php

require_once dirname(__FILE__).'/../lib/psFeeNewsLettersGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/psFeeNewsLettersGeneratorHelper.class.php';

/**
 * psFeeNewsLetters actions.
 *
 * @package    KidsSchool.vn
 * @subpackage psFeeNewsLetters
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeNewsLettersActions extends autoPsFeeNewsLettersActions
{
	// Cap nhat trang thai
	public function executeUpdatedStatus(sfWebRequest $request) {
		
		$fee_id = $request->getParameter ( 'fee_id' );
		
		$records = Doctrine::getTable('PsFeeNewsLetters')->getCheckFeeNewsLetters($fee_id);
		
		if (!$records) {
			
			echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
			exit ( 0 );
			
		} else {
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				// Kiem tra xem co quyen thao tac chon truong hay khong
				if (! myUser::credentialPsCustomers('PS_FEE_NEWSLETTER_FILTER_SCHOOL')) {
					$ps_customer_id = $records->getPsCustomerId ();
					if($ps_customer_id != myUser::getPscustomerID()){
						echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
						exit ( 0 );
					}
				}
				
				if($records->getIsPublic() == PreSchool::PUBLISH){
					$is_public = PreSchool::NOT_PUBLISH;
				}else{
					$is_public = PreSchool::PUBLISH;
				}
				
				$records->setIsPublic ( $is_public );
				
				$records->save ();
				
				$conn->commit ();
				
				return $this->renderPartial ( 'psFeeNewsLetters/list_field_boolean', array ('ps_fee_news_letters'=> $records ) );
				
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$this->logMessage ( "ERROR FEE NEWS LETTERS: " . $e->getMessage () );
				
				$conn->rollback ();
				
				echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
				
				exit ();
			}
		}
	}
	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {
		
		$fee_id = $request->getParameter ( 'fee_id' );
		
		$records = Doctrine::getTable('PsFeeNewsLetters')->getCheckFeeNewsLetters($fee_id);
		
		if (!$records) {
			
			echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
			exit ( 0 );
			
		} else {
			
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				if($records -> getIsPublic() == PreSchool::PUBLISH) {
				
					$ps_workplace_id = $records->getPsWorkplaceId ();
					
					// Kiem tra xem co quyen thao tac chon truong hay khong
					if (! myUser::credentialPsCustomers('PS_FEE_NEWSLETTER_FILTER_SCHOOL')) {
						$ps_customer_id = $records->getPsCustomerId ();
						if($ps_customer_id != myUser::getPscustomerID()){
							echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
							exit ( 0 );
						}
					}
					
					// Kiem tra ton tai cua co so
					if($ps_workplace_id > 0){
						
						$psWorkplace = Doctrine::getTable('PsWorkPlaces')->getWorkPlacesByWorkPlacesId($ps_workplace_id);
						
						if (!$psWorkplace) {
							echo $this->getContext ()->getI18N ()->__ ( 'PsWorkplaceId invalid' );
							exit ( 0 );
						}
						
						$records->setNumberPushNotication ( $records->getNumberPushNotication () + 1 );
						
						$records->save ();
						
						$logo_school = $psWorkplace->getLogo();
						
						if($logo_school != ''){
							$url_largeIcon = sfConfig::get ( 'app_url_site' ) . '/media/logo/' . $psWorkplace->getYearData () . '/' . $logo_school;
							$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}else{
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}
						
						$user_type_r = PreSchool::USER_TYPE_RELATIVE;
						
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( $user_type_r, null, null, null, $ps_workplace_id )->execute ();
						
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
														
							$psI18n = $this->getContext () ->getI18N ();
							
							if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
								
								$setting = new \stdClass ();
								/*
								$setting->title 	= $psI18n->__ ( 'Notice of fee news letter' ) . date ( 'm-Y', strtotime ( $records->getPsYearMonth()."01" ));
								$setting->subTitle  = $records->getTitle();
								*/								
								$setting->title 	 = $records->getTitle();
								$setting->subTitle   = $psI18n->__ ('From').' '.$psWorkplace->getName();								
								$setting->tickerText = $setting->subTitle;
								
								$setting->message = PreString::stringTruncate ( PreString::stripTags(PreString::htmlEntityDecode($records->getNote (),null, 'UTF-8')), 100, '...' );
								
								$setting->lights = '1';
								$setting->vibrate = '1';
								$setting->sound = '1';
								$setting->smallIcon = 'ic_small_notification';
								$setting->smallIconOld = 'ic_small_notification_old';
								
								$setting->largeIcon = $largeIcon;
								
								$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
								$setting->itemId = '0';
								$setting->studentId = 0;
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
					} else{// Kiem tra ton tai cua co so
						echo $this->getContext ()->getI18N ()->__ ( 'PsWorkplaceId invalid' );
						exit ( 0 );
					}
				}else{
					//echo $this->getContext ()->getI18N ()->__ ( 'Có phải bạn đang muốn hack hệ thống của chúng tôi phải không?' );
					echo $this->getContext ()->getI18N ()->__ ( 'You can public notication' );
					exit ( 0 );
				}
				
				$conn->commit ();
				
				return $this->renderPartial ( 'psFeeNewsLetters/load_number_notication', array ('ps_fee_news_letters' => $records ) );
				
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$this->logMessage ( "ERROR FEE NEWS LETTERS: " . $e->getMessage () );
				
				$conn->rollback ();
				
				echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
				
				exit ();
			}
		}
	}
}
