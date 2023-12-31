<?php

/**
 * BaseFeatureOptionSubject
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $type
 * @property integer $order_by
 * @property integer $feature_option_id
 * @property integer $ps_service_id
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property StudentServiceCourseComment $StudentServiceCourseComment
 * @property FeatureOption $FeatureOption
 * @property Service $Service
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer                     getType()                        Returns the current record's "type" value
 * @method integer                     getOrderBy()                     Returns the current record's "order_by" value
 * @method integer                     getFeatureOptionId()             Returns the current record's "feature_option_id" value
 * @method integer                     getPsServiceId()                 Returns the current record's "ps_service_id" value
 * @method integer                     getUserCreatedId()               Returns the current record's "user_created_id" value
 * @method integer                     getUserUpdatedId()               Returns the current record's "user_updated_id" value
 * @method StudentServiceCourseComment getStudentServiceCourseComment() Returns the current record's "StudentServiceCourseComment" value
 * @method FeatureOption               getFeatureOption()               Returns the current record's "FeatureOption" value
 * @method Service                     getService()                     Returns the current record's "Service" value
 * @method sfGuardUser                 getUserCreated()                 Returns the current record's "UserCreated" value
 * @method sfGuardUser                 getUserUpdated()                 Returns the current record's "UserUpdated" value
 * @method FeatureOptionSubject        setType()                        Sets the current record's "type" value
 * @method FeatureOptionSubject        setOrderBy()                     Sets the current record's "order_by" value
 * @method FeatureOptionSubject        setFeatureOptionId()             Sets the current record's "feature_option_id" value
 * @method FeatureOptionSubject        setPsServiceId()                 Sets the current record's "ps_service_id" value
 * @method FeatureOptionSubject        setUserCreatedId()               Sets the current record's "user_created_id" value
 * @method FeatureOptionSubject        setUserUpdatedId()               Sets the current record's "user_updated_id" value
 * @method FeatureOptionSubject        setStudentServiceCourseComment() Sets the current record's "StudentServiceCourseComment" value
 * @method FeatureOptionSubject        setFeatureOption()               Sets the current record's "FeatureOption" value
 * @method FeatureOptionSubject        setService()                     Sets the current record's "Service" value
 * @method FeatureOptionSubject        setUserCreated()                 Sets the current record's "UserCreated" value
 * @method FeatureOptionSubject        setUserUpdated()                 Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFeatureOptionSubject extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('feature_option_subject');
        $this->hasColumn('type', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('order_by', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('feature_option_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ps_service_id', 'integer', null, array(
             'type' => 'integer',
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
        $this->hasOne('StudentServiceCourseComment', array(
             'local' => 'id',
             'foreign' => 'feature_option_subject_id'));

        $this->hasOne('FeatureOption', array(
             'local' => 'feature_option_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Service', array(
             'local' => 'ps_service_id',
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