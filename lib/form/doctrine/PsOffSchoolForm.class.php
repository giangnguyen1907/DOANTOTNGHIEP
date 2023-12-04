<?php

/**
 * PsOffSchool form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class PsOffSchoolForm extends BasePsOffSchoolForm
{
    
    public function configure()
    {
        
        $student_id = $this->getObject()->getStudentId();
        
        //$this->addPsCustomerFormNotEdit('PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL');
        
        $this->setDefault('is_activated', PreSchool::NOT_ACTIVE);
        $school_year_id = '';
        
        $ps_customer_id = myUser::getPscustomerID ();
        
        $this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
        
        $this->setDefault('ps_customer_id', $ps_customer_id);
        
        if ($this->getObject()->isNew()) {
            
	        $ps_workplace_id = $this->getDefault('ps_workplace_id');
	        
	        if ($ps_customer_id > 0) {
	            
	            $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
	                'model' => "PsWorkplaces",
	                'query' => Doctrine::getTable('PsWorkplaces')->setSQLByCustomerId('id,title', $ps_customer_id),
	                'add_empty' => _('-Select basis enrollment-')
	            ), array(
	                'class' => 'select2',
	                'style' => "min-width:200px;",
	                'data-placeholder' => _('-Select workplaces-')
	            ));            
	            
	        } else {
	            
	            $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormSelect(array(
	                'choices' => array(
	                    '' => _('-Select workplaces-')
	                )
	            ), array(
	                'class' => 'select2',
	                'style' => "min-width:200px;",
	                'data-placeholder' => _('-Select workplaces-')
	            ));
	        }
	        
	        $this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
	            'required' => false,
	            'model' => 'PsWorkplaces',
	            'column' => 'id'
	        ));
	        
	        $param_class = array(
	        	'ps_school_year_id' => $school_year_id,
	            'ps_customer_id' => $ps_customer_id,
	            'ps_workplace_id' => $ps_workplace_id,
	        	'is_activated' =>PreSchool::ACTIVE
	        );        
	        
	        if ($ps_workplace_id > 0) {
	            
	            $this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
	                'model' => 'MyClass',
	                'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
	                'add_empty' => _('-Select class-'),
	            	'multiple' => true
	            ), array(
	                'class' => 'select2',
	                'style' => "min-width:150px;",
	                'data-placeholder' => _('-Select class-')
	            ));
	            
	            $this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
	                'required' => false,
	                'model' => 'MyClass',
	                'column' => 'id'
	            ));
	        } else {
	            $this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
	                'choices' => array(
	                    '' => _('-Select class-')
	                )
	            ), array(
	                'class' => 'select2',
	                'style' => "min-width:200px;",
	                'data-placeholder' => _('-Select class-')
	            ));
	            
	            $this->validatorSchema['ps_class_id'] = new sfValidatorPass(array(
	                'required' => false,
	            ));
	        }
	        
	        $ps_class_id = $this->getDefault('ps_class_id');
	        
	        if ($ps_class_id > 0) {
	        	//Student
	        	$this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
	        			'model' => 'Student',
	        			'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id, $this->getObject()->getCreatedAt()),
	        			'add_empty' => _('-Select students-')
	        	), array(
	        			'class' => 'select2 form-control',
	        			'style' => "min-width:200px;",
	        			'data-placeholder' => _('-Select students-')
	        	));
	        	
	        	$this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
	        			'required' => true,
	        			'model' => 'Student',
	        			'column' => 'id'
	        	));
	        	
	        	$student_id = $this->getDefault('student_id');
	        	
	        } else {
	        	//Student
	        	$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
	        			'choices' => array(
	        					'' => _('-Select students-')
	        			)
	        	), array(
	        			'class' => 'select2 form-control',
	        			'style' => "min-width:200px;",
	        			'data-placeholder' => _('-Select students-')
	        	));
	        	
	        	$this->validatorSchema['student_id'] = new sfValidatorPass(array(
	        			'required' => true
	        			
	        	));
	        }
	        
        } else {
        	/**/
        	$student = $this->getObject()->getStudent();
        	
        	$ps_customer_id = $student->getPsCustomerId();
        	
        	// Lay lop hoc cua hoc sinh tai thoi diem dang ky
        	$student_class = $student->getClassByDate(PsDateTime::psDatetoTime($this->getObject()->getCreatedAt()));
        	$ps_class_id = $student_class->getMyclassId();
        	
        	$this->setDefault('ps_workplace_id', $student_class->getPsWorkplaceId());
        	
        	$this->setDefault('ps_class_id', $ps_class_id);
        	
        	$this->setDefault('student_id', $student->getId());
        	
        	$reason_illegal = $this->getObject()->getIsActivated();
        	
        	$school_year_id = $student_class->getSchoolYearId();
        	
        	$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormInputHidden ();
        	
        	$this->widgetSchema ['ps_class_id'] = new sfWidgetFormInputHidden ();
        	
        }
        
        if ($ps_class_id > 0) {
        	//Student
        	$this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
        			'model' => 'Student',
        			'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id, $this->getObject()->getCreatedAt()),
        			'add_empty' => _('-Select students-')
        	), array(
        			'class' => 'select2 form-control',
        			'style' => "min-width:200px;",
        			'data-placeholder' => _('-Select students-')
        	));
        	
        	$this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
        			'required' => true,
        			'model' => 'Student',
        			'column' => 'id'
        	));
        	
        	$student_id = $this->getDefault('student_id');
        	
        } else {
        	//Student
        	$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
        			'choices' => array(
        					'' => _('-Select students-')
        			)
        	), array(
        			'class' => 'select2 form-control',
        			'style' => "min-width:200px;",
        			'data-placeholder' => _('-Select students-')
        	));
        	
        	$this->validatorSchema['student_id'] = new sfValidatorPass(array(
        			'required' => true
        			
        	));
        }
        
        //nguoi than
        if ($student_id > 0 ) {
            
            $this->widgetSchema['relative_id'] = new sfWidgetFormDoctrineChoice(array(
                'model' => 'RelativeStudent',
                'query' => Doctrine::getTable('RelativeStudent')->sqlFindByStudentId($student_id,$ps_customer_id),
                'add_empty' => _('-Relative student-')
            ), array(
                'class' => 'select2 form-control',
                'style' => "min-width:150px;",
                'data-placeholder' => _('-Relative students-')
            ));
            
            $this->validatorSchema['relative_id'] = new sfValidatorDoctrineChoice(array(
                'required' => true,
                'model' => 'RelativeStudent',
                'column' => 'relative_id'
            ));
            
        } else {
            $this->widgetSchema['relative_id'] = new sfWidgetFormChoice(array(
                'choices' => array(
                    '' => _('-Relative student-')
                )
            ), array(
                'class' => 'select2 form-control',
                'style' => "min-width:200px;",
                'data-placeholder' => _('-Relative students-')
            ));
            
            $this->validatorSchema['relative_id'] = new sfValidatorPass(array(
                'required' => true
                //'required' => false
            ));
        }
        
        $this->widgetSchema ['date_at'] = new psWidgetFormFilterInputDate ();
        
        $this->validatorSchema ['date_at'] = new sfValidatorDate ( array (
        		'required' => true,
        		'max' => date ( 'Y-m-d' ) ), array (
        				'invalid' => 'Invalid tracked at',
        				'max' => 'Date must be no larger than %max%' ) );
        
        $this->widgetSchema ['date_at']->setAttributes ( array (
        		'data-dateformat' => 'dd-mm-yyyy',
        		'placeholder' => 'dd-mm-yyyy',
        		'title' => 'Tracked at',
        		'required' => true ) );
        
        
        
        $this->widgetSchema['date'] = new sfWidgetFormDateRange(array(
            
            'from_date' => new psWidgetFormInputDate(array(), array('data-dateformat' => 'dd-mm-yyyy', 'placeholder' => sfContext::getInstance()->getI18n()->__('From date').'(dd-mm-yyyy)', 'required' => 'required')),
            
            'to_date' => new psWidgetFormInputDate(array(), array('data-dateformat' => 'dd-mm-yyyy', 'placeholder' => sfContext::getInstance()->getI18n()->__('To date').'(dd-mm-yyyy)', 'required' => 'required')),
            
            'template'  => '<div class="row"><div class="col-md-6">%from_date%</div><div class="col-md-6">%to_date%</div></div>'
        ));
        
        $this->validatorSchema ['date'] = new sfValidatorDateRange ( array (
            
        //             'required' 	=> true,
            'required' => true,
            
            'from_date' => new sfValidatorDate ( array ('required' => false)),
            
            'to_date' 	=> new sfValidatorDate ( array ('required' => false)),
            
        ),
            array ('invalid' => 'The from date ("%left_field%") must be before the to date ("%right_field%")')
            
        );
        
        $this->validatorSchema['from_date'] = new sfValidatorPass();
        $this->validatorSchema['to_date'] 	= new sfValidatorPass();
        
        unset($this['from_date'], $this['to_date']);
        
        $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array(
            'class' => 'form-control',
        	'maxlength' => 255,
        ));
        
        $this->widgetSchema['reason_illegal'] = new sfWidgetFormTextarea(array(), array(
            'class' => 'form-control',
        	'maxlength' => 255,
        ));
        
        $user_id = myUser::getUserId();
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
        $this->setDefault('user_id',$user_id);
        
        $this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
            'choices' => PreSchool::getOffSchoolStatus()
        ), array(
            'class' => 'radiobox'
        ));
        
        $this->widgetSchema['config_time'] = new sfWidgetFormInputHidden();
        
        //$config_time = $workplace ? $workplace->getConfigTimeReceiveValid():null;
        
        //$this->setDefault('config_time', $config_time);
        
        $this->validatorSchema['config_time'] = new sfValidatorString(array(
            'required' => false
        ));
        
        
        if($student_id > 0 && $this->getObject()->isNew()){
            
            $this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
            $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormInputHidden();
            $this->widgetSchema['ps_class_id'] = new sfWidgetFormInputHidden();
            $this->widgetSchema['student_id'] = new sfWidgetFormInputHidden();
            
            $this->setDefault('ps_customer_id', $this->getObject()->getPsCustomerId());
            $this->setDefault('ps_workplace_id', $this->getObject()->getPsWorkplaceId());
            $this->setDefault('ps_class_id', $this->getObject()->getPsClassId());
            $this->setDefault('student_id', $student_id);
            $ps_customer_id = $this->getObject()->getPsCustomerId();
            $ps_class_id = $this->getObject()->getPsClassId();
            
            /*
            if ($ps_class_id > 0) {
                //Student
                $this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'Student',
                    'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id),
                    'add_empty' => _('-Select students-')
                ), array(
                    'class' => 'form-control',
                    'style' => "width:100%;",
                    'data-placeholder' => _('-Select students-')
                ));
                
                $this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'Student',
                    'column' => 'id'
                ));
                
                $student_id = $this->getDefault('student_id');
                
            } else {
                //Student
                $this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
                    'choices' => array(
                        '' => _('-Select students-')
                    )
                ), array(
                    'class' => 'form-control',
                    'style' => "width:100%;",
                    'data-placeholder' => _('-Select students-')
                ));
                
                $this->validatorSchema['student_id'] = new sfValidatorPass(array(
                    'required' => true
                ));
                
            }
            */
            
            //nguoi than
            if ($student_id > 0 ) {
                
                $this->widgetSchema['relative_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'RelativeStudent',
                    'query' => Doctrine::getTable('RelativeStudent')->sqlFindByStudentId($student_id,$ps_customer_id),
                ), array(
                    'class' => 'select2 form-control',
                    'style' => "width:100%;",
                    'data-placeholder' => _('-Relative students-')
                ));
                
                $this->validatorSchema['relative_id'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'RelativeStudent',
                    'column' => 'relative_id'
                ));
                
            } else {
                $this->widgetSchema['relative_id'] = new sfWidgetFormChoice(array(
                    'choices' => array(
                        '' => _('-Relative student-')
                    )
                ), array(
                    'class' => 'select2 form-control',
                    'style' => "width:100%;",
                    'data-placeholder' => _('-Relative students-')
                ));
                
                $this->validatorSchema['relative_id'] = new sfValidatorPass(array(
                    'required' => true
                ));
            }
            
        }
        
        
        $this->addBootstrapForm();
        if (! myUser::credentialPsCustomers('PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL') || ! $this->isNew()) {
            $this->widgetSchema['ps_customer_id']->setAttributes(array(
                'class' => 'form-control'
            ));
        }
        
        $this->mergePostValidator ( new sfValidatorCallback ( array (
            'callback' => array (
                $this,
                'postValidateTimeReceive'
            )
        ) ) );
    }
    
    public function updateObject($values = null)
    {
        return parent::baseUpdateObject($values);
    }
    
    public function updateDefaultsFromObject()
    {
        parent::updateDefaultsFromObject();
        
        if (isset($this->widgetSchema['date']))
        {
            $this->setDefault('date', array("from" => $this->getObject()->getFromDate(), "to" => $this->getObject()->getToDate()));
        }
        
    }
    
    public function processValues($values)
    {
        $values = parent::processValues($values);
        
        $values['from_date'] 	= $values["date"]["from"];
        $values['to_date'] 		= $values["date"]["to"];
        return $values;
    }
    
    public function postValidateTimeReceive(sfValidatorCallback $validator, array $values) {
        
        $values = parent::processValues($values);
        
        $date_at = $values['date_at'];
        $from_date = $values["date"]["from"];
        $workplace_id = $values['ps_workplace_id'];
        
        //         $this->setDefault('ps_workplace_id', $values['ps_workplace_id']);
        //         $this->setDefault('ps_class_id', $values['ps_class_id']);
        //         $this->setDefault('student_id', $values['student_id']);
        //         $this->setDefault('relative_id', $values['relative_id']);
        
        $time_receive_valid = Doctrine::getTable('PsWorkPlaces')->findOneById($workplace_id);
        $config_time_receive_valid = $time_receive_valid ? $time_receive_valid->getConfigTimeReceiveValid():null;
        
        if(isset($config_time_receive_valid)) {
            
            //Chuyen ve kieu time va convert sang so hour
            $config_time_receive_valid = strtotime($config_time_receive_valid);
            $config_time = date('H', $config_time_receive_valid) + date('i', $config_time_receive_valid) / 60 + date('s', $config_time_receive_valid) / 3600 ;
            
            //Tinh toan chenh lech so hour giua date_at va from_date
            $from_date = strtotime($from_date);
            $date_at = strtotime($date_at);
            
            if(date('Y-m-d', $from_date) == date('Y-m-d', $date_at)) {
                
                if(date('H:i', $date_at) >= date('H:i',$config_time_receive_valid)) {
                    
                    $error_str = 'Date at need before config time receive valid in workplaces';
                    
                }
                
            } elseif(date('Y-m-d', $from_date) < date('Y-m-d', $date_at)) {
                
                $error_str = 'Please, check date at time';
                
            }
            
            if(isset($error_str) && $error_str != '') {
                
                $error = new sfValidatorError ( $validator, $error_str );
                
                throw new sfValidatorErrorSchema ( $validator, array (
                    "date_at" => $error
                ) );
            }
        }
        return $values;
    }
}

