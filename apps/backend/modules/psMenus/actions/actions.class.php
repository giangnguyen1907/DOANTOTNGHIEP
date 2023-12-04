<?php
require_once dirname ( __FILE__ ) . '/../lib/psMenusGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMenusGeneratorHelper.class.php';

/**
 * psMenus actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psMenus
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMenusActions extends autoPsMenusActions {

	/**
	 * Kiem tra menu da ton tai chua.
	 *
	 * @param
	 *        	string
	 * @return json
	 */
	public function executeCheckMenu(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );

		$ps_object_group_id = $request->getParameter ( 'ps_object_group_id' );

		$ps_year = $request->getParameter ( 'ps_year' );

		$ps_week = $request->getParameter ( 'ps_week' );

		// $ps_object_group_id = $request->getParameter ( 'ps_object_group_id_destination' );

		// $ps_week = $request->getParameter ( 'week_destination' );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		// $message = sfContext::getInstance ()->getI18n ()->__ ( 'Destination menu has data. Do you want to continue?' ).' co so:'.$ps_workplace_id.'-Nhóm:'.$ps_object_group_id. ' - Nam:'.$ps_year.'-Tuan:'.$ps_week;

		$message = sfContext::getInstance ()->getI18n ()
			->__ ( 'Destination menu has data. Do you want to continue?' );

		/*
		 * if (myUser::isAdministrator()):
		 * $message .= 'Truong:'.$ps_customer_id.'<br/>';
		 * $message .= 'Co so:'.$ps_workplace_id.'<br/>';
		 * $message .= 'Nhom trẻ:'.$ps_object_group_id.'<br/>';
		 * $message .= 'Năm:'.$ps_year.'<br/>';
		 * $message .= 'Tuần:'.$ps_week.'<br/>';
		 * $message .= 'form_week_start:'.$form_week_start.'<br/>';
		 * $message .= 'form_week_end'.$form_week_end;
		 * endif;
		 */

		echo json_encode ( array (
				'valid' => true,
				'message' => $message,
				'available' => ! Doctrine::getTable ( 'PsMenus' )->checkListMenuWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_object_group_id, $ps_workplace_id ) ) );

		exit ( 0 );
	}

	// Ham xu ly Form Filter cua action new
	protected function processFilterMenusWeek(sfWebRequest $request, $ps_menus = null) {

		$formFilter = new sfFormFilter ();

		if (myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' )) {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px; width:auto;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
		}

		$formFilter->setWidget ( 'ps_object_group_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'add_empty' => _ ( '-Select object groups-' ) ), array (
				'class' => 'form-control',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select object groups-' ),
				'required' => true ) ) );

		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$formFilter->setWidget ( 'ps_number_week', new sfWidgetFormInputHidden () );
		$formFilter->setWidget ( 'ps_current_year', new sfWidgetFormInputHidden () );

		if ($ps_menus) {

			$ps_customer_id = $ps_menus->getPsCustomerId ();

			$ps_workplace_id = $ps_menus->getPsWorkplaceId ();

			$ps_object_group_id = $ps_menus->getPsObjectGroupId ();

			$int_date_at = strtotime ( $ps_menus->getDateAt () );

			$ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d', $int_date_at ) );

			$ps_year = date ( 'Y', $int_date_at );
			
			if($ps_week == '01' && date('m',$int_date_at) == 12){
				$ps_year = $ps_year + 1;
			}
			
		} else {

			$value_student_filter = $this->getKeepValueForms ();

			$ps_customer_id = isset ( $value_student_filter ['ps_customer_id'] ) ? $value_student_filter ['ps_customer_id'] : myUser::getPscustomerID ();
			$ps_workplace_id = isset ( $value_student_filter ['ps_workplace_id'] ) ? $value_student_filter ['ps_workplace_id'] : null;
			$ps_object_group_id = isset ( $value_student_filter ['ps_object_group_id'] ) ? $value_student_filter ['ps_object_group_id'] : null;
			$ps_week = isset ( $value_student_filter ['ps_week'] ) ? $value_student_filter ['ps_week'] : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
			$ps_year = isset ( $value_student_filter ['ps_year'] ) ? $value_student_filter ['ps_year'] : date ( 'Y' );
		}

		if ($ps_customer_id > 0) {
			
			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px; width:auto;",
					'data-placeholder' => _ ( '-Select workplace-' ),
					'required' => false ) ) );

			$formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'required' => false ) ) );
		} else {
			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), 
			array (
					'class' => 'select2' ) ) );

			$formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass ( array (
					'required' => false ) ) );
		}

		/*
		 * if ($request->isMethod('post')) { // Handle the form submission $value_student_filter = $request->getParameter('menus_filter'); $ps_customer_id = $value_student_filter['ps_customer_id']; $ps_workplace_id = $value_student_filter['ps_workplace_id']; $ps_object_group_id = $value_student_filter['ps_object_group_id']; $ps_week = $value_student_filter['ps_week']; $ps_year = $value_student_filter['ps_year']; }
		 */

		// Lay thong tin tuan cua nam
		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:300px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) ) );

		$formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );

		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();

		if (isset ( $weeks [$ps_week - 1] )) {

			$weeks_form = $weeks [$ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];

			$form_week_end 	 = $weeks_form ['week_end'];

			$form_week_list   = $weeks_form ['week_list'];
		}

		$formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );
		$formFilter->setDefault ( 'ps_week', $ps_week );
		$formFilter->setDefault ( 'ps_year', $ps_year );
		$formFilter->setDefault ( 'ps_current_year', $ps_year );

		$formFilter->getWidgetSchema ()
			->setNameFormat ( 'menus_filter[%s]' );

		return array (
				'formFilter' => $formFilter,
				'form_week_start' => $form_week_start,
				'form_week_end' => $form_week_end,
				'form_week_list' => $form_week_list );
	}

	// Lay danh sach tuan trong nam
	public function executePsMenusWeeksYear(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_year = $request->getParameter ( 'ps_year' );

			$app_begin_year = sfConfig::get ( 'app_begin_year' );

			$years = range ( date ( 'Y' ) + 1, $app_begin_year );

			if (($ps_year > date ( 'Y' ) + 1))
				$ps_year = date ( 'Y' ) + 1;
			elseif ($ps_year < $app_begin_year)
				$ps_year = $app_begin_year;

			$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

			$option_weeks = PsDateTime::getOptionsWeeks ( $weeks );

			return $this->renderPartial ( 'psMenus/option_weeks', array (
					'option_weeks' => $option_weeks ) );
		} else {
			exit ( 0 );
		}
	}

	// Lay thuc don cua tuan
	public function executePsMenusWeek(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			// $this->form = $this->configuration->getForm();

			$this->formFilter = new sfFormFilter ();

			$value_student_filter = $request->getParameter ( 'menus_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			// Cần check quyền của $ps_customer_id

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$ps_object_group_id = $value_student_filter ['ps_object_group_id'];
			$ps_week = $value_student_filter ['ps_week'];
			$ps_year = $value_student_filter ['ps_year'];

			// Lay thong tin tuan cua nam
			$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

			$this->formFilter->setWidget ( 'ps_number_week', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );
			$this->formFilter->setWidget ( 'ps_current_year', new sfWidgetFormInputHidden () );

			// Get week in form
			$this->week_start = null;
			$this->week_end = null;
			$this->week_list = array ();

			if (isset ( $weeks [$ps_week - 1] )) {

				$weeks_form = $weeks [$ps_week - 1];

				$this->week_start = $weeks_form ['week_start'];
				$this->week_end = $weeks_form ['week_end'];
				$this->week_list = $weeks_form ['week_list'];
			}

			$this->formFilter->setDefault ( 'ps_current_year', $ps_year );

			$this->formFilter->getWidgetSchema ()
				->setNameFormat ( 'menus_filter[%s]' );

			$this->list_meal = array ();

			if ($ps_customer_id > 0)
				$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
						'ps_customer_id' => $ps_customer_id,
						'ps_workplace_id' => $ps_workplace_id,
						'is_activated' => PreSchool::ACTIVE ) )
					->execute ();

			$this->list_menu = array ();

			if ($ps_customer_id > 0 && $this->week_start != null && $ps_object_group_id > 0)
				$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $this->week_start, $this->week_end, $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

			$this->setKeepValueForms ( $value_student_filter );

			return $this->renderPartial ( 'psMenus/ajax_table_menu', array (
					'list_meal' => $this->list_meal,
					'list_menu' => $this->list_menu,
					'week_list' => $this->week_list,
					'width_th' => (100 / (count ( $this->week_list ) + 1)),
					'formFilter' => $this->formFilter ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$filterMenusWeek = $this->processFilterMenusWeek ( $request );

		$this->formFilter = $filterMenusWeek ['formFilter'];

		$ps_meal_id = '';
		
		if ($this->form->getDefault ( 'ps_customer_id' ) > 0)
			$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );
		else
			$ps_customer_id = $this->formFilter->getDefault ( 'ps_customer_id' );

		if ($this->form->getDefault ( 'ps_workplace_id' ) > 0)
			$ps_workplace_id = $this->form->getDefault ( 'ps_workplace_id' );
		else
			$ps_workplace_id = $this->formFilter->getDefault ( 'ps_workplace_id' );

		if ($this->form->getDefault ( 'ps_meal_id' ) > 0)
			$ps_meal_id = $this->form->getDefault ( 'ps_meal_id' );

		if ($this->form->getDefault ( 'ps_object_group_id' ) > 0)
			$ps_object_group_id = $this->form->getDefault ( 'ps_object_group_id' );
		else
			$ps_object_group_id = $this->formFilter->getDefault ( 'ps_object_group_id' );

		$this->ps_menus = $this->form->getObject ();

		$this->form->getObject ()
			->setPsCustomerId ( $ps_customer_id );
		$this->form->getObject ()
			->setPsWorkplaceId ( $ps_workplace_id );
		$this->form->getObject ()
			->getPsMealId ( $ps_meal_id );
		$this->form->getObject ()
			->setPsObjectGroupId ( $ps_object_group_id );

		$this->form = $this->configuration->getForm ( $this->form->getObject () );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );

		$this->week_list = $filterMenusWeek ['form_week_list'];

		$this->list_meal = array ();

		if ($ps_customer_id > 0)
			$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) )
				->execute ();

		$this->list_menu = array ();

		if ($ps_customer_id > 0 && $filterMenusWeek ['form_week_start'] != null && $ps_object_group_id > 0)
			$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $filterMenusWeek ['form_week_start'], $filterMenusWeek ['form_week_end'], $ps_customer_id, $ps_object_group_id, $ps_workplace_id );
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$filterMenusWeek = $this->processFilterMenusWeek ( $request );

		$this->formFilter = $filterMenusWeek ['formFilter'];

		if ($this->form->getDefault ( 'ps_customer_id' ) > 0)
			$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );
		else
			$ps_customer_id = $this->formFilter->getDefault ( 'ps_customer_id' );

		if ($this->form->getDefault ( 'ps_workplace_id' ) > 0)
			$ps_workplace_id = $this->form->getDefault ( 'ps_workplace_id' );
		else
			$ps_workplace_id = $this->formFilter->getDefault ( 'ps_workplace_id' );

		if ($this->form->getDefault ( 'ps_meal_id' ) > 0)
			$ps_meal_id = $this->form->getDefault ( 'ps_meal_id' );

		if ($this->form->getDefault ( 'ps_object_group_id' ) > 0)
			$ps_object_group_id = $this->form->getDefault ( 'ps_object_group_id' );
		else
			$ps_object_group_id = $this->formFilter->getDefault ( 'ps_object_group_id' );

		$this->ps_menus = $this->form->getObject ();

		$this->form->getObject ()
			->setPsCustomerId ( $ps_customer_id );
		$this->form->getObject ()
			->setPsWorkplaceId ( $ps_workplace_id );
		$this->form->getObject ()
			->getPsMealId ( $ps_meal_id );
		$this->form->getObject ()
			->setPsObjectGroupId ( $ps_object_group_id );

		$this->form = $this->configuration->getForm ( $this->form->getObject () );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );

		$this->week_list = $filterMenusWeek ['form_week_list'];

		$this->list_meal = array ();

		if ($ps_customer_id > 0)
			$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) )
				->execute ();

		$this->list_menu = array ();
		if ($ps_customer_id > 0 && $filterMenusWeek ['form_week_start'] != null && $ps_object_group_id > 0)
			$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $filterMenusWeek ['form_week_start'], $filterMenusWeek ['form_week_end'], $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_menus = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_menus, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()->getObject ()->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_menus );

		$date_at = $this->ps_menus->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );
		
		if($ps_week == '01' && date('m',strtotime($date_at)) == 12){
			$ps_year = $ps_year + 1;
		}

		$request->setParameter ( 'ps_year', $ps_year );

		$filterMenusWeek = $this->processFilterMenusWeek ( $request, $this->ps_menus );

		$this->formFilter = $filterMenusWeek ['formFilter'];

		$ps_customer_id = $this->ps_menus->getPsCustomerId ();
		$ps_workplace_id = $this->ps_menus->getPsWorkplaceId ();

		$ps_object_group_id = $this->ps_menus->getPsObjectGroupId ();

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setDefault ( 'ps_year', $ps_year );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		$this->week_list = $weeks_form ['week_list'];

		$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE ) )
			->execute ();

		$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

		$this->setTemplate ( 'new' );
	}

	public function executeDetail(sfWebRequest $request) {

		$this->ps_menus = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_menus, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_menus );

		$date_at = $this->ps_menus->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		$request->setParameter ( 'ps_year', $ps_year );

		$filterMenusWeek = $this->processFilterMenusWeek ( $request, $this->ps_menus );

		$this->formFilter = $filterMenusWeek ['formFilter'];

		$ps_customer_id = $this->ps_menus->getPsCustomerId ();

		$ps_workplace_id = $this->ps_menus->getPsWorkplaceId ();

		$ps_object_group_id = $this->ps_menus->getPsObjectGroupId ();

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setDefault ( 'ps_year', $ps_year );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		$this->week_list = $weeks_form ['week_list'];

		$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE ) )
			->execute ();

		$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

		$this->setTemplate ( 'detail' );
	}

	// Thuc hien copy thuc don
	public function executePsMenusCopy(sfWebRequest $request) {

		// Form gốc
		$form_menu = $request->getParameter ( 'form' );

		$ps_customer_id = $form_menu ['ps_customer_id'];

		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ) && $ps_customer_id != myUser::getPscustomerID ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$ps_workplace_id = $form_menu ['ps_workplace_id'];

		$ps_object_group_id = $form_menu ['ps_object_group_id'];

		$ps_year_source = $form_menu ['ps_year_source'];

		$ps_week_source = $form_menu ['week_source'];

		// Đích đến
		$ps_year_destination = $form_menu ['ps_year_destination'];
		$ps_week_destination = $form_menu ['week_destination'];
		
		if($ps_workplace_id > 0){
			$destination_ps_workplace_id = $ps_workplace_id; // $form_menu ['ps_workplace_id_destination'];
		}else{
			$destination_ps_workplace_id = 0;
		}
		
		$destination_ps_object_group_id = $form_menu ['ps_object_group_id_destination'];
		/*
		if ($destination_ps_workplace_id <= 0 || $destination_ps_object_group_id <= 0) {
			$this->redirect ( '@ps_menus_new' );
		}
		*/
		if ($destination_ps_object_group_id <= 0) {
			$this->getUser ()->setFlash ( 'error', 'Menus was copied fail. You cant select object group id' );
			$this->redirect ( '@ps_menus_new' );
		}
		// Lay thong tin tuan cua nam - nguon
		$weeks_source = PsDateTime::getWeeksOfYear ( $ps_year_source );

		$weeks_source_form = $weeks_source [$ps_week_source - 1];

		// Ngay bat dau cua tuan nguon
		$form_week_start_source = $weeks_source_form ['week_start'];

		$form_week_end_source = $weeks_source_form ['week_end'];

		// Lay danh sach menu tuan nguon
		$list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $form_week_start_source, $form_week_end_source, $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

		// Lay thong tin tuan cua nam - dich
		$weeks_destination = PsDateTime::getWeeksOfYear ( $ps_year_destination );
		$weeks_destination_form = $weeks_destination [$ps_week_destination - 1];

		// Ngay bat dau cua tuan dich
		$form_week_start_destination = $weeks_destination_form ['week_start'];

		// Ngay ket thuc cua tuan dich
		// $form_week_end_destination = $weeks_destination_form['week_end'];
		// Xoa ban ghi cu

		// tinh so ngay chech lech giua tuan nguon va tuan dich
		$date1 = date_create ( $form_week_start_source );
		$date2 = date_create ( $form_week_start_destination );
		$diff = date_diff ( $date1, $date2 );
		$number_day = $diff->format ( "%R%a" );

		/*
		 * print_r($form_menu);
		 * echo '<br/>destination_ps_workplace_id:'.$destination_ps_workplace_id;
		 * echo 'destination_ps_object_group_id:'.$destination_ps_object_group_id;
		 * foreach ( $list_menu as $menu ) {
		 * $date_at = strtotime ($number_day. " day", strtotime ($menu->getDateAt ()));
		 * echo date('Y-m-d', $date_at).'<br/>';
		 * }
		 * die;
		 */

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			$number_record = 0;

			foreach ( $list_menu as $menu ) {

				$date_at = strtotime ( $number_day . " day", strtotime ( $menu->getDateAt () ) );

				$new_menus = new PsMenus ();
				$new_menus->setPsMealId ( $menu->getMealId () );
				$new_menus->setPsFoodId ( $menu->getFoodId () );
				$new_menus->setPsCustomerId ( $menu->getPsCustomerId () );
				$new_menus->setPsWorkplaceId ( $destination_ps_workplace_id );
				$new_menus->setPsObjectGroupId ( $destination_ps_object_group_id );
				$new_menus->setDateAt ( date ( 'Y-m-d', $date_at ) );
				$new_menus->setNote ( $menu->getNote () );
				$new_menus->setUserCreatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );
				$new_menus->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );

				$new_menus->save ();

				if ($new_menus && $new_menus->getId ()) {
					$number_record ++;
				}
			}

			$this->getUser ()
				->setFlash ( 'notice', 'Menus was copied successfully' . ': ' . $number_record . 'dữ liệu' );

			$conn->commit ();
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$this->getUser ()
				->setFlash ( 'error', 'Menus was copied fail.' );
			$conn->rollback ();
		}

		if (myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ))
			$this->redirect ( '@ps_menus_new?ps_customer_id=' . $ps_customer_id . '&ps_workplace_id=' . $ps_workplace_id . '&ps_object_group_id=' . $ps_object_group_id . '&ps_week=' . $ps_week_destination . '&ps_year=' . $ps_year_destination );
		else
			$this->redirect ( '@ps_menus_new?ps_workplace_id=' . $ps_workplace_id . '&ps_object_group_id=' . $ps_object_group_id . '&ps_week=' . $ps_week_destination . '&ps_year=' . $ps_year_destination );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_menus = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_menus, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_menus );

		$filterMenusWeek = $this->processFilterMenusWeek ( $request );

		$this->formFilter = $filterMenusWeek ['formFilter'];

		$ps_customer_id = $this->ps_menus->getPsCustomerId ();

		$ps_workplace_id = $this->ps_menus->getPsWorkplaceId ();

		$ps_object_group_id = $this->ps_menus->getPsObjectGroupId ();

		$date_at = $this->ps_menus->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_object_group_id', $ps_object_group_id );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setDefault ( 'ps_year', $ps_year );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		$this->week_list = $weeks_form ['week_list'];

		// $this->list_meal = Doctrine::getTable('PsMeals')->getMealByCustomerId('id, title', $ps_customer_id);
		$this->list_meal = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE ) );

		$this->list_menu = Doctrine::getTable ( 'PsMenus' )->getListMenuWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_object_group_id, $ps_workplace_id );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsMenus' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$list_record = $records->execute ();

		foreach ( $list_record as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_menus' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$object = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $object, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $object->getId () ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $object ) ) );

		$ps_menus = $object;

		$ps_customer_id = $ps_menus->getPsCustomerId ();

		$ps_workplace_id = $ps_menus->getPsWorkplaceId ();

		$ps_object_group_id = $ps_menus->getPsObjectGroupId ();

		$date_at = $ps_menus->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		$keep_forms = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_object_group_id' => $ps_object_group_id,
				'ps_week' => $ps_week,
				'ps_year' => $ps_year );

		$this->setKeepValueForms ( $keep_forms );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_menus_new' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$ps_food_id = $form->getValue ( 'ps_food_id' );

				$ps_menus = $form->save ();

				$ps_menus->setPsFoodId ( $ps_food_id );
				
				$ps_menus->save ();

				$ps_customer_id = $ps_menus->getPsCustomerId ();

				$ps_workplace_id = $ps_menus->getPsWorkplaceId ();

				$ps_object_group_id = $ps_menus->getPsObjectGroupId ();

				$date_at = $ps_menus->getDateAt ();

				$ps_year = date ( "Y", strtotime ( $date_at ) );
				
				$ps_week = date ( "W", strtotime ( $date_at ) );
				
				if($ps_week == '01' && date('m',strtotime($date_at)) == 12){
					$ps_year = $ps_year + 1;
				}
				
				$keep_forms = array (
						'ps_customer_id' 		=> $ps_customer_id,
						'ps_workplace_id' 		=> $ps_workplace_id,
						'ps_object_group_id' 	=> $ps_object_group_id,
						'ps_week' => $ps_week,
						'ps_year' => $ps_year );

				$this->setKeepValueForms ( $keep_forms );
				
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
					'object' => $ps_menus ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {

				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_menus_new' );
			} else {

				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_menus_edit',
						'sf_subject' => $ps_menus ) );

				/*
				 * if (myUser::credentialPsCustomers('PS_NUTRITION_MENUS_FILTER_SCHOOL'))
				 * $this->redirect('@ps_menus_new?ps_customer_id=' . $ps_customer_id . '&ps_workplace_id='.$ps_workplace_id.'&ps_object_group_id=' . $ps_object_group_id . '&ps_week=' . $ps_week . '&ps_year=' . $ps_year);
				 * else
				 * $this->redirect('@ps_menus_new?ps_workplace_id='.$ps_workplace_id.'&ps_object_group_id=' . $ps_object_group_id . '&ps_week=' . $ps_week . '&ps_year=' . $ps_year);
				 */
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	protected function setKeepValueForms(array $forms) {

		return $this->getUser ()->setAttribute ( 'psMenus.valueForms', $forms, 'admin_module' );
	}

	protected function getKeepValueForms() {

		$filters = $this->getUser ()->getAttribute ( 'psMenus.valueForms', array (), 'admin_module' );

		return $filters;
	}
	
}
