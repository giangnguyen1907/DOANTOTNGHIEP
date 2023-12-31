<?php

/**
 * BaseRecurrenceService
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $student_service_id
 * @property double $amount
 * @property timestamp $effectives_at
 * @property timestamp $expires_at
 * @property timestamp $recurrence_at
 * @property integer $user_created_id
 * @property StudentService $StudentService
 * @property sfGuardUser $UserCreated
 * 
 * @method integer           getStudentServiceId()   Returns the current record's "student_service_id" value
 * @method double            getAmount()             Returns the current record's "amount" value
 * @method timestamp         getEffectivesAt()       Returns the current record's "effectives_at" value
 * @method timestamp         getExpiresAt()          Returns the current record's "expires_at" value
 * @method timestamp         getRecurrenceAt()       Returns the current record's "recurrence_at" value
 * @method integer           getUserCreatedId()      Returns the current record's "user_created_id" value
 * @method StudentService    getStudentService()     Returns the current record's "StudentService" value
 * @method sfGuardUser       getUserCreated()        Returns the current record's "UserCreated" value
 * @method RecurrenceService setStudentServiceId()   Sets the current record's "student_service_id" value
 * @method RecurrenceService setAmount()             Sets the current record's "amount" value
 * @method RecurrenceService setEffectivesAt()       Sets the current record's "effectives_at" value
 * @method RecurrenceService setExpiresAt()          Sets the current record's "expires_at" value
 * @method RecurrenceService setRecurrenceAt()       Sets the current record's "recurrence_at" value
 * @method RecurrenceService setUserCreatedId()      Sets the current record's "user_created_id" value
 * @method RecurrenceService setStudentService()     Sets the current record's "StudentService" value
 * @method RecurrenceService setUserCreated()        Sets the current record's "UserCreated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseRecurrenceService extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('recurrence_service');
        $this->hasColumn('student_service_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('amount', 'double', null, array(
             'type' => 'double',
             ));
        $this->hasColumn('effectives_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('expires_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('recurrence_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('StudentService', array(
             'local' => 'student_service_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}