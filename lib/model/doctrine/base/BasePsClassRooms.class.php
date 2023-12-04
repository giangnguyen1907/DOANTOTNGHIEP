<?php

/**
 * BasePsClassRooms
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_workplace_id
 * @property string $title
 * @property boolean $is_global
 * @property string $note
 * @property string $description
 * @property integer $iorder
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsWorkPlaces $PsWorkPlaces
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsClassRoomss
 * @property PsCamera $PsWorkPlacess
 * @property Doctrine_Collection $PsClassRoomses
 * 
 * @method integer             getPsWorkplaceId()   Returns the current record's "ps_workplace_id" value
 * @method string              getTitle()           Returns the current record's "title" value
 * @method boolean             getIsGlobal()        Returns the current record's "is_global" value
 * @method string              getNote()            Returns the current record's "note" value
 * @method string              getDescription()     Returns the current record's "description" value
 * @method integer             getIorder()          Returns the current record's "iorder" value
 * @method boolean             getIsActivated()     Returns the current record's "is_activated" value
 * @method integer             getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsWorkPlaces        getPsWorkPlaces()    Returns the current record's "PsWorkPlaces" value
 * @method sfGuardUser         getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsClassRoomss()   Returns the current record's "PsClassRoomss" collection
 * @method PsCamera            getPsWorkPlacess()   Returns the current record's "PsWorkPlacess" value
 * @method Doctrine_Collection getPsClassRoomses()  Returns the current record's "PsClassRoomses" collection
 * @method PsClassRooms        setPsWorkplaceId()   Sets the current record's "ps_workplace_id" value
 * @method PsClassRooms        setTitle()           Sets the current record's "title" value
 * @method PsClassRooms        setIsGlobal()        Sets the current record's "is_global" value
 * @method PsClassRooms        setNote()            Sets the current record's "note" value
 * @method PsClassRooms        setDescription()     Sets the current record's "description" value
 * @method PsClassRooms        setIorder()          Sets the current record's "iorder" value
 * @method PsClassRooms        setIsActivated()     Sets the current record's "is_activated" value
 * @method PsClassRooms        setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsClassRooms        setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsClassRooms        setPsWorkPlaces()    Sets the current record's "PsWorkPlaces" value
 * @method PsClassRooms        setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsClassRooms        setUserUpdated()     Sets the current record's "UserUpdated" value
 * @method PsClassRooms        setPsClassRoomss()   Sets the current record's "PsClassRoomss" collection
 * @method PsClassRooms        setPsWorkPlacess()   Sets the current record's "PsWorkPlacess" value
 * @method PsClassRooms        setPsClassRoomses()  Sets the current record's "PsClassRoomses" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsClassRooms extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_class_rooms');
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('is_global', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 2000, array(
             'type' => 'string',
             'length' => 2000,
             ));
        $this->hasColumn('iorder', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
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
        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('FeatureBranchTimes as PsClassRoomss', array(
             'local' => 'id',
             'foreign' => 'ps_class_room_id'));

        $this->hasOne('PsCamera as PsWorkPlacess', array(
             'local' => 'id',
             'foreign' => 'ps_class_room_id'));

        $this->hasMany('MyClass as PsClassRoomses', array(
             'local' => 'id',
             'foreign' => 'ps_class_room_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}