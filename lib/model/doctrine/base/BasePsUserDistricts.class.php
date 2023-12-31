<?php

/**
 * BasePsUserDistricts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $ps_district_id
 * @property integer $user_created_id
 * @property sfGuardUser $Users
 * @property PsDistrict $PsDistricts
 * @property sfGuardUser $UserCreated
 * 
 * @method integer         getUserId()          Returns the current record's "user_id" value
 * @method integer         getPsDistrictId()    Returns the current record's "ps_district_id" value
 * @method integer         getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method sfGuardUser     getUsers()           Returns the current record's "Users" value
 * @method PsDistrict      getPsDistricts()     Returns the current record's "PsDistricts" value
 * @method sfGuardUser     getUserCreated()     Returns the current record's "UserCreated" value
 * @method PsUserDistricts setUserId()          Sets the current record's "user_id" value
 * @method PsUserDistricts setPsDistrictId()    Sets the current record's "ps_district_id" value
 * @method PsUserDistricts setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsUserDistricts setUsers()           Sets the current record's "Users" value
 * @method PsUserDistricts setPsDistricts()     Sets the current record's "PsDistricts" value
 * @method PsUserDistricts setUserCreated()     Sets the current record's "UserCreated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsUserDistricts extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_user_districts');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_district_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('symfony', array(
             'form' => false,
             'filter' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as Users', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('PsDistrict as PsDistricts', array(
             'local' => 'ps_district_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}