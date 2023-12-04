<?php

/**
 * BasePsDepartment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property string $title
 * @property string $description
 * @property integer $iorder
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsCustomer $PsCustomer
 * @property PsWorkPlaces $PsWorkPlaces
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsMemberDepartments
 * 
 * @method integer             getPsCustomerId()        Returns the current record's "ps_customer_id" value
 * @method integer             getPsWorkplaceId()       Returns the current record's "ps_workplace_id" value
 * @method string              getTitle()               Returns the current record's "title" value
 * @method string              getDescription()         Returns the current record's "description" value
 * @method integer             getIorder()              Returns the current record's "iorder" value
 * @method boolean             getIsActivated()         Returns the current record's "is_activated" value
 * @method integer             getUserCreatedId()       Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()       Returns the current record's "user_updated_id" value
 * @method PsCustomer          getPsCustomer()          Returns the current record's "PsCustomer" value
 * @method PsWorkPlaces        getPsWorkPlaces()        Returns the current record's "PsWorkPlaces" value
 * @method sfGuardUser         getUserCreated()         Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()         Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsMemberDepartments() Returns the current record's "PsMemberDepartments" collection
 * @method PsDepartment        setPsCustomerId()        Sets the current record's "ps_customer_id" value
 * @method PsDepartment        setPsWorkplaceId()       Sets the current record's "ps_workplace_id" value
 * @method PsDepartment        setTitle()               Sets the current record's "title" value
 * @method PsDepartment        setDescription()         Sets the current record's "description" value
 * @method PsDepartment        setIorder()              Sets the current record's "iorder" value
 * @method PsDepartment        setIsActivated()         Sets the current record's "is_activated" value
 * @method PsDepartment        setUserCreatedId()       Sets the current record's "user_created_id" value
 * @method PsDepartment        setUserUpdatedId()       Sets the current record's "user_updated_id" value
 * @method PsDepartment        setPsCustomer()          Sets the current record's "PsCustomer" value
 * @method PsDepartment        setPsWorkPlaces()        Sets the current record's "PsWorkPlaces" value
 * @method PsDepartment        setUserCreated()         Sets the current record's "UserCreated" value
 * @method PsDepartment        setUserUpdated()         Sets the current record's "UserUpdated" value
 * @method PsDepartment        setPsMemberDepartments() Sets the current record's "PsMemberDepartments" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsDepartment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_department');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
        $this->hasColumn('iorder', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
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
        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('PsMemberDepartments', array(
             'local' => 'id',
             'foreign' => 'ps_department_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}