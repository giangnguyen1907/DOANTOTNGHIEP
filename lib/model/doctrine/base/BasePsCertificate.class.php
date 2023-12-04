<?php

/**
 * BasePsCertificate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $description
 * @property integer $iorder
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method string        getTitle()           Returns the current record's "title" value
 * @method string        getDescription()     Returns the current record's "description" value
 * @method integer       getIorder()          Returns the current record's "iorder" value
 * @method boolean       getIsActivated()     Returns the current record's "is_activated" value
 * @method integer       getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer       getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method sfGuardUser   getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser   getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method PsCertificate setTitle()           Sets the current record's "title" value
 * @method PsCertificate setDescription()     Sets the current record's "description" value
 * @method PsCertificate setIorder()          Sets the current record's "iorder" value
 * @method PsCertificate setIsActivated()     Sets the current record's "is_activated" value
 * @method PsCertificate setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsCertificate setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsCertificate setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsCertificate setUserUpdated()     Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsCertificate extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_certificate');
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