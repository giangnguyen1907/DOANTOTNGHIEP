<?php

require_once dirname(__FILE__).'/../lib/psMenusImportsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/psMenusImportsGeneratorHelper.class.php';

/**
 * psMenusImports actions.
 *
 * @package    KidsSchool.vn
 * @subpackage psMenusImports
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMenusImportsActions extends autoPsMenusImportsActions
{
    public function executeShow(sfWebRequest $request) {
        
        $this->formFilter = new sfFormFilter ();
        
        $this->ps_obj_group_id = $ps_obj_group_id = $request->getParameter ( 'o' );
        
        $this->ps_customer_id = $ps_customer_id= $request->getParameter ( 'c' );
        
        $this->ps_workplace_id = $ps_workplace_id = $request->getParameter ( 'wp' );
        
        $this->ps_week = $request->getParameter ( 'w' );
        
        $this->list_menu = null;
        
        $this->filter_list_student = array ();
        
        $feature_branch_times_filters = $request->getParameter ( 'feature_branch_times_filters' );
        
        if ($request->isMethod ( 'post' )) {
            
            // Handle the form submission
            $value_student_filter = $feature_branch_times_filters;
            
            $ps_customer_id = $value_student_filter ['ps_customer_id'];
            $this->ps_customer_id = $ps_customer_id;
            
            $ps_workplace_id = $value_student_filter ['ps_workplace_id'];
            $this->ps_workplace_id = $ps_workplace_id;
            
            $ps_obj_group_id = $value_student_filter ['ps_obj_group_id'];
            $this->ps_obj_group_id = $ps_obj_group_id;
            
            $this->ps_week = $value_student_filter ['ps_week'];
            
            $this->ps_year = $value_student_filter ['ps_year'];
            
            $weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );
            
            $weeks_form = $weeks [$this->ps_week - 1];
            
            $form_week_start = $weeks_form ['week_start'];
            $this->week_start = $form_week_start;
            
            $form_week_end = $weeks_form ['week_end'];
            $this->week_end = $form_week_end;
            
            $this->week_list = $weeks_form ['week_list'];
            
            $param = array(
                'ps_customer_id' => $ps_customer_id,
                'ps_workplace_id' => $ps_workplace_id,
                'is_activated' => PreSchool::ACTIVE
            );
            
            // Lay bua an cua co so
            $this->ps_meals = Doctrine::getTable('PsMeals')->setSQLByParams($param)->execute();
            // danh sach mon an
            $this->list_menu = Doctrine::getTable ( 'PsMenusImports' )->getListMenuWeek($form_week_start, $form_week_end, $ps_customer_id, $ps_obj_group_id, $ps_workplace_id);
            
        } else {
            
            if($this->ps_customer_id ==''){
                
                $this->ps_customer_id = myUser::getPscustomerID ();
                
                $member_id = myUser::getUser ()->getMemberId ();
                $this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
                
            }
            
            if($this->ps_week == ''){
                
                $this->ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
                
            }
            
            $ps_customer_id = $this->ps_customer_id;
            $ps_workplace_id = $this->ps_workplace_id;
            
            $this->ps_year = date ( 'Y' );
            
            $weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );
            
            $weeks_form = $weeks [$this->ps_week - 1];
            
            $form_week_start = $weeks_form ['week_start'];
            $this->week_start = $form_week_start;
            //echo $form_week_start;
            $form_week_end = $weeks_form ['week_end'];
            $this->week_end = $form_week_end;
            
            $this->week_list = $weeks_form ['week_list'];
            
            $this->ps_meals = $this->list_menu = array();
            
            /**/
            $param = array(
                'ps_customer_id' => $ps_customer_id,
                'ps_workplace_id' => $ps_workplace_id,
                'is_activated' => PreSchool::ACTIVE
            );
            
            // Lay bua an cua co so
            $this->ps_meals = Doctrine::getTable('PsMeals')->setSQLByParams($param)->execute();
            
            $this->list_menu = Doctrine::getTable ( 'PsMenusImports' )->getListMenuWeek($form_week_start, $form_week_end, $ps_customer_id, $ps_obj_group_id, $ps_workplace_id);
            
        }
        
        if ($feature_branch_times_filters) {
            
            $this->ps_workplace_id = isset ( $feature_branch_times_filters ['ps_workplace_id'] ) ? $feature_branch_times_filters ['ps_workplace_id'] : 0;
            
            $this->ps_obj_group_id = isset ( $feature_branch_times_filters ['ps_obj_group_id'] ) ? $feature_branch_times_filters ['ps_obj_group_id'] : 0;
            
            $this->ps_year = isset ( $feature_branch_times_filters ['ps_year'] ) ? $feature_branch_times_filters ['ps_year'] : date ( 'Y' );
            
            $this->ps_week = isset ( $feature_branch_times_filters ['ps_week'] ) ? $feature_branch_times_filters ['ps_week'] : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
            
            if ($this->ps_workplace_id > 0) {
                
                $this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );
                
                $ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );
                
                $this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
                
                $this->ps_customer_id = $ps_workplace->getPsCustomerId ();
            }
        }
        
        $years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );
        
        $this->formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
            'choices' => array_combine ( $years, $years ) ), array (
                'class' => 'select2',
                'style' => "min-width:80px; width:auto;",
                'data-placeholder' => _ ( '-Select year-' ) ) ) );
        
        $this->formFilter->setValidator ( 'ps_year', new sfValidatorPass ( array (
            'required' => true ) ) );
        
        if ($this->ps_year == '') {
            $this->ps_year = date ( 'Y' );
        }
        
        $weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );
        
        $this->formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
            'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
                'class' => 'select2',
                'style' => "min-width:250px;width:100%;",
                'data-placeholder' => _ ( '-Select week-' ) ) ) );
        
        $this->formFilter->setValidator ( 'ps_week', new sfValidatorPass ( array (
            'required' => true ) ) );
        
        // Get week in form
        $form_week_start = null;
        $form_week_end = null;
        $form_week_list = array ();
        
        $weeks_form = $weeks [$this->ps_week - 1];
        
        $form_week_start = $weeks_form ['week_start'];
        $this->week_start = $form_week_start;
        
        $form_week_end = $weeks_form ['week_end'];
        $this->week_end = $form_week_end;
        
        $this->week_list = $weeks_form ['week_list'];
        
        // $ps_week = $this->getDefault('ps_week');
        
        if ($this->ps_week == '') {
            $this->ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
        }
        
        if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' )) {
            
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
        
        $this->formFilter->setWidget ( 'ps_obj_group_id', new sfWidgetFormDoctrineChoice ( array (
            'model' => 'PsObjectGroups',
            
            'add_empty' => _ ( '-Select object group-' ) ), array (
                'class' => 'select2',
                'style' => "min-width:200px;width:100%;",
                'required' => false,
                'data-placeholder' => _ ( '-Select object group-' ) ) ) );
        
        $this->formFilter->setValidator ( 'ps_obj_group_id', new sfValidatorDoctrineChoice ( array (
            'model' => 'PsObjectGroups',
            'required' => false ) ) );
        
        $this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );
        
        $this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
        
        $this->formFilter->setDefault ( 'ps_obj_group_id', $this->ps_obj_group_id );
        
        $this->formFilter->setDefault ( 'ps_year', $this->ps_year );
        
        $this->formFilter->setDefault ( 'ps_week', $this->ps_week );
        
        $this->formFilter->getWidgetSchema ()
        ->setNameFormat ( 'feature_branch_times_filters[%s]' );
    }
    
    public function executeImport(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_object_group_id = null;
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' )) {
			
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
		
		$sql_query_psObjectGroups = Doctrine::getTable ( 'PsObjectGroups' )->setSQL ();
		
		$this->formFilter->setWidget ( 'ps_obj_group_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => $sql_query_psObjectGroups,
				'add_empty' => _ ( '-Select object group-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'required' => false,
				'data-placeholder' => _ ( '-Select object group-' )
		) ) );
		
		$this->formFilter->setValidator ( 'ps_obj_group_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'required' => false
		) ) );
		
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'ps_obj_group_id', $this->ps_obj_group_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
	}
	
	public function executeImportSave(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_object_group_id = null;
		
		$ps_file = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' )) {
			
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
		
		$sql_query_psObjectGroups = Doctrine::getTable ( 'PsObjectGroups' )->setSQL ();
		
		$this->formFilter->setWidget ( 'ps_obj_group_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => $sql_query_psObjectGroups,
				'add_empty' => _ ( '-Select object group-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'required' => false,
				'data-placeholder' => _ ( '-Select object group-' )
		) ) );
		
		$this->formFilter->setValidator ( 'ps_obj_group_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'required' => false
		) ) );
		
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'ps_obj_group_id', $this->ps_obj_group_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		/**
		 * * Import file excel **
		 */
		
		$import_filter_form = $request->getParameter ( 'import_filter' );
		
		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );
		
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		// Nhom tre
		$ps_obj_group_id = $this->formFilter->getValue ( 'ps_obj_group_id' );
		
		$ps_meals = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array('is_activated' => PreSchool::ACTIVE,'ps_customer_id' => $ps_customer_id, 'ps_workplace_id'=>$ps_workplace_id) )->execute ();
		
		$array_meals = array();
		foreach ($ps_meals as $meals){
			array_push($array_meals,$meals->getId());
		}
		
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			$txt_student_code_age_error = '';
			
			if ($this->formFilter->isValid ()) {
				
				$user_id = myUser::getUserId ();
				
				$file_classify = $this->getContext ()->getI18N ()->__ ( 'ps menus import' );
				
				$file = $this->formFilter->getValue ( 'ps_file' );
				
				$filename = time () . $file->getOriginalName ();
				
				$file_link = 'Menus' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
				
				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
				
				$file->save ( $path_file . $filename );
				
				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );
				
				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu
				
				$highestRow = $provinceSheet->getHighestRow (); // Lấy số hàng lớn nhất trong sheet
				
				$highestColumn = $provinceSheet->getHighestColumn (); // Lấy số cột lớn nhất trong sheet
				
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
				
				$true = 0;
				
				$array_id_meals = array ();
				// lay ra id bua an
				for($k = 1; $k < $highestColumnIndex; $k ++) {
					$start = 3;
					$id_meals = $provinceSheet->getCellByColumnAndRow ( $k, $start )->getCalculatedValue ();
					if (in_array($id_meals,$array_meals) || $id_meals == '') {
						array_push ( $array_id_meals, $id_meals );
					}
				}
				
				for($row = 4; $row <= $highestRow; $row ++) {
					
					$receiva = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 0, $row )
							->getCalculatedValue ());
					
					if(is_numeric ($receiva)){
						
						$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($receiva));
						
						if($receivable_date != '1970-01-01'){
							$date_receivable = true;
						}else {
							$date_receivable = false;
						}
					}else{ // Neu de dinh dang la text
						
						$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $receiva ) ) ); // chuyển định dạng
						
						if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
							$date_receivable = true;
						} else {
							$date_receivable = false;
						}
					}
					
					if($date_receivable){
						
						for($k = 1; $k < $highestColumnIndex; $k ++) {
							
							$i = $k-1;
							$meals_id = $array_id_meals[$i];
							
							if($meals_id > 0){
								
								$cacmonan = $provinceSheet->getCellByColumnAndRow ( $k, $row )->getCalculatedValue ();
								
								if($cacmonan != ''){
									
									$true ++;
									
									$psMenusImport = new PsMenusImports();
									
									$psMenusImport -> setPsCustomerId($ps_customer_id);
									
									$psMenusImport -> setPsWorkplaceId($ps_workplace_id);
									
									$psMenusImport -> setPsObjectGroupId($ps_obj_group_id);
									
									$psMenusImport -> setPsMealId($meals_id);
									
									$psMenusImport -> setDescription($cacmonan);
									
									$psMenusImport -> setDateAt($receivable_date);
									
									$psMenusImport -> setUserUpdatedId($user_id);
									
									$psMenusImport -> setUserCreatedId($user_id);
									
									$psMenusImport ->save();
									
								}
							}
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
			}else {
				
				$error_import = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' );
				$this->getUser ()->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_menus_import' );
			}
			
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' ) . ": " . $e->getMessage ();
			$this->getUser ()->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_menus_import' );
		}
		
		if($true > 0){
			$import_success = $this->getContext ()->getI18N ()->__ ( 'Import file success.' );
			$this->getUser ()->setFlash ( 'notice', $import_success );
		}else{
			$import_success = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' );
			$this->getUser ()->setFlash ( 'error', $import_success );
		}
		
		$this->redirect ( '@ps_menus_imports' );
		
	}
	
	public function executeExportTemp(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		if ($request->isMethod ( 'post' )) {
			
			$value_student_filter = $request->getParameter ( 'export_filter' );
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$this->exportMealsBySchool ($ps_customer_id, $ps_workplace_id);
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' )) {
			
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
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}
	
	protected function exportMealsBySchool ($ps_customer_id, $ps_workplace_id) {
		
		$exportFile = new ExportStudentLogtimesReportHelper ( $this );
		
		$file_template_pb = 'bm_thucdon.xls';
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;
		
		$class_code = '';
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$ps_meals = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( array('is_activated' => PreSchool::ACTIVE,'ps_customer_id' => $ps_customer_id, 'ps_workplace_id'=>$ps_workplace_id) )->execute ();
		
		$exportFile->setDataExportMeals ( $ps_meals );
		
		$exportFile->saveAsFile ( "ThucDon.xls" );
	}

	
	public function executeDelete(sfWebRequest $request)
	{
	    $request->checkCSRFProtection();
	    
	    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));
	    
	    $ps_menus_imports = $this->getRoute()->getObject();
	    
	    $ps_customer_id = $ps_menus_imports->getPsCustomerId();
	    $ps_workplace_id = $ps_menus_imports->getPsWorkplaceId();
	    $ps_obj_group_id = $ps_menus_imports->getPsObjectGroupId();
	    $week = date('W', strtotime($ps_menus_imports->getDateAt()));
	    
	    if ($this->getRoute()->getObject()->delete())
	    {
	        $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
	    }
	    
	    //$this->redirect(array('sf_route' => 'ps_menus_imports_by_week', 'sf_subject' => $ps_menus_imports));
	    $this->redirect('@ps_menus_imports_by_week?c='.$ps_customer_id.'&wp='.$ps_workplace_id.'&o='.$ps_obj_group_id.'&w='.$week);
	}
	
	/**/
	public function executeEdit(sfWebRequest $request) {
	    
	    if ($request->isXmlHttpRequest ()) {
	        
	        $this->ps_menus_imports = $this->getRoute()->getObject();
	        
	        //echo $this->ps_menus_imports->getId(); die;
	        
	        //$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_menus_imports, 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
	        
	        $this->form = $this->configuration->getForm($this->ps_menus_imports);
	        
	        // Lay danh sach lop theo: truong, nam hoc, co so dao tao, nhom tre
	        //$ps_customer_id = $this->ps_menus_imports->getPsCustomerId ();
	        
	        // Co so dao tao
	        //$ps_workplace_id = $this->ps_menus_imports->getPsWorkplaceId ();
	        
	        // Nhom tre
	        //$ps_obj_group_id = $this->ps_menus_imports->getPsObjGroupId ();
	        
	        $this->helper = new PsMenusImportsGeneratorHelper();
	        
	        return $this->renderPartial ( 'psMenusImports/formSuccess', array (
	            'ps_menus_imports' => $this->ps_menus_imports,
	            'form' => $this->form,
	            'configuration' => $this->configuration,
	            'helper' => $this->helper
	        ) );
	        
	    } else {
	        exit ( 0 );
	    }
	}
	
	public function executeNew(sfWebRequest $request)
	{
	    
	    $ps_obj_group_id = $request->getParameter ( 'o' );
	    
	    $ps_customer_id = $request->getParameter ( 'c' );
	    
	    $ps_workplace_id = $request->getParameter ( 'w' );
	    
	    $date_at = $request->getParameter ( 'date' );
	    
	    $ps_meals_id = $request->getParameter ( 'm' );
	    
	    $psMenusImports = new PsMenusImports();
	    
	    $psMenusImports -> setPsCustomerId( $ps_customer_id );
	    
	    $psMenusImports -> setPsWorkplaceId( $ps_workplace_id );
	    
	    $psMenusImports -> setPsObjectGroupId( $ps_obj_group_id );
	    
	    $psMenusImports -> setPsMealId( $ps_meals_id );
	    
	    $psMenusImports -> setDateAt( date('Y-m-d',$date_at) );
	    
	    $this->form = $this->configuration->getForm($psMenusImports);
	    
	    $this->helper = new psMenusImportsGeneratorHelper ();
	    
	    $this->ps_menus_imports = $this->form->getObject();
	    
	    return $this->renderPartial ( 'psMenusImports/formSuccess', array (
	        'ps_menus_imports' => $this->ps_menus_imports,
	        'form' => $this->form,
	        'configuration' => $this->configuration,
	        'helper' => $this->helper
	    ) );
	}
	
	protected function processForm(sfWebRequest $request, sfForm $form)
	{
	    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
	    if ($form->isValid())
	    {
	        $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
	        
	        try {
	            $description = $form->getValue ( 'description' );
	            if($description !=''){
	               $ps_menus_imports = $form->save();
	               $this->getUser()->setFlash('notice', $notice);
	               
	               //$this->redirect(array('sf_route' => 'ps_menus_imports_edit', 'sf_subject' => $ps_menus_imports));
	               $ps_customer_id = $ps_menus_imports->getPsCustomerId();
	               $ps_workplace_id = $ps_menus_imports->getPsWorkplaceId();
	               $ps_obj_group_id = $ps_menus_imports->getPsObjectGroupId();
	               $week = date('W', strtotime($ps_menus_imports->getDateAt()));
	               
	            }else{
	                
	                $this->getUser()->setFlash('warning', 'No save data because description not isset');
	                
	                //$this->redirect(array('sf_route' => 'ps_menus_imports_edit', 'sf_subject' => $ps_menus_imports));
	                $ps_customer_id = $form->getValue ( 'ps_customer_id' );
	                $ps_workplace_id = $form->getValue ( 'ps_workplace_id' );
	                $ps_obj_group_id = $form->getValue ( 'ps_object_group_id' );
	                $week = date('W', strtotime($form->getValue ( 'date_at' )));
	                
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
	        
	        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_menus_imports)));
	        /*
	        if ($request->hasParameter('_save_and_add'))
	        {
	            $this->getUser()->setFlash('notice', $notice.' You can add another one below.');
	            
	            $this->redirect('@ps_menus_imports_new');
	        }
	        else
	        {
	            $this->getUser()->setFlash('notice', $notice);
	            
	            //$this->redirect(array('sf_route' => 'ps_menus_imports_edit', 'sf_subject' => $ps_menus_imports));
	            $ps_customer_id = $ps_menus_imports->getPsCustomerId();
	            $ps_workplace_id = $ps_menus_imports->getPsWorkplaceId();
	            $ps_obj_group_id = $ps_menus_imports->getPsObjectGroupId();
	            $week = date('W', strtotime($ps_menus_imports->getDateAt()));
	            
	            //$this->redirect(array('sf_route' => 'ps_menus_imports_by_week', 'sf_subject' => $ps_menus_imports));
	            $this->redirect('@ps_menus_imports_by_week?c='.$ps_customer_id.'&wp='.$ps_workplace_id.'&o='.$ps_obj_group_id.'&w='.$week);
	        }
	        */
	        $this->redirect('@ps_menus_imports_by_week?c='.$ps_customer_id.'&wp='.$ps_workplace_id.'&o='.$ps_obj_group_id.'&w='.$week);
	    }
	    else
	    {
	        $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
	    }
	}
}
