<?php

/**
 * BasePsServiceSaturdayDate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_service_saturday_id
 * @property integer $student_id
 * @property timestamp $service_date
 * @property string $note
 * @property integer $is_status
 * @property string $feeback_note
 * @property timestamp $deleted_at
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsServiceSaturday $PsServiceSaturday
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer               getPsServiceSaturdayId()    Returns the current record's "ps_service_saturday_id" value
 * @method integer               getStudentId()              Returns the current record's "student_id" value
 * @method timestamp             getServiceDate()            Returns the current record's "service_date" value
 * @method string                getNote()                   Returns the current record's "note" value
 * @method integer               getIsStatus()               Returns the current record's "is_status" value
 * @method string                getFeebackNote()            Returns the current record's "feeback_note" value
 * @method timestamp             getDeletedAt()              Returns the current record's "deleted_at" value
 * @method integer               getUserCreatedId()          Returns the current record's "user_created_id" value
 * @method integer               getUserUpdatedId()          Returns the current record's "user_updated_id" value
 * @method PsServiceSaturday     getPsServiceSaturday()      Returns the current record's "PsServiceSaturday" value
 * @method sfGuardUser           getUserCreated()            Returns the current record's "UserCreated" value
 * @method sfGuardUser           getUserUpdated()            Returns the current record's "UserUpdated" value
 * @method PsServiceSaturdayDate setPsServiceSaturdayId()    Sets the current record's "ps_service_saturday_id" value
 * @method PsServiceSaturdayDate setStudentId()              Sets the current record's "student_id" value
 * @method PsServiceSaturdayDate setServiceDate()            Sets the current record's "service_date" value
 * @method PsServiceSaturdayDate setNote()                   Sets the current record's "note" value
 * @method PsServiceSaturdayDate setIsStatus()               Sets the current record's "is_status" value
 * @method PsServiceSaturdayDate setFeebackNote()            Sets the current record's "feeback_note" value
 * @method PsServiceSaturdayDate setDeletedAt()              Sets the current record's "deleted_at" value
 * @method PsServiceSaturdayDate setUserCreatedId()          Sets the current record's "user_created_id" value
 * @method PsServiceSaturdayDate setUserUpdatedId()          Sets the current record's "user_updated_id" value
 * @method PsServiceSaturdayDate setPsServiceSaturday()      Sets the current record's "PsServiceSaturday" value
 * @method PsServiceSaturdayDate setUserCreated()            Sets the current record's "UserCreated" value
 * @method PsServiceSaturdayDate setUserUpdated()            Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsServiceSaturdayDate extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_service_saturday_date');
        $this->hasColumn('ps_service_saturday_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('service_date', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => 'true)',
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'default' => 'note',
             'length' => 255,
             ));
        $this->hasColumn('is_status', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('feeback_note', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('deleted_at', 'timestamp', null, array(
             'type' => 'timestamp',
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
              1 => 'service_date',
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
        $this->hasOne('PsServiceSaturday', array(
             'local' => 'ps_service_saturday_id',
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