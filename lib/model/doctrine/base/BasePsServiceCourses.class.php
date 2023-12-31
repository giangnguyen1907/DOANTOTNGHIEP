<?php

/**
 * BasePsServiceCourses
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_service_id
 * @property string $title
 * @property integer $ps_member_id
 * @property date $start_at
 * @property date $end_at
 * @property string $note
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Service $PsService
 * @property PsMember $PsMember
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsServiceCourseSchedules
 * @property Doctrine_Collection $StudentService
 * 
 * @method integer             getPsServiceId()              Returns the current record's "ps_service_id" value
 * @method string              getTitle()                    Returns the current record's "title" value
 * @method integer             getPsMemberId()               Returns the current record's "ps_member_id" value
 * @method date                getStartAt()                  Returns the current record's "start_at" value
 * @method date                getEndAt()                    Returns the current record's "end_at" value
 * @method string              getNote()                     Returns the current record's "note" value
 * @method boolean             getIsActivated()              Returns the current record's "is_activated" value
 * @method integer             getUserCreatedId()            Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()            Returns the current record's "user_updated_id" value
 * @method Service             getPsService()                Returns the current record's "PsService" value
 * @method PsMember            getPsMember()                 Returns the current record's "PsMember" value
 * @method sfGuardUser         getUserCreated()              Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()              Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsServiceCourseSchedules() Returns the current record's "PsServiceCourseSchedules" collection
 * @method Doctrine_Collection getStudentService()           Returns the current record's "StudentService" collection
 * @method PsServiceCourses    setPsServiceId()              Sets the current record's "ps_service_id" value
 * @method PsServiceCourses    setTitle()                    Sets the current record's "title" value
 * @method PsServiceCourses    setPsMemberId()               Sets the current record's "ps_member_id" value
 * @method PsServiceCourses    setStartAt()                  Sets the current record's "start_at" value
 * @method PsServiceCourses    setEndAt()                    Sets the current record's "end_at" value
 * @method PsServiceCourses    setNote()                     Sets the current record's "note" value
 * @method PsServiceCourses    setIsActivated()              Sets the current record's "is_activated" value
 * @method PsServiceCourses    setUserCreatedId()            Sets the current record's "user_created_id" value
 * @method PsServiceCourses    setUserUpdatedId()            Sets the current record's "user_updated_id" value
 * @method PsServiceCourses    setPsService()                Sets the current record's "PsService" value
 * @method PsServiceCourses    setPsMember()                 Sets the current record's "PsMember" value
 * @method PsServiceCourses    setUserCreated()              Sets the current record's "UserCreated" value
 * @method PsServiceCourses    setUserUpdated()              Sets the current record's "UserUpdated" value
 * @method PsServiceCourses    setPsServiceCourseSchedules() Sets the current record's "PsServiceCourseSchedules" collection
 * @method PsServiceCourses    setStudentService()           Sets the current record's "StudentService" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsServiceCourses extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_service_courses');
        $this->hasColumn('ps_service_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('ps_member_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('start_at', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('end_at', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             ));


        $this->index('title_idx', array(
             'fields' => 
             array(
              0 => 'title',
             ),
             ));
        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Service as PsService', array(
             'local' => 'ps_service_id',
             'foreign' => 'id'));

        $this->hasOne('PsMember', array(
             'local' => 'ps_member_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('PsServiceCourseSchedules', array(
             'local' => 'id',
             'foreign' => 'ps_service_course_id'));

        $this->hasMany('StudentService', array(
             'local' => 'id',
             'foreign' => 'ps_service_course_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}