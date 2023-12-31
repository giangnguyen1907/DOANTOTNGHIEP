<?php

/**
 * BaseReceivable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property integer $ps_school_year_id
 * @property string $title
 * @property double $amount
 * @property string $description
 * @property integer $iorder
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property ReceivableStudent $ReceivableStudent
 * @property ReceivableTemp $ReceivableTemp
 * @property PsCustomer $PsCustomer
 * @property PsWorkPlaces $PsWorkPlaces
 * @property PsSchoolYear $PsSchoolYear
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $ReceivableDetail
 * 
 * @method integer             getPsCustomerId()      Returns the current record's "ps_customer_id" value
 * @method integer             getPsWorkplaceId()     Returns the current record's "ps_workplace_id" value
 * @method integer             getPsSchoolYearId()    Returns the current record's "ps_school_year_id" value
 * @method string              getTitle()             Returns the current record's "title" value
 * @method double              getAmount()            Returns the current record's "amount" value
 * @method string              getDescription()       Returns the current record's "description" value
 * @method integer             getIorder()            Returns the current record's "iorder" value
 * @method boolean             getIsActivated()       Returns the current record's "is_activated" value
 * @method integer             getUserCreatedId()     Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()     Returns the current record's "user_updated_id" value
 * @method ReceivableStudent   getReceivableStudent() Returns the current record's "ReceivableStudent" value
 * @method ReceivableTemp      getReceivableTemp()    Returns the current record's "ReceivableTemp" value
 * @method PsCustomer          getPsCustomer()        Returns the current record's "PsCustomer" value
 * @method PsWorkPlaces        getPsWorkPlaces()      Returns the current record's "PsWorkPlaces" value
 * @method PsSchoolYear        getPsSchoolYear()      Returns the current record's "PsSchoolYear" value
 * @method sfGuardUser         getUserCreated()       Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()       Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getReceivableDetail()  Returns the current record's "ReceivableDetail" collection
 * @method Receivable          setPsCustomerId()      Sets the current record's "ps_customer_id" value
 * @method Receivable          setPsWorkplaceId()     Sets the current record's "ps_workplace_id" value
 * @method Receivable          setPsSchoolYearId()    Sets the current record's "ps_school_year_id" value
 * @method Receivable          setTitle()             Sets the current record's "title" value
 * @method Receivable          setAmount()            Sets the current record's "amount" value
 * @method Receivable          setDescription()       Sets the current record's "description" value
 * @method Receivable          setIorder()            Sets the current record's "iorder" value
 * @method Receivable          setIsActivated()       Sets the current record's "is_activated" value
 * @method Receivable          setUserCreatedId()     Sets the current record's "user_created_id" value
 * @method Receivable          setUserUpdatedId()     Sets the current record's "user_updated_id" value
 * @method Receivable          setReceivableStudent() Sets the current record's "ReceivableStudent" value
 * @method Receivable          setReceivableTemp()    Sets the current record's "ReceivableTemp" value
 * @method Receivable          setPsCustomer()        Sets the current record's "PsCustomer" value
 * @method Receivable          setPsWorkPlaces()      Sets the current record's "PsWorkPlaces" value
 * @method Receivable          setPsSchoolYear()      Sets the current record's "PsSchoolYear" value
 * @method Receivable          setUserCreated()       Sets the current record's "UserCreated" value
 * @method Receivable          setUserUpdated()       Sets the current record's "UserUpdated" value
 * @method Receivable          setReceivableDetail()  Sets the current record's "ReceivableDetail" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReceivable extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('receivable');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('ps_school_year_id', 'integer', 11, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 11,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('amount', 'double', null, array(
             'type' => 'double',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('description', 'string', 300, array(
             'type' => 'string',
             'length' => 300,
             ));
        $this->hasColumn('tk_no', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('tk_co', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('tk_mua', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
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

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ReceivableStudent', array(
             'local' => 'id',
             'foreign' => 'receivable_id'));

        $this->hasOne('ReceivableTemp', array(
             'local' => 'id',
             'foreign' => 'receivable_id'));

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
             'foreign' => 'id'));

        $this->hasOne('PsSchoolYear', array(
             'local' => 'ps_school_year_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('ReceivableDetail', array(
             'local' => 'id',
             'foreign' => 'receivable_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}