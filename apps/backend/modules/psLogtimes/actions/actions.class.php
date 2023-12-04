<?php
use Illuminate\Support\Facades\Redirect;

require_once dirname ( __FILE__ ) . '/../lib/psLogtimesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psLogtimesGeneratorHelper.class.php';

/**
 * psLogtimes actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psLogtimes
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psLogtimesActions extends autoPsLogtimesActions {

	protected function folderRen() {

		// Lay danh sach cac ma truong hoc
		$records = Doctrine_Query::create ()->select ( '*' )
			->from ( 'PsCustomer' )
			->execute ();

		// Tao thu muc chua du lieu

		// Lay thu muc goc data_cache
		$data_cache_dir = sfConfig::get ( 'app_ps_data_cache_dir' );

		$app_ps_data_dir = sfConfig::get ( 'app_ps_data_dir' );

		if ($data_cache_dir != '') {

			foreach ( $records as $key => $record ) {
				/*
				 * $folder_name = $record->getId () . $record->getSchoolCode ();
				 * $folder_name = PsEndCode::psHash256 ( $folder_name );
				 * $record->setCacheData($folder_name);
				 * $record->save();
				 */

				/*
				 * // $folder_name = $record->getSchoolCode();
				 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_name, 0777 );
				 * $folder_data_cache = $data_cache_dir . '/' . $folder_name . '/2018';
				 * Doctrine_Lib::makeDirectories ( $folder_data_cache, 0777 );
				 * Doctrine_Lib::copyDirectory ( sfConfig::get ( 'app_ps_sch_data_cache_dir' ), $folder_data_cache );
				 * // Doctrine_Lib::copyDirectory(sfConfig::get('app_ps_data_dir').'/'.$record->getSchoolCode().'/hr/avatar', $folder_data_cache);
				 * $folder_hr = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $record->getSchoolCode () . '/hr/avatar';
				 * if (is_dir ($folder_hr)) {
				 * @ $dir = dir ($folder_hr);
				 * @ $file = $dir->read ();
				 * @ $file = $dir->read ();
				 * while ( $file = $dir->read () ) {
				 * // copy file
				 * copy ( $folder_hr.'/'. $file, $folder_data_cache.'/hr/' . $file );
				 * }
				 * }
				 * $folder_relative = sfConfig::get('app_ps_data_dir').'/'.$record->getSchoolCode().'/relative/avatar';
				 * if (is_dir ($folder_relative)) {
				 * @ $dir = dir ($folder_relative);
				 * @ $file = $dir->read ();
				 * @ $file = $dir->read ();
				 * while ( $file = $dir->read () ) {
				 * // copy file
				 * copy ( $folder_relative.'/'. $file, $folder_data_cache.'/relative/' . $file );
				 * }
				 * }
				 * $folder_profile = sfConfig::get('app_ps_data_dir').'/'.$record->getSchoolCode().'/profile/avatar';
				 * if (is_dir ($folder_profile)) {
				 * @ $dir = dir ($folder_profile);
				 * @ $file = $dir->read ();
				 * @ $file = $dir->read ();
				 * while ( $file = $dir->read () ) {
				 * // copy file
				 * copy ( $folder_profile.'/'. $file, $folder_data_cache.'/student/' . $file );
				 * }
				 * }
				 */

				$folder_school_code = $record->getSchoolCode ();

				Doctrine_Lib::makeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/2018', 0777 );

				Doctrine_Lib::copyDirectory ( $app_ps_data_dir . '/' . $folder_school_code, $app_ps_data_dir . '/' . $folder_school_code . '/2018' );

				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/camera' );
				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/hr' );
				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/profile' );
				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/receipt' );
				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/relative' );
				Doctrine_Lib::removeDirectories ( $app_ps_data_dir . '/' . $folder_school_code . '/reportcard' );
			}
		}
	}

	public function preExecute() {

		$this->configuration = new psLogtimesGeneratorConfiguration ();

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( $this->getActionName () ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.pre_execute', array (
				'configuration' => $this->configuration ) ) );

		$this->helper = new psLogtimesGeneratorHelper ();

		parent::preExecute ();
	}

	public function executeIndexXXXXXXXXXXX(sfWebRequest $request) {

		// $this->redirect ( '@ps_attendances' );

		/*
		 * $records = Doctrine_Query::create ()->select ( '*' )->from ( 'PsCustomer' )->execute ();
		 * foreach ( $records as $key => $record ) {
		 * $record->setCacheData($record->getSchoolCode());
		 * $record->save();
		 * }
		 */
		$this->filter_value = $this->getFilters ();

		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		if ($this->filter_value ['ps_class_id'] <= 0) {

			// $this->getUser()->setFlash('warning', 'Please select class to view student list', false);

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

			$this->setTemplate ( 'warning' );
		} else {

			$this->pager = $this->getPager ();

			$this->sort = $this->getSort ();

			// Lay hang so mac dinh
			if ($this->filter_value ['ps_customer_id'] > 0) {
				/*
				 * $login_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $this->filter_value ['ps_customer_id'], PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );
				 * $login_time = ($login_times->getValue () != '') ? $login_times->getValue () : $login_times->getValueDefault ();
				 * $logout_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $this->filter_value ['ps_customer_id'], PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );
				 * $logout_time = ($logout_times->getValue () != '') ? $logout_times->getValue () : $logout_times->getValueDefault ();
				 * $this->ps_constant_option = new \stdClass ();
				 * $this->ps_constant_option->login_time_default = $login_time;
				 * $this->ps_constant_option->logout_time_default = $logout_time;
				 */

				$this->ps_constant_option->login_time_default = date ( 'H:i', strtotime ( "now" ) );
				$this->ps_constant_option->logout_time_default = date ( 'H:i', strtotime ( "now" ) );
			}
		}
	}

	protected function getPager() {

		$pager = $this->configuration->getPager ( 'PsLogtimes' );
		$pager->setQuery ( $this->buildQuery () );
		$pager->setPage ( $this->getPage () );
		$pager->init ();

		return $pager;
	}

	public function executeNew2(sfWebRequest $request) {

		// Form list student
		$this->formFilter = new sfFormFilter ();

		$ps_workplace_id = null;

		$ps_customer_id = null;

		$student = $ps_school_year_id = null;

		$class_id = $request->getParameter ( 'class_id' );

		$student_id = $request->getParameter ( 'student_id' );

		$tracked_at_form = $request->getParameter ( 'tracked_at' );

		$this->filter_list_student = array ();

		$tracked_at_formFilter = $tracked_at_form ? date ( 'd-m-Y', $tracked_at_form ) : date ( 'd-m-Y' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = isset ( $value_student_filter ['ps_workplace_id'] ) ? $value_student_filter ['ps_workplace_id'] : '';

			$class_id = $value_student_filter ['class_id'];

			$student_id = $value_student_filter ['student_id'];

			$tracked_at_formFilter = $value_student_filter ['tracked_at'];

			$ps_logtimes = Doctrine::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $tracked_at_formFilter );

			if ($ps_logtimes) {

				$this->redirect ( array (
						'sf_route' => 'ps_logtimes_edit',
						'sf_subject' => $ps_logtimes ) );
			}
		}

		if ($student_id > 0) {

			$student = Doctrine::getTable ( 'Student' )->getStudentById ( $student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			if (! $class_id) {

				$student_class = $student->getMyClassByStudent ( $tracked_at_formFilter );

				if ($student_class) {

					$ps_customer_id = $student_class->getPsCustomerId ();

					$class_id = $student_class->getClassId ();

					$ps_workplace_id = $student_class->getMyClass ()
						->getPsClassRooms ()
						->getPsWorkplaceId ();
				}
			}

			$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );
		} elseif ($class_id > 0) {

			$ps_class = Doctrine::getTable ( 'MyClass' )->findOneBy ( 'id', $class_id );

			$ps_workplace_id = $ps_class->getPsClassRooms ()
				->getPsWorkplaceId ();

			$ps_customer_id = $ps_class->getPsCustomerId ();

			$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );
		}

		$this->formFilter->setWidget ( 'student_id', new sfWidgetFormInputText () );

		$this->formFilter->setValidator ( 'student_id', new sfValidatorInteger ( array (
				'required' => false ) ) );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		}

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
						'ps_customer_id' => $ps_customer_id,
						'ps_workplace_id' => $ps_workplace_id,
						'is_activated' => PreSchool::ACTIVE ) );
			}

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sqlMyClass,
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true,
					'column' => 'id' ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => myUser::getPscustomerID (),
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "width:100%;min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ),
					'required' => true ) ) );
		}

		$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true,
				'column' => 'id' ) ) );

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault () ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		$this->formFilter->getWidget ( 'tracked_at' )
			->setAttributes ( array (
				'class' => 'form-control',
				'required' => true,
				'data-fv-date-format' => 'DD-MM-YYYY' ) );

		$this->formFilter->getWidget ( 'student_id' )
			->setAttributes ( array (
				'required' => false ) );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		// Deal with the request

		// END : formFilter ----------------------------------------------------->

		$this->form = $this->configuration->getForm ();

		$this->ps_logtimes = $this->form->getObject ();

		if ($student)
			$this->ps_logtimes->setStudent ( $student );

		$this->ps_logtimes->setLoginAt ( $tracked_at_formFilter );

		$this->form = $this->configuration->getForm ( $this->ps_logtimes );

		$this->form->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->form->setDefault ( 'class_id', $class_id );

		$login_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );
		$login_time = ($login_times->getValue () != '') ? $login_times->getValue () : $login_times->getValueDefault ();
		$login_at = date ( 'H:i', strtotime ( $login_time ) );

		$this->form->setDefault ( 'login_at', $login_at );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'tracked_at', $tracked_at_formFilter );
		$this->formFilter->setDefault ( 'student_id', $student_id );
	}

	public function executeNewXXXXXXXXXXXXX(sfWebRequest $request) {

		// Form list student
		$this->formFilter = new sfFormFilter ();

		$this->filter_list_student = array ();

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );
		$class_id = $request->getParameter ( 'class_id' );

		$tracked_at_form = $request->getParameter ( 'tracked_at' );
		$tracked_at_formFilter = $tracked_at_form ? date ( 'd-m-Y', $tracked_at_form ) : date ( 'd-m-Y' );

		$student_id = $request->getParameter ( 'student_id' );

		if ($student_id > 0) {

			$student = Doctrine::getTable ( 'Student' )->getStudentById ( $student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			// Can bo sung Neu la Giao vien của lop, kiem tra hoc sinh nay co phai cua lop hay ko

			$student_class = $student->getMyClassByStudent ( $tracked_at_formFilter );

			if (count ( $student_class ) > 0) {

				$ps_customer_id = $student_class->getPsCustomerId ();

				$class_id = $student_class->getClassId ();

				$ps_workplace_id = $student_class->getMyClass ()
					->getPsClassRooms ()
					->getPsWorkplaceId ();
			}

			// $this->filter_list_student = Doctrine::getTable('StudentClass')->getStudentsByClassId($class_id, $tracked_at_formFilter);
		} else {
			$student = new Student ();

			if ($class_id) {
				$ps_class = Doctrine::getTable ( 'MyClass' )->findOneBy ( 'id', $class_id );

				$ps_workplace_id = $ps_class->getPsClassRooms ()
					->getPsWorkplaceId ();

				$ps_customer_id = $ps_class->getPsCustomerId ();
			}
		}

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault () ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = isset ( $value_student_filter ['ps_workplace_id'] ) ? $value_student_filter ['ps_workplace_id'] : '';

			$class_id = $value_student_filter ['class_id'];

			$tracked_at_formFilter = $value_student_filter ['tracked_at'];

			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		}

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false,
				'column' => 'id' ) ) );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) );
		} else {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
		}

		$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => $sqlMyClass,
				'add_empty' => _ ( '-Select class-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select class-' ) ) ) );

		$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true,
				'column' => 'id' ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			// $value_student_filter = $request->getParameter('student_filter');
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );

		// END: Form list student

		$this->form = $this->configuration->getForm ();

		$this->ps_logtimes = $this->form->getObject ();

		$this->ps_logtimes->setStudent ( $student );
		$this->ps_logtimes->setLoginAt ( $tracked_at_formFilter );

		$this->form = $this->configuration->getForm ( $this->ps_logtimes );

		$this->form->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->form->setDefault ( 'class_id', $class_id );

		$login_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );
		$login_time = ($login_times->getValue () != '') ? $login_times->getValue () : $login_times->getValueDefault ();
		$login_at = date ( 'H:i', strtotime ( $login_time ) );

		$this->form->setDefault ( 'login_at', $login_at );
	}

	public function executeCreateXXXXXXXXXXXXXX(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		// Form list student
		$this->formFilter = new sfFormFilter ();

		$this->filter_list_student = array ();

		$tracked_at_formFilter = date ( 'd-m-Y' );

		$value_student_form = $request->getParameter ( $this->form->getName () );

		$student_id = $value_student_form ['student_id'];

		$this->forward404Unless ( $student_id, sprintf ( 'Object does not exist.' ) );

		$student = Doctrine::getTable ( 'Student' )->getStudentById ( $student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$ps_customer_id = $value_student_form ['ps_customer_id'];

		$ps_workplace_id = $value_student_form ['ps_workplace_id'];

		$class_id = $value_student_form ['class_id'];

		// Ngay diem danh
		$tracked_at_formFilter = $value_student_form ['tracked_at'];

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault () ), array (
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
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		}

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false,
				'column' => 'id' ) ) );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) );
		} else {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
		}

		$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => $sqlMyClass,
				'add_empty' => _ ( '-Select class-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select class-' ) ) ) );

		$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true,
				'column' => 'id' ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		if ($request->isMethod ( 'post' ) && $request->getGetParameter ( 'student_filter' )) {
			// Handle the form submission
			// $value_student_filter = $request->getParameter('student_filter');
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->form = $this->configuration->getForm ();

		$this->ps_logtimes = $this->form->getObject ();

		$this->ps_logtimes->setStudent ( $student );

		$this->ps_logtimes->setLoginAt ( $tracked_at_formFilter );

		$this->form = $this->configuration->getForm ( $this->ps_logtimes );

		$this->form->setDefault ( 'login_at', date ( 'H:i' ) );

		$this->processForm ( $request, $this->form );

		$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );

		$this->setTemplate ( 'new' );
	}

	public function executeEditXXXXXXXXXXXXXXXX(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$this->ps_logtimes = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_logtimes );

		$student = $this->ps_logtimes->getStudent ();

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$tracked_at_formFilter = date ( 'd-m-Y', strtotime ( $this->ps_logtimes->getLoginAt () ) );

		$current_date = date ( "Ymd" );

		$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at_formFilter ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai

		if (! $check_current_date && $this->getUser ()
			->hasCredential ( array (
				'PS_STUDENT_ATTENDANCE_TEACHER' ) ) && ! myUser::isAdministrator ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'You can not edit or delete after attendance 1 day.' );

			$this->redirect ( '@ps_logtimes' );
		}

		$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );

		$class_id = $this->form->getDefault ( 'class_id' );

		$ps_workplace_id = $this->form->getDefault ( 'ps_workplace_id' );

		$login_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );

		$login_time = ($login_times->getValue () != '') ? $login_times->getValue () : $login_times->getValueDefault ();

		$logout_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );

		$logout_time = ($logout_times->getValue () != '') ? $logout_times->getValue () : $logout_times->getValueDefault ();

		$login_at = $this->ps_logtimes->getLoginAt () ? date ( 'H:i', strtotime ( $this->ps_logtimes->getLoginAt () ) ) : $login_time;

		$logout_at = $this->ps_logtimes->getLogoutAt () ? date ( 'H:i', strtotime ( $this->ps_logtimes->getLogoutAt () ) ) : $logout_time;

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
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

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		}

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false,
				'column' => 'id' ) ) );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) );
		} else {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
		}

		$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => $sqlMyClass,
				'add_empty' => _ ( '-Select class-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select class-' ) ) ) );

		$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true,
				'column' => 'id' ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'tracked_at', $tracked_at_formFilter );

		if ($request->isMethod ( 'post' )) {
			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );
			$this->formFilter->bind ( $value_student_filter, $request->getFiles ( 'student_filter' ) );
		}

		$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->form->setDefault ( 'class_id', $class_id );

		$this->form->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->form->setDefault ( 'login_at', $login_at );

		$this->form->setDefault ( 'logout_at', $logout_at );

		$this->form->setDefault ( 'log_value', 1 );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );

		$this->setTemplate ( 'new' );
	}

	public function executeUpdateXXXXXXXXXXXXXXXXXXXXXXX(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$this->ps_logtimes = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_logtimes );

		$student = $this->ps_logtimes->getStudent ();

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->processForm ( $request, $this->form );

		$tracked_at_formFilter = date ( 'd-m-Y', strtotime ( $this->ps_logtimes->getLoginAt () ) );

		$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );

		$class_id = $this->form->getDefault ( 'class_id' );

		$ps_workplace_id = $this->form->getDefault ( 'ps_workplace_id' );

		$login_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );

		$login_time = ($login_times->getValue () != '') ? $login_times->getValue () : $login_times->getValueDefault ();

		$logout_times = Doctrine::getTable ( 'PsConstantOption' )->getConstantByCode ( $ps_customer_id, PreSchool::CONSTANT_OPTION_DEFAULT_LOGIN );

		$logout_time = ($logout_times->getValue () != '') ? $logout_times->getValue () : $logout_times->getValueDefault ();

		$login_at = $this->ps_logtimes->getLoginAt () ? date ( 'H:i', strtotime ( $this->ps_logtimes->getLoginAt () ) ) : $login_time;

		$logout_at = $this->ps_logtimes->getLogoutAt () ? date ( 'H:i', strtotime ( $this->ps_logtimes->getLogoutAt () ) ) : $logout_time;

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
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

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
		}

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false,
				'column' => 'id' ) ) );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE ) );
		} else {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
		}

		$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => $sqlMyClass,
				'add_empty' => _ ( '-Select class-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select class-' ) ) ) );

		$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => true,
				'column' => 'id' ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->form->setDefault ( 'class_id', $class_id );

		$this->form->setDefault ( 'tracked_at', $tracked_at_formFilter );

		$this->form->setDefault ( 'login_at', $login_at );

		$this->form->setDefault ( 'logout_at', $logout_at );

		$this->form->setDefault ( 'log_value', 1 );

		$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );

		$this->setTemplate ( 'edit' );
	}

	public function executeDeleteXXXXXXXXXXX(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$student = $this->getRoute ()
			->getObject ()
			->getStudent ();

		$current_date = date ( "Ymd" );

		$check_current_date = (PsDateTime::psDatetoTime ( $this->getRoute ()
			->getObject ()
			->getLoginAt () ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai

		if (! $check_current_date) {
			$this->getUser ()
				->setFlash ( 'notice', 'You can not edit or delete after attendance 1 day.' );

			$this->redirect ( '@ps_logtimes' );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$logtime_id = $this->getRoute ()
			->getObject ()
			->getId ();

		$student_id = $student->getId ();

		$student_name = $student->getFirstName () . " " . $student->getLastName ();

		$login_at = $this->getRoute ()
			->getObject ()
			->getLoginAt ();

		$login_relative_id = $this->getRoute ()
			->getObject ()
			->getLoginRelativeId ();

		$login_relative_name = $this->getRoute ()
			->getObject ()
			->getRelativeLogin ()
			->getFirstName () . " " . $this->getRoute ()
			->getObject ()
			->getRelativeLogin ()
			->getLastName ();

		$logout_at = $this->getRoute ()
			->getObject ()
			->getLogoutAt ();

		$logout_relative_id = $this->getRoute ()
			->getObject ()
			->getLogoutRelativeId ();

		$login_relative_name = $this->getRoute ()
			->getObject ()
			->getRelativeLogout ()
			->getFirstName () . " " . $this->getRoute ()
			->getObject ()
			->getRelativeLogout ()
			->getLastName ();

		$login_member_id = $this->getRoute ()
			->getObject ()
			->getLoginMemberId ();

		$login_member_name = $this->getRoute ()
			->getObject ()
			->getPsMemberLogin ()
			->getFirstName () . " " . $this->getRoute ()
			->getObject ()
			->getPsMemberLogin ()
			->getLastName ();

		$logout_member_id = $this->getRoute ()
			->getObject ()
			->getLogoutMemberId ();

		$logout_member_name = $this->getRoute ()
			->getObject ()
			->getPsMemberLogout ()
			->getFirstName () . " " . $this->getRoute ()
			->getObject ()
			->getPsMemberLogout ()
			->getLastName ();

		$currentUser = myUser::getUser ();

		$action = 'delete';

		$history_content = $this->getContext ()
			->getI18N ()
			->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Login at' ) . ": " . $login_at . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Logout at' ) . ": " . $logout_at . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Login relative id' ) . ": " . $login_relative_id . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Login relative name' ) . ": " . $login_relative_name . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Login member id' ) . ": " . $login_member_id . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Login member name' ) . ": " . $login_member_name . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Logout relative id' ) . ": " . $logout_relative_id . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Logout relative name' ) . ": " . $logout_relative_name . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Logout member id' ) . ": " . $logout_member_id . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Logout member name' ) . ": " . $logout_member_name . '<br/>' . $this->getContext ()
			->getI18N ()
			->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . " " . $currentUser->getLastName () . '(' . $currentUser->getUsername () . ')' . '<br/>';

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {

			$logHistory = new PsHistoryLogtimes ();

			$logHistory->setPsLogtimeId ( $logtime_id );

			$logHistory->setPsAction ( $action );

			$logHistory->setHistoryContent ( $history_content );

			$logHistory->save ();

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_logtimes' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$notice = $form->getObject ()
				->isNew () ? 'Attendance successfully.' : 'Updated attendance successfully.';

			try {

				$action = $form->getObject ()
					->isNew () ? "add" : "edit";

				$value_ps_logtimes_form = $request->getParameter ( 'ps_logtimes' );

				$student_id = $value_ps_logtimes_form ['student_id'];

				$tracked_at = $value_ps_logtimes_form ['tracked_at'];

				$services = isset ( $value_ps_logtimes_form ['student_service'] ) ? $value_ps_logtimes_form ['student_service'] : '';

				// print_r($services); die();

				$formObject = $form->getObject ();

				$service_name = "";

				$currentUser = myUser::getUser ();

				Doctrine_Core::getTable ( 'StudentServiceDiary' )->findByStudentTrackedAt ( $student_id, $tracked_at )
					->delete ();

				if (is_array ( $services )) {

					foreach ( $services as $service ) {

						$student_service_diary = new StudentServiceDiary ();

						$student_service_diary->setServiceId ( $service );

						$student_service_diary->setStudentId ( $student_id );

						$student_service_diary->setTrackedAt ( date ( 'Y-m-d', strtotime ( $tracked_at ) ) );

						$student_service_diary->setUserCreatedId ( myUser::getUserId () );

						$student_service_diary->save ();

						$service_name .= Doctrine::getTable ( 'Service' )->getServiceName ( $service )
							->getTitle () . ", ";
					}
				}

				$service_name = substr ( $service_name, 0, - 2 );

				$history_content = $this->getContext ()
					->getI18N ()
					->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Student name' ) . ": " . Doctrine::getTable ( 'Student' )->getStudentName ( $student_id ) . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Login at' ) . ": " . $formObject->getLoginAt () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Logout at' ) . ": " . $formObject->getLogoutAt () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Login relative id' ) . ": " . $login_relative_id = $formObject->getLoginRelativeId () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Login relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $login_relative_id ) ['name'] . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Login member id' ) . ": " . $login_member_id = $formObject->getLoginMemberId () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Login member name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getMemberName ( $login_member_id ) ['name'] . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Logout relative id' ) . ": " . $logout_relative_id = $formObject->getLogoutRelativeId () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Logout relative name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getRelativeName ( $logout_relative_id ) ['name'] . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Logout member id' ) . ": " . $logout_member_id = $formObject->getLogoutMemberId () . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Logout member name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getMemberName ( $logout_member_id ) ['name'] . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . " " . $currentUser->getLastName () . '(' . $currentUser->getUsername () . ')' . '<br/>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Used service' ) . ": " . $service_name . '<br/>';

				$ps_logtimes = $form->save ();

				$historyLogtime = new PsHistoryLogtimes ();

				$historyLogtime->setPsLogtimeId ( $form->getObject ()
					->getId () );

				$historyLogtime->setPsAction ( $action );

				$historyLogtime->setStudentId ( $student_id );

				$historyLogtime->setHistoryContent ( $history_content );

				$historyLogtime->save ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message . 'aaa' );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_logtimes ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				// $this->redirect('@ps_logtimes_new');
				return sfView::SUCCESS;
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_logtimes_edit',
						'sf_subject' => $ps_logtimes ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	// Luu diem danh theo lop
	public function executeSavePsLogtimeXXXXXXXXXXXX(sfWebRequest $request) {

		$tracked_at = $request->getParameter ( 'tracked_at' );

		$student_logtimes = $request->getParameter ( 'student_logtime' );

		print_r ( $student_logtimes );
		die ();

		$conn = Doctrine_Manager::connection ();

		$current_date = date ( "Ymd" );

		$check_current_date = true;

		// Chỗ này Việt viết đang sai
		if ($this->getUser ()
			->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' ) && ! myUser::isAdministrator ()) {

			$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai
		}

		if (! $check_current_date) {
			$this->getUser ()
				->setFlash ( 'notice', 'You can not edit or delete after attendance 1 day.' );

			$this->redirect ( '@ps_logtimes' );
		}

		$currentUser = myUser::getUser ();

		try {

			$conn->beginTransaction ();
			$error = false;
			foreach ( $student_logtimes as $key => $student_logtime ) {

				$student_id = (isset ( $student_logtime ['student_id'] ) && $student_logtime ['student_id'] > 0) ? $student_logtime ['student_id'] : null;

				$relative_login = (isset ( $student_logtime ['relative_login'] ) && $student_logtime ['relative_login'] > 0) ? $student_logtime ['relative_login'] : null;

				$member_login = (isset ( $student_logtime ['member_login'] ) && $student_logtime ['member_login'] > 0) ? $student_logtime ['member_login'] : null;

				/*
				 * $time_in = (isset($student_logtime['login_at'])) ? $student_logtime['login_at'] : '00:00:00';
				 * $login_at = date("Y-m-d", strtotime($tracked_at)) . ' ' . date("H:i", strtotime($time_in));
				 */
				if (isset ( $student_logtime ['login_at'] ) && $student_logtime ['login_at'] != '') {

					$date_temp = $tracked_at . ' ' . $student_logtime ['login_at'];

					$login_at = date ( "Y-m-d H:i:s", PsDateTime::psDatetoTime ( $date_temp ) );
				} else {
					$login_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $tracked_at ) ) . " " . date ( "H:i:s" );
				}

				$relative_logout = (isset ( $student_logtime ['relative_logout'] ) && $student_logtime ['relative_logout'] > 0) ? $student_logtime ['relative_logout'] : null;

				$member_logout = (isset ( $student_logtime ['member_logout'] ) && $student_logtime ['member_logout'] > 0) ? $student_logtime ['member_logout'] : null;

				/*
				 * $time_out = (isset($student_logtime['logout_at']) && $student_logtime['logout_at'] != '') ? $student_logtime['logout_at'] : '00:00:00';
				 * $logout_at = ($student_logtime['logout_at'] != '') ? date("Y-m-d", strtotime($tracked_at)) . ' ' . date("H:i:s", strtotime($time_out)) : null;
				 */

				if (isset ( $student_logtime ['logout_at'] ) && $student_logtime ['logout_at'] != '') {

					$date_temp = $tracked_at . ' ' . $student_logtime ['logout_at'];

					$logout_at = date ( "Y-m-d H:i:s", PsDateTime::psDatetoTime ( $date_temp ) );
				} else {
					$logout_at = null;
				}

				$log_value = (isset ( $student_logtime ['log_value'] )) ? $student_logtime ['log_value'] : null;
				$note = (isset ( $student_logtime ['note'] )) ? $student_logtime ['note'] : null;
				$services = (isset ( $student_logtime ['student_service'] )) ? $student_logtime ['student_service'] : null;

				// Tim xem da ton tai chua
				$ps_logtimes = Doctrine::getTable ( 'PsLogtimes' )->updateLogtimeByTrackedAt ( $student_id, $tracked_at );

				$student_name = Doctrine::getTable ( 'Student' )->getStudentName ( $student_id );

				$relative_login_name = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_login ) ['name'];

				if ($relative_logout > 0)
					$relative_logout_name = Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative_logout )
						->getName ();

				$member_login_name = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_login ) ['name'];

				if ($member_logout > 0)
					$member_logout_name = Doctrine::getTable ( 'PsMember' )->getMemberName ( $member_logout )
						->getName ();

				// Xoa bo cac dich vu da dung trong ngay diem danh
				if (count ( $ps_logtimes ) > 0)
					Doctrine::getTable ( 'StudentServiceDiary' )->findByStudentTrackedAt ( $student_id, $tracked_at )
						->delete ();

				$service_name = "";

				if ($log_value > 0) {

					foreach ( $services as $service ) {
						$student_service_diary = new StudentServiceDiary ();
						$student_service_diary->setServiceId ( $service );
						$student_service_diary->setStudentId ( $student_id );
						$student_service_diary->setTrackedAt ( $tracked_at );
						$student_service_diary->setUserCreatedId ( myUser::getUserId () );
						$student_service_diary->save ();

						$student_service_name = Doctrine::getTable ( 'Service' )->getServiceName ( $service )
							->getTitle ();

						$service_name .= $student_service_name . ", ";
					}

					// bo dau "," cuoi chuoi
					$service_name = substr ( $service_name, 0, - 2 );

					$history_content = $this->getContext ()
						->getI18N ()
						->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login at' ) . ": " . $login_at . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout at' ) . ": " . $logout_at . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login relative id' ) . ": " . $relative_login . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login relative name' ) . ": " . $relative_login_name . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login member id' ) . ": " . $member_login . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login member name' ) . ": " . $member_login_name . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout relative id' ) . ": " . $relative_logout . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout relative name' ) . ": " . $relative_logout_name . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout member id' ) . ": " . $member_logout . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout member name' ) . ": " . $member_logout_name . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . " " . $currentUser->getLastName () . '(' . $currentUser->getUsername () . ')' . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Used service' ) . ": " . $service_name . '<br/>';

					if (count ( $ps_logtimes )) { // Neu da ton tai

						$action = "edit";

						foreach ( $ps_logtimes as $ps_logtime ) {
							$ps_logtime->setStudentId ( $student_id );
							$ps_logtime->setLoginAt ( $login_at );
							$ps_logtime->setLoginRelativeId ( $relative_login );
							$ps_logtime->setLoginMemberId ( $member_login );
							$ps_logtime->setStudentId ( $student_id );
							$ps_logtime->setLogoutAt ( $logout_at );
							$ps_logtime->setLogoutRelativeId ( $relative_logout );
							$ps_logtime->setLogoutMemberId ( $member_logout );
							$ps_logtime->setLogValue ( $log_value );
							$ps_logtime->setNote ( $note );
							$ps_logtime->setUserCreatedId ( myUser::getUserId () );

							$ps_logtime->save ();

							$logtimeHistory = new PsHistoryLogtimes ();

							$logtimeHistory->setPsLogtimeId ( $ps_logtime->getId () );

							$logtimeHistory->setStudentId ( $student_id );

							$logtimeHistory->setPsAction ( $action );

							$logtimeHistory->setHistoryContent ( $history_content );

							$logtimeHistory->save ();
						}
					} else {

						$action = "add";

						$ps_logtime = new PsLogtimes ();
						$ps_logtime->setStudentId ( $student_id );
						$ps_logtime->setLoginAt ( $login_at );
						$ps_logtime->setLoginRelativeId ( $relative_login );
						$ps_logtime->setLoginMemberId ( $member_login );
						$ps_logtime->setStudentId ( $student_id );
						$ps_logtime->setLogoutAt ( $logout_at );
						$ps_logtime->setLogoutRelativeId ( $relative_logout );
						$ps_logtime->setLogoutMemberId ( $member_logout );
						$ps_logtime->setLogValue ( $log_value );
						$ps_logtime->setNote ( $note );
						$ps_logtime->setUserCreatedId ( myUser::getUserId () );
						$ps_logtime->save ();

						$logtimeHistory = new PsHistoryLogtimes ();

						$logtimeHistory->setPsLogtimeId ( $ps_logtime->getId () );

						$logtimeHistory->setStudentId ( $ps_logtime->getStudentId () );

						$logtimeHistory->setPsAction ( $action );

						$logtimeHistory->setHistoryContent ( $history_content );

						$logtimeHistory->save ();
					}
				} else {
					// Tim va xoa du lieu neu da co
					$ps_logtimes->delete ();
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$this->getUser ()
				->setFlash ( 'error', 'Classroom attendance was saved failed.' );

			$conn->rollback ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'Classroom attendance was saved successfully. You can add another one below.' );

		$this->redirect ( '@ps_logtimes' );
	}

	public function executeTrackbook(sfWebRequest $request) {

		$student_id = $request->getParameter ( 'sid' );

		$date_at = $request->getParameter ( 'date_at' );

		$this->student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

		// ? Cần check lại
		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form_filter = new sfForm ();

		$this->year = $request->getParameter ( 'ps_year' );

		$this->month = $request->getParameter ( 'ps_month' );

		if (! $this->year || ! $this->month) {
			$this->month = date ( 'm' );
			$this->year = date ( 'Y' );
		}

		if ($date_at) {
			$this->month = date ( 'm', $date_at );
			$this->year = date ( 'Y', $date_at );
		}

		$default_month = $this->month;

		$this->month = ($this->month < 10) ? '0' . ( int ) $this->month : $this->month;

		$tracked_at = '01' . '-' . $this->month . '-' . $this->year;

		$years = range ( date ( 'Y' ), sfConfig::get ( 'app_begin_year' ) );

		$this->form_filter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$month = range ( 1, 12 );

		$this->form_filter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $month, $month ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select month-' ) ) ) );

		$this->form_filter->setDefault ( 'ps_month', ( int ) $default_month );
		$this->form_filter->setDefault ( 'ps_year', $this->year );

		$this->list_relative = $this->student->getRelativesOfStudent ();

		$this->list_member = array ();

		// $this->class = $this->student->getMyClassByStudent();

		// Lay lop hoc o thoi diem hien tai
		$this->class = $this->student->getClassByDate ( time () );

		$this->ps_work_places = null;

		if ($this->class) {

			$this->class_id = $this->class->getMyclassId ();
			$this->ps_work_places = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $this->class->getPsWorkplaceId () );
		} else {
			$this->class_id = null;
		}

		$this->student_logtime = $this->student->getLogtimeByDate ( $this->year . $this->month );

		// Kiem tra lop cua hoc sinh trong thang
		// $this->check_student_class = $this->student->checkStudentClassByDate($this->year . $this->month);

		$this->check_student_class = true;

		// Lay tat ca cac dich vu - dich vu hoc ma hoc sinh dang dang ky su dung
		$this->list_registered_service = Doctrine::getTable ( 'Service' )->getServicesByStudentId ( $student_id, null, time () );
	}

	// Luu diem danh so theo doi => Chưa thấy có Save History
	public function executeSaveTrackbook(sfWebRequest $request) {

		$student_logtimes = $request->getParameter ( 'student_logtime' );

		$user_id = myUser::getUserId ();

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			$index = 0;

			foreach ( $student_logtimes as $key => $student_logtime ) {

				$index ++;

				$tracked_at = date ( 'Y-m-d', $key );

				if ($index == 1) { // chi lay id hoc sinh 1 lan
					$student_id = $student_logtime ['student_id'];
					$class_id = Doctrine::getTable ( 'StudentClass' )->getCurrentClassOfStudent ( $student_id )
						->getClassId ();
					$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );
					$ps_customer_id = $ps_student->getPsCustomerId ();
					$student_name = $ps_student->getFirstName () . ' ' . $ps_student->getLastName ();
				}
				// Tim xem da ton tai chua
				$ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->updateLogtimeByTrackedAt ( $student_id, $tracked_at );

				// Xoa bo cac dich vu da dung trong ngay diem danh
				Doctrine_Core::getTable ( 'StudentServiceDiary' )->findByStudentTrackedAt ( $student_id, $tracked_at )
					->delete ();

				$log_value = (isset ( $student_logtime ['log_value'] )) ? $student_logtime ['log_value'] : null;

				if ($log_value > 0) {

					$relative_login = ($student_logtime ['relative_login'] != null) ? $student_logtime ['relative_login'] : null;

					$member_login = ($student_logtime ['member_login'] != null) ? $student_logtime ['member_login'] : null;

					$time_in = ($student_logtime ['login_at'] != null) ? $student_logtime ['login_at'] : '00:00:00';

					$login_at = date ( "Y-m-d", strtotime ( $tracked_at ) ) . ' ' . date ( "H:i:s", strtotime ( $time_in ) );

					$relative_logout = ($student_logtime ['relative_logout'] != null) ? $student_logtime ['relative_logout'] : null;

					$member_logout = ($student_logtime ['member_logout'] != null) ? $student_logtime ['member_logout'] : null;

					$time_out = ($student_logtime ['login_at'] != null) ? $student_logtime ['logout_at'] : null;
					$logout_at = ($time_out) ? date ( "Y-m-d", strtotime ( $tracked_at ) ) . ' ' . date ( "H:i:s", strtotime ( $time_out ) ) : null;

					// Cắt lấy 255 ký tự nếu vượt 255
					$note = (strlen ( $student_logtime ['note'] ) <= 255) ? $student_logtime ['note'] : substr ( $student_logtime ['note'], 0, 255 );

					$service_name = '';

					$services = (isset ( $student_logtime ['student_service'] )) ? $student_logtime ['student_service'] : array ();

					foreach ( $services as $service ) {
						$student_service_diary = new StudentServiceDiary ();
						$student_service_diary->setServiceId ( $service );
						$student_service_diary->setStudentId ( $student_id );
						$student_service_diary->setTrackedAt ( $tracked_at );
						$student_service_diary->setUserCreatedId ( myUser::getUserId () );
						$student_service_diary->save ();

						$service_name .= Doctrine::getTable ( 'Service' )->getServiceName ( $service )
							->getTitle () . ", ";
					}

					if ($ps_logtimes > 0) { // Neu da ton tai
					                        // foreach ($ps_logtimes as $ps_logtime) {

						// kiem tra neu du lieu thay doi thi luu, khong thay doi thi thoi
						if (strtotime ( $login_at ) != strtotime ( $ps_logtimes->getLoginAt () ) || strtotime ( $logout_at ) != strtotime ( $ps_logtimes->getLogoutAt () )) {

							// luu diem danh
							$ps_logtimes->setStudentId ( $student_id );
							$ps_logtimes->setLoginAt ( $login_at );
							$ps_logtimes->setLoginRelativeId ( $relative_login );
							$ps_logtimes->setLoginMemberId ( $member_login );
							$ps_logtimes->setStudentId ( $student_id );
							$ps_logtimes->setLogoutAt ( $logout_at );
							$ps_logtimes->setLogoutRelativeId ( $relative_logout );
							$ps_logtimes->setLogoutMemberId ( $member_logout );
							$ps_logtimes->setLogValue ( $log_value );
							$ps_logtimes->setNote ( $note );
							$ps_logtimes->setUserUpdatedId ( myUser::getUserId () );
							$ps_logtimes->save ();

							$trangthai = $this->getContext ()
								->getI18N ()
								->__ ( 'Go school' );

							$history_content = $this->getContext ()
								->getI18N ()
								->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Login at' ) . ": " . $login_at . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Logout at' ) . ": " . $logout_at . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Login relative id' ) . ": " . $relative_login . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Login member id' ) . ": " . $member_login . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Logout relative id' ) . ": " . $relative_logout . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Logout member id' ) . ": " . $member_logout . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Status' ) . ": " . $trangthai . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . $this->getContext ()
								->getI18N ()
								->__ ( 'Used service' ) . ": " . $service_name . '<br/>';

							// luu lich su diem danh

							$historyLogtime = new PsHistoryLogtimes ();

							$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );

							$historyLogtime->setPsAction ( 'edit' );

							$historyLogtime->setStudentId ( $student_id );

							$historyLogtime->setHistoryContent ( $history_content );

							$historyLogtime->save ();
						}

						// }
					} else {

						$ps_logtimes = new PsLogtimes ();
						$ps_logtimes->setStudentId ( $student_id );
						$ps_logtimes->setLoginAt ( $login_at );
						$ps_logtimes->setLoginRelativeId ( $relative_login );
						$ps_logtimes->setLoginMemberId ( $member_login );
						$ps_logtimes->setStudentId ( $student_id );
						$ps_logtimes->setLogoutAt ( $logout_at );
						$ps_logtimes->setLogoutRelativeId ( $relative_logout );
						$ps_logtimes->setLogoutMemberId ( $member_logout );
						$ps_logtimes->setLogValue ( $log_value );
						$ps_logtimes->setNote ( $note );
						$ps_logtimes->setUserCreatedId ( myUser::getUserId () );
						$ps_logtimes->setUserUpdatedId ( myUser::getUserId () );
						$ps_logtimes->save ();

						$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $tracked_at );

						if (! $number_attendances) {
							$number_attendances = new PsAttendancesSynthetic ();
							$number_attendances->setPsCustomerId ( $ps_customer_id );
							$number_attendances->setPsClassId ( $class_id );
							$number_attendances->setLoginSum ( 1 );

							if ($logout_at != '') {
								$number_attendances->setLogoutSum ( 1 );
							} else {
								$number_attendances->setLogoutSum ( 0 );
							}

							$number_attendances->setTrackedAt ( $date_at );
							$number_attendances->setUserUpdatedId ( $user_id );
							$number_attendances->save ();
						} else {

							$number_login = $number_attendances->getLoginSum ();
							$number_logout = $number_attendances->getLogoutSum ();

							$number_attendances->setLoginSum ( $number_login + 1 );

							if ($logout_at != '') {
								$number_attendances->setLogoutSum ( $number_logout + 1 );
							}
							$number_attendances->setUserUpdatedId ( $user_id );
							$number_attendances->save ();
						}

						$trangthai = $this->getContext ()
							->getI18N ()
							->__ ( 'Go school' );

						$history_content = $this->getContext ()
							->getI18N ()
							->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login at' ) . ": " . $login_at . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout at' ) . ": " . $logout_at . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login relative id' ) . ": " . $relative_login . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login member id' ) . ": " . $member_login . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout relative id' ) . ": " . $relative_logout . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout member id' ) . ": " . $member_logout . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Status' ) . ": " . $trangthai . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Used service' ) . ": " . $service_name . '<br/>';

						// luu lich su diem danh

						$historyLogtime = new PsHistoryLogtimes ();

						$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );

						$historyLogtime->setPsAction ( 'add' );

						$historyLogtime->setStudentId ( $student_id );

						$historyLogtime->setHistoryContent ( $history_content );

						$historyLogtime->save ();
					}
				} else {

					// Tim va xoa du lieu neu da co

					$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $tracked_at );

					if ($number_attendances) {
						if ($logout_at != '' && $number_attendances->getLogoutSum () > 0) {
							$number_logout = $number_attendances->getLogoutSum ();
							$number_attendances->setLogoutSum ( $number_logout - 1 );
						}
						$number_login = $number_attendances->getLoginSum ();
						if ($number_login > 0) {
							$number_attendances->setLoginSum ( $number_login - 1 );
						}
						$number_attendances->setUserUpdatedId ( $user_id );
						$number_attendances->save ();

						// luu lich su xoa diem danh

						$history_content = $this->getContext ()
							->getI18N ()
							->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Student name' ) . ": " . $student_name . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Delete attendance' ) . ": " . $tracked_at . '<br/>';

						if ($ps_logtimes) {
							$log_id = $ps_logtimes->getId (); // xoa diem danh cua ngay
						}

						$historyLogtime = new PsHistoryLogtimes ();

						$historyLogtime->setPsLogtimeId ( $log_id );

						$historyLogtime->setPsAction ( 'delete' );

						$historyLogtime->setStudentId ( $student_id );

						$historyLogtime->setHistoryContent ( $history_content );

						$historyLogtime->save ();
					}
					if ($ps_logtimes) {
						$ps_logtimes->delete (); // xoa diem danh cua ngay
					}
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$this->getUser ()
				->setFlash ( 'error', 'Trackbook attendance was saved failed.' );

			$conn->rollback ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'Trackbook attendance was saved successfully. You can add another one below.' );

		$this->redirect ( '@ps_student_info_trackbook?sid=' . $student_id . '&date_at=' . strtotime ( $tracked_at ) );
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		$ps_logtimes_statistic_url = $request->getParameter ( 'ps_logtimes_statistic_url' );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_logtimes' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );

		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_logtimes' );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		$this->setTemplate ( 'index' );
	}

	// Ham thong ke diem danh cua thang
	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$this->year_month = date ( 'm-Y', strtotime ( $request->getParameter ( 'date' ) ) );

		$ps_school_year_id = null;

		$this->class_id = $request->getParameter ( 'cid' );

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
		} elseif ($this->class_id > 0) {

			$info_class = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $this->class_id );

			if ($info_class) {
				$this->ps_school_year_id = $info_class->getSyId ();
				$this->ps_customer_id = $info_class->getPsCustomerId ();
				$this->ps_workplace_id = $info_class->getWpId ();
			}

			$this->filter_list_student = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesClassId ( $this->class_id, $this->year_month );

			$this->filter_list_logtime = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesStatistic ( $this->class_id, $this->year_month );
		}

		// $this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );
		if ($this->year_month == '01-1970') {
			$this->year_month = date ( "m-Y" );
		}

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

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'logtimes_filter[%s]' );
	}

	// luu hoc sinh diem danh ve muon
	public function executeSaveDelay(sfWebRequest $request) {

		$student_id = $request->getParameter ( 'student_id' );
		$logout_at = $request->getParameter ( 'logout_at' );
		$note = $request->getParameter ( 'note' );
		$lt_id = $request->getParameter ( 'lt_id' );
		$relative = $request->getParameter ( 'relative' );

		if ($request->getParameter ( 'date_at' ) != '') {
			$date_at = $request->getParameter ( 'date_at' );
		} else {
			$date_at = date ( 'Y-m-d' );
		}
		$date_fomat = $date_at . ' ' . $logout_at;
		$date = date ( 'Y-m-d H:i:s', strtotime ( $date_fomat ) );

		// Check role
		$ps_student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );

		if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			echo '<code>' . $this->getContext ()->getI18N ()->__ ( 'Not role data' ) . '</code>';

			exit ( 0 );
		} else {

			$ps_logtimes = Doctrine_Core::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $date );

			if (! $ps_logtimes) {
				echo '<code>' . $this->getContext ()
					->getI18N ()
					->__ ( 'Not data' ) . '</code>';
				exit ( 0 );
			} else {

				// check xem co quyen duoc diem danh lui ngay hay khong
				$access_ps_logtimes = $this->getUser ()
					->hasCredential ( array (
						'PS_STUDENT_ATTENDANCE_DELAY',
						'PS_STUDENT_ATTENDANCE_ADD',
						'PS_STUDENT_ATTENDANCE_EDIT',
						'PS_STUDENT_ATTENDANCE_DELETE',
						'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), false );

				if ($access_ps_logtimes && (! $this->getUser ()
					->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' ) && ! myUser::isAdministrator ())) {

					$ps_logtimes->setLogoutAt ( $date );

					$ps_logtimes->setLogoutRelativeId ( $relative ); // $relative là ID người đón?
					$ps_logtimes->setNote ( $note );

					$member_id = myUser::getUserId ();

					$ps_logtimes->setLogoutMemberId ( $member_id ); // Chỗ này phải ghi lại để sửa. Khi cô được phân công thì vẫn có ID Phải thêm cột Giáo viên trả trẻ vào

					$ps_logtimes->setUserUpdatedId ( $member_id );

					// luu log diem danh ve muon cua hoc sinh

					$history_content = $this->getContext ()
						->getI18N ()
						->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Student name' ) . ": " . Doctrine::getTable ( 'Student' )->getStudentName ( $student_id ) . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login at' ) . ": " . $ps_logtimes->getLoginAt () . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout at' ) . ": " . $date . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login relative id' ) . ": " . $ps_logtimes->getLoginRelativeId () . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $ps_logtimes->getLoginRelativeId () ) ['name'] . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login member id' ) . ": " . $login_member_id = $ps_logtimes->getLoginMemberId () . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Login member name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getMemberName ( $login_member_id ) ['name'] . '<br/>' . 
					$this->getContext ()
						->getI18N ()
						->__ ( 'Logout relative id' ) . ": " . $relative . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative ) ['name'] . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout member id' ) . ": " . $member_id . '<br/>' . $this->getContext ()
						->getI18N ()
						->__ ( 'Logout member name' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . // phan nay phai sua lai giao vien tra tre ve muon
					$this->getContext ()
						->getI18N ()
						->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>';

					$historyLogtime = new PsHistoryLogtimes ();

					$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );

					$historyLogtime->setPsAction ( 'edit' );

					$historyLogtime->setStudentId ( $student_id );

					$historyLogtime->setHistoryContent ( $history_content );

					$historyLogtime->save ();

					$ps_logtimes->save ();

					$check_logtime = false;

					$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_student->getId (), $ps_student->getPsCustomerId () );

					return $this->renderPartial ( 'psLogtimes/row_li_delay', array (
							'list_student' => $ps_logtimes,
							'check_logtime' => $check_logtime,
							'list_relative' => $list_relative ) );
				} else {
					// Neu khong thi kiem tra xem ngay diem danh co phai ngay hien tai hay ko
					$now = date ( 'Ymd' );
					if (strtotime ( $now ) == strtotime ( $date )) {
						$ps_logtimes->setLogoutAt ( $date );

						$ps_logtimes->setLogoutRelativeId ( $relative ); // $relative là ID người đón?
						$ps_logtimes->setNote ( $note );

						$member_id = myUser::getUserId ();

						$ps_logtimes->setLogoutMemberId ( $member_id ); // Chỗ này phải ghi lại để sửa. Khi cô được phân công thì vẫn có ID Phải thêm cột Giáo viên trả trẻ vào

						$ps_logtimes->setUserUpdatedId ( $member_id );

						// luu log diem danh ve muon cua hoc sinh

						$history_content = $this->getContext ()
							->getI18N ()
							->__ ( 'Student id' ) . ": " . $student_id . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Student name' ) . ": " . Doctrine::getTable ( 'Student' )->getStudentName ( $student_id ) . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login at' ) . ": " . $ps_logtimes->getLoginAt () . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout at' ) . ": " . $date . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login relative id' ) . ": " . $ps_logtimes->getLoginRelativeId () . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $ps_logtimes->getLoginRelativeId () ) ['name'] . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login member id' ) . ": " . $login_member_id = $ps_logtimes->getLoginMemberId () . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Login member name' ) . ": " . Doctrine::getTable ( 'PsMember' )->getMemberName ( $login_member_id ) ['name'] . '<br/>' . 
						$this->getContext ()
							->getI18N ()
							->__ ( 'Logout relative id' ) . ": " . $relative . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout relative name' ) . ": " . Doctrine::getTable ( 'Relative' )->getRelativeName ( $relative ) ['name'] . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout member id' ) . ": " . $member_id . '<br/>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Logout member name' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>' . // phan nay phai sua lai giao vien tra tre ve muon
						$this->getContext ()
							->getI18N ()
							->__ ( 'Created by' ) . ": " . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName () . '(' . myUser::getUser ()->getUsername () . ')' . '<br/>';

						$historyLogtime = new PsHistoryLogtimes ();

						$historyLogtime->setPsLogtimeId ( $ps_logtimes->getId () );

						$historyLogtime->setPsAction ( 'edit' );

						$historyLogtime->setStudentId ( $student_id );

						$historyLogtime->setHistoryContent ( $history_content );

						$historyLogtime->save ();

						$ps_logtimes->save ();

						$number_attendances = Doctrine_Core::getTable ( 'PsAttendancesSynthetic' )->getAttendanceSyntheticByDate ( $class_id, $date );

						if ($number_attendances) {
							$number_log = $number_attendances->getLogoutSum ();
							$number_attendances->setLogoutSum ( $number_log + 1 );
							$number_attendances->setUserUpdatedId ( $user_id );
							$number_attendances->save ();
						}

						$check_logtime = false;

						$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $ps_student->getId (), $ps_student->getPsCustomerId () );

						return $this->renderPartial ( 'psLogtimes/row_li_delay', array (
								'list_student' => $ps_logtimes,
								'check_logtime' => $check_logtime,
								'list_relative' => $list_relative ) );
					} else {
						echo '<code>' . $this->getContext ()
							->getI18N ()
							->__ ( 'Not role data' ) . '</code>';
						exit ( 0 );
					}
				}
			}
		}
	}

	// lich su diem danh
	public function executeHistory(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$year_month = null;

		$class_id = null;

		$student_id = null;

		$this->filter_list_student = array ();

		$history_filter = $request->getParameter ( 'history_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'history_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$student_id = $value_student_filter ['student_id'];

			$date_at_from = $value_student_filter ['date_at_from'];

			$date_at_to = $value_student_filter ['date_at_to'];

			$this->filter_list_history = Doctrine::getTable ( 'PsHistoryLogtimes' )->getHistoryLogtimes ( $student_id, $date_at_from, $date_at_to );
		}

		if ($history_filter) {

			$this->ps_school_year_id = isset ( $history_filter ['ps_school_year_id'] ) ? $history_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$this->student_id = isset ( $history_filter ['student_id'] ) ? $history_filter ['student_id'] : 0;

			$this->date_at_from = isset ( $history_filter ['date_at_from'] ) ? $history_filter ['date_at_from'] : '';

			$this->date_at_to = isset ( $history_filter ['date_at_to'] ) ? $history_filter ['date_at_to'] : '';

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

		// $this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
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

			// Filters student

			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsByClassId ( $class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'Student',
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

			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorPass () );
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

		$this->formFilter->setWidget ( 'date_at_from', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_from', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setWidget ( 'date_at_to', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date to' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date to' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_to', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'student_id', $this->student_id );

		$this->formFilter->setDefault ( 'date_at_from', $this->date_at_from );

		$this->formFilter->setDefault ( 'date_at_to', $this->date_at_to );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'history_filter[%s]' );
	}

	// so diem danh
	public function executeExportLogtimesStatistic(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'clid' );

		$month_year = $request->getParameter ( 'date' );

		$this->exportReportLogtimesStatistic ( $class_id, $month_year );

		$this->redirect ( '@ps_logtimes_statistic' );
	}

	protected function exportReportLogtimesStatistic($class_id, $month_year) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );

		$file_template_pb = 'tkhs_sodiemdanh_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );

		$ps_customer_id = $class_name->getPsCustomerId ();

		$dung = 1;
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$dung = 0;
			}
		}

		if ($dung == 0) {
			$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
		}

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$filter_list_student = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesClassId ( $class_id, $month_year );

		$filter_list_logtime = Doctrine::getTable ( 'PsLogtimes' )->getStudentsLogtimesStatistic ( $class_id, $month_year );

		$number_day = PsDateTime::psNumberDaysOfMonth ( $month_year );

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'Statistic track book' ) . $month_year;

		$title_xls = $class_name->getName () . ' ' . $month_year;

		$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_info, $title_xls );

		$exportFile->setDataExportStatistic ( $filter_list_student, $filter_list_logtime, $month_year );

		$exportFile->saveAsFile ( "SoDiemDanh" . ".xls" );
	}
}
