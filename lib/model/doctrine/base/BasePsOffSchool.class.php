<?php

/**
 * BasePsOffSchool
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property integer $ps_class_id
 * @property integer $user_id
 * @property integer $relative_id
 * @property integer $student_id
 * @property string $description
 * @property string $reason_illegal
 * @property integer $is_activated
 * @property datetime $date_at
 * @property date $from_date
 * @property date $to_date
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Student $Student
 * @property Relative $Relative
 * @property PsCustomer $PsCustomer
 * @property PsWorkPlaces $PsWorkPlaces
 * @property MyClass $MyClass
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property sfGuardUser $UserId
 * 
 * @method integer      getPsCustomerId()    Returns the current record's "ps_customer_id" value
 * @method integer      getPsWorkplaceId()   Returns the current record's "ps_workplace_id" value
 * @method integer      getPsClassId()       Returns the current record's "ps_class_id" value
 * @method integer      getUserId()          Returns the current record's "user_id" value
 * @method integer      getRelativeId()      Returns the current record's "relative_id" value
 * @method integer      getStudentId()       Returns the current record's "student_id" value
 * @method string       getDescription()     Returns the current record's "description" value
 * @method string       getReasonIllegal()   Returns the current record's "reason_illegal" value
 * @method integer      getIsActivated()     Returns the current record's "is_activated" value
 * @method datetime     getDateAt()          Returns the current record's "date_at" value
 * @method date         getFromDate()        Returns the current record's "from_date" value
 * @method date         getToDate()          Returns the current record's "to_date" value
 * @method integer      getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer      getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method Student      getStudent()         Returns the current record's "Student" value
 * @method Relative     getRelative()        Returns the current record's "Relative" value
 * @method PsCustomer   getPsCustomer()      Returns the current record's "PsCustomer" value
 * @method PsWorkPlaces getPsWorkPlaces()    Returns the current record's "PsWorkPlaces" value
 * @method MyClass      getMyClass()         Returns the current record's "MyClass" value
 * @method sfGuardUser  getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser  getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method sfGuardUser  getUserId()          Returns the current record's "UserId" value
 * @method PsOffSchool  setPsCustomerId()    Sets the current record's "ps_customer_id" value
 * @method PsOffSchool  setPsWorkplaceId()   Sets the current record's "ps_workplace_id" value
 * @method PsOffSchool  setPsClassId()       Sets the current record's "ps_class_id" value
 * @method PsOffSchool  setUserId()          Sets the current record's "user_id" value
 * @method PsOffSchool  setRelativeId()      Sets the current record's "relative_id" value
 * @method PsOffSchool  setStudentId()       Sets the current record's "student_id" value
 * @method PsOffSchool  setDescription()     Sets the current record's "description" value
 * @method PsOffSchool  setReasonIllegal()   Sets the current record's "reason_illegal" value
 * @method PsOffSchool  setIsActivated()     Sets the current record's "is_activated" value
 * @method PsOffSchool  setDateAt()          Sets the current record's "date_at" value
 * @method PsOffSchool  setFromDate()        Sets the current record's "from_date" value
 * @method PsOffSchool  setToDate()          Sets the current record's "to_date" value
 * @method PsOffSchool  setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsOffSchool  setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsOffSchool  setStudent()         Sets the current record's "Student" value
 * @method PsOffSchool  setRelative()        Sets the current record's "Relative" value
 * @method PsOffSchool  setPsCustomer()      Sets the current record's "PsCustomer" value
 * @method PsOffSchool  setPsWorkPlaces()    Sets the current record's "PsWorkPlaces" value
 * @method PsOffSchool  setMyClass()         Sets the current record's "MyClass" value
 * @method PsOffSchool  setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsOffSchool  setUserUpdated()     Sets the current record's "UserUpdated" value
 * @method PsOffSchool  setUserId()          Sets the current record's "UserId" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsOffSchool extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_off_school');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_class_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('relative_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('reason_illegal', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_activated', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('date_at', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('from_date', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('to_date', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
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

        $this->hasOne('Relative', array(
             'local' => 'relative_id',
             'foreign' => 'id'));

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
             'foreign' => 'id'));

        $this->hasOne('MyClass', array(
             'local' => 'ps_class_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserId', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}