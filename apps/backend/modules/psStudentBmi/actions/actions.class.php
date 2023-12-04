<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentBmiGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentBmiGeneratorHelper.class.php';

/**
 * psStudentBmi actions.
 *
 * @package kidsschool.vn
 * @subpackage psStudentBmi
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentBmiActions extends autoPsStudentBmiActions {

	public function executeExportStudent(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$class_id = null;
		
		$ps_school_year_id = null;
		
		if ($request->isMethod ( 'post' )) {
			
			$value_student_filter = $request->getParameter ( 'export_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$ps_month = $value_student_filter ['ps_month'];
			
			$class_id = $value_student_filter ['class_id'];
			
			$this->exportReportFeeReceiptStudent ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month );
		}
		
		$this->ps_month = isset ( $value_student_filter ['ps_month'] ) ? $value_student_filter ['ps_month'] : date ( "m-Y" );
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}
		
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		
		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) 
				) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) 
		), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) 
		) ) );
		
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->ps_month );
		
		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true 
			) ) );
		} else {
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true 
			) ) );
		}
		
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId ();
		
		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => true 
		), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true 
		) ) );
		
		if ($this->ps_customer_id > 0) {
			
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true 
			) ) );
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE 
					) ),
					'add_empty' => _ ( '-Select class-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false 
			) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		// echo $this->ps_workplace_id;
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}

	protected function exportReportFeeReceiptStudent($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );
		
		$file_template_pb = 'bm_dulieuyte.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;
		$class_code = '';
		if ($class_id > 0) {
			
			$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $class_id, $ps_workplace_id );
			
			$class_name = Doctrine::getTable ( 'MyClass' )->getClassName ( $class_id );
			
			$class_code = $class_name->getCode () . '_';
			
			$title_class = $this->getContext ()->getI18N ()->__ ( 'List student class' ) . $class_name->getName () . ', ' . $this->getContext ()->getI18N ()->__ ( 'Month' ) . ' ' . $ps_month;
		} else {
			
			$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $class_id = null, $ps_workplace_id );
			
			$title_class = $this->getContext ()->getI18N ()->__ ( 'List student import growths' ) . ', ' . $this->getContext ()->getI18N ()->__ ( 'Month' ) . ' ' . $ps_month;
		}
		
		$students = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $ps_customer_id, $ps_workplace_id, $class_id, $ps_month )->execute ();
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$title_xls = "DuLieuYTe_" . date ( 'Ym', strtotime ( '01-' . $ps_month ) );
		
		$title_info = $title_class;
		
		$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_info, $title_xls );
		
		$exportFile->setDataExportFeeReceiptStudent ( $students );
		
		$exportFile->saveAsFile ( "DLYTe_" . $class_code . date ( 'Ym', strtotime ( '01-' . $ps_month ) ) . ".xls" );
	}

	public function executeImport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_receivable_id = null;
		
		$ps_school_year_id = null;
		
		$class_id = null;
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false 
			) ) );
		} else {
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true 
			) ) );
		}
		
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId ();
		
		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false 
		), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true 
		) ) );
		
		if ($this->ps_customer_id > 0) {
			
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false 
			) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
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
							'is_activated' => PreSchool::ACTIVE 
					) ),
					'add_empty' => _ ( '-Select class-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false 
			) ) );
			
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id 
					) ),
					'add_empty' => _ ( '-Select examination-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => true 
			) ) );
		} else {
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
			
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorPass () );
		}
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte 
		), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size 
				) ) 
		) ) );
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->setDefault ( 'examination_id', $this->examination_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
	}

	public function executeImportSave(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_receivable_id = null;
		
		$ps_school_year_id = null;
		
		$class_id = null;
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false 
			) ) );
		} else {
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true 
			) ) );
		}
		
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId ();
		
		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false 
		), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true 
		) ) );
		
		if ($this->ps_customer_id > 0) {
			
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false 
			) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) 
			) ) );
			
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
							'is_activated' => PreSchool::ACTIVE 
					) ),
					'add_empty' => _ ( '-Select class-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false 
			) ) );
			
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id 
					) ),
					'add_empty' => _ ( '-Select examination-' ) 
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsExamination',
					'required' => true 
			) ) );
		} else {
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
			
			$this->formFilter->setWidget ( 'examination_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) 
					) 
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select examination-' ) 
			) ) );
			
			$this->formFilter->setValidator ( 'examination_id', new sfValidatorPass () );
		}
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' 
		) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte 
		), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size 
				) ) 
		) ) );
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $this->class_id );
		
		$this->formFilter->setDefault ( 'examination_id', $this->examination_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		
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
		// id lop hoc
		$class_id = $this->formFilter->getValue ( 'class_id' );
		// id dot kham
		$examination_id = $this->formFilter->getValue ( 'examination_id' );
		
		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		
		$psExamination = Doctrine::getTable ( 'PsExamination' )->findOneBy ( 'id', $examination_id );
		if ($psExamination) {
			$input_date = $psExamination->getInputDateAt ();
		}
		
		$student_bmi = Doctrine::getTable ( 'PsStudentBmi' )->getStudentBmi ();
		
		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id, $ps_workplace_id, $class_id );
		
		$array_student = array ();
		
		$_array_student = array ();
		
		foreach ( $students as $student ) {
			array_push ( $array_student, $student->getStudentCode () );
			$_array_student [$student->getStudentCode ()] = $student->getId ();
		}
		
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			$txt_student_code_age_error = '';
			
			if ($this->formFilter->isValid ()) {
				
				$user_id = myUser::getUserId ();
				
				$file_classify = $this->getContext ()->getI18N ()->__ ( 'Student growth import' );
				
				$file = $this->formFilter->getValue ( 'ps_file' );
				
				$filename = time () . $file->getOriginalName ();
				
				$file_link = 'Receivable' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
				
				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
				
				$file->save ( $path_file . $filename );
				
				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );
				
				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu
				
				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet
				
				$array_error = array ();
				
				$false = 0;
				
				$true = 0;
				
				for($row = 5; $row <= $highestRow; $row ++) {
					
					$student_code = $provinceSheet->getCellByColumnAndRow ( 2, $row )->getValue ();
					
					$note = $provinceSheet->getCellByColumnAndRow ( 12, $row )->getValue (); // Ghi chu
					
					$str_number = strlen ( $note );
					
					if ($student_code != '') {
						
						if (in_array ( $student_code, $array_student ) && $str_number < 255) {
							
							$student_id = null;
							
							$student_id = $_array_student [$student_code];
							
							if ($student_id > 0) {
								
								// Kiem tra xem da ton tai hay chua
								$ps_student_growths = Doctrine::getTable ( 'PsStudentGrowths' )->getStudentGrowthsByStudentId ( $student_id, $examination_id );
								// Xoa du lieu y te
								if ($ps_student_growths) {
									$ps_student_growths->delete ();
								}
								
								$student = Doctrine::getTable ( 'Student' )->setSqlStudentById ( $student_id )->fetchOne ();
								
								$height = str_replace ( ",", ".", $provinceSheet->getCellByColumnAndRow ( 3, $row )->getCalculatedValue () );
								
								$weight = str_replace ( ",", ".", $provinceSheet->getCellByColumnAndRow ( 4, $row )->getCalculatedValue () );
								
								$index_tooth = $provinceSheet->getCellByColumnAndRow ( 5, $row )->getValue (); // răng - hàm - mặt
								
								$index_throat = $provinceSheet->getCellByColumnAndRow ( 6, $row )->getValue (); // Tai - mũi - họng
								
								$index_eye = $provinceSheet->getCellByColumnAndRow ( 7, $row )->getValue (); // Mắt
								
								$index_heart = $provinceSheet->getCellByColumnAndRow ( 8, $row )->getValue (); // Tim
								
								$index_lung = $provinceSheet->getCellByColumnAndRow ( 9, $row )->getValue (); // Phổi
								
								$index_skin = $provinceSheet->getCellByColumnAndRow ( 10, $row )->getValue (); // Da
								
								$month = PreSchool::getMonthYear ( $student->getBirthday (), $input_date );
								// Nếu quá 10 tuổi(120 tháng tuổi) thì báo lỗi
								$month_validate = 120;
								
								// Da sua 29/08/2019 Bo dieu kien kiem tra chieu cao
								if ($student && $month <= $month_validate && ($weight > 0 || $height > 0 || $index_tooth != '' || $index_throat != '' || $index_eye != '' || $index_heart != '' || $index_lung != '' || $index_skin != '')) {
									
									$true ++;
									
									$people_make = $provinceSheet->getCellByColumnAndRow ( 11, $row )->getValue (); // Nguoi kham
									
									$sex = ( int ) $student->getSex ();
									
									foreach ( $student_bmi as $data ) {
										
										$data_sex = ( int ) $data->getSex ();
										
										$data_month = ( int ) $data->getIsMonth ();
										
										if ($sex == $data_sex and $month >= $data_month) {
											// So sanh chieu cao voi bang chuan
											if ($height > 0) {
												if ($data->getMinHeight1 () > 0 && $height < $data->getMinHeight1 ()) {
													$index_height = - 2;
												} elseif ($height < $data->getMinHeight () && $height > $data->getMinHeight1 ()) {
													$index_height = - 1;
												} elseif ($height > $data->getMaxHeight ()) {
													$index_height = 1;
												} else {
													$index_height = 0;
												}
											} else {
												$height = null;
												$index_height = null;
											}
											// So sanh can nang voi bang chuan
											if ($weight > 0) {
												if ($data->getMinWeight1 () > 0 && $weight < $data->getMinWeight1 ()) {
													$index_weight = - 2;
												} elseif ($weight < $data->getMinWeight () && $weight > $data->getMinWeight1 ()) {
													$index_weight = - 1;
												} elseif ($weight > $data->getMaxWeight ()) {
													$index_weight = 1;
												} else {
													$index_weight = 0;
												}
											} else {
												$weight = null;
												$index_weight = null;
											}
											
											break;
										}
									}
									
									$ps_student_growths = new PsStudentGrowths ();
									
									$ps_student_growths->setStudentId ( $student_id );
									
									$ps_student_growths->setExaminationId ( $examination_id );
									
									$ps_student_growths->setHeight ( $height );
									
									$ps_student_growths->setWeight ( $weight );
									
									$ps_student_growths->setIndexHeight ( $index_height );
									
									$ps_student_growths->setIndexWeight ( $index_weight );
									
									$ps_student_growths->setIndexAge ( $month );
									
									$ps_student_growths->setIndexTooth ( $index_tooth );
									
									$ps_student_growths->setIndexThroat ( $index_throat );
									
									$ps_student_growths->setIndexEye ( $index_eye );
									
									$ps_student_growths->setIndexHeart ( $index_heart );
									
									$ps_student_growths->setIndexLung ( $index_lung );
									
									$ps_student_growths->setIndexSkin ( $index_skin );
									
									$ps_student_growths->setPeopleMake ( $people_make );
									
									$ps_student_growths->setNote ( $note );
									
									$ps_student_growths->setUserUpdatedId ( myUser::getUserId () );
									
									$ps_student_growths->setUserCreatedId ( myUser::getUserId () );
									
									$ps_student_growths->save ();
									
								} else {
									$false ++;
									array_push ( $array_error, $row );
									if ($month > 120) {
										$txt_student_code_age_error .= '; ' . $student->getStudentCode ();
									}
								}
							}
						} else {
							$false ++;
							array_push ( $array_error, $row );
						}
					}
				}
				
				if ($true > 0) {
					// luu lich su import file phieu ghi no
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
				}
				
				$error_line = implode ( ' ; ', $array_error );
			} else {
				$error_import = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' );
				$this->getUser ()->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_student_bmi_import' );
			}
			
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' ) . ": " . $e->getMessage ();
			$this->getUser ()->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_student_bmi_import' );
		}
		
		if ($false == 0) {
			$successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully %value% data.', array (
					'%value%' => $true 
			) );
			$this->getUser ()->setFlash ( 'notice', $successfully );
		} else {
			
			$successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully %value% data.', array (
					'%value%' => $true 
			) );
			
			$error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
			
			$error_array = $this->getContext ()->getI18N ()->__ ( 'Line' ) . $error_line;
			
			$this->getUser ()->setFlash ( 'notice', $successfully );
			
			if ($txt_student_code_age_error != '') {
				$txt_student_code_age_error = sprintf ( 'Student of code %s% students aged unusually large. Please check back.', $txt_student_code_age_error );
				
				// In tạm có thể phải sửa lại style
				$this->getUser ()->setFlash ( 'notice1', $error_number . " " . $txt_student_code_age_error );
			} else {
				$this->getUser ()->setFlash ( 'notice1', $error_number );
			}
			
			$this->getUser ()->setFlash ( 'notice2', $error_array );
		}
		
		$this->redirect ( '@ps_student_bmi_import' );
	}

}
