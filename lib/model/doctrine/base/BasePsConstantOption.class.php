<?php

/**
 * BasePsConstantOption
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $value
 * @property string $note
 * @property integer $ps_constant_id
 * @property integer $ps_customer_id
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsConstant $PsConstant
 * @property PsCustomer $PsCustomer
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method string           getValue()           Returns the current record's "value" value
 * @method string           getNote()            Returns the current record's "note" value
 * @method integer          getPsConstantId()    Returns the current record's "ps_constant_id" value
 * @method integer          getPsCustomerId()    Returns the current record's "ps_customer_id" value
 * @method integer          getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer          getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsConstant       getPsConstant()      Returns the current record's "PsConstant" value
 * @method PsCustomer       getPsCustomer()      Returns the current record's "PsCustomer" value
 * @method sfGuardUser      getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser      getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method PsConstantOption setValue()           Sets the current record's "value" value
 * @method PsConstantOption setNote()            Sets the current record's "note" value
 * @method PsConstantOption setPsConstantId()    Sets the current record's "ps_constant_id" value
 * @method PsConstantOption setPsCustomerId()    Sets the current record's "ps_customer_id" value
 * @method PsConstantOption setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsConstantOption setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsConstantOption setPsConstant()      Sets the current record's "PsConstant" value
 * @method PsConstantOption setPsCustomer()      Sets the current record's "PsCustomer" value
 * @method PsConstantOption setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsConstantOption setUserUpdated()     Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsConstantOption extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_constant_option');
        $this->hasColumn('value', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('note', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('ps_constant_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
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
        $this->hasOne('PsConstant', array(
             'local' => 'ps_constant_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

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