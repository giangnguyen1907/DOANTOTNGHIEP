<?php

/**
 * BaseServiceSplit
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $service_id
 * @property integer $count_value
 * @property integer $count_ceil
 * @property double $split_value
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Service $Service
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer      getServiceId()       Returns the current record's "service_id" value
 * @method integer      getCountValue()      Returns the current record's "count_value" value
 * @method integer      getCountCeil()       Returns the current record's "count_ceil" value
 * @method double       getSplitValue()      Returns the current record's "split_value" value
 * @method integer      getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer      getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method Service      getService()         Returns the current record's "Service" value
 * @method sfGuardUser  getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser  getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method ServiceSplit setServiceId()       Sets the current record's "service_id" value
 * @method ServiceSplit setCountValue()      Sets the current record's "count_value" value
 * @method ServiceSplit setCountCeil()       Sets the current record's "count_ceil" value
 * @method ServiceSplit setSplitValue()      Sets the current record's "split_value" value
 * @method ServiceSplit setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method ServiceSplit setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method ServiceSplit setService()         Sets the current record's "Service" value
 * @method ServiceSplit setUserCreated()     Sets the current record's "UserCreated" value
 * @method ServiceSplit setUserUpdated()     Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseServiceSplit extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('service_split');
        $this->hasColumn('service_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('count_value', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('count_ceil', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('split_value', 'double', null, array(
             'type' => 'double',
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
        $this->hasOne('Service', array(
             'local' => 'service_id',
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