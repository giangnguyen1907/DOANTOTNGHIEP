<?php

/**
 * PsReview form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReviewForm extends BasePsReviewForm
{
  public function configure()
  {
	$this->widgetSchema['date_at'] = new psWidgetFormInputDate();

    $this->widgetSchema['date_at']->setAttributes(array(

      'data-dateformat' => 'dd-mm-yyyy',

      'placeholder' => 'dd-mm-yyyy',

      'class' => 'datepicker',

      'style' =>'z-index:2 !important'

    ));
	
    $student_id = $this->getObject()->getStudentId();
        
    //$this->addPsCustomerFormNotEdit('PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL');
    
    $this->setDefault('is_activated', PreSchool::NOT_ACTIVE);

    $school_year_id = '';
    
    $ps_customer_id = myUser::getPscustomerID ();
    
    $this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
    
    $this->setDefault('ps_customer_id', $ps_customer_id);

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
		$this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'MyClass',
            'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
            'add_empty' => _('-Select class-'),
          // 'multiple' => true
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
		
        // $this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
            // 'choices' => array(
                // '' => _('-Select class-')
            // )
        // ), array(
            // 'class' => 'select2',
            // 'style' => "min-width:200px;",
            // 'data-placeholder' => _('-Select class-')
        // ));
        
        // $this->validatorSchema['ps_class_id'] = new sfValidatorPass(array(
            // 'required' => false,
        // ));
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
      $this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'Student',
          'query' => Doctrine::getTable('Student')->setSqlStudentById($student_id),
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
    }

    // if ($ps_class_id > 0) {
      // //Student
      // $this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
          // 'model' => 'Student',
          // 'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id, $this->getObject()->getCreatedAt()),
          // 'add_empty' => _('-Select students-')
      // ), array(
          // 'class' => 'select2 form-control',
          // 'style' => "min-width:200px;",
          // 'data-placeholder' => _('-Select students-')
      // ));
      
      // $this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
          // 'required' => true,
          // 'model' => 'Student',
          // 'column' => 'id'
      // ));
      
      // $student_id = $this->getDefault('student_id');
      
    // } else {
      // //Student
      // $this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
          // 'choices' => array(
              // '' => _('-Select students-')
          // )
      // ), array(
          // 'class' => 'select2 form-control',
          // 'style' => "min-width:200px;",
          // 'data-placeholder' => _('-Select students-')
      // ));
      
      // $this->validatorSchema['student_id'] = new sfValidatorPass(array(
          // 'required' => true
          
      // ));
    // }

    $this->widgetSchema['category_review_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsCategoryReview',
          'add_empty' => '-Chọn danh mục-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn danh mục-')
        ));

    $this->validatorSchema['category_review_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsCategoryReview',
      'required' => false
    ));

    $this->widgetSchema['review_relative_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsReviewRelative',
          'add_empty' => '-Chọn nhận xét-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn nhận xét-')
        ));

    $this->validatorSchema['review_relative_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsReviewRelative',
      'required' => false
    ));

    $this->widgetSchema['member_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsMember',
          'query' => Doctrine::getTable('PsMember')->setSQLByMember(),
          'add_empty' => '-Chọn giáo viên-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn giáo viên-')
        ));

    $this->validatorSchema['member_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsMember',
      'required' => false
    ));

    $this->widgetSchema['status'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsActivity()
    ), array(
        'class' => 'radiobox'
    ));

    $this->widgetSchema ['note'] = new sfWidgetFormTextarea ();

    $this->widgetSchema['note']->setAttributes(array(
        'class' => 'form-control',
		'rows'  => 4
    ));

    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      return $object;
  }
}
