<?php

/**
 * BasePsReduceYourself
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property string $title
 * @property integer $start
 * @property integer $stop
 * @property integer $level
 * @property integer $status
 * @property integer $discount
 * @property boolean $is_type
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * 
 * @method integer          get()                Returns the current record's "ps_customer_id" value
 * @method integer          get()                Returns the current record's "ps_workplace_id" value
 * @method string           get()                Returns the current record's "title" value
 * @method integer          get()                Returns the current record's "start" value
 * @method integer          get()                Returns the current record's "stop" value
 * @method integer          get()                Returns the current record's "level" value
 * @method integer          get()                Returns the current record's "status" value
 * @method integer          get()                Returns the current record's "discount" value
 * @method boolean          get()                Returns the current record's "is_type" value
 * @method integer          get()                Returns the current record's "user_created_id" value
 * @method integer          get()                Returns the current record's "user_updated_id" value
 * @method PsReduceYourself set()                Sets the current record's "ps_customer_id" value
 * @method PsReduceYourself set()                Sets the current record's "ps_workplace_id" value
 * @method PsReduceYourself set()                Sets the current record's "title" value
 * @method PsReduceYourself set()                Sets the current record's "start" value
 * @method PsReduceYourself set()                Sets the current record's "stop" value
 * @method PsReduceYourself set()                Sets the current record's "level" value
 * @method PsReduceYourself set()                Sets the current record's "status" value
 * @method PsReduceYourself set()                Sets the current record's "discount" value
 * @method PsReduceYourself set()                Sets the current record's "is_type" value
 * @method PsReduceYourself set()                Sets the current record's "user_created_id" value
 * @method PsReduceYourself set()                Sets the current record's "user_updated_id" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsReduceYourself extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_reduce_yourself');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('reduce_code', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('start', 'integer', null, array(
             'type' => 'integer',
             'default' => 1,
             ));
        $this->hasColumn('stop', 'integer', null, array(
             'type' => 'integer',
             'default' => 1,
             ));
        $this->hasColumn('level', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('status', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('discount', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('is_type', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('json_service', 'string', null, array(
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

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}