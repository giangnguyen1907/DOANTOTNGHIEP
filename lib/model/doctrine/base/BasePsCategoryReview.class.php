<?php

/**
 * BasePsCategoryReview
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property string $title
 * @property string $note
 * @property integer $status
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * 
 * @method integer          getPsCustomerId()    Returns the current record's "ps_customer_id" value
 * @method integer          getPsWorkplaceId()   Returns the current record's "ps_workplace_id" value
 * @method string           getTitle()           Returns the current record's "title" value
 * @method string           getNote()            Returns the current record's "note" value
 * @method integer          getStatus()          Returns the current record's "status" value
 * @method integer          getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer          getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsCategoryReview setPsCustomerId()    Sets the current record's "ps_customer_id" value
 * @method PsCategoryReview setPsWorkplaceId()   Sets the current record's "ps_workplace_id" value
 * @method PsCategoryReview setTitle()           Sets the current record's "title" value
 * @method PsCategoryReview setNote()            Sets the current record's "note" value
 * @method PsCategoryReview setStatus()          Sets the current record's "status" value
 * @method PsCategoryReview setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsCategoryReview setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsCategoryReview extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_category_review');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             ));
        $this->hasColumn('note', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             ));
        $this->hasColumn('status', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             'default' => 1,
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
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}