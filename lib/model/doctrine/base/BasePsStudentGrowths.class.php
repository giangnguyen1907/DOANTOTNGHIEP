<?php

/**
 * BasePsStudentGrowths
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $student_id
 * @property double $height
 * @property double $weight
 * @property integer $index_height
 * @property integer $index_weight
 * @property string $index_tooth
 * @property string $index_throat
 * @property string $index_eye
 * @property string $index_heart
 * @property string $index_lung
 * @property string $index_skin
 * @property integer $index_age
 * @property timestamp $date_push_notication
 * @property integer $number_push_notication
 * @property string $people_make
 * @property string $organization_make
 * @property integer $examination_id
 * @property string $note
 * @property integer $user_push_notication_id
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Student $Student
 * @property PsExamination $PsExamination
 * @property sfGuardUser $UserPushNotication
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer          getStudentId()               Returns the current record's "student_id" value
 * @method double           getHeight()                  Returns the current record's "height" value
 * @method double           getWeight()                  Returns the current record's "weight" value
 * @method integer          getIndexHeight()             Returns the current record's "index_height" value
 * @method integer          getIndexWeight()             Returns the current record's "index_weight" value
 * @method string           getIndexTooth()              Returns the current record's "index_tooth" value
 * @method string           getIndexThroat()             Returns the current record's "index_throat" value
 * @method string           getIndexEye()                Returns the current record's "index_eye" value
 * @method string           getIndexHeart()              Returns the current record's "index_heart" value
 * @method string           getIndexLung()               Returns the current record's "index_lung" value
 * @method string           getIndexSkin()               Returns the current record's "index_skin" value
 * @method integer          getIndexAge()                Returns the current record's "index_age" value
 * @method timestamp        getDatePushNotication()      Returns the current record's "date_push_notication" value
 * @method integer          getNumberPushNotication()    Returns the current record's "number_push_notication" value
 * @method string           getPeopleMake()              Returns the current record's "people_make" value
 * @method string           getOrganizationMake()        Returns the current record's "organization_make" value
 * @method integer          getExaminationId()           Returns the current record's "examination_id" value
 * @method string           getNote()                    Returns the current record's "note" value
 * @method integer          getUserPushNoticationId()    Returns the current record's "user_push_notication_id" value
 * @method integer          getUserCreatedId()           Returns the current record's "user_created_id" value
 * @method integer          getUserUpdatedId()           Returns the current record's "user_updated_id" value
 * @method Student          getStudent()                 Returns the current record's "Student" value
 * @method PsExamination    getPsExamination()           Returns the current record's "PsExamination" value
 * @method sfGuardUser      getUserPushNotication()      Returns the current record's "UserPushNotication" value
 * @method sfGuardUser      getUserCreated()             Returns the current record's "UserCreated" value
 * @method sfGuardUser      getUserUpdated()             Returns the current record's "UserUpdated" value
 * @method PsStudentGrowths setStudentId()               Sets the current record's "student_id" value
 * @method PsStudentGrowths setHeight()                  Sets the current record's "height" value
 * @method PsStudentGrowths setWeight()                  Sets the current record's "weight" value
 * @method PsStudentGrowths setIndexHeight()             Sets the current record's "index_height" value
 * @method PsStudentGrowths setIndexWeight()             Sets the current record's "index_weight" value
 * @method PsStudentGrowths setIndexTooth()              Sets the current record's "index_tooth" value
 * @method PsStudentGrowths setIndexThroat()             Sets the current record's "index_throat" value
 * @method PsStudentGrowths setIndexEye()                Sets the current record's "index_eye" value
 * @method PsStudentGrowths setIndexHeart()              Sets the current record's "index_heart" value
 * @method PsStudentGrowths setIndexLung()               Sets the current record's "index_lung" value
 * @method PsStudentGrowths setIndexSkin()               Sets the current record's "index_skin" value
 * @method PsStudentGrowths setIndexAge()                Sets the current record's "index_age" value
 * @method PsStudentGrowths setDatePushNotication()      Sets the current record's "date_push_notication" value
 * @method PsStudentGrowths setNumberPushNotication()    Sets the current record's "number_push_notication" value
 * @method PsStudentGrowths setPeopleMake()              Sets the current record's "people_make" value
 * @method PsStudentGrowths setOrganizationMake()        Sets the current record's "organization_make" value
 * @method PsStudentGrowths setExaminationId()           Sets the current record's "examination_id" value
 * @method PsStudentGrowths setNote()                    Sets the current record's "note" value
 * @method PsStudentGrowths setUserPushNoticationId()    Sets the current record's "user_push_notication_id" value
 * @method PsStudentGrowths setUserCreatedId()           Sets the current record's "user_created_id" value
 * @method PsStudentGrowths setUserUpdatedId()           Sets the current record's "user_updated_id" value
 * @method PsStudentGrowths setStudent()                 Sets the current record's "Student" value
 * @method PsStudentGrowths setPsExamination()           Sets the current record's "PsExamination" value
 * @method PsStudentGrowths setUserPushNotication()      Sets the current record's "UserPushNotication" value
 * @method PsStudentGrowths setUserCreated()             Sets the current record's "UserCreated" value
 * @method PsStudentGrowths setUserUpdated()             Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsStudentGrowths extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_student_growths');
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('height', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             ));
        $this->hasColumn('weight', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             ));
        $this->hasColumn('index_height', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 1,
             ));
        $this->hasColumn('index_weight', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 1,
             ));
        $this->hasColumn('index_tooth', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_throat', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_eye', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_heart', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_lung', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_skin', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('index_age', 'integer', 3, array(
             'type' => 'integer',
             'length' => 3,
             ));
        $this->hasColumn('date_push_notication', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => 'true)',
             ));
        $this->hasColumn('number_push_notication', 'integer', 3, array(
             'type' => 'integer',
             'default' => 0,
             'length' => 3,
             ));
        $this->hasColumn('people_make', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('organization_make', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('examination_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('user_push_notication_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             ));


        $this->index('date_at_idx', array(
             'fields' => 
             array(
              0 => 'student_id',
              1 => 'examination_id',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Student', array(
             'local' => 'student_id',
             'foreign' => 'id'));

        $this->hasOne('PsExamination', array(
             'local' => 'examination_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserPushNotication', array(
             'local' => 'user_push_notication_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}