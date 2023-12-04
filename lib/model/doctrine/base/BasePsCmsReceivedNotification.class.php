<?php

/**
 * BasePsCmsReceivedNotification
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_cms_notification_id
 * @property string $title
 * @property integer $user_id
 * @property boolean $is_read
 * @property datetime $date_at
 * @property string $private_key
 * @property boolean $is_delete
 * @property integer $user_created_id
 * @property sfGuardUser $UserCreated
 * @property PsCmsNotifications $Notification
 * 
 * @method integer                   getPsCmsNotificationId()    Returns the current record's "ps_cms_notification_id" value
 * @method string                    getTitle()                  Returns the current record's "title" value
 * @method integer                   getUserId()                 Returns the current record's "user_id" value
 * @method boolean                   getIsRead()                 Returns the current record's "is_read" value
 * @method datetime                  getDateAt()                 Returns the current record's "date_at" value
 * @method string                    getPrivateKey()             Returns the current record's "private_key" value
 * @method boolean                   getIsDelete()               Returns the current record's "is_delete" value
 * @method integer                   getUserCreatedId()          Returns the current record's "user_created_id" value
 * @method sfGuardUser               getUserCreated()            Returns the current record's "UserCreated" value
 * @method PsCmsNotifications        getNotification()           Returns the current record's "Notification" value
 * @method PsCmsReceivedNotification setPsCmsNotificationId()    Sets the current record's "ps_cms_notification_id" value
 * @method PsCmsReceivedNotification setTitle()                  Sets the current record's "title" value
 * @method PsCmsReceivedNotification setUserId()                 Sets the current record's "user_id" value
 * @method PsCmsReceivedNotification setIsRead()                 Sets the current record's "is_read" value
 * @method PsCmsReceivedNotification setDateAt()                 Sets the current record's "date_at" value
 * @method PsCmsReceivedNotification setPrivateKey()             Sets the current record's "private_key" value
 * @method PsCmsReceivedNotification setIsDelete()               Sets the current record's "is_delete" value
 * @method PsCmsReceivedNotification setUserCreatedId()          Sets the current record's "user_created_id" value
 * @method PsCmsReceivedNotification setUserCreated()            Sets the current record's "UserCreated" value
 * @method PsCmsReceivedNotification setNotification()           Sets the current record's "Notification" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsCmsReceivedNotification extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_cms_received_notification');
        $this->hasColumn('ps_cms_notification_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 150,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('is_read', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('date_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('private_key', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('is_delete', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));


        $this->index('title_idx', array(
             'fields' => 
             array(
              0 => 'title',
             ),
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

        $this->hasOne('PsCmsNotifications as Notification', array(
             'local' => 'ps_cms_notification_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}