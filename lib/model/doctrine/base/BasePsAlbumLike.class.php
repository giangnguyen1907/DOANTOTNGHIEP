<?php

/**
 * BasePsAlbumLike
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property integer $album_id
 * @property integer $relative_id
 * @property integer $number_like
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * 
 * @method integer     getPsCustomerId()    Returns the current record's "ps_customer_id" value
 * @method integer     getPsWorkplaceId()   Returns the current record's "ps_workplace_id" value
 * @method integer     getAlbumId()         Returns the current record's "album_id" value
 * @method integer     getRelativeId()      Returns the current record's "relative_id" value
 * @method integer     getNumberLike()      Returns the current record's "number_like" value
 * @method integer     getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer     getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method PsAlbumLike setPsCustomerId()    Sets the current record's "ps_customer_id" value
 * @method PsAlbumLike setPsWorkplaceId()   Sets the current record's "ps_workplace_id" value
 * @method PsAlbumLike setAlbumId()         Sets the current record's "album_id" value
 * @method PsAlbumLike setRelativeId()      Sets the current record's "relative_id" value
 * @method PsAlbumLike setNumberLike()      Sets the current record's "number_like" value
 * @method PsAlbumLike setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsAlbumLike setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsAlbumLike extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_album_like');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('album_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('relative_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('number_like', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
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
        $this->hasOne('Relative', array(
             'local' => 'relative_id',
             'foreign' => 'id'));

        $this->hasOne('PsAlbum', array(
             'local' => 'album_id',
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

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}