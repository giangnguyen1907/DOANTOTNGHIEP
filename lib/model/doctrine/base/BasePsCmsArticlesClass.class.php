<?php

/**
 * BasePsCmsArticlesClass
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_class_id
 * @property integer $ps_article_id
 * @property integer $user_created_id
 * @property MyClass $MyClass
 * @property PsCmsArticles $PsCmsArticles
 * @property sfGuardUser $UserCreated
 * 
 * @method integer            getPsClassId()       Returns the current record's "ps_class_id" value
 * @method integer            getPsArticleId()     Returns the current record's "ps_article_id" value
 * @method integer            getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method MyClass            getMyClass()         Returns the current record's "MyClass" value
 * @method PsCmsArticles      getPsCmsArticles()   Returns the current record's "PsCmsArticles" value
 * @method sfGuardUser        getUserCreated()     Returns the current record's "UserCreated" value
 * @method PsCmsArticlesClass setPsClassId()       Sets the current record's "ps_class_id" value
 * @method PsCmsArticlesClass setPsArticleId()     Sets the current record's "ps_article_id" value
 * @method PsCmsArticlesClass setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsCmsArticlesClass setMyClass()         Sets the current record's "MyClass" value
 * @method PsCmsArticlesClass setPsCmsArticles()   Sets the current record's "PsCmsArticles" value
 * @method PsCmsArticlesClass setUserCreated()     Sets the current record's "UserCreated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsCmsArticlesClass extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_cms_articles_class');
        $this->hasColumn('ps_class_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_article_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8mb4_unicode_ci');
        $this->option('charset', 'utf8mb4');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('MyClass', array(
             'local' => 'ps_class_id',
             'foreign' => 'id'));

        $this->hasOne('PsCmsArticles', array(
             'local' => 'ps_article_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}