<?php

/**
 * BasePsTypeSchool
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $description
 * @property integer $iorder
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property PsCustomer $PsCustomers
 * 
 * @method string       getTitle()           Returns the current record's "title" value
 * @method string       getDescription()     Returns the current record's "description" value
 * @method integer      getIorder()          Returns the current record's "iorder" value
 * @method integer      getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer      getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method sfGuardUser  getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser  getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method PsCustomer   getPsCustomers()     Returns the current record's "PsCustomers" value
 * @method PsTypeSchool setTitle()           Sets the current record's "title" value
 * @method PsTypeSchool setDescription()     Sets the current record's "description" value
 * @method PsTypeSchool setIorder()          Sets the current record's "iorder" value
 * @method PsTypeSchool setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsTypeSchool setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsTypeSchool setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsTypeSchool setUserUpdated()     Sets the current record's "UserUpdated" value
 * @method PsTypeSchool setPsCustomers()     Sets the current record's "PsCustomers" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsTypeSchool extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_type_school');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('iorder', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
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
        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasOne('PsCustomer as PsCustomers', array(
             'local' => 'id',
             'foreign' => 'ps_typeschool_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}