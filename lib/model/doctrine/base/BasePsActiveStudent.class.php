<?php

/**
 * BasePsActiveStudent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $ps_class_id
 * @property date $start_at
 * @property date $end_at
 * @property string $start_time
 * @property string $end_time
 * @property string $note
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * 
 * @method string          getPsClassId()       Returns the current record's "ps_class_id" value
 * @method date            getStartAt()         Returns the current record's "start_at" value
 * @method date            getEndAt()           Returns the current record's "end_at" value
 * @method string          getStartTime()       Returns the current record's "start_time" value
 * @method string          getEndTime()         Returns the current record's "end_time" value
 * @method string          getNote()            Returns the current record's "note" value
 * @method integer         getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer         getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsActiveStudent setPsClassId()       Sets the current record's "ps_class_id" value
 * @method PsActiveStudent setStartAt()         Sets the current record's "start_at" value
 * @method PsActiveStudent setEndAt()           Sets the current record's "end_at" value
 * @method PsActiveStudent setStartTime()       Sets the current record's "start_time" value
 * @method PsActiveStudent setEndTime()         Sets the current record's "end_time" value
 * @method PsActiveStudent setNote()            Sets the current record's "note" value
 * @method PsActiveStudent setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsActiveStudent setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsActiveStudent extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_active_student');
        
        $this->hasColumn('ps_class_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('start_at', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('end_at', 'date', null, array(
             'type' => 'date',
             'notnull' => false,
             ));
        $this->hasColumn('start_time', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('end_time', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('title', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             ));
        $this->hasColumn('note', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             ));
         $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8mb4_unicode_ci');
        $this->option('charset', 'utf8mb4');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));
        $this->hasOne('MyClass', array(
             'local' => 'ps_class_id',
             'foreign' => 'id'));
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}