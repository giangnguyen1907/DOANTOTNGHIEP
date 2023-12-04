<?php

require_once dirname(__FILE__).'/../lib/psSemesterGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/psSemesterGeneratorHelper.class.php';

/**
 * psSemester actions.
 *
 * @package    KidsSchool.vn
 * @subpackage psSemester
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSemesterActions extends autoPsSemesterActions
{
	public function executeConfig(sfWebRequest $request) {
		
		$user_id = myUser::getUserId ();
		
		$this->formFilter = new sfFormFilter ();
		
		$this->list_config = array ();
		
		$delay_filter = $request->getParameter ( 'delay_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $delay_filter;
			
			$this->ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$this->ps_school_year_id = $value_student_filter ['ps_school_year_id'];
		
		}elseif($request->getParameter ('w_id') > 0){
			
			$this->ps_customer_id = $request->getParameter ('c_id');
			
			$this->ps_workplace_id = $request->getParameter ('w_id');
			
			$this->ps_school_year_id = $request->getParameter ('y_id');
			
		}else{
			
			$member_id = myUser::getUser ()->getMemberId ();
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
			
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		} 
		
		if ($this->ps_school_year_id == '') {
			$this->schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$this->schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $this->ps_school_year_id );
		}
		//echo 'AAAAAA'.$this->ps_school_year_id;die;
		$yearsDefaultStart = date ( "Y-m", strtotime ( $this->schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $this->schoolYearsDefault->getToDate () ) );
		
		$this->ps_month = PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd );
		
		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();
		}
		
		$list_config = Doctrine::getTable ( 'PsSemester' )->getSemesterConfig ( $this->ps_school_year_id, $this->ps_customer_id, $this->ps_workplace_id );
		
		if ($list_config) {
			$this->list_config = $list_config;
		} else {
			$this->list_config = false;
		}
		
		if ($delay_filter) {
			
			$this->ps_workplace_id = isset ( $delay_filter ['ps_workplace_id'] ) ? $delay_filter ['ps_workplace_id'] : 0;
			
			$this->ps_school_year_id = isset ( $delay_filter ['ps_school_year_id'] ) ? $delay_filter ['ps_school_year_id'] : 0;
			
			if ($this->ps_workplace_id > 0) {
				
				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' )) {
			
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->getWidgetSchema ()
		->setNameFormat ( 'delay_filter[%s]' );
	}
	
	public function executeSaveConfig(sfWebRequest $request) {
		
		$ps_semester_config = $request->getParameter ( 'ps_semester_config' );
		//print_r($ps_semester_config);die;
		$ps_customer_id = $ps_semester_config ['ps_customer_id'];
		$ps_workplace_id = $ps_semester_config ['ps_workplace_id'];
		$ps_school_year_id = $ps_semester_config ['ps_school_year_id'];
		$end_semester_one = $ps_semester_config ['end_semester_one'];
		$end_semester_two = $ps_semester_config ['end_semester_two'];
		
		// Quyen thiet lap hoc ky lay ma quyen tao phong hoc
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_ROOMS_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}
		}
		
		$error_semester = 0;
		$user_id = myUser::getUserId ();
		
		if(strtotime('01-'.$end_semester_two) > strtotime('01-'.$end_semester_one)){
			
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );
			// Bat dau nam hoc
			$from_date = $schoolYearsDefault->getFromDate();
			// bat dau hoc ky 2
			$start_semester_two = date('m-Y',strtotime(date("Y-m-d", strtotime('01-'.$end_semester_one)) . " +1 month"));
			// Hoc ky 1
			$semester_one = date('m-Y',strtotime($from_date)).';'.$end_semester_one;
			// Hoc ky 2
			$semester_two = $start_semester_two.';'.$end_semester_two;
			
			$check_config = Doctrine::getTable ( 'PsSemester' )->getSemesterConfig ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id );
			
			if(!$check_config){
				$ps_semester = new PsSemester();
				$ps_semester -> setPsCustomerId($ps_customer_id);
				$ps_semester -> setSchoolYearId($ps_school_year_id);
				$ps_semester -> setPsWorkplaceId($ps_workplace_id);
				$ps_semester -> setSemesterOne($semester_one);
				$ps_semester -> setSemesterTwo($semester_two);
				$ps_semester -> setUserCreatedId($user_id);
				$ps_semester -> setUserUpdatedId($user_id);
				
				$ps_semester -> save();
			}else{
				$check_config -> setSemesterOne($semester_one);
				$check_config -> setSemesterTwo($semester_two);
				$check_config -> setUserUpdatedId($user_id);
				
				$check_config -> save();
			}
			
		}else{
			$error_semester = 1;
		}
		if($error_semester == 1){
			$message = $this->getContext () ->getI18N () ->__ ( 'Error config semerter. End semester two.' );
			$this->getUser () ->setFlash ( 'error', $message );
		}else{
			$message = $this->getContext () ->getI18N () ->__ ( 'Config semerter was saved successfully. You can add another one below.' );
			$this->getUser () ->setFlash ( 'notice', $message );
		}
		
		$this->redirect ( '@ps_semester_config?c_id='.$ps_customer_id.'&w_id='.$ps_workplace_id.'&y_id='.$ps_school_year_id );
	}
	
}
