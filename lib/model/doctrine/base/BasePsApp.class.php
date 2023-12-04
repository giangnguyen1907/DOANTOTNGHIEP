<?php

/**
 * BasePsApp
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_app_root
 * @property string $title
 * @property string $app_code
 * @property string $description
 * @property integer $iorder
 * @property boolean $is_system
 * @property integer $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsApp $PsApp
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsApps
 * @property Doctrine_Collection $PsAppRoots
 * 
 * @method integer             getPsAppRoot()       Returns the current record's "ps_app_root" value
 * @method string              getTitle()           Returns the current record's "title" value
 * @method string              getAppCode()         Returns the current record's "app_code" value
 * @method string              getDescription()     Returns the current record's "description" value
 * @method integer             getIorder()          Returns the current record's "iorder" value
 * @method boolean             getIsSystem()        Returns the current record's "is_system" value
 * @method integer             getIsActivated()     Returns the current record's "is_activated" value
 * @method integer             getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsApp               getPsApp()           Returns the current record's "PsApp" value
 * @method sfGuardUser         getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsApps()          Returns the current record's "PsApps" collection
 * @method Doctrine_Collection getPsAppRoots()      Returns the current record's "PsAppRoots" collection
 * @method PsApp               setPsAppRoot()       Sets the current record's "ps_app_root" value
 * @method PsApp               setTitle()           Sets the current record's "title" value
 * @method PsApp               setAppCode()         Sets the current record's "app_code" value
 * @method PsApp               setDescription()     Sets the current record's "description" value
 * @method PsApp               setIorder()          Sets the current record's "iorder" value
 * @method PsApp               setIsSystem()        Sets the current record's "is_system" value
 * @method PsApp               setIsActivated()     Sets the current record's "is_activated" value
 * @method PsApp               setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsApp               setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsApp               setPsApp()           Sets the current record's "PsApp" value
 * @method PsApp               setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsApp               setUserUpdated()     Sets the current record's "UserUpdated" value
 * @method PsApp               setPsApps()          Sets the current record's "PsApps" collection
 * @method PsApp               setPsAppRoots()      Sets the current record's "PsAppRoots" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsApp extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_app');
        $this->hasColumn('ps_app_root', 'integer', 11, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 11,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('app_code', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('iorder', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('is_system', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('is_activated', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'length' => 1,
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
        $this->hasOne('PsApp', array(
             'local' => 'ps_app_root',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('sfGuardPermission as PsApps', array(
             'local' => 'id',
             'foreign' => 'ps_app_id'));

        $this->hasMany('PsApp as PsAppRoots', array(
             'local' => 'id',
             'foreign' => 'ps_app_root'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}