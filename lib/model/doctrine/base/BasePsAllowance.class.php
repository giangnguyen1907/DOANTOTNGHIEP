<?php

/**
 * BasePsAllowance
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property string $title
 * @property double $allowance_value
 * @property boolean $is_activated
 * @property string $note
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsCustomer $PsCustomer
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsMemberAllowance
 * 
 * @method integer             getPsCustomerId()      Returns the current record's "ps_customer_id" value
 * @method string              getTitle()             Returns the current record's "title" value
 * @method double              getAllowanceValue()    Returns the current record's "allowance_value" value
 * @method boolean             getIsActivated()       Returns the current record's "is_activated" value
 * @method string              getNote()              Returns the current record's "note" value
 * @method integer             getUserCreatedId()     Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()     Returns the current record's "user_updated_id" value
 * @method PsCustomer          getPsCustomer()        Returns the current record's "PsCustomer" value
 * @method sfGuardUser         getUserCreated()       Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()       Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsMemberAllowance() Returns the current record's "PsMemberAllowance" collection
 * @method PsAllowance         setPsCustomerId()      Sets the current record's "ps_customer_id" value
 * @method PsAllowance         setTitle()             Sets the current record's "title" value
 * @method PsAllowance         setAllowanceValue()    Sets the current record's "allowance_value" value
 * @method PsAllowance         setIsActivated()       Sets the current record's "is_activated" value
 * @method PsAllowance         setNote()              Sets the current record's "note" value
 * @method PsAllowance         setUserCreatedId()     Sets the current record's "user_created_id" value
 * @method PsAllowance         setUserUpdatedId()     Sets the current record's "user_updated_id" value
 * @method PsAllowance         setPsCustomer()        Sets the current record's "PsCustomer" value
 * @method PsAllowance         setUserCreated()       Sets the current record's "UserCreated" value
 * @method PsAllowance         setUserUpdated()       Sets the current record's "UserUpdated" value
 * @method PsAllowance         setPsMemberAllowance() Sets the current record's "PsMemberAllowance" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsAllowance extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_allowance');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('allowance_value', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
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
        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('PsMemberAllowance', array(
             'local' => 'id',
             'foreign' => 'ps_allowance_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}