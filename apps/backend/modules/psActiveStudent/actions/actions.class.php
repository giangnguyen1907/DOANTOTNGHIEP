<?php
require_once dirname ( __FILE__ ) . '/../lib/psActiveStudentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psActiveStudentGeneratorHelper.class.php';

/**
 * psActiveStudent actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psActiveStudent
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psActiveStudentActions extends autopsActiveStudentActions {

    protected function verifyTime($date, $format = 'H:i') {

        $d = DateTime::createFromFormat ( $format, $date );
        return $d && $d->format ( $format ) == $date;
    }

    public function executeImport(sfWebRequest $request) {

        $this->form = $this->configuration->getForm ();

        $this->formFilter = new sfFormFilter ();

        $ps_customer_id = null;

        $ps_workplace_id = null;

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

        $this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

        $this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

        $this->formFilter->getWidgetSchema () ->setNameFormat ( 'import_filter[%s]' );

        if ($request->isMethod('post')) {
            

            $this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

            $import_filter_form = $request->getParameter ( 'import_filter' );

            $ps_customer_id = $import_filter_form['ps_customer_id'];

            $ps_workplace_id = $import_filter_form['ps_workplace_id'];

            if ($ps_customer_id <= 0) {
                $ps_customer_id = myUser::getPscustomerID ();
            }
            // lay ra tat ca cac lop theo nam hoc, truong va co so
            $myclass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
                    'ps_customer_id' => $ps_customer_id,
                    'ps_workplace_id' => $ps_workplace_id ) )
                ->execute ();

            $array_class = array ();
            $array_class_obj = array ();
            foreach ( $myclass as $class ) {

                array_push ( $array_class, $class->getId () );

                $array_class_obj [$class->getCode ()] = $class->getId ();
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

                    $file_link = 'ActiveStudent' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

                    $path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

                    $file->save ( $path_file . $filename );

                    $objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

                    $provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

                    $highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

                    $arr_class_exits = array ();

                    for($row = 3; $row <= $highestRow; $row ++) {
                        $ps_myclass_code = $provinceSheet->getCellByColumnAndRow ( 4, $row )
                            ->getValue ();
                        if ($ps_myclass_code != '') {

                            if (array_key_exists ( $ps_myclass_code, $array_class_obj )) {
                                $err_class = 0;
                                array_push ( $arr_class_exits, array (
                                        $ps_myclass_code => $array_class_obj [$ps_myclass_code] ) );
                                
                            } else {
                                $err_class = 1;
                                $err_class_id = $this->getContext ()
                                    ->getI18N ()
                                    ->__ ( 'Unknown class id' ) . $ps_myclass_code . $this->getContext ()
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
                            
                            // $end_at = PreString::trim ($provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue ());

                            // // Neu de dinh dang là date
                            // if(is_numeric ($end_at)){
                                // $end_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($end_at));
                            // }else{ // Neu de dinh dang la text
                                // $end_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $end_at ) ) ); // chuyển định dạng
                            // }
                            
                            $start_time1 = $provinceSheet->getCellByColumnAndRow ( 1, $row );
                            $start_time = PHPExcel_Style_NumberFormat::toFormattedString ( $start_time1->getCalculatedValue (), 'hh:mm' );
                            $end_time1 = $provinceSheet->getCellByColumnAndRow ( 2, $row );
                            $end_time = PHPExcel_Style_NumberFormat::toFormattedString ( $end_time1->getCalculatedValue (), 'hh:mm' );
                            $ps_myclass_code = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row )
                            ->getCalculatedValue ());
                            $title = $provinceSheet->getCellByColumnAndRow ( 4, $row )
                            ->getCalculatedValue ();
                            $note = $provinceSheet->getCellByColumnAndRow ( 5, $row )
                            ->getCalculatedValue ();
                            
                            //lấy ra id theo mã lớp
                            if (array_key_exists ( $ps_myclass_code, $array_class_obj )) {
                                $class_id = $array_class_obj[$ps_myclass_code];
                            }

                            if ($start_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
                                $check_start = true;
                            } else {
                                $check_start = false;
                            }
                            // if ($end_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
                                // $check_end = true;
                            // } else {
                                // $check_end = false;
                            // }

                            $check_time_st = $this->verifyTime ( $start_time );
                            $check_time_sn = $this->verifyTime ( $end_time );

                            if ($start_at != '') {
                                if ($check_start == true && $check_end == true && $check_time_st == true && $check_time_sn == true) {

                                    $ps_active_student = new PsActiveStudent ();
                                    $ps_active_student->setPsClassId ( $class_id );
                                    $ps_active_student->setStartAt ( $start_date );
                                    $ps_active_student->setEndAt ( $start_date );
                                    $ps_active_student->setStartTime ( $start_time );
                                    $ps_active_student->setEndTime ( $end_time );
									$ps_active_student->setTitle ( $title );
                                    $ps_active_student->setNote ( $note );
                                    $ps_active_student->setUserCreatedId ( $user_id );
                                    $ps_active_student->setUserUpdatedId ( $user_id );

                                    $ps_active_student->save ();

                                    $ps_active_student_id = $ps_active_student->getId ();
                                } else {
                                    $er_number ++;
                                    array_push ( $error_date, $row );
                                    $ps_active_student_id = '';
                                }
                            } else {
                                if ($ps_active_student_id != '') {
                                    $ps_active_student = Doctrine::getTable ( 'PsActiveStudent' )->getPsActiveStudentByField ( $ps_active_student_id ,'note');
                                    $ps_active_student->setTitle ( $title );
									$ps_active_student->setNote ( $note );
                                    $ps_active_student->save ();
                                }
                            }
                        }
                    } else {
                        unlink ( $path_file . $filename );
                        $this->getUser ()
                            ->setFlash ( 'error', $err_class_id );
                        $this->redirect ( '@ps_active_student_import' );
                    }
                } else {
                    $error_import = $this->getContext ()
                        ->getI18N ()
                        ->__ ( 'Import file failed.' );
                    $this->getUser ()
                        ->setFlash ( 'error', $error_import );
                    $this->redirect ( '@ps_active_student_import' );
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

                $this->redirect ( '@ps_active_student_import' );
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

            $this->redirect ( '@ps_active_student_import' );

        }
    }
}
