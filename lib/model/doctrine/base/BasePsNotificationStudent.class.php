<?php

/**
 * BasePsNotificationStudent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $note
 * @property string $screen_code
 * @property string $module_code
 * @property integer $item_id
 * @property boolean $is_read
 * @property integer $student_id
 * @property integer $user_id
 * @property integer $ps_customer_id
 * @property Student $PsStudents
 * @property sfGuardUser $Users
 * 
 * @method string                getTitle()          Returns the current record's "title" value
 * @method string                getNote()           Returns the current record's "note" value
 * @method string                getScreenCode()     Returns the current record's "screen_code" value
 * @method string                getModuleCode()     Returns the current record's "module_code" value
 * @method integer               getItemId()         Returns the current record's "item_id" value
 * @method boolean               getIsRead()         Returns the current record's "is_read" value
 * @method integer               getStudentId()      Returns the current record's "student_id" value
 * @method integer               getUserId()         Returns the current record's "user_id" value
 * @method integer               getPsCustomerId()   Returns the current record's "ps_customer_id" value
 * @method Student               getPsStudents()     Returns the current record's "PsStudents" value
 * @method sfGuardUser           getUsers()          Returns the current record's "Users" value
 * @method PsNotificationStudent setTitle()          Sets the current record's "title" value
 * @method PsNotificationStudent setNote()           Sets the current record's "note" value
 * @method PsNotificationStudent setScreenCode()     Sets the current record's "screen_code" value
 * @method PsNotificationStudent setModuleCode()     Sets the current record's "module_code" value
 * @method PsNotificationStudent setItemId()         Sets the current record's "item_id" value
 * @method PsNotificationStudent setIsRead()         Sets the current record's "is_read" value
 * @method PsNotificationStudent setStudentId()      Sets the current record's "student_id" value
 * @method PsNotificationStudent setUserId()         Sets the current record's "user_id" value
 * @method PsNotificationStudent setPsCustomerId()   Sets the current record's "ps_customer_id" value
 * @method PsNotificationStudent setPsStudents()     Sets the current record's "PsStudents" value
 * @method PsNotificationStudent setUsers()          Sets the current record's "Users" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsNotificationStudent extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_notification_student');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('screen_code', 'string', 50, array(
             'type' => 'string',
             'unique' => false,
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('module_code', 'string', 50, array(
             'type' => 'string',
             'unique' => false,
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('is_read', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));


        $this->index('title_idx', array(
             'fields' => 
             array(
              0 => 'title',
             ),
             ));
        $this->index('ps_customer_idx', array(
             'fields' => 
             array(
              0 => 'ps_customer_id',
             ),
             ));
        $this->index('screen_code_idx', array(
             'fields' => 
             array(
              0 => 'screen_code',
             ),
             ));
        $this->index('module_code_idx', array(
             'fields' => 
             array(
              0 => 'module_code',
             ),
             ));
        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8mb4_unicode_ci');
        $this->option('charset', 'utf8mb4');
        $this->option('symfony', array(
             'form' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Student as PsStudents', array(
             'local' => 'student_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as Users', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}