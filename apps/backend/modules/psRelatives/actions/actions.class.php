<?php
require_once dirname ( __FILE__ ) . '/../lib/psRelativesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psRelativesGeneratorHelper.class.php';

/**
 * psRelatives actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psRelatives
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRelativesActions extends autoPsRelativesActions {

	public function executeCheckEmail(sfWebRequest $request) {

		$email = $request->getParameter ( 'email' );

		$objid = $request->getParameter ( 'objid' );

		echo json_encode ( array (
				'valid' => psValidatorEmail::checkUniqueEmailPsMember ( $email, $objid, PreSchool::USER_TYPE_RELATIVE ) ) );

		exit ( 0 );
	}

	public function executeRestore(sfWebRequest $request) {
		
		$relative_id = $request->getParameter ( 'id' );
		
		//echo $relative_id; die;
		
		//$this->relative = $this->getRoute ()->getObject ();
		
		$this->relative = Doctrine::getTable('Relative')->getRelativeByField($relative_id,'deleted_at,user_updated_id,ps_customer_id');
		
		$this->forward404Unless ( myUser::checkAccessObject ( $this->relative, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		if ($this->relative && $this->relative->getDeletedAt () != '') {
			
			$this->relative->setDeletedAt ( null );
			$this->relative->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
			
			if ($this->relative->save ())$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'This relatives has been restore.' ) );
		
			// kiem tra xem da co tai khoan chua
			$updated_user = Doctrine::getTable('sfGuardUser')->checkAccountRelative($relative_id);
			if($updated_user){
				$updated_user -> setIsActive(PreSchool::ACTIVE);
				$updated_user -> setUserUpdatedId(myUser::getUserId());
				$updated_user -> save();
			}
		}
		
		$this->redirect ( '@ps_relatives' );
	}
	
	public function executeCheckIdentityCard(sfWebRequest $request) {

		$identity_card = $request->getParameter ( 'identity_card' );

		$objid = $request->getParameter ( 'objid' );

		if (isset ( $identity_card ) && $identity_card != '') {

			$identity_card_boolean = Doctrine::getTable ( 'Relative' )->checkIdentityCardExits ( $identity_card, $objid );

			echo json_encode ( array (
					'valid' => ! $identity_card_boolean ) );
		} else {

			echo json_encode ( array (
					'valid' => true ) );
		}

		exit ( 0 );
	}

	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$this->school_year_id = null;

		$this->class_list = array ();

		$this->ps_class_id = null;

		//$this->ps_month = null;
		$this->type_statistic = 0;
		
		$this->app_mobile_actived = null;

		$this->filter_member_statistic = array ();

		$filter_member_statistic = $request->getParameter ( 'member_statistic_filter' );

		if ($filter_member_statistic) {

			$this->school_year_id = isset ( $filter_member_statistic ['school_year_id'] ) ? $filter_member_statistic ['school_year_id'] : 0;

			//$this->ps_month = $filter_member_statistic ['ps_month'];

			$this->ps_workplace_id = isset ( $filter_member_statistic ['ps_workplace_id'] ) ? $filter_member_statistic ['ps_workplace_id'] : 0;

			$this->ps_class_id = isset ( $filter_member_statistic ['ps_class_id'] ) ? $filter_member_statistic ['ps_class_id'] : 0;

			$this->app_mobile_actived = isset ( $filter_member_statistic ['app_mobile_actived'] ) ? $filter_member_statistic ['app_mobile_actived'] : 0;
			
			$this->type_statistic = isset ( $filter_member_statistic ['type_statistic'] ) ? $filter_member_statistic ['type_statistic'] : 0;
			
			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_member_filter = $request->getParameter ( 'member_statistic_filter' );

			$ps_customer_id = $value_member_filter ['ps_customer_id'];
			$this->ps_customer_id = $ps_customer_id;

			$ps_workplace_id = $value_member_filter ['ps_workplace_id'];
			$this->ps_workplace_id = $ps_workplace_id;
			
			$school_year_id = $value_member_filter ['school_year_id'];
			$this->school_year_id = $school_year_id;

			//$this->ps_month = $value_member_filter ['ps_month'];

			$this->ps_class_id = $value_member_filter ['ps_class_id'];

			$this->app_mobile_actived = $value_member_filter ['app_mobile_actived'];
			
			$this->type_statistic = $value_member_filter ['type_statistic'];
			
			if($this->type_statistic == 0){
			
				if ($this->ps_class_id > 0) {
					$this->filter_member_statistic = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount ( $ps_customer_id, $this->ps_class_id, null, null, $this->app_mobile_actived );
					$relative_id = array_column ( $this->filter_member_statistic->toArray (), 'id' );
					$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
						->fetchArray ();
					$this->students = array ();
					foreach ( $student as $student ) {
						$this->students [$student ['relative_id']] [] = "{$student['student_name']}    ({$student['mc_name']})";
					}
				} elseif ($ps_workplace_id > 0) {
					$this->class_list = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
							'ps_school_year_id' => $school_year_id,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE ) ) ->toArray ();
	
					$this->filter_member_statistic = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount2 ( $ps_customer_id, $ps_workplace_id, null, $this->app_mobile_actived );
					$this->students = array ();
				}
			}else{
				
				if ($this->ps_class_id > 0) {
					$this->filter_member_statistic = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount ( $ps_customer_id, $this->ps_class_id, null, null, $this->app_mobile_actived );
					$relative_id = array_column ( $this->filter_member_statistic->toArray (), 'id' );
					$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
					->fetchArray ();
					$this->students = array ();
					foreach ( $student as $student ) {
						$this->students [$student ['relative_id']] [] = "{$student['student_name']}    ({$student['mc_name']})";
					}
					//$this->filter_member_statistic2 = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount2 ( $ps_customer_id, $this->ps_class_id, $this->app_mobile_actived );
				} elseif ($ps_workplace_id > 0) {
					$this->class_list = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
							'ps_school_year_id' => $school_year_id,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE ) ) ->toArray ();
					
					$this->array_class = array_column ( $this->class_list, 'id' );
					
					$this->filter_member_statistic = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount2 ( $ps_customer_id, $ps_workplace_id, null, $this->app_mobile_actived );
					$this->students = array ();
					
				}
				
			}
		}

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE ),
					'add_empty' => '-Select customer-' ), array (
					'class' => 'select2',
					'required' => 'required',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select customer-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		}

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		// Lay nam hoc hien tai
		if ($this->school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
			$this->school_year_id = $schoolYearsDefault->getId();
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $school_year_id );
			$this->school_year_id = $schoolYearsDefault->getId();
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				// 'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears(),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => false,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

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
			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
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

		$this->formFilter->setWidget ( 'app_mobile_actived', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select status actived-' ) ) + PreSchool::getAppMobileActived () ), array (
				'class' => 'select2',
				'style' => "min-width:170px;",
				'required' => false,
				'data-placeholder' => _ ( '-Select status actived-' ) ) ) );

		$this->formFilter->setValidator ( 'app_mobile_actived', new sfValidatorPass () );
		
		$this->formFilter->setWidget ( 'type_statistic', new sfWidgetFormChoice ( array (
				'choices' => array (
						'0' => _ ( 'Type with relative' ),
						'1' => _ ( 'Type with student' )) ), array (
								'class' => 'select2',
								'style' => "min-width:170px;",
								'required' => false,
								'data-placeholder' => _ ( '-Select type statistic-' ) ) ) );
		
		$this->formFilter->setValidator ( 'type_statistic', new sfValidatorPass () );
		
		$this->formFilter->setDefault ( 'type_statistic', $this->type_statistic );
		
		$this->formFilter->setDefault ( 'app_mobile_actived', $this->app_mobile_actived );

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'member_statistic_filter[%s]' );

		/*
		 * $this->form = $this->configuration->getForm();
		 * $this->formFilter = new sfFormFilter();
		 * $this->filter_member_statistic = array();
		 * $filter_member_statistic = $request->getParameter('member_statistic_filter');
		 * $school_year_id = null;
		 * $ps_customer_id = null;
		 * $ps_class_id = null;
		 * $keywords = null;
		 * if($request->isMethod('POST')){
		 * $value_member_filter = $request->getParameter('member_statistic_filter');
		 * $school_year_id = $value_member_filter['school_year_id'];
		 * $this->school_year_id = $school_year_id;
		 * $ps_customer_id = $value_member_filter['ps_customer_id'];
		 * $this->ps_customer_id = $ps_customer_id;
		 * $ps_class_id = $value_member_filter['ps_class_id'];
		 * $this->ps_class_id = $ps_class_id;
		 * $keywords = $value_member_filter['keywords'];
		 * $this->keywords = $keywords;
		 * if($ps_class_id > 0) {
		 * $this->filter_member_statistic = Doctrine::getTable('Relative')->getRelativeActiveAccount($ps_customer_id, $ps_class_id, $keywords);
		 * $relative_id = array_column($this->filter_member_statistic->toArray(), 'id');
		 * $student = Doctrine::getTable('RelativeStudent')->sqlGetStudentByRelativeId($relative_id)->fetchArray();
		 * $this->students = array();
		 * foreach ($student as $student){
		 * $this->students[$student['relative_id']][] = "{$student['student_name']} ({$student['mc_name']})";
		 * }
		 * } else {
		 * $this->class_list = Doctrine::getTable('MyClass')->getClassInfoByCustomerId($ps_customer_id,$school_year_id)->toArray();
		 * $this->filter_member_statistic = Doctrine::getTable('Relative')->getRelativeActiveAccount($ps_customer_id, array_column($this->class_list, 'mc_id'), $keywords);
		 * // $relative_id = array_column($this->filter_member_statistic->toArray(), 'id');
		 * // $student = Doctrine::getTable('RelativeStudent')->sqlGetStudentByRelativeId($relative_id)->fetchArray();
		 * // $this->students = array();
		 * // foreach ($student as $student){
		 * // $this->students[$student['relative_id']][] = "{$student['student_name']} ({$student['mc_name']})";
		 * // }
		 * }
		 * }
		 * if($filter_member_statistic){
		 * $this->school_year_id = isset($filter_member_statistic['school_year_id']) ? $filter_member_statistic['school_year_id'] : 0;
		 * $this->ps_customer_id = isset($filter_member_statistic['ps_customer_id']) ? $filter_member_statistic['ps_customer_id'] : 0;
		 * $this->ps_class_id = isset($filter_member_statistic['ps_class_id']) ? $filter_member_statistic['ps_class_id'] : 0;
		 * $this->keywords = isset($filter_member_statistic['keywords']) ? $filter_member_statistic['keywords'] : '';
		 * }
		 * $this->formFilter->setWidget('school_year_id', new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'PsSchoolYear',
		 * 'query' => Doctrine::getTable('PsSchoolYear')->getPsSchoolYearsDefault())
		 * , array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:150px;",
		 * 'data-placeholder' =>sfContext::getInstance ()->getI18n ()->__('-Select school year-'),
		 * 'required' => true
		 * )));
		 * $this->formFilter->setValidator('school_year_id', new sfValidatorDoctrineChoice(array(
		 * 'required' => true,
		 * 'column' => 'id',
		 * 'model' => 'PsSchoolYear'
		 * )));
		 * if(myUser::credentialPsCustomers('PS_STUDENT_RELATIVE_FILTER_SCHOOL')) {
		 * $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsCustomer',
		 * 'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers (PreSchool::ACTIVE),
		 * 'add_empty' => '-Select customer-'
		 * ), array (
		 * 'class' => 'select2',
		 * 'required' => 'required',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select customer-' )
		 * ) ) );
		 * } else {
		 * $ps_customer_id = myUser::getPscustomerID();
		 * $this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsCustomer',
		 * 'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers (PreSchool::ACTIVE, $ps_customer_id),
		 * 'add_empty' => '-Select customer-'
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'required' => 'required',
		 * 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select customer-' )
		 * ) ) );
		 * }
		 * $this->formFilter->setValidator ('ps_customer_id', new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsCustomer',
		 * 'required' => 'required'
		 * ) ) );
		 * if ($ps_customer_id > 0) {
		 * $this->class = Doctrine::getTable('MyClass')->getChoisGroupMyClassByCustomerAndYear($ps_customer_id, $school_year_id, null, PreSchool::ACTIVE);
		 * $this->formFilter->setWidget('ps_class_id', new sfWidgetFormSelect(array(
		 * 'choices' => array(
		 * '' => '-Select class-'
		 * ) + $this->class
		 * ), array(
		 * 'class' => 'select2',
		 * 'required' => false,
		 * 'style' => "min-width:250px;"
		 * )));
		 * $this->formFilter->setValidator ('ps_class_id', new sfValidatorInteger(array(
		 * 'required' => false
		 * )));
		 * } else {
		 * $this->formFilter->setWidget('ps_class_id', new sfWidgetFormSelect(array(
		 * 'choices' => array(
		 * '' => '-Select class-'
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'required' => false,
		 * 'style' => "min-width:250px;"
		 * )));
		 * $this->formFilter->setValidator ('ps_class_id', new sfValidatorInteger(array(
		 * 'required' => false
		 * )));
		 * }
		 * $this->formFilter->setWidget('keywords', new sfWidgetFormInputText (array(
		 * ), array(
		 * 'class' => 'form-control',
		 * 'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords' )
		 * )));
		 * $this->formFilter->setValidator ('keywords', new sfValidatorString ( array (
		 * 'required' => false
		 * ) ) );
		 * if($ps_customer_id ==''){
		 * $ps_customer_id = myUser::getPscustomerID();
		 * }
		 * $this->formFilter->setDefault ( 'school_year_id', $school_year_id );
		 * $this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		 * $this->formFilter->setDefault ( 'ps_class_id', $ps_class_id );
		 * $this->formFilter->setDefault ( 'keywords', $keywords );
		 * $this->formFilter->getWidgetSchema ()->setNameFormat ( 'member_statistic_filter[%s]' );
		 */
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {

			$check_new = $notice = $form->getObject ()->isNew ();

			$notice = $check_new ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$relative = $form->save ();

				if ($relative->getEmail () != '') {
					// Insert or Update email to ps_emails
					$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $relative->getId (), PreSchool::USER_TYPE_RELATIVE );

					if (! $ps_email) {

						$ps_email = new PsEmails ();

						$ps_email->setPsEmail ( $relative->getEmail () );
						$ps_email->setObjId ( $relative->getId () );
						$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
						$ps_email->save ();
					} else {
						$ps_email->setPsEmail ( $relative->getEmail () );
						$ps_email->save ();
					}
				} else {
					$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $relative->getId (), PreSchool::USER_TYPE_RELATIVE );

					if ($ps_email)
						$ps_email->delete ();
				}

				if (! $check_new) {
					// update firstname lastname email to sf_guard_user
					$ps_user = Doctrine::getTable ( 'sfGuardUser' )->findOneByMemberId ( $relative->getId (), PreSchool::USER_TYPE_RELATIVE );

					if ($ps_user) {
						$ps_user->setFirstName ( $relative->getFirstName () );
						$ps_user->setLastName ( $relative->getLastName () );
						$ps_user->setEmailAddress ( $relative->getEmail () );
						$ps_user->save ();
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
					'object' => $relative ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {
					$this->redirect ( '@ps_relatives_new?customer_id=' . $relative->getPsCustomerId () );
				} else {
					$this->redirect ( '@ps_relatives_new' );
				}
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_relatives_edit',
						'sf_subject' => $relative ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->relative = $this->form->getObject ();

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {

			$ps_customer_id = $request->getParameter ( 'customer_id' );

			if ($ps_customer_id > 0) {

				$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

				$this->forward404Unless ( $psCustomer, sprintf ( 'Object does not exist .' ) );

				$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

				$this->form->getObject ()
					->setPsCustomerId ( $ps_customer_id );

				$this->form = $this->configuration->getForm ( $this->form->getObject () );
			}
		}
	}

	public function executeEdit(sfWebRequest $request) {

		$this->relative = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->relative, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->relative );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->relative = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->relative, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->relative );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$relative = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $relative, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $relative ) ) );

		$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $relative->getId (), PreSchool::USER_TYPE_RELATIVE );

		$ps_user = Doctrine::getTable ( 'sfGuardUser' )->findOneByMemberId ( $relative->getId (), PreSchool::USER_TYPE_RELATIVE );

		$id = $ps_user->id;

		$conn = sfContext::getInstance ()->getDatabaseManager ()->getDatabase ( 'doctrine' )->getDoctrineConnection ();

		try {

			$conn->beginTransaction ();

			// Lay danh sách người than nay trong table RelativeStudent
			// $list = $relative->getRelativeStudent ();

			$list_RelativeStudent = Doctrine::getTable ( 'Relative' )->getRelativeByStudent ( $relative->getId () );

			/*
			 * if ($relative->delete ()) {
			 * if ($ps_email)
			 * $ps_email->delete ();
			 * if ($ps_user) {
			 * $ps_user->delete ();
			 * }
			 * foreach ( $list_RelativeStudent as $record ) {
			 * $record->delete ();
			 * }
			 * $this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
			 * }
			 */

			$relative->setDeletedAt ( date ( 'Y-m-d H:i:s' ) );
			$relative->setUpdatedAt ( date ( 'Y-m-d H:i:s' ) );
			$relative->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
			$relative->save ();

			if ($ps_user) {
				$ps_user->setIsActive ( PreSchool::CUSTOMER_LOCK );
				$ps_user->setApiToken ( NULL );
				$ps_user->setNotificationToken ( NULL );
				$ps_user->setUpdatedAt ( date ( 'Y-m-d H:i:s' ) );
				$ps_user->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
				$ps_user->save ();
			}

			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );

			$conn->commit ();
		} catch ( Doctrine_Validator_Exception $e ) {

			$conn->rollback ();

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

		$this->redirect ( '@ps_relatives' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'Relative' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$list = $records->execute ();

		try {

			foreach ( $list as $record ) {

				// Check du lieu rang buoc

				// Nguoi than - Hoc sinh
				$relative_students = Doctrine::getTable ( 'Relative' )->getRelativeByStudent ( $record->getId () );

				// Dia chi email
				$ps_email = Doctrine::getTable ( 'PsEmails' )->findOneByObj ( $record->getId (), PreSchool::USER_TYPE_RELATIVE );

				// Tai khoan
				$ps_user = Doctrine::getTable ( 'sfGuardUser' )->findOneByMemberId ( $record->getId (), PreSchool::USER_TYPE_RELATIVE );

				// Lich su kich hoat su dung APP KidsSchool trong ps_mobile_apps

				/*
				 * if ($record->delete ()) {
				 * if ($ps_email)
				 * $ps_email->delete ();
				 * if ($ps_user) {
				 * $ps_user->delete ();
				 * }
				 * foreach ( $relative_students as $relative_student ) {
				 * $relative_student->delete ();
				 * }
				 * $this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
				 * }
				 */

				$record->setDeletedAt ( date ( 'Y-m-d H:i:s' ) );
				$record->setUpdatedAt ( date ( 'Y-m-d H:i:s' ) );
				$record->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );
				$record->save ();

				if ($ps_user) {
					$ps_user->setIsActive ( PreSchool::CUSTOMER_LOCK );
					$ps_user->setApiToken ( NULL );
					$ps_user->setNotificationToken ( NULL );
					$ps_user->setUpdatedAt ( date ( 'Y-m-d H:i:s' ) );
					$ps_user->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
						->getGuardUser ()
						->getId () );
					$ps_user->save ();
				}

				$this->getUser ()
					->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
			}
		} catch ( Doctrine_Validator_Exception $e ) {

			$conn->rollback ();

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

		$this->redirect ( '@ps_relatives' );
	}

	public function executeDetail(sfWebRequest $request) {

		$realtive_id = $request->getParameter ( 'id' );

		if ($realtive_id <= 0) {
			$this->forward404Unless ( $realtive_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->_relative = Doctrine::getTable ( 'Relative' )->getRelativeById ( $realtive_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->_relative, 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $realtive_id ) );

		$this->students = Doctrine::getTable ( 'RelativeStudent' )->findStudentsByRelativeId ( $realtive_id, $this->_relative->getPsCustomerId () );
	}

	public function executeExportRelativeAccountStatistic(sfWebRequest $request) {

		$schoolyear_id = $request->getParameter ( 'y_id' );

		$custmer_id = $request->getParameter ( 'c_id' );

		$class_id = $request->getParameter ( 'class_id' ) ? $request->getParameter ( 'class_id' ) : 0;

		$keywords = $request->getParameter ( 'keywords' );

		$param = array ();
		$param ['schoolyear_id'] = $schoolyear_id;
		$param ['custmer_id'] = $custmer_id;
		$param ['class_id'] = $class_id;
		$param ['keywords'] = $keywords;

		$this->reportHelper ( $param );

		$this->reditect ( '@ps_relative_statistic' );
	}

	protected function reportHelper($param) {

		$exportFile = new exportRelativeStatisticHelper ( $this );

		$file_template_pb = 'tk_relative_account_00001.xls';

		$path_template_file = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		// $my_customer = Doctrine::getTable('PsCustomer')->getCustomerById($param['custmer_id']);
		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $param ['schoolyear_id'] )
			->toArray ();

		$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $param ['class_id'] );

		$exportFile->setCustomerInfoExportAccountStatistic ( $school_year, $school_info );

		$relative_account = Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount ( $param ['custmer_id'], $param ['class_id'], $param ['keywords'] );

		$relative_id = array_column ( $relative_account->toArray (), 'id' );

		$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
			->fetchArray ();

		$students = array ();
		foreach ( $student as $student ) {
			$students [$student ['relative_id']] [] = "{$student['student_name']} ({$student['mc_name']})";
		}

		$exportFile->setDataExportRelativeAccounts ( $relative_account, $students );

		$exportFile->saveAsFile ( "Thong_Ke_Tai_Khoan_Phu_Huynh" . date ( 'YmdHi' ) . ".xls" );

		$this->reditect ( '@ps_relative_statistic' );
	}
}
