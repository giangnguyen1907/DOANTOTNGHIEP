<?php

/**
 * BasePsHistoryLogin
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $user_type
 * @property datetime $login_at
 * @property string $ip_remote
 * 
 * @method integer        getUserId()    Returns the current record's "user_id" value
 * @method string         getUserType()  Returns the current record's "user_type" value
 * @method datetime       getLoginAt()   Returns the current record's "login_at" value
 * @method string         getIpRemote()  Returns the current record's "ip_remote" value
 * @method PsHistoryLogin setUserId()    Sets the current record's "user_id" value
 * @method PsHistoryLogin setUserType()  Sets the current record's "user_type" value
 * @method PsHistoryLogin setLoginAt()   Sets the current record's "login_at" value
 * @method PsHistoryLogin setIpRemote()  Sets the current record's "ip_remote" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsHistoryLogin extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_history_login');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_type', 'string', 1, array(
             'type' => 'string',
             'length' => 1,
             ));
        $this->hasColumn('login_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('ip_remote', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));


        $this->index('user_idx', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}