<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeatureBranchGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeatureBranchGeneratorHelper.class.php';

/**
 * psFeatureBranch actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFeatureBranch
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureBranchActions extends autoPsFeatureBranchActions {

	public function executeFeatureBranchActivated(sfWebRequest $request) {

		$id = $request->getParameter ( 'id' );

		try {

			if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_EDIT' )) {
				
				$feature_branch = Doctrine::getTable ( 'FeatureBranch' )->getFeatureBranchByField ( $id,'id,is_activated');
				/*
				if (! myUser::checkAccessObject ( $feature_branch, 'PS_SYSTEM_FEATURE_BRANCH_EDIT' )) {
					return $this->renderPartial ( 'psFeatureBranch/ajax_activated', array (
							'feature_branch' => $feature_branch ) );
				}
				*/
				if ($feature_branch) {
					
					$status = $feature_branch->getIsActivated ();
					
					$feature_branch->setIsActivated ( ! $status );
					
					$feature_branch->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );
					
					$feature_branch->save ();
					
					return $this->renderPartial ( 'psFeatureBranch/ajax_activated', array (
							'feature_branch' => $feature_branch ) );
				}else{
					echo __('Not roll data');
					exit(0);
				}
				
			}else{
				echo __('Not roll data');
				exit(0);
			}
			
		} catch ( Exception $e ) {

			$this->redirect ( '@ps_feature_branch' );
		}

	}

	public function executeIndex(sfWebRequest $request) {
		/* 
		if (myUser::isAdministrator()) {
			
			$records = Doctrine_Query::create()->from('FeatureBranch')->execute();
			 
			foreach ($records as $record) {
			 	$record->setNumberOption($record->getCountBranchOption());
				$record->save();
			}
		}
		*/
		
		// Check dieu kien
		$feature_id = $request->getParameter ( 'feature_id' );

		if ($feature_id > 0) {

			$this->feature = Doctrine_Core::getTable ( 'Feature' )->findOneById ( $feature_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->ps_customer_id = $this->feature->getPsCustomerId ();

			myUser::setPscustomerID ( $this->ps_customer_id );

			$this->setFilters ( array (
					'ps_customer_id' => $this->ps_customer_id,
					'feature_id' => $feature_id ) );
		} else {
			$this->ps_customer_id = parent::getFilters () ['ps_customer_id'];
		}

		parent::executeIndex ( $request );
	}

	public function executePsFeatureBranchSchoolYear(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_school_year_id = $request->getParameter ( 'yid' );
			$ps_customer_id = $request->getParameter ( 'cid' );
			$feature_id = $request->getParameter ( 'fid' );
			if ($ps_customer_id > 0) {

				$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByYear ( $ps_school_year_id, $ps_customer_id, $feature_id )
					->execute ();

				return $this->renderPartial ( 'option_select', array (
						'option_select' => $ps_feature_branch ) );
			} else {
				echo null;
				exit ( 0 );
			}
		} else {
			echo null;
			exit ( 0 );
		}
	}

	public function executePsFeatureBranchCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = $request->getParameter ( 'cid' );
			$feature_id = $request->getParameter ( 'fid' );
			if ($ps_customer_id > 0) {

				$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranch ( $ps_customer_id, $feature_id )
					->execute ();

				return $this->renderPartial ( 'option_select', array (
						'option_select' => $ps_feature_branch ) );
			} else {
				echo null;
				exit ( 0 );
			}
		} else {
			echo null;
			exit ( 0 );
		}
	}

	public function executePsFeatureBranchByClass(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$student_feature_filters = $request->getParameter ( 'student_feature_filters' );

			$myclass_id = $student_feature_filters ['ps_class_id'];
			$tracked_at = $student_feature_filters ['tracked_at'];

			$ps_feature_branch = array ();

			if ($myclass_id > 0) {

				$my_class = Doctrine::getTable ( 'MyClass' )->getMyClassByField ( $myclass_id,'ps_customer_id,school_year_id,ps_obj_group_id,ps_workplace_id' );

				$check_access = (myUser::credentialPsCustomers ( 'PS_STUDENT_FEATURE_FILTER_SCHOOL' ) || ($my_class->getPsCustomerId () == myUser::getPscustomerID ()));

				if ($check_access) {

					$params = array ();
					
					$params ['ps_customer_id'] = $my_class->getPsCustomerId ();
					$params ['ps_school_year_id'] = $my_class->getSchoolYearId ();
// 					$params ['ps_obj_group_id'] = $my_class->getPsObjGroupId ();
					$params ['ps_workplace_id'] = $my_class->getPsWorkplaceId ();
					$params ['ps_myclass_id'] = $myclass_id;
					$params ['tracked_at'] = $tracked_at;
					$params ['is_activated'] = PreSchool::ACTIVE;
					$params ['number_option'] = PreSchool::ACTIVE;
					
					$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByMyClassParams ( $params )->execute ();
				}
			}

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $ps_feature_branch ) );
		} else {
			echo null;
			exit ( 0 );
		}
	}

	public function executeGroupFilters(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			// $feature_branch_times_filters = $request->getParameter ( 'feature_branch_times_filters' );

			$param_feature = array (
					'ps_customer_id' => $request->getParameter ( 'c_id' ),
					'school_year_id' => $request->getParameter ( 'y_id' ),
					'ps_workplace_id' => $request->getParameter ( 'w_id' ),
					'is_activated' => $request->getParameter ( 'i_at' ) );

			$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByFilters ( $param_feature )
				->execute ();

			return $this->renderPartial ( 'psFeatureBranch/option_select', array (
					'option_select' => $ps_feature_branch ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeEdit(sfWebRequest $request) {

		$this->feature_branch = $this->getRoute ()
			->getObject ();

		$ps_feature = $this->feature_branch->getFeature ();
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->feature_branch );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->feature_branch = $this->getRoute ()
			->getObject ();

		$ps_feature = $this->feature_branch->getFeature ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->feature_branch );

		// $this->form->loadRowFeatureBranchTimesFormTemplate(/*$this->feature_branch*/);

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$ps_feature = $this->getRoute ()
			->getObject ()
			->getFeature ();

		$ps_feature_id = $ps_feature->getId ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Check du lieu rang buoc FeatureBranchTimes, FeatureOptionFeature
		$ps_feature_option_feature = $this->getRoute ()
			->getObject ()
			->getFeatureOptionFeature ();
		$ps_feature_branch_times = $this->getRoute ()
			->getObject ()
			->getFeatureBranchTimes ();

		if (count ( $ps_feature_option_feature ) || count ( $ps_feature_branch_times )) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'This feature branch has generated data. Can not delete.' ) );

			$this->redirect ( '@ps_feature_branch?feature_id=' . $ps_feature_id );
		} else {

			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}

			$this->redirect ( '@ps_feature_branch?feature_id=' . $ps_feature_id );
		}
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'FeatureBranch' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$ps_feature = $record->getFeature ();
			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_feature_branch' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$ps_feature_branch_value_form = $request->getParameter ( $form->getName () );

		if (isset ( $ps_feature_branch_value_form ['id'] ) && $ps_feature_branch_value_form ['id'] > 0) {

			// Check quyen voi Service nay
			$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $ps_feature_branch_value_form ['id'] );

			$ps_feature = $ps_feature_branch->getFeature ();

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		// Kiem tra du lieu ServiceDetail muon Remove da duoc su dung chua
		if (isset ( $ps_feature_branch_value_form ['FeatureBranchTimes'] )) {

			foreach ( $ps_feature_branch_value_form ['FeatureBranchTimes'] as $i => $bookmarkValues ) {

				if (isset ( $bookmarkValues ['delete'] ) && $bookmarkValues ['id']) {

					// Kiem tra id $bookmarkValues['id']

					// Neu da ton tai thi thong bao ko xoa
					// $this->getUser()->setFlash('warning', $this->getContext()->getI18N()->__('FeatureBranchTimes has generated data. Can not delete.'));

					// $this->redirect(array('sf_route' => 'ps_feature_branch', 'sf_subject' => $ps_feature_branch));
				}
			}
		}

		parent::processForm ( $request, $form );
	}

	public function executeImport(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$feature_branch = null;

		$feature = null;

		$ps_school_year_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
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

			// filter feature
			$this->formFilter->setWidget ( 'feature', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select feature-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature-' ) ) ) );

			$this->formFilter->setValidator ( 'feature', new sfValidatorDoctrineChoice ( array (
					'model' => 'Feature',
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

			$this->formFilter->setWidget ( 'feature', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature-' ) ) ) );

			$this->formFilter->setValidator ( 'feature', new sfValidatorPass () );
		}

		// $this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);

		if ($this->ps_workplace_id > 0) {
			// Filters by classroom
			$this->formFilter->setWidget ( 'class_room_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id ) ),
					'add_empty' => _ ( '-Select class room-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class room-' ) ) ) );

			$this->formFilter->setValidator ( 'class_room_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'class_room_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class room-' ) ) ) );

			$this->formFilter->setValidator ( 'class_room_id', new sfValidatorPass () );
		}

		// filter feature branch
		if ($feature > 0) {

			$this->formFilter->setWidget ( 'feature_branch', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'FeatureBranch',
					'query' => Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByYear ( $this->ps_school_year_id, $this->ps_customer_id, $this->feature ),
					'add_empty' => _ ( '-Select feature branch name-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select feature branch name-' ) ) ) );

			$this->formFilter->setValidator ( 'feature_branch', new sfValidatorDoctrineChoice ( array (
					'model' => 'FeatureBranch',
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'feature_branch', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature name-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature branch name-' ) ) ) );

			$this->formFilter->setValidator ( 'feature_branch', new sfValidatorPass () );
		}

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_room_id', $this->class_room_id );

		$this->formFilter->setDefault ( 'feature', $this->feature );

		$this->formFilter->setDefault ( 'feature_branch', $this->feature_branch );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	protected function verifyDate($date, $format = 'Y-m-d') {

		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}

	protected function verifyTime($date, $format = 'H:i:s') {

		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}

	public function executeImportSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$feature_branch = null;

		$feature = null;

		$ps_school_year_id = null;

		$ps_file = null;

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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

			// filter feature
			$this->formFilter->setWidget ( 'feature', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select feature-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature-' ) ) ) );

			$this->formFilter->setValidator ( 'feature', new sfValidatorDoctrineChoice ( array (
					'model' => 'Feature',
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

			$this->formFilter->setWidget ( 'feature', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature-' ) ) ) );

			$this->formFilter->setValidator ( 'feature', new sfValidatorPass () );
		}

		if ($this->ps_workplace_id > 0) {
			// Filters by classroom
			$this->formFilter->setWidget ( 'class_room_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id ) ),
					'add_empty' => _ ( '-Select class room-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class room-' ) ) ) );

			$this->formFilter->setValidator ( 'class_room_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'class_room_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class room-' ) ) ) );

			$this->formFilter->setValidator ( 'class_room_id', new sfValidatorPass () );
		}
		// filter feature branch
		if ($feature > 0) {

			$this->formFilter->setWidget ( 'feature_branch', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'FeatureBranch',
					'query' => Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByYear ( $this->ps_school_year_id, $this->ps_customer_id, $this->feature ),
					'add_empty' => _ ( '-Select feature branch name-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select feature branch name-' ) ) ) );

			$this->formFilter->setValidator ( 'feature_branch', new sfValidatorDoctrineChoice ( array (
					'model' => 'FeatureBranch',
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'feature_branch', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature name-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select feature branch name-' ) ) ) );

			$this->formFilter->setValidator ( 'feature_branch', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_room_id', $this->class_room_id );

		$this->formFilter->setDefault ( 'feature', $this->feature );

		$this->formFilter->setDefault ( 'feature_branch', $this->feature_branch );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );
		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		// id hoat dong
		$feature_branch = $this->formFilter->getValue ( 'feature_branch' );
		// id phong hoc
		$class_room_id = $this->formFilter->getValue ( 'class_room_id' );

		if ($class_room_id == '') {
			$class_room_id = null;
		}

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		// lay ra tat ca cac lop theo nam hoc, truong va co so
		$myclass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id ) )
			->execute ();

		$array_class = array ();
		$array_class_obj = array ();
		foreach ( $myclass as $class ) {

			array_push ( $array_class, $class->getId () );

			$array_class_obj [$class->getId ()] = $class->getName ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Feature branch import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeatureBranch' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$arr_class_exits = array ();

				for($row = 3; $row <= $highestRow; $row ++) {
					$ps_myclass_id = $provinceSheet->getCellByColumnAndRow ( 8, $row )
						->getValue ();
					if ($ps_myclass_id != '') {
						if (array_key_exists ( ( int ) $ps_myclass_id, $array_class_obj )) {
							$err_class = 0;
							array_push ( $arr_class_exits, array (
									$ps_myclass_id => $array_class_obj [$ps_myclass_id] ) );
						} else {
							$err_class = 1;
							$err_class_id = $this->getContext ()
								->getI18N ()
								->__ ( 'Unknown class id' ) . $ps_myclass_id . $this->getContext ()
								->getI18N ()
								->__ ( 'line' ) . $row . $this->getContext ()
								->getI18N ()
								->__ ( 'Of file' ) . $file->getOriginalName ();
							break;
						}
					}
				}

				if ($err_class != 1) {

					$name_class = array ();

					$er_number = 0;
					$error_date = array ();

					for($row = 3; $row <= $highestRow; $row ++) {

						$start_at = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 0, $row )->getCalculatedValue ());
						
						if ($start_at != '') {
							$name_class = array ();
						}
						
						// Neu de dinh dang là date
						if(is_numeric ($start_at)){
							$start_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($start_at));
						}else{ // Neu de dinh dang la text
							$start_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $start_at ) ) ); // chuyển định dạng
						}
						
						$end_at = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue ());
						// Neu de dinh dang là date
						if(is_numeric ($end_at)){
							$end_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($end_at));
						}else{ // Neu de dinh dang la text
							$end_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $end_at ) ) ); // chuyển định dạng
						}
						
						$start_time1 = $provinceSheet->getCellByColumnAndRow ( 2, $row );
						$start_time = PHPExcel_Style_NumberFormat::toFormattedString ( $start_time1->getCalculatedValue (), 'hh:mm:ss' );
						$end_time1 = $provinceSheet->getCellByColumnAndRow ( 3, $row );
						$end_time = PHPExcel_Style_NumberFormat::toFormattedString ( $end_time1->getCalculatedValue (), 'hh:mm:ss' );
						$is_saturday = $provinceSheet->getCellByColumnAndRow ( 4, $row )
						->getCalculatedValue ();
						$is_sunday = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row )
						->getCalculatedValue ());
						$note = $provinceSheet->getCellByColumnAndRow ( 6, $row )
						->getCalculatedValue ();
						$ps_myclass_id = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 8, $row )
						->getCalculatedValue ());
						$content_fb = $provinceSheet->getCellByColumnAndRow ( 9, $row )
						->getCalculatedValue ();
						$address = $provinceSheet->getCellByColumnAndRow ( 10, $row )
						->getCalculatedValue ();

						if ($start_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
							$check_start = true;
						} else {
							$check_start = false;
						}
						if ($end_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
							$check_end = true;
						} else {
							$check_end = false;
						}

						$check_time_st = $this->verifyTime ( $start_time );
						$check_time_sn = $this->verifyTime ( $end_time );

						if (isset ( $array_class_obj [$ps_myclass_id] )) {
							array_push ( $name_class, $array_class_obj [$ps_myclass_id] );
						}

						if ($start_at != '') {
							if ($check_start == true && $check_end == true && $check_time_st == true && $check_time_sn == true) {

								$name_str = implode ( ' , ', $name_class );
								$feature_branch_time = new FeatureBranchTimes ();

								$feature_branch_time->setPsFeatureBranchId ( $feature_branch );
								$feature_branch_time->setPsClassRoomId ( $class_room_id );
								$feature_branch_time->setStartAt ( $start_date );
								$feature_branch_time->setEndAt ( $end_date );
								$feature_branch_time->setStartTime ( $start_time );
								$feature_branch_time->setEndTime ( $end_time );
								$feature_branch_time->setIsSaturday ( ( int ) $is_saturday );
								$feature_branch_time->setIsSunday ( ( int ) $is_sunday );
								$feature_branch_time->setNote ( $note );
								$feature_branch_time->setNoteClassName ( $name_str );

								$feature_branch_time->setUserCreatedId ( $user_id );
								$feature_branch_time->setUserUpdatedId ( $user_id );

								$feature_branch_time->save ();

								$feature_branch_id = $feature_branch_time->getId ();
							} else {
								$er_number ++;
								array_push ( $error_date, $row );
								$feature_branch_id = '';
							}
						} else {
							if ($feature_branch_id != '') {
								$name_str = implode ( ',', $name_class );
								$feature_branch_time = Doctrine::getTable ( 'FeatureBranchTimes' )->getFeatureBranchTimesByField ( $feature_branch_id ,'note_class_name');
								$feature_branch_time->setNoteClassName ( $name_str );
								$feature_branch_time->save ();
							}
						}

						if ($feature_branch_id != '' && $ps_myclass_id != '') {

							$ps_feature_branch_time_my_class = new PsFeatureBranchTimeMyClass ();
							$ps_feature_branch_time_my_class->setPsFeatureBranchTimeId ( $feature_branch_id );
							$ps_feature_branch_time_my_class->setPsMyclassId ( $ps_myclass_id );
							$ps_feature_branch_time_my_class->setNote ( $content_fb );
							$ps_feature_branch_time_my_class->setPsClassRoom ( $address );
							$ps_feature_branch_time_my_class->setUserCreatedId ( $user_id );
							$ps_feature_branch_time_my_class->setUserUpdatedId ( $user_id );
							$ps_feature_branch_time_my_class->save ();
						}
					}
					// luu lich su import file lich hoat dong
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$this->getUser ()
						->setFlash ( 'error', $err_class_id );
					$this->redirect ( '@ps_feature_branch_import' );
				}
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_feature_branch_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			unlink ( $path_file . $filename );
			$conn->rollback ();
			if ($err_class == 1) {
				$this->getUser ()
					->setFlash ( 'error', $err_class_id );
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
			}

			$this->redirect ( '@ps_feature_branch_import' );
		}

		if ($er_number > 0) {
			$line_str = $er_number . $this->getContext ()
				->getI18N ()
				->__ ( 'Error date line' ) . implode ( ' , ', $error_date );
			$this->getUser ()
				->setFlash ( 'error', $line_str );
		} else {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully. You can add another one below.' );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		}

		$this->redirect ( '@ps_feature_branch_import' );
	}
}
